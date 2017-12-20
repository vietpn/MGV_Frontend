<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class transaction_manage extends CI_Controller
{
	public $deviceOS  = 'Unknow';
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('language');
        $this->load->library('session_memcached');
        $this->load->helper('url');
        $this->load->library('redis');
        $this->lang->load('error_message');
        $this->lang->load('megav_message');
		$this->load->library('megav_libs');
		$this->load->library('megav_core_interface');
		$this->load->helper('cookie');
		
		$this->session_memcached->get_userdata();
		if(!isset($this->session_memcached->userdata['info_user']['userID']))
		{
			redirect();
			die;
		}
    }

    
    public function index()
    {
		$data = array();
		$data['view_data']['user_info'] = $this->session_memcached->userdata['info_user'];
		
		$cookie = $this->input->cookie('isVerifyPhone');
		if($cookie)
		{
			$data['popup'] = $this->load->view('popup/popup_result', array('mess' => 'Chúc mừng bạn đã xác thực số điện thoại thành công',
																		'redirect' => '1'), true);
			delete_cookie("isVerifyPhone");
		}
		else
		{
			
			if($this->session_memcached->userdata['info_user']['mobileNo'] == "")
			{
				$data['popup'] = $this->load->view('popup/update_phone', $data['view_data'], true);
			}
			elseif($this->session_memcached->userdata['info_user']['phone_status'] == "0")
			{
				$popupData = array('user_info' => $this->session_memcached->userdata['info_user']);
				$data['popup'] = $this->load->view('popup/verify_phone', $popupData, true);
			}
			else
			{
				if($this->session_memcached->userdata['info_user']['security_method'] == "0")
				{
					$data['popup'] = $this->load->view('popup/security', $data['view_data'], true);
				}
				elseif($this->session_memcached->userdata['info_user']['countUserbankAcc'] == "0" && REQUIRE_HAVE_BANK_ACCOUNT == '1')
				{
					$data['popup'] = $this->load->view('popup/popup_add_bank', array('mess' => 'Bạn chưa có thông tin tài khoản ngân hàng.<br> Hãy thêm thông tin ngân hàng để tiếp tục'), true);
					$this->input->set_cookie('addBank', '1', 7200, "", '/');
				}
				else
				{
					delete_cookie("addBank");
				}
				
			}
		}
		
		$dataMenu = array();
		$dataMenu['userinfo'] = array('userName' => $this->session_memcached->userdata['info_user']['userID'],
									  'mobileNo' => $this->session_memcached->userdata['info_user']['mobileNo'],
										'balance' => $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																											$this->megav_libs->genarateAccessToken()));
		
		$redis = new CI_Redis();
		$dataMenu['userinfo']['balance_behold'] = $redis->get('BALANCE_BEHOLD' . $dataMenu['userinfo']['userName']);
		
		$this->view['content'] = $this->load->view('transaction_manage/info', $data, true);
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', $dataMenu, true),
			'user_info' => $this->session_memcached->userdata['info_user']
		));
		
    }
	
	
	
	
	public function sendOtp()
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
				$dataRedis = $this->megav_libs->getDataTransRedis('SRM');
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
						//$mess = 'Xác thực số điện thoại thành công.';
						//echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
						//echo "<script>alert('" . $mess . "');</script>";
						//echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
						$this->input->set_cookie('isVerifyPhone', '1', 30, "", '/');
						
						// update mobile stt
						$arrUserinfo = $this->session_memcached->userdata['info_user'];
						$arrUserinfo['phone_status'] 	= '1';
						$this->session_memcached->set_userdata('info_user', $arrUserinfo);
						redirect('/transaction_manage');
					}
					elseif($response->status == STATUS_WRONG_OTP)
					{
						/*
						//$message = 'Sai OTP';
						$popupData = array();
						$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
						$popupData['sendOtp'] = 1;
						$popupData['error'] = 'Sai OTP';
						$data['popup'] = $this->load->view('popup/verify_phone', $popupData, true);
						$data['user_info'] = $this->session_memcached->userdata['info_user'];
						$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
						//$this->load->view('page/index', $data);
						$this->load->view('transaction_manage/info', $data);
						*/
						
						$data = array();
						$popupData = array();
						$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
						$popupData['sendOtp'] = 1;
						$popupData['error'] = 'Sai OTP';
						$data['view_data']['user_info'] = $this->session_memcached->userdata['info_user'];
						$data['popup'] = $this->load->view('popup/verify_phone', $popupData, true);
						$this->view['content'] = $this->load->view('transaction_manage/info', $data, true);
						$this->load->view('Layout/layout_info', array(
							'data' => $this->view,
							'nav_left' => $this->load->view('Layout/layout_menu_left', "", true),
							'user_info' => $this->session_memcached->userdata['info_user']
						));
						
					}
					else
					{
						$mess = lang('MVM_'.$response->status);
						echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
						echo "<script>alert('" . $mess . "');</script>";
						echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
					}
				}
				else
				{
					$mess = "Có lỗi trong quá trình xác thực số điện thoại. Vui lòng thử lại.";
					echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
					echo "<script>alert('" . $mess . "');</script>";
					echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
				}
			}
			else
			{
				/*
				$popupData = array();
				$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
				$popupData['sendOtp'] = 1;
				$error = $this->form_validation->error_array();
				$popupData['error'] = $error['otp'];
				$data['popup'] = $this->load->view('popup/verify_phone', $popupData, true);
				$data['user_info'] = $this->session_memcached->userdata['info_user'];
				$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
				//$this->load->view('page/index', $data);
				$this->load->view('transaction_manage/info', $data);
				*/
				
				$data = array();
				$popupData = array();
				$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
				$popupData['sendOtp'] = 1;
				$error = $this->form_validation->error_array();
				$popupData['error'] = $error['otp'];
				$data['popup'] = $this->load->view('popup/verify_phone', $popupData, true);
				$this->view['content'] = $this->load->view('transaction_manage/info', $data, true);
				$this->load->view('Layout/layout_info', array(
					'data' => $this->view,
					'nav_left' => $this->load->view('Layout/layout_menu_left', "", true),
					'user_info' => $this->session_memcached->userdata['info_user']
				));
			}
		}
		else
		{
			redirect('transaction_manage');
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
					
					/*
					$requestMGV = $this->megav_core_interface->genOtpCheckPhone($this->session_memcached->userdata['info_user']['userID'],
																				$this->session_memcached->userdata['info_user']['email'], 
																				$post['mobile'], $transId);
					*/
					$dataRedis = array('transId' => $transId, 'mobile' => $post['mobile']);
					$prefix = 'SRM';
					log_message('error', 'Prefix save redis: ' . $prefix) ;
					$this->input->set_cookie('_prefix', $prefix, 3600, "", '/');
					
					$this->megav_libs->saveCookieUserData($transId, $dataRedis, $prefix);
					
					$requestMGV = $this->megav_core_interface->genOTP($this->session_memcached->userdata['info_user']['email'], 
																		$post['mobile'], 
																		$this->session_memcached->userdata['info_user']['userID'], $transId);
					
					$response = json_decode($requestMGV);
					
					if(isset($response->status))
					{
						$message = '';
						if($response->status == STATUS_SUCCESS)
						{
							/*
							$popupData = array();
							$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
							$popupData['sendOtp'] = 1;
							$data['popup'] = $this->load->view('popup/update_phone', $popupData, true);
							$data['user_info'] = $this->session_memcached->userdata['info_user'];
							$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
							//$this->load->view('page/index', $data);
							$this->load->view('transaction_manage/info', $data);
							*/
							
							$data = array();
							$popupData = array();
							$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
							$popupData['user_info']['mobileNo'] = $post['mobile'];
							$popupData['sendOtp'] = 1;
							//$data['view_data']['user_info'] = $this->session_memcached->userdata['info_user'];
							$data['popup'] = $this->load->view('popup/update_phone', $popupData, true);
							$this->view['content'] = $this->load->view('transaction_manage/info', $data, true);
							$this->load->view('Layout/layout_info', array(
								'data' => $this->view,
								'nav_left' => $this->load->view('Layout/layout_menu_left', "", true),
								'user_info' => $this->session_memcached->userdata['info_user']
							));
							
						}
						else
						{
							$mess = lang('MVM_'.$response->status);
							echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
							echo "<script>alert('" . $mess . "');</script>";
							echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
						}
						
					}
					else
					{
						$mess = "Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
						echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
						echo "<script>alert('" . $mess . "');</script>";
						echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
					}
				}
				
				if(isset($post['updatePhone']))
				{
					$dataRedis = $this->megav_libs->getDataTransRedis('SRM');
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
							echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
							
							$arrUserinfo = $this->session_memcached->userdata['info_user'];
							$arrUserinfo['mobileNo'] = $dataRedis['mobile'];
							$arrUserinfo['phone_status'] = '1';
							$this->session_memcached->set_userdata('info_user', $arrUserinfo);
						}
						elseif($response->status == STATUS_WRONG_OTP)
						{
							/*
							$popupData = array();
							$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
							$popupData['sendOtp'] = 1;
							$popupData['error']['otp'] = 'Sai OTP';
							$data['popup'] = $this->load->view('popup/update_phone', $popupData, true);
							$data['user_info'] = $this->session_memcached->userdata['info_user'];
							$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
							//$this->load->view('page/index', $data);
							$this->load->view('transaction_manage/info', $data);
							*/
							
							$data = array();
							$popupData = array();
							$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
							$popupData['user_info']['mobileNo'] = $dataRedis['mobile'];
							$popupData['sendOtp'] = 1;
							$popupData['error']['otp'] = 'Sai OTP';
							//$data['view_data']['user_info'] = $this->session_memcached->userdata['info_user'];
							$data['popup'] = $this->load->view('popup/update_phone', $popupData, true);
							$this->view['content'] = $this->load->view('transaction_manage/info', $data, true);
							$this->load->view('Layout/layout_info', array(
								'data' => $this->view,
								'nav_left' => $this->load->view('Layout/layout_menu_left', "", true),
								'user_info' => $this->session_memcached->userdata['info_user']
							));
							
							
						}
						else
						{
							
							$mess = lang('MVM_'.$response->status);
							echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
							echo "<script>alert('" . $mess . "');</script>";
							echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
						}
					}
					else
					{
						$mess = "Có lỗi trong quá trình thay đổi số điện thoại. Vui lòng thử lại.";
						echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
						echo "<script>alert('" . $mess . "');</script>";
						echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
					}
					
				}
				
			}
			else
			{
				/*
				$popupData = array();
				$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
				//$popupData['sendOtp'] = 1;
				$error = $this->form_validation->error_array();
				$popupData['error'] = $error;
				$data['popup'] = $this->load->view('popup/update_phone', $popupData, true);
				$data['user_info'] = $this->session_memcached->userdata['info_user'];
				$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
				//$this->load->view('page/index', $data);
				$this->load->view('transaction_manage/info', $data);
				*/
				$dataRedis = $this->megav_libs->getDataTransRedis('SRM');
				$data = array();
				$popupData = array();
				$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
				$popupData['user_info']['mobileNo'] = $dataRedis['mobile'];
				if(isset($post['updatePhone']))
					$popupData['sendOtp'] = 1;
				$error = $this->form_validation->error_array();
				$popupData['error'] = $error;
				//$data['view_data']['user_info'] = $this->session_memcached->userdata['info_user'];
				$data['popup'] = $this->load->view('popup/update_phone', $popupData, true);
				$this->view['content'] = $this->load->view('transaction_manage/info', $data, true);
				$this->load->view('Layout/layout_info', array(
					'data' => $this->view,
					'nav_left' => $this->load->view('Layout/layout_menu_left', "", true),
					'user_info' => $this->session_memcached->userdata['info_user']
				));
				
			}
			
		}
		else
		{
			redirect('/transaction_manage');
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
									echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
								}
								else
								{
									$mess = lang('MVM_'.$response->status);
									echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
									echo "<script>alert('" . $mess . "');</script>";
									echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
								}
							}
							else
							{
								$mess = "Có lỗi trong quá trình thêm hình thức xác thực. Vui lòng thử lại.";
								
								echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
								echo "<script>alert('" . $mess . "');</script>";
								echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
							}
						}
						else
						{
							$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
							echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
							echo "<script>alert('" . $mess . "');</script>";
							echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
						}
					}
					else
					{
						//verify_phone
						//$mess = "Số điện thoại chưa được xác thực";
						//echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
						//echo "<script>alert('" . $mess . "');</script>";
						//echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
						log_message('error', 'Số điện thoại chưa được xác thực');
						/*
						$popupData = array();
						$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
						$data['popup'] = $this->load->view('popup/verify_phone', $popupData, true);
						$data['user_info'] = $this->session_memcached->userdata['info_user'];
						$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
						//$this->load->view('page/index', $data);
						$this->load->view('transaction_manage/info', $data);
						//die;
						*/
						
						
						$popupData = array();
						$popupData['user_info'] = $this->session_memcached->userdata['info_user'];
						//$data['view_data']['user_info'] = $this->session_memcached->userdata['info_user'];
						$data['popup'] = $this->load->view('popup/verify_phone', $popupData, true);
						$this->view['content'] = $this->load->view('transaction_manage/info', $data, true);
						$this->load->view('Layout/layout_info', array(
							'data' => $this->view,
							'nav_left' => $this->load->view('Layout/layout_menu_left', "", true),
							'user_info' => $this->session_memcached->userdata['info_user']
						));
						
						
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
						if($this->session_memcached->userdata['info_user']['mobileNo'] != '')
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
										/*
										$data['view_data'] = array(
																'sentMobileFl' => 1,
																'phoneSent' => $this->session_memcached->userdata['info_user']['mobileNo']
															);
										$data['popup'] = $this->load->view('popup/security', $data['view_data'], true);
										$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
										$data['user_info'] = $this->session_memcached->userdata['info_user'];
										//$this->load->view('page/index', $data);
										$this->load->view('transaction_manage/info', $data);
										*/
										
										
										$popupData = array();
										$data['view_data'] = array(
																'sentMobileFl' => 1,
																'phoneSent' => $this->session_memcached->userdata['info_user']['mobileNo']
															);
										$data['popup'] = $this->load->view('popup/security', $data['view_data'], true);
										$this->view['content'] = $this->load->view('transaction_manage/info', $data, true);
										$this->load->view('Layout/layout_info', array(
											'data' => $this->view,
											'nav_left' => $this->load->view('Layout/layout_menu_left', "", true),
											'user_info' => $this->session_memcached->userdata['info_user']
										));
										
									}
									else
									{
										$mess = lang('MVM_'.$response->status);
																			
										echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
										echo "<script>alert('" . $mess . "');</script>";
										echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
									}
								}
								else
								{
									$mess = "Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
									echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
									echo "<script>alert('" . $mess . "');</script>";
									echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
								}
							}
							else
							{
								$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
								echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
								echo "<script>alert('" . $mess . "');</script>";
								echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
							}
						}
						else
						{
							$mess = "Chưa có thông tin số điện thoại";
							echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
							echo "<script>alert('" . $mess . "');</script>";
							echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
						}
					}
					elseif($post['sub_met'] == '2')
					{
						if($this->session_memcached->userdata['info_user']['email'] != '')
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
										
										$popupData = array();
										$data['view_data'] = array(
																'sentMobileFl' => 1,
																'phoneSent' => $this->session_memcached->userdata['info_user']['email']
															);
										//$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
										//$data['user_info'] = $this->session_memcached->userdata['info_user'];
										//$data['popup'] = $this->load->view('popup/security', $data['view_data'], true);
										//$this->load->view('page/index', $data);
										//$this->load->view('transaction_manage/info', $data);
										
										
										
										
										$data['popup'] = $this->load->view('popup/security', $popupData, true);
										$this->view['content'] = $this->load->view('transaction_manage/info', $data, true);
										$this->load->view('Layout/layout_info', array(
											'data' => $this->view,
											'nav_left' => $this->load->view('Layout/layout_menu_left', "", true),
											'user_info' => $this->session_memcached->userdata['info_user']
										));
										
									}
									else
									{
										$mess = lang('MVM_'.$response->status);
										echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
										echo "<script>alert('" . $mess . "');</script>";
										echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
									}
								}
								else
								{
									$mess = "Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
									echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
									echo "<script>alert('" . $mess . "');</script>";
									echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
								}
							}
							else
							{
								$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
								echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
								echo "<script>alert('" . $mess . "');</script>";
								echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
							}
						}
						else
						{
							$mess = "Chưa có thông tin email";
							echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
							echo "<script>alert('" . $mess . "');</script>";
							echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
						}
					}
				}
			}
			else
			{
				$data['view_data']['error'] = $this->form_validation->error_array();
				$data['view_data']['checkBox'] = $post['security'];
				$data['view_data']['selectBox'] = $post['sub_met'];
				$data['user_info'] = $this->session_memcached->userdata['info_user'];
				$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
				//$data['popup'] = $this->load->view('popup/security', $data['view_data'], true);
				//$this->load->view('page/index', $data);
				//$this->load->view('transaction_manage/info', $data);
				
				
				$data['popup'] = $this->load->view('popup/security', $data['view_data'], true);
				$this->view['content'] = $this->load->view('transaction_manage/info', $data, true);
				$this->load->view('Layout/layout_info', array(
					'data' => $this->view,
					'nav_left' => $this->load->view('Layout/layout_menu_left', "", true),
					'user_info' => $this->session_memcached->userdata['info_user']
				));
			}
		}
		else
		{
			redirect('/transaction_manage');
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
							
							echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
							echo "<script>alert('Cập nhật hình thức xác thực thành công.');</script>";
							echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
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
							//$data['popup'] = $this->load->view('popup/security', $data['view_data'], true);
							//$this->load->view('page/index', $data);
							//$this->load->view('transaction_manage/info', $data);
							
							$data['popup'] = $this->load->view('popup/security', $data['view_data'], true);
							$this->view['content'] = $this->load->view('transaction_manage/info', $data, true);
							$this->load->view('Layout/layout_info', array(
								'data' => $this->view,
								'nav_left' => $this->load->view('Layout/layout_menu_left', "", true),
								'user_info' => $this->session_memcached->userdata['info_user']
							));
							
						}
						else
						{
							$mess = lang('MVM_'.$response->status);
							echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
							echo "<script>alert('" . $mess . "');</script>";
							echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
						}
					}
					else
					{
						$mess = "Có lỗi trong quá trình cập nhật hình thức xác thực. Vui lòng thử lại.";
						echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
						echo "<script>alert('" . $mess . "');</script>";
						echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
					}
				}
				else
				{
					$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
					echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
					echo "<script>alert('" . $mess . "');</script>";
					echo "<script>location.href='" . base_url('/transaction_manage') . "'</script>";
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
				//$data['popup'] = $this->load->view('popup/security', $data['view_data'], true);
				//$this->load->view('page/index', $data);
				//$this->load->view('transaction_manage/info', $data);
				
				$data['popup'] = $this->load->view('popup/security', $data['view_data'], true);
				$this->view['content'] = $this->load->view('transaction_manage/info', $data, true);
				$this->load->view('Layout/layout_info', array(
					'data' => $this->view,
					'nav_left' => $this->load->view('Layout/layout_menu_left', "", true),
					'user_info' => $this->session_memcached->userdata['info_user']
				));
							
			}
		}
		else
		{
			redirect('transaction_manage');
			die;
		}
	}
	
}
?>