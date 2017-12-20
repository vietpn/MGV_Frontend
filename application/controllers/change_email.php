<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class change_email extends CI_Controller
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
			$url = current_url() . '?' . $_SERVER['QUERY_STRING'];
			$this->megav_libs->saveSourceUrl($url);
			
			echo "<script>window.top.location='" . base_url() . "'</script>";
			die;
		}
    }

    
	public function index()
	{
		$popup = '';
		$cookie = $this->input->cookie('isSendEmail');
		$isSendMail = '0';
		if($cookie)
		{
			$popup = $this->load->view('popup/popup_result', array('mess' => 'Vui lòng kiểm tra mail để kích hoạt email'), true);
			$isSendMail = '1';
			delete_cookie("isSendEmail");
		}
		$cookie = $this->input->cookie('isChangeEmail');
		if($cookie)
		{
			if($this->session_memcached->userdata['info_user']['email_status'] == '0')
			{
				$mess = 'Thay đổi email thành công.<br> Email chưa được xác thực. Bạn có muốn xác thực ngay?';
				$popup = $this->load->view('popup/popup_active_email', array('mess' => $mess), true);
			}
		}
		
		if($this->session_memcached->userdata['info_user']['email'] == '')
		{
			redirect('/change_email/changeEmail');
			die;
		}
		else
		{
			$this->load->view('change_email/index', array('acc_info' => $this->session_memcached->userdata['info_user'],
															'popup' => $popup,
															'sendmail' => $isSendMail));
		}
		//echo $view;
		/*
		$this->view['content'] = $this->load->view('change_email/index', "", true);
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
		));
		*/
		
	}
	
    public function changeEmail()
    {
		$post = $this->input->post();
		$data = array();
		if($post)
		{
			log_message('error', 'data post : ' . print_r($post, true));
			
			if($this->session_memcached->userdata['info_user']['email_status'] == '0')
			{
				$this->form_validation->set_rules('newemail', 'Địa chỉ Email mới', 'trim|valid_email|required|xss_clean|max_length[255]');
				$this->form_validation->set_rules('password', 'Mật khẩu', 'required|trim|xss_clean|max_length[20]|min_length[6]');
			}else{
				$this->form_validation->set_rules('email', 'Địa chỉ Email', 'required|trim|xss_clean|max_length[255]');
			}
			
			log_message('error', 'thong tin user : ' . print_r($this->session_memcached->userdata['info_user'], true));
			
			if($this->form_validation->run() == true) 
			{
				
				//$transId = "CEM" . date("Ymd") . rand();
				$transId = $this->megav_libs->genarateTransactionId('CEM');
				// sent otp 
				if($this->session_memcached->userdata['info_user']['email_status'] == '1')
				{
					if($post['sec_met'] == '1')
					{
						$focus = "số điện thoại";
						$requestSendOtp = $this->megav_core_interface->genOTP($this->session_memcached->userdata['info_user']['email'],
																				$this->session_memcached->userdata['info_user']['mobileNo'], 
																				$this->session_memcached->userdata['info_user']['userID'], $transId);
					}
					else
					{
						$focus = "email";
						$requestSendOtp = $this->megav_core_interface->genOTPToEmail($this->session_memcached->userdata['info_user']['email'],
																				$this->session_memcached->userdata['info_user']['mobileNo'],
																				$this->session_memcached->userdata['info_user']['userID'], $transId);
					}
					
					if($requestSendOtp)
					{
						$response = json_decode($requestSendOtp);
						log_message('error', 'respone: ' . print_r($requestSendOtp, true));
						if(isset($response->status))
						{
							if($response->status == '00')
							{
								// gui OTP thanh cong
								$data['sentOtp'] = 1;
								$data['messSentOtp'] = "Hệ thống đã gửi OTP tới " . $focus ." .";
								
								//$redis = new CI_Redis();
								$dataChangeEmail = array('sec_met' => $post['sec_met'],'transid' => $transId);
								//$redis->set($transId, json_encode($dataChangeEmail));
								//unset($dataChangePhone);
								//$session = $this->input->cookie("megav_session");
								//$session = $this->megav_libs->_unserialize($session);
								//$session['user_data'] = $transId;
								//$this->session_memcached->_set_cookie($session);
								//unset($session);
								
								$this->megav_libs->saveCookieUserData($transId, $dataChangeEmail);
								
							}
							else
							{
								$loadView = 1;
								$view = $this->megav_libs->page_result(lang('MVM_'.$response->status), '/change_email');
								echo $view;
							}
						}
						else
						{
							$loadView = 1;
							$mess = "Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
							$view = $this->megav_libs->page_result($mess, '/change_email');
							echo $view;
						}
					}
					else
					{
						$loadView = 1;
						$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
						$view = $this->megav_libs->page_result($mess, '/change_email');
						echo $view;
					}
				}
				else
				{
					// update email
					log_message('error', 'Chua co email');
					$accessToken = $this->megav_libs->genarateAccessToken();
					$requestChangeEmail = $this->megav_core_interface->changeEmail($this->session_memcached->userdata['info_user']['email'], 
																		$post['newemail'], $this->session_memcached->userdata['info_user']['userID'],
																		'', $post['password'], $transId, $this->session_memcached->userdata['info_user']['mobileNo'], $accessToken);
					if($requestChangeEmail)
					{
						$response = json_decode($requestChangeEmail);
						log_message('error', 'respone: ' . print_r($requestChangeEmail, true));
						if(isset($response->status))
						{
							if($response->status == STATUS_SUCCESS)
							{
								$loadView = 1;
								$mess = "Thay đổi email thành công. Hệ thống sẽ đăng xuất sau <b id='countdown_text'></b> giây";
								$this->megav_libs->page_result($mess, null, '5000', base_url());
								$this->megav_libs->remove_data();
								/*
								$arrUserinfo = $this->session_memcached->userdata['info_user'];
								$arrUserinfo['email'] = $post['newemail'];
								$this->session_memcached->set_userdata('info_user', $arrUserinfo);
								$this->input->set_cookie('isChangeEmail', '1', 30, "", '/');
								redirect('/change_email');
								*/
							}
							elseif($response->status == '15')
							{
								$data['error_pass'] = 'Mật khẩu sai';
							}
							else
							{
								$loadView = 1;
								$view = $this->megav_libs->page_result(lang('MVM_'.$response->status), '/change_email');
								echo $view;
							}
						}
						else
						{
							$loadView = 1;
							$mess = "Có lỗi trong quá trình thay đổi email. Vui lòng thử lại.";
							$view = $this->megav_libs->page_result($mess, '/change_email');
							echo $view;
						}
					}
					else
					{
						$loadView = 1;
						$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
						$view = $this->megav_libs->page_result($mess, '/change_email');
						echo $view;
					}
				}
			}
		}
		
		if(!isset($loadView))
		{
			$this->load->view('change_email/info', $data);
			//echo $view;
			/*
			$this->view['content'] = $this->load->view('change_email/info', $data, true);
			$this->load->view('Layout/layout_info', array(
				'data' => $this->view,
				'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
			));
			*/
		}
    }
	

	public function updateEmail()
	{
		$post = $this->input->post();
		$data = array();
		$data['sentOtp'] = 1;
		if($post)
		{
			$this->form_validation->set_rules('email', 'Địa chỉ Email', 'required|trim|xss_clean|max_length[255]');
			$this->form_validation->set_rules('newemail', 'Địa chỉ Email', 'valid_email|required|trim|xss_clean|max_length[255]');
			$this->form_validation->set_rules('otp', 'OTP', 'alpha_dash|required|trim|xss_clean|max_length[20]');
			
			if($this->form_validation->run() == true) 
			{
				if($post['newemail'] != $this->session_memcached->userdata['info_user']['email'])
				{
					$session = $this->input->cookie("megav_session");
					$session = $this->megav_libs->_unserialize($session);
					$accessToken = $this->megav_libs->genarateAccessToken();
					$requestChangeEmail = $this->megav_core_interface->changeEmail($this->session_memcached->userdata['info_user']['email'], 
																				$post['newemail'], $this->session_memcached->userdata['info_user']['userID'],
																				$post['otp'], '', $session['user_data'], $this->session_memcached->userdata['info_user']['mobileNo'],
																				$accessToken);
					if($requestChangeEmail)
					{
						$response = json_decode($requestChangeEmail);
						log_message('error', 'respone: ' . print_r($requestChangeEmail, true));
						if(isset($response->status))
						{
							if($response->status == STATUS_SUCCESS)
							{
								//load view change email thanh cong
								
								$mess = "Thay đổi email thành công. Hệ thống sẽ đăng xuất sau <b id='countdown_text'></b> giây";
								$this->megav_libs->page_result($mess, null, '5000', base_url());
								$this->megav_libs->remove_data();
								
								/*
								$arrUserinfo = $this->session_memcached->userdata['info_user'];
								$arrUserinfo['email'] = $post['newemail'];
								$arrUserinfo['email_status'] = '0';
								$this->session_memcached->set_userdata('info_user', $arrUserinfo);
								$this->input->set_cookie('isChangeEmail', '1', 30, "", '/');
								redirect('/change_email');
								*/
							}
							elseif($response->status == STATUS_WRONG_OTP)
							{
								$data['wrong_otp'] = "Sai OTP";
								$view = $this->load->view('change_email/info', $data, true);
								echo $view;
								/*
								$this->view['content'] = $this->load->view('change_email/info', $data, true);
								$this->load->view('Layout/layout_info', array(
									'data' => $this->view,
									'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
								));
								*/
							}
							else
							{
								$view = $this->megav_libs->page_result(lang('MVM_'.$response->status), '/change_email');
								echo $view;
							}
						}
						else
						{
							$mess = "Có lỗi trong quá trình thay đổi email. Vui lòng thử lại.";
							$view = $this->megav_libs->page_result($mess, '/change_email');
							echo $view;
							
						}
					}
					else
					{
						$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
						$view = $this->megav_libs->page_result($mess, '/change_email');
						echo $view;
					}
				}
				else
				{
					$data['wrong_email'] = "Email mới không được giống email cũ";
					$view = $this->load->view('change_email/info', $data, true);
					echo $view;
					/*
					$this->view['content'] = $this->load->view('change_email/info', $data, true);
					$this->load->view('Layout/layout_info', array(
											'data' => $this->view,
											'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
										));
					*/
				}
			}
			else
			{
				$view = $this->load->view('change_email/info', $data, true);
				echo $view;
				/*
				$this->view['content'] = $this->load->view('change_email/info', $data, true);
				$this->load->view('Layout/layout_info', array(
					'data' => $this->view,
					'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
				));
				*/
			}
		}
		else
		{
			redirect('/change_email');
			die;
		}
	}
	
	
	public function sendMailActive()
	{
		// gui mail
		$transId = $this->megav_libs->genarateTransactionId('SMA');
		$requestSendEmail = $this->megav_core_interface->sendMailActiveEmail($this->session_memcached->userdata['info_user']['userID'], $transId);
		
		if($requestSendEmail)
		{
			$response = json_decode($requestSendEmail);
			log_message('error', 'respone: ' . print_r($requestSendEmail, true));
			if(isset($response->status))
			{
				if($response->status == STATUS_SUCCESS)
				{
					$this->input->set_cookie('isSendEmail', '1', 30, "", '/');
					delete_cookie("isChangeEmail");
					sleep(5);
					redirect('/change_email');
				}
				else
				{
					$view = $this->megav_libs->page_result(lang('MVM_'.$response->status), '/change_email');
					echo $view;
				}
			}
			else
			{
				$mess = "Có lỗi trong quá trình gửi mail active. Vui lòng thử lại.";
				$view = $this->megav_libs->page_result($mess, '/change_email');
				echo $view;
			}
		}
		else
		{
			$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
			$view = $this->megav_libs->page_result($mess, '/change_email');
			echo $view;
		}
		
		// redirect lai trang emil
		
	}
	
	public function activeEmail()
	{
		parse_str(urldecode($_SERVER['QUERY_STRING']), $_GET);
		$get = $this->input->get();
		if(!empty($get) && isset($get['acctivecode']))
		{
			$transId = $this->megav_libs->genarateTransactionId('AM');
			$accessToken = $this->megav_libs->genarateAccessToken();
			$requestSendEmail = $this->megav_core_interface->activeEmail($this->session_memcached->userdata['info_user']['userID'], $accessToken, $get['acctivecode'], $transId);
			
			if($requestSendEmail)
			{
				$response = json_decode($requestSendEmail);
				log_message('error', 'respone: ' . print_r($requestSendEmail, true));
				if(isset($response->status))
				{
					if($response->status == STATUS_SUCCESS)
					{
						//redirect('/change_email');
						
						//update cache userinfo 
						$arrUserinfo = $this->session_memcached->userdata['info_user'];
						$arrUserinfo['email_status'] = '1';
						$this->session_memcached->set_userdata('info_user', $arrUserinfo);
						
						$mess = "Xác thực Email thành công. <br> Email sử dụng hiện tại " . $arrUserinfo['email'];
						$this->megav_libs->page_result($mess, '/acc_manage', null, null, null, "Về trang địa chỉ email", null, null, null, 1);
					}
					else
					{
						$view = $this->megav_libs->page_result(lang('MVM_'.$response->status), '/change_email', null, null, null, null, null, null, null, 1);
						echo $view;
					}
				}
				else
				{
					$mess = "Có lỗi trong quá trình xác thực email. Vui lòng thử lại.";
					$view = $this->megav_libs->page_result($mess, '/change_email', null, null, null, null, null, null, null, 1);
					echo $view;
				}
			}
			else
			{
				$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
				$view = $this->megav_libs->page_result($mess, '/change_email', null, null, null, null, null, null, null, 1);
				echo $view;
			}
		}
	}
}
?>