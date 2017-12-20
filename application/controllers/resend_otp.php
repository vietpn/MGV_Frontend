<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class resend_otp extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->driver('cache');
        $this->load->library('session_memcached');
        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('redis');
		$this->load->library('megav_core_interface');
		$this->load->library('megav_libs');
		
    }

    
	public function index()
	{
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		
		$this->session_memcached->get_userdata();
		$session = $this->input->cookie("megav_session");
		$session = $this->megav_libs->_unserialize($session);
		log_message('error', 'resend_otp session || ' . print_r($session, true));
		if(isset($session['user_data']) && !empty($session['user_data']))
		{
			$redis = new CI_Redis();
			
			$prefix = $this->input->cookie('_prefix');
			if(!$prefix)
				$prefix = "DF";
			log_message('error', 'keyredis data re send otp : ' . $session['session_id'].$prefix);
			$dataSendOTP = json_decode($redis->get($session['session_id'].$prefix), true);
			log_message('error', 'dataSendOTP: ' . print_r($dataSendOTP, true));
			if(isset($dataSendOTP['email']))
				$email = $dataSendOTP['email'];
			elseif(isset($this->session_memcached->userdata['info_user']['email']))
				$email = $this->session_memcached->userdata['info_user']['email'];
			else
				$email = '';
			
			if(isset($dataSendOTP['uname']))
				$uname = $dataSendOTP['uname'];
			elseif(isset($this->session_memcached->userdata['info_user']['userID']))
				$uname = $this->session_memcached->userdata['info_user']['userID'];
			else
				$uname = '';
			
			if(isset($dataSendOTP['mobile']))
				$mobile = $dataSendOTP['mobile'];
			elseif(isset($this->session_memcached->userdata['info_user']['mobileNo']))
				$mobile = $this->session_memcached->userdata['info_user']['mobileNo'];
			else
				$mobile = '';
			
			if(!isset($dataSendOTP['sec_met']))
			{
				$requestSendOtp = $this->megav_core_interface->genOTP($email, $mobile, $uname, $session['user_data']);
			}
			else
			{
				if($dataSendOTP['sec_met'] == '1')
					$requestSendOtp = $this->megav_core_interface->genOTP($email, $mobile, $uname, $session['user_data']);
				else
					$requestSendOtp = $this->megav_core_interface->genOTPToEmail($email, $mobile, $uname, $session['user_data']);
			}
			
			$response = json_decode($requestSendOtp);
			log_message('error', 'Resend OTP respone: ' . print_r($response, true));
			if(isset($response->status))
			{
				$message = '';
				if($response->status == STATUS_SUCCESS)
				{
					if(!isset($dataSendOTP['sec_met']))
					{
						$message = 'Hệ thống đã gửi lại OTP tới số điện thoại của bạn.';
					}
					else
					{
						if($dataSendOTP['sec_met'] == '1')
							$message = 'Hệ thống đã gửi lại OTP tới số điện thoại của bạn.';
						else
							$message = 'Hệ thống đã gửi lại OTP tới email của bạn.';
					}
				}
				elseif($response->status == STATUS_CANT_SEND_OTP)
				{
					$message = 'Bạn không thể nhận thêm OTP';
				}
				$message = $response->status . ',' . $message;
			}
			else
			{
				$message = '2,';
			}
		}
		else
		{
			$message = '32,Bạn không thể nhận thêm OTP.';
		}
		echo $message;
		log_message('error', 'resend_otp || ' . $message);
		$this->session_memcached->_set_cookie($session);
	}	
}
?>