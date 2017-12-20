<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class payment_epurse extends CI_Controller
{
    public function __construct()
    {
		die;
        parent::__construct(true);
        $this->load->helper('language');
        $this->load->library('session_memcached');
        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->library('redis');
		$this->lang->load('megav_message');
		$this->load->library('megav_core_interface');
		$this->load->library('megav_libs');
		
		$this->session_memcached->get_userdata();
		if(!isset($this->session_memcached->userdata['info_user']['userID']))
		{
			//redirect();
			echo "<script>window.top.location='" . base_url() . "'</script>";
			die;
		}
	   
    }

    
	public function index()
	{
		$data = array();
		$post = $this->input->post();
		if (isset($_POST['paymentMappingStep1'])) {
			$this->form_validation->set_rules('bank_code', 'Ngân hàng', 'required|trim|xss_clean');
			$this->form_validation->set_rules('amount', 'Số tiền nạp', 'required|trim|xss_clean');
			$data['check_valid1'] = '1';
			if($this->form_validation->run() == true) {
				$data['step'] = '1';
				
				// gửi xác nhận
				$transId = "RP2" . date("Ymd") . rand();
				//$listBank = $this->megav_core_interface->getProvider($transId);
				//$listBank = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, DEPOSIT_IBK_TEMP);
				$accessToken = $this->megav_libs->genarateAccessToken();
				$listBankMap = $this->megav_core_interface->getListBanksAcountMapping($this->session_memcached->userdata['info_user']['userID'], $accessToken);
				foreach ($listBankMap as $key => $value) {
					if (isset($value->bankcode) && $value->bankcode == trim($post['bank_code'])) {
						$info_bank = $value;
					}
				}
				if (isset($info_bank->bankName)) {
					$data['bank_name'] = $info_bank->bankName;
				}
				$data['bank_code']    = trim($post['bank_code']);
				$data['amount']    = trim($post['amount']);
				if($post['feePaymentMap'] != "" )
					$data['feePaymentMap'] = trim($post['feePaymentMap']);
				else
					$data['feePaymentMap'] = $this->get_fee_payment_mapping_to_view($data['amount'], $data['bank_code']);


				if($this->session_memcached->userdata['info_user']['security_method'] == '1') {
					$requestSendOtp = $this->megav_core_interface->genOTP($this->session_memcached->userdata['info_user']['email'],$this->session_memcached->userdata['info_user']['mobileNo'], $this->session_memcached->userdata['info_user']['userID'], $transId);

					if($requestSendOtp){
						$response = json_decode($requestSendOtp);
						log_message('error', 'respone otp: ' . print_r($requestSendOtp, true));
						if(isset($response->status)){
							if($response->status == '00'){
								$data['trans_id']    = $transId;
								$data['mess_otp_success'] = 'Hệ thống đã gửi OTP tới số điện thoại '.substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 0, (strlen($this->session_memcached->userdata['info_user']['mobileNo']) - 4)).'.Không nhận được <a hre="javascript:void(0);" class="re-sendOTPMap" style="color: #2794cc; cursor: pointer;">Click gửi lại.</a>';
							}
						}
					}
				}
			}
		}else if (isset($_POST['paymentMappingStep3'])) {
			$this->form_validation->set_rules('otp', 'Mã xác thực', 'numeric|required|trim|xss_clean');
			$this->form_validation->set_rules('amount', 'Số tiền nạp', 'required|trim|xss_clean');
			$this->form_validation->set_rules('trans_id', 'Mã giao dịch', 'required|trim|xss_clean');
			$data['step'] = '3';
			$data['result_info'] = array(
				'requestId' => trim($this->input->post('trans_id')),
				'bankName' => trim($this->input->post('bank_name')),
				'realReceive' => trim($this->input->post('amount'))
			);
			if($this->form_validation->run() == true) {
				$data['step'] = '2';
				$amount = str_replace(',', '', trim($this->input->post('amount')));
				$accessToken = $this->megav_libs->genarateAccessToken();
				
				$merchantId = null;
				if(!empty($this->input->cookie("merchantId")))
					$merchantId = $this->input->cookie("merchantId");
				
				$requestExceedMapping =json_decode($this->megav_core_interface->exceedMappingOTPBank($this->session_memcached->userdata['info_user']['userID'],$amount,trim($this->input->post('trans_id')),trim($this->input->post('otp')), $accessToken, $merchantId));

				//$key3des = $this->megav_core_interface->getSessionKey();
				//$dataMappingResult = json_decode($this->megav_core_interface->decrypt3DES($requestExceedMapping->data, $key3des), true);

				//giả lập trạng thái trả về thành công
				//$dataMappingResult = '00';

				if ($requestExceedMapping->status=='00') {
					$data['balance'] = number_format($this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																											$this->megav_libs->genarateAccessToken()));
					$requestTransId = $this->megav_libs->genarateTransactionId('GLT');
					$result_info = $this->megav_core_interface->getTransList($this->session_memcached->userdata['info_user']['userID'], 1, trim($this->input->post('trans_id')), "","", "", "", 0, 0, 1, $requestTransId, 3);

					if (!empty($result_info)) {
						$data['result_info'] = array(
							'requestId' => $result_info->listTrans[0]->requestId,
							'bankName' => $result_info->listTrans[0]->bankName,
							'timeCreate' => $result_info->listTrans[0]->timeCreate,
							'feeAmount' => $result_info->listTrans[0]->feeAmount,
							'amount' => $result_info->listTrans[0]->amount,
							'realReceive' => $result_info->listTrans[0]->realReceive,
							'status' => ($result_info->listTrans[0]->status=='00') ? 'Thành công' : 'Thất bại',
						);
					}
					
					$transId = $this->megav_libs->genarateTransactionId('GLB');
					//$listBank = $this->megav_core_interface->getProvider($transId, SUB_DEPOSIT_BANK_REDIRECT);
					$listBank = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, DEPOSIT_IBK_TEMP);
					//log_message('error', 'List Bank: ' . print_r($listBank, true));
					if($listBank)
						$data['listBank'] = $listBank;
					
					//$listfee = $this->megav_core_interface->getTempFee($transId);

					$data['paymentOffline'] = $this->megav_core_interface->paymentOffline($transId);
								
					// load ngân hàng liên kết
					$accessToken = $this->megav_libs->genarateAccessToken();
					$listBankMap = $this->megav_core_interface->getListBanksAcountMapping($this->session_memcached->userdata['info_user']['userID'], $accessToken);
					if($listBankMap)
						$data['listBankMap'] = $listBankMap;

					$this->load->view('payment_epurse/index', $data, true);
				}else{
					$loadView = 1;
					$error = lang('MVM_'.$requestExceedMapping->status);
					$this->megav_libs->page_result($error, null, null, '/transaction_manage');
				}

			}
		}else if (isset($_POST['paymentMappingStep2'])) {
			$this->form_validation->set_rules('bank_code', 'Ngân hàng', 'required|trim|xss_clean');
			$this->form_validation->set_rules('amount', 'Số tiền nạp', 'required|trim|xss_clean');
			if($this->session_memcached->userdata['info_user']['security_method'] == '1') {
				$this->form_validation->set_rules('otp', 'Mã xác nhận', 'required|trim|xss_clean');
			}else{
				$this->form_validation->set_rules('pass', 'Mật khẩu cấp 2', 'required|trim|xss_clean');
			}
			$data['bank_name'] = trim($this->input->post('bank_name'));
			$data['bank_code'] = trim($this->input->post('bank_code'));
			$data['amount'] = trim($this->input->post('amount'));
			if($post['feePaymentMap'] != "" )
				$data['feePaymentMap'] = trim($post['feePaymentMap']);
			else
				$data['feePaymentMap'] = $this->get_fee_payment_mapping_to_view($data['amount'], $data['bank_code']);
			$data['trans_id'] = trim($this->input->post('trans_id'));
			$data['step'] = '1';


			if($this->form_validation->run() == true) {
				$transId = "RP2" . date("Ymd") . rand();

					$data['step'] = '2';
					$amount = str_replace(',', '', trim($this->input->post('amount')));
					$accessToken = $this->megav_libs->genarateAccessToken();
					
					$merchantId = null;
					if(!empty($this->input->cookie("merchantId")))
						$merchantId = $this->input->cookie("merchantId");
					
					$requestnapOnlineMapping =json_decode($this->megav_core_interface->napOnlineMappingBank($this->session_memcached->userdata['info_user']['userID'],trim($this->input->post('bank_code')),$amount,trim($this->input->post('trans_id')),$this->session_memcached->userdata['info_user']['mobileNo'],$this->session_memcached->userdata['info_user']['email'],trim($this->input->post('otp')),trim($this->input->post('pass')), $accessToken, $merchantId));

					if ($requestnapOnlineMapping->status=='00') {
						$key3des = $this->megav_core_interface->getSessionKey();
						$dataMapping = json_decode($this->megav_core_interface->decrypt3DES($requestnapOnlineMapping->data, $key3des), true);

						if ($dataMapping['status']=='00') {
							$data['balance'] = number_format($this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																													$this->megav_libs->genarateAccessToken()));
							$requestTransId = $this->megav_libs->genarateTransactionId('GLT');
							$trans_id = $dataMapping['trans_id'];
							$result_info = $this->megav_core_interface->getTransList($this->session_memcached->userdata['info_user']['userID'], 1, $trans_id, "","", "", "", 0, 0, 1, $requestTransId, 3);

							if (!empty($result_info)) {
								$data['result_info'] = array(
									'requestId' => $result_info->listTrans[0]->requestId,
									'bankName' => $result_info->listTrans[0]->bankName,
									'timeCreate' => $result_info->listTrans[0]->timeCreate,
									'feeAmount' => $result_info->listTrans[0]->feeAmount,
									'amount' => $result_info->listTrans[0]->amount,
									'realReceive' => $result_info->listTrans[0]->realReceive,
									'status' => ($result_info->listTrans[0]->status=='00') ? 'Thành công' : 'Thất bại',
								);
							}
							
							$transId = $this->megav_libs->genarateTransactionId('GLB');
							//$listBank = $this->megav_core_interface->getProvider($transId, SUB_DEPOSIT_BANK_REDIRECT);
							$listBank = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, DEPOSIT_IBK_TEMP);
							//log_message('error', 'List Bank: ' . print_r($listBank, true));
							if($listBank)
								$data['listBank'] = $listBank;
							
							$listfee = $this->megav_core_interface->getTempFee($this->session_memcached->userdata['info_user']['userID'], $transId);

							$data['paymentOffline'] = $this->megav_core_interface->paymentOffline($transId);
										
							// load ngân hàng liên kết
							$accessToken = $this->megav_libs->genarateAccessToken();
							$listBankMap = $this->megav_core_interface->getListBanksAcountMapping($this->session_memcached->userdata['info_user']['userID'], $accessToken);
							if($listBankMap)
								$data['listBankMap'] = $listBankMap;

							$this->load->view('payment_epurse/index', $data, true);
						}else{
							$loadView = 1;
							$error = lang('MVM_'.$requestnapOnlineMapping->status);
							$this->megav_libs->page_result($error, null, null, '/transaction_manage');
						}
					}else if($requestnapOnlineMapping->status == STATUS_BANK_EXCEED){  
								// trường hợp vượt ngưỡng ngân hàng

							$key3des = $this->megav_core_interface->getSessionKey();
							$dataMapping = json_decode($this->megav_core_interface->decrypt3DES($requestnapOnlineMapping->data, $key3des), true);
							log_message('error', 'data transaction deposit mapping ' . print_r($dataMapping, true));
							$data['step'] = '3';
							$requestTransId = $this->megav_libs->genarateTransactionId('GLT');
							$trans_id = $dataMapping['trans_id'];
							$result_info = $this->megav_core_interface->getTransList($this->session_memcached->userdata['info_user']['userID'], 1, $trans_id, "","", "", "", 0, 0, 1, $requestTransId, 3);
							if (!empty($result_info)) {
								
								$data['result_info'] = array(
									'requestId' => $result_info->listTrans[0]->requestId,
									'bankName' => $result_info->listTrans[0]->bankName,
									'realReceive' => $result_info->listTrans[0]->realReceive
								);
							}


							$transId = $this->megav_libs->genarateTransactionId('GLB');
							//$listBank = $this->megav_core_interface->getProvider($transId, SUB_DEPOSIT_BANK_REDIRECT);
							$listBank = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, DEPOSIT_IBK_TEMP);
							//log_message('error', 'List Bank: ' . print_r($listBank, true));
							if($listBank)
								$data['listBank'] = $listBank;
							
							$listfee = $this->megav_core_interface->getTempFee($this->session_memcached->userdata['info_user']['userID'], $transId);

							$data['paymentOffline'] = $this->megav_core_interface->paymentOffline($transId);
										
							// load ngân hàng liên kết
							$accessToken = $this->megav_libs->genarateAccessToken();
							$listBankMap = $this->megav_core_interface->getListBanksAcountMapping($this->session_memcached->userdata['info_user']['userID'], $accessToken);
							if($listBankMap)
								$data['listBankMap'] = $listBankMap;

							$this->load->view('payment_epurse/index', $data, true);





						}elseif ($requestnapOnlineMapping->status==STATUS_WRONG_OTP || $requestnapOnlineMapping->status==STATUS_WRONG_PASSLV2) {
							$data['step'] = '1';
							$data['error_lv2'] = lang('MVM_'.$requestnapOnlineMapping->status);
							$data['mess_otp_success'] = 'Hệ thống đã gửi OTP tới số điện thoại '.substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 0, (strlen($this->session_memcached->userdata['info_user']['mobileNo']) - 4)).'.Không nhận được <a hre="javascript:void(0);" class="re-sendOTPMap">Click gửi lại.</a>';


							$transId = $this->megav_libs->genarateTransactionId('GLB');
							//$listBank = $this->megav_core_interface->getProvider($transId, SUB_DEPOSIT_BANK_REDIRECT);
							$listBank = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, DEPOSIT_IBK_TEMP);
							//log_message('error', 'List Bank: ' . print_r($listBank, true));
							if($listBank)
								$data['listBank'] = $listBank;
							
							$listfee = $this->megav_core_interface->getTempFee($this->session_memcached->userdata['info_user']['userID'], $transId);

							$data['paymentOffline'] = $this->megav_core_interface->paymentOffline($transId);
										
							// load ngân hàng liên kết
							$accessToken = $this->megav_libs->genarateAccessToken();
							$listBankMap = $this->megav_core_interface->getListBanksAcountMapping($this->session_memcached->userdata['info_user']['userID'], $accessToken);
							if($listBankMap)
								$data['listBankMap'] = $listBankMap;

							$this->load->view('payment_epurse/index', $data, true);


							//$loadView = 1;
							//$this->megav_libs->page_result(lang('MVM_'.$requestnapOnlineMapping->status), '/transaction_manage');
						}else{
							$loadView = 1;
							$error = lang('MVM_'.$requestnapOnlineMapping->status);
							$this->megav_libs->page_result($error, null, null, '/transaction_manage');
						}



			}else{
				$data['mess_otp_success'] = 'Hệ thống đã gửi OTP tới số điện thoại '.substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 0, (strlen($this->session_memcached->userdata['info_user']['mobileNo']) - 4)).'.Không nhận được <a hre="javascript:void(0);" class="re-sendOTPMap">Click gửi lại.</a>';
			}
		}else{
			$data['step'] = '0';
			if($post){
				log_message('error', 'data post: ' . print_r($post, true));
				
				if($post['payment_type'] == '1')
				{
					$this->form_validation->set_rules('provider_code', 'Ngân hàng', 'required|trim|xss_clean');
				}			
				//$this->form_validation->set_rules('payment_type', 'Phương thức nạp', 'required|trim|xss_clean');
				$this->form_validation->set_rules('amount', 'Số tiền nạp', 'required|trim|xss_clean');
				if($this->form_validation->run() == true) 
				{
					// payment online
					$amount = str_replace(',', '', $post['amount']);
					$transId = $this->megav_libs->genarateTransactionId('PBO');
					$accessToken = $this->megav_libs->genarateAccessToken();
					$urlNotify = base_url() . 'payment_epurse/notify';
					
					$merchantId = null;
					if(!empty($this->input->cookie("merchantId")))
						$merchantId = $this->input->cookie("merchantId");
					
					$paymentRequest = $this->megav_core_interface->paymentOnline($this->session_memcached->userdata['info_user']['userID'], $post['payment_type'], 
																				$amount, $post['provider_code'], $urlNotify, $accessToken, $transId, $merchantId);
					$paymentInfo = json_decode($paymentRequest);
					if(isset($paymentInfo->status))
					{
						$dataPayment = '';
						$key3des = $this->megav_core_interface->getSessionKey();
						
						
						log_message('error', 'Merchant Info: ' . print_r($dataPayment, true));
						if($paymentInfo->status == STATUS_SUCCESS)
						{
							$loadView = 1;
							//$data['merchantInfo'] = isset($dataPayment['merchantInfo']) ? $dataPayment['merchantInfo'] : '';
							//$data['paymentInfo'] = isset($dataPayment['ecPayment']) ? $dataPayment['ecPayment'] : '';
							//$dataPaymentRedis = array('transId' => $transId, 
							//						'paymentId' => $dataPayment['ecPayment']['requestId'],
							//						'merchantInfo' => $data['merchantInfo'],
							//						'paymentInfo' => $data['paymentInfo']);
							//$this->megav_libs->saveCookieUserData($transId, $dataPaymentRedis);
							//$this->megav_libs->page_result("Nạp tiền thành công.", 'payment_online');
							if(isset($paymentInfo->data))
								$dataPayment = json_decode($this->megav_core_interface->decrypt3DES($paymentInfo->data, $key3des), true);
							
							log_message('error', 'Payment data | ' . print_r($dataPayment, true));
							
							echo '<script>window.top.location = "' . $dataPayment['redirect_url'] . '";</script>';
							//redirect($dataPayment['redirect_url']);
							//die;
							
						}
						elseif($paymentInfo->status == STATUS_BONUS_NOT_ENOUGH_MONEY)
						{
							$loadView = 1;
							//$data['notEnoughMoney'] = 1;
							//$data['redirect_link'] = isset($dataPayment['redirect_url']) ? $dataPayment['redirect_url'] : '';
							//$data['timeRedirect'] = EC_TIME_REDIRECT_NOT_ENOUGH_MONEY;
							//$data['merchantInfo'] = isset($dataPayment['merchantInfo']) ? $dataPayment['merchantInfo'] : '';
							//$data['paymentInfo'] = isset($dataPayment['ecPayment']) ? $dataPayment['ecPayment'] : '';
							//$data['message'] = "Số dư không đủ, thanh toán thất bại. Hệ thống sẽ tự động chuyển về trang của merchant sau <b id='countdown_text'></b> giây. Nếu không muốn chờ đợi, vui lòng click <a href='" . $data['redirect_link'] . "'>Quay lại</a>";
							$this->megav_libs->page_result("Số dư không đủ, nạp tiền thất bại.", null, null, '/transaction_manage');
							//echo $view;
						}
						else
						{
							//redirect($dataPayment['redirect_url']);
							$loadView = 1;
							$this->megav_libs->page_result(lang('MVM_'.$paymentInfo->status), null, null, '/transaction_manage');
							//echo $view;
						}
					}
					else
					{
						$loadView = 1;
						$this->megav_libs->page_result("Có lỗi trong quá trình nạp tiền. Vui lòng thử lại.", null, null, '/transaction_manage');
						//echo $view;
					}
				}
			}
		}
		
		
		if(!isset($loadView))
		{
			$transId = $this->megav_libs->genarateTransactionId('GLB');
			//$listBank = $this->megav_core_interface->getProvider($transId, SUB_DEPOSIT_BANK_REDIRECT);
			$listBank = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, DEPOSIT_IBK_TEMP);
			//log_message('error', 'List Bank: ' . print_r($listBank, true));
			if($listBank)
				$data['listBank'] = $listBank;
			
			$listfee = $this->megav_core_interface->getTempFee($this->session_memcached->userdata['info_user']['userID'], $transId);
			
			//$balance = $this->megav_core_interface->getBalaceUser($this->session_memcached->userdata['info_user']['userID'], $transId);
			
			//$balanceBonus = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'], $transId);
			
			$data['paymentOffline'] = $this->megav_core_interface->paymentOffline($transId);
						
			// load ngân hàng liên kết
			$accessToken = $this->megav_libs->genarateAccessToken();
			$listBankMap = $this->megav_core_interface->getListBanksAcountMapping($this->session_memcached->userdata['info_user']['userID'], $accessToken);
			if($listBankMap)
				$data['listBankMap'] = $listBankMap;

			//$this->load->view('payment_epurse/index', $data);
			$this->view['content'] = $this->load->view('payment_epurse/index', $data, true);
			$this->load->view('Layout/layout_iframe', array(
				'data' => $this->view
			));
			
		}
		
		
		
	}
	public function payment_online()
	{
		$data = array();
		$post = $this->input->post();
		if($post)
		{
			if($post['paymentOnline'])
			{
				//$this->form_validation->set_rules('trans_type', 'Hình thức nạp tiền', 'required|trim|xss_clean');
				$this->form_validation->set_rules('provider_code', 'Ngân hàng', 'required|trim|xss_clean');
				$this->form_validation->set_rules('payment_type', 'Phương thức nạp', 'required|trim|xss_clean');
				$this->form_validation->set_rules('amount', 'Số tiền nạp', 'required|trim|xss_clean');
				if($this->form_validation->run() == true) 
				{
					// payment online
					$amount = str_replace(',', '', $post['amount']);
					$transId = $this->megav_libs->genarateTransactionId('PBO');
					$accessToken = $this->megav_libs->genarateAccessToken();
					$urlNotify = base_url() . 'payment_epurse/notify';
					
					$merchantId = null;
					if(!empty($this->input->cookie("merchantId")))
						$merchantId = $this->input->cookie("merchantId");
					
					$paymentRequest = $this->megav_core_interface->paymentOnline($this->session_memcached->userdata['info_user']['userID'], $post['payment_type'], 
																				$amount, $post['provider_code'], $urlNotify, $accessToken, $transId, $merchantId);
					$paymentInfo = json_decode($paymentRequest);
					if(isset($paymentInfo->status))
					{
						$dataPayment = '';
						$key3des = $this->megav_core_interface->getSessionKey();
						
						
						log_message('error', 'payment Info: ' . print_r($dataPayment, true));
						if($paymentInfo->status == STATUS_SUCCESS)
						{
							$loadView = 1;
							//$data['merchantInfo'] = isset($dataPayment['merchantInfo']) ? $dataPayment['merchantInfo'] : '';
							//$data['paymentInfo'] = isset($dataPayment['ecPayment']) ? $dataPayment['ecPayment'] : '';
							//$dataPaymentRedis = array('transId' => $transId, 
							//						'paymentId' => $dataPayment['ecPayment']['requestId'],
							//						'merchantInfo' => $data['merchantInfo'],
							//						'paymentInfo' => $data['paymentInfo']);
							//$this->megav_libs->saveCookieUserData($transId, $dataPaymentRedis);
							//$this->megav_libs->page_result("Nạp tiền thành công.", 'payment_online');
							if(isset($paymentInfo->data))
								$dataPayment = json_decode($this->megav_core_interface->decrypt3DES($paymentInfo->data, $key3des), true);
							//var_dump($dataPayment);
							log_message('error', 'Payment data | ' . print_r($dataPayment, true));
							//redirect($dataPayment['redirect_url']);
							//die;
							echo '<script>window.top.location = "' . $dataPayment['redirect_url'] . '";</script>';
							
						}
						elseif($paymentInfo->status == STATUS_BONUS_NOT_ENOUGH_MONEY)
						{
							$loadView = 1;
							//$data['notEnoughMoney'] = 1;
							//$data['redirect_link'] = isset($dataPayment['redirect_url']) ? $dataPayment['redirect_url'] : '';
							//$data['timeRedirect'] = EC_TIME_REDIRECT_NOT_ENOUGH_MONEY;
							//$data['merchantInfo'] = isset($dataPayment['merchantInfo']) ? $dataPayment['merchantInfo'] : '';
							//$data['paymentInfo'] = isset($dataPayment['ecPayment']) ? $dataPayment['ecPayment'] : '';
							//$data['message'] = "Số dư không đủ, thanh toán thất bại. Hệ thống sẽ tự động chuyển về trang của merchant sau <b id='countdown_text'></b> giây. Nếu không muốn chờ đợi, vui lòng click <a href='" . $data['redirect_link'] . "'>Quay lại</a>";
							$view = $this->megav_libs->page_result("Số dư không đủ, nạp tiền thất bại.", null, null, '/transaction_manage');
							echo $view;
						}
						else
						{
							//redirect($dataPayment['redirect_url']);
							$loadView = 1;
							$view = $this->megav_libs->page_result(lang('MVM_'.$paymentInfo->status), null, null, '/transaction_manage');
							echo $view;
						}
					}
					else
					{
						$loadView = 1;
						$view = $this->megav_libs->page_result("Có lỗi trong quá trình nạp tiền. Vui lòng thử lại.", null, null, '/transaction_manage');
						echo $view;
					}
				}
			}
		}
		
		if(!isset($loadView))
		{
			$transId = $this->megav_libs->genarateTransactionId('GLB');
			//$listBankRedirect = $this->megav_core_interface->getProvider($transId, SUB_DEPOSIT_BANK_REDIRECT);
			$listBankRedirect = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, DEPOSIT_IBK_TEMP);
			//log_message('error', 'List Bank: ' . print_r($listBank, true));
			if($listBank)
				$data['listBank'] = $listBankRedirect;
			
			$listfee = $this->megav_core_interface->getTempFee($this->session_memcached->userdata['info_user']['userID'], $transId);
			
			//$balance = $this->megav_core_interface->getBalaceUser($this->session_memcached->userdata['info_user']['userID'], $transId);
			
			//$balanceBonus = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'], $transId);
						
			//$this->load->view('payment_epurse/payment_online', $data);
			$this->view['content'] = $this->load->view('payment_epurse/payment_online', $data, true);
			$this->load->view('Layout/layout_iframe', array(
				'data' => $this->view
			));
			//echo $view;
			/*
			$this->view['content'] = $this->load->view('payment_epurse/payment_online', $data, true);
			$this->load->view('Layout/layout_info', array(
				'data' => $this->view,
				'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
			));
			*/
		}
	}
	
	
	public function notify()
	{
		log_message('error', 'payment_bank_notify URL QUERY_STRING: ' . print_r($_SERVER['QUERY_STRING'], true));
		$url = base_url() . 'payment_epurse/payment_bank_notify?' . $_SERVER['QUERY_STRING'];
		log_message('error', 'URL QUERY_STRING: ' . print_r($url, true));
		//$this->view['content'] = $this->load->view('acc_manage/info', $data, true);
		$dataMenu = array();
		$dataMenu['userinfo'] = array('userName' => $this->session_memcached->userdata['info_user']['userID'],
										'mobileNo' => $this->session_memcached->userdata['info_user']['mobileNo'],
										'balance' => $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																											$this->megav_libs->genarateAccessToken()));
		
		$redis = new CI_Redis();
		$dataMenu['userinfo']['balance_behold'] = $redis->get('BALANCE_BEHOLD' . $dataMenu['userinfo']['userName']);
		
		$this->load->view('Layout/layout_info', array(
			'nav_left' => $this->load->view('Layout/layout_menu_left', $dataMenu, true),
			'user_info' => $this->session_memcached->userdata['info_user'],
			'contentIframe' => $url
		));
	}
	
	public function payment_bank_notify()
	{
		//$url = $_SERVER['QUERY_STRING'];
		//log_message('error', 'payment_bank_notify URL QUERY_STRING: ' . print_r($url, true));
		
		$get = $this->input->get();
		if($get)
		{
			$data = array();
			$data['step'] = '0';
			//var_dump($get); die;
			//($userName, $trans_id, $responCode, $transId)
			$transId = $this->megav_libs->genarateTransactionId('PBO');
			
			$merchantId = null;
			if(!empty($this->input->cookie("merchantId")))
				$merchantId = $this->input->cookie("merchantId");
			
			$paymentRequest = $this->megav_core_interface->paymentOnlineUpdate($this->session_memcached->userdata['info_user']['userID'], 
																		$get['transid'], $get['responCode'], $transId, $this->megav_libs->genarateAccessToken(), $merchantId);
			$paymentInfo = json_decode($paymentRequest);
			if(isset($paymentInfo->status))
			{
				if($paymentInfo->status == STATUS_SUCCESS)
				{
					$loadView = 1;
					$dataPayment = '';
					//$data['merchantInfo'] = isset($dataPayment['merchantInfo']) ? $dataPayment['merchantInfo'] : '';
					//$data['paymentInfo'] = isset($dataPayment['ecPayment']) ? $dataPayment['ecPayment'] : '';
					//$dataPaymentRedis = array('transId' => $transId, 
					//						'paymentId' => $dataPayment['ecPayment']['requestId'],
					//						'merchantInfo' => $data['merchantInfo'],
					//						'paymentInfo' => $data['paymentInfo']);
					//$this->megav_libs->saveCookieUserData($transId, $dataPaymentRedis);
					//$this->megav_libs->page_result("Nạp tiền thành công.", 'payment_online');
					$key3des = $this->megav_core_interface->getSessionKey();
					$dataPayment = json_decode($this->megav_core_interface->decrypt3DES($paymentInfo->data, $key3des), true);
					log_message('error', 'payment Info: ' . print_r($dataPayment, true));
					
					if(isset($dataPayment['status']))
					{
						if($dataPayment['status'] == STATUS_SUCCESS)
						{
							//$this->megav_libs->page_result("Nạp tiền thành công", null, null, '/transaction_manage');
							//echo $view;
							
							$data['finish'] = 1;
							
							$data['mess'] = 'Nạp tiền thành công';
							$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																									$this->megav_libs->genarateAccessToken());
							
							$key3des = $this->megav_core_interface->getSessionKey();
							if(isset($dataPayment['data']))
								$dataPayment = json_decode($this->megav_core_interface->decrypt3DES($dataPayment['data'], $key3des), true);
							
							//log_message('error', 'data payment decrypt: ' . print_r($dataPayment, true));
							
							/*
							$result = $this->megav_core_interface->getTransList($uname, 
																	trim($post['transaction_type']), trim($post['transId']), trim($post['provider_code']), 
																	trim($post['fdate']), trim($post['tdate']), trim($post['status']), trim($post['numbPage']), 
																	trim($post['pageSize']), trim($post['transferType']), $requestTransId, $transSubType);
							*/
							
							$transDetail = $this->megav_core_interface->getTransList($this->session_memcached->userdata['info_user']['userID'], 1, 
																				$dataPayment['trans_id'], "", "", "", "", 0, 0, 1, $this->megav_libs->genarateTransactionId('GLT'), "");
							$data['detail'] = $transDetail->listTrans[0];
							
							$this->view['content'] = $this->load->view('payment_epurse/index', $data, true);
							$this->load->view('Layout/layout_iframe', array('data' => $this->view));
							
						}
					
						else
						{
							//$this->megav_libs->page_result(lang('MVM_'.$dataPayment['status']), null, null, '/transaction_manage');
							//echo $view;
							$data['finish'] = 1;
							$data['mess'] = lang('MVM_'.$dataPayment['status']);
							$this->view['content'] = $this->load->view('payment_epurse/index', $data, true);
							$this->load->view('Layout/layout_iframe', array(
								'data' => $this->view
							));
						}
						
					}
					else
					{
						//$this->megav_libs->page_result("Giao dịch đang xử lý", null, null, '/payment_epurse');
						//echo $view;
						$data['finish'] = 1;
						$data['mess'] = 'Giao dịch đang xử lý';
						$this->view['content'] = $this->load->view('payment_epurse/index', $data, true);
						$this->load->view('Layout/layout_iframe', array(
							'data' => $this->view
						));
					}
					
				}
				else
				{
					//redirect($dataPayment['redirect_url']);
					//$loadView = 1;
					//$this->megav_libs->page_result(lang('MVM_'.$paymentInfo->status), null, null, '/transaction_manage');
					//echo $view;
					
					if(isset($paymentInfo->data))
					{
						$key3des = $this->megav_core_interface->getSessionKey();
						$dataPayment = json_decode($this->megav_core_interface->decrypt3DES($paymentInfo->data, $key3des), true);
						$transDetail = $this->megav_core_interface->getTransList($this->session_memcached->userdata['info_user']['userID'], 1, 
																				$dataPayment['trans_id'], "", "", "", "", 0, 0, 1, $this->megav_libs->genarateTransactionId('GLT'), "");
						$data['detail'] = $transDetail->listTrans[0];
					}
					
					$data['finish'] = 1;
					$data['mess'] = lang('MVM_'.$paymentInfo->status);
					$this->view['content'] = $this->load->view('payment_epurse/index', $data, true);
					$this->load->view('Layout/layout_iframe', array(
						'data' => $this->view
					));
				}
			}
			else
			{
				//$loadView = 1;
				//$this->megav_libs->page_result("Có lỗi trong quá trình nạp tiền. Vui lòng thử lại.", null, null, '/transaction_manage');
				//echo $view;
				$data['finish'] = 1;
				$data['mess'] = "Có lỗi trong quá trình nạp tiền. Vui lòng thử lại.";
				$this->view['content'] = $this->load->view('payment_epurse/index', $data, true);
				$this->load->view('Layout/layout_iframe', array(
					'data' => $this->view
				));
			}
		}
		else
		{
			// hiển thị chuyển sang trang giao dich
			log_message('error', 'Khong co get');
		}
	}

	public function payment_offline()
	{
		$data = array();
		$transId = $this->megav_libs->genarateTransactionId('PBO');
		//$listBank = $this->megav_core_interface->paymentOffline($transId);
		//if($listBank)
		//	$data['listBank'] = $listBank;
		
		$this->view['content'] = $this->load->view('payment_epurse/payment_offline', $data, true);
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
		));
	}
	
	public function get_fee_payment()
	{
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		$post = $this->input->post();
		if($post)
		{
			$transId = $this->megav_libs->genarateTransactionId('GLB');
			//$listBankRedirect = $this->megav_core_interface->getProvider($transId, SUB_DEPOSIT_BANK_REDIRECT);
			
			$listBankRedirect = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, DEPOSIT_IBK_TEMP);
			
			$post['amount'] 	= $this->security->xss_clean($post['amount']);
			$post['bank_code'] 	= $this->security->xss_clean($post['bank_code']);
			//log_message('error', 'get_fee_payment: ' . print_r($post, true));
			if(isset($post['amount']) && !empty($post['amount']) && isset($post['bank_code']) && !empty($post['bank_code']))
			{
				
				foreach($listBankRedirect as $bankTemp){
					if($bankTemp->providerId == $post['bank_code']){
						$post['amount'] = str_replace(',', '', $post['amount']);
						$feeAmount = $bankTemp->fixDiscount + $bankTemp->rateDiscount * $post['amount'] / 100;
						$feeAmountFormat = number_format($feeAmount);
						//$realAmount = $post['amount'] + $feeAmount;
						$realAmountFormat = number_format($post['amount']);
						echo $feeAmountFormat . '|' . $realAmountFormat;
					}
				}
				
				//$redis = new CI_Redis();
				//
				//$listProvider = $redis->sMembers(PROVIDER_KEY_REDIS);
				//$feeId = '';
				//foreach($listProvider as $bank)
				//{
				//	$bank = json_decode($bank, true);
				//	if($post['bank_code'] == $bank['recId'])
				//	{
				//		$feeId = $bank['ibkTemp'];
				//	}
				//}
				
				//if($feeId != '')
				//{
				//	//$listTemplate = $redis->sMembers(TEMPLATE_FEE_KEY_REDIS);
				//	
				//	$listTemplate = $this->megav_core_interface->getTempFee($this->session_memcached->userdata['info_user']['userID'], $transId);
				//	
				//	//log_message('error', 'list template ' . print_r($listTemplate, true));
				//	foreach($listTemplate as $template)
				//	{
				//		$template = json_decode($template, true);
				//		//if($template['templateId'] == $feeId)
				//		if($post['bank_code'] == $template['bank_code'])
				//		{
				//			$post['amount'] = str_replace(',', '', $post['amount']);
				//			$feeAmount = $template['fixFee'] + $template['fixRate'] * $post['amount'] / 100;
				//			$feeAmountFormat = number_format($feeAmount);
				//			//$realAmount = $post['amount'] + $feeAmount;
				//			$realAmountFormat = number_format($post['amount']);
				//			echo $feeAmountFormat . '|' . $realAmountFormat;
				//		}
				//	}
				//}
			}
			
			
		}
	}

	public function get_fee_payment_mapping(){
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		$post = $this->input->post();
		if($post)
		{		
			$post['amount'] 	= $this->security->xss_clean($post['amount']);
			$post['bank_code'] 	= $this->security->xss_clean($post['bank_code']);
			//log_message('error', 'get_fee_payment_mapping: ' . print_r($post, true));
			if(isset($post['amount']) && !empty($post['amount']) && isset($post['bank_code']) && !empty($post['bank_code']))
			{
				$transId = $this->megav_libs->genarateTransactionId('GLB');
				$listBankRedirect = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, DEPOSIT_BANK_MAPPING_TEMP);
				$getAllProvider = $this->megav_core_interface->getProvider($transId);
				$bankId = "";
				foreach($getAllProvider as $provider){
					if(isset($provider->epurseBankCode) && $provider->epurseBankCode == $post['bank_code'])
					{
						$bankId = $provider->recId;
						break;
					}
				}
				
				foreach($listBankRedirect as $bankTemp){
					if($bankTemp->providerId == $bankId){
						$post['amount'] = str_replace(',', '', $post['amount']);
						$feeAmount = $bankTemp->fixDiscount + $bankTemp->rateDiscount * $post['amount'] / 100;
						$feeAmountFormat = number_format($feeAmount);
						//$realAmount = $post['amount'] + $feeAmount;
						$realAmountFormat = number_format($post['amount']);
						echo $feeAmountFormat . '|' . $realAmountFormat;
					}
				}
				
				//$redis = new CI_Redis();
				//$listProvider = $redis->sMembers(PROVIDER_KEY_REDIS);
                //
                //
				//$feeId = '';
				//foreach($listProvider as $bank)
				//{
				//	$bank = json_decode($bank, true);
				//	if(isset($bank['epurseBankCode']) && $post['bank_code'] == $bank['epurseBankCode'])
				//	{
				//		$feeId = $bank['bankMappingCreTemp'];
				//	}
				//}
                //
				//if($feeId != '')
				//{
				//	$listTemplate = $redis->sMembers(TEMPLATE_FEE_KEY_REDIS);
				//	
				//	foreach($listTemplate as $template)
				//	{
				//		$template = json_decode($template, true);
				//		if($template['templateId'] == $feeId)
				//		{
				//			$post['amount'] = str_replace(',', '', $post['amount']);
				//			$feeAmount = $template['fixFee'] + $template['fixRate'] * $post['amount'] / 100;
				//			$feeAmountFormat = number_format($feeAmount);
				//			//$realAmount = $post['amount'] + $feeAmount;
				//			$realAmountFormat = number_format($post['amount']);
				//			echo $feeAmountFormat . '|' . $realAmountFormat;
				//		}
				//	}
				//}
			}
			
			
		}
	}
	
	public function get_fee_payment_mapping_to_view($amount, $bankcode){
		//log_message('error', 'get_fee_payment_mapping_to_view' . $amount . " | " . $bankcode);
			
		$transId = $this->megav_libs->genarateTransactionId('GLB');
		$listBankRedirect = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, DEPOSIT_BANK_MAPPING_TEMP);
		$getAllProvider = $this->megav_core_interface->getProvider($transId);
		$bankId = "";
		foreach($getAllProvider as $provider){
			if(isset($provider->epurseBankCode) && $provider->epurseBankCode == $bankcode)
			{
				$bankId = $provider->recId;
				break;
			}
		}
		
		foreach($listBankRedirect as $bankTemp){
			if($bankTemp->providerId == $bankId){
				$amount = str_replace(',', '', $amount);
				$feeAmount = $bankTemp->fixDiscount + $bankTemp->rateDiscount * $amount / 100;
				$feeAmountFormat = number_format($feeAmount);
				$realAmountFormat = number_format($amount);
				//echo $feeAmountFormat . '|' . $realAmountFormat;
				return $feeAmountFormat;
				break;
			}
		}
			
		
	}
	
	public function resenOTPMap(){
		// genotp
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
	    if (isset($_POST['check'])) {
	    	// gửi xác nhận
			$transId = "RP2" . date("Ymd") . rand();
			if($this->session_memcached->userdata['info_user']['security_method'] == '1') {
							//echo $this->session_memcached->userdata['info_user']['userID'];die;
							$requestSendOtp = $this->megav_core_interface->genOTP($this->session_memcached->userdata['info_user']['email'],$this->session_memcached->userdata['info_user']['mobileNo'], $this->session_memcached->userdata['info_user']['userID'], $transId);

							if($requestSendOtp){
									$response = json_decode($requestSendOtp);
									log_message('error', 'respone: ' . print_r($requestSendOtp, true));
									if(isset($response->status))
									{
										if($response->status == '00')
										{
											// gui OTP thanh cong
											$data = array(
												'status' => 1,
												'mess'   => 'Hệ thống đã gửi OTP đến số điện thoại của bạn.',
												'transId' => $transId
											);

											echo json_encode($data);
										}
										else
										{
											$data = array(
												'status' => 0,
												'mess'   => 'Gửi OTP thất bại.'
											);

											echo json_encode($data);
										}
									}
									else
									{
										$mess = "Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
										$data = array(
											'status' => 0,
											'mess'   => $mess
										);

										echo json_encode($data);
									}
								}
								else
								{					
									$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
									$data = array(
										'status' => 0,
										'mess'   => $mess
									);

									echo json_encode($data);
								}
			}
	    }
	}
}
?>