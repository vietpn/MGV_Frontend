<?php defined('BASEPATH') OR exit('No direct script access allowed');

class megav_core_interface
{
	// Param Request toi megav core
	var $processing_code 	= "";
	var $data			 	= "";
	var $partner_id 		= "";
	var $partner_pass 		= "";
	var $mac 				= "";
	var $client_os 			= "";
	var $client_ip 			= "";
	var $merchantId 		= "";
	var $signature 			= "";
	
	var $dataDownloadItems 		= ""; // Danh sách các items softpin cần download
	
	var $dataDownloadItemsProductId 		= ""; // Id sản phẩm
	var $dataDownloadItemsProductQuantity 	= ""; // Số lượng sản phẩm cần mua
	var $dataDownloadItemscCardSerial 		= ""; // Serial thẻ
	var $dataDownloadItemscCardPin 			= ""; // Mã thẻ
	var $dataDownloadItemscExpiredDate 		= ""; // Ngày hết hạn định dạng yyyy-mm-dd
	var $dataDownloadItemscTelcoCode 		= ""; // Mã telco
	
	var $sessionKey = "";
	
    public function __construct() {
        $this->ci = & get_instance();
        $this->ci->load->library('redis');
        $this->ci->load->library('id_curl');
		$this->ci->load->library('id_encrypt');
		
		$this->partner_id = PARTNER_ID;
		$this->partner_username = PARTNER_UNAME;
		$this->partner_pass = PARTNER_PASS;
		$this->client_ip = $this->ci->input->ip_address();
		$this->client_os = trim(substr($this->ci->input->user_agent(), 0, 120));
    }

	/*	Function dang ky
	** 	param: 	username: ten tai khoan
	**			phone: so dien thoai
	**			password: mat khau
	**			tranid: ma giao dich voi core
	*/
	public function register($userName, $phone, $fullname, $email, $password, $transId = null) {
		log_message('error', 'library register');
		$this->processing_code = '8000';
		$data = new stdClass();
		$data->user_name = $userName;
		$data->birthday = "";
		$data->fullname = $fullname;
		$data->address = "";
		
		if(empty($email))
			$data->email = "";
		else
			$data->email = $email;
		
		if(empty($phone))
			$data->phone = "";
		else
			$data->phone = $phone;
		
		$data->password = $password;
		if(is_null($transId))
			$data->request_id = "RGT" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		/*
		$key3des = $this->getSessionKeyCache();
		if(!$key3des)
			$key3des = $this->getSessionKeyServer();
		*/
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		//log_message('error', 'data request register : ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone register: ' . print_r($requestMegaV, true));
		
		//$requestMegaV = '{"status":"00"}';
		
		return $requestMegaV;
	}
	
	/*	Function kich hoat tai khoan bang otp
	** 	param: 	username: ten tai khoan
	**			phone: so dien thoai
	**			password: mat khau
	**			otp: ma xac thuc so dien thoai
	**			tranid: ma giao dich voi core
	*/
	public function acctiveAccount($userName, $phone, $fullname, $email, $password, $otp, $transId = null) {
		log_message('error', 'library acctiveAccount');
		$this->processing_code = '1027';
		$data = new stdClass();
		if(empty($email))
			$data->email = "";
		else
			$data->email = $email;
		
		if(empty($phone))
			$data->phone = "";
		else
			$data->phone = $phone;
		
		$data->user_name = $userName;
		$data->email = "";
		$data->birthday = "";
		$data->fullname = $fullname;
		$data->address = "";
		$data->password = $password;
		$data->otp = $otp;
		if(is_null($transId))
			$data->request_id = "RGT" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		/*
		$key3des = $this->getSessionKeyCache();
		if(!$key3des)
			$key3des = $this->getSessionKeyServer();
		*/
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		//log_message('error', 'data request acctiveAccount: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone acctiveAccount: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	/*	Function gen link reset mat khau
	** 	Param: 	
	** 			$userName: ten tai khoan
	**			$email: email
	*/
	public function genarateLinkReset($email, $userName, $transId) {
		$this->processing_code = "1029"; // chuyen ra cau hinh
		$data = new stdClass();
		$data->email = $email;
		$data->user_name = $userName;
		if(is_null($transId))
			$data->request_id = "RGT" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		log_message('error', 'data request genarateLinkReset: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}

	/*	Function reset mat khau
	** 	Param: 	$phone: so dien thoai
	** 			$userName: ten tai khoan
	**			$email: email
	**			$step: cac buoc quen mat khau
	**			$otp: Max xac thuc
	*/
	public function resetPassWord($email, $phone, $userName, $otp, $pass, $transId) {
		$this->processing_code = "1012";
		$data = new stdClass();
		if(empty($email))
			$data->email = "";
		else
			$data->email = $email;
		
		if(empty($phone))
			$data->phone = "";
		else
			$data->phone = $phone;
		
		$data->user_name = $userName;
		
		if(is_null($transId))
			$data->request_id = "RGT" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		log_message('error', 'data request resetPassWord: ' . print_r($data, true));
		$data->otp = $otp;
		$data->password = $pass;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	/*	Function reset mat khau cap 2
	** 	Param: 	$phone: so dien thoai
	** 			$userName: ten tai khoan
	**			$email: email
	**			$otp: Max xac thuc
	**			$passLv2: Mat khau cap 2
	**			$transId: transId
	*/
	public function resetPassWordLv2($email, $phone, $userName, $otp, $passLv2, $transId) {
		$this->processing_code = "1035";
		$data = new stdClass();
		if(empty($email))
			$data->email = "";
		else
			$data->email = $email;
		
		if(empty($phone))
			$data->phone = "";
		else
			$data->phone = $phone;
		
		$data->user_name = $userName;
		
		if(is_null($transId))
			$data->request_id = "RP2" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		log_message('error', 'data request resetPassWordLv2: ' . print_r($data, true));
		$data->pass_lv2 = $passLv2;
		$data->otp = $otp;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	/*	Function them moi hinh thuc xac thuc
	** 	Param: 	$phone: so dien thoai
	** 			$userName: ten tai khoan
	**			$passLv2: mat khau cap 2
	**			$securityType: loai hinh xa thuc 1: OTP, 2: mat khau cap 2
	**			$securitySubType: loai hinh gui ma xac thuc 1: sdt, 2: email
	*/
	public function updateSecurity($userName, $email, $phone, $passLv2, $securityType, $securitySubType, $otp, $transId) {
		$this->processing_code = "1017";
		$data = new stdClass();
		
		if(empty($email))
			$data->email = "";
		else
			$data->email = $email;
		
		if(empty($phone))
			$data->phone = "";
		else
			$data->phone = $phone;
		
		$data->user_name = $userName;
		$data->security_method = $securityType;
		if(!empty($securitySubType))
			$data->security_sub_method = $securitySubType;
		
		if(is_null($transId))
			$data->request_id = "USM" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		log_message('error', 'data request updateSecurity: ' . print_r($data, true));
		if(!empty($otp))
			$data->otp = $otp;
		if(!empty($passLv2))
			$data->pass_lv2 = $passLv2;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		
		return $requestMegaV;
		
	}
	
	/*	Function thay doi mat khau
	** 	Param: 	$phone: so dien thoai
	** 			$userName: ten tai khoan
	**			$otp: mat khau cap 2
	**			$pass: mat khau
	*/
	public function changePass($email, $phone, $userName, $otp, $pass, $transId) {
		
		$this->processing_code = "1010";
		$data = new stdClass();
		if(empty($email))
			$data->email = "";
		else
			$data->email = $email;
		
		if(empty($phone))
			$data->phone = "";
		else
			$data->phone = $phone;
		
		$data->user_name = $userName;
		$data->password = $pass;
		if(is_null($transId))
			$data->request_id = "CHP" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		log_message('error', 'data request changePass: ' . print_r($data, true));
		$data->otp = $otp;
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	/*	Function thay doi mat khau cap 2
	** 	Param: 	$phone: so dien thoai
	** 			$userName: ten tai khoan
	**			$passLv2: mat khau cap 2
	**			$securityType: loai hinh xa thuc 1: OTP, 2: mat khau cap 2
	**			$securitySubType: loai hinh gui ma xac thuc 1: sdt, 2: email
	*/
	public function changePassLv2($pasLv2Old, $pasLv2New, $userName, $transId){
		$this->processing_code = "1034";
		$data = new stdClass();
		
		$data->user_name = $userName;
		if(is_null($transId))
			$data->request_id = "CP2" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		log_message('error', 'data request changePassLv2: ' . print_r($data, true));
		$data->pass_lv2 = $pasLv2New;
		$data->old_pass_lv2 = $pasLv2Old;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		 
		//$requestMegaV = new stdClass();
		//$requestMegaV->status = '00';
		//$requestMegaV = json_encode($requestMegaV);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	/*	Function thay email
	** 	Param: 	$emailOld: email cũ
	** 			$emailNew: email mới
	**			$userName: tên tài khoản
	**			$otp: mã OTP
	**			$password: Mật khẩu đăng nhập
	**			$transId: transId
	**			$phone: Số điện thoại
	**			$accessToken: access token
	*/
	public function changeEmail($emailOld, $emailNew, $userName, $otp, $password, $transId, $phone, $accessToken){
		$this->processing_code = "1009";
		$data = new stdClass();
		$data->new_email = $emailNew;
		$data->user_name = $userName;
		$data->password = $password;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		if(empty($emailOld))
			$data->email = "";
		else
			$data->email = $emailOld;
		
		if(empty($phone))
			$data->phone = "";
		else
			$data->phone = $phone;
		
		
		if(is_null($transId))
			$data->request_id = "CEM" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		log_message('error', 'data request changeEmail: ' . print_r($data, true));
		$data->otp = $otp;
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		 
		//$requestMegaV = new stdClass();
		//$requestMegaV->status = '00';
		//$requestMegaV = json_encode($requestMegaV);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	/*	Function gui mail kích hoạt email
	** 	Param: 	$userName: tên tài khoản
	**			$transId: transId
	*/
	public function sendMailActiveEmail($userName, $transId){
		$this->processing_code = "1068";
		$data = new stdClass();
		$data->user_name = $userName;
		
		if(is_null($transId))
			$data->request_id = "SEA" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		log_message('error', 'data request sendMailActiveEmail: ' . print_r($data, true));
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	public function activeEmail($userName, $accessToken, $activecode, $transId){
		$this->processing_code = "1069";
		$data = new stdClass();
		$data->user_name = $userName;
		
		if(is_null($transId))
			$data->request_id = "AEM" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		$data->access_token = $accessToken;
		$data->active_code = $activecode;
		$data->client_id = CLIENT_ID_OPENID;
		log_message('error', 'data request ActiveEmail: ' . print_r($data, true));
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	/*	Function thay đổi số điện thoại
	** 	Param: 	$phonelOld: số điện thoại cũ
	** 			$phoneNew: số điện thoại mới
	**			$userName: tên tài khoản
	**			$otp: mã OTP
	**			$password: Mật khẩu đăng nhập
	**			$transId: transId
	**			$email: email
	**			$accessToken: access token
	*/
	public function changePhone($phonelOld, $phoneNew, $userName, $otp, $password, $transId, $email, $accessToken){
		$this->processing_code = "1006";
		$data = new stdClass();
		$data->user_name = $userName;
		$data->password = $password;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		$data->phone = $phoneNew;
		
		if(empty($email))
			$data->email = "";
		else
			$data->email = $email;
		
		if(empty($phonelOld))
			$data->old_phone = "";
		else
			$data->old_phone = $phonelOld;
		
		if(is_null($transId))
			$data->request_id = "CPN" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		log_message('error', 'data request change phone: ' . print_r($data, true));
		$data->otp = $otp;
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		 
		//$requestMegaV = new stdClass();
		//$requestMegaV->status = '00';
		//$requestMegaV = json_encode($requestMegaV);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	/*	Function thay đổi số điện thoại
	** 	Param: 	$phoneNew: số điện thoại mới
	**			$userName: tên tài khoản
	**			$otp: mã OTP
	**			$transId: transId
	**			$email: email
	**			$accessToken: access token
	*/
	public function insertPhone($phoneNew, $userName, $otp, $transId, $email, $accessToken){
		$this->processing_code = "1049";
		$data = new stdClass();
		$data->user_name = $userName;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		$data->phone = $phoneNew;
		
		if(empty($email))
			$data->email = "";
		else
			$data->email = $email;
		
		if(is_null($transId))
			$data->request_id = "CPN" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		log_message('error', 'data request change phone: ' . print_r($data, true));
		$data->otp = $otp;
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		 
		//$requestMegaV = new stdClass();
		//$requestMegaV->status = '00';
		//$requestMegaV = json_encode($requestMegaV);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	/*	Function xác thực số điện thoại
	** 	Param: 	$phonel: số điện thoại
	**			$userName: tên tài khoản
	**			$otp: mã OTP
	**			$transId: transId
	**			$email: email
	*/
	public function verifyPhone($userName, $email, $phone, $otp, $accessToken, $transId) {
		$this->processing_code = "1007";
		$data = new stdClass();
		$data->user_name = $userName;
		$data->email = $email;
		$data->phone = $phone;
		$data->client_id = CLIENT_ID_OPENID;
		$data->access_token = $accessToken;
		
		log_message('error', 'data request verifyPhone: ' . print_r($data, true));
		$data->otp = $otp;
		if(is_null($transId))
			$data->request_id = "VRP" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	/*	Function Cập nhật thông tin chứng minh thư
	** 	Param: 	$userName: tên tài khoản
	**			$idFullName: họ và tên
	**			$birthday: ngày sinh
	**			$idNo: số CMT
	**			$idPlace: nơi cấp
	**			$idDate: ngày cấp
	**			$idImagesFront: link ảnh mặt trc
	**			$idImagesBack: link ảnh mặt sau
	**			$accessToken: accessToken
	**			$transId: transId
	*/
	public function updateIdInfo($userName, $idFullName, $birthday, $idNo, $idPlace, $idDate, 
								 $idImagesFront, $idImagesBack, $accessToken, $transId) {
		$this->processing_code = "1011";
		$data = new stdClass();
		$data->user_name = $userName;
		$data->id_fullname = $idFullName;
		$data->id_no = $idNo;
		$data->id_issued_place = $idPlace;
		$data->id_issued_date = $idDate;
		$data->id_img_f = $idImagesFront;
		$data->id_img_b = $idImagesBack;
		$data->birthday = $birthday;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		if(is_null($transId))
			$data->request_id = "UII" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		log_message('error', 'data request updateIdInfo: ' . print_r($data, true));
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	/*	Function xác thực chứng minh thư
	** 	Param: 	$userName: tên tài khoản
	**			$accessToken: accessToken
	**			$transId: transId
	*/
	public function verifyId($userName, $transId){
		$this->processing_code = "1037";
		$data = new stdClass();
		$data->user_name = $userName;
		if(is_null($transId))
			$data->request_id = "VRI" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		log_message('error', 'data request verifyId: ' . print_r($data, true));
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		
		
		//$requestMegaV = new stdClass();
		//$requestMegaV->status = '19';
		//$requestMegaV = json_encode($requestMegaV);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	/*
	*	Test send OTP to email
	*/
	public function genOTPToEmail($email, $phone, $uname, $transId = null) {
		$this->processing_code = "1036";
		$data = new stdClass();
		if(empty($email))
			$data->email = "";
		else
			$data->email = $email;
		
		if(empty($phone))
			$data->phone = "";
		else
			$data->phone = $phone;
		
		$data->user_name = $uname;
		if($transId == null)
			$data->request_id = "GOTP" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		//$key3des = $this->getSessionKeyCache();
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request genOTPToEmail: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		
		//$requestMegaV = new stdClass();
		//$requestMegaV->status = '00';
		//$requestMegaV = json_encode($requestMegaV);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	/*
	*	Test send OTP to phone
	*/
	public function genOTP($email, $phone, $uname, $transId = null) {
		$this->processing_code = "1028";
		$data = new stdClass();
		if($transId == null)
			$data->request_id = "GOTP" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		$data->user_name = $uname;
		
		if(empty($email))
			$data->email = "";
		else
			$data->email = $email;
		
		if(empty($phone))
			$data->phone = "";
		else
			$data->phone = $phone;
		
		//$data->user_name = $uname;
		
		//$key3des = $this->getSessionKeyCache();
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		
		log_message('error', 'data request genOTP: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		
		//$requestMegaV = '{"status":"18"}';
		
		return $requestMegaV;
	}
	
	/*	Function valid otp
	** 	
	*/
	public function validOtp($email, $phone, $userName, $otp, $transId) {
		log_message('error', 'library validOtp');
		$this->processing_code = "1033";
		$data = new stdClass();
		if(empty($email))
			$data->email = "";
		else
			$data->email = $email;
		
		if(empty($phone))
			$data->phone = "";
		else
			$data->phone = $phone;
		$data->user_name = $userName;
		$data->request_id = $transId;
		log_message('error', 'data request validOtp: ' . print_r($data, true));
		$data->otp = $otp;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	/*	Function valid pass2
	** 	
	*/
	public function validPassLv2($userName, $passLv2, $transId) {
		log_message('error', 'library validPassLv2');
		$this->processing_code = "1044";
		$data = new stdClass();
		$data->user_name = $userName;
		
		$data->request_id = $transId;
		
		log_message('error', 'data request validPassLv2: ' . print_r($data, true));
		$data->pass_lv2 = $passLv2;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	/*	Function lấy thông tin đối tác: bank, vietel, vina...
	** 	Param: 	$transId: transId
	*/
	public function getProvider($transId, $subtype = null) {
		log_message('error', 'library getProvider');
		$this->processing_code = "1031";
		$data = new stdClass();
		$data->request_id = $transId;
		$data->user_name = "";
		if($subtype != null)
			$data->trans_sub_type = $subtype;
		else
			$data->trans_sub_type = '0';
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request getProvider: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		
		$requestMegaV = json_decode($requestMegaV);
		if(isset($requestMegaV->status) && $requestMegaV->status == '00')
		{
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			$listBank = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			if(isset($listBank->status) && $listBank->status == '00')
			{
				//log_message('error', 'DATA Provider: ' . print_r($listBank, true));
				
				$redis = new CI_Redis();
				//$redis->set(PROVIDER_KEY_REDIS, json_encode((array)$listBank->providers));
				$redis->del(PROVIDER_KEY_REDIS);
				foreach($listBank->providers as $bank)
				{
					$redis->sadd(PROVIDER_KEY_REDIS, json_encode($bank));
				}
					
				return (array)$listBank->providers;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		
	}
	/*
	*	lay danh sach ngan hang theo template phi
	*
	*/
	public function getProviderWithFee($userName, $transId, $subtype = null) {
		log_message('error', 'library getProviderWithFee');
		$this->processing_code = "1081";
		$data = new stdClass();
		$data->request_id = $transId;
		$data->user_name = $userName;
		if($subtype != null)
			$data->templateType = $subtype;
		else
			$data->templateType = '0';
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request getProviderWithFee: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'Respone getProviderWithFee: ' . print_r($requestMegaV, true));
		$requestMegaV = json_decode($requestMegaV);
		if(isset($requestMegaV->status) && $requestMegaV->status == '00')
		{
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			$listBank = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			//log_message('error', 'DATA getProviderWithFee: ' . print_r($listBank, true));
			if(isset($listBank->status) && $listBank->status == '00')
			{
				//log_message('error', 'DATA getProviderWithFee: ' . print_r($listBank, true));
				
				$redis = new CI_Redis();
				//$redis->set(PROVIDER_KEY_REDIS, json_encode((array)$listBank->providers));
				//$redis->del(PROVIDER_KEY_REDIS);
				//foreach($listBank->providers as $bank)
				//{
				//	$redis->sadd(PROVIDER_KEY_REDIS, json_encode($bank));
				//}
					
				return (array)$listBank->listCommission;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		
	}
	
	/*	Function lấy thông tin tỉnh thành
	** 	Param: 	$transId: transId
	*/
	public function getProvince($transId) {
		log_message('error', 'library getProvince');
		$this->processing_code = "1043";
		$data = new stdClass();
		$data->request_id = $transId;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request getProvince: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		
		$requestMegaV = json_decode($requestMegaV);
		if(isset($requestMegaV->status) && $requestMegaV->status == '00')
		{
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			$listProvince = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			if(isset($listProvince->status) && $listProvince->status == '00')
			{
				//log_message('error', 'DATA PROVINCE: ' . print_r($listProvince, true));
				return (array)$listProvince->provinces;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	/*	Funtion lấy danh sách ngân hàng liên kết tài khoản
	**	$userName : tên tài khoản
	**	$accessToken : accessToken
	*/
	public function getListBanksAcountMapping($userName,$accessToken){
		log_message('error', 'library getListBanksAcountMapping');
		$this->processing_code = "1065";
		
		$data = new stdClass();
		$data->user_name = $userName;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request getListBanksAcountMapping: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone getListBanksAcountMapping: ' . print_r($requestMegaV, true));
		$requestMegaV = json_decode($requestMegaV);
		if(isset($requestMegaV->status) && $requestMegaV->status == '00')
		{

			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			$listBanks = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			if(isset($listBanks->status) && $listBanks->status == '00')
			{
				log_message('error', 'bankmapping list:' . print_r($listBanks, true));
				return $listBanks->userBanksMapping;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	/*	Funtion Khởi tạo mapping bank và username
	**	$userName : tên tài khoản
	**	$bankCode : id ngân hàng
	**	$accessToken;: accessToken;
	*/
	public function initMappingBank($userName,$bankCode, $accessToken) {
		log_message('error', 'library initMappingBank');
		$this->processing_code = "1053";
		$data = new stdClass();
		$data->user_name = $userName;
		$data->bank_code = $bankCode;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request initMappingBank: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone initMappingBank: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}

	/*	Funtion xác nhận gaio dịch từ ngân hàng trả về
	**	$userName : tên tài khoản
	**	$bankCode : id ngân hàng
	**	$transID : Mã giao dịch bank trả về
	**	$status : trạng thái giao dịch bank trả về
	**	$accessToken;: accessToken;
	*/
	public function confirmMappingBank($userName,$bankCode,$transId,$status, $bank_acc, $bank_account_name, $accessToken) {
		log_message('error', 'library confirmMappingBank');
		$this->processing_code = "1054";
		$data = new stdClass();
		$data->user_name = $userName;
		$data->bank_code = $bankCode;
		$data->trans_id = $transId;
		$data->status = $status;
		$data->bank_account = $bank_acc;
		$data->bank_account_name = $bank_account_name;
		
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request confirmMappingBank: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone confirmMappingBank: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}


	/*	Funtion hủy liên kết với ngân hàng
	**	$userName : tên tài khoản
	**	$bankCode : id ngân hàng
	**	$accessToken;: accessToken;
	*/
	public function unMappingBank($userName,$bankCode, $accessToken) {
		log_message('error', 'library unMappingBank');
		$this->processing_code = "1062";
		$data = new stdClass();
		$data->user_name = $userName;
		$data->bank_code = $bankCode;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request unMappingBank: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone unMappingBank: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}


	/*	Funtion Nạp tiền online Mapping
	**	$userName : tên tài khoản
	**	$bankCode : id ngân hàng
	**	$accessToken;: accessToken;
	*/
	public function napOnlineMappingBank($userName,$bankCode,$amount,$trans_id,$phone,$email,$otp,$passLv2, $accessToken, $merchantId=null) {
		log_message('error', 'library napOnlineMappingBank');
		$this->processing_code = "1064";
		$data = new stdClass();
		$data->user_name = $userName;
		$data->bank_code = $bankCode;
		$data->amount = $amount;
		$data->request_id = $trans_id;
		$data->phone = $phone;
		$data->email = $email;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		if($merchantId != null)
			$data->merchant_id = $merchantId;
		
		log_message('error', 'data request napOnlineMappingBank: ' . print_r($data, true));
		$data->pass_lv2 = $passLv2;
		$data->otp = $otp;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone napOnlineMappingBank: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}

	/*	Funtion Nạp tiền Mapping check OTP khi vượt ngưỡng ngân hàng
	**	$userName : tên tài khoản
	**	$amount : số tiền nạp
	**	$trans_id : mã giao dịch
	**	$otp : otp
	**	$accessToken;: accessToken;
	*/
	public function exceedMappingOTPBank($userName,$amount,$trans_id,$otp, $accessToken, $merchantId=null) {
		log_message('error', 'library exceedMappingOTPBank');
		$this->processing_code = "1066";
		$data = new stdClass();
		$data->user_name = $userName;
		$data->trans_id = $trans_id;
		$data->amount = $amount;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		if($merchantId != null)
			$data->merchant_id = $merchantId;
		
		log_message('error', 'data request exceedMappingOTPBank: ' . print_r($data, true));
		$data->otp = $otp;
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone exceedMappingOTPBank: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}

    

	
	/*	Function lấy thông tin ngân hàng của User
	** 	Param: 	$email: email
	**			$phone: số điện thoại
	**			$userName: userName
	**			$transId: transId
	**			$trans_sub_type: loại giao dịch
	*/
	public function getListBankAccount($email, $phone, $userName, $transId,$trans_sub_type="") {
		log_message('error', 'library getListBankAccount');
		$this->processing_code = "1031";
		$data = new stdClass();
		if(empty($email))
			$data->email = "";
		else
			$data->email = $email;
		
		if(empty($phone))
			$data->phone = "";
		else
			$data->phone = $phone;
		$data->user_name = $userName;
		$data->request_id = $transId;

		if ($trans_sub_type!="") {
			$data->trans_sub_type = $trans_sub_type;
		}
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request getListBankAccount: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		
		$requestMegaV = json_decode($requestMegaV);
		if(isset($requestMegaV->status) && $requestMegaV->status == '00')
		{
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			$listBanks = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			if(isset($listBanks->status) && $listBanks->status == '00')
			{
				log_message('error', 'bank list:' . print_r($listBanks, true));
				return $listBanks->userBanks;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		
	}
	
	/*	Function thêm mới thông tin ngân hàng của User
	** 	Param: 	$userName: userName
	**			$bankCode: mã ngân hàng
	**			$bankAccName: tên tài khoản ngân hàng
	**			$bankAcc: số tài khoản
	**			$bankProvince: mã tỉnh thành
	**			$bankBranch: chi nhánh
	**			$transId: transId
	*/
	public function insertBankAccount($userName, $bankCode, $bankAccName, $bankAcc, $bankProvince, $bankBranch, $transId) {
		log_message('error', 'library insertBankAccount');
		$this->processing_code = "1040";
		$data = new stdClass();
		$data->user_name = $userName;
		$data->bank_id = $bankCode;
		$data->bank_account_name = $bankAccName;
		$data->bank_account = $bankAcc;
		$data->bank_branch = $bankBranch;
		$data->bank_province = $bankProvince;
		$data->request_id = $transId;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request insertBankAccount: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	/*	Function cập nhât thông tin ngân hàng của User
	** 	Param: 	$userName: userName
	**			$bankCode: mã ngân hàng
	**			$bankAccName: tên tài khoản ngân hàng
	**			$bankAcc: số tài khoản
	**			$bankProvince: tỉnh thành
	**			$bankBranch: chi nhánh
	**			$transId: transId
	*/
	public function updateBankAccount($userName, $bankCode, $bankAccName, $bankAcc, $bankProvince, $bankBranch,$rowId, $transId) {
		log_message('error', 'library updateBankAccount');
		$this->processing_code = "1041";
		$data = new stdClass();
		$data->user_name = $userName;
		$data->bank_code = $bankCode;
		$data->bank_account_name = $bankAccName;
		$data->bank_account = $bankAcc;
		$data->bank_branch = $bankBranch;
		$data->bank_province = $bankProvince;
		$data->rowId = $rowId;
		$data->request_id = $transId;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request updateBankAccount: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		
		return $requestMegaV;
		
	}
	
	/*	Function xóa thông tin ngân hàng
	** 	Param: 	$userName: userName
	**			$bankAcc: số tài khoản
	**			$transId: transId
	*/
	public function deleteBankAccount($userName, $bankAcc, $bankCode, $rowId, $transId) {
		log_message('error', 'library deleteBankAccount');
		$this->processing_code = "1042";
		$data = new stdClass();
		$data->user_name = $userName;
		$data->bank_account = $bankAcc;
		$data->request_id = $transId;
		$data->bank_code = $bankCode;
		$data->rowId = $rowId;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request deleteBankAccount: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		return $requestMegaV;
	}
	
	/*	Function lấy danh sách lịch sử giao dịch
	** 	Param: 	$userName: userName
	**			$transactionType: loại giao dịch: 1: Topup, 2: Nạp game, 3: mua mã thẻ, 4: rút tiền, 5: Nạp tiền, 6:Chuyển tiền
	**			$requestId: mã giao dịch
	**			$providerCode: mã dối tác 
	**			$fromDate: thời gian từ ngày
	**			$toDate: thời gian đến ngày
	**			$transStatus: Trạng thái giao dịch
	**			$numbPage: Số trang
	**			$pageSize: Số lượng giao dịch lấy trên 1 trang
	**			$transType: Loại giao dịch chuyển tiền 1: Chuyển tiền, 2: nhận tiền
	**			$transId: transId
	*/
	public function getTransList($userName, $transactionType, $requestId, $providerCode, $fromDate, $toDate, $transStatus, $numbPage, $pageSize, $transType, $transId, $transSubType = '', $epurseTransId = ''){
		log_message('error', 'library getTransList');
		$this->processing_code = "1025";
		$data = new stdClass();
		$data->user_name = $userName;
		$data->transaction_type = $transactionType;
		$data->trans_id = $requestId;
		$data->providerCode = $providerCode;
		$data->bank_code = $providerCode;
		$data->fromDate = $fromDate;
		$data->toDate = $toDate;
		$data->trans_stt = $transStatus;
		if ($numbPage && $pageSize) {
            $data->page_num = $numbPage;
            $data->page_size = $pageSize;
        }

		$data->tranfer_type = $transType;
		$data->requestId = $transId;
		$data->trans_sub_type = 0;
		if ($transSubType) {
            $data->trans_sub_type = $transSubType;
        }
        if ($epurseTransId) {
		    $data->request_id = $epurseTransId;
        }

		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request getTransList: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone getTransList: ' . print_r($requestMegaV, true));
		
		$requestMegaV = json_decode($requestMegaV);
		if(isset($requestMegaV->status) && $requestMegaV->status == '00')
		{
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			$listTrans = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			//log_message('error', 'data respone: ' . print_r($listTrans, true));
			if(isset($listTrans->status) && $listTrans->status == '00')
			{
				log_message('error', 'list transaction: ' . print_r($listTrans, true));
				return $listTrans;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		return $requestMegaV;
	}

	/*	Function lấy biến động số dư
	** 	Param: 	$userName: userName
	**			$transactionType: loại giao dịch: 1: Topup, 2: Nạp game, 3: mua mã thẻ, 4: rút tiền, 5: Nạp tiền, 6:Chuyển tiền
	**			$requestId: mã giao dịch
	**			$providerCode: mã dối tác 
	**			$fromDate: thời gian từ ngày
	**			$toDate: thời gian đến ngày
	**			$transStatus: Trạng thái giao dịch
	**			$numbPage: Số trang
	**			$pageSize: Số lượng giao dịch lấy trên 1 trang
	*/
	public function getBalanceChange($userName, $transactionType, $requestId, $fromDate, $toDate, $transStatus, $numbPage, $pageSize){
		log_message('error', 'library get Balance Change');
		$this->processing_code = "1067";
		$data = new stdClass();
		$data->user_name = $userName;
		$data->transaction_type = $transactionType;
		$data->trans_id = $requestId;
		$data->fromDate = $fromDate;
		$data->toDate = $toDate;
		$data->trans_stt = $transStatus;
		if ($numbPage && $pageSize) {
            $data->page_num = $numbPage;
            $data->page_size = $pageSize;
        }


		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request getBalanceChange: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone getBalanceChange: ' . print_r($requestMegaV, true));
		
		$requestMegaV = json_decode($requestMegaV);
		if(isset($requestMegaV->status) && $requestMegaV->status == '00')
		{
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			$listTrans = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			//log_message('error', 'data respone: ' . print_r($listTrans, true));
			if(isset($listTrans->status) && $listTrans->status == '00')
			{
				log_message('error', 'list transaction: ' . print_r($listTrans, true));
				return $listTrans;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		return $requestMegaV;
	}


	/*	Function Add bank firm banking (Thêm ngân hàng)
	** 	Param: 	$userName: userName
	** 	Param: 	$bankCode: ID ngân hàng
	** 	Param: 	$accessToken: accessToken
	*/
	public function addBankFirmBanking($userName,$bankCode ,$bankAccName,$bankAcc,$bankBranch,$accessToken){
		log_message('error', 'library Add bank firm banking');
		$this->processing_code = "1070";
		$data = new stdClass();
		$data->user_name = $userName;
		$data->bank_code = $bankCode;
		$data->bank_account_name = $bankAccName;
		$data->bank_account = $bankAcc;
		$data->bank_branch = $bankBranch;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;

		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request addBankFirmBanking: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone addBankFirmBanking: ' . print_r($requestMegaV, true));
		
		/*$requestMegaV = json_decode($requestMegaV);
		if(isset($requestMegaV->status) && $requestMegaV->status == '00')
		{
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			$listTrans = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			//log_message('error', 'data respone: ' . print_r($listTrans, true));
			if(isset($listTrans->status) && $listTrans->status == '00')
			{
				log_message('error', 'list transaction: ' . print_r($listTrans, true));
				return $listTrans;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}*/
		return $requestMegaV;
	}


	/*	Function lấy tra cứu hóa đơn
	** 	Param: 	
	**			$contractID: mã hóa đơn
	**			$providerCode: mã dối tác 
	*/
	public function getPaymentBill($contractID,$providerCode){
		log_message('error', 'library getPaymentBill');
		$this->processing_code = "1058";
		$data = new stdClass();
		$data->contractID = $contractID;
		$data->providerCode = $providerCode;

		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request getPaymentBill: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone getPaymentBill: ' . print_r($requestMegaV, true));
		
		$requestMegaV = json_decode($requestMegaV);
		if(isset($requestMegaV->status) && $requestMegaV->status == '00')
		{
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			$listTrans = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			//log_message('error', 'data respone: ' . print_r($listTrans, true));
			if(isset($listTrans->status) && $listTrans->status == '00')
			{
				log_message('error', 'list transaction: ' . print_r($listTrans, true));
				return $listTrans;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		return $requestMegaV;
	}
	
	/*	Funtion payment cho EC merchaint
	**	$dataPayment : dữ liệu thanh toán merchaint redirect sang
	**	$merchant_id : Mã đối tác
	**	$signature : Chứ ký của đối tác
	**	$userName : Tên tài khoản
	**	$transId : transId
	*/
	public function ECPayment($merchant_id, $signature, $dataPayment, $userName, $transId){
		log_message('error', 'library ECPayment');
		$this->processing_code = "1045";
		$this->merchantId = $merchant_id;
		$this->signature = $signature;
		
		$data = new stdClass();
		$data->user_name = $userName;
		$data->paymentData = $dataPayment;
		$data->request_id = $transId;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request ECPayment: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone ECPayment: ' . print_r($requestMegaV, true));
		
		//$requestMegaV = new stdclass();
		//$requestMegaV->status = '00';
		//$requestMegaV = json_encode($requestMegaV);
		
		return $requestMegaV;
	}
	
	/*	Funtion từ chối giao dịch
	**	$paymentId : mã giao dịch
	**	$transId : transId
	*/
	public function rejectEcPayment($paymentId, $transId){
		log_message('error', 'library rejectEcPayment');
		$this->processing_code = "1047";
		
		$data = new stdClass();
		$data->paymentId = $paymentId;
		$data->request_id = $transId;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request rejectEcPayment: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone rejectEcPayment: ' . print_r($requestMegaV, true));
		
		//$requestMegaV = new stdclass();
		//$requestMegaV->status = '00';
		//$requestMegaV = json_encode($requestMegaV);
		
		return $requestMegaV;
	}
	
	/*	Funtion chập nhận thanh toán
	**	$paymentId : mã giao dịch
	**	$userName : tên tài khoản
	**	$phone : Số điện thoại
	**	$email : email
	**	$otp : otp
	**	$passLv2 : passLv2
	**	$accessToken : accessToken
	**	$transId : transId
	*/
	public function acceptEcPayment($paymentId, $userName, $phone, $email, $otp, $passLv2, $accessToken, $transId){
		log_message('error', 'library acceptEcPayment');
		$this->processing_code = "1046";
		
		$data = new stdClass();
		$data->paymentId = $paymentId;
		$data->request_id = $transId;
		
		if(empty($email))
			$data->email = "";
		else
			$data->email = $email;
		
		if(empty($phone))
			$data->phone = "";
		else
			$data->phone = $phone;
		$data->user_name = $userName;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		log_message('error', 'data request acceptEcPayment: ' . print_r($data, true));
		$data->pass_lv2 = $passLv2;
		$data->otp = $otp;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone acceptEcPayment: ' . print_r($requestMegaV, true));
		
		//$requestMegaV = new stdclass();
		//$requestMegaV->status = '00';
		//$requestMegaV = json_encode($requestMegaV);
		
		return $requestMegaV;
	}
	
	/*	Funtion chập nhận thanh toán
	**	$paymentId : mã giao dịch
	**	$userName : tên tài khoản
	**	$phone : Số điện thoại
	**	$email : email
	**	$otp : otp
	**	$passLv2 : passLv2
	**	$accessToken : accessToken
	**	$transId : transId
	*/
	public function paymentOnline($userName, $payment_type, $amount, $bankId, $bank_redirect_url, $accessToken, $transId, $merchantId=null){
		log_message('error', 'library paymentOnline');
		$this->processing_code = "1018";
		
		$data = new stdClass();
		$data->request_id = $transId;
		$data->bank_id = $bankId;
		$data->bank_code = $bankId;
		$data->user_name = $userName;
		$data->amount = $amount;
		$data->trans_sub_type = $payment_type;
		$data->bank_redirect_url = $bank_redirect_url;
		
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		if($merchantId != null)
			$data->merchant_id = $merchantId;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request paymentOnline paymentOnline: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		
		//$requestMegaV = new stdclass();
		//$requestMegaV->status = '00';
		//$requestMegaV = json_encode($requestMegaV);
		
		return $requestMegaV;
	}
	
	/*	Funtion chập nhận thanh toán
	**	$paymentId : mã giao dịch
	**	$userName : tên tài khoản
	**	$phone : Số điện thoại
	**	$email : email
	**	$otp : otp
	**	$passLv2 : passLv2
	**	$accessToken : accessToken
	**	$transId : transId
	*/
	public function paymentOnlineUpdate($userName, $trans_id, $responCode, $transId, $accessToken, $merchantId=null){
		log_message('error', 'library paymentOnlineUpdate');
		$this->processing_code = "1019";
		
		$data = new stdClass();
		$data->request_id = $transId;
		$data->user_name = $userName;
		$data->trans_stt = $responCode;
		$data->trans_id = $trans_id;
		
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		if($merchantId != null)
			$data->merchant_id = $merchantId;
				
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request paymentOnlineUpdate: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone paymentOnlineUpdate: ' . print_r($requestMegaV, true));
		
		
		return $requestMegaV;
	}
	
	public function paymentOffline($transId){
		log_message('error', 'library paymentOffline');
		$this->processing_code = "1052";
		
		$data = new stdClass();
		$data->request_id = $transId;
		
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request paymentOffline: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone paymentOffline: ' . print_r($requestMegaV, true));
		
		$requestMegaV = json_decode($requestMegaV);
		if(isset($requestMegaV->status) && $requestMegaV->status == '00')
		{
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			$listBank = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			
			//log_message('error', 'data respone: ' . print_r($listTrans, true));
			if(isset($listBank->status) && $listBank->status == '00')
			{
				//log_message('error', 'list bank Epay: ' . print_r($listBank, true));
				return $listBank->listEpayBankAcc;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		
		//$requestMegaV = new stdclass();
		//$requestMegaV->status = '00';
		//$requestMegaV = json_encode($requestMegaV);
		
		return $requestMegaV;
	}
	
	public function genOtpCheckPhone($uname, $email, $phone, $transId){
		$this->processing_code = "1048";
		$data = new stdClass();
		if($transId == null)
			$data->request_id = "GOTP" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		$data->user_name = $uname;
		
		if(empty($email))
			$data->email = "";
		else
			$data->email = $email;
		
		if(empty($phone))
			$data->phone = "";
		else
			$data->phone = $phone;
		
		//$key3des = $this->getSessionKeyCache();
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request genOtpCheckPhone: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		
		return $requestMegaV;
	}
	
	public function transferToAcc($uname, $phone, $email, $reciveUserName, $reciveUserId, $amount, $whoPayFee, $note, $otp, $passLv2, $accessToken, $transId, $merchantId=null){
		$this->processing_code = "7547";
		$data = new stdClass();
		if($transId == null)
			$data->request_id = "TTA" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		$data->user_name = $uname;
		$data->receiver_user_name = $reciveUserName;
		$data->receiver_user_id = $reciveUserId;
		$data->amount = $amount;
		$data->who_pay_fee = $whoPayFee;
		$data->note = $note;
		$data->phone = $phone;
		$data->email = $email;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		if($merchantId != null)
			$data->merchant_id = $merchantId;
		
		log_message('error', 'data request transferToAcc: ' . print_r($data, true));
		$data->pass_lv2 = $passLv2;
		$data->otp = $otp;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone transferToAcc: ' . print_r($requestMegaV, true));
		
		return $requestMegaV;
	}
	
	public function withdrawOffline($uname, $phone, $email, $bankCode, $bankAccName, $amount, $otp, $passLv2, $accessToken, $transId, $merchantId=null){
		$this->processing_code = "1020";
		$data = new stdClass();
		if($transId == null)
			$data->request_id = "WDO" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		$data->user_name = $uname;
		$data->bank_code = $bankCode;
		$data->bank_account = $bankAccName;
		$data->amount = $amount;
		$data->phone = $phone;
		$data->email = $email;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		if($merchantId != null)
			$data->merchant_id = $merchantId;
		
		log_message('error', 'data request withdrawOffline: ' . print_r($data, true));
		$data->pass_lv2 = $passLv2;
		$data->otp = $otp;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		
		return $requestMegaV;
	}
	// rút tiền firm banking

	public function withdrawFast($uname, $phone, $email, $bankCode, $bankAccName, $amount, $otp, $passLv2, $accessToken, $transId, $merchantId=null){
		$this->processing_code = "1071";
		$data = new stdClass();
		if($transId == null)
			$data->request_id = "WDO" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		$data->user_name = $uname;
		$data->bank_code = $bankCode;
		$data->bank_account = $bankAccName;
		$data->amount = $amount;
		$data->phone = $phone;
		$data->email = $email;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		if($merchantId != null)
			$data->merchant_id = $merchantId;
		
		log_message('error', 'data request withdrawFast: ' . print_r($data, true));
		$data->pass_lv2 = $passLv2;
		$data->otp = $otp;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone withdrawFast: ' . print_r($requestMegaV, true));
		
		return $requestMegaV;
	}
	
	public function getTempFee($uname, $transId = null){
		$this->processing_code = "1050";
		$data = new stdClass();
		if($transId == null)
			$data->request_id = "GTF" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
				
		//$key3des = $this->getSessionKeyCache();
		$data->user_name = $uname;
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request getTempFee: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		
		$requestMegaV = json_decode($requestMegaV);
		if(isset($requestMegaV->status) && $requestMegaV->status == STATUS_SUCCESS)
		{
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			$listTempFee = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			//log_message('error', 'DATA FEE: ' . print_r($listTempFee, true));
			if(isset($listTempFee->status) && $listTempFee->status == STATUS_SUCCESS)
			{
				//log_message('error', 'DATA fee: ' . print_r($listTempFee, true));
				
				$redis = new CI_Redis();
				$redis->del(TEMPLATE_FEE_KEY_REDIS);
				foreach($listTempFee->listFeeTemplate as $template)
				{
					$redis->sadd(TEMPLATE_FEE_KEY_REDIS, json_encode($template));
				}
				
				
				return (array)$listTempFee->listFeeTemplate;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	
	public function getAmountCDV($username, $transId = null, $providerCode = null, $getCache = null, $transType = null){
		$this->processing_code = "1056";
		$data = new stdClass();
		if($transId == null)
			$data->request_id = "GTF" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		if($providerCode != null)
			$data->providerCode = $providerCode;
		//$key3des = $this->getSessionKeyCache();
		if($transType != null)
			$data->templateType = $transType;
		
		$data->user_name = $username;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request getAmountCDV: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		
		$requestMegaV = json_decode($requestMegaV);
		if(isset($requestMegaV->status) && $requestMegaV->status == STATUS_SUCCESS)
		{
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			$listTempFee = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			log_message('error', 'DATA list amount: ' . print_r($listTempFee, true));
			if(isset($listTempFee->status) && $listTempFee->status == STATUS_SUCCESS)
			{
				//log_message('error', 'DATA getAmountCDV: ' . print_r($listTempFee, true));
				$redis = new CI_Redis();
				
				if($providerCode == null)
				{
					$redis->set('DETAIL_CARD_SOFTPIN', json_encode((array)$listTempFee->listSoftpin));
					return (array)$listTempFee->listSoftpin;
				}
				else
				{
					$redis->set('DETAIL_CARD_COMMISSION' . $providerCode, json_encode((array)$listTempFee->listCommission));
					return (array)$listTempFee->listCommission;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	public function buyCardCDV($userName, $phone, $email, $amount, $otp, $passLv2, $quantity, $telco_code, $accessToken, $transId = null, $merchantId=null){
		$this->processing_code = "1024";
		$data = new stdClass();
		if($transId == null)
			$data->request_id = "GTF" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		$data->user_name = $userName;
		$data->amount = $amount;
		$data->phone = $phone;
		$data->email = $email;
		$data->quantity = $quantity;
		$data->providerCode = $telco_code;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		if($merchantId != null)
			$data->merchant_id = $merchantId;
				
		log_message('error', 'data request buyCardCDV: ' . print_r($data, true));
		$data->pass_lv2 = $passLv2;
		$data->otp = $otp;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone buyCardCDV: ' . print_r($requestMegaV, true));
		
		return $requestMegaV;
	}
	
	public function paymentTopupToPhone($userName, $phone, $email, $amount, $otp, $passLv2, $topup_account, $telco_code, $accessToken, $transId = null, $merchantId=null){
		$this->processing_code = "1023";
		$data = new stdClass();
		if($transId == null)
			$data->request_id = "GTF" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		$data->user_name = $userName;
		$data->topup_amount = $amount;
		$data->phone = $phone;
		$data->email = $email;
		$data->topup_account = $topup_account;
		$data->providerCode = $telco_code;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		if($merchantId != null)
			$data->merchant_id = $merchantId;
				
		log_message('error', 'data request paymentTopupToPhone: ' . print_r($data, true));
		$data->pass_lv2 = $passLv2;
		$data->otp = $otp;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone paymentTopupToPhone: ' . print_r($requestMegaV, true));
		
		return $requestMegaV;
	}
	
	public function paymentTopupToAccGame($userName, $phone, $email, $amount, $otp, $passLv2, $topup_account, $telco_code, $accessToken, $transId = null, $merchantId=null){
		$this->processing_code = "1013";
		$data = new stdClass();
		if($transId == null)
			$data->request_id = "GTF" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		$data->user_name = $userName;
		$data->topup_amount = $amount;
		$data->phone = $phone;
		$data->email = $email;
		$data->topup_account = $topup_account;
		$data->providerCode = $telco_code;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		if($merchantId != null)
			$data->merchant_id = $merchantId;
				
		log_message('error', 'data request paymentTopupToAccGame: ' . print_r($data, true));
		$data->pass_lv2 = $passLv2;
		$data->otp = $otp;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone paymentTopupToAccGame: ' . print_r($requestMegaV, true));
		
		return $requestMegaV;
	}
	
	public function getBalaceUser($userName, $transId = null){
		$this->processing_code = "1016";
		$data = new stdClass();
		if($transId == null)
			$data->request_id = "GTF" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		$data->user_name = $userName;
				
		//$key3des = $this->getSessionKeyCache();
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request getBalaceUser: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		
		
		$requestMegaV = json_decode($requestMegaV);
		if(isset($requestMegaV->status) && $requestMegaV->status == STATUS_SUCCESS)
		{
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			$listTempFee = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			log_message('error', 'DATA balance: ' . print_r($listTempFee, true));
			if(isset($listTempFee->status) && $listTempFee->status == STATUS_SUCCESS)
			{
				//log_message('error', 'DATA balace: ' . print_r($listTempFee, true));
				
				//return (array)$listTempFee->provinces;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	/** Function lấy thông tin User bằng tên tài khoản
	**	Param: 	$userName: userName
	**			$transId: transId
	*/	
	public function getUserInfoByName($userName, $transId = null){
		$this->processing_code = "8003";
		$data = new stdClass();
		if($transId == null)
			$data->request_id = "GUI" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		$data->user_name = $userName;
		
		//$key3des = $this->getSessionKeyCache();
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request getUserInfoByName: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		
		$requestMegaV = json_decode($requestMegaV);
		if(isset($requestMegaV->status) && $requestMegaV->status == STATUS_SUCCESS)
		{
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			$userInfo = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			if(isset($userInfo->status) && $userInfo->status == STATUS_SUCCESS)
			{
				log_message('error', 'DATA Userinfo : ' . print_r($userInfo, true));
				
				if(isset($userInfo->uInfo))
				{
					return $userInfo->uInfo;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		
	}
	
	/** Function lấy thông tin User bằng tên tài khoản
	**	Param: 	$userName: userName
	**			$transId: transId
	*/	
	public function getUserInfoByPhone($phone, $accessToken, $transId = null){
		$this->processing_code = "1015";
		$data = new stdClass();
		if($transId == null)
			$data->request_id = "GUI" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		$data->phone = $phone;
		$data->access_token = $accessToken;
		
		//$key3des = $this->getSessionKeyCache();
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request getUserInfoByPhone: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone: ' . print_r($requestMegaV, true));
		
		//$requestMegaV = json_decode($requestMegaV);
		return $requestMegaV;
		/*
		if(isset($requestMegaV->status) && $requestMegaV->status == STATUS_SUCCESS)
		{
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			$userInfo = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			if(isset($userInfo->status))
			{
				//return $userInfo;
				
				if($userInfo->status == STATUS_SUCCESS)
				{
					//log_message('error', 'DATA Userinfo : ' . print_r($userInfo, true));
					
					if(isset($userInfo->uInfo))
					{
						return $userInfo->uInfo;
					}
					else
					{
						return false;
					}
				}
				elseif($userInfo->status == '22')
				{
					return $userInfo;
				}
				
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		*/
		
	}
	
	public function getBalaceUserWithBonusId($userName, $accessToken, $transId = null, $getcache = null){
		
		if($getcache != null)
		{
			// get balancein redis
			log_message('error', 'library balance in cache');
			$redis_get = new CI_Redis();
			$value = $redis_get->get('BALANCE_' . $userName);
			if(!empty($value))
				return $value;
			else
				$getcache = null;
		}
		
		if($getcache == null)
		{
			$this->processing_code = "1051";
			$data = new stdClass();
			if($transId == null)
				$data->request_id = "GTF" . date("Ymd") . rand();
			else
				$data->request_id = $transId;
			
			$data->user_name = $userName;
			$data->access_token = $accessToken;
			$data->client_id = CLIENT_ID_OPENID;
			//$key3des = $this->getSessionKeyCache();
			
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			
			log_message('error', 'data request getBalaceUser: ' . print_r($data, true));
			$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
			$requestMegaV = $this->requestMegaVCore($encryptData);
			log_message('error', 'data respone: ' . print_r($requestMegaV, true));
			
			
			$requestMegaV = json_decode($requestMegaV);
			if(isset($requestMegaV->status) && $requestMegaV->status == STATUS_SUCCESS)
			{
				$key3des = $this->getSessionKey();
				if(!$key3des)
					return false;
				$listBalance = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
				//log_message('error', 'DATA balance: ' . print_r($listBalance, true));
				if(isset($listBalance->status) && $listBalance->status == STATUS_SUCCESS)
				{
					log_message('error', 'DATA balace: ' . print_r($listBalance, true));
					
					//return (array)$listTempFee->provinces;
					if(isset($listBalance->uInfo))
					{
						$balanceBonus = $listBalance->uInfo;
						if(isset($balanceBonus->bonus_balance) && is_array($balanceBonus->bonus_balance) && count($balanceBonus->bonus_balance) > 0)
						{
							$bonusID = $balanceBonus->bonus_balance;
							$bonusID = $bonusID[0];
							$redis = new CI_Redis();
							if(isset($bonusID->bonusBeHold))
							{
								$redis->set('BALANCE_BEHOLD' . $userName, $bonusID->bonusBeHold);
							}
							
							if(isset($bonusID->bonusBal))
							{
								$redis->set('BALANCE_' . $userName, $bonusID->bonusBal);
								return $bonusID->bonusBal;
							}
							else
							{
								return false;
							}
						}
						else
						{
							return false;
						}
					}
					else
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		
	}
	/*
	*	$transType = 8 : nap tien dien thoai, game(topup) | 9: download ma the
	*/
	public function getDiscountAmount($userName, $providerCode, $amount, $transType, $transId = null, $getcache = null){
		if($getcache != null)
		{
			// get balancein redis
			log_message('error', 'library getDiscountAmount in cache');
			$redis_get = new CI_Redis();
			$value = $redis_get->get('DISCOUNT_' . $providerCode);
			if(!empty($value))
				return $value;
			else
				$getcache = null;
		}
		
		if($getcache == null)
		{
			$this->processing_code = "1057";
			$data = new stdClass();
			if($transId == null)
				$data->request_id = "GDA" . date("Ymd") . rand();
			else
				$data->request_id = $transId;
			
			$data->providerCode = $providerCode;
			$data->amount = $amount;
			//$data->transaction_type = $transType;
			$data->templateType = $transType;
			$data->user_name = $userName;
			
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			
			log_message('error', 'data request getDiscountAmount: ' . print_r($data, true));
			$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
			$requestMegaV = $this->requestMegaVCore($encryptData);
			log_message('error', 'data respone getDiscountAmount: ' . print_r($requestMegaV, true));
						
			$requestMegaV = json_decode($requestMegaV);
			if(isset($requestMegaV->status) && $requestMegaV->status == STATUS_SUCCESS)
			{
				$key3des = $this->getSessionKey();
				if(!$key3des)
					return false;
				$discount = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
				log_message('error', 'DATA getDiscountAmount: ' . print_r($discount, true));
				if(isset($discount->status) && $discount->status == STATUS_SUCCESS)
				{
					log_message('error', 'DATA getDiscountAmount: ' . print_r($discount, true));
					
					//return (array)$listTempFee->provinces;
					if(isset($discount->discountAmount))
					{
						$redis = new CI_Redis();
						$redis->set('DISCOUNT_' . $providerCode, $discount->discountAmount);
						return $discount->discountAmount;
					}
					else
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
	}
	
	public function withdrawMapping($userName, $phone, $email, $bankCode, $amount, $otp, $passLv2, $accessToken, $transId, $merchantId=null){
		$this->processing_code = "1063";
		$data = new stdClass();
		if($transId == null)
			$data->request_id = "WDM" . date("Ymd") . rand();
		else
			$data->request_id = $transId;
		
		$data->user_name = $userName;
		$data->bank_code = $bankCode;
		$data->amount = $amount;
		$data->phone = $phone;
		$data->email = $email;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		if($merchantId != null)
			$data->merchant_id = $merchantId;
		
		log_message('error', 'data request withdrawMapping: ' . print_r($data, true));
		$data->pass_lv2 = $passLv2;
		$data->otp = $otp;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone withdrawMapping: ' . print_r($requestMegaV, true));
		
		//$requestMegaV = '{"status":"99"}';
		
		return $requestMegaV;
	}
	
	public function getSessionKey() {
		$key3des = $this->getSessionKeyCache();
		if(!$key3des)
		{
			for($retry = 1; $retry <= RETRY_GET_SESSION_KEY; $retry++)
			{
				$key3des = $this->getSessionKeyServer();
				log_message('error', 'getSessionKeyServer lan ' . $retry);
				if($key3des)
					break;
			}
		}
		//log_message('error', 'sesion key: ' . $key3des);
		return $key3des;
	}
	
	/*	Function lay key ma hoa data tu core megav luu key vao redis
	** 	
	*/
	public function getSessionKeyServer() {
		log_message('error', 'library getSessionKeyServer');
		$this->processing_code = "1030";
		$data = new stdClass();
		$data->p_id = $this->partner_id;
		$data->p_username = $this->partner_username;
		$data->p_pass = $this->partner_pass;
		log_message('error', 'data request getSessionKeyServer: ' . print_r($data, true));
		$encryptData = $this->RSAencrypt(json_encode($data));
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone getSessionKeyServer: ' . print_r($requestMegaV, true));
		$respone = json_decode($requestMegaV, true);
		if(isset($respone['data']))
		{
			$sessionKey = $this->RSAdecrypt($respone['data']);
			
			$sessionKey = json_decode($sessionKey, true);
			if(isset($sessionKey['status']) && $sessionKey['status'] == "00")
			{
				if($sessionKey['status'] == "00")
				{
					$redis = new CI_Redis();
					$redis->set('SESSION_KEY', $sessionKey['session_key']);
					$redis->expire('SESSION_KEY', SESSION_KEY_TTL);
					return $sessionKey['session_key'];
				}
			}
			else
			{
				// log $sessionKey['status']
			}
		}
		return false;
	}
	
	/*	Function lay key ma hoa data tu redis
	** 	
	*/
	public function getSessionKeyCache(){
		log_message('error', 'library getSessionKeyCache');
		$redis_get = new CI_Redis();
		$value = $redis_get->get('SESSION_KEY');
		if(!empty($value))
			return $value;
		else
			return false;
	}
	
	
	/*	Function tao request len core megaV
	** 	param: data: du lieu da duoc ma hoa 3Des
	*/
	public function requestMegaVCore($data) {
		log_message('error', 'Request core megaV');
		$url = URL_MEGAV_CORE;		
		$dataRequest = new stdClass();
		$dataRequest->processing_code = $this->processing_code;
		$dataRequest->partner_id = $this->partner_id;
		$dataRequest->mac = $this->mac;
		$dataRequest->client_os = $this->client_os;
		$dataRequest->client_ip = $this->client_ip;
		$dataRequest->merchantId = $this->merchantId;
		$dataRequest->signature = $this->signature;
		$dataRequest->data = $data;
		
		$url .= urlencode(json_encode($dataRequest));
		try{
			$response = $this->ci->id_curl->get_curl($url, true);
			
			$requestMegaV = json_decode($response);
			//log_message('error', 'data respone requestMegaVCore: ' . print_r($requestMegaV, true));
			if(isset($requestMegaV->status) && $requestMegaV->status == STATUS_WRONG_SESSIONKEY)
			{
				// giai ma data
					// get key cache
				$key3Des = $this->getSessionKeyCache();
				$dataDecrypt = $this->decrypt3DES($data, $key3Des);
				//log_message('error', 'data decrypt: ' . print_r($dataDecrypt, true));
				// tao data request moi voi key moi lay tu server
				$key3DesNew = $this->getSessionKeyServer();
				$dataEncrypt = $this->encrypt3DES($dataDecrypt, $key3DesNew);
				
				$dataRequest->data = $dataEncrypt;
				$url = URL_MEGAV_CORE . urlencode(json_encode($dataRequest));
				$response = $this->ci->id_curl->get_curl($url);
			}
			
			return $response;
		} catch (Exception $e) {
			log_message('error', 'Goi core megaV loi: ' . print_r($e, true));
			throw $e;
		}
	}
	
	/*	Function ma hoa du lieu bang RSA
	** 	param: $data: du lieu can ma hoa
	*/
	private function RSAencrypt($data){
		$file_key = 'file://'.$_SERVER['DOCUMENT_ROOT'].'/key/public_key.pem';
		$pubkey = openssl_pkey_get_public(file_get_contents($file_key));
		openssl_public_encrypt($data, $encryptData, $pubkey, OPENSSL_PKCS1_PADDING);
		return bin2hex($encryptData);
	}
	
	/*	Function giai ma du lieu bang RSA
	** 	param: $data: du lieu can giai ma
	*/
	private function RSAdecrypt($data){
		//$file_key = 'file://'.$_SERVER['DOCUMENT_ROOT'].'/key/private_key.pem';
		//$file_key = 'file://'.$_SERVER['DOCUMENT_ROOT'].'/key/public_key.pem';
		//$privateKey = openssl_pkey_get_public(file_get_contents($file_key));
		//openssl_public_decrypt(base64_decode($data), $decryptData, $privateKey);
		
		
		// giai ma bang private key
		
		$file_key = 'file://'.$_SERVER['DOCUMENT_ROOT'].'/key/private_key.pem';
		$privateKey = openssl_pkey_get_private(file_get_contents($file_key));
		openssl_private_decrypt(hex2bin($data), $decryptData, $privateKey, OPENSSL_PKCS1_PADDING);
		return $decryptData;
	}
	
	
	/*	Function ma hoa du lieu bang 3Des
	** 	param: 	$text: du lieu can ma hoa
	**			$key: key 3Des lay tu redis hoac core megaV
	*/
	private function encrypt3DES($text, $key) {
        $key = base64_decode($key);
        $size = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
        $text = $this->pkcs5_pad($text, $size);
        $bin = pack('H*', bin2hex($text));
        $size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($size, MCRYPT_RAND);
        $encrypted = bin2hex(mcrypt_encrypt(MCRYPT_3DES, $key, $bin, MCRYPT_MODE_ECB, $iv));
        return $encrypted;
    }
	
	private function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
	
	/*	Function giai ma du lieu bang 3Des
	** 	param: 	$text: du lieu can ma hoa
	**			$key: key 3Des lay tu redis hoac core megaV
	*/
	public function decrypt3DES($text, $key) {
        $key = base64_decode($key);
        $str = $this->hex2bin($text);
        $size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($size, MCRYPT_RAND);
        $decrypted = mcrypt_decrypt(MCRYPT_3DES, $key, $str, MCRYPT_MODE_ECB, $iv);
        $info = rtrim($this->pkcs5_unpad($decrypted));
		//log_message('error', 'data decrypt: ' . print_r($info, true));
        return $info;
    }
	
	public function hex2bin($str) {
        $bin = "";
        $i = 0;
        do {
            $bin .= chr ( hexdec ( $str {$i} . $str {($i + 1)} ) );
            $i += 2;
        } while ( $i < strlen ( $str ) );
        return $bin;
    }
	
	public function pkcs5_unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        $a = substr($text, 0, -1 * $pad);
        return substr($text, 0, -1 * $pad);
    }
	/*	Funtion chập nhận thanh toán
	**	$paymentId : mã giao dịch
	**	$userName : tên tài khoản
	**	$phone : Số điện thoại
	**	$email : email
	**	$otp : otp
	**	$passLv2 : passLv2
	**	$accessToken : accessToken
	**	$transId : transId
	*/
	public function acceptBillPayment($paymentId,$provider_code, $userName, $phone, $email,$amount, $otp, $passLv2, $accessToken, $transId,$requestId, $merchantId=null){
		log_message('error', 'library acceptBillPayment');
		$this->processing_code = "1059";
		
		$data = new stdClass();
		$data->contractID = $paymentId;
		$data->trans_id = $transId;
		$data->request_id = $requestId;
		$data->providerCode = $provider_code;
		
		if(empty($email))
			$data->email = "";
		else
			$data->email = $email;
		
		if(empty($phone))
			$data->phone = "";
		else
			$data->phone = $phone;
		$data->user_name = $userName;
		$data->amount = $amount;
		$data->access_token = $accessToken;
		$data->client_id = CLIENT_ID_OPENID;
		
		if($merchantId != null)
			$data->merchant_id = $merchantId;
		
		log_message('error', 'data request acceptBillPayment: ' . print_r($data, true));
		$data->pass_lv2 = $passLv2;
		$data->otp = $otp;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone acceptBillPayment: ' . print_r($requestMegaV, true));
		
		//$requestMegaV = new stdclass();
		//$requestMegaV->status = '00';
		//$requestMegaV = json_encode($requestMegaV);
		
		return $requestMegaV;
	}


	/*	Funtion update avatar
	**	$userName : tên tài khoản
	**	$avatar_url : đường dẫn avatar
	*/
	public function updateAvatar($userName, $avatar_url){
		log_message('error', 'library updateAvatar');
		$this->processing_code = "1072";
		
		$data = new stdClass();
		$data->user_name = $userName;
		$data->avatar_url = $avatar_url;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request updateAvatar: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone updateAvatar: ' . print_r($requestMegaV, true));
		
		//$requestMegaV = new stdclass();
		//$requestMegaV->status = '00';
		//$requestMegaV = json_encode($requestMegaV);
		
		return $requestMegaV;
	}

	/*
	**			$numbPage: Số trang
	**			$pageSize: Số lượng giao dịch lấy trên 1 trang
	*/
	public function getListMessager($userId,$userName, $numbPage, $pageSize){
		log_message('error', 'library getListMessager');
		$this->processing_code = "1086";
		$data = new stdClass();
		$data->user_id = $userId;
		$data->user_name = $userName;
        $data->page_num = $numbPage;
        $data->page_size = $pageSize;

		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request getListMessager: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data response getListMessager: ' . print_r($requestMegaV, true));
		
		$requestMegaV = json_decode($requestMegaV);
		if(isset($requestMegaV->status) && $requestMegaV->status == '00')
		{
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			$listTrans = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			//log_message('error', 'data respone: ' . print_r($listTrans, true));
			if(isset($listTrans->status) && $listTrans->status == '00')
			{
				log_message('error', 'list Messager: ' . print_r($listTrans, true));
				return $listTrans;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		return $requestMegaV;
	}

	public function checkInboxMessager($id){
		log_message('error', 'library getListMessager');
		$this->processing_code = "1087";
		$data = new stdClass();
		$data->inbox_id = $id;

		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request checkInboxMessager: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data response checkInboxMessager: ' . print_r($requestMegaV, true));
		
		$requestMegaV = json_decode($requestMegaV);
		if(isset($requestMegaV->status) && $requestMegaV->status == '00')
		{
			$key3des = $this->getSessionKey();
			if(!$key3des)
				return false;
			$listTrans = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			//log_message('error', 'data respone: ' . print_r($listTrans, true));
			if(isset($listTrans->status) && $listTrans->status == '00')
			{
				log_message('error', 'list checkInboxMessager: ' . print_r($listTrans, true));
				return $listTrans;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		return $requestMegaV;
	}
	
	public function validRedirectLinkMerchant($transId, $mappingId, $merchantId, $merchantCode){
		log_message('error', 'library validRedirectLinkMerchant');
		$this->processing_code = "1083";
		
		$data = new stdClass();
		$data->request_id = $transId;
		$data->merchant_id = $merchantId;
		$data->mapping_id = $mappingId;
		$data->merchantCode = $merchantCode;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request validRedirectLinkMerchant: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone validRedirectLinkMerchant: ' . print_r($requestMegaV, true));
		
		return $requestMegaV;
	}
	
	/*	Funtion lay danh sach merchant cua user da lien ket vi
	**	$userName : tên tài khoản
	*/
	public function listMerchantMappingEpurse($userName, $transId){
		log_message('error', 'library listMerchantMappingEpurse');
		$this->processing_code = "1084";
		
		$data = new stdClass();
		$data->user_name = $userName;
		$data->request_id = $transId;
		
		$key3des = $this->getSessionKey();
		if(!$key3des)
			return false;
		
		log_message('error', 'data request listMerchantMappingEpurse: ' . print_r($data, true));
		$encryptData = $this->encrypt3DES(json_encode($data), $key3des);
		$requestMegaV = $this->requestMegaVCore($encryptData);
		log_message('error', 'data respone listMerchantMappingEpurse: ' . print_r($requestMegaV, true));
		
		$requestMegaV = json_decode($requestMegaV);
		if(isset($requestMegaV->status) && $requestMegaV->status == STATUS_SUCCESS)
		{
			$listMerchant = json_decode($this->decrypt3DES($requestMegaV->data, $key3des));
			log_message('error', 'DATA listMerchant : ' . print_r($listMerchant, true));
			if(isset($listMerchant->accMapping))
			{
				return $listMerchant->accMapping;
			}
			else
			{
				return false;
			}
		}
		
		return false;
	}
	
}