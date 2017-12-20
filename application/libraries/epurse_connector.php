<?php
class Epurse_Connector {
	public $url_v2_5 = URL_EPURESE;
//    public $url_v2_5 = 'http://172.16.10.70:8080/WS_Epurse_Ver2.5_Interface_proc_L/services/EpurseV2Interface?wsdl';

    public $partnerID_v2_5 = PARTNER_ID;
	public $partnerKey_v2_5 = PARTNER_KEY;
	public $partnerCode_v2_5 = PARTNER_CODE;



    /**
     * Cap nhat thong tin nguoi dung cho thay doi email, sdt va pass,...
     * @param $current
     * @param $info
     * @return bool|mixed
     */
    public function update_profile($current, $info){
		
		$data = array ();
		try {
			$ci=& get_instance();
			$time = gettimeofday ();

            $arr_acc_Token= array(
                'value' =>$ci->session->userdata ['Access_Token'],
                'clientID' =>CLIENT_ID,
                'email'     => $ci->session->userdata ['Account_Email'],
                'expiretime' =>$ci->session->userdata['Au_ExpiredDate'],
                'signature'  =>$ci->session->userdata['signature']
            );


			$transid = $time ['sec'] . $time ['usec'] . rand ( 100, 999 );
			$data ['mti'] = '0600';
			//echo 'id no: '.$info['Account_IDCard']; die;
			if(isset($info['Account_IDCard'])){
                $data ['idNo'] = trim($info['Account_IDCard']);
            }else{
                $data ['idNo'] = '"0'.rand(111111111, 999999999).'"';
            }

			$birthday = date("Ymd", strtotime($info['Account_Birthday']));
			$data ['birthday'] = $birthday;
			$data ['termTxnDateTime'] = date("YmdHis");
            if(isset($info['Account_Address'])){
                $data ['address'] = $info['Account_Address'];
            }else{
                $data ['address'] = "VietNam";
            }
            $data ['transId'] = PARTNER_CODE . '_'.$transid;
            $data ['subscriberId'] = $this->mencrypt_3des ( $current['Account_Name'], PARTNER_KEY );
            $data ['gender'] = $info['Account_Sex'];
//			$data ['token'] = $ci->session->userdata ['epurse_token'];
            $data ['accesstoken'] = json_encode($arr_acc_Token);
            if(!empty($info ['Account_Password'])){

			$data ['subscriberPasswd_new'] = $this->mencrypt_3des ( $info ['Account_Password'], PARTNER_KEY );
            }
            $data ['lmsPCode'] = '8001';
            $data ['registerNotify'] = '1';
            $data ['partnerId'] = PARTNER_ID;
            if(!empty($info['Account_Fullname']))
            $data ['subscriberfullName'] = $info['Account_Fullname'];
            $data ['emailAddress'] = $current['Account_Email'];
            $data ['registerNotify'] = '1';

			$data ['mobileNo'] = !empty($info['Account_PhoneNumber']) ? $info['Account_PhoneNumber'] : '0987123456';
			$mac = $data ['subscriberId'] . PARTNER_ID . $data ['transId'] . $data ['termTxnDateTime'];
			$data ['mac'] = $this->mDESMAC_3des ( $mac, PARTNER_KEY );

            log_message('error','INFO: UPDATE_PROFILE: Thong tin gui len WS: | transid: '.$transid.' | data: '.json_encode($data), false, true);
			$client = new SoapClient ( URL_EPURESE );
			
			$result = $client->__soapCall ( "procService", array (json_encode ( $data ) ) );
            log_message('error','INFO: UPDATE_PROFILE: Thong tin WS tra ve: | transid: '.$transid.' | data: '.json_encode($result), false, true);

			//var_dump($result);
			$result = json_decode ( $result );
			if ($result->lmsRespCode=='00') {
				return $result;
			} else
				return false;
		
		} catch ( Exception $e ) {
			log_message('error','ERROR: UPDATE_PROFILE: Loi goi WS: transid: '.$transid.' | Exception: '.$e->getMessage(), false, true);
			return false;
		}
	}

    /**
     * Tru tien tren epure
     * @param $amount
     * @param $info
     * @param int $time
     * @param string $mti
     * @param int $termtxt
     * @param $bonusId
     * @param $transid
     * @return bool|mixed
     *
     * hatt: modified 10/01/2014
     * chinh theo openID
     */
    public function payment($amount, $info, $time = 0, $mti = "0200", $termtxt = 0, $bonusId, $transid){
		try {
            $ci=& get_instance();
            if($termtxt == 0)
			{
				$termtxt = date("YmdHis");
			}
			$arr_acc_Token= array(
                'value' =>$ci->session->userdata ['Access_Token'],
                'clientID' =>CLIENT_ID,
                'email'     => $ci->session->userdata ['Account_Email'],
                'expiretime' =>$ci->session->userdata['Au_ExpiredDate'],
                'signature'  =>$ci->session->userdata['signature']
            );
			$data = array ();
			$data ['mti'] = $mti;
			$data ['lmsPCode'] = '7217';
			
			if($mti=='0200')
				$data ['transId'] = PARTNER_CODE . '_' .$transid;
			else if($mti=='0400')
				$data ['transId'] = $transid;
			
			$data ['partnerId'] = PARTNER_ID;
			$data ['subscriberId'] = $this->mencrypt_3des ( $info['Account_Name'], PARTNER_KEY );
			$data ['termTxnDateTime'] = $termtxt;
//			$data ['token'] = $ci->session->userdata ['epurse_token'];
            $data ['accesstoken'] = json_encode($arr_acc_Token);
			$data ['txnAmt'] = "$amount";
			$data ['bonusId'] = $bonusId;
			$data ['productCode'] = '1';
			$data ['productQty'] = '1';
			$mac = $data ['subscriberId'] . PARTNER_ID . $data ['transId'] . $data ['termTxnDateTime'];
			$data ['mac'] = $this->mDESMAC_3des ( $mac, PARTNER_KEY );

            //log thong tin gui len WS
            log_message('error','INFO: PAYMENT: Thong tin gui len WS: transid: '.$transid.' | data: '.json_encode ( $data ), false, true);

			$client = new SoapClient ( URL_EPURESE );

			$result = $client->__soapCall ( "procService", array (json_encode ( $data ) ) );
			
			log_message('error','INFO: PAYMENT: Thong tin WS tra ve: transid: '.$transid.' | response: '. json_encode($result), false, true );
			
			$result = json_decode ( $result );
			
			if (isset($result->lmsRespCode)) {
				return $result;
			} else
				return false;
		} catch ( Exception $e ) {
			log_message ( 'error', 'ERROR: PAYMENT: Loi goi WS Payment: transid: '.$transid.' | Exception: ' . $e->getMessage (), false, true );
			return false;
		}
		
	}

    /**
     * Cong tien tren epure
     * @param $amount
     * @param $info
     * @param $TransId
     * @param string $mti
     * @param int $termtxt
     * @param int $stan
     * @param $bonusId
     * @return bool|mixed
     */

    public function deposite_epurse($amount, $info, $TransId, $mti = "0200", $termtxt = 0,$stan = 0, $bonusId = BONUS_BALANCE,$type=0){
		try {
			$ci=& get_instance();
			$data = array ();
			$data ['mti'] = $mti;
			$data ['lmsPCode'] = '7707';
            if($type=='0' || isset($type)==false)
            {
                //CHG - Nap tien qua Charging the
			    $data ['transId'] = PARTNER_CODE . '_' .TRANSID_CHARGING.$TransId;
            }else if($type=='1'){
                //IBK - Nap tien qua Ibanking
                $data ['transId'] = PARTNER_CODE . '_IBK' .$TransId;
            }else if($type == '2'){
                //CT - Chuyen Tien
                $data ['transId'] = PARTNER_CODE.'_'.$TransId;
            }
            $data ['partnerId'] = PARTNER_ID;
			$data ['subscriberId'] = $this->mencrypt_3des ( $info['Account_Name'], PARTNER_KEY );
			//$data ['subscriberPasswd'] = $this->mencrypt_3des ( $info['subscriberPasswd'], PARTNER_KEY );
			$data ['termTxnDateTime'] = $termtxt;
//			$data ['accesstoken'] = ''.$ci->session->userdata ['Access_Token'];
			$data ['txnAmt'] = $amount;
			$data ['bonusId'] = $bonusId;
			
			$mac = $data ['subscriberId'] . PARTNER_ID . $data ['transId'] . $data ['termTxnDateTime'];
			$data ['mac'] = $this->mDESMAC_3des ( $mac, PARTNER_KEY );
				
			//var_dump(json_encode ( $data ));
            log_message('error','INFO: DEPOSITE: Thong tin gui len WS: transi: '.$TransId.' | data: '.json_encode ($data).' | user: '.json_encode($info), false, true);

			$client = new SoapClient ( URL_EPURESE );
			$result = $client->__soapCall ( "procService", array (json_encode ( $data ) ) );
				
			log_message('error','INFO: DEPOSITE: Thong tin WS tra ve: transid: '.$TransId.' | response: '.json_encode($result), false, true );
				
			$result = json_decode ( $result );
			
			//var_dump ( 'Response: ' . $result->lmsRespCode );
				
			if (isset($result->lmsRespCode)) {
				return $result;
			} else
				return false;
		} catch ( Exception $e ) {
			log_message ( 'error', 'ERROR: DEPOSITE: Loi goi WS Deposite: transid: '.$TransId.' | Exception: ' . $e->getMessage (), false, true );
			return false;
		}
		
		
	}

    /**
     * Giai ma seri the
     * @param $encryptText
     * @param string $key
     * @return bool|string
     */
    public function decrypt_cardSeries($encryptText, $key = CARD_DECRYPT_KEY) {
		$key = substr ( $key, 0, 24 );
		$iv = substr ( $key, 0, 8 );
		$keyData = "\xA2\x15\x37\x08\xCA\x62\xC1\xD2" . "\xF7\xF1\x93\xDF\xD2\x15\x4F\x79\x06" . "\x67\x7A\x82\x94\x16\x32\x95";
		$cipherText = base64_decode ( $encryptText );
		$res = mcrypt_decrypt ( "tripledes", $key, $cipherText, "cbc", $iv );
		$resUnpadded = $this->pkcs5_unpad ( $res );
		return $resUnpadded;
	}

    /**
     * Ma hoa the
     * @param $plainText
     * @param string $key
     * @return string
     */
    public function encrypt_cardSeries($plainText, $key = CARD_DECRYPT_KEY) {
		$key = substr ( $key, 0, 24 );
		$iv = substr ( $key, 0, 8 );
		$keyData = "\xA2\x15\x37\x08\xCA\x62\xC1\xD2" . "\xF7\xF1\x93\xDF\xD2\x15\x4F\x79\x06" . "\x67\x7A\x82\x94\x16\x32\x95";
		$padded = $this->pkcs5_pad ( $plainText, mcrypt_get_block_size ( "tripledes", "cbc" ) );
		$encText = mcrypt_encrypt ( "tripledes", $key, $padded, "cbc", $iv );
		return base64_encode ( $encText );
	}
	
	public function DownloadExtFile($gameID, $requestID) {
		$request = array ('id' => $gameID, 'request_id' => $requestID, 'user' => WS_XZONE_NAME, 'pass' => WS_XZONE_CODE );
		$client = new SoapClient ( WS_XZONE_URL );
		try {
			$using_result = $client->__call ( 'GetGameUrlByGameID', array ('input' => $request ) );
			return $using_result->GetGameUrlByGameIDResult;
		
		} catch ( Exception $e ) {
			return false;
		}
	}
	
	public function DownloadFile($path, $speed = null) {
		$fp = FOPEN ( $path, 'r' );
		$data = STREAM_GET_META_DATA ( $fp );
		fclose ( $fp );
		$size = ( int ) str_replace ( 'Content-Length: ', "", $data ['wrapper_data'] [1] );
		if ($size <= 0) {
			$ch = curl_init ();
			curl_setopt ( $ch, CURLOPT_HEADER, true );
			curl_setopt ( $ch, CURLOPT_NOBODY, true );
			curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt ( $ch, CURLOPT_URL, $path ); // specify the url
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
			$head = curl_exec ( $ch );
			$size = curl_getinfo ( $ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD );
		}
		
		if ($size > 0) {
			set_time_limit ( 0 );
			
			while ( ob_get_level () > 0 ) {
				ob_end_clean ();
			}
			
			// $size = sprintf('%u', filesize($path));
			$speed = (is_null ( $speed ) === true) ? $size : intval ( $speed ) * 1024;
			
			header ( 'Expires: 0' );
			header ( 'Pragma: public' );
			header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header ( 'Content-Type: application/octet-stream' );
			header ( 'Content-Length: ' . $size );
			header ( 'Content-Disposition: attachment; filename="' . basename ( $path ) . '"' );
			header ( 'Content-Transfer-Encoding: binary' );
			
			for($i = 0; $i <= $size; $i = $i + $speed) {
				echo file_get_contents ( $path, false, null, $i, $speed );
				
				while ( ob_get_level () > 0 ) {
					ob_end_clean ();
				}
				
				flush ();
				sleep ( 1 );
			}
			
			exit ();
		}
		
		return false;
	}
	
	private function GenerateMAC($data, $key) {
		log_message ( 'error', 'INFO: GENERATE_MAC: Bat dau Genera Mac: key: '.json_encode($key).' | data: '.json_encode($data), false, true);
		$client = new SoapClient ( WS_REG_URL );
		$params = array ("input" => ( object ) $data, "key" => $key );
		try {
			$result = $client->__soapCall ( "GenerateMAC", array ($params ) );
            log_message ( 'error', 'INFO: GENERATE_MAC: Thong tin WS tra ve: response: '.json_encode($result), false, true);
            return $result->GenerateMACResult;
		} catch ( Exception $e ) {
			log_message ( 'error', 'ERROR: GENERATE_MAC: Loi goi WS: Exception: ' . $e->getMessage (), false, true );
			return false;
		}
	}

	private function mencrypt_3des($text,$key) {
		
		$text=$this->pkcs5_pad($text,8);  // AES?16????????
		$size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($size, MCRYPT_RAND);
		$bin = pack('H*', bin2hex($text) );
		//var_dump($bin);
		$encrypted = mcrypt_encrypt(MCRYPT_3DES, $key, $bin, MCRYPT_MODE_ECB,$iv);
		//var_dump($encrypted);
		$encrypted = base64_encode ( $encrypted );
		return $encrypted;
	}
	
	private function mdecrypt_3des($input, $key) {
		$td = mcrypt_module_open ( MCRYPT_3DES, '', MCRYPT_MODE_ECB, '' );
		$blocksize = mcrypt_enc_get_block_size ( $td );
		$keysize = mcrypt_enc_get_key_size ( $td );
		$iv_size = mcrypt_enc_get_iv_size ( $td );
		$iv = mcrypt_create_iv ( $iv_size, MCRYPT_RAND );
		$input_len = strlen ( $input );
		$padsize = $blocksize - ($input_len % $blocksize);
		@mcrypt_generic_init ( $td, $key, $iv );
		$decrypted_data = mdecrypt_generic ( $td, $input );
		
		return $decrypted_data;
	}
	
	private function PaddingPKCS7s($data) {
		$block_size = mcrypt_get_block_size ( 'tripledes', 'cbc' );
		$padding_char = $block_size - (strlen ( $data ) % $block_size);
		$data .= str_repeat ( chr ( $padding_char ), $padding_char );
		return $data;
	}
	
	private function mDESMAC_3des($input, $key) {
		$input = sha1 ( $input );
		$len = strlen ( $input );
		
		$td = mcrypt_module_open ( MCRYPT_3DES, '', MCRYPT_MODE_ECB, '' );
		$blocksize = mcrypt_enc_get_block_size ( $td );
		$keysize = mcrypt_enc_get_key_size ( $td );
		$iv_size = mcrypt_enc_get_iv_size ( $td );
		 $iv = "hywebpg5";
		$input_len = strlen ( $input );
		$padsize = $blocksize - ($input_len % $blocksize);
		@mcrypt_generic_init ( $td, $key, $iv );
		
		// echo strlen($input) . "<BR>";
		// echo strlen(mcrypt_generic($td, $input)) . "<BR>";
		$MacDes = bin2hex ( mcrypt_generic ( $td, $this->hex2bin ( $input ) ) );
		return strtoupper ( $MacDes );
	}
	
	private function CardNumberEncrypt_3des($key, $input) {
		
		$td = mcrypt_module_open ( MCRYPT_3DES, '', MCRYPT_MODE_ECB, '' );
		$blocksize = mcrypt_enc_get_block_size ( $td );
		$input = $this->pkcs5_pad ( $input, $blocksize );
		
		$keysize = mcrypt_enc_get_key_size ( $td );
		$iv_size = mcrypt_enc_get_iv_size ( $td );
		$iv = mcrypt_create_iv ( mcrypt_enc_get_iv_size ( $td ), MCRYPT_RAND );
		
		$input_len = strlen ( $input );
		$padsize = $blocksize - ($input_len % $blocksize);
		
		@mcrypt_generic_init ( $td, $key, $iv );
		$Encrypt3Des = mcrypt_generic ( $td, $input );
		
		$Encrypt3Des = bin2hex ( $Encrypt3Des );
		$Encrypt3Des = strtoupper ( $Encrypt3Des );
		return $Encrypt3Des;
	}
	
	private function pkcs5_pad($text, $blocksize) {
		$pad = $blocksize - (strlen ( $text ) % $blocksize);
		return $text . str_repeat ( chr ( $pad ), $pad );
	}
	
	private function pkcs5_unpad($text) {
		$pad = ord ( $text {strlen ( $text ) - 1} );
		if ($pad > strlen ( $text ))
			return false;
		if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)
			return false;
		return substr ( $text, 0, - 1 * $pad );
	}
	
	private function Byte2Hex1($b) {
		$string = "";
		for($k = 0; $k < strlen ( $b ); $k ++) {
			$string = $string . substr ( $b, ($k), 2 );
			$k ++;
		}
		$b = $string;
		echo $b;
		
		$counter = strlen ( $b ) / 2;
		$hs = "";
		$stmp = "";
		echo "<BR>";
		echo $counter;
		echo "<BR>";
		for($n = strlen ( $b ) - 16; $n < strlen ( $b ) - 8; $n ++) {
			$stmp = $b [$n] . "0XFF";
			if (strlen ( $stmp ) == 1) {
				$hs = $hs + "0" + $stmp;
			} else {
				$hs = $hs + $stmp;
			}
			
			if ($n < (strlen ( $b ) - 1)) {
				$hs = $hs + "";
			}
		}
		return $hs;
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
	
	private function encryptRSA($input, $rsa_key) {
		$key = $this->get_public_rsa_key ( $rsa_key );
		openssl_public_encrypt ( $input, $output, $key );
		return $output;
	}
	
	private function decryptRSA($input, $rsa_key) {
		$key = $this->get_private_rsa_key ( $rsa_key );
		openssl_private_decrypt ( $input, $output, $key );
		return $output;
	}
	
	private function get_public_rsa_key($pub_key) {
		openssl_get_publickey ( $pub_key );
		return $pub_key;
	}
	
	private function get_private_rsa_key($pri_key) {
		openssl_get_privatekey ( $pri_key );
		return $pri_key;
	}
}