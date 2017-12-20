<?php
class index extends CI_Controller
{
    public function __construct()
    {
        parent::__construct(true);
		$this->load->library('session_memcached');
		$this->load->library('redis');
		$this->load->library('megav_core_interface');
    }

    public function index()
    {
		$redis = new CI_Redis();
		$str = $redis->get('SESSION_KEY');
		var_dump($str); die;
				
		$str = $this->megav_core_interface->getSessionKeyServer();
		
		var_dump($str); die;
		if(!empty($str))
		{
			if($str['status'] == "00")
			{
				$redis = new CI_Redis();
				$redis->set('SESSION_KEY', $str['session_key']);
			}
		}
		
		$redis_get = new CI_Redis();
		$value = $redis_get->get('SESSION_KEY');
		var_dump($value);
		//echo "<br>";
		//print_r($str2);
		
		
		//$this->session_memcached->get_userdata();
		//$ip_client = $this->session_memcached->userdata('ip_address');
		//$redis = new CI_Redis;
		//$numb_wrong_pass = $redis->get('WRONG_PASS_' . $ip_client . date('Ymd'));
		//var_dump($numb_wrong_pass);
		//$redis->del('WRONG_PASS_' . $ip_client . date('Ymd'));
    }
	
	public function genOtpMail()
	{
		$transId = "CEM" . date("Ymd") . rand();
		$str = $this->megav_core_interface->genOTPToEmail('honda67@mailinator.com', 'honda67', $transId);
		var_dump($str); die;
	}
	
	public function genOTP()
	{
		//$data = new stdclass();
		//$data->username = "123";
		//$data->userpass = "123";
		//
		//$query = base64_encode(json_encode($data));
		//echo $query;
		//
		//var_dump(json_decode(base64_decode("eyJ1c2VybmFtZSI6IjEyMyIsInVzZXJwYXNzIjoiMTIzIn0=")));
		$get = $this->input->get();
		$str = $this->megav_core_interface->genOTP($get['phone'], $get['uname'], $get['transid']);
		var_dump($str);
	}
	
	public function validOtp() {
		$get = $this->input->get();
														
		$str = $this->megav_core_interface->validOtp($get['email'], $get['phone'], $get['uname'], $get['otp'], $get['transid']);
		var_dump($str);
	}
	
	public function getRedis()
	{
		$this->session_memcached->get_userdata();
		$session = $this->session_memcached->userdata['info_user']['userID'];
		var_dump($session);
		
		/*
		$redis_get = new CI_Redis();
		$value = $redis_get->get('0c3c9b3c24c607314364ed7cf9ce8934');
		var_dump($value);
		*/
	}
	
	public function register()
	{
		$this->megav_core_interface->register();
		//var_dump($this->megav_core_interface->getSessionKeyCache());
	}
}