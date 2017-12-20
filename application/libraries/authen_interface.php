<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class authen_interface
 *
 * @author: tienhm
 * @created_on: 07/01/2015
 * Lop cac phuong thuc giao tiep voi he thong Authen Core
 */

class authen_interface
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->ci->load->library('session_memcached');
        $this->ci->load->library('redis');
        $this->ci->load->library('id_curl');
		$this->ci->load->library('id_encrypt');
		
    }

    /**
     * Ham dang nhap qua Username
     *
     * @author: tienhm
     * @created_on: 07/01/2015
     *  - He thong frontend dung ham nay de login truc tiep cho
     * nhung client khac neu nguoi dung van con session dang nhap
     * tren frontend co yeu cau dang nhap
     */
    public function loginThroughUsername($username, $login_from, $mac_address, $non_epay_account_id, $publisher_id, $ref_clientid, $str_access_token){
        log_message('error', 'library loginThroughUsername');
		$data_array = 'method=loginThroughUsername&clientid=' . CLIENT_ID_OPENID . '&username=' . $username . '&login_from=' . $login_from;
		$data_array .= '&mac_address=' . $mac_address . '&non_epay_account_id=' . $non_epay_account_id . '&publisher_id=' . $publisher_id;
		$data_array .= '&ref_clientid=' . $ref_clientid . '&str_access_token=' . $str_access_token;
        
        $url = URL_AUTHENSERVER;
		$url .= 'clientid=' . CLIENT_ID_OPENID . '&request_data=' . $this->encrypt($data_array);
        //log_message('error', 'Login Through Username: Data gui di:'.print_r($data_array, true));
        //log_message('error', 'Login Through Username: Truoc khi gui di:'.$url);
        $response = $this->ci->id_curl->get_curl($url);
        log_message('error', 'Login Through Username: Du lieu tra ve:'.$response);

        $array_response = json_decode($response, true);
        return $array_response;
    }
	//[14/7/2015] luanbv
	public function getAccessToken($refreshtoken){
		log_message('error', 'library getAccessToken');
		$data_array = 'method=refresh&clientid=' . CLIENT_ID_OPENID . '&refreshtoken=' . $refreshtoken;
		$url = URL_AUTHENSERVER;
		$url .= 'clientid=' . CLIENT_ID_OPENID;
		$url .= '&request_data=' . $this->encrypt($data_array);
		$response = $this->ci->id_curl->get_curl($url);
        log_message('error', 'getAccessToken: Du lieu tra ve:'.$response);
		
        $array_response = json_decode($response, true);
		
        return $array_response;
	}
	
	/*	Phongwm 
	*	2016-11-15
	*	Function Login bằng user pass
	*/
	
	public function login_authen_user_pas($clientId, $uname, $pass, $mac_address, $publisher_id, $deviceOS)
	{
		log_message('error', 'library login_authen_user_pas');
		$url = URL_AUTHENSERVER;
		$url .= 'clientid=' . CLIENT_ID_OPENID;
        $url .= '&request_data='. $this->encrypt('method=login&clientid=' . $clientId . '&username=' . $uname . '&password=' . $this->encrypt($pass) . '&mac_address=' . $mac_address . '&publisher_id=' . $publisher_id  . '&sdk_version='.$deviceOS);
        //log_message('error', 'DANG NHAP ||send_info_user:  url truyen di ' . $url);
        $data = $this->ci->id_curl->get_curl($url);
		log_message('error', 'DANG NHAP: Du lieu tra ve:'.$data);
		return $data;
	}
	
	public function user_confirm($clientId, $data)
	{
		log_message('error', 'libary user_confirm');
		$url = URL_AUTHENSERVER;
		$url .= 'clientid=' . CLIENT_ID_OPENID;
        $url .= '&request_data=' . $this->encrypt('method=confirm&clientid=' . $clientId . '&data=' . $data . '&confirm=Yes');
        $info = $this->ci->id_curl->get_curl($url); //thong tin response
		
		return $info;
	}
	
	public function get_client_info($clientID, $myID = null)
	{
		log_message('error', 'library get_client_info');
		$url = URL_AUTHENSERVER;
        if (is_null($myID)) {
            $currentID = CLIENT_ID_OPENID;
        } else {
            $currentID = $myID;
        }
		
		$url .= 'clientid=' . CLIENT_ID_OPENID;
		
        $data = array(
            'method' => 'getClientInf2',
            'clientid' => $currentID,
            'ref_clientid' => $clientID
        );
        $url .= '&request_data=' . $this->encrypt(http_build_query($data));

        $info = $this->ci->id_curl->get_curl($url);
		
		return $info;
	}
	
	public function login_through_email($clientId, $email, $loginfrom, $mac_address, $publisher_id, $idfb)
	{
		log_message('error', 'library login_through_email');
		$url = URL_AUTHENSERVER;
		$url .= 'clientid=' . CLIENT_ID_OPENID;
        $url .= '&request_data=' . $this->encrypt('method=loginThroughEmail&clientid=' . $clientId . '&email=' . $email . '&login_from=' . $loginfrom . '&mac_address=' . $mac_address . '&publisher_id=' . $publisher_id . '&non_epay_account_id=' . $idfb);
        $info_res = $this->ci->id_curl->get_curl($url);
		log_message('error', 'login_through_email: Du lieu tra ve: '.$info_res);
		return $info_res;
	}
	
	public function register($username, $email, $pass, $fullname, $fone, $gen, $birthday, $address, $idNo, $idIssueDate, $add_IssueIdNo, $mac_address, $clientID, $deviceOS)
	{
		log_message('error', 'library register');
		$arrData = 
            'method=register'.
            '&username=' . $username.
            '&email=' . $email.
            '&password=' . $pass.
            '&fullname=' . $fullname.
            '&phone=' . $fone.
            '&sex=' . $gen.
            '&birthday=' . $birthday.
            '&address=' . $address.
            '&id=' . $idNo.
            '&idissuedate=' . $idIssueDate.
            '&idissueplace=' . $add_IssueIdNo.
            '&register_from=' . '1'.
            '&mac_address=' . $mac_address.
            '&clientid=' . $clientID.
            '&non_epay_account_id=' . '-1'.
			'&sdk_version=' . $deviceOS.
			'&phone_status=' . '0'.
			'&email_status=' . '0';
        log_message('error', 'data register: ' . print_r($arrData, true));
        $url = URL_AUTHENSERVER;
        $url .= 'clientid=' . CLIENT_ID_OPENID;
        $url .= '&request_data=' . $this->encrypt($arrData);
        //log_message('error', 'REGISTER:||Thông tin dang ky tu dong gửi lên Server: ' . $url);
        
        $info = $this->ci->id_curl->get_curl($url);
		log_message('error', 'register: Du lieu tra ve: '.$info);
		return $info;
	}
	
	public function get_access_token($clientId, $authCode)
	{
		log_message('error', 'library get_access_token');
		$url = URL_AUTHENSERVER;
		$url .= 'clientid=' . CLIENT_ID_OPENID;
        $url .= '&request_data=' . $this->encrypt('method=access&clientid=' . $clientId . '&authencode=' . $authCode . '&mac_address=');
        $info_response = $this->ci->id_curl->get_curl($url);
		return $info_response;
	}
	
	public function get_user_info($email)
	{
		log_message('error', 'library get_user_info');
		$url = URL_AUTHENSERVER;
		$url .= 'clientid=' . CLIENT_ID_OPENID;
        $url .= '&request_data=' . $this->encrypt('method=getUserInfo&clientid=' . CLIENT_ID_OPENID . '&email=' . $email);
        //log_message('error', 'get_user_info_to_email url ' . $url);
        $info = $this->ci->id_curl->get_curl($url);
		return $info;
	}
	
	public function update_user_info($username, $password, $fullname, $email, $fone, $gen, $birthday, $phone_status, $email_status, $address, $idNo, $id_date, $id_place, $arr_acc_Token)
	{
		log_message('error', 'library update_user_info: ' . $fullname . ' | ' . $address);
		$url = URL_AUTHENSERVER;
		$url .= 'clientid=' . CLIENT_ID_OPENID;
        $url_data = 'method=updateUserInfo&username=' . $username . '&password=' . $password . '&clientid=' . CLIENT_ID_OPENID . '&fullname=' . $fullname;
        $url_data .= '&email=' . $email . '&phone=' . $fone . '&sex=' . $gen . '&birthday=' . $birthday;
        $url_data .= '&address=' . $address . '&id=' . $idNo . '&idissuedate=' . $id_date . '&idissueplace=' . $id_place . '&accesstoken=' . $arr_acc_Token;
		$url_data .= '&phone_status='.$phone_status.'&email_status='.$email_status;
        log_message('error', 'URL REQUEST: ' . $url_data);
		$url .= '&request_data=' . $this->encrypt($url_data);
        //log_message('error', 'UPDATE USER INFO:||Thong tin cap nhat gui len :' . $url);
        $info = $this->ci->id_curl->get_curl($url);
		log_message('error', 'update_user_info: Du lieu tra ve: '.$info);
		return $info;
	}
	
	public function change_pass($is_reset, $password, $old_password, $username)
	{
		log_message('error', 'library change_pass');
		$url = URL_AUTHENSERVER;
		$url .= 'clientid=' . CLIENT_ID_OPENID;
		$param = 'method=resetUserPass&clientid=' . CLIENT_ID_OPENID . '&username=' . $username . '&password=' . $this->encrypt($password);
		if($is_reset == 1)
		{
			$resetpassword = 'true';
		}
		else
		{
			$resetpassword = 'false';
			$param .=  '&oldpassword='.$this->encrypt($old_password);
		}
		$param .= '&resetpassword='.$resetpassword;
		$url .= '&request_data=' . $this->encrypt($param);
		//log_message('error', 'RESET MAT KHAU:||Thong tin reset gui len :' . $url);
		$info = $this->ci->id_curl->get_curl($url);
		log_message('error', 'change_pass: Du lieu tra ve: '.$info);
		return $info;
	}
	
	public function activeUsers($clientId, $username)
	{
		log_message('error', 'library change_pass');
        $url = URL_AUTHENSERVER;
		$url .= 'clientid=' . CLIENT_ID_OPENID;
        $url .= '&request_data=' . $this->encrypt('method=activeUsers&clientid=' . $clientId . '&username=' . $username);
        //log_message('error', 'activeUsers || DUONG DAN active_account ' . $url);
        $info = $this->ci->id_curl->get_curl($url);
		return $info;
	}
	
	public function check_otp($phone, $username, $otp_code)
	{
		log_message('error', 'library check_otp');
        $url = URL_AUTHENSERVER;
		$url .= 'clientid=' . CLIENT_ID_OPENID;
        $url .= '&request_data=' . $this->encrypt('method=otpValidator&clientid=' . CLIENT_ID_OPENID . '&username=' . $username . '&phone=' . $phone . '&otp_code=' . $otp_code);
        //log_message('error', 'otpValidator || DUONG DAN check_otp ' . $url);
        $info = $this->ci->id_curl->get_curl($url);
		return $info;
	}
	
	public function update_phone($username, $phone, $otp_code, $arr_acc_Token, $old_otp_code, $old_phone)
	{
		log_message('error', 'library update_phone');
		$url = URL_AUTHENSERVER;
		$url .= 'clientid=' . CLIENT_ID_OPENID;
        $url_data = 'method=phoneUpdater&username=' . $username . '&clientid=' . CLIENT_ID_OPENID ;
        $url_data .= '&phone=' . $phone . '&otp_code=' . $otp_code;
        $url_data .= '&accesstoken=' . $arr_acc_Token;
		$url_data .= '&old_otp_code=' . $old_otp_code . '&old_phone=' .$old_phone;
		
        $url .= '&request_data=' . $this->encrypt($url_data);
        //log_message('error', 'UPDATE PHONE:||Thong tin cap nhat gui len :' . $url);
        $info = $this->ci->id_curl->get_curl($url);
		log_message('error', 'update_phone: Du lieu tra ve: '.$info);
		return $info;
	}
	
	public function check_active_link($email, $username, $active_link)
	{
		log_message('error', 'library check_active_link' . '-' . $email . '-' . $username . '-' . $active_link);
        $url = URL_AUTHENSERVER;
		$url .= 'clientid=' . CLIENT_ID_OPENID;
        $url .= '&request_data=' . $this->encrypt('method=activeLinkValidator&clientid=' . CLIENT_ID_OPENID . '&username=' . $username . '&active_link=' . $active_link . '&email=' . $email);
        //log_message('error', 'activeLinkValidator || DUONG DAN check_active_link ' . $url);
        $info = $this->ci->id_curl->get_curl($url);
		return $info;
	}
	
	public function update_email($username, $old_email, $new_email, $arr_acc_Token, $active_link)
	{
		log_message('error', 'library update_email');
		$url = URL_AUTHENSERVER;
		$url .= 'clientid=' . CLIENT_ID_OPENID;
        $url_data = 'method=emailUpdater&username=' . $username . '&clientid=' . CLIENT_ID_OPENID ;
        $url_data .= '&email=' . $new_email . '&old_email=' . $old_email;
        $url_data .= '&accesstoken=' . $arr_acc_Token;
		$url_data .= '&active_link=' . $active_link;
		
        $url .= '&request_data=' . $this->encrypt($url_data);
        log_message('error', 'UPDATE EMAIL:||Thong tin cap nhat gui len :' . $url);
        $info = $this->ci->id_curl->get_curl($url);
		log_message('error', 'update_email :||Thong tin tra ve :' . print_r($info, true));
		return $info;
	}
	
	
	public function check_link_reset_pass($active_link)
	{
		log_message('error', 'library check_link_reset_pass: ' . $active_link);
        $url = URL_AUTHENSERVER;
		$url .= 'clientid=' . CLIENT_ID_OPENID;
        $url .= '&request_data=' . $this->encrypt('method=resetActiveLinkValidator&clientid=' . CLIENT_ID_OPENID . '&activeCode=' . $active_link);
        //log_message('error', 'activeLinkValidator || DUONG DAN check_link_reset_pass ' . $url);
        $info = $this->ci->id_curl->get_curl($url);
		log_message('error', 'DATA respone: ' . print_r($info, true));
		return $info;
	}
	
	public function reset_password($pass, $activecode)
	{
		log_message('error', 'library reset_password');
		$url = URL_AUTHENSERVER;
		$url .= 'clientid=' . CLIENT_ID_OPENID;
		$param = 'method=resetUserPass&clientid=' . CLIENT_ID_OPENID . '&password=' . $this->encrypt($pass);
		$param .= '&resetpassword=true&activeCode=' . $activecode;
		$url .= '&request_data=' . $this->encrypt($param);
		//log_message('error', 'RESET MAT KHAU:||Thong tin reset gui len :' . $url);
		$info = $this->ci->id_curl->get_curl($url);
		log_message('error', 'reset_password: Du lieu tra ve: '.$info);
		return $info;
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