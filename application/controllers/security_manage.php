<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class security_manage extends CI_Controller
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
        $this->lang->load('payment');
        $this->lang->load('login');
        $this->lang->load('error_message');
		$this->lang->load('megav_message');
		$this->load->library('megav_core_interface');
		
    }

    
    public function index()
    {
		$this->session_memcached->get_userdata();
		if(isset($this->session_memcached->userdata['info_user']['userID']))
		{
			//$curr_url = $_SERVER['QUERY_STRING'];
			//parse_str($curr_url, $query_array);
			//$this->session_memcached->set_userdata('clientID', htmlEntities($query_array['clientID']));
			//log_message('error', 'THEM CLIENTID: ' . print_r($this->session_memcached->userdata, true));
			//if (isset($query_array['source_url']) && !empty($query_array['source_url'])) {
			//	$redis->set(htmlEntities($query_array['clientID']) . '_url_info_return', htmlEntities($query_array['source_url'])); //duong link url se tra ve cho clientid
			//}
			//$source_url = isset($query_array['source_url']) ? htmlEntities($query_array['source_url']) : '';
			$data = array();
			//$this->view['content'] = $this->load->view('userinfo/security', $data, true);
			$this->load->view('security_manage/security', $data);
			/*
			$this->load->view('Layout/layout_info', array(
				'data' => $this->view,
				'info' => true,
				'clientId' => htmlEntities($query_array['clientID']),
				'source_url' => $source_url
			));
			*/
		}
		else
		{
			//redirect();
			echo "<script>window.top.location='" . base_url() . "'</script>";
			die;
		}
    }
/*
		public function update_security()
		{
			$redis = new CI_Redis();
			$post = $this->input->post();
			$this->form_validation->set_rules('security', 'Chọn hình thức xác thực giao dịch', 'required|trim|xss_clean');
			
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
					echo "<meta charset='utf-8'>";
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
									echo "Thêm hình thức xác thực thành công.";
									// luu redis user
									$arrUserinfo = $this->session_memcached->userdata['info_user'];
									$arrUserinfo['security_method'] = '1';
									$this->session_memcached->set_userdata('info_user', $arrUserinfo);
								}
								else
								{
									echo lang('MVM_'.$response->status);
									echo '<div class="row">
										<div class="col-md-3 col-lg-3 col-xs-6 col-sm-6 none-padding">
											<a href="/security_manage">Quay lại</a>
										</div>
										</div>';
								}
							}
							else
							{
								echo "Có lỗi trong quá trình thêm hình thức xác thực. Vui lòng thử lại.";
								echo '<div class="row">
										<div class="col-md-3 col-lg-3 col-xs-6 col-sm-6 none-padding">
											<a href="/security_manage">Quay lại</a>
										</div>
										</div>';
							}
						}
						else
						{
							echo "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
							echo '<div class="row">
										<div class="col-md-3 col-lg-3 col-xs-6 col-sm-6 none-padding">
											<a href="/security_manage">Quay lại</a>
										</div>
										</div>';
						}
					}
					else
					{
						echo "Số điện thoại chưa được xác thực";
						echo '<div class="row">
										<div class="col-md-3 col-lg-3 col-xs-6 col-sm-6 none-padding">
											<a href="/security_manage">Quay lại</a>
										</div>
										</div>';
					}
				}
				
				if($post['security'] == '2')
				{
					log_message('error', 'loai mat khau cap 2');
					$data_update_sec = array('passLv2' => $post['passLv2'], 'subMethod' => $post['sub_met'],'transid' => $transId);
					$redis->set($transId, json_encode($data_update_sec));
					unset($data_update_sec);
					$session = $this->input->cookie("megav_session");
					$session = $this->_unserialize($session);
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
								if($response->status == '00')
								{
									$data = array(
										'sentMobileFl' => 1,
										'phoneSent' => $this->session_memcached->userdata['info_user']['mobileNo']
									);
									$this->load->view('security_manage/security', $data);
									
								}
								else
								{
									echo lang('MVM_'.$response->status);
									echo '<div class="row">
										<div class="col-md-3 col-lg-3 col-xs-6 col-sm-6 none-padding">
											<a href="/security_manage">Quay lại</a>
										</div>
										</div>';
								}
							}
							else
							{
								echo "Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
								echo '<div class="row">
										<div class="col-md-3 col-lg-3 col-xs-6 col-sm-6 none-padding">
											<a href="/security_manage">Quay lại</a>
										</div>
										</div>';
							}
						}
						else
						{
							echo "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
							echo '<div class="row">
										<div class="col-md-3 col-lg-3 col-xs-6 col-sm-6 none-padding">
											<a href="/security_manage">Quay lại</a>
										</div>
										</div>';
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
									$data = array(
										'sentMobileFl' => 1,
										'phoneSent' => $this->session_memcached->userdata['info_user']['mobileNo']
									);
									$this->load->view('security_manage/security', $data);
									
								}
								else
								{
									echo lang('MVM_'.$response->status);
									echo '<div class="row">
										<div class="col-md-3 col-lg-3 col-xs-6 col-sm-6 none-padding">
											<a href="/security_manage">Quay lại</a>
										</div>
										</div>';
								}
							}
							else
							{
								echo "Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
								echo '<div class="row">
										<div class="col-md-3 col-lg-3 col-xs-6 col-sm-6 none-padding">
											<a href="/security_manage">Quay lại</a>
										</div>
										</div>';
							}
						}
						else
						{
							echo "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
							echo '<div class="row">
										<div class="col-md-3 col-lg-3 col-xs-6 col-sm-6 none-padding">
											<a href="/security_manage">Quay lại</a>
										</div>
										</div>';
						}
					}
				}
			}
		}
		
		public function updatePassLv2()
		{
			$post = $this->input->post();
			if($post)
			{
				$this->form_validation->set_rules('otp', 'mã xác thực', 'required|trim|xss_clean');
				if($this->form_validation->run() == true) 
				{
					$this->session_memcached->get_userdata();
					$redis = new CI_Redis();
					$session = $this->input->cookie("megav_session");
					$session = $this->_unserialize($session);
					$data_update_sec = json_decode($redis->get($session['user_data']), true);
					// ('passLv2' => $post['passLv2'], 'subMethod' => $post['sub_met'],'transid' => $transId);
									
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
							if($response->status == '00')
							{
								echo "Cập nhật hình thức xác thực thành công.";
								$arrUserinfo = $this->session_memcached->userdata['info_user'];
								$arrUserinfo['security_method'] = '2';
								$this->session_memcached->set_userdata('info_user', $arrUserinfo);
							}
							else
							{
								echo lang('MVM_'.$response->status);
								echo '<div class="row">
										<div class="col-md-3 col-lg-3 col-xs-6 col-sm-6 none-padding">
											<a href="/security_manage">Quay lại</a>
										</div>
										</div>';
							}
						}
						else
						{
							echo "Có lỗi trong quá trình cập nhật hình thức xác thực. Vui lòng thử lại.";
							echo '<div class="row">
										<div class="col-md-3 col-lg-3 col-xs-6 col-sm-6 none-padding">
											<a href="/security_manage">Quay lại</a>
										</div>
										</div>';
						}
					}
					else
					{
						echo "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
						echo '<div class="row">
										<div class="col-md-3 col-lg-3 col-xs-6 col-sm-6 none-padding">
											<a href="/security_manage">Quay lại</a>
										</div>
										</div>';
					}
				}
				else
				{
					$source_url = isset($query_array['source_url']) ? htmlEntities($query_array['source_url']) : '';
					$data = array(
						'clientId' => htmlEntities($query_array['clientID']),
						'source_url' => $source_url,
						'sentMobileFl' => $data_update_sec['subMethod']
					);
					
					if($data_update_sec['subMethod'] == '1')
						$data['phoneSent'] = $this->session_memcached->userdata['info_user']['mobileNo'];
					if($data_update_sec['subMethod'] == '2')
						$data['emailSent'] = $this->session_memcached->userdata['info_user']['email'];
					
					$this->load->view('security_manage/security', $data);
				}
			}
			else
			{
				redirect();
				die;
			}
		}
*/

	public function getInfo() 
	{
		$post = $this->input->post();
		if($post)
		{
			$this->session_memcached->get_userdata();
			if(isset($post['getphone']) && $post['getphone'] == true)
			{
				log_message('error', 'data post: ' . print_r($post, true));
				if($this->session_memcached->userdata['info_user']['phone_status'] == '1')
					echo "Số điện thoại : " . substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 3, 5);
				else
					echo "Số điện thoại chưa được xác thực";
			}
			
			if(isset($post['security_info']) && $post['security_info'] == true)
			{
				$returnInfo = '<p>Mật khẩu giao dịch<input name="passLv2" type="password" name="security" ></p>';
				$returnInfo .= '<p>Nhập lại mật khẩu giao dịch<input name="rePasLv2" type="password" name="security" ></p>';
				
				$returnInfo .= "Hình thức xác thực <select name='sub_met'>";
				
				if($this->session_memcached->userdata['info_user']['phone_status'] == '1')
					$returnInfo .= "<option value='1'>" . substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 3, 5) . "</option>";
				if($this->session_memcached->userdata['info_user']['email_status'] == '1')
					$returnInfo .= "<option value='2'>" . substr_replace($this->session_memcached->userdata['info_user']['email'], '****', 3, 5) . "</option>";
				
				
				$returnInfo .= "</select>";
				
				echo $returnInfo;
			}
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