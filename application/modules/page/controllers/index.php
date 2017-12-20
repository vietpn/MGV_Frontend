<?php
class index extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('language');
        $this->load->library('session_memcached');
        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('redis');
        $this->load->helper('form');
        $this->lang->load('payment');
        $this->lang->load('login');
        $this->lang->load('error_message');
		$this->lang->load('megav_message');
		$this->load->library('megav_core_interface');
		$this->load->library('megav_libs');
		
    }

    public function index()
    {
		$this->session_memcached->get_userdata();
		if(isset($this->session_memcached->userdata['info_user']['userID']))
		{
			$data = array();
			$data['user_info'] = $this->session_memcached->userdata['info_user'];
			$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'], $this->megav_libs->genarateAccessToken());
			
			$data['view_data']['user_info'] = $this->session_memcached->userdata['info_user'];
			
			if($this->session_memcached->userdata['info_user']['mobileNo'] == "")
			{
				$data['popup'] = $this->load->view('popup/update_phone', $data['view_data'], true);
			}
			else
			{
				if($this->session_memcached->userdata['info_user']['security_method'] == "0")
					$data['popup'] = $this->load->view('popup/security', $data['view_data'], true);
			}
			
			$this->load->view('page/login', $data);
			
			//$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
			//$this->load->view('page/index', $data);
		}
		else
		{
			redirect();
			die;
		}
		
		
    }
	
	public function getAccPage()
	{
		$data = array();
		
		$html = $this->load->view('acc_manage/info', $data, true);
		$returnJson = json_encode(array('status' => '00',
										'html' => $html));
		echo $returnJson;
	}
	
	public function getTransPage()
	{
		$data = array();
		if(!empty($this->input->cookie("merchantId")))
		{
			$html = $this->load->view('merchant_transaction/index', $data, true);
		}
		else
		{
			
			$this->session_memcached->get_userdata();
			$data['view_data']['user_info'] = $this->session_memcached->userdata['info_user'];
			
			$html = $this->load->view('transaction_manage/info', $data, true);
			
		}
		$returnJson = json_encode(array('status' => '00',
										'html' => $html));
		
		echo $returnJson;
	}
	
	public function login()
	{
		$this->session_memcached->get_userdata();
		
		$data = array();
		if(!isset($this->session_memcached->userdata['info_user']['userID']))
		{
			$data = array();
			
			$redis = new CI_Redis();
			$showlogin = $redis->get('SHOWLOGIN');
			if(!is_null($showlogin) || !empty($showlogin))
			{
				$data['showlogin'] = $showlogin;
				$redis->del('SHOWLOGIN');
			}
			
			//$data['showlogin'] = '1';
			$this->load->view('page/login', $data);
		}
		else
		{
			redirect('/page/index');
			die;
		}
	}
	
	public function warning()
	{
		$post = $this->input->post();
		log_message('error', 'port warning ' . print_r($post, true));
		if($post)
		{
			if($_SERVER['HTTP_REFERER'] == base_url())
			{
				$data = array();
				$data['showlogin'] = '1';
				$this->load->view('page/login', $data);
			}
			else
			{
				log_message('error', 'Post from REFERER URL: ' . print_r($_SERVER['HTTP_REFERER'], true));
				//echo "Fuck you";
			}
		}
		else
		{
			$this->load->view('page/warning', '');
		}
	}
	
	public function sendOtp()
	{
		
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		
		$this->session_memcached->get_userdata();
		if(!isset($this->session_memcached->userdata['info_user']['userID']))
		{
			redirect();
			die;
		}
		
		$post = $this->input->post();
		if($post)
		{
			$transId = $this->megav_libs->genarateTransactionId('USM');
			$requestMGV = $this->megav_core_interface->genOTP($this->session_memcached->userdata['info_user']['email'], $this->session_memcached->userdata['info_user']['mobileNo'], $this->session_memcached->userdata['info_user']['userID'], $transId);
			$response = json_decode($requestMGV);
			log_message('error', 'respone || ' .  $transId . ': ' . print_r($response, true));
			$dataRedis = array('transId' => $transId);
			$this->megav_libs->saveCookieUserData($transId, $dataRedis, 'SRM');
			if(isset($response->status))
			{
				$message = '';
				if($response->status == STATUS_SUCCESS)
				{
					$message = 'Hệ thống đã gửi lại OTP tới số điện thoại của bạn.';
				}
				elseif($response->status == STATUS_CANT_SEND_OTP)
				{
					$message = 'Bạn không thể nhận thêm OTP';
				}
				else
				{
					$message = lang('MVM_'.$response->status);
					
				}
				$message = $response->status . ',' . $message;
				echo $message;
				log_message('error', 'sendOtp message: ' . print_r($message, true));
			}
			else
			{
				echo ",Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
			}
		}
	}
	
	public function verify_phone()
	{
		$this->session_memcached->get_userdata();
		if(!isset($this->session_memcached->userdata['info_user']['userID']))
		{
			redirect();
			die;
		}
		
		$post = $this->input->post();
		if($post)
		{
			$this->form_validation->set_rules('otp', 'OTP', 'max_length[20]|min_length[6]|required|trim|xss_clean');
			if($this->form_validation->run() == true) 
			{
				$dataRedis = $this->megav_libs->getDataTransRedis();
				$accessToken = $this->megav_libs->genarateAccessToken();
				$verifyphone = $this->megav_core_interface->verifyPhone($this->session_memcached->userdata['info_user']['userID'], 
																		$this->session_memcached->userdata['info_user']['email'],
																		$this->session_memcached->userdata['info_user']['mobileNo'],
																		$post['otp'], $accessToken, $dataRedis['transId']);
				$response = json_decode($verifyphone);
				if(isset($response->status))
				{
					$message = '';
					if($response->status == STATUS_SUCCESS)
					{
						$mess = 'Xác thực số điện thoại thành công.';
						echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
						echo "<script>alert('" . $mess . "');</script>";
						echo "<script>location.href='" . base_url() . "'</script>";
						
						// update mobile stt
						$arrUserinfo = $this->session_memcached->userdata['info_user'];
						$arrUserinfo['phone_status'] 	= '1';
						$this->session_memcached->set_userdata('info_user', $arrUserinfo);
					}
					elseif($response->status == STATUS_WRONG_OTP)
					{
						//$message = 'Sai OTP';
						$popupData = array();
						$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
						$popupData['sendOtp'] = 1;
						$popupData['error'] = 'Sai OTP';
						$data['popup'] = $this->load->view('popup/verify_phone', $popupData, true);
						$data['user_info'] = $this->session_memcached->userdata['info_user'];
						$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
						$this->load->view('page/index', $data);
					}
					else
					{
						$mess = lang('MVM_'.$response->status);
						echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
						echo "<script>alert('" . $mess . "');</script>";
						echo "<script>location.href='" . base_url() . "'</script>";
					}
				}
				else
				{
					$mess = "Có lỗi trong quá trình xác thực số điện thoại. Vui lòng thử lại.";
					echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
					echo "<script>alert('" . $mess . "');</script>";
					echo "<script>location.href='" . base_url() . "'</script>";
				}
			}
			else
			{
				$popupData = array();
				$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
				$popupData['sendOtp'] = 1;
				$error = $this->form_validation->error_array();
				$popupData['error'] = $error['otp'];
				$data['popup'] = $this->load->view('popup/verify_phone', $popupData, true);
				$data['user_info'] = $this->session_memcached->userdata['info_user'];
				$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
				$this->load->view('page/index', $data);
			}
		}
		else
		{
			redirect();
			die;
		}
	}
	
	
	public function update_phone()
	{
		$this->session_memcached->get_userdata();
		if(!isset($this->session_memcached->userdata['info_user']['userID']))
		{
			redirect();
			die;
		}
		
		$post = $this->input->post();
		if($post)
		{
			$data = array();
			if(isset($post['sendOtp']))
				$this->form_validation->set_rules('mobile', 'Số điện thoại', 'required|trim|xss_clean|is_numeric|max_length[11]|min_length[10]');
			
			if(isset($post['updatePhone']))
				$this->form_validation->set_rules('otp', 'OTP', 'max_length[20]|min_length[6]|required|trim|xss_clean');
			
			if($this->form_validation->run() == true) 
			{
				if(isset($post['sendOtp']))
				{
					// send otp
					$transId = $this->megav_libs->genarateTransactionId('GOP');
					// ($uname, $email, $phone, $transId)
					$requestMGV = $this->megav_core_interface->genOtpCheckPhone($this->session_memcached->userdata['info_user']['userID'],
																				$this->session_memcached->userdata['info_user']['email'], 
																				$post['mobile'], $transId);
					
					$response = json_decode($requestMGV);
					$dataRedis = array('transId' => $transId, 'mobile' => $post['mobile']);
					$this->megav_libs->saveCookieUserData($transId, $dataRedis);
					if(isset($response->status))
					{
						$message = '';
						if($response->status == STATUS_SUCCESS)
						{
							$popupData = array();
							$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
							$popupData['sendOtp'] = 1;
							$data['popup'] = $this->load->view('popup/update_phone', $popupData, true);
							$data['user_info'] = $this->session_memcached->userdata['info_user'];
							$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
							$this->load->view('page/index', $data);
						}
						else
						{
							$mess = lang('MVM_'.$response->status);
							echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
							echo "<script>alert('" . $mess . "');</script>";
							echo "<script>location.href='" . base_url() . "'</script>";
						}
						
					}
					else
					{
						$mess = "Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
						echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
						echo "<script>alert('" . $mess . "');</script>";
						echo "<script>location.href='" . base_url() . "'</script>";
					}
				}
				
				if(isset($post['updatePhone']))
				{
					$dataRedis = $this->megav_libs->getDataTransRedis();
					$accessToken = $this->megav_libs->genarateAccessToken();
					$requestInsertPhone = $this->megav_core_interface->insertPhone($dataRedis['mobile'], $this->session_memcached->userdata['info_user']['userID'],
																				$post['otp'], $dataRedis['transId'], $this->session_memcached->userdata['info_user']['email'],
																				$accessToken);
					
					$response = json_decode($requestInsertPhone);
					log_message('error', 'respone: ' . print_r($requestInsertPhone, true));
					if(isset($response->status))
					{
						if($response->status == STATUS_SUCCESS)
						{
							$error = 1;
							$mess = "Thay đổi số điện thoại thành công thành công";
							$this->megav_libs->page_result($mess, 'user_info');
							
							$mess = "Thêm số điện thoại thành công";
							echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
							echo "<script>alert('" . $mess . "');</script>";
							echo "<script>location.href='" . base_url() . "'</script>";
							
							$arrUserinfo = $this->session_memcached->userdata['info_user'];
							$arrUserinfo['mobileNo'] = $dataRedis['mobile'];
							$arrUserinfo['phone_status'] = '1';
							$this->session_memcached->set_userdata('info_user', $arrUserinfo);
						}
						elseif($response->status == STATUS_WRONG_OTP)
						{
							$popupData = array();
							$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
							$popupData['sendOtp'] = 1;
							$popupData['error']['otp'] = 'Sai OTP';
							$data['popup'] = $this->load->view('popup/update_phone', $popupData, true);
							$data['user_info'] = $this->session_memcached->userdata['info_user'];
							$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
							$this->load->view('page/index', $data);
						}
						else
						{
							
							$mess = lang('MVM_'.$response->status);
							echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
							echo "<script>alert('" . $mess . "');</script>";
							echo "<script>location.href='" . base_url() . "'</script>";
						}
					}
					else
					{
						$mess = "Có lỗi trong quá trình thay đổi số điện thoại. Vui lòng thử lại.";
						echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
						echo "<script>alert('" . $mess . "');</script>";
						echo "<script>location.href='" . base_url() . "'</script>";
					}
					
				}
				
			}
			else
			{
				$popupData = array();
				$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
				//$popupData['sendOtp'] = 1;
				$error = $this->form_validation->error_array();
				$popupData['error'] = $error;
				$data['popup'] = $this->load->view('popup/update_phone', $popupData, true);
				$data['user_info'] = $this->session_memcached->userdata['info_user'];
				$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
				$this->load->view('page/index', $data);
			}
			
			
		}
		else
		{
			redirect();
			die;
		}
	}
	
	public function update_security()
	{
		
		$this->session_memcached->get_userdata();
		if(!isset($this->session_memcached->userdata['info_user']['userID']))
		{
			redirect();
			die;
		}
		
		$redis = new CI_Redis();
		$post = $this->input->post();
		$data = array();
		$this->form_validation->set_rules('security', 'Chọn hình thức xác thực giao dịch', 'required|trim|xss_clean');
		
		if(isset($post['security']))
		{
			// validaton neu la mat khau cap 2
			if($post['security'] == '2')
			{
				$this->form_validation->set_rules('passLv2', 'Mật khẩu cấp 2', 'max_length[20]|min_length[6]|required|trim|xss_clean');
				$this->form_validation->set_rules('rePasLv2', 'Nhập lại mật khẩu cấp 2', 'max_length[20]|min_length[6]|required|trim|matches[passLv2]|xss_clean');
				$this->form_validation->set_rules('sub_met', 'sub method', 'required|trim|xss_clean');
			}
			if($this->form_validation->run() == true) 
			{
				log_message('error', 'data post: ' . print_r($post, true));
				$this->session_memcached->get_userdata();
				$error = true;
				$transId = "USM" . date("Ymd") . rand();
				if($post['security'] == '1')
				{
					if($this->session_memcached->userdata['info_user']['phone_status'] == '1')
					{
						$passLv2 = "";
						$securitySubType= "";
						
						$requestMGV = $this->megav_core_interface->updateSecurity($this->session_memcached->userdata['info_user']['userID'], 
																					$this->session_memcached->userdata['info_user']['email'], 
																					$this->session_memcached->userdata['info_user']['mobileNo'], 
																					$passLv2, $post['security'], $securitySubType, '',$transId);
						if($requestMGV)
						{
							$response = json_decode($requestMGV);
							log_message('error', 'respone: ' . print_r($response, true));
							if(isset($response->status))
							{
								if($response->status == '00')
								{
									$mess = "Thêm hình thức xác thực thành công.";
									// luu redis user
									$arrUserinfo = $this->session_memcached->userdata['info_user'];
									$arrUserinfo['security_method'] = '1';
									$this->session_memcached->set_userdata('info_user', $arrUserinfo);
									
									echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
									echo "<script>alert('" . $mess . "');</script>";
									echo "<script>location.href='" . base_url() . "'</script>";
								}
								else
								{
									$mess = lang('MVM_'.$response->status);
									echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
									echo "<script>alert('" . $mess . "');</script>";
									echo "<script>location.href='" . base_url() . "'</script>";
								}
							}
							else
							{
								$mess = "Có lỗi trong quá trình thêm hình thức xác thực. Vui lòng thử lại.";
								
								echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
								echo "<script>alert('" . $mess . "');</script>";
								echo "<script>location.href='" . base_url() . "'</script>";
							}
						}
						else
						{
							$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
							echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
							echo "<script>alert('" . $mess . "');</script>";
							echo "<script>location.href='" . base_url() . "'</script>";
						}
					}
					else
					{
						//verify_phone
						//$mess = "Số điện thoại chưa được xác thực";
						//echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
						//echo "<script>alert('" . $mess . "');</script>";
						//echo "<script>location.href='" . base_url() . "'</script>";
						log_message('error', 'Số điện thoại chưa được xác thực');
						$popupData = array();
						$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
						$data['popup'] = $this->load->view('popup/verify_phone', $popupData, true);
						$data['user_info'] = $this->session_memcached->userdata['info_user'];
						$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
						$this->load->view('page/index', $data);
						
						//die;
					}
				}
				
				if($post['security'] == '2')
				{
					log_message('error', 'loai mat khau cap 2');
					$data_update_sec = array('passLv2' => $post['passLv2'], 'subMethod' => $post['sub_met'],'transid' => $transId);
					$redis->set($transId, json_encode($data_update_sec));
					unset($data_update_sec);
					$session = $this->input->cookie("megav_session");
					$session = $this->megav_libs->_unserialize($session);
					$session['user_data'] = $transId;
					$this->session_memcached->_set_cookie($session);
					//var_dump($session);
					unset($session);
					
					// genotp 
					if($post['sub_met'] == '1')
					{
						log_message('error', 'loai mat khau cap 2 so dien thoai');
						$requestMGV = $this->megav_core_interface->genOTP($this->session_memcached->userdata['info_user']['email'], $this->session_memcached->userdata['info_user']['mobileNo'], $this->session_memcached->userdata['info_user']['userID'], $transId);
						if($requestMGV)
						{
							$response = json_decode($requestMGV);
							log_message('error', 'respone: ' . print_r($response, true));
							if(isset($response->status))
							{
								if($response->status == STATUS_SUCCESS)
								{
									$data['view_data'] = array(
															'sentMobileFl' => 1,
															'phoneSent' => $this->session_memcached->userdata['info_user']['mobileNo']
														);
									$data['popup'] = $this->load->view('popup/security', $data['view_data'], true);
									$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
									$data['user_info'] = $this->session_memcached->userdata['info_user'];
									$this->load->view('page/index', $data);
								}
								else
								{
									$mess = lang('MVM_'.$response->status);
																		
									echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
									echo "<script>alert('" . $mess . "');</script>";
									echo "<script>location.href='" . base_url() . "'</script>";
								}
							}
							else
							{
								$mess = "Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
								echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
								echo "<script>alert('" . $mess . "');</script>";
								echo "<script>location.href='" . base_url() . "'</script>";
							}
						}
						else
						{
							$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
							echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
							echo "<script>alert('" . $mess . "');</script>";
							echo "<script>location.href='" . base_url() . "'</script>";
						}
					}
					elseif($post['sub_met'] == '2')
					{
						// otp send to email
						$requestMGV = $this->megav_core_interface->genOTPToEmail($this->session_memcached->userdata['info_user']['email'], $this->session_memcached->userdata['info_user']['mobileNo'], $this->session_memcached->userdata['info_user']['userID'], $transId);
						if($requestMGV)
						{
							$response = json_decode($requestMGV);
							log_message('error', 'respone: ' . print_r($response, true));
							if(isset($response->status))
							{
								if($response->status == '00')
								{
									$data['view_data'] = array(
															'sentMobileFl' => 1,
															'phoneSent' => $this->session_memcached->userdata['info_user']['email']
														);
									$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
									$data['user_info'] = $this->session_memcached->userdata['info_user'];
									$data['popup'] = $this->load->view('popup/security', $data['view_data'], true);
									$this->load->view('page/index', $data);
								}
								else
								{
									$mess = lang('MVM_'.$response->status);
									echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
									echo "<script>alert('" . $mess . "');</script>";
									echo "<script>location.href='" . base_url() . "'</script>";
								}
							}
							else
							{
								$mess = "Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
								echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
								echo "<script>alert('" . $mess . "');</script>";
								echo "<script>location.href='" . base_url() . "'</script>";
							}
						}
						else
						{
							$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
							echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
							echo "<script>alert('" . $mess . "');</script>";
							echo "<script>location.href='" . base_url() . "'</script>";
						}
					}
				}
			}
			else
			{
				$data['view_data']['error'] = $this->form_validation->error_array();
				$data['view_data']['checkBox'] = $post['security'];
				$data['user_info'] = $this->session_memcached->userdata['info_user'];
				$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
				$data['popup'] = $this->load->view('popup/security', $data['view_data'], true);
				$this->load->view('page/index', $data);
			}
		}
		else
		{
			redirect();
			die;
		}
	}
	
	public function updatePassLv2()
	{
		$this->session_memcached->get_userdata();
		if(!isset($this->session_memcached->userdata['info_user']['userID']))
		{
			redirect();
			die;
		}
		
		$post = $this->input->post();
		if($post)
		{
			$redis = new CI_Redis();
			$session = $this->input->cookie("megav_session");
			$session = $this->megav_libs->_unserialize($session);
			$data_update_sec = json_decode($redis->get($session['user_data']), true);
			$this->form_validation->set_rules('otp', 'Mã xác nhận', 'required|trim|xss_clean');
			if($this->form_validation->run() == true) 
			{
						
				$requestMGV = $this->megav_core_interface->updateSecurity($this->session_memcached->userdata['info_user']['userID'],
																			$this->session_memcached->userdata['info_user']['email'], 
																			$this->session_memcached->userdata['info_user']['mobileNo'], 
																			$data_update_sec['passLv2'], '2', $data_update_sec['subMethod'], $post['otp'], $data_update_sec['transid']);
				if($requestMGV)
				{
					$response = json_decode($requestMGV);
					log_message('error', 'respone: ' . print_r($response, true));
					if(isset($response->status))
					{
						if($response->status == STATUS_SUCCESS)
						{
							//echo "Cập nhật hình thức xác thực thành công.";
							$arrUserinfo = $this->session_memcached->userdata['info_user'];
							$arrUserinfo['security_method'] = '2';
							$this->session_memcached->set_userdata('info_user', $arrUserinfo);
							
							//echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
							echo "<script>alert('Cập nhật hình thức xác thực thành công.');</script>";
							echo "<script>location.href='" . base_url() . "'</script>";
						}
						elseif($response->status == STATUS_WRONG_OTP)
						{
							$data = array();
							$data['view_data'] = array(
													'sentMobileFl' => 1,
													'phoneSent' => $this->session_memcached->userdata['info_user']['mobileNo'],
													'wrong_otp' => lang('MVM_'.$response->status)
												);
							$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
							$data['user_info'] = $this->session_memcached->userdata['info_user'];
							$data['popup'] = $this->load->view('popup/security', $data['view_data'], true);
							$this->load->view('page/index', $data);
							
							//$this->session_memcached->_set_cookie($session);
							//echo 123;
						}
						else
						{
							$mess = lang('MVM_'.$response->status);
							//echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
							echo "<script>alert('" . $mess . "');</script>";
							echo "<script>location.href='" . base_url() . "'</script>";
						}
					}
					else
					{
						$mess = "Có lỗi trong quá trình cập nhật hình thức xác thực. Vui lòng thử lại.";
						//echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
						echo "<script>alert('" . $mess . "');</script>";
						echo "<script>location.href='" . base_url() . "'</script>";
					}
				}
				else
				{
					$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
					//echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
					echo "<script>alert('" . $mess . "');</script>";
					echo "<script>location.href='" . base_url() . "'</script>";
				}
			}
			else
			{
				$data = array();
				$data['view_data'] = array(
										'sentMobileFl' => 1,
										'phoneSent' => $this->session_memcached->userdata['info_user']['mobileNo'],
										'sentMobileFl' => $data_update_sec['subMethod']
									);
				
				if($data_update_sec['subMethod'] == '1')
					$data['view_data']['phoneSent'] = $this->session_memcached->userdata['info_user']['mobileNo'];
				if($data_update_sec['subMethod'] == '2')
					$data['view_data']['emailSent'] = $this->session_memcached->userdata['info_user']['email'];
				
				$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
				$data['user_info'] = $this->session_memcached->userdata['info_user'];
				$data['popup'] = $this->load->view('popup/security', $data['view_data'], true);
				$this->load->view('page/index', $data);
			}
		}
		else
		{
			redirect();
			die;
		}
	}


	
	
	
	public function test()
	{
		
		$datapayment = new stdclass();
		$datapayment->paymentId = $_GET['paymentId'];
		$datapayment->txnAmount = $_GET['txnAmount'];
		$datapayment->txnTermDateTime = $_GET['txnTermDateTime']; //"20170517061532";
		$datapayment->username = $_GET['username'];
		$datapayment->remark = $_GET['remark'];
		$datapayment = json_encode($datapayment);
		
		//echo $datapayment;
		
		//$datapayment = '{"paymentId":"10001_20170522_12312","txnAmount":"100000","txnTermDateTime":"20170517061532","username":"Test","remark":"Thanh toan"}';
		
		//echo $datapayment;
		//echo "<br>";
		//$data = '{"paymentId":"10001_20170522_12312","txnAmount":"100000","txnTermDateTime":"20170517061532","username":"abc","remark":"def"}';

		//$file_private_key = 'file://'.$_SERVER['DOCUMENT_ROOT'].'/key/private_key_merchant_solar.pem';
		$file_private_key = 'file://'.$_SERVER['DOCUMENT_ROOT'].'/key/private_key_merchant.pem';
		
		//var_dump($file_private_key); 
		//echo "<br>";
		
		$fp = fopen($file_private_key, "r");
		
		//var_dump($fp); die;
		
		$priv_key = fread($fp, 8192);
		fclose($fp);
		$pkeyid = openssl_get_privatekey($priv_key);
		//create signature
		openssl_sign($datapayment, $signature, $pkeyid, OPENSSL_ALGO_SHA256);
		var_dump(bin2hex($signature));
		
		
		
		$dataRef = new stdclass();
		$dataRef->merchantId = $_GET['merchantId']; //'10001';
		$dataRef->signature = bin2hex($signature);
		$dataRef->clientType = $_GET['clientType'];
		$dataRef->data = $datapayment;
		$data = json_encode($dataRef);
		
		$url = "http://172.16.12.107:6969/ec_payment?ref=" . urlencode($data);
		
		echo "<a target='blank' href='$url'>$url</a>";
	}
	
	public function check_trans()
	{
		$dataCheckTrans = new stdClass();
		$dataCheckTrans->paymentId = "10001_20170621_055313M00000005";
		$dataCheckTrans->merchantId = "10001";
		$dataCheckTrans = json_encode($dataCheckTrans);
		
		$file_private_key = 'file://'.$_SERVER['DOCUMENT_ROOT'].'/key/private_key_merchant.pem';
		$fp = fopen($file_private_key, "r");
		$priv_key = fread($fp, 8192);
		fclose($fp);
		$pkeyid = openssl_get_privatekey($priv_key);
		openssl_sign($dataCheckTrans, $signature, $pkeyid, OPENSSL_ALGO_SHA256);
		
		$dataRequest = new stdClass();
		$dataRequest->processing_code = "2002"; // cố định
		$dataRequest->merchantId = '10001';
		$dataRequest->signature = bin2hex($signature);
		$dataRequest->data = $dataCheckTrans;
		
		$url = "http://113.164.227.19:8094/MegaVCore/rest/megav/authenServer?mgvrequest="; // api dc cap
		//$url = "http://172.16.10.61:8080/MegaVCore/rest/megav/authenServer?mgvrequest=";
		$url .= urlencode(json_encode($dataRequest));
		
		echo $url; die;
		
		try
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

			$output = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			curl_close($ch);

			if ($httpcode >= 200 && $httpcode < 300)
				print_r(json_decode($output));
				
			else
			{
				return false;
			}
		} catch(Exception $e ) {
			//log_message('error', 'Exceptions curl: ' . print_r($e, true));
			return false;
		}
		
	}
	
	public function cmp($a, $b)
	{
		return ($a->amount < $b->amount) ? -1 : 1;
	}
	
	public function test_sort_array()
	{
		$requestGetAmount = $this->megav_core_interface->getAmountCDV($transId, 'VTT');
		usort($requestGetAmount, array($this, "cmp"));
		foreach($requestGetAmount as $amount)
		{
			if($amount->templateId == '7')
			{
				echo $amount->amount . "<br>";
			}
		}
		
		//print_r($requestGetAmount);
		//usort($your_data, "cmp");
	}
	
	public function test_function()
	{
		
		$data = new stdClass();
		
		$data->request_id = "MAPB" . date("Ymd") . rand();
		
		$data->user_name = "gn125";
		$data->amount = "100000";
		$data->access_token = $this->megav_libs->genarateAccessToken();
		$data->client_id = CLIENT_ID_OPENID;
		$data->bank_account_name = "Nguyen Phong";
		$data->bank_account = "123123123";
		$data->bank_branch = "Dong Da";
		$data->bank_code = "970418"; // 970418 : BIDV, 970405 : AGRIBANK
		$data->trans_id = "000091_MGV17082310656300056";
		$data->status = "00";
		$data->note = "123456";
				
		$key3des = $this->megav_core_interface->getSessionKey();
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
				
		log_message('error', 'Request core megaV');
		$url = URL_MEGAV_CORE;		
		$dataRequest = new stdClass();
		$dataRequest->processing_code = "1063";   // mapping 1053, // 1062: unmapping   // 1063: rut mapping  // 1054:confirm mapping , //1064 : nap mapping, // 1065 listbank mapping
		$dataRequest->partner_id = PARTNER_ID;
		$dataRequest->mac = "";
		$dataRequest->client_os = "";
		$dataRequest->client_ip = "";
		$dataRequest->merchantId = "";
		$dataRequest->signature = "";
		$dataRequest->data = $encryptData;
		
		$url .= urlencode(json_encode($dataRequest));
		log_message('error', 'data request: ' . print_r($dataRequest, true));
		try{
			$this->load->library('id_curl');
			$response = $this->id_curl->get_curl($url);
			
			$requestMegaV = json_decode($response);
			
			print_r($requestMegaV);
			echo "<br>";
			$data = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			
			print_r($data);
			//echo $response;
		} catch (Exception $e) {
			log_message('error', 'Goi core megaV loi: ' . print_r($e, true));
			throw $e;
		}
		
	}
	
	private function encrypt3DES($text, $key) {
        $key = base64_decode($key);
        $size = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
        $text = $this->pkcs5_pad($text, $size);
        $bin = pack('H*', bin2hex($text));
        $size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($size, MCRYPT_RAND);
        $encrypted = bin2hex(mcrypt_encrypt(MCRYPT_3DES, $key, $bin, MCRYPT_MODE_ECB, $iv));
        return $encrypted;
    }
	
	private function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
	
	public function decrypt()
	{
		
		$data = '7d470e47faa9914acfac51c51d47e9b6ba930f35137a0cdf8e51abbd164fb36b650c5189e12145eeb33456a9f83fe8fbcde0cbe9e0f93f4849bd6899a41b408001feb5f457bce94815288bc08b6fb5d52424e583aa7cddf02d9d7055e16580eb84f100ba238853f09c84f88779c995f0eda0233ce197f7df0eeb021b611c91e1f0de4603c1f84f305f50125afe8ee01c8c38fb1c39705401f36938929666a86211e927933bf47e6861d5b437d50eeee257b1e79f0c9631b7726171a732e54525ce25608c97203edd9fc7791a395e5866ca5070a7e5791927114a8b8c89493eb943c6705e68c0b451384b6023fd8f43bb2625ed4eb8cfcaf200ed2ea1108cdf6e07f6b6d0398fd05493d5f0f8dee547b57c2392c93fc0df4830c2ed9bbfe6929db701bea33cdaf55955c6d46d94ce3ff085248c8988142d647378fc326bbe7b6d57f1838e83764242dd80a25c041f2e7bab29e10c01004fd1854cd2b50ba36c367ed544ce05db3941548b3f444cc3d85155bf28d5803abc0d7b6f7468fe1c4f96255f2d192fb41d698b8721fadb52d20b2ac03fb0e330e64bdf83f83493fda0cd5b5475f89d3ffe1df6d56867322a1192d86dc56b954c4443ea47c82fe5e28e2f4a4d65de4d7f0ad9ba3db8e40f648636792173d002ea01d185818b46360c4ff336c8778153f56e0fd21311b0d801eee0ea89d274425dfa072e607837ebc2a30181c279434688e026eda1237b113e2a2bf63a108dc1a048d7131200d3f80815717bd2ef8c8835acd166f78229f43db547dc1d85a6e254b7fff8d5a211326fba355dd56012498ba70f845708d73b481e5b31803a4704bd41656f448eafba4467dac367fa761e9f769126ba8668f1b84ba00c56051fb8b7b6f19f348fdbce69a16febd58fe653bcc35993330dee6788133b4313946d227f09d7b30956deb1d00dae474d8341c13097b84831e0ce70fe5a04f185dad35f84e11d7f0c1acd5ef32db34df31618892c2a61d96b00c5f4e62dff43efb4fadf74bbb401fccb0815149e7f40bbcafa08b162234990bf663f106be110e0936f02063dc7472df28baf979fa99c527fdc0a175527611abee2c268ea1d1cadee4b2bbaf465d20b06c7fd4c1b96bfc480ddf2e1fd708e511e1e26b4e2aae6e220e58d9d1af8d182a683b30faeaf8c283fed1d767f32a914f418db081dcf221b155ad9f3b026bb6fbadd430530fd2f10729667f0a56646cb82d868619f8e766395b7496b1ff6ca73aa0a0eb291d2';
		$this->load->library('megav_core_interface');
		$result = $this->megav_core_interface->decrypt3DES($data, KEY_DECODE);
		log_message('error', 'data || ' . $result);
		var_dump($result); die;
	}
	
	public function decrypt3DES($text, $key) {
        $key = base64_decode($key);
        $str = $this->hex2bin($text);
        $size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($size, MCRYPT_RAND);
        $decrypted = mcrypt_decrypt(MCRYPT_3DES, $key, $str, MCRYPT_MODE_ECB, $iv);
        $info = rtrim($this->pkcs5_unpad($decrypted));
		//log_message('error', 'data decrypt: ' . print_r($info, true));
        return $info;
    }
	
	public function hex2bin($str) {
        $bin = "";
        $i = 0;
        do {
            $bin .= chr ( hexdec ( $str {$i} . $str {($i + 1)} ) );
            $i += 2;
        } while ( $i < strlen ( $str ) );
        return $bin;
    }
	
	public function pkcs5_unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        $a = substr($text, 0, -1 * $pad);
        return substr($text, 0, -1 * $pad);
    }
	
	public function getsessionKey()
	{
		$sessionKey = $this->megav_core_interface->getSessionKeyServer();
		var_dump($sessionKey);
	}
	
	public function test_redis()
	{
		$redis = new CI_Redis();
		$redis->del(TEMPLATE_FEE_KEY_REDIS);
		$redis->del(PROVIDER_KEY_REDIS);
	}
	
	public function test_api_game()
	{
		$dataRequest = new stdClass();
		$dataRequest->processing_code = "3000";
		$dataRequest->partner_id = '1002';
		$dataRequest->partner_ip = '172.16.12.150';
		$dataRequest->signature = "";
		
		$data = new stdClass();
		$data->user_name = '01636375048';
		$data->amount = '600000';
		$data->txn_datetime = date('YmdHis');
		$data->trans_id = $dataRequest->partner_id . '_' . $data->user_name . '_' . $data->txn_datetime . '_0000';
		
		$data->client_ip = '172.16.12.150';
		
		$getSession = $this->getSessionApi();
		if(isset($getSession->status) && $getSession->status == '00')
		{
			//$dataRequest->data = $this->encrypt3DES(json_encode($data), $getSession->session_key);
			$dataRequest->requestData = json_encode($data);
			//var_dump($dataRequest);
			$url = "http://172.16.12.79:8080/API_Partner_MegaV/rest/partner/api?request_data=";
			$url .= urlencode(json_encode($dataRequest));
			$this->load->library('id_curl');
			$response = $this->id_curl->get_curl($url, true);
			
			var_dump($response);
			echo "<br>";
			$response = json_decode($response);
			$dataTransaction = $this->decrypt3DES($response->data, $getSession->session_key);
			var_dump($dataTransaction);
		}
		else
		{
			return false;
		}
	}
	
	public function getSessionApi() {
		$dataRequest = new stdClass();
		$dataRequest->processing_code = "1030";
		$dataRequest->partner_id = '1002';
		$dataRequest->partner_ip = '172.16.12.150';
		$dataRequest->signature = "";
		
		$data = new stdClass();
		$data->partner_id = '1002';
		$data->p_username = 'honda67';
		$data->p_pass = '17e5e711b56d2c84b135f3c116c7d591';
		$data->client_ip = '172.16.12.150';
		
		//$encryptData = $this->RSAencrypt(json_encode($data));
		$dataRequest->rqSession = json_encode($data);
		//$dataRequest->data = $encryptData;
		
		$url = "http://172.16.12.79:8080/API_Partner_MegaV/rest/partner/api?request_data=";
		$url .= urlencode(json_encode($dataRequest));
		$this->load->library('id_curl');
		$response = $this->id_curl->get_curl($url, true);
		$responeMegaV = json_decode($response);
		//print_r($responeMegaV);
		
		if($responeMegaV->status == '00')
		{
			return json_decode($this->RSAdecrypt($responeMegaV->data));
		}
		
		//return false;
	}
	/*
	private function encrypt3DES($text, $key) {
        $key = base64_decode($key);
        $size = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
        $text = $this->pkcs5_pad($text, $size);
        $bin = pack('H*', bin2hex($text));
        $size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($size, MCRYPT_RAND);
        $encrypted = bin2hex(mcrypt_encrypt(MCRYPT_3DES, $key, $bin, MCRYPT_MODE_ECB, $iv));
        return $encrypted;
    }
	
	
	private function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
	*/
	private function RSAencrypt($data){
		$file_key = 'file://'.$_SERVER['DOCUMENT_ROOT'].'/key/public_key.pem';
		$pubkey = openssl_pkey_get_public(file_get_contents($file_key));
		openssl_public_encrypt($data, $encryptData, $pubkey, OPENSSL_PKCS1_PADDING);
		return bin2hex($encryptData);
	}
	
	private function RSAdecrypt($data){
		$file_key = 'file://'.$_SERVER['DOCUMENT_ROOT'].'/key/private_key.pem';
		$privateKey = openssl_pkey_get_private(file_get_contents($file_key));
		openssl_private_decrypt(hex2bin($data), $decryptData, $privateKey, OPENSSL_PKCS1_PADDING);
		return $decryptData;
	}

}
