<?php defined('BASEPATH') OR exit('No direct script access allowed');

class gen_access_token
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->ci->load->library('session_memcached');
    }

    public function genarateAccessToken()
	{
		$this->ci->session_memcached->get_userdata();
        $arr_acc_Token = $this->encrypt(json_encode(array(
            'value' => $this->ci->session_memcached->userdata['info_user']['access_token'],
            'clientID' => CLIENT_ID_OPENID,
            'email' => $this->ci->session_memcached->userdata ['info_user']['email'],
            'expiretime' => $this->ci->session_memcached->userdata ['info_user']['Au_ExpiredDate'],
            'signature' => $this->ci->session_memcached->userdata ['info_user']['signature']
        )));
		return $arr_acc_Token;
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
}