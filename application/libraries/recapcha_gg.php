<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class authen_interface
 *
 * @author: tienhm
 * @created_on: 07/01/2015
 * Lop cac phuong thuc giao tiep voi he thong Authen Core
 */

class recapcha_gg
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->ci->load->library('session_memcached');
		
    }

	/*
	* $response => $input['g-recaptcha-response'] trong form recapcha google
	*/
    public function verifyReCapcha($response)
	{
		log_message('error', 'verifyReCapcha');
		$this->ci->session_memcached->get_userdata();
		$ip_client = $this->ci->session_memcached->userdata('ip_address');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, API_GOOGLE_RECAPTCHA_URL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'secret'    =>  API_GOOGLE_RECAPTCHA_SECRET,
            'response'  =>  $response,
            'remoteip'  =>  $ip_client
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        try {
            $responseAPI = curl_exec($ch);
            if ($responseAPI) {
                $responseAPI = json_decode($responseAPI);
                if ($responseAPI->success == 'true') {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
	}
	
}