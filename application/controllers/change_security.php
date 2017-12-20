<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class change_security extends CI_Controller
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
		$data['userInfo'] = $this->session_memcached->userdata['info_user'];
		//$this->view['content'] = $this->load->view('security_manage/index', $data);
		$this->load->view('security_manage/index', $data);
		
		/*
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
		));
		*/
		
	}
	
	public function updateSecuriyPass() 
	{
		$data = array();
		$post = $this->input->post();
		if($post)
		{
			$this->form_validation->set_rules('newpassLv2', 'Mật khẩu cấp 2', 'max_length[20]|min_length[6]|required|trim|xss_clean');
			$this->form_validation->set_rules('repassLv2', 'Mật khẩu cấp 2 nhập lại', 'max_length[20]|min_length[6]|required|trim|xss_clean|matches[newpassLv2]');
			$this->form_validation->set_rules('sec_met', 'Hình thức xác thực', 'required|trim|xss_clean');
			if($this->form_validation->run() == true) 
			{
				
				// sent otp
				$transId = "CSM" . date("Ymd") . rand();
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
							$redis = new CI_Redis();
							$dataChangeSecurity = array('sec_met' => $post['sec_met'],'transid' => $transId, 'pass' => $post['newpassLv2']);
							$redis->set($transId, json_encode($dataChangeSecurity));
							unset($dataChangeSecurity);
							$session = $this->input->cookie("megav_session");
							$session = $this->_unserialize($session);
							$session['user_data'] = $transId;
							$this->session_memcached->_set_cookie($session);
							unset($session);
							
						}
						else
						{
							$error = 1;
							$this->megav_libs->page_result(lang('MVM_'.$response->status), '/change_security');
						}
					}
					else
					{
						$error = 1;
						$mess = "Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
						$this->megav_libs->page_result($mess, '/change_security');
					}
				}
				else
				{
					$error = 1;
					$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
					$this->megav_libs->page_result($mess, '/change_security');
				}
				
			}
		}
		
		if(!isset($error))
		{
			$this->load->view('security_manage/changeToPass', $data);
		}
		/*
		$this->view['content'] = $this->load->view('security_manage/changeToPass', $data, true);
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
		));
		*/
	}
	
	public function updatePassLv2()
	{
		$post = $this->input->post();
		if($post)
		{
			$data = array();
			$session = $this->input->cookie("megav_session");
			$session = $this->_unserialize($session);
			$redis = new CI_Redis();
			$dataChangeSecurity = json_decode($redis->get($session['user_data']), true);
			
			$this->form_validation->set_rules('otp', 'Mã xác nhận', 'required|trim|xss_clean');
			if($this->form_validation->run() == true) 
			{
				$requestMGV = $this->megav_core_interface->updateSecurity($this->session_memcached->userdata['info_user']['userID'],
																			$this->session_memcached->userdata['info_user']['email'], 
																			$this->session_memcached->userdata['info_user']['mobileNo'], 
																			$dataChangeSecurity['pass'], '2', $dataChangeSecurity['sec_met'], $post['otp'], $session['user_data']);
				if($requestMGV)
				{
					$response = json_decode($requestMGV);
					log_message('error', 'respone: ' . print_r($response, true));
					if(isset($response->status))
					{
						if($response->status == STATUS_SUCCESS)
						{
							$data['mess'] = "Cập nhật hình thức xác thực thành công.";
							$this->load->view('security_manage/finish', $data);
							
							$arrUserinfo = $this->session_memcached->userdata['info_user'];
							$arrUserinfo['security_method'] = '2';
							$this->session_memcached->set_userdata('info_user', $arrUserinfo);
						}
						elseif($response->status == STATUS_WRONG_OTP)
						{
							$this->session_memcached->_set_cookie($session);
							$redis->set($session['user_data'], json_encode($dataChangeSecurity));
							
							$focus = ($dataChangeSecurity['sec_met'] == '1') ? "số điện thoại" : "Email";
							$data['sentOtp'] = 1;
							$data['messSentOtp'] = "Hệ thống đã gửi OTP tới " . $focus; // . substr_replace($post['newphone'], '****', 0, 6);
							$data['wrong_otp'] = "Sai OTP";

							$this->load->view('security_manage/changeToPass', $data);
							/*
							$this->view['content'] = $this->load->view('security_manage/changeToPass', $data, true);
							$this->load->view('Layout/layout_info', array(
								'data' => $this->view,
								'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
							));
							*/
						}
						else
						{
							$this->megav_libs->page_result(lang('MVM_'.$response->status), '/change_security');
						}
					}
					else
					{
						$data['mess'] = "Có lỗi trong quá trình cập nhật hình thức xác thực. Vui lòng thử lại.";
						$this->load->view('security_manage/finish', $data);
					}
				}
				else
				{
					$data['mess'] = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
					$this->load->view('security_manage/finish', $data);
				}
			}
			else
			{
				$this->session_memcached->_set_cookie($session);
				$redis->set($session['user_data'], json_encode($dataChangeSecurity));
				
				$focus = ($dataChangeSecurity['sec_met'] == '1') ? "số điện thoại" : "Email";
				$data['sentOtp'] = 1;
				$data['messSentOtp'] = "Hệ thống đã gửi OTP tới " . $focus; 
				$this->load->view('security_manage/changeToPass', $data);
				/*
				$this->view['content'] = $this->load->view('security_manage/changeToPass', $data, true);
				$this->load->view('Layout/layout_info', array(
					'data' => $this->view,
					'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
				));
				*/
			}
		}
	}
	
	
	public function updateSecurityOtp() 
	{
		$data = array();
		$post = $this->input->post();
		if($post)
		{
			$this->form_validation->set_rules('passLv2', 'Mật khẩu cấp 2', 'max_length[20]|min_length[6]|required|trim|xss_clean');
			if($this->form_validation->run() == true) 
			{
				$transId = "CSM" . date("Ymd") . rand();
				$requestMGV = $this->megav_core_interface->updateSecurity($this->session_memcached->userdata['info_user']['userID'],
																			$this->session_memcached->userdata['info_user']['email'], 
																			$this->session_memcached->userdata['info_user']['mobileNo'], 
																			$post['passLv2'], '1', '', '', $transId);
				if($requestMGV)
				{
					$response = json_decode($requestMGV);
					log_message('error', 'respone: ' . print_r($response, true));
					if(isset($response->status))
					{
						if($response->status == STATUS_SUCCESS)
						{
							$data['mess'] = "Cập nhật hình thức xác thực thành công.";
							//$this->megav_libs->page_result($mess, '/change_security', null, null, 1);
							$this->load->view('security_manage/finish_otp', $data);
							
							$error = 1;
							
							$arrUserinfo = $this->session_memcached->userdata['info_user'];
							$arrUserinfo['security_method'] = '1';
							$this->session_memcached->set_userdata('info_user', $arrUserinfo);
						}
						elseif($response->status == STATUS_WRONG_PASSLV2)
						{
							$data['errPass'] = lang('MVM_'.$response->status);
						}
						else
						{
							$error = 1;
							$this->megav_libs->page_result(lang('MVM_'.$response->status), '/change_security/updateSecurityOtp');
						}
					}
					else
					{
						$error = 1;
						$mess = "Có lỗi trong quá trình cập nhật hình thức xác thực. Vui lòng thử lại.";
						$this->megav_libs->page_result($mess, '/change_security/updateSecurityOtp');
					}
				}
				else
				{
					$error = 1;
					$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
					$this->megav_libs->page_result($mess, '/change_security/updateSecurityOtp');
				}
			}
		}
		
		if(!isset($error))
		{
			
			$this->load->view('security_manage/changeToOtp', $data);
			/*
			$this->view['content'] = $this->load->view('security_manage/changeToOtp', $data, true);
			$this->load->view('Layout/layout_info', array(
				'data' => $this->view,
				'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
			));
			*/
		}
	}
	
	function _unserialize($data)
	{
		$data = @unserialize(strip_slashes($data));
        if (is_array($data))
        {
            foreach ($data as $key => $val)
            {
                if (is_string($val))
                {
                    $data[$key] = str_replace('{{slash}}', '\\', $val);
                }
            }

            return $data;
        }

        return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
    }
}
?>