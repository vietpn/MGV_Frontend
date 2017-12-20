<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class change_pass_lv2 extends CI_Controller
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
		// check loai hinh xac thuc giao dich
		if($this->session_memcached->userdata['info_user']['security_method'] != '2')
		{
			$err = 1;
			$mess = "Mật khẩu giao dịch đang sử dụng qua OTP nên hệ thống từ chối sử dụng tính năng này";
			$this->megav_libs->page_result($mess, null, null, '/acc_manage');
		}
		
		$post = $this->input->post();
		if($post)
		{
			$this->form_validation->set_rules('passLv2', 'Mật khẩu cấp 2 đang dùng', 'max_length[20]|min_length[6]|alpha_dash|required|trim|xss_clean');
			$this->form_validation->set_rules('newpassLv2', 'Mật khẩu cấp 2 mới', 'max_length[20]|min_length[6]|alpha_dash|required|trim|xss_clean');
			$this->form_validation->set_rules('repassLv2', 'Mật khẩu cấp 2 nhập lại', 'matches[newpassLv2]|max_length[20]|min_length[6]|alpha_dash|required|trim|xss_clean');
			if($this->form_validation->run() == true) 
			{
				if($post['passLv2'] != $post['newpassLv2'])
				{
					$transId = "CP2" . date("Ymd") . rand();
					$requestMGV = $this->megav_core_interface->changePassLv2($post['passLv2'], $post['newpassLv2'], $this->session_memcached->userdata['info_user']['userID'], $transId);
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
								$this->megav_libs->page_result($mess, null, null, '/acc_manage', 1);
							}
							else
							{
								$err = 1;
								$this->megav_libs->page_result(lang('MVM_'.$response->status), '/change_pass_lv2');
							}
						}
						else
						{
							$err = 1;
							$mess = "Có lỗi trong quá trình thay đổi mật khẩu cấp 2. Vui lòng thử lại.";
							$this->megav_libs->page_result($mess, '/change_pass_lv2');
						}
					}
					else
					{
						$err = 1;
						// dawng ky that bai
						$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
						$this->megav_libs->page_result($mess, '/change_pass_lv2');
					}
				}
				else
				{
					// mat khau moi giong mat khau cu
					
				}
			}
		}
		
		if(!isset($err))
		{
			$data = array();
			$this->load->view('userinfo/changePassLv2', $data);
			/*
			$this->view['content'] = $this->load->view('userinfo/changePassLv2', $data, true);
			$this->load->view('Layout/layout_info', array(
				'data' => $this->view,
				'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
			));*/
			
			// check loai hinh xac thuc giao dịc
			
		}
    }
	
}
?>