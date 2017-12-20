<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class payment_bill extends CI_Controller
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


    public function start()
	{
		$data = array();
		if(!isset($loadView)){					
			//$this->load->view('payment_epurse/index', $data);
			$this->view['content'] = $this->load->view('payment_bill/start', $data, true);
			$this->load->view('Layout/layout_iframe', array(
				'data' => $this->view
			));
			
		}
	}

    
	public function index($type=null){
		$data = array();
		if ($type!=null) {
			$data['type_tel'] = $type;
		}
		$transId = $this->megav_libs->genarateTransactionId('CTH');
		$data['balance_check'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																					$this->megav_libs->genarateAccessToken(), null, 1);
		$listTelco = $this->megav_core_interface->getProvider($transId);
		
		$post = $this->input->post();
		if($post)
		{
			
			$this->form_validation->set_rules('bill_code', 'Mã hợp đồng', 'trim|required|min_length[1]|max_length[22]|xss_clean');//max_length[10]|
			$this->form_validation->set_rules('provider_code', 'Nhà cung cấp mã thẻ', 'trim|required|xss_clean');
			//$this->form_validation->set_rules('amount', 'Mệnh giá', 'trim|required|xss_clean');
			if($this->form_validation->run() == true)
			{
				$contractId = $this->input->post('bill_code');
				$providerCode = $this->input->post('provider_code');

				$data['info'] = $this->getBillInfo($contractId,$providerCode);

				if (!empty($data['info'])) {
						$data['post'] = $post;
				
						$transId = $this->megav_libs->genarateTransactionId('BLC');
						$dataTopupRedis = array('transId' => $transId, 'post' => $post);
						$this->megav_libs->saveCookieUserData($dataTopupRedis['transId'], $dataTopupRedis);
						$loadView = 1;
						$this->view['content'] = $this->load->view('payment_bill/payment_bill', $data, true);
						$this->load->view('Layout/layout_iframe', array(
							'data' => $this->view
						));
				}else{
					$data['info_error'] = 'Mã hợp đồng không tồn tại.';
				}	
				
			}
			$data['type_tel'] = $post['provider_code'];
		}
		
		if($listTelco)
			$data['listTelco'] = $listTelco;
		
		if(!isset($loadView)){					
			//$this->load->view('payment_epurse/index', $data);
			$this->view['content'] = $this->load->view('payment_bill/index', $data, true);
			$this->load->view('Layout/layout_iframe', array(
				'data' => $this->view
			));
			
		}
	}
	
	public function payment_topup()
	{
		$post = $this->input->post();
		$data = array();
		
		$dataPaymentTopupRedis = $this->megav_libs->getDataTransRedis();
		$data['post'] = $dataPaymentTopupRedis['post'];
				
		if($post){
			
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
				// payment topup
				
				$accessToken = $this->megav_libs->genarateAccessToken();
				$topup_megaV = $this->megav_core_interface->paymentTopupToPhone($this->session_memcached->userdata['info_user']['userID'],
																		$this->session_memcached->userdata['info_user']['mobileNo'],
																		$this->session_memcached->userdata['info_user']['email'],
																		$dataPaymentTopupRedis['post']['amount'], 
																		$otp, $passLv2, $dataPaymentTopupRedis['post']['acc_game'], 
																		$dataPaymentTopupRedis['post']['provider_code'], $accessToken,
																		$dataPaymentTopupRedis['transId']);
				$topup_megaV = json_decode($topup_megaV);
				if(isset($topup_megaV->status))
				{
					if($topup_megaV->status == STATUS_SUCCESS)
					{
						$loadView = 1;
						$key3des = $this->megav_core_interface->getSessionKey();
						if(isset($topup_megaV->data))
							$dataTopup = json_decode($this->megav_core_interface->decrypt3DES($topup_megaV->data, $key3des), true);
						log_message('error', 'data buy card: ' . print_r($dataTopup, true));
						
						
						$mess = "Giao dịch nạp điện thoại thành công. ";
						if(isset($dataTopup['trans_id']))
							$mess .= "Mã giao dịch tham chiếu " . $dataTopup['trans_id'];
						
						$this->megav_libs->page_result($mess, '/payment_bill', null, null, 1, 'Về trang nạp điện thoại', '/transaction_history', 'Xem lịch sử giao dịch');
						
					}
					elseif($topup_megaV->status == STATUS_WRONG_OTP)
					{
						$data['error_otp'] = 'Sai OTP';
						$data['sentOtp'] = 1;
						
					}
					elseif($topup_megaV->status == STATUS_WRONG_PASSLV2)
					{
						$data['error_passLv2'] = 'Sai mật khẩu cấp 2';
					}
					else
					{
						$loadView = 1;
						$this->megav_libs->page_result(lang('MVM_'.$topup_megaV->status), '/payment_bill');
					}
				}
				else
				{
					$loadView = 1;
					$this->megav_libs->page_result("Có lỗi trong quá trình nạp tiền game. Vui lòng thử lại.", '/payment_bill');
					
				}
			}
			else
			{
				$this->load->view('payment_bill/payment_topup', $data);
			}
		}
		
		if(!isset($loadView))
		{
			$this->load->view('payment_bill/payment_topup', $data);
		}
	}
	public function getBillInfo($contractId,$providerCode){
		$paymentRequest = $this->megav_core_interface->getPaymentBill($contractId, $providerCode);

		if (!empty($paymentRequest)) {
			if ($paymentRequest->status==00) {
				$billInfo = json_decode($paymentRequest->billInfo,true);
				$customerInfo = json_decode($paymentRequest->customerInfo,true);
				
				$data = array(
					'transId' => $billInfo['BillId'],
					'constructId' => $billInfo['ContractNo'],
					'fullname' => $customerInfo['CustomerName'],
					'currentPaymentNeed' => number_format($billInfo['CurrentAmountNeedPayment']),
					'currentPaymentExpire' => $billInfo['CurrentPaymentExpire']
				);
				return $data;
			}else{
				return false;
			}
		}else{
			return false;
		}
		
		
	}

	public function senOTPPaymentCheckOut(){
		// genotp
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		if (isset($_POST['check'])) {
				$transData = $_POST['data'];
				$transData['amount'] 			= $this->security->xss_clean($transData['amount']);
				$transData['amount_curent'] 	= $this->security->xss_clean($transData['amount_curent']);
				$transData['provider_code'] 	= $this->security->xss_clean($transData['provider_code']);



				$transData['amount'] = str_replace(',', '', trim($transData['amount']));
				$transData['amount_curent'] = str_replace(',', '', trim($transData['amount_curent']));
				//$balance = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID']);
				if (!is_numeric($transData['amount_curent']) || $transData['amount_curent']<=0) {
					$data = array(
						'status'=> false,
						'error'=> 4,
						'mess'  => 'Bạn phải nhập vào là số và lớn hơn 0.'
					);
					echo json_encode($data);
				//}elseif (!is_numeric($transData['amount']) || $transData['amount']<=0) {
				//	$data = array(
				//		'status'=> false,
				//		'error'=> 5,
				//		'mess'  => 'Bạn phải nhập vào là số và lớn hơn 0.'
				//	);
				//	echo json_encode($data);
				}elseif ($transData['provider_code']=='FE_CREDIT' && ($transData['amount_curent']<1000 || $transData['amount_curent']>100000000)) {
					$data = array(
						'status'=> false,
						'error'=> 6,
						'mess'  => 'Số tiền bạn nhập phải trong khoảng từ 1,000 đến 100,000,000.'
					);
					echo json_encode($data);
				}elseif ($transData['provider_code']=='HOME_CREDIT' && ($transData['amount_curent']<50000 || $transData['amount_curent']>$transData['amount'])) {
					$data = array(
						'status'=> false,
						'error'=> 6,
						'mess'  => 'Số tiền bạn nhập phải trong khoảng từ 50,000 đến '. number_format(trim($transData['amount'])) .'.'
					);
					echo json_encode($data);

					/*
					elseif ($balance < $transData['amount_curent']) {
						$data = array(
							'status'=> false,
							'error'=> 6,
							'mess'  => 'Số tiền bạn nhập đang lớn hơn số dư khả dụng của bạn.'
						);
						echo json_encode($data);
					}
					*/


				}else{

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
														$data['sentOtp'] = 1;

														$data = array(
															'status' => true,
															'otp_pass' => 1,
															'mess'   => 'Hệ thống đã gửi OTP đến số điện thoại của bạn.',
															'phone'   => substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 0, (strlen($this->session_memcached->userdata['info_user']['mobileNo']) - 4)),
															'transId' => $transId
														);

														echo json_encode($data);
														
														$redis = new CI_Redis();
														$dataReset = array('sec_met' => $post['sec_met'],'transid' => $transId);
														$redis->set($transId, json_encode($dataReset));
														unset($dataReset);
														
														$session = $this->input->cookie("megav_session");
														$session = $this->megav_libs->_unserialize($session);
														$session['user_data'] = $transId;
														$this->session_memcached->_set_cookie($session);
														unset($session);
													}
													else
													{
														$data = array(
															'status' => false,
															'mess'   => 'Gửi OTP thất bại.'
														);

														echo json_encode($data);
													}
												}
												else
												{
													$mess = "Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
													$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
													$data = array(
														'status' => false,
														'mess'   => $mess
													);

													echo json_encode($data);
												}
											}
											else
											{					$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
												$data = array(
													'status' => false,
													'mess'   => $mess
												);

												echo json_encode($data);
											}
						}else{
							$data = array(
								'status' => true,
								'otp_pass' => 2,
								'mess'   => 'Nhập mật khẩu cấp 2.',
								'transId' => $transId
							);

							echo json_encode($data);
						}

				}

				
				
		}
		
	}
	public function reqPaymentCheckOut(){
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }

		$transData = $_POST['data'];
		$transData['amount'] 			= $this->security->xss_clean($transData['amount']);
		$transData['amount_curent'] 	= $this->security->xss_clean($transData['amount_curent']);
		$transData['provider_code'] 	= $this->security->xss_clean($transData['provider_code']);
		$transData['constructId'] 		= $this->security->xss_clean($transData['constructId']);
		$transData['fullname'] 			= $this->security->xss_clean($transData['fullname']);
		$transData['transId'] 			= $this->security->xss_clean($transData['transId']);
		$transData['requestId'] 		= $this->security->xss_clean($transData['requestId']);
		$transData['check'] 			= $this->security->xss_clean($transData['check']);


		$transData['amount'] = str_replace(',', '', trim($transData['amount']));
		$transData['amount_curent'] = str_replace(',', '', trim($transData['amount_curent']));
		//$balance = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID']);
		if (!is_numeric($transData['amount_curent']) || $transData['amount_curent']<=0) {
			$data = array(
				'status'=> 4,
				'mess'  => 'Bạn phải nhập vào là số và lớn hơn 0.'
			);
			echo json_encode($data);
		}elseif (!is_numeric($transData['amount']) || $transData['amount']<=0) {
			$data = array(
				'status'=> 5,
				'mess'  => 'Bạn phải nhập vào là số và lớn hơn 0.'
			);
			echo json_encode($data);
		}elseif ($transData['provider_code']=='FE_CREDIT' && ($transData['amount_curent']<1000 || $transData['amount_curent']>100000000)) {
			$data = array(
				'status'=> 6,
				'mess'  => 'Số tiền bạn nhập phải trong khoảng từ 1,000 đến 100,000,000.'
			);
			echo json_encode($data);
		}elseif ($transData['provider_code']=='HOME_CREDIT' && ($transData['amount_curent']<50000 || $transData['amount_curent']>$transData['amount'])) {
			$data = array(
				'status'=> 6,
				'mess'  => 'Số tiền bạn nhập phải trong khoảng từ 50,000 đến '. number_format(trim($transData['amount'])) .'.'
			);
			echo json_encode($data);
			/*
			elseif ($balance < $transData['amount_curent']) {
				$data = array(
					'status'=> false,
					'error'=> 6,
					'mess'  => 'Số tiền bạn nhập đang lớn hơn số dư khả dụng của bạn.'
				);
				echo json_encode($data);
			}
			*/
		}else{
			// ($paymentId, $userName, $phone, $email, $accessToken, $transId)
			$accessToken = $this->megav_libs->genarateAccessToken();

			$merchantId = null;
			if(!empty($this->input->cookie("merchantId")))
				$merchantId = $this->input->cookie("merchantId");
			
			$acceptPayment = $this->megav_core_interface->acceptBillPayment($transData['constructId'], $transData['provider_code'] ,
																		$this->session_memcached->userdata['info_user']['userID'],
																		$this->session_memcached->userdata['info_user']['mobileNo'],
																		$this->session_memcached->userdata['info_user']['email'],$transData['amount_curent'],
																		isset($transData['check']) && $transData['check']==1 ? $transData['otp_lv'] : '', isset($transData['check']) && $transData['check'] ==2 ? $transData['pass_lv'] : '',
																		$accessToken, $transData['transId'],$transData['requestId'], $merchantId);

			$respone = json_decode($acceptPayment);


			if (isset($respone->status)) {
				if ($respone->status=='00') {
					$key3des = $this->megav_core_interface->getSessionKey();
					if(isset($respone->data)){
						$dataPayment = json_decode($this->megav_core_interface->decrypt3DES($respone->data, $key3des), true);
					}
					$newPay = json_decode($dataPayment['paymentInfo']);
					$data = array(
						'status'=> 1,
						'balance' => number_format($dataPayment['balance_after']),
						'referenceId' => $dataPayment['trans_id'],//$newPay->ReferenceId,
						'mess'  => 'Thanh toán thành công. Mã giao dịch tham chiếu '.$dataPayment['trans_id']
					);
					echo json_encode($data);
				}elseif($respone->status=='99'){
					$key3des = $this->megav_core_interface->getSessionKey();
					if(isset($respone->data)){
						$dataPayment = json_decode($this->megav_core_interface->decrypt3DES($respone->data, $key3des), true);
					}
					$newPay = json_decode($dataPayment['paymentInfo']);
					$data = array(
						'status'=> 9,
						'referenceId' => $dataPayment['trans_id'],//$newPay->ReferenceId,
						'mess'  => lang('MVM_'.$respone->status)
					);
					echo json_encode($data);

				}elseif($respone->status=='24'){
					$data = array(
						'status'=> 2,
						'mess'  => 'Mật khẩu cấp 2 của bạn nhập sai.'
					);
					echo json_encode($data);

				}elseif($respone->status=='08'){
					$data = array(
						'status'=> 3,
						'mess'  => 'Mã xác nhận của bạn nhập sai.'
					);
					echo json_encode($data);

				}elseif($respone->status=='33'){
					$data = array(
						'status'=> 0,
						'mess'  => lang('MVM_'.$respone->status)
					);
					echo json_encode($data);

				}else{
					$data = array(
						'status'=> 0,
						'mess'  => lang('MVM_'.$respone->status)
					);
					echo json_encode($data);
				}
			}else{
				$data = array(
					'status'=> 0,
					'mess'  => 'Thanh toán thất bại.Vui lòng thử lại sau.'
				);
				echo json_encode($data);
			}
		}
		
	}

	
	public function get_provider_code()
	{
		$post = $this->input->post();
		if($post && isset($post['phone']))
		{
			$phone = $this->security->xss_clean($post['phone']);
			
			$viettel_10 = array('098', '097', '096', '086');
			$viettel_11 = array('0162', '0163', '0164', '0165', '0166', '0167', '0168', '0169');
			
			$vina_10 = array('091', '094', '088');
			$vina_11 = array('0123', '0124', '0125', '0127', '0129');
			
			$mobi_10 = array('090', '093', '089');
			$mobi_11 = array('0120', '0121', '0122', '0126', '0128');
			
			switch(strlen($phone))
			{
				case 10:
					if(in_array(substr($phone, 0, 3), $viettel_10))
					{
						echo 'VTT';
					}
					if(in_array(substr($phone, 0, 3), $vina_10))
					{
						echo 'VNP';
					}
					if(in_array(substr($phone, 0, 3), $mobi_10))
					{
						echo 'VMS';
					}
				break;
				
				case 11:
					if(in_array(substr($phone, 0, 4), $viettel_11))
					{
						echo 'VTT';
					}
					if(in_array(substr($phone, 0, 4), $vina_11))
					{
						echo 'VNP';
					}
					if(in_array(substr($phone, 0, 4), $mobi_11))
					{
						echo 'VMS';
					}
				break;
				
				default:
					echo 'false';
			}
		}
	}
}
?>