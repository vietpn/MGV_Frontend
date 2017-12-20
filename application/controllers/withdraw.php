<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class withdraw extends CI_Controller
{
    public function __construct()
    {
        parent::__construct(true);
        $this->load->driver('cache');
        $this->load->helper('language');
        $this->load->library('session_memcached');
        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('redis');
        $this->load->library('id_curl');
		$this->load->library('id_encrypt');
        $this->load->library('authen_interface');
        $this->load->helper('form');
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
		$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																				$this->megav_libs->genarateAccessToken(), null, 1);
		$this->view['content'] = $this->load->view('withdraw/withdraw_epurse_1', $data, true);
		$this->load->view('Layout/layout_iframe', array(
			'data' => $this->view
		));
	}
	
	/*
	public function withdraw_epurse_1()
	{

		$data = array();
		$this->load->view('withdraw/withdraw_epurse_1', $data);
	}
	*/
	
	public function withdraw_epurse($widthdraw_met = null)
	{

		$post = $this->input->post();
		

		log_message('error', 'data post: ' . print_r($post, true));
        $accInfo = $this->session_memcached->userdata['info_user'];

		$data = array();
		$data['step'] = 2;
		$transId = $this->megav_libs->genarateTransactionId('GBL');
		$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																				$this->megav_libs->genarateAccessToken(), null, null);
		$uniqueBank = array();
		if($widthdraw_met == 1 || $post['widthdraw_met'] == '1')
		{
			$listBankACC = $this->megav_core_interface->getListBankAccount($this->session_memcached->userdata['info_user']['email'],
																		$this->session_memcached->userdata['info_user']['mobileNo'],
																		$this->session_memcached->userdata['info_user']['userID'], $transId);
			$listBank = $this->megav_core_interface->getProvider($transId);

			$bankProvider = array();
			foreach($listBankACC as &$bankAcc){
				
				foreach($listBank as $bank){
					//log_message('error', 'Withdraw || bank acc ' . print_r($bankAcc, true));
					if(isset($bankAcc->bankCode) && $bankAcc->bankCode == $bank->providerCode){
						$bankAcc->recId = $bank->recId;
						//log_message('error', 'Mapp Bank: ' . print_r($bankAcc, true));
						$bankProvider[] = $bankAcc;
					}
				}
			}
			
			
			foreach ($bankProvider as $object) {
				if (isset($uniqueBank[$object->recId])) {
					continue;
				}
				$uniqueBank[$object->recId] = $object;
			}
		}
		else if($widthdraw_met == 2 || $post['widthdraw_met'] == '2')
		{
			$listBank = $this->megav_core_interface->getProvider($transId, SUB_WITHDRAW_TYPE_FAST);

			$listBank_info_user = $this->megav_core_interface->getListBankAccount($this->session_memcached->userdata['info_user']['email'],$this->session_memcached->userdata['info_user']['mobileNo'],$this->session_memcached->userdata['info_user']['userID'], $transId,1);
			log_message('error', 'list bank firm: ' . print_r($listBank_info_user, true));
			$uniqueBank = array();
			if ($listBank && $listBank_info_user) {
				foreach ($listBank_info_user as $k => $v) {
					if($v->status != '99')
					{
						foreach ($listBank as $key => $value) {
							if ($v->bankCode==$value->epurseBankCode) {
								$uniqueBank[] = $value;
							}
						}
					}
				}
			}

/*			echo "<pre>";
			print_r($listBank);die;*/
			//$uniqueBank = $listBank;


		}
		elseif($widthdraw_met == 3 || $post['widthdraw_met'] == '3')
		{
			$accessToken = $this->megav_libs->genarateAccessToken();
			$listBank = $this->megav_core_interface->getListBanksAcountMapping($accInfo['userID'], $accessToken);
			//log_message('error', 'list bank mapping: ' . print_r($listBank, true));
			$uniqueBank = $listBank;
		}
		
		
		$accInfo = $this->session_memcached->userdata['info_user'];
		if($post)
		{
			$post['widthdraw_met'] 	= $this->security->xss_clean($post['widthdraw_met']);
			$post['fee'] 			= $this->security->xss_clean($post['fee']);
			$post['amount'] 		= $this->security->xss_clean($post['amount']);
			$post['bankAcc'] 		= $this->security->xss_clean($post['bankAcc']);
			$post['provider_code'] 	= $this->security->xss_clean($post['provider_code']);
			
			$data['post_act'] = $this->input->post();
			$this->form_validation->set_rules('widthdraw_met', 'Hình thức rút tiền', 'required|trim|xss_clean');
			$this->form_validation->set_rules('amount', 'Số tiền rút', 'required|trim|xss_clean|max_length[13]|callback_check_amount');
			
			if(isset($post['widthdraw_met']) && $post['widthdraw_met'] == '1'){
				$this->form_validation->set_rules('bankAcc', 'Tài khoản ngân hàng', 'required|trim|xss_clean');
			}elseif(isset($post['widthdraw_met']) && $post['widthdraw_met'] == '2'){
				$this->form_validation->set_rules('bankAcc', 'Số thẻ ngân hàng', 'required|trim|xss_clean');
			}
			
			$this->form_validation->set_rules('provider_code', 'Ngân hàng', 'required|trim|xss_clean');
			if($this->form_validation->run() == true)
			{
				$post['amount'] = str_replace(',', '', $post['amount']);
				if($post['widthdraw_met'] == '2'){
					$tempType = WIDTHDRAW_FAST_TEMP;
				}elseif($post['widthdraw_met'] == '3'){
					$tempType = WIDTHDRAW_MAPPING_TEMP;
				}else{
					$tempType = '4';
				}
				$data['fee'] = $this->getFee($post['amount'], $tempType, $post['provider_code']);
				if($data['balance'] >= $post['amount'] + $data['fee'])
				{
					if(WITHDRAW_MINIMUM_AMOUNT <= $post['amount'] && $post['amount'] <= WITHDRAW_MAXIMUM_AMOUNT)
					{
						$transId = $this->megav_libs->genarateTransactionId('WDA');
						$dataWithdrawRedis = array('transId' => $transId);
						if(isset($post['widthdraw_met']) && $post['widthdraw_met'] == '1')
						{
							
							// luu session data post va phí
							
							$data['post'] = $post;
							
							$dataWithdrawRedis['post_1'] = $data['post'];
							$dataWithdrawRedis['fee'] = $data['fee'];
							
							// load view B2: data = datapost buoc 1
							$data['listBankP2'] = $listBank;
							if($accInfo['security_method'] == '1')
							{
								$requestSendOtp = $this->megav_core_interface->genOTP($accInfo['email'], $accInfo['mobileNo'], $accInfo['userID'], $dataWithdrawRedis['transId']);
								$requestSendOtp = json_decode($requestSendOtp);
								if($requestSendOtp->status == STATUS_SUCCESS)
								{
									$loadView = 1;
									$data['sentOtp'] = 1;
									$data['step'] = 3;
                                    $data['userInfo'] = $accInfo;
									$this->view['content'] = $this->load->view('withdraw/withdraw_epurse_2', $data, true);
									$this->load->view('Layout/layout_iframe', array(
										'data' => $this->view
									));
								}
								else
								{
									$loadView = 1;
									$mess = lang('MVM_'.$requestSendOtp->status);
									$this->megav_libs->page_result($mess, 'withdraw');
								}
								
							}
							else
							{
								$loadView = 1;
								$data['step'] = 3;
                                $data['userInfo'] = $accInfo;
								$this->view['content'] = $this->load->view('withdraw/withdraw_epurse_2', $data, true);
								$this->load->view('Layout/layout_iframe', array(
									'data' => $this->view
								));
							}
							
						}
						
						if(isset($post['widthdraw_met']) && $post['widthdraw_met'] == '2')
						{

							// luu session data post va phí
							
							$data['post'] = $post;
							
							$dataWithdrawRedis['post_1'] = $data['post'];
							$dataWithdrawRedis['fee'] = $data['fee'];
							
							// load view B2: data = datapost buoc 1
							$data['listBankP2'] = $listBank;

							if($accInfo['security_method'] == '1')
							{
								$requestSendOtp = $this->megav_core_interface->genOTP($accInfo['email'], $accInfo['mobileNo'], $accInfo['userID'], $dataWithdrawRedis['transId']);
								$requestSendOtp = json_decode($requestSendOtp);
								if($requestSendOtp->status == STATUS_SUCCESS)
								{
									$loadView = 1;
									$data['sentOtp'] = 1;
									$data['step'] = 3;
                                    $data['userInfo'] = $accInfo;

                                    $transId = $this->megav_libs->genarateTransactionId('GLB');
									$listBank = $this->megav_core_interface->getListBankAccount($this->session_memcached->userdata['info_user']['email'],$this->session_memcached->userdata['info_user']['mobileNo'],$this->session_memcached->userdata['info_user']['userID'], $transId,1);
									$data['listBankP2'] = $listBank;

									$this->view['content'] = $this->load->view('withdraw/withdraw_epurse_2', $data, true);
									$this->load->view('Layout/layout_iframe', array(
										'data' => $this->view
									));
								}
								else
								{
									$loadView = 1;
									$mess = lang('MVM_'.$requestSendOtp->status);
									$this->megav_libs->page_result($mess, 'withdraw');
								}
								
							}
							else
							{
								$loadView = 1;
								$data['step'] = 3;
                                $data['userInfo'] = $accInfo;
                                $transId = $this->megav_libs->genarateTransactionId('GLB');
									$listBank = $this->megav_core_interface->getListBankAccount($this->session_memcached->userdata['info_user']['email'],$this->session_memcached->userdata['info_user']['mobileNo'],$this->session_memcached->userdata['info_user']['userID'], $transId,1);
									$data['listBankP2'] = $listBank;

								$this->view['content'] = $this->load->view('withdraw/withdraw_epurse_2', $data, true);
								$this->load->view('Layout/layout_iframe', array(
									'data' => $this->view
								));
							}
							
						}
						
						if(isset($post['widthdraw_met']) && $post['widthdraw_met'] == '3')
						{
							$data['post'] = $post;
							
							$dataWithdrawRedis['post_1'] = $data['post'];
							$dataWithdrawRedis['fee'] = $data['fee'];
							
							// load view B2: data = datapost buoc 1
							$data['listBankP2'] = $listBank;
							if($accInfo['security_method'] == '1')
							{
								$requestSendOtp = $this->megav_core_interface->genOTP($accInfo['email'], $accInfo['mobileNo'], $accInfo['userID'], $dataWithdrawRedis['transId']);
								$requestSendOtp = json_decode($requestSendOtp);
								if($requestSendOtp->status == STATUS_SUCCESS)
								{
									$loadView = 1;
									$data['sentOtp'] = 1;
									$data['step'] = 3;
                                    $data['userInfo'] = $accInfo;
									$this->view['content'] = $this->load->view('withdraw/withdraw_epurse_2', $data, true);
									$this->load->view('Layout/layout_iframe', array(
										'data' => $this->view
									));
								}
								else
								{
									$loadView = 1;
									$mess = lang('MVM_'.$requestSendOtp->status);
									$this->megav_libs->page_result($mess, 'withdraw');
								}
								
							}
							else
							{
								$loadView = 1;
								$data['step'] = 3;
                                $data['userInfo'] = $accInfo;
								$this->view['content'] = $this->load->view('withdraw/withdraw_epurse_2', $data, true);
								$this->load->view('Layout/layout_iframe', array(
									'data' => $this->view
								));
							}
						}
						
						$this->megav_libs->saveCookieUserData($dataWithdrawRedis['transId'], $dataWithdrawRedis);
					}
					else
					{
						$data['err_amount'] = "Số tiền yêu cầu rút quy định tối thiểu là " . number_format(WITHDRAW_MINIMUM_AMOUNT) . " đ, tối đa là " . number_format(WITHDRAW_MAXIMUM_AMOUNT) . ' đ';
						if($post['widthdraw_met'] == '2'){
							$tempType = WIDTHDRAW_FAST_TEMP;
						}elseif($post['widthdraw_met'] == '3'){
							$tempType = WIDTHDRAW_MAPPING_TEMP;
						}else{
							$tempType = '4';
						}
						$data['fee'] = $this->getFee($post['amount'],$tempType,$post['provider_code']);
					}
				}
				else
				{
					log_message('error', 'balance ' . print_r($data['balance'],true) . ' | amount ' . print_r($post['amount'], true) . ' | Fee: ' . print_r($data['fee'], true));
					//$loadView = 1;
					//$data['err_amount'] = "Số tiền rút không được lớn hơn số dư khả dụng";
					$data['err_amount'] = "Số dư không đủ";
					if($post['widthdraw_met'] == '2'){
						$tempType = WIDTHDRAW_FAST_TEMP;
					}elseif($post['widthdraw_met'] == '3'){
						$tempType = WIDTHDRAW_MAPPING_TEMP;
					}else{
						$tempType = '4';
					}
					$data['fee'] = $this->getFee($post['amount'],$tempType,$post['provider_code']);
				}
			}
			else
			{
				//echo $post['amount'];die;
				$errorForm = $this->form_validation->error_array();
				//log_message('error', 'validation: ' . print_r($arror, true));
				//if(!isset($errorForm['amount']))
					//$data['fee'] = $this->getFee($post['amount']);

					if($post['widthdraw_met'] == '2'){
						$tempType = WIDTHDRAW_FAST_TEMP;
					}elseif($post['widthdraw_met'] == '3'){
						$tempType = WIDTHDRAW_MAPPING_TEMP;
					}else{
						$tempType = '4';
					}
					$data['fee'] = $this->getFee($post['amount'],$tempType,$post['provider_code']);
			}
		}
		
		if(!isset($loadView))
		{
			$transId = $this->megav_libs->genarateTransactionId('GLB');
			
			//log_message('error', 'Withdraw || list provider ' . print_r($listBank, true));
			
			/*
			if($listBank)
				$data['listBank'] = $listBank;
			*/
			
			//log_message('error', 'Withdraw || list bank acc ' . print_r($listBankACC, true));
			
			//if($listBankACC)
			//	$data['listBankAcc'] = $listBankACC;
			
			
			$data['listBank'] = $uniqueBank;
			
			if($widthdraw_met != null)
				$data['post_act']['widthdraw_met'] = $widthdraw_met;
			else
				$data['post_act']['widthdraw_met'] = isset($post['widthdraw_met']) ? $post['widthdraw_met'] : 1;
			
			if($widthdraw_met == '2'){
				$tempType = WIDTHDRAW_FAST_TEMP;
			}elseif($widthdraw_met == '3'){
				$tempType = WIDTHDRAW_MAPPING_TEMP;
			}else{
				$tempType = WIDTHDRAW_TEMP;
			}
			//$data['feeTooltip'] = $this->getFee('100000',$tempType, '970457'); // lay phí fill vào tooltip
			
			$this->view['content'] = $this->load->view('withdraw/withdraw_epurse', $data, true);
			$this->load->view('Layout/layout_iframe', array(
				'data' => $this->view
			));
		}
	}
	
	public function withdraw_epurse_offline()
	{
		$data = array();
		$data['step'] = 3;
		$post = $this->input->post();
		$dataWithdrawRedis = $this->megav_libs->getDataTransRedis();
		$data['post'] = $dataWithdrawRedis['post_1'];
		
		$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																				$this->megav_libs->genarateAccessToken(), null, 1);
		
		if($post)
		{
			if($this->session_memcached->userdata['info_user']['security_method'] == '1')
			{
				$this->form_validation->set_rules('otp', 'OTP', 'required|trim|xss_clean|max_length[10]');
				$otp = $post['otp'];
				$passLv2 = "";
			}
			else
			{
				$this->form_validation->set_rules('passLv2', 'Mật khẩu cấp 2', 'required|trim|xss_clean|max_length[20]|min_length[6]');
				$passLv2 = $post['passLv2'];
				$otp = "";
			}
			
			if($this->form_validation->run() == true)
			{

				$accessToken = $this->megav_libs->genarateAccessToken();
				if($dataWithdrawRedis['post_1']['widthdraw_met'] == '1')
				{
					
					$merchantId = null;
					if(!empty($this->input->cookie("merchantId")))
						$merchantId = $this->input->cookie("merchantId");
					
					$widthdraw_megaV = $this->megav_core_interface->withdrawOffline($this->session_memcached->userdata['info_user']['userID'],
																					$this->session_memcached->userdata['info_user']['mobileNo'],
																					$this->session_memcached->userdata['info_user']['email'],
																					$dataWithdrawRedis['post_1']['provider_code'], 
																					$dataWithdrawRedis['post_1']['bankAcc'], 
																					$dataWithdrawRedis['post_1']['amount'], $otp, $passLv2, $accessToken, 
																					$dataWithdrawRedis['transId'], $merchantId);
					$widthdraw_megaV = json_decode($widthdraw_megaV);
					if(isset($widthdraw_megaV->status))
					{
						
						if($widthdraw_megaV->status == STATUS_SUCCESS)
						{
							
							$loadView = 1;
							$key3des = $this->megav_core_interface->getSessionKey();
							if(isset($widthdraw_megaV->data))
								$dataWithdraw = json_decode($this->megav_core_interface->decrypt3DES($widthdraw_megaV->data, $key3des), true);
							log_message('error', 'data withdraw: ' . print_r($dataWithdraw, true));
							
							$mess = "Khởi tạo yêu cầu rút tiền thành công. ";
							if(isset($dataWithdraw['trans_id']))
								$mess .= "Mã giao dịch tham chiếu " . $dataWithdraw['trans_id'] . ".";
							$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																									$this->megav_libs->genarateAccessToken(), null, null);
							$data['mess'] = $mess;
							
							$transDetail = $this->megav_core_interface->getTransList($this->session_memcached->userdata['info_user']['userID'], 2, 
																					$dataWithdraw['trans_id'], "", "", "", "", 0, 0, 1, $this->megav_libs->genarateTransactionId('GLT'), "");
							$data['detail'] = $transDetail->listTrans[0];
							$this->view['content'] = $this->load->view('withdraw/withdraw_epurse_offline', $data, true);
							$this->load->view('Layout/layout_iframe', array(
								'data' => $this->view
							));
							
							//$this->megav_libs->page_result($mess, '/withdraw', null, null, 1, null, null, null, $balance);
						}
						elseif($widthdraw_megaV->status == STATUS_WRONG_OTP)
						{
							$data['error_otp'] = 'Sai OTP';
							$data['sentOtp'] = 1;
							
						}
						elseif($widthdraw_megaV->status == STATUS_WRONG_PASSLV2)
						{
							$data['error_passLv2'] = 'Sai mật khẩu cấp 2';
						}
						elseif($widthdraw_megaV->status == STATUS_WRONG_AMOUNT)
						{
							$key3des = $this->megav_core_interface->getSessionKey();
							if(isset($widthdraw_megaV->data))
								$dataWithdraw = json_decode($this->megav_core_interface->decrypt3DES($widthdraw_megaV->data, $key3des), true);
							
							$loadView = 1;
							$mess = "Số tiền rút tối thiểu là " . number_format($dataWithdraw['minTransAmount']) . ' đ, tối đa là ' . number_format($dataWithdraw['maxTransAmount']) . ' đ';
							$this->megav_libs->page_result($mess, '/withdraw');
						}
						else
						{
							$loadView = 1;
							$this->megav_libs->page_result(lang('MVM_'.$widthdraw_megaV->status), '/withdraw');
						}
					}
					else
					{
						$loadView = 1;
						$this->megav_libs->page_result("Có lỗi trong quá trình rút tiền. Vui lòng thử lại.", '/withdraw');
						
					}
				}
				elseif($dataWithdrawRedis['post_1']['widthdraw_met'] == '2'){
					$merchantId = null;
					if(!empty($this->input->cookie("merchantId")))
						$merchantId = $this->input->cookie("merchantId");
					
					$widthdraw_megaV = $this->megav_core_interface->withdrawFast($this->session_memcached->userdata['info_user']['userID'],
																					$this->session_memcached->userdata['info_user']['mobileNo'],
																					$this->session_memcached->userdata['info_user']['email'],
																					$dataWithdrawRedis['post_1']['provider_code'], 
																					$dataWithdrawRedis['post_1']['bankAcc'], 
																					$dataWithdrawRedis['post_1']['amount'], $otp, $passLv2, $accessToken, 
																					$dataWithdrawRedis['transId'], $merchantId);
					$widthdraw_megaV = json_decode($widthdraw_megaV);

					if(isset($widthdraw_megaV->status))
					{
						
						if($widthdraw_megaV->status == STATUS_SUCCESS)
						{
							
							$loadView = 1;
							$key3des = $this->megav_core_interface->getSessionKey();
							if(isset($widthdraw_megaV->data))
								$dataWithdraw = json_decode($this->megav_core_interface->decrypt3DES($widthdraw_megaV->data, $key3des), true);
							log_message('error', 'data withdraw: ' . print_r($dataWithdraw, true));
							
							$mess = "Khởi tạo yêu cầu rút tiền thành công. ";
							if(isset($dataWithdraw['trans_id']))
								$mess .= "Mã giao dịch tham chiếu " . $dataWithdraw['trans_id'] . ".";
							$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																									$this->megav_libs->genarateAccessToken(), null, null);
							$data['mess'] = $mess;
							
							$transDetail = $this->megav_core_interface->getTransList($this->session_memcached->userdata['info_user']['userID'], 2, 
																					$dataWithdraw['trans_id'], "", "", "", "", 0, 0, 1, $this->megav_libs->genarateTransactionId('GLT'), "");
							$data['detail'] = $transDetail->listTrans[0];
							$this->view['content'] = $this->load->view('withdraw/withdraw_epurse_offline', $data, true);
							$this->load->view('Layout/layout_iframe', array(
								'data' => $this->view
							));
							
							//$this->megav_libs->page_result($mess, '/withdraw', null, null, 1, null, null, null, $balance);
						}
						elseif($widthdraw_megaV->status == STATUS_WRONG_OTP)
						{
							$data['error_otp'] = 'Sai OTP';
							$data['sentOtp'] = 1;
							
						}
						elseif($widthdraw_megaV->status == STATUS_WRONG_PASSLV2)
						{
							$data['error_passLv2'] = 'Sai mật khẩu cấp 2';
						}
						elseif($widthdraw_megaV->status == STATUS_WRONG_AMOUNT)
						{
							$key3des = $this->megav_core_interface->getSessionKey();
							if(isset($widthdraw_megaV->data))
								$dataWithdraw = json_decode($this->megav_core_interface->decrypt3DES($widthdraw_megaV->data, $key3des), true);
							
							$loadView = 1;
							$mess = "Số tiền rút tối thiểu là " . number_format($dataWithdraw['minTransAmount']) . ' đ, tối đa là ' . number_format($dataWithdraw['maxTransAmount']) . ' đ';
							$this->megav_libs->page_result($mess, '/withdraw');
						}
						else
						{
							$loadView = 1;
							$this->megav_libs->page_result(lang('MVM_'.$widthdraw_megaV->status), '/withdraw');
						}
					}
					else
					{
						$loadView = 1;
						$this->megav_libs->page_result("Có lỗi trong quá trình rút tiền. Vui lòng thử lại.", '/withdraw');
						
					}
				}
				elseif($dataWithdrawRedis['post_1']['widthdraw_met'] == '3')
				{
					$merchantId = null;
					if(!empty($this->input->cookie("merchantId")))
						$merchantId = $this->input->cookie("merchantId");
					
					$widthdraw_megaV = $this->megav_core_interface->withdrawMapping($this->session_memcached->userdata['info_user']['userID'],
																					$this->session_memcached->userdata['info_user']['mobileNo'],
																					$this->session_memcached->userdata['info_user']['email'],
																					$dataWithdrawRedis['post_1']['provider_code'], 
																					$dataWithdrawRedis['post_1']['amount'], $otp, $passLv2, $accessToken, 
																					$dataWithdrawRedis['transId'], $merchantId);
					$widthdraw_megaV = json_decode($widthdraw_megaV);
					if(isset($widthdraw_megaV->status))
					{
						
						if($widthdraw_megaV->status == STATUS_SUCCESS)
						{
							
							$loadView = 1;
							$key3des = $this->megav_core_interface->getSessionKey();
							if(isset($widthdraw_megaV->data))
								$dataWithdraw = json_decode($this->megav_core_interface->decrypt3DES($widthdraw_megaV->data, $key3des), true);
							log_message('error', 'data withdraw mapping: ' . print_r($dataWithdraw, true));
							
							$mess = "Rút tiền thành công. ";
							if(isset($dataWithdraw['trans_id']))
								$mess .= "Mã giao dịch tham chiếu " . $dataWithdraw['trans_id'] . ".";
							$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																									$this->megav_libs->genarateAccessToken(), null, null);
							$data['mess'] = $mess;
							
							$transDetail = $this->megav_core_interface->getTransList($this->session_memcached->userdata['info_user']['userID'], 2, 
																					$dataWithdraw['trans_id'], "", "", "", "", 0, 0, 1, $this->megav_libs->genarateTransactionId('GLT'), "");
							$data['detail'] = $transDetail->listTrans[0];
							$this->view['content'] = $this->load->view('withdraw/withdraw_epurse_offline', $data, true);
							$this->load->view('Layout/layout_iframe', array(
								'data' => $this->view
							));
							
							//$this->megav_libs->page_result($mess, '/withdraw', null, null, 1, null, null, null, $balance);
						}
						elseif($widthdraw_megaV->status == STATUS_WRONG_OTP)
						{
							$data['error_otp'] = 'Sai OTP';
							$data['sentOtp'] = 1;
							
						}
						elseif($widthdraw_megaV->status == STATUS_WRONG_PASSLV2)
						{
							$data['error_passLv2'] = 'Sai mật khẩu cấp 2';
						}
						elseif($widthdraw_megaV->status == STATUS_WRONG_AMOUNT)
						{
							$key3des = $this->megav_core_interface->getSessionKey();
							if(isset($widthdraw_megaV->data))
								$dataWithdraw = json_decode($this->megav_core_interface->decrypt3DES($widthdraw_megaV->data, $key3des), true);
							
							$loadView = 1;
							$mess = "Số tiền rút tối thiểu là " . number_format($dataWithdraw['minTransAmount']) . ' đ, tối đa là ' . number_format($dataWithdraw['maxTransAmount']) . ' đ';
							$this->megav_libs->page_result($mess, '/withdraw');
						}
						elseif($widthdraw_megaV->status == "99")
						{
							$balance = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																									$this->megav_libs->genarateAccessToken(), null, null);
							$loadView = 1;
							$mess = lang('MVM_'.$widthdraw_megaV->status);
							$this->megav_libs->page_result($mess, '/withdraw', null, null, null, null, null, null, $balance, null);
						}
						else
						{
							$loadView = 1;
							$this->megav_libs->page_result(lang('MVM_'.$widthdraw_megaV->status), '/withdraw');
						}
					}
					else
					{
						$loadView = 1;
						$this->megav_libs->page_result("Có lỗi trong quá trình rút tiền. Vui lòng thử lại.", '/withdraw');
						
					}
				}
			}
			else
			{
				if($this->session_memcached->userdata['info_user']['security_method'] == '1')
					$data['sentOtp'] = 1;
			}
		}
		
		
		if(!isset($loadView))
		{
			if($dataWithdrawRedis['post_1']['widthdraw_met'] == '1')
			{
				$transId = $this->megav_libs->genarateTransactionId('GLB');
				$listBank = $this->megav_core_interface->getProvider($transId);
			}
			elseif($dataWithdrawRedis['post_1']['widthdraw_met'] == '3')
			{
				$accessToken = $this->megav_libs->genarateAccessToken();
				$listBank = $this->megav_core_interface->getListBanksAcountMapping($this->session_memcached->userdata['info_user']['userID'], $accessToken);
			}
			else
			{
				$transId = $this->megav_libs->genarateTransactionId('GLB');
				$accessToken = $this->megav_libs->genarateAccessToken();
				//$listBank = $this->megav_core_interface->getListBanksAcountMapping($this->session_memcached->userdata['info_user']['userID'], $accessToken);
				$listBank = $this->megav_core_interface->getListBankAccount($this->session_memcached->userdata['info_user']['email'],$this->session_memcached->userdata['info_user']['mobileNo'],$this->session_memcached->userdata['info_user']['userID'], $transId,1);
			}
			$data['post_act']['widthdraw_met'] = $dataWithdrawRedis['post_1']['widthdraw_met'];
			$data['listBankP2'] = $listBank;
			$this->view['content'] = $this->load->view('withdraw/withdraw_epurse_2', $data, true);
			$this->load->view('Layout/layout_iframe', array(
				'data' => $this->view
			));
		}
	}
	
	
	public function get_fee_widthdraw()
	{		
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		$post = $this->input->post();
		log_message('error', 'data post get fee withdraw: ' . print_r($post, true));
		if($post)
		{
			$post['amount'] 	= $this->security->xss_clean($post['amount']);
			$post['withdraw_met'] 	= $this->security->xss_clean($post['withdraw_met']);
			$post['providerCode'] 	= $this->security->xss_clean($post['providerCode']);
			if($post['withdraw_met'] == '1')
			{
				$tempType = WIDTHDRAW_TEMP;
			}
			elseif($post['withdraw_met'] == '2')
			{
				$tempType = WIDTHDRAW_FAST_TEMP;
			}
			elseif($post['withdraw_met'] == '3')
			{
				$tempType = WIDTHDRAW_MAPPING_TEMP;
			}
			
			$transId = $this->megav_libs->genarateTransactionId('GLB');
			//$listfee = $this->megav_core_interface->getTempFee($this->session_memcached->userdata['info_user']['userID']);
			
			$listfee = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], null, $tempType);
			
			log_message('error', 'list tempfee mapping: ' . $tempType . "|" . print_r($listfee, true));
			
			$getAllProvider = $this->megav_core_interface->getProvider($transId);
			$bankId = "";
			foreach($getAllProvider as $provider){
				if(isset($provider->epurseBankCode) && $provider->epurseBankCode == $post['providerCode'])
				{
					$bankId = $provider->recId;
					break;
				}
			}
			
			foreach($listfee as $template)
			{
				if($template->providerId == $bankId)
				{
					$post['amount'] = str_replace(',', '', $post['amount']);
					$feeAmount = $template->fixDiscount + ($template->rateDiscount * $post['amount']/100);
					$feeAmountFormat = number_format($feeAmount);
					log_message('error', 'info fee: ' . print_r($template, true));
					log_message('error', 'fee mapping: ' . $feeAmountFormat);
					echo $feeAmountFormat;
				}
			}
		}
	}
	
	
	public function getFee($amount, $tempType , $bankcode)
	{
		log_message('error', 'get fee to view: ' . $tempType);
		$transId = $this->megav_libs->genarateTransactionId('GLB');
		//$listfee = $this->megav_core_interface->getTempFee($this->session_memcached->userdata['info_user']['userID']);
		$listfee = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], null, $tempType);
		log_message('error', 'template fee withdraw: ' . print_r($listfee, true));
		
		$getAllProvider = $this->megav_core_interface->getProvider($transId);
		$bankId = "";
		foreach($getAllProvider as $provider){
			if(isset($provider->epurseBankCode) && $provider->epurseBankCode == $bankcode)
			{
				$bankId = $provider->recId;
				break;
			}
		}
		
		foreach($listfee as $template)
		{
			if($template->providerId == $bankId)
			{
				$amount = str_replace(',', '', $amount);
				$feeAmount = $template->fixDiscount + ($template->rateDiscount * $amount/100);
				$feeAmountFormat = number_format($feeAmount);
				return $feeAmountFormat;
			}
		}
		
		//foreach($listfee as $template)
		//{
		//	if ($tempType=='') {
		//		$tempType = WIDTHDRAW_TEMP;
		//		if($template->templateType == $tempType){
		//			$amount = str_replace(',', '', $amount);
		//			$feeAmount = $template->fixFee + $template->fixRate * $amount;
		//			return $feeAmount;
		//		}
		//	}else{
		//		if($template->templateType == $tempType){
		//			$amount = str_replace(',', '', $amount);
		//			$feeAmount = $template->fixFee + ($template->fixRate * $amount/100);
		//			//$feeAmountFormat = number_format($feeAmount);
		//			return $feeAmount;
		//		}
		//	}
		//	
		//}
		return 0;
	}

	public function withdrawGetAjaxBankAcc(){
		log_message('error', 'data post: ' . print_r($_POST, true));
		if (isset($_POST['code']) && $_POST['code'] !='') {
			$transId = $this->megav_libs->genarateTransactionId('GLB');
			//$listBank = $this->megav_core_interface->getProvider($transId);

			$_POST['code'] 	= $this->security->xss_clean($_POST['code']);
			$_POST['withdraw'] 	= $this->security->xss_clean($_POST['withdraw']);
			if (isset($_POST['withdraw']) && $_POST['withdraw'] == '2') {
				$listBankAcc = $this->megav_core_interface->getListBankAccount($this->session_memcached->userdata['info_user']['email'],
																		$this->session_memcached->userdata['info_user']['mobileNo'],
																		$this->session_memcached->userdata['info_user']['userID'], $transId,1);


			}else{
				$listBankAcc = $this->megav_core_interface->getListBankAccount($this->session_memcached->userdata['info_user']['email'],
																		$this->session_memcached->userdata['info_user']['mobileNo'],
																		$this->session_memcached->userdata['info_user']['userID'], $transId);
			}

			log_message('error', 'withdrawGetAjaxBankAcc | list bak' . print_r($listBankAcc, true));
			$arr_acc_new = array();
			foreach ($listBankAcc as $key => $value) {
				log_message('error', 'withdrawGetAjaxBankAcc | bak' . print_r($value, true));
				if (isset($value->bankCode) && $value->bankCode == trim(addslashes($_POST['code']))) {
					$arr_acc_new[] = $listBankAcc[$key];
				}
			}
			if (isset($_POST['withdraw']) && $_POST['withdraw'] == '2') {
				$html = '<option value="">Chọn số thẻ</option>';
			}else{
				$html = '<option value="">Chọn tài khoản</option>';
			}
			if (!empty($arr_acc_new)) {

				foreach ($arr_acc_new as $bank) {
					$html .= '<option value="'.$bank->bankAccount.'" '.set_select('bankAcc', $bank->bankAccount, False).'>'.$bank->bankAccount.'</option>';
				}
			}


			$data = array(
				'status' => true,
				'html'		=> $html
			);
			echo json_encode($data);
		}
		

	}
	
	public function check_amount($amount){
		$amount = (int)trim(str_replace(',', '', $amount));
		$balance = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																		$this->megav_libs->genarateAccessToken(), null, 1);
		if ($amount<=0) {
            $this->form_validation->set_message("check_amount","Số nhập vào phải lớn hơn 0!");
            return FALSE;
        }
        if($amount>$balance){
            $this->form_validation->set_message("check_amount","Số tiền rút phải nhỏ hơn hoặc bằng số dư khả dụng?");
            return FALSE;
        }else{
            return TRUE;
        }
    }
	
	public function get_list_bank_for_withdraw_method()
	{
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		$post = $this->input->post();
		log_message('error', 'data post get list bank: ' . print_r($post, true));
		if($post)
		{
			$post['widthdraw_met'] 	= $this->security->xss_clean($post['widthdraw_met']);
			if($post['widthdraw_met'] == '1')
			{
				$transId = $this->megav_libs->genarateTransactionId('GLB');
				$listBankACC = $this->megav_core_interface->getListBankAccount($this->session_memcached->userdata['info_user']['email'],
																			$this->session_memcached->userdata['info_user']['mobileNo'],
																			$this->session_memcached->userdata['info_user']['userID'], $transId);
				$listBank = $this->megav_core_interface->getProvider($transId);
				$bankProvider = array();
				foreach($listBankACC as &$bankAcc){
					
					foreach($listBank as $bank){
						//log_message('error', 'Withdraw || bank acc ' . print_r($bankAcc, true));
						if(isset($bankAcc->bankCode) && $bankAcc->bankCode == $bank->providerCode){
							$bankAcc->recId = $bank->recId;
							//log_message('error', 'Mapp Bank: ' . print_r($bankAcc, true));
							$bankProvider[] = $bankAcc;
						}
					}
				}
				
				$uniqueBank = array();
				foreach ($bankProvider as $object) {
					if (isset($uniqueBank[$object->recId])) {
						continue;
					}
					$uniqueBank[$object->recId] = $object;
				}
			}
			elseif($post['widthdraw_met'] == '2'){
				$listBank = $this->megav_core_interface->getProvider($transId, SUB_WITHDRAW_TYPE_FAST);

				$listBank_info_user = $this->megav_core_interface->getListBankAccount($this->session_memcached->userdata['info_user']['email'],$this->session_memcached->userdata['info_user']['mobileNo'],$this->session_memcached->userdata['info_user']['userID'], $transId,1);
				$uniqueBank = array();
				if ($listBank) {
					foreach ($listBank_info_user as $k => $v) {
						if($v->status != '99')
						{
							foreach ($listBank as $key => $value) {
								if ($v->bankCode==$value->epurseBankCode) {
									$uniqueBank[] = $value;
								}
							}
						}
					}
				}
			}
			elseif($post['widthdraw_met'] == '3')
			{
				$accessToken = $this->megav_libs->genarateAccessToken();
				$uniqueBank = $this->megav_core_interface->getListBanksAcountMapping($this->session_memcached->userdata['info_user']['userID'], $accessToken);
								
			}
			
			$option = '<option value="">Chọn ngân hàng</option>';
			if(isset($uniqueBank))
			{
				foreach($uniqueBank as $bank)
				{
					log_message('error', 'bank acc: ' . print_r($bank, true));
					if($post['widthdraw_met'] == '1')
					{
						$bank_id = $bank->recId;
						$bankcode = $bank->bankCode;
						$bankName = $bank->bankName;
					}
					elseif($post['widthdraw_met'] == '2'){
						$bank_id = $bank->epurseBankCode;
						$bankcode = $bank->epurseBankCode;
						$bankName = $bank->providerName;
					}
					elseif($post['widthdraw_met'] == '3')
					{
						$bank_id = $bank->bankcode;
						$bankcode = $bank->bankcode;
						$bankName = $bank->bankName;
					}
					$option .= "<option value='" .$bank_id . "' data-code='" . $bankcode . "' data-check='".$post['widthdraw_met']."'>" . $bankName . "</option>" ;
				}
			}
			echo $option;
		}
	}
}
?>