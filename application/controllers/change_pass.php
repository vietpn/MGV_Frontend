<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class change_pass extends CI_Controller
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
        $this->load->library('authen_interface');
        $this->load->helper('form');
        $this->lang->load('payment');
        $this->lang->load('login');
        $this->lang->load('error_message');
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
		log_message('error', 'ACTION THAY DOI MAT KHAU || index: ' . print_r($this->session_memcached->userdata, true));
		$data = array();
		$this->form_validation->set_rules('old_pass', 'Mật khẩu cũ', 'trim|required|xss_clean|max_length[20]');
        $this->form_validation->set_rules('password', 'Mật khẩu mới', 'trim|required|xss_clean|min_length[6]|max_length[20]');
        $this->form_validation->set_rules('re_pass', 'Nhập lại mật khẩu', 'trim|required|xss_clean|max_length[20]|matches[password]');
        if ($this->form_validation->run() == true) 
		{
			$username = $this->session_memcached->userdata['info_user']['userID'];
			$password = $this->input->post('password');
			$old_password = $this->input->post('old_pass');
			$info = $this->authen_interface->change_pass(0, $password, $old_password, $username);
			
			log_message('error', 'THAY DOI MAT KHAU:||Thong tin reset nhan ve :' . $info);

			if (!empty($info)) 
			{
				log_message('error', 'THAY DOI MAT KHAU: info not empty');
				$response = json_decode($info, true);
				$error_mess = '';
				if ($response['status'] == '00') 
				{
					$err = 1;
					$tail = $this->input->post('source_url') ? ('&source_url=' . $this->input->post('source_url')) : '';
					$error_mess = lang('change_pass_success');
					
					$mess = "Thay đổi mật khẩu thành công. Còn <b id='countdown_text'></b> giây nữa hệ thống sẽ chuyển hướng bạn về màn hình đăng nhập.";
					
					$this->megav_libs->page_result($mess, null, 30000, '/', 1);
					$this->megav_libs->remove_data();
					/*
					//$this->cache->memcached->save('USER_SESS_ID'.$username, '', MEMCACHE_TTL);
					$this->session_memcached->unset_userdata('user_data');
					$this->session_memcached->unset_userdata('info_user');
					
					echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
					echo "<script>alert('Cập nhật thành công. Vui lòng đăng nhập lại');</script>";
					echo "<script>location.href='" . base_url() . 'login' . "'</script>";
					*/
				} 
				elseif($response['status'] == 'CQ')
				{
					$data['wrongPass'] = 'Mật khẩu sai';
				}
				else 
				{
					$this->megav_libs->page_result(lang('MVM_'.$response['status']), '/change_pass');
				}
			} 
			else 
			{
				$err = 1;
				$mess = 'Hệ thống đang bận xin vui lòng thử lại sau';
				$this->megav_libs->page_result($mess, '/change_pass');
			}
			
			
        } 

		if(!isset($err)){ 
            
            $view = $this->load->view('change_pass/change_password', $data, true);
			echo $view;
			/*
            $this->view['content'] = $this->load->view('change_pass/change_password', $data, true);
			$this->load->view('Layout/layout_info', array(
															'data' => $this->view,
															'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
														));
							*/
        }
		
    }
	
	public function reset_pass()
	{
		$this->megav_libs->remove_data();
		redirect('reset_password');
	}
}
?>