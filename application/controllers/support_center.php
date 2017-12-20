<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class support_center extends CI_Controller
{
	
    public function __construct()
    {
        parent::__construct(true);
        $this->load->helper('language');
        $this->load->library('session_memcached');
        $this->load->helper('url');
        $this->load->library('redis');
        $this->lang->load('error_message');
		$this->load->library('megav_libs');
		$this->load->library('megav_core_interface');
		$this->load->helper('cookie');
		
		$this->session_memcached->get_userdata();
		if(!isset($this->session_memcached->userdata['info_user']['userID']))
		{
			echo "<script>window.top.location='" . base_url() . "'</script>";
			die;
		}
    }

    
    public function index()
    {
		$data = array();
		$data['tab'] = 'info';
		$dataMenu = array();
		$dataMenu['userinfo'] = array('userName' => $this->session_memcached->userdata['info_user']['userID'],
										'balance' => $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																											$this->megav_libs->genarateAccessToken()));
		$this->view['content'] = $this->load->view('support_center/info', $data, true);
		/*
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', $dataMenu, true),
			'user_info' => $this->session_memcached->userdata['info_user']
		));
		*/
		
		
		$this->load->view('Layout/layout_iframe', array(
			'data' => $this->view
		));
		
    }
	

}
?>