<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ec_payment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session_memcached');
        $this->load->helper('cookie');
        $this->load->library('redis');
        $this->load->library('authen_interface');
		$this->lang->load('megav_message');
		$this->load->library('megav_core_interface');
		$this->load->library('megav_libs');
		
		delete_cookie("addBank");
		
		for($retry = 1; $retry <= RETRY_GET_UINFO_REDIS; $retry++)
		{
			$this->session_memcached->get_userdata();
			if(isset($this->session_memcached->userdata['info_user']['userID']))
				break;
		}
		//$this->session_memcached->get_userdata();
		if(!isset($this->session_memcached->userdata['info_user']['userID']))
		{
			$url = current_url() . '?' . $_SERVER['QUERY_STRING'];
			if($this->input->cookie("megav_session"))
			{
				log_message('error', 'FUCK');
				$this->megav_libs->saveSourceUrl($url);
				//redirect();
				echo "<script>window.top.location='" . base_url() . "'</script>";
				die;
			}
			else
			{
				log_message('error', 'shit');
				redirect($url);
			}
		}		
    }

    
	public function index()
	{
		parse_str(urldecode($_SERVER['QUERY_STRING']), $_GET);
		$get = $this->input->get();
		$data = array();
		$data['userInfo'] = $this->session_memcached->userdata['info_user'];
		//$redis = new CI_Redis();
		// B1: nhan request tren link
		if(!empty($get) && isset($get['ref']))
		{
			
			log_message('error', 'Request EC Merchant: ' . print_r($get['ref'], true));
			if($get['ref'] == strip_tags($get['ref']))
			{
				$requestData = json_decode($get['ref'], true);
				$merchantId = isset($requestData['merchantId']) ? trim($requestData['merchantId']) : '';
				$signature = isset($requestData['signature']) ? trim($requestData['signature']) : '';
				$clientType = isset($requestData['clientType']) ? trim($requestData['clientType']) : '';
				$dataECPayment = isset($requestData['data']) ? trim($requestData['data']) : '';
				
				$transId = $this->megav_libs->genarateTransactionId('ECPM');
				$paymentInfo = $this->megav_core_interface->ECPayment($merchantId, $signature, $dataECPayment, 
																	$this->session_memcached->userdata['info_user']['userID'], $transId);
				
				$paymentInfo = json_decode($paymentInfo);
				if(isset($paymentInfo->status))
				{
					$dataPayment = '';
					$key3des = $this->megav_core_interface->getSessionKey();
					if(isset($paymentInfo->data))
						$dataPayment = json_decode($this->megav_core_interface->decrypt3DES($paymentInfo->data, $key3des), true);
					
					log_message('error', 'Merchant Info 1: ' . print_r($dataPayment, true));
					if($paymentInfo->status == STATUS_SUCCESS)
					{
						$loadView = 1;
						$data['merchantInfo'] = isset($dataPayment['merchantInfo']) ? $dataPayment['merchantInfo'] : '';
						$data['paymentInfo'] = isset($dataPayment['ecPayment']) ? $dataPayment['ecPayment'] : '';
						
						$dataPaymentRedis = array('transId' 		=> $transId, 
												  'paymentId' 		=> $dataPayment['ecPayment']['requestId'],
												  'merchantInfo' 	=> $data['merchantInfo'],
												  'clientType' 		=> $clientType,
												  'paymentInfo' 	=> $data['paymentInfo']);
						$this->megav_libs->saveCookieUserData($transId, $dataPaymentRedis, 'ecpayment');
					}
					elseif($paymentInfo->status == STATUS_BONUS_NOT_ENOUGH_MONEY)
					{
						$loadView = 1;
						$data['notEnoughMoney'] = 1;
						$data['redirect_link'] = isset($dataPayment['redirect_url']) ? $dataPayment['redirect_url'] : '';
						$data['timeRedirect'] = EC_TIME_REDIRECT_NOT_ENOUGH_MONEY;
						$data['merchantInfo'] = isset($dataPayment['merchantInfo']) ? $dataPayment['merchantInfo'] : '';
						$data['paymentInfo'] = isset($dataPayment['ecPayment']) ? $dataPayment['ecPayment'] : '';
						$data['message'] = "Số dư không đủ, thanh toán thất bại. Hệ thống sẽ tự động chuyển về trang của merchant sau <b id='countdown_text'></b> giây. Nếu không muốn chờ đợi, vui lòng click <a href='" . $data['redirect_link'] . "'>Quay lại</a>";
					}
					elseif($paymentInfo->status == STATUS_INVALID_MERCHANTID)
					{
						$this->megav_libs->page_result(lang('MVM_'.$paymentInfo->status), '/transaction_manage', null, null, null, null, null, null, null, 1);
					}
					else
					{
						redirect($dataPayment['redirect_url']);
						//$this->megav_libs->page_result(lang('MVM_'.$paymentInfo->status), '/');
					}
				}
				else
				{
					$this->megav_libs->page_result("Có lỗi trong quá trình thanh toán. Vui lòng thử lại.", '/transaction_manage', null, null, null, null, null, null, null, 1);
				}
			}
			else
			{
				$this->megav_libs->remove_data();
				redirect();
				// logout
			}
				
		}
		
		$post = $this->input->post();
		// B2: Xác nhận thanh toán
		if($post)
		{
			log_message('error', 'EC merchant: data post: ' . print_r($post, true));
			// đồng ý thanh toán: load form nhập OTP hoặc Mật khẩu cấp 2
			if(isset($post['accept']))
			{
				//$dataPayment = $this->megav_libs->getDataTransRedis('ecpayment');
				//log_message('error', 'data payment in redis: ' . print_r($dataPayment, true));
				for($retry = 1; $retry <= RETRY_GET_UINFO_REDIS; $retry++)
				{
					$dataPayment = $this->megav_libs->getDataTransRedis('ecpayment');
					log_message('error', 'data transaction in redis lan: ' . $retry . ' | data : ' . print_r($dataPayment, true));
					
					if(isset($dataPayment['paymentId']))
						break;
				}
				
				if($dataPayment)
				{
					// send otp
					if($this->session_memcached->userdata['info_user']['security_method'] == '1')
					{
						$requestSendOtp = $this->megav_core_interface->genOTP($this->session_memcached->userdata['info_user']['email'], 
																		$this->session_memcached->userdata['info_user']['mobileNo'], 
																		$this->session_memcached->userdata['info_user']['userID'], 
																		$dataPayment['transId']);
					
						$requestSendOtp = json_decode($requestSendOtp);
						if(isset($requestSendOtp->status))
						{
							if($requestSendOtp->status == STATUS_SUCCESS)
							{
								$loadView = 1;
								$data['SendOTP'] = 1;
								$data['btnPayment'] = 1;
								
								$data['merchantInfo'] = isset($dataPayment['merchantInfo']) ? $dataPayment['merchantInfo'] : '';
								$data['paymentInfo'] = isset($dataPayment['paymentInfo']) ? $dataPayment['paymentInfo'] : '';
							}
							else
							{
								$this->megav_libs->page_result(lang('MVM_'.$requestSendOtp->status), '/transaction_manage', null, null, null, null, null, null, null, 1);
							}
						}
						else
						{
							$mess = "Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
							$this->megav_libs->page_result($mess, '/transaction_manage', null, null, null, null, null, null, null, 1);
						}
					}
					elseif($this->session_memcached->userdata['info_user']['security_method'] == '2')
					{
						$loadView = 1;
						$data['passLv2'] = 1;
						$data['btnPayment'] = 1;
						$data['merchantInfo'] = isset($dataPayment['merchantInfo']) ? $dataPayment['merchantInfo'] : '';
						$data['paymentInfo'] = isset($dataPayment['paymentInfo']) ? $dataPayment['paymentInfo'] : '';
					}
					else
					{
						log_message('error', 'Hình thức bảo mật: ' . $this->session_memcached->userdata['info_user']['userID'] . ' | ' .$this->session_memcached->userdata['info_user']['security_method']);
						redirect();
					}
						
				}
				else
				{
					redirect();
				}
			}
			
			// từ chối giao dịch
			if(isset($post['reject']))
			{
				//$transData = $this->megav_libs->getDataTransRedis('ecpayment');
				
				for($retry = 1; $retry <= RETRY_GET_UINFO_REDIS; $retry++)
				{
					$transData = $this->megav_libs->getDataTransRedis('ecpayment');
					log_message('error', 'data transaction in redis lan: ' . $retry . ' | data : ' . print_r($transData, true));
					
					if(isset($transData['paymentId']))
						break;
				}
				
				$rejectPayment = $this->megav_core_interface->rejectEcPayment($transData['paymentId'], $transData['transId']);
				$respone = json_decode($rejectPayment);
				if(isset($respone->status))
				{
					if($respone->status == STATUS_SUCCESS)
					{
						// redirect
						$dataPayment = '';
						$key3des = $this->megav_core_interface->getSessionKey();
						if(isset($respone->data))
							$dataPayment = json_decode($this->megav_core_interface->decrypt3DES($respone->data, $key3des), true);
						
						log_message('error', 'reject payment: ' . print_r($dataPayment, true));
						redirect($dataPayment['redirect_url']);
						
					}
					else
					{
						$this->megav_libs->page_result(lang('MVM_'.$respone->status), '/transaction_manage', null, null, null, null, null, null, null, 1);
					}
				}
				else
				{
					$mess = "Có lỗi trong quá trình từ chối thanh toán. Vui lòng thử lại.";
					$this->megav_libs->page_result($mess, '/transaction_manage', null, null, null, null, null, null, null, 1);
				}
				
			}
			
			// xác nhận thanh toán : nhập OTP hoặc mật khẩu cấp 2
			if(isset($post['payment']))
			{
				if($this->session_memcached->userdata['info_user']['security_method'] == '1')
				{
					$this->form_validation->set_rules('otp', 'OTP', 'trim|required|xss_clean|max_length[10]');
					$data['SendOTP'] = 1;
				}
				else
				{
					$this->form_validation->set_rules('passLv2', 'Mật khẩu cấp 2', 'trim|required|xss_clean|max_length[20]|min_length[6]');
					$data['passLv2'] = 1;
				}
				
				
				
				for($retry = 1; $retry <= RETRY_GET_UINFO_REDIS; $retry++)
				{
					$transData = $this->megav_libs->getDataTransRedis('ecpayment');
					log_message('error', 'data transaction in redis lan: ' . $retry . ' | data : ' . print_r($transData, true));
					
					if(isset($transData['paymentId']))
						break;
				}
				
				
				
				if($this->form_validation->run() === true) 
				{
					
					// ($paymentId, $userName, $phone, $email, $accessToken, $transId)
					$accessToken = $this->megav_libs->genarateAccessToken();
					$acceptPayment = $this->megav_core_interface->acceptEcPayment($transData['paymentId'], 
																				$this->session_memcached->userdata['info_user']['userID'],
																				$this->session_memcached->userdata['info_user']['mobileNo'],
																				$this->session_memcached->userdata['info_user']['email'],
																				isset($post['otp']) ? $post['otp'] : '', isset($post['passLv2']) ? $post['passLv2'] : '',
																				$accessToken, $transData['transId']);
					
					$respone = json_decode($acceptPayment);
					if(isset($respone->status))
					{
						$dataPayment = '';
						$key3des = $this->megav_core_interface->getSessionKey();
						if(isset($respone->data))
							$dataPayment = json_decode($this->megav_core_interface->decrypt3DES($respone->data, $key3des), true);
						
						log_message('error', 'Payment success: ' . print_r($dataPayment, true));
						
						if($respone->status == STATUS_SUCCESS)
						{
							// redirect
							//redirect($dataPayment['redirect_url']);

							//$mess = "Thanh toán thành công. Mã giao dịch tham chiếu " . $dataPayment['ecPayment']['requestId'] . " <br> Hệ thống sẽ tự động chuyển về trang " . $transData['merchantInfo']['merchantName'] . " sau <b id='countdown_text'></b> giây. Nếu không muốn đợi lâu, vui lòng Click";
							$mess = "Thanh toán thành công. <br> Mã giao dịch tham chiếu " . $dataPayment['ecPayment']['requestId'] ;
							//$this->megav_libs->page_result_payment($mess, $dataPayment['redirect_url'], 500, "Về trang " . $transData['merchantInfo']['merchantName'], '/' , 'Lịch sử giao dịch');
							
							if($transData['clientType'] == '1')
							{
								$this->megav_libs->page_result_payment($mess, null, 2000, null, null , null, 1, $dataPayment['schema_url']);
							}
							else
							{
								$this->megav_libs->page_result_payment($mess, $dataPayment['redirect_url'], 2000, null, null , null, 1);
							}
						}
						elseif($respone->status == STATUS_WRONG_OTP)
						{
							$loadView = 1;
							$data['SendOTP'] = 1;
							$data['btnPayment'] = 1;
							$data['otpErr'] = "Sai OTP";
							$data['merchantInfo'] = isset($transData['merchantInfo']) ? $transData['merchantInfo'] : '';
							$data['paymentInfo'] = isset($transData['paymentInfo']) ? $transData['paymentInfo'] : '';
							$this->megav_libs->saveCookieUserData($transData['transId'], $transData, 'ecpayment');
						}
						elseif($respone->status == STATUS_WRONG_PASSLV2)
						{
							$loadView = 1;
							$data['passLv2'] = 1;
							$data['btnPayment'] = 1;
							$data['passLv2Err'] = lang('MVM_'.$respone->status);
							$data['merchantInfo'] = isset($transData['merchantInfo']) ? $transData['merchantInfo'] : '';
							$data['paymentInfo'] = isset($transData['paymentInfo']) ? $transData['paymentInfo'] : '';
							$this->megav_libs->saveCookieUserData($transData['transId'], $transData, 'ecpayment');
						}
						elseif($respone->status == STATUS_WRONG_PASSLV2)
						{
							if(isset($dataPayment['redirect_url']))
							{
								redirect($dataPayment['redirect_url']);
								die;
							}
							else
							{
								$this->megav_libs->page_result(lang('MVM_'.$respone->status), '/transaction_manage', null, null, null, null, null, null, null, 1);
							}
							
						}
						else
						{
							$this->megav_libs->page_result(lang('MVM_'.$respone->status), '/transaction_manage', null, null, null, null, null, null, null, 1);
						}
					}
					else
					{
						$mess = "Có lỗi trong quá trình thanh toán. Vui lòng thử lại.";
						$this->megav_libs->page_result($mess, '/transaction_manage', null, null, null, null, null, null, null, 1);
					}
				}
				else
				{
					$loadView = 1;
					//$data['SendOTP'] = 1;
					$data['btnPayment'] = 1;
					$data['merchantInfo'] = isset($transData['merchantInfo']) ? $transData['merchantInfo'] : '';
					$data['paymentInfo'] = isset($transData['paymentInfo']) ? $transData['paymentInfo'] : '';
				}
			}
		}
		
		if(isset($loadView))
		{
			if($this->session_memcached->userdata['info_user']['countUserbankAcc'] == "0" && REQUIRE_HAVE_BANK_ACCOUNT == '1')
			{
				$data['popup'] = $this->load->view('popup/popup_add_bank', array('mess' => 'Bạn chưa có thông tin tài khoản ngân hàng.<br> Hãy thêm thông tin ngân hàng để tiếp tục'), true);
			}
			
			$dataMenu = array();
			$dataMenu['userinfo'] = array('userName' => $this->session_memcached->userdata['info_user']['userID'],
											'mobileNo' => $this->session_memcached->userdata['info_user']['mobileNo'],
											'balance' => $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																												$this->megav_libs->genarateAccessToken()));
			$redis = new CI_Redis();
			$dataMenu['userinfo']['balance_behold'] = $redis->get('BALANCE_BEHOLD' . $dataMenu['userinfo']['userName']);
			$this->view['content'] = $this->load->view('ec_payment/index', $data, true);
			$this->load->view('Layout/layout_info', array(
				'data' => $this->view,
				'nav_left' => $this->load->view('Layout/layout_menu_left', $dataMenu, true),
				'user_info' => $this->session_memcached->userdata['info_user']
			));
			
		}
		
		if(!$post && empty($get))
		{
			redirect('');
			die;
		}
	}
	
	
}
?>