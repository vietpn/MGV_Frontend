<?php defined('BASEPATH') OR exit('No direct script access allowed');

class megav_libs
{

    public function __construct()
    {
        $this->ci = & get_instance();
    }

	
	public function genarateTransactionId($prefix = null)
	{
		if($prefix == null)
			$prefix = "MGVRQ_";
		
		$transId = $prefix . date('hms') . uniqid();
		return $transId;
	}
								
	public function page_result($messa, $redirect_link = null, $timeRedirect = null, $redirectIframelink = null, 
								$success = null, $buttonNameRediect = null, $cancelUrl = null, $buttonNameCancel = null, 
								$balance=null, $background = null)
    {
        $mess = array();
		$data = array();
        $mess['mess'] = $messa;
		if ($balance != null)
            $mess['balance'] = $balance; 
        if ($redirect_link != null)
            $mess['redirect_link'] = $redirect_link; 
		if ($timeRedirect != null)
            $mess['timeRedirect'] = $timeRedirect;
		if ($redirectIframelink != null)
            $mess['targetIframeLink'] = $redirectIframelink;
		if ($success != null)
            $data['success'] = $success;
		if ($cancelUrl != null)
            $mess['cancelUrl'] = $cancelUrl;
		if ($buttonNameRediect != null)
            $mess['buttonNameRediect'] = $buttonNameRediect;
		if ($buttonNameCancel != null)
            $mess['buttonNameCancel'] = $buttonNameCancel;
		if ($background != null)
            $data['background'] = $background;
		
        $this->ci->view['content'] = $this->ci->load->view('result/result', $mess, true);
		$data['data'] = $this->ci->view;
        $this->ci->load->view('Layout/layout', $data);
    }
	
	public function page_result_reset_pass($messa, $redirect_link = null, $timeRedirect = null, $redirectIframelink = null)
    {
        $mess = array();
        $mess['mess'] = $messa;
        if ($redirect_link != null)
            $mess['redirect_link'] = $redirect_link; 
		if ($timeRedirect != null)
            $mess['timeRedirect'] = $timeRedirect;
		if ($redirectIframelink != null)
            $mess['targetIframeLink'] = $redirectIframelink;
        $this->ci->view['content'] = $this->ci->load->view('result/result', $mess, true);
        //$this->ci->load->view('Layout/layout', array('data' => $this->ci->view));
        $this->ci->load->view('Layout/layout_active_pass', array('data' => $this->ci->view));
    }
	
	public function page_result_login($messa, $redirect_link = null, $timeRedirect = null, $redirectIframelink = null, $error = null)
    {
        $mess = array();
        $mess['mess'] = $messa;
        if ($redirect_link != null)
            $mess['redirect_link'] = $redirect_link; 
		if ($timeRedirect != null)
            $mess['timeRedirect'] = $timeRedirect;
		if ($redirectIframelink != null)
            $mess['targetIframeLink'] = $redirectIframelink;
		if ($error != null)
            $mess['error'] = $error;
        $this->ci->load->view('result/result_login', $mess);
        //$view = $this->ci->load->view('result/result_login', $mess, true);
		//return $view;
        
    }
	
	public function page_result_register($messa, $redirect_link = null, $timeRedirect = null, $redirectIframelink = null, $error = null)
    {
        $mess = array();
        $mess['mess'] = $messa;
        if ($redirect_link != null)
            $mess['redirect_link'] = $redirect_link; 
		if ($timeRedirect != null)
            $mess['timeRedirect'] = $timeRedirect;
		if ($redirectIframelink != null)
            $mess['targetIframeLink'] = $redirectIframelink;
		if ($error != null)
            $mess['error'] = $error;
        $view = $this->ci->load->view('result/result_register', $mess, true);
		return $view;
        
    }
	
	
	public function page_result_payment($messa, $redirect_link = null, $timeRedirect = null, $buttonNameRediect = null, 
										$cancelUrl = null, $buttonNameCancel = null, $background = null, $schema_url = null)
    {
		$data = array();
        $mess = array();
        $mess['mess'] = $messa;
        if ($redirect_link != null)
            $mess['redirect_link'] = $redirect_link; 
		if ($timeRedirect != null)
            $mess['timeRedirect'] = $timeRedirect;
		if ($cancelUrl != null)
            $mess['cancelUrl'] = $cancelUrl;
		if ($buttonNameRediect != null)
            $mess['buttonNameRediect'] = $buttonNameRediect;
		if ($buttonNameCancel != null)
            $mess['buttonNameCancel'] = $buttonNameCancel;
		if ($schema_url != null)
            $mess['schema_url'] = $schema_url;
		if ($background != null)
            $data['background'] = $background;
		
        $this->ci->view['content'] = $this->ci->load->view('result/result_payment', $mess, true);
		$data['data'] = $this->ci->view;
        $this->ci->load->view('Layout/layout', $data);
    }
	
    public function genarateAccessToken()
	{
		$this->ci->load->library('session_memcached');
		$this->ci->session_memcached->get_userdata();
		
		//log_message('error', 'signature in access : ' . print_r($this->ci->session_memcached->userdata ['info_user']['signature'], true));
		
        $arr_acc_Token = $this->encrypt(json_encode(array(
            'value' => $this->ci->session_memcached->userdata['info_user']['access_token'],
            'clientID' => CLIENT_ID_OPENID,
            'email' => $this->ci->session_memcached->userdata ['info_user']['email'],
            'expiretime' => $this->ci->session_memcached->userdata ['info_user']['Au_ExpiredDate'],
            'signature' => $this->ci->session_memcached->userdata ['info_user']['signature'],
			'username' => $this->ci->session_memcached->userdata ['info_user']['userID']
        )));
		
		//$arr_acc_Token = $this->encrypt(json_encode(array(
        //    'value' => 'baa866a4e1d56e5bdad1d969d0c57d28',
        //    'clientID' => CLIENT_ID_OPENID,
        //    'email' => 'thuypt@vnptepay.com.vn',
        //    'expiretime' => '20170523103736',
        //    'signature' => 'bT98bd/rKfB3j2IFOc1SY4zNgA+qcfEKSCBOswQipC65u25qKVyezFfzTSsnfn1XBbye4nN2rGoY7UxGcZ7mQYUoWvFuacbOdhWb239d2TF7z2xOzpqJRu/o0oLqjINYNcGxg0zdmXXji4V5rm1cKH7mHPgXgj1iOd1rWIgwX3Q='
        //)));
		
		
		return $arr_acc_Token;
	}
	
	public function saveSourceUrl($url)
	{
		log_message('error', 'URL REDIRECT: ' . $url);
		$this->ci->load->helper('cookie');
		$session = $this->ci->input->cookie("megav_session");
		$session = $this->_unserialize($session);
		$this->ci->load->library('redis');
		$redis = new CI_Redis();
		$keyRedis = SOURCE_URL_KEY_PREFIX . $session['session_id'];
		$redis->set($keyRedis, $url);
		$redis->expire($keyRedis, SOURCE_URL_TTL);
		return true;
	}
	
	public function saveCookieUserData($transId, $dataRedis = array(), $prefix = null)
	{
		//$prefix = 'SRM';
		log_message('error', 'data save redis: ' . $transId . ' | ' . $prefix . ' | ' . print_r($dataRedis, true));
		$this->ci->load->helper('cookie');
		$session = $this->ci->input->cookie("megav_session");
		$session = $this->_unserialize($session);
		$session['user_data'] = $transId;
		$this->ci->load->library('session_memcached');
		$this->ci->session_memcached->_set_cookie($session);
		
		$this->ci->load->library('redis');
		$dataRedis['ip_client'] = $this->ci->input->ip_address();
		$redis = new CI_Redis();
		if($prefix != null)
			$keyRedis = $session['session_id'] . $prefix;
		else
			$keyRedis = $session['session_id'] . 'DF';
		log_message('error', 'key redis: ' . $keyRedis);
		$redis->set($keyRedis, json_encode($dataRedis));
		$redis->expire($keyRedis, TRANS_DATA_TTL);
		return true;
	}
	
	public function getDataTransRedis($prefix = null)
	{
		$this->ci->load->helper('cookie');
		$session = $this->ci->input->cookie("megav_session");
		$session = $this->_unserialize($session);
		
		//if(isset($session['user_data']) && !empty($session['user_data'])) 
		//{
			$this->ci->load->library('redis');
			$redis = new CI_Redis();
			
			for($retry = 1; $retry <= RETRY_GET_UINFO_REDIS; $retry++)
			{
				if($prefix != null)
					$keyRedis = $session['session_id'] . $prefix;
				else
					$keyRedis = $session['session_id'] . 'DF';
				log_message('error', 'key redis: ' . $keyRedis);
				$dataRedis = $redis->get($keyRedis);
				log_message('error', 'get data trans lan : ' . $retry . ' | trans data: ' . print_r($dataRedis, true));
				if(!is_null($dataRedis) && !empty($dataRedis))
					break;
			}
				//$dataRedis = $redis->get($session['user_data']);
			
			if(!empty($dataRedis))
			{
				$dataRedis = json_decode($dataRedis, true);
				if(isset($dataRedis['ip_client']) && $dataRedis['ip_client'] == $this->ci->input->ip_address())
				{
					return $dataRedis;
				}
				else
				{
					log_message('error', 'SAI IP: ' . print_r($this->ci->input->ip_address(), true));
					return false;
				}
			}
			else
			{
				log_message('error', 'K get dc data trans trong redis');
				return false;
			}
		//}
		//else
		//{
		//	log_message('error', 'K get session_id trong cookie');
		//	return false;
		//}
	}
	
	public function remove_data()
    {
		log_message('error', 'megav_libs | remove data');
		$this->ci->load->library('session_memcached');
        $this->ci->session_memcached->unset_userdata('user_data');
        $this->ci->session_memcached->unset_userdata('info_user');
        //del danh sach redis
        delete_cookie('clientId');
        delete_cookie('megav_session');
        delete_cookie('ci_session');
        foreach ($_COOKIE as $key => $value) {
            if (stripos($key, '_authen')) {
                delete_cookie($key);
            }
        }
        return true;
    }
	
	private function encrypt($text)
    {
        $key = base64_decode(KEY_DECODE);
        $size = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
        $text = $this->pkcs5_pad($text, $size);
        $bin = pack('H*', bin2hex($text));
        $size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($size, MCRYPT_RAND);
        $encrypted = bin2hex(mcrypt_encrypt(MCRYPT_3DES, $key, $bin, MCRYPT_MODE_ECB, $iv));
        return $encrypted;
    }
	
	private function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
	
	public function _unserialize($data)
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
    public function saveFlashCachedOtp($transId,$username)
	{
		log_message('error', 'OTP Flash: ' . $transId.'|'.$username);
		$this->ci->load->helper('cookie');
		$session = $this->ci->input->cookie("megav_session");
		$session = $this->_unserialize($session);
		$this->ci->load->library('redis');
		$redis = new CI_Redis();
		$keyRedis = SESSION_KEY_TTL . '_'.$transId.'_'.$username;
		$redis->set($keyRedis, $transId.'_'.$username);
		$redis->expire($keyRedis, SESSION_KEY_TTL);
		return true;
	}
	/*public function updateFlashCachedOtp($transId,$username)
	{
		log_message('error', 'OTP Flash: ' . $transId.'|'.$username);
		$this->ci->load->helper('cookie');
		$session = $this->ci->input->cookie("megav_session");
		$session = $this->_unserialize($session);
		$this->ci->load->library('redis');
		$redis = new CI_Redis();
		$keyRedis = SESSION_KEY_TTL . $session['session_id'].'_'.$transId.'_'.$username;
		$redis->set($keyRedis, $transId.'_'.$username.'_1');// bằng 1 là đã gửi và đã xác nhận
		$redis->expire($keyRedis, SESSION_KEY_TTL);
		return true;
	}*/
	public function getFlashCachedOtp($transId,$username){
		$this->ci->load->helper('cookie');
		$session = $this->ci->input->cookie("megav_session");
		$session = $this->_unserialize($session);
		$this->ci->load->library('redis');
		$redis = new CI_Redis();
		$keyRedis = SESSION_KEY_TTL . '_'.$transId.'_'.$username;
		$dataRedis = $redis->get($keyRedis);
		if(!empty($dataRedis)){
			return $dataRedis;
		}else{
			log_message('error', 'K get dc data trans trong redis');
			return false;
		}
	}

}