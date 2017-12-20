<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class reset_pass_lv2 extends CI_Controller
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
		$post = $this->input->post();
		$data = array();
		if($post)
		{
			$this->form_validation->set_rules('sec_met', 'Hình thức xác thực', 'required|trim|xss_clean');
			if($this->form_validation->run() == true) 
			{
				// check hinh thuc xac thuc
				if($this->session_memcached->userdata['info_user']['phone_status'] != '1' && $post['sec_met'] == '1')
				{
					redirect();
					die;
				}
				
				if($this->session_memcached->userdata['info_user']['email_status'] != '1' && $post['sec_met'] == '2')
				{
					redirect();
					die;
				}
				
				// genotp
				$transId = "RP2" . date("Ymd") . rand();
				if($post['sec_met'] == '1')
				{
					$focus = "số điện thoại " . substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 0, 6);
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
							$err = 1;
							$this->megav_libs->page_result(lang('MVM_'.$response->status), '/reset_pass_lv2');
						}
					}
					else
					{
						$err = 1;
						$mess = "Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
						$this->megav_libs->page_result($mess, '/reset_pass_lv2');
					}
				}
				else
				{
					$err = 1;
					$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
					$this->megav_libs->page_result($mess, '/reset_pass_lv2');
				}
				
			}
		}
		
		if(!isset($err))
		{
			$this->load->view('reset_pass_lv2/resetPassLv2', $data);
			/*
			$this->view['content'] = $this->load->view('reset_pass_lv2/resetPassLv2', $data, true);
			$this->load->view('Layout/layout_info', array(
				'data' => $this->view
			));
			*/
		}
    }
	
	public function resetPassLv2()
	{
		$post = $this->input->post();
		if($post)
		{
			$data = array();
			
			$redis = new CI_Redis();
			$session = $this->input->cookie("megav_session");
			$session = $this->megav_libs->_unserialize($session);
			$dataReset = json_decode($redis->get($session['user_data']), true);
			
			$this->form_validation->set_rules('otp', 'OTP', 'alpha_dash|required|trim|xss_clean');
			$this->form_validation->set_rules('newpassLv2', 'Mật khẩu cấp 2 mới', 'max_length[20]|min_length[6]|alpha_dash|required|trim|xss_clean');
			$this->form_validation->set_rules('repassLv2', 'Mật khẩu cấp 2 nhập lại', 'matches[newpassLv2]|max_length[20]|min_length[6]|alpha_dash|required|trim|xss_clean');
			if($this->form_validation->run() == true) 
			{
				$requestMGV = $this->megav_core_interface->resetPassWordLv2($this->session_memcached->userdata['info_user']['email'], 
																			$this->session_memcached->userdata['info_user']['mobileNo'],
																			$this->session_memcached->userdata['info_user']['userID'], 
																			$post['otp'], $post['newpassLv2'], $session['user_data']);
				if($requestMGV)
				{
					$response = json_decode($requestMGV);
					log_message('error', 'respone: ' . print_r($response, true));
					if(isset($response->status))
					{
						if($response->status == '00')
						{
							$err = 1;
							$mess = "Thay đổi mật khẩu cấp 2 thành công.";
							$this->megav_libs->page_result($mess, null, null, '/acc_manage');
						}
						elseif($response->status == '08')
						{
							// luu redis so lan sai otp
							$data['wrongOtp'] = 'Sai Otp';
						}
						else
						{
							$err = 1;
							$this->megav_libs->page_result(lang('MVM_'.$response->status), '/reset_pass_lv2');
						}
					}
					else
					{
						$err = 1;
						$mess = "Có lỗi trong quá trình thay đổi mật khẩu cấp 2. Vui lòng thử lại.";
						$this->megav_libs->page_result($mess, '/reset_pass_lv2');
					}
				}
				else
				{
					$err = 1;
					// dawng ky that bai
					$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
					$this->megav_libs->page_result($mess, '/reset_pass_lv2');
				}
			}
			else
			{
				$redis->set($session['user_data'], json_encode($dataReset));
				$this->session_memcached->_set_cookie($session);
				unset($session);
			}
		
			if(!isset($err))
			{
				$data['sentOtp'] = 1;
				if($dataReset['sec_met'] == '1')
					$focus = "số điện thoại " . substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 0, 6);
				else
					$focus = "email";
				$data['messSentOtp'] = "Hệ thống đã gửi OTP tới " . $focus ." .";
				$this->load->view('reset_pass_lv2/resetPassLv2', $data);
				//$this->view['content'] = $this->load->view('reset_pass_lv2/resetPassLv2', $data);
				/*
				$this->load->view('Layout/layout_info', array(
					'data' => $this->view
				));
				*/
			}
			
		}
		else
		{
			//redirect('/acc_manage');
			die;
		}
	}
	
}
?>