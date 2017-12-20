<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class logout extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
        $this->session->sess_destroy();       
		//session_destroy(); 
		/*
        $source_url = $this->input->get('source_url');
        if($source_url){            
            $redirectUrl = base64_decode(urldecode($source_url));
            $arr_acc_Token= json_encode(array(
                'clientID' =>CLIENT_ID,
                'url'     => '',
                'redirect_url'=>$redirectUrl
            ));
        }else{            
            $arr_acc_Token= json_encode(array(
                'clientID' =>CLIENT_ID,
                'url'     => '',
                'redirect_url'=> base_url()
            ));
        };
		*/
        redirect(); die;
    }
}