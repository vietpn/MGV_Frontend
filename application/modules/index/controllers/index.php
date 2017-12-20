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
		
		$data = array('user_info' => $this->session_memcached->userdata);
		
		$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
		$this->load->view('index/index', $data);
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
			$this->form_validation->set_rules('passLv2', 'Mật khẩu cấp 2', 'max_length[20]|min_length[6]|required|trim|xss_clean');
			if($this->form_validation->run() == true) 
			{
				
			}
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
					$mess = "Số điện thoại chưa được xác thực";
					echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
					echo "<script>alert('" . $mess . "');</script>";
					echo "<script>location.href='" . base_url() . "'</script>";
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
								$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
								$this->load->view('index/index', $data);
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
								$this->load->view('index/index', $data);
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
			//echo validation_errors();
			$data['nav_left'] = $this->load->view('Layout/layout_menu_left', "", true);
			$this->load->view('index/index', $data);
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
							$this->load->view('index/index', $data);
							
							//$this->session_memcached->_set_cookie($session);
							//echo 123;
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
						$mess = "Có lỗi trong quá trình cập nhật hình thức xác thực. Vui lòng thử lại.";
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
				$this->load->view('index/index', $data);
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
		//$this->load->library('megav_core_interface');
		//// ($userName, $requestId, $providerCode, $fromDate, $toDate, $transStatus, $numbPage, $pageSize, $transType)
		//$get = $this->input->get();
		////var_dump($get); die;
		//$transId = "GLT" . date("Ymd") . rand();
		//$result = $this->megav_core_interface->getTransList($get['username'], $get['transaction_type'], $get['requestId'], $get['providerCode'], $get['fromDate'],
		//												$get['toDate'], $get['transStatus'], $get['numbPage'], $get['pageSize'], 
		//													$get['transType'], $transId);
		//var_dump($result); die;
		echo "FUCK";
		$url = current_url() . '?' . $_SERVER['QUERY_STRING'];
		log_message('error', 'Notify: ' . print_r($url, true));
	}
	
	public function decrypt()
	{
		
		$data = '7d470e47faa9914acfac51c51d47e9b6ba930f35137a0cdf8e51abbd164fb36b650c5189e12145eeb33456a9f83fe8fbcde0cbe9e0f93f4849bd6899a41b408001feb5f457bce94815288bc08b6fb5d52424e583aa7cddf02d9d7055e16580eb84f100ba238853f09c84f88779c995f0eda0233ce197f7df0eeb021b611c91e1f0de4603c1f84f305f50125afe8ee01c8c38fb1c39705401f36938929666a86211e927933bf47e6861d5b437d50eeee257b1e79f0c9631b7726171a732e54525ce25608c97203edd9fc7791a395e5866ca5070a7e5791927114a8b8c89493eb943c6705e68c0b451384b6023fd8f43bb2625ed4eb8cfcaf200ed2ea1108cdf6e07f6b6d0398fd05493d5f0f8dee547b57c2392c93fc0df4830c2ed9bbfe6929db701bea33cdaf55955c6d46d94ce3ff085248c8988142d647378fc326bbe7b6d57f1838e83764242dd80a25c041f2e7bab29e10c01004fd1854cd2b50ba36c367ed544ce05db3941548b3f444cc3d85155bf28d5803abc0d7b6f7468fe1c4f96255f2d192fb41d698b8721fadb52d20b2ac03fb0e330e64bdf83f83493fda0cd5b5475f89d3ffe1df6d56867322a1192d86dc56b954c4443ea47c82fe5e28e2f4a4d65de4d7f0ad9ba3db8e40f648636792173d002ea01d185818b46360c4ff336c8778153f56e0fd21311b0d801eee0ea89d274425dfa072e607837ebc2a30181c279434688e026eda1237b113e2a2bf63a108dc1a048d7131200d3f80815717bd2ef8c8835acd166f78229f43db547dc1d85a6e254b7fff8d5a211326fba355dd56012498ba70f845708d73b481e5b31803a4704bd41656f448eafba4467dac367fa761e9f769126ba8668f1b84ba00c56051fb8b7b6f19f348fdbce69a16febd58fe653bcc35993330dee6788133b4313946d227f09d7b30956deb1d00dae474d8341c13097b84831e0ce70fe5a04f185dad35f84e11d7f0c1acd5ef32db34df31618892c2a61d96b00c5f4e62dff43efb4fadf74bbb401fccb0815149e7f40bbcafa08b162234990bf663f106be110e0936f02063dc7472df28baf979fa99c527fdc0a175527611abee2c268ea1d1cadee4b2bbaf465d20b06c7fd4c1b96bfc480ddf2e1fd708e511e1e26b4e2aae6e220e58d9d1af8d182a683b30faeaf8c283fed1d767f32a914f418db081dcf221b155ad9f3b026bb6fbadd430530fd2f10729667f0a56646cb82d868619f8e766395b7496b1ff6ca73aa0a0eb291d2';
		$this->load->library('megav_core_interface');
		$result = $this->megav_core_interface->decrypt3DES($data, KEY_DECODE);
		log_message('error', 'data || ' . $result);
		var_dump($result); die;
	}
}