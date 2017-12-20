<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class change_phone extends CI_Controller
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
		$this->load->view('change_phone/index', "");
		
		//echo $view;
		/*
		$this->view['content'] = $this->load->view('change_phone/index', "", true);
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
		));
		*/
	}
	
    public function changePhone()
    {
		$post = $this->input->post();
		$data = array();
		if($post)
		{
			$this->form_validation->set_rules('phone', 'Số điện thoại', 'required|trim|xss_clean');
			
			if($this->session_memcached->userdata['info_user']['phone_status'] == '0')
			{
				$this->form_validation->set_rules('newphone', 'Số điện thoại mới', 'max_length[11]|min_length[10]|required|trim|xss_clean');
				$this->form_validation->set_rules('password', 'Mật khẩu', 'required|trim|xss_clean');
			}
			else
			{
				$this->form_validation->set_rules('sec_met', 'Hình thức xác thực', 'required|trim|xss_clean');
			}
			
			if($this->form_validation->run() == true) 
			{
				$transId = "CPN" . date("Ymd") . rand();
				// sent otp 
				if($this->session_memcached->userdata['info_user']['phone_status'] == '1')
				{
					// số điện thoại đã xác thục: b1 gửi OTP
					if($post['sec_met'] == '1')
					{
						$focus = "số điện thoại " . substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 0, 6);;
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
							if($response->status == STATUS_SUCCESS)
							{
								// gui OTP thanh cong
								$data['sentOtp'] = 1;
								$data['messSentOtp'] = "Hệ thống đã gửi OTP tới " . $focus ." .";
								$redis = new CI_Redis();
								$dataChangePhone = array('sec_met' => $post['sec_met'],'transid' => $transId);
								$redis->set($transId, json_encode($dataChangePhone));
								unset($dataChangePhone);
								$session = $this->input->cookie("megav_session");
								$session = $this->megav_libs->_unserialize($session);
								$session['user_data'] = $transId;
								$this->session_memcached->_set_cookie($session);
								unset($session);
								
							}
							else
							{
								$loadView = 1;
								$mess = lang('MVM_'.$response->status);
								$this->megav_libs->page_result($mess, 'change_phone');
								
							}
						}
						else
						{
							$loadView = 1;
							$mess = "Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
							$this->megav_libs->page_result($mess, 'change_phone');
							
						}
					}
					else
					{
						$loadView = 1;
						$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
						$this->megav_libs->page_result($mess, 'change_phone');
						
					}
				}
				else
				{
					// Số điện thoại chưa được xác thực:  update phone luôn
					$accessToken = $this->megav_libs->genarateAccessToken();
					$requestChangePhone = $this->megav_core_interface->changePhone($this->session_memcached->userdata['info_user']['mobileNo'], 
																		$post['newphone'], $this->session_memcached->userdata['info_user']['userID'],
																		'', $post['password'], $transId, $this->session_memcached->userdata['info_user']['email'], $accessToken);
					if($requestChangePhone)
					{
						$response = json_decode($requestChangePhone);
						log_message('error', 'respone: ' . print_r($requestChangePhone, true));
						if(isset($response->status))
						{
							if($response->status == STATUS_SUCCESS)
							{
								echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
								echo "<script>alert('Số điện thoại chưa được xác nhận bạn có muốn xác thực ngay');</script>";
								//$this->megav_libs->remove_data();
								//redirect();
								//die;
							}
							else
							{
								$loadView = 1;
								$mess = lang('MVM_'.$response->status);
								$this->megav_libs->page_result($mess, 'change_phone');
								
							}
						}
						else
						{
							$loadView = 1;
							$mess = "Có lỗi trong quá trình thay đổi số điện thoại. Vui lòng thử lại.";
							$this->megav_libs->page_result($mess, 'change_phone');
							
						}
					}
					else
					{
						$loadView = 1;
						$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
						$this->megav_libs->page_result($mess, 'change_phone');
						
					}
				}
			}
		}
		
		if(!isset($loadView))
		{
			$this->load->view('change_phone/info', $data);
			
			
			/*
			$this->view['content'] = $this->load->view('change_phone/info', $data, true);
			$this->load->view('Layout/layout_info', array(
				'data' => $this->view,
				'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
			));
			*/
		}
    }
	

	public function updatePhone()
	{
		$post = $this->input->post();
		$data = array();
		$data['sentOtp'] = 1;
		if($post)
		{
			$this->form_validation->set_rules('phone', 'Số điện thoại', 'required|trim|xss_clean');
			$this->form_validation->set_rules('newphone', 'Số điện thoại mới', 'max_length[11]|min_length[10]|required|trim|xss_clean');
			$this->form_validation->set_rules('otp', 'Mã OTP', 'max_length[10]|alpha_dash|required|trim|xss_clean');
			
			$session = $this->input->cookie("megav_session");
			$session = $this->megav_libs->_unserialize($session);
			log_message('error', 'updatePhone || session : ' . print_r($session, true));
			if(!isset($session['user_data']) || empty($session['user_data']))
			{
				redirect('change_phone');
				die;
			}
			
			$redis = new CI_Redis();
			$dataChangePhone = json_decode($redis->get($session['user_data']), true);
			
			if($this->form_validation->run() == true) 
			{
				if($post['newphone'] != $this->session_memcached->userdata['info_user']['mobileNo'])
				{
					// valid OTP gui toi so dien thoai cu
					$requestValidOtp = $this->megav_core_interface->validOtp($this->session_memcached->userdata['info_user']['email'], 
																			$this->session_memcached->userdata['info_user']['mobileNo'],
																			$this->session_memcached->userdata['info_user']['userID'], 
																			$post['otp'], $session['user_data']);
					if($requestValidOtp)
					{
						$responseValidOTP = json_decode($requestValidOtp);
						log_message('error', 'respone: ' . print_r($requestValidOtp, true));
						if(isset($responseValidOTP->status))
						{
							if($responseValidOTP->status == STATUS_SUCCESS)
							{
								// gui OTP vao so dt moi va chuyen sang man hinh confirm OTP
								
								//$dataChangePhone['target'] = $post['newphone'];
								$dataChangePhone['mobile'] = $post['newphone'];
								$dataChangePhone['numb_wrong'] = 0;
								
								$requestSendOtp = $this->megav_core_interface->genOTP($this->session_memcached->userdata['info_user']['email'],
																				$post['newphone'], 
																				$this->session_memcached->userdata['info_user']['userID'], $session['user_data']);
								if($requestSendOtp)
								{
									$responeSendOtp = json_decode($requestSendOtp);
									log_message('error', 'respone: ' . print_r($responeSendOtp, true));
									if($responeSendOtp->status == STATUS_SUCCESS)
									{
										//$redis->set($session['user_data'], json_encode($dataChangePhone));
										//unset($dataChangePhone);
										//$this->session_memcached->_set_cookie($session);
										//unset($session);
										
										$dataChangePhone['sec_met'] = 1;
										
										$data['sentOtp'] = 1;
										$data['messSentOtp'] = "Hệ thống đã gửi OTP tới số điện thoại " . substr_replace($post['newphone'], '****', 0, 6);
										$data['newphone'] = $post['newphone'];
										
										$this->load->view('change_phone/update', $data);
										//echo $view;
										/*
										$this->view['content'] = $this->load->view('change_phone/update', $data, true);
										$this->load->view('Layout/layout_info', array(
											'data' => $this->view,
											'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
										));
										*/
									}
									else
									{
										$mess = lang('MVM_'.$responeSendOtp->status);
										$this->megav_libs->page_result($mess, 'change_phone');
										
									}
								}
								else
								{
									$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
									$this->megav_libs->page_result($mess, 'change_phone');
									
								}
							}
							elseif($responseValidOTP->status == STATUS_WRONG_OTP)
							{
								/*
								if(isset($dataChangePhone['numb_wrong']) && $dataChangePhone['numb_wrong'] > WRONG_OTP)
								{
									$this->megav_libs->page_result(lang('MVM_'.$responseValidOTP->status), 'change_phone');
									//die;
								}
								*/
								
								if(isset($dataChangePhone['numb_wrong']))
									$dataChangePhone['numb_wrong'] += 1;
								else
									$dataChangePhone['numb_wrong'] = 1;
								
								
								
								$data['sentOtp'] = 1;
								$data['messSentOtp'] = "Hệ thống đã gửi OTP tới số điện thoại " . substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 0, 6);
								$data['wrong_otp'] = "Sai OTP";
								
								$this->load->view('change_phone/info', $data);
								
								/*
								$this->view['content'] = $this->load->view('change_phone/info', $data, true);
								$this->load->view('Layout/layout_info', array(
									'data' => $this->view,
									'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
								));
								*/
							}
							else
							{
								$mess = lang('MVM_'.$responseValidOTP->status);
								$this->megav_libs->page_result($mess, 'change_phone');
								
							}
						}
						else
						{
							$mess = "Có lỗi trong quá trình thay đổi số điện thoại. Vui lòng thử lại.";
							$this->megav_libs->page_result($mess, 'change_phone');
							
						}
					}
					else
					{
						$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
						$this->megav_libs->page_result($mess, 'change_phone');
						
					}
					
					
				}
				else
				{
					$data['sentOtp'] = 1;
					$data['messSentOtp'] = "Hệ thống đã gửi OTP tới số điện thoại " . substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 0, 6);
					$data['wrong_phone'] = "Số điện thoại mới không được giống số điện thoại cũ";
					
					$this->load->view('change_phone/info', $data);
					
					/*
					$this->view['content'] = $this->load->view('change_phone/info', $data, true);
					$this->load->view('Layout/layout_info', array(
											'data' => $this->view,
											'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
										));
					*/
				}
				
			}
			else
			{
				if($dataChangePhone['sec_met'] == 1)
					$focus = "Số điện thoại " . substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 0, 6);
				else
					$focus = "email";
				
				$data['messSentOtp'] = "Hệ thống đã gửi OTP tới " . $focus ." .";
				$this->load->view('change_phone/info', $data);
				
				/*
				$this->view['content'] = $this->load->view('change_phone/info', $data, true);
				$this->load->view('Layout/layout_info', array(
					'data' => $this->view,
					'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
				));
				*/
			}
			
			
			$redis->set($session['user_data'], json_encode($dataChangePhone));
			$this->session_memcached->_set_cookie($session);
		}
		else
		{
			redirect('/change_phone');
			die;
		}
	}
	
	public function confirmOtp()
	{
		$data = array();
		$post = $this->input->post();
		if($post)
		{
			$session = $this->input->cookie("megav_session");
			$session = $this->megav_libs->_unserialize($session);
			
			if(!isset($session['user_data']) || empty($session['user_data']))
			{
				redirect('change_phone');
				die;
			}
			
			$redis = new CI_Redis();
			$dataChangePhone = json_decode($redis->get($session['user_data']), true);
			log_message('error', 'data change phone in redis: ' . print_r($dataChangePhone, true));
			
			$data['sentOtp'] = 1;
			$data['messSentOtp'] = "Hệ thống đã gửi OTP tới số điện thoại " . substr_replace($dataChangePhone['mobile'], '****', 0, 6);
			$data['newphone'] = $dataChangePhone['mobile'];
			
			$this->form_validation->set_rules('otp', 'Mã OTP', 'alpha_dash|max_length[10]|trim|required|xss_clean');
			if ($this->form_validation->run() === true) 
			{
				
				$accessToken = $this->megav_libs->genarateAccessToken();
				$requestChangePhone = $this->megav_core_interface->changePhone($this->session_memcached->userdata['info_user']['mobileNo'], 
																			$dataChangePhone['mobile'], $this->session_memcached->userdata['info_user']['userID'],
																			$post['otp'], '', $session['user_data'], $this->session_memcached->userdata['info_user']['email'],
																			$accessToken);
				if($requestChangePhone)
				{
					$response = json_decode($requestChangePhone);
					log_message('error', 'respone: ' . print_r($requestChangePhone, true));
					if(isset($response->status))
					{
						if($response->status == '00')
						{
							$error = 1;
							$mess = "Thay đổi số điện thoại thành công thành công";
							$this->megav_libs->page_result($mess, '/change_phone');
							
							
							$arrUserinfo = $this->session_memcached->userdata['info_user'];
							$arrUserinfo['mobileNo'] = $dataChangePhone['mobile'];
							$this->session_memcached->set_userdata('info_user', $arrUserinfo);
						}
						elseif($response->status == STATUS_WRONG_OTP)
						{
							if(isset($dataChangePhone['numb_wrong']) && $dataChangePhone['numb_wrong'] > WRONG_OTP)
							{
								$this->megav_libs->page_result(lang('MVM_'.$response->status), 'change_phone');
								
								die;
							}
							
							if(isset($dataChangePhone['numb_wrong']))
								$dataChangePhone['numb_wrong'] += 1;
							else
								$dataChangePhone['numb_wrong'] = 1;
								
							$redis->set($session['user_data'], json_encode($dataChangePhone));
							$this->session_memcached->_set_cookie($session);
							
							$data['messSentOtp'] = "Hệ thống đã gửi OTP tới số điện thoại " . substr_replace($dataChangePhone['mobile'], '****', 0, 6);
							$data['wrong_otp'] = "Sai OTP";
							$data['newphone'] = $dataChangePhone['mobile'];
							//$this->view['content'] = $this->load->view('change_phone/update', $data, true);
							//$this->load->view('Layout/layout_info', array(
							//	'data' => $this->view,
							//	'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
							//));
						}
						else
						{
							$error = 1;
							$mess = lang('MVM_'.$response->status);
							$this->megav_libs->page_result($mess, 'change_phone');
							
						}
					}
					else
					{
						$error = 1;
						$mess = "Có lỗi trong quá trình thay đổi số điện thoại. Vui lòng thử lại.";
						$this->megav_libs->page_result($mess, 'change_phone');
						
					}
				}
				else
				{
					$error = 1;
					$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
					$this->megav_libs->page_result($mess, 'change_phone');
					
				}
			}
			
			if(!isset($error))
			{
				
				$this->load->view('change_phone/update', $data);
				
				/*
				$this->view['content'] = $this->load->view('change_phone/update', $data, true);
				$this->load->view('Layout/layout_info', array(
					'data' => $this->view,
					'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
				));
				*/
			}
		}
	}

}
?>