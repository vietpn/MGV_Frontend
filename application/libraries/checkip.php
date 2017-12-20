<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class authen_interface
 *
 * @author: tienhm
 * @created_on: 07/01/2015
 * Lop cac phuong thuc giao tiep voi he thong Authen Core
 */

class checkip
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->ci->load->library('session_memcached');
        $this->ci->load->library('id_curl');
		$this->ci->load->library('id_encrypt');
		$this->ci->load->library('redis');
		
    }

    public function check_ip($action = null)
	{
		$this->ci->load->config('warnning_ip');
		$wn_acc = $this->ci->config->item('acc_access');
		$this->ci->session_memcached->get_userdata();
		$ip_client = $this->ci->session_memcached->userdata('ip_address');
		$time = date('Y-m-d h:i:s', time());
		$email = '';
		$username = isset($this->ci->session_memcached->userdata['info_user']['userID']) ? $this->ci->session_memcached->userdata['info_user']['userID'] : '' ;
		//foreach($wn_ip as $ip)
		//{
			if($this->search_ip($ip_client))
			{
				$data_request = array('method' 		=> 6, 
							  'username' 	=> $username, 
							  'clientID' 	=> $ip_client,
							  'source_url' 	=> $time,
							  'link_client' => $action,
							  'email' 		=> $email);
						
				$request = $this->encrypt_notify(json_encode($data_request));
				log_message('error', 'Notify IP');
				
				// save redis warnning
				$redis = new CI_Redis();
				$redis->sadd('WARNING_IP_ACC', $request);
				//$arr = $redis->sMembers('');
				
				/*
					$url = URL_SERVICE_NOTIFY . "?request=$request";
					log_message('error', 'URL: ' . $url);
					try{
						$result = $this->ci->id_curl->get_curl($url);
						$result = json_decode($result);
						log_message('error', 'WS return : '.print_r($result, true));
					} catch (Exception $e){
						log_message('error', 'Exception notify IP');
					}
				*/
			}
		//}
		
		
		if($username != '')
		{
			foreach($wn_acc as $acc)
			{
				if($acc == $username)
				{
					$data_request = array('method' 		=> 6, 
								  'username' 	=> $username, 
								  'clientID' 	=> $ip_client,
								  'source_url' 	=> $time,
								  'link_client' => $action,
								  'email' 		=> $email);
			
					$request = $this->encrypt_notify(json_encode($data_request));
					//$redis = new CI_Redis();
					//$redis->sadd('WARNING_IP_ACC', $request);
					
						$url = URL_SERVICE_NOTIFY . "?request=$request";
						log_message('error', 'URL: ' . $url);
						try{
							$result = $this->ci->id_curl->get_curl($url);
							$result = json_decode($result);
							log_message('error', 'WS return : '.print_r($result, true));
						} catch (Exception $e){
							
						}
					
				}
			}
		}
		
	}
	
	private function search_ip($client_ip)
	{
		/* check ip theo config */
		$this->ci->load->config('warnning_ip');
		$wn_ip = $this->ci->config->item('ip_access');
		$warnning = 0;
		foreach($wn_ip as $ip)
		{
			$stt = strpos($ip, '*');
			if($stt)
			{
				$warnning = substr($ip, 0, $stt);
				if(preg_match('/^'.$warnning.'/', $client_ip))
					$warnning = 1;			
			}
			else
			{
				if($client_ip == $ip)
					$warnning = 1;
			}
		}
		
		if(MEGAID_WARNNING_GEO_IP == '1')
		{
			log_message('error', 'Check IP theo country');
			/* check ip theo country */
			include_once('geoip.inc');
			if((strpos($client_ip, ":") === false)) {
				//ipv4
				$gi = geoip_open(PATH_GEOIP . "/GeoIP.dat",GEOIP_STANDARD);
				$country = geoip_country_code_by_addr($gi, $client_ip);
			}
			else {
				//ipv6
				$gi = geoip_open(PATH_GEOIP . "/GeoIPv6.dat",GEOIP_STANDARD);
				$country = geoip_country_code_by_addr_v6($gi, $client_ip);
			}
			if($country != '' && trim($country) != 'VN')
				$warnning = 1;
		}
		
		if($warnning == 1)
			return true;
		else
			return false;
	}
	
	
	private function encrypt_notify($text)
    {
        $key = base64_decode(KEY_ENCODE);
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
}