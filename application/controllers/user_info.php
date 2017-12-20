<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class user_info extends CI_Controller
{
	public $deviceOS  = 'Unknow';
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
        $this->lang->load('login');
        $this->lang->load('error_message');
        $this->lang->load('megav_message');
		$this->load->library('megav_libs');
		$this->load->library('megav_core_interface');
		
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
		//$data['popup'] = $this->load->view('popup/inputSecurityCode', "", true);
		$data['userInfo'] = $this->session_memcached->userdata['info_user'];
		$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																				$this->megav_libs->genarateAccessToken());
		
		$data['listMerchantMapping'] = $this->megav_core_interface->listMerchantMappingEpurse($this->session_memcached->userdata['info_user']['userID'],
																								$this->megav_libs->genarateTransactionId('GLT'));
		
		$redis = new CI_Redis();
		$data['balance_behold'] = $redis->get('BALANCE_BEHOLD' . $this->session_memcached->userdata['info_user']['userID']);
		$this->load->view('userinfo/info', $data);
		//echo $view;
		//$this->load->view('Layout/layout_info', array(
		//	'data' => $this->view,
		//	'info' => true,
		//	'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
		//));
    }

    
    public function edit_info_user()
    {
		$data = array();
		log_message('error', 'ACTION THAY DOI THONG TIN || edit_info_user: ' . print_r($this->session_memcached->userdata, true));
		$post = $this->input->post();
		if($post)
		{
			//var_dump($post); die;
			$redis = new CI_Redis();
			$this->form_validation->set_rules('gen', 'Giới tính', 'alpha|trim|xss_clean|max_length[200]');
			$this->form_validation->set_rules('fullname', 'Tên đầy đủ', 'trim|xss_clean|max_length[200]');
			$this->form_validation->set_rules('address', 'Ðịa chỉ', 'trim|xss_clean|max_length[200]');
			if ($this->form_validation->run() == true) 
			{
				$session = $this->input->cookie("megav_session");
				$session = $this->megav_libs->_unserialize($session);
				if($this->session_memcached->userdata['info_user']['security_method'] == '1')
				{
					 // send OTP
					$transId = "UUI" . date("Ymd") . rand();
					$requestSendOtp = $this->megav_core_interface->genOTP($this->session_memcached->userdata['info_user']['email'], 
																		$this->session_memcached->userdata['info_user']['mobileNo'], 
																		$this->session_memcached->userdata['info_user']['userID'], 
																		$transId);
					
					$requestSendOtp = json_decode($requestSendOtp);
					if(isset($requestSendOtp->status))
					{
						if($requestSendOtp->status == '00')
						{
							$redis->set($transId, json_encode($post));
							$session['user_data'] = $transId;
							$this->session_memcached->_set_cookie($session);
							unset($session);
						}
						else
						{
							$error = 1;
							//$redis->del($session['user_data']);
							$this->megav_libs->page_result(lang('MVM_'.$requestSendOtp->status), 'user_info');
							
						}
					}
					else
					{
						$error = 1;
						$redis->del($transId);
						$mess = "Có lỗi trong quá trình cập nhật thông tin. Vui lòng thử lại.";
						$this->megav_libs->page_result($mess, 'user_info');
						
					}
				}
				else
				{
					$transId = "UUI" . date("Ymd") . rand();
					$redis->set($transId, json_encode($post));
					$session['user_data'] = $transId;
					$this->session_memcached->_set_cookie($session);
					unset($session);
				}
				$data['popup'] = $this->load->view('popup/inputSecurityCode', "", true);
			}
		}
		
		if(!isset($error))
		{
			$data['userInfo'] = $this->session_memcached->userdata['info_user'];
			$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																					$this->megav_libs->genarateAccessToken());
            $this->load->view('userinfo/info', $data);
			//echo $view;
            //$this->load->view('Layout/layout_info', array(
            //    'data' => $this->view,
            //    'info' => true,
			//	'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
            //));
        }
    }
	
	public function update_info()
	{
		log_message('error', 'UPDATE USER INFO || update_info: ');
		$post = $this->input->post();
		if($post)
		{
			$data = array();
			$data['userInfo'] = $this->session_memcached->userdata['info_user'];
			$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																					$this->megav_libs->genarateAccessToken());
			if($this->session_memcached->userdata['info_user']['security_method'] == '1')
				$this->form_validation->set_rules('otp', 'OTP', 'required|trim|xss_clean|max_length[9]');
			else
				$this->form_validation->set_rules('passLv2', 'Mật khẩu cấp 2', 'required|trim|xss_clean|max_length[20]|min_length[6]');
			
			if ($this->form_validation->run() == true) 
			{
				$session = $this->input->cookie("megav_session");
				$session = $this->megav_libs->_unserialize($session);
				// verity OTP or PASS LV2
				$verifySecurity = 0;
				if($this->session_memcached->userdata['info_user']['security_method'] == '1')
				{
					// verify otp
					$requestVerifyOtp = $this->megav_core_interface->validOtp($this->session_memcached->userdata['info_user']['email'], 
																			$this->session_memcached->userdata['info_user']['mobileNo'], 
																			$this->session_memcached->userdata['info_user']['userID'], 
																			$post['otp'], $session['user_data']);
					$requestVerifyOtp = json_decode($requestVerifyOtp);
					if(isset($requestVerifyOtp->status))
					{
						if($requestVerifyOtp->status == STATUS_SUCCESS)
						{
							$verifySecurity = 1;
						}
						elseif($requestVerifyOtp->status == STATUS_WRONG_OTP)
						{
							
							$message = "Sai OTP";
						}
						else
						{
							$errorFl = 1;
							$this->megav_libs->page_result(lang('MVM_' . $requestVerifyOtp->status), 'user_info');
							//die;
						}
					}
				}
				else
				{
					// verify pass Lv2
					$transId = "UUI" . date("Ymd") . rand();
					$requestVerifyPassLv2 = $this->megav_core_interface->validPassLv2($this->session_memcached->userdata['info_user']['userID'], $post['passLv2'], $transId);
					$requestVerifyPassLv2 = json_decode($requestVerifyPassLv2);
					if(isset($requestVerifyPassLv2->status))
					{
						if($requestVerifyPassLv2->status == '00')
							$verifySecurity = 1;
						else
							$message = "Sai mật khẩu cấp 2";
					}
				}
				
				if($verifySecurity == 1)
				{
					$redis = new CI_Redis();
					$dataUpdate = json_decode($redis->get($session['user_data']), true);
					
					$username = $this->session_memcached->userdata['info_user']['userID'];
					$phone_status = $this->session_memcached->userdata['info_user']['phone_status'];
					$email_status = $this->session_memcached->userdata['info_user']['email_status'];
					$password = '-1';
					$fullname = ($dataUpdate['fullname'] == '') ? $this->session_memcached->userdata['info_user']['fullname'] : $dataUpdate['fullname'];
					$gen = ($dataUpdate['gen'] == '') ? '-1' : $dataUpdate['gen'];
					$idNo = '-1';
					$birthday = '-1';
					$id_date = '-1';
					$email = '-1';
					$fone = '-1';
					$address = ($dataUpdate['address'] == '') ? '-1' : $dataUpdate['address'];
					$id_place = '-1';
					$arr_acc_Token = $this->megav_libs->genarateAccessToken();
					
					$info = $this->authen_interface->update_user_info($username, $password, $fullname, $email, $fone, $gen, $birthday, $phone_status, 
																		$email_status, $address, $idNo, $id_date, $id_place, $arr_acc_Token);
					
					//$info = '{"status":"00"}';
					log_message('error', 'UPDATE USER INFO:||Thong tin nhan ve :' . $info);
					if (!empty($info)) {
						$response = json_decode($info);
						if ($response->status == '00')
						{
							// del redis
							$redis->del($session['user_data']);
							// update Userinfo in redis
							$arrUserinfo = $this->session_memcached->userdata['info_user'];
							$arrUserinfo['address'] = $dataUpdate['address'];
							$arrUserinfo['gender'] = $dataUpdate['gen'];
							$arrUserinfo['fullname'] = $dataUpdate['fullname'];
							$this->session_memcached->set_userdata('info_user', $arrUserinfo);
							$this->megav_libs->page_result('Cập nhật thông tin thành công', 'user_info');
							
						} else {
							log_message('error', 'respone status: '. $response->status);
							$this->megav_libs->page_result(lang('MVM_' . $response->status), 'user_info');
							
						}
					} else {
						
						$this->megav_libs->page_result(lang('login_error'), 'user_info');
					
					}
				}
				else
				{
					if(!isset($errorFl))
					{
						$error = array();
						if(isset($message))
							$error['message'] = $message;
						$data['popup'] = $this->load->view('popup/inputSecurityCode', $error, true);
						
						$this->load->view('userinfo/info', $data);
						
						//$this->load->view('Layout/layout_info', array(
						//	'data' => $this->view,
						//	'info' => true,
						//	'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
						//));
					}
				}
			}
			else
			{
				$error = array();
				$errArr = $this->form_validation->error_array();
				if($this->session_memcached->userdata['info_user']['security_method'] == '1')
				{
					$error['message'] = $errArr['otp'];
				}
				else
				{
					$error['message'] = $errArr['passLv2'];
				}
				
				$data['popup'] = $this->load->view('popup/inputSecurityCode', $error, true);
				$this->load->view('userinfo/info', $data);
			}
		}
	}

}
?>