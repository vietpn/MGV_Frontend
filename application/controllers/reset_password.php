<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hatt
 * Date: 8/11/14
 * Time: 10:31 AM
 * To change this template use File | Settings | File Templates.
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class reset_password extends CI_Controller
{
	public $deviceOS  = 'Unknow';
    public function __construct()
    {
        parent::__construct();
        $this->load->driver('cache');
        $this->load->helper('language');
        $this->load->library('session_memcached');
        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('redis');
        $this->load->library('authen_interface');
        $this->load->helper('form');
        $this->lang->load('login');
        $this->lang->load('error_message');
		$this->load->library('megav_core_interface');
		$this->lang->load('megav_message');
		$this->load->library('megav_libs');
		
		$this->session_memcached->get_userdata();
		if (isset($this->session_memcached->userdata['info_user']['userID']) && !empty($this->session_memcached->userdata['info_user']['userID'])) {
			redirect('/transaction_manage'); 
			die;
        }
    }

    
    public function index()
    {
		$post = $this->input->post();
		$data = array();
		if($post)
		{
			$redis = new CI_Redis();
			$session = $this->input->cookie("megav_session");
			$session = $this->megav_libs->_unserialize($session);
			(!empty($session['user_data'])) ? $data_reset = $redis->get($session['user_data']) : $data_reset = null;
			log_message('error', 'data reset in redis: ' . print_r($data_reset, true));
			//if(empty(trim($data_reset)))
			//{
				//$this->load->library('recapcha_gg');
				//if($this->recapcha_gg->verifyReCapcha($this->input->post('g-recaptcha-response')))
				//{
					$this->form_validation->set_rules('p_request', 'Số điện thoại hoặc email xác thực tài khoản ', 'trim|required|xss_clean|max_length[200]'); // max leng
					$this->form_validation->set_message('required', '%s bắt buộc không được để trống');
					if ($this->form_validation->run() === true) 
					{
						$dataSaveSentOtp = array();
						$request = trim($post['p_request']);
						//fillter email php
						if(preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $request))
						{
							// quen mat khau bang email
							log_message('error', 'RESET PASS EMAIL');
							//$transId = "GLA" . date("Ymd") . rand(); // dung uid
							$transId = $this->megav_libs->genarateTransactionId('GLA'); 
							$requestReset = $this->megav_core_interface->genarateLinkReset($request, '', $transId);
							$requestReset = json_decode($requestReset);
							if(isset($requestReset->status))
							{
								if($requestReset->status == STATUS_SUCCESS)
								{
									$error = 1;
									//$redis->del($session['user_data']);
									$this->megav_libs->page_result(lang('login_check_mail'), '/reset_password', null, null, null, null, null, null, null, 1);
									$dataSaveSentOtp['email'] = $request;
									$this->megav_libs->saveCookieUserData($transId, $dataSaveSentOtp);
								}
								elseif($requestReset->status == STATUS_INVALID_PHONE_EMAIL)
								{
									$data['form_error'] = "Email không tồn tại.";
									$redis->del($session['user_data']);
								}
								else
								{
									$error = 1;
									//$redis->del($session['user_data']);
									$this->megav_libs->page_result(lang('MVM_'.$requestReset->status), '/reset_password', null, null, null, null, null, null, null, 1);
									$redis->del($session['user_data']);
								}
							}
							else
							{
								$error = 1;
								//$redis->del($session['user_data']);
								$mess = "Có lỗi trong quá trình reset mật khẩu. Vui lòng thử lại.";
								$this->megav_libs->page_result($mess, '/reset_password', null, null, null, null, null, null, null, 1);
								$redis->del($session['user_data']);
							}
						}
						else
						{
							log_message('error', 'RESET PASS PHONE');
							// quen mat khau bang so dien thoai
							if(is_numeric($request))
							{
								if(strlen($request) == 10 || strlen($request) == 11)
								{
									// call core megaV
									$transId = $this->megav_libs->genarateTransactionId('RSP'); 
									$requestReset = $this->megav_core_interface->genOTP('', $request, '', $transId);
									$requestReset = json_decode($requestReset);
									if(isset($requestReset->status))
									{
										$dataSaveSentOtp['mobile'] = $request;
										$this->megav_libs->saveCookieUserData($transId, $dataSaveSentOtp);
										$data_reset = array('mobile' => $request, 'transid' => $transId, 'uname' => "");
										$redis->set($transId, json_encode($data_reset));
										
										unset($data_reset);
										$session['user_data'] = $transId;
										$this->session_memcached->_set_cookie($session);
										
										
										/*
										if($requestReset->status == STATUS_MANY_USER)
										{
											$data['many_user'] = 1;
										}
										else 
											*/
										if($requestReset->status == STATUS_SUCCESS)
										{
											$data['otp_sent'] = 1;
										}
										elseif($requestReset->status == STATUS_INVALID_PHONE_EMAIL)
										{
											$data['form_error'] = "Số điện thoại không tồn tại.";
											$redis->del($session['user_data']);
										}
										else
										{
											$error = 1;
											$redis->del($transId);
											$redis->del($session['user_data']);
											$this->megav_libs->page_result(lang('MVM_'.$requestReset->status), '/reset_password', null, null, null, null, null, null, null, 1);
										}
										unset($session);
									}
									else
									{
										$error = 1;
										$mess = "Có lỗi trong quá trình reset mật khẩu. Vui lòng thử lại.";
										$this->megav_libs->page_result($mess, '/reset_password', null, null, null, null, null, null, null, 1);
										$redis->del($session['user_data']);
									}
								}
								else
								{
									$data['form_error'] = "Số điện thoại có độ dài 10 hoặc 11 ký tự.";
									$redis->del($session['user_data']);
								}
							}
							else
							{
								$data['form_error'] = "Chỉ nhập số điện thoại hoặc Email";
								$redis->del($session['user_data']);
							}
						}
					}
				//}
				//else
				//{
				//	$data['error_capcha'] = 1;
				//}
			//}
			/*
			else // trường hợp nhiều tài khoản cùng chung số điện thoại
			{
				$this->form_validation->set_rules('uname', 'Tên đăng nhập', 'trim|required|xss_clean');
				$this->form_validation->set_message('required', '%s bắt buộc không được để trống');
				if ($this->form_validation->run() === true) 
				{
					$data_reset = json_decode($data_reset, true);
					$requestReset = $this->megav_core_interface->genOTP('', $data_reset['mobile'], $post['uname'], $data_reset['transid']);
					$requestReset = json_decode($requestReset);
					if(isset($requestReset->status))
					{
						if($requestReset->status == STATUS_SUCCESS)
						{
							$data['otp_sent'] = 1;
							$data_reset['uname'] = $post['uname'];
							$redis->del($session['user_data']);
							$redis->set($session['user_data'], json_encode($data_reset));
							$this->session_memcached->_set_cookie($session);
						}
						else
						{
							$error = 1;
							$redis->del($session['user_data']);
							$this->megav_libs->page_result(lang('MVM_'.$requestReset->status), '/reset_password', null, null, null, null, null, null, null, 1);
							
						}
					}
					else
					{
						$error = 1;
						$redis->del($session['user_data']);
						$mess = "Có lỗi trong quá trình reset mật khẩu. Vui lòng thử lại.";
						$this->megav_libs->page_result($mess, '/reset_password', null, null, null, null, null, null, null, 1);
					}
				}
				else
				{
					// luu lai session
					$redis->del($session['user_data']);
					$redis->set($session['user_data'], json_encode($data_reset));
					unset($data_reset);
					$this->session_memcached->_set_cookie($session);
					unset($session);
				}
			}
			*/
		}
		else
		{
			$redis = new CI_Redis();
			$session = $this->input->cookie("megav_session");
			$session = $this->megav_libs->_unserialize($session);
			$session['user_data'] = '';
			// luu lai session
			$redis->del($session['user_data']);
			unset($data_reset);
			$this->session_memcached->_set_cookie($session);
			unset($session);
		}
		
		if(!isset($error))
		{
			//log_message('error', 'ACTION QUEN MAT KHAU || resetbyemail :' . print_r($this->session_memcached->userdata, true));
					
			$this->view['content'] = $this->load->view('sub_pages/inputEmailResetpass', $data, true);
			$this->load->view('Layout/layout_resetpass', array('data' => $this->view, 'reset' => true, 'clientId' => htmlEntities($this->input->cookie('clientId'))));
		}
    }
	
	public function confirm_reset()
	{
		$post = $this->input->post();
		$data = array();
		if(isset($post))
		{
			$redis = new CI_Redis();
			$session = $this->input->cookie("megav_session");
			$session = $this->megav_libs->_unserialize($session);
			$data_reset = json_decode($redis->get($session['user_data']), true);
			
			$this->form_validation->set_rules('otp', 'Mã xác thực', 'alpha_dash|trim|required|xss_clean|max_length[10]');
			$this->form_validation->set_message('required', '%s bắt buộc không được để trống');
			$this->form_validation->set_message('alpha_dash', '%s không được chứa ký tự đặc biệt');
			if ($this->form_validation->run() === true) 
			{
				$requestReset = $this->megav_core_interface->validOtp('', $data_reset['mobile'], $data_reset['uname'], $post['otp'], $data_reset['transid']);
				$requestReset = json_decode($requestReset);
				if(isset($requestReset->status))
				{
					if($requestReset->status == STATUS_SUCCESS)
					{
						$data_reset['otp'] = $post['otp'];
						$redis->del($session['user_data']);
						$redis->set($session['user_data'], json_encode($data_reset));
						unset($data_reset);
						$this->session_memcached->_set_cookie($session);
						unset($session);
						
						// form nhap mat khau moi
						$this->view['content'] = $this->load->view('sub_pages/inputNewPass', $data, true);
						$this->load->view('Layout/layout_resetpass', array('data' => $this->view, 'reset' => true, 'clientId' => htmlEntities($this->input->cookie('clientId'))));
					}
					else
					{
						$redis->del($session['user_data']);
						$view = $this->megav_libs->page_result(lang('MVM_'.$requestReset->status), '/reset_password', null, null, null, null, null, null, null, 1);
						echo $view;
					}
				}
				else
				{
					$redis->del($session['user_data']);
					$mess = "Có lỗi trong quá trình reset mật khẩu. Vui lòng thử lại.";
					$view = $this->megav_libs->page_result($mess, '/reset_password', null, null, null, null, null, null, null, 1);
					echo $view;
				}
			}
			else
			{
				// luu lai session
				$redis->del($session['user_data']);
				$redis->set($session['user_data'], json_encode($data_reset));
				unset($data_reset);
				$this->session_memcached->_set_cookie($session);
				unset($session);
				
				$this->view['content'] = $this->load->view('sub_pages/inputEmailResetpass', array('otp_sent' => 1), true);
				$this->load->view('Layout/layout_resetpass', array('data' => $this->view, 'reset' => true, 'clientId' => htmlEntities($this->input->cookie('clientId'))));
			}
		}
	}
	
	public function change_pass()
	{
		$post = $this->input->post();
		$data = array();
		if($post)
		{
			$redis = new CI_Redis();
			$session = $this->input->cookie("megav_session");
			$session = $this->megav_libs->_unserialize($session);
			$data_reset = json_decode($redis->get($session['user_data']), true);
			
			$this->form_validation->set_rules('passwd', 'Mật khẩu mới', 'alpha_dash|trim|required|xss_clean|max_length[20]|min_length[6]');
			$this->form_validation->set_rules('rpasswd', 'Mật khẩu nhập lại', 'alpha_dash|trim|required|xss_clean|matches[passwd]');
			$this->form_validation->set_message('required', '%s bắt buộc không được để trống');
			$this->form_validation->set_message('alpha_dash', '%s không được chứa ký tự đặc biệt');
			$this->form_validation->set_message('matches', '%s không khớp');
			if ($this->form_validation->run() === true) 
			{
				$requestReset = $this->megav_core_interface->resetPassWord('', $data_reset['mobile'], $data_reset['uname'], $data_reset['otp'], $post['passwd'], $data_reset['transid']);
				$requestReset = json_decode($requestReset);
				if(isset($requestReset->status))
				{
					if($requestReset->status == STATUS_SUCCESS)
					{
						$mess = "Thay đổi mật khẩu thành công.";
						$this->megav_libs->page_result($mess, '/', null, null, 1, 'Quay lại trang chủ', null, null, null, 1);
					}
					else
					{
						$this->megav_libs->page_result(lang('MVM_'.$requestReset->status), '/reset_password', null, null, null, null, null, null, null, 1);
					}
				}
				else
				{
					$redis->del($session['user_data']);
					$mess = "Có lỗi trong quá trình reset mật khẩu. Vui lòng thử lại.";
					$this->megav_libs->page_result($mess, '/reset_password', null, null, null, null, null, null, null, 1);
				}
			}
			else
			{
				$redis->del($session['user_data']);
				$redis->set($session['user_data'], json_encode($data_reset));
				unset($data_reset);
				$this->session_memcached->_set_cookie($session);
				unset($session);
				
				$this->view['content'] = $this->load->view('sub_pages/inputNewPass', $data, true);
				$this->load->view('Layout/layout_resetpass', array('data' => $this->view, 'reset' => true, 'clientId' => htmlEntities($this->input->cookie('clientId'))));
			}
		}
		else
		{
			redirect('/reset_password');
			die;
		}
	}
	

}
?>