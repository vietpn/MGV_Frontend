<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: Hatt
 * Date: 8/11/14
 * Time: 10:39 AM
 * To change this template use File | Settings | File Templates.
 */

class Register extends CI_Controller
{
	public $deviceOS  = 'Unknow';
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('id_encrypt');
        $this->load->library('id_curl');
        $this->load->library('redis');
		$this->load->library('session_memcached');
        $this->load->helpers('security');
        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->helper('form');
		$this->load->library('form_validation');

        $this->load->helper('language');
        $this->lang->load('megav_message');
		$this->load->library('authen_interface');
		$this->load->library('megav_core_interface');
		$this->load->library('megav_libs');
		
		//ini_set('display_errors', 'Off');
		//error_reporting(0);
		//define('MP_DB_DEBUG', false);
		
    }

    public function index()
    {
		$this->session_memcached->get_userdata();
		if (isset($this->session_memcached->userdata['info_user']['userID']) && !empty($this->session_memcached->userdata['info_user']['userID'])) {
            redirect(); die;
        }
		
        delete_cookie('clientId');
        delete_cookie('mac_address');
        delete_cookie('publisher_id');
        delete_cookie('source_url');
        $curr_url = $_SERVER['QUERY_STRING'];
        if (empty($curr_url)) {
            $clientId = CLIENT_ID_OPENID;
            $mac_address = $publisher_id = $source_url = '';
        } else {
            parse_str($curr_url, $query_array);
            $clientId = isset($query_array['appId']) ? htmlEntities($query_array['appId']) : CLIENT_ID_OPENID;
            $mac_address = isset($query_array['mac_address']) ? htmlEntities($query_array['mac_address']) : '';
            $publisher_id = isset($query_array['publisher_id']) ? htmlEntities($query_array['publisher_id']) : '';
            $source_url = isset($query_array['source_url']) && $this->valid_url(htmlEntities($query_array['source_url'])) ? htmlEntities($query_array['source_url']) : '';
        }
		
        //$clientInfo = $this->get_client_info($clientId, CLIENT_ID_OPENID);
		
        $this->load->library('user_agent');
        $is_mobile = $this->agent->is_mobile();
        //$is_autoactive = isset($clientInfo->requireActiveUser)&&$clientInfo->requireActiveUser == 1?'false':'true';
        $is_autoactive = isset($clientInfo->requireActiveUser)&&$clientInfo->requireActiveUser == 1?'true':'false';
        //$client_type = $clientInfo->clientType;

        //$this->input->set_cookie('clientId', $clientId, TIMELIFE_REDIS);
        //$this->input->set_cookie('mac_address', $mac_address, TIMELIFE_REDIS);
        //$this->input->set_cookie('publisher_id', $publisher_id, TIMELIFE_REDIS);
        //$this->input->set_cookie('source_url', $source_url, TIMELIFE_REDIS);

		
		$this->load->view('Layout/layout_register_combined', array('postFL' => 1));
		
			
    }

    /**
     * Ham Dang ky moi tai khoan tien loi
     * @author tienhm
     * - Kiem tra thong tin Client
     *  + Neu la Auto Active -> Khong required truong email
     *  + Neu là Normal -> required truong email
     * - Kiem tra dang thiet bi
     *  + Neu thiet bi la mobile -> Khong hien thi day du thong tin. Khong hien thi captcha
     *  + Neu thiet bi la web -> Hien thi day du thong tin. Co yeu cau nhap captcha
     */

	 public function do_register()
	 {
        $redis = new CI_Redis();
		$data = array('wrong_form' =>'1');
		$data['postFL'] = 1;
		$clientID = htmlEntities($this->input->cookie('clientId'));
		$post = $this->input->post();
		if(isset($post))
		{
			$session = $this->input->cookie("megav_session");
			$session = $this->megav_libs->_unserialize($session);
			
			$this->form_validation->set_rules('client_type', '', 'trim|xss_clean');
			$this->form_validation->set_rules('clientId', '', 'trim|xss_clean');
			$this->form_validation->set_rules('mac_address', '', 'trim|xss_clean');
			$this->form_validation->set_rules('publisher_id', '', 'trim|xss_clean');
			$this->form_validation->set_rules('source_url', '', 'trim|xss_clean');
			
			//$this->form_validation->set_rules('username', 'Tên tài khoản', 'trim|alpha_dash|required|xss_clean');
			$this->form_validation->set_rules('password', 'Mật khẩu', 'required|trim|xss_clean|max_length[20]|min_length[6]');
			$this->form_validation->set_rules('re_password', 'Mật khẩu nhập lại', 'required|xss_clean|matches[password]|max_length[20]|min_length[6]');
			$this->form_validation->set_rules('fone', 'Số điện thoại', 'trim|required|is_numeric|max_length[11]|min_length[10]|xss_clean');
			$this->form_validation->set_rules('fullname', 'Tên đầy đủ', 'trim|required|max_length[50]|xss_clean');
			$this->form_validation->set_rules('accept_dksd', 'DKSD', 'trim|required|xss_clean');

			//$this->form_validation->set_message('alpha_dash', '%s không được chứa ký tự đặc biệt');
			//$this->form_validation->set_message('required', '%s không được để trống');
			//$this->form_validation->set_message('checkUser', '%s đã tồn tại trên hệ thống');
			//$this->form_validation->set_message('valid_email', 'Email sai định dạng');
			//$this->form_validation->set_message('is_numeric', '%s phải là dạng số');
			//$this->form_validation->set_message('xss_clean', '%s chứa các thành phần đặc biệt. Sai định dạng');
			//$this->form_validation->set_message('max_length', '%s vượt quá độ dài quy định');
			//$this->form_validation->set_message('min_length', '%s phải có ít nhất %s ký tự.');
			//$this->form_validation->set_message('matches', 'Mật khẩu nhập lại không khớp');
			if ($this->form_validation->run() == true ) 
			{
				//$this->load->library('recapcha_gg');
				//if($this->recapcha_gg->verifyReCapcha($this->input->post('g-recaptcha-response')))
				//{
					//$username = $this->input->post('username');
					$username = $this->input->post('fone');
					$fullname = $this->input->post('fullname');
					
					$pass = $this->input->post('password');
					$fone = $this->input->post('fone')?$this->input->post('fone'):'-1';
					$mac_address = $this->input->cookie('mac_address');
					
					$user_data = array(
						'username' => $username,
						'pass' => random_string('numeric', 8)
					);
					//$this->session_memcached->set_userdata('user_data', $user_data);
					//$info = $this->authen_interface->register($username, $email, $this->id_encrypt->encrypt($pass), $fullname, $fone, $gen, $birthday, $address, $idNo, $idIssueDate, $add_IssueIdNo, $mac_address, $clientID, $this->deviceOS);
					
					
					// luu redis giao dich dang ky: key= sesson key
					$transId = "RGT" . date("Ymd") . rand();
					//$session = $this->input->cookie("megav_session");
					//$session = $this->_unserialize($session);
					$data_register = array('mobile' => $fone, 
											'uname' => $fone, 
											'pass' => $pass, 
											'transid' => $transId, 
											'fullname' => $fullname);
					$redis->set($transId, json_encode($data_register));
					unset($data_register);
					$session['user_data'] = $transId;
					$this->session_memcached->_set_cookie($session);
					unset($session);
					
					$requestRegister = $this->megav_core_interface->register($username, $fone, $fullname, "", $pass, $transId);
					if($requestRegister)
					{
						$response = json_decode($requestRegister);
						log_message('error', 'data register: ' . print_r($response, true));
						if(isset($response->status))
						{
							if($response->status == STATUS_SUCCESS)
							{
								// goi dang ky thanh cong ==> hien form nhap OTP
								$data['send_otp'] = '1';
								//$this->load->view('Layout/layout_register_combined', $data);
							}
							elseif($response->status == '10')
							{
								$data['error_fone'] = "Tài khoản đã tồn tại";
							}
							else
							{
								// hien thon bao loi
								$error = 1;
								$view = $this->megav_libs->page_result_register(lang('MVM_'.$response->status), '/register', null, null, 1);
								echo $view;
							}
						}
						else
						{
							$error = 1;
							$mess = "Có lỗi trong quá trình đăng ký. Vui lòng thử lại.";
							$view =  $this->megav_libs->page_result_register($mess, '/register', null, null, 1);
							echo $view;
						}
					}
					else
					{
						// dawng ky that bai
						$mess = "Không kết nối dc server.";
						$error = 1;
						$view = $this->megav_libs->page_result_register($mess, '/register');
						echo $view;
					}
					
					
				//}
				//else // rerify recapcha google fail
				//{
					//$data['error_capcha'] = 1;
					/*
					$this->load->library('user_agent');
					$is_mobile = $this->agent->is_mobile();
					$is_autoactive = $this->input->post('is_autoactive') == 'true'?'true':'false';
					$client_type = $this->input->post('client_type');
					$this->load->helper('form');
					$this->load->view('Layout/layout_register_combined', $data);
					*/
				//}
				

			}
		}
		
		if(!isset($error))
            $this->load->view('Layout/layout_register_combined', $data);
        
	 }
	 
	public function confirm_register()
	{
		$this->form_validation->set_rules('otp_code', 'Mã xác thực', 'min_length[3]|max_length[50]|trim|alpha_dash|required|xss_clean');
		$this->form_validation->set_message('alpha_dash', '%s không được chứa ký tự đặc biệt');
        $this->form_validation->set_message('required', '%s không được để trống');
        $this->form_validation->set_message('checkUser', '%s đã tồn tại trên hệ thống');
        $this->form_validation->set_message('valid_email', 'Email sai định dạng');
        $this->form_validation->set_message('is_numeric', '%s phải là dạng số');
        $this->form_validation->set_message('xss_clean', '%s chứa các thành phần đặc biệt. Sai định dạng');
        $this->form_validation->set_message('max_length', '%s vượt quá độ dài quy định');
        $this->form_validation->set_message('min_length', '%s phải có ít nhất %s ký tự.');
        $this->form_validation->set_message('matches', 'Mật khẩu nhập lại không khớp');
		if ($this->form_validation->run() == true ) 
		{
			$redis = new CI_Redis();
			$session = $this->input->cookie("megav_session");
			$session = $this->megav_libs->_unserialize($session);
			if(!empty($session['user_data']))
			{
				$data_register = $redis->get($session['user_data']);
				$data_register = json_decode($data_register, true);
				$requestRegister = $this->megav_core_interface->acctiveAccount($data_register['mobile'], $data_register['mobile'], $data_register['fullname'], '', $data_register['pass'], $this->input->post('otp_code'),$data_register['transid']);
				if($requestRegister)
				{
					$response = json_decode($requestRegister);
					if(isset($response->status))
					{
						log_message('error', 'Register respone: ' . print_r($response, true));
						if($response->status == STATUS_SUCCESS)
						{
							//hien trang thong bao
							//$mess = "Chúc mừng bạn đã đăng ký thành công trên MegaV.vn. Còn <b id='countdown_text'></b>s nữa hệ thống sẽ chuyển hướng bạn về màn hình đăng nhập.";
							$mess = "Chúc mừng bạn đã kích hoạt thành công tài khoản trên MegaV.vn";
							$view = $this->megav_libs->page_result_register($mess, null, null, '/', null);
							echo $view;
							
							$redis = new CI_Redis();
							$redis->set('SHOWLOGIN', '1');
						}
						elseif($response->status == STATUS_WRONG_OTP)
						{
							/*
							if(isset($data_register['numb_wrong']) && $data_register['numb_wrong'] > WRONG_OTP)
							{
								$view = $this->megav_libs->page_result_register(lang('MVM_'.$response->status), '/register');
								echo $view;
								die;
							}
							*/
								
							if(isset($data_register['numb_wrong']))
								$data_register['numb_wrong'] += 1;
							else
								$data_register['numb_wrong'] = 1;
							
							
							$redis->set($session['user_data'], json_encode($data_register));
							$this->session_memcached->_set_cookie($session);
							
							$this->load->library('user_agent');
							$is_mobile = $this->agent->is_mobile();
							$is_autoactive = $this->input->post('is_autoactive') == 'true'?'true':'false';
							$client_type = $this->input->post('client_type');
							$this->load->view('Layout/layout_register_combined', array(
									'is_mobile' => $is_mobile,
									'is_autoactive' =>$is_autoactive,
									'clientId' => htmlEntities($this->input->cookie('clientId')),
									'mac_address' => $this->input->post('mac_address'),
									'publisher_id' => $this->input->post('publisher_id'),
									'source_url' => $this->input->post('source_url'),
									'client_type' => $client_type,
									'wrong_form' =>'1',
									'send_otp' =>'1',
									'error_capcha' => 1,
									'err_mess' => 'Mã OTP sai',
									'postFL' => 1
									));
							
							
						}
						else
						{
							$view = $this->megav_libs->page_result_register(lang('MVM_'.$response->status), 'register', null, null, 1);
							echo $view;
						}
					}
					else
					{
						$mess = "Có lỗi trong quá trình đăng ký. Vui lòng thử lại.";
						$view = $this->megav_libs->page_result_register($mess, 'register', null, null, 1);
						echo $view;
					}
				}
				else
				{
					// dawng ky that bai
					$mess = "Không kết nối được server.";
					$view = $this->megav_libs->page_result_register($mess, 'register', null, null, 1);
					echo $view;
				}
			}
			else
			{
				// khong lay dc transID
				
			}
		}
		else
		{
			$error = $this->form_validation->error_array();
			$this->load->library('user_agent');
			$is_mobile = $this->agent->is_mobile();
			$is_autoactive = $this->input->post('is_autoactive') == 'true'?'true':'false';
			$client_type = $this->input->post('client_type');
			$this->load->view('Layout/layout_register_combined', array(
					'is_mobile' => $is_mobile,
					'is_autoactive' =>$is_autoactive,
					'clientId' => htmlEntities($this->input->cookie('clientId')),
					'mac_address' => $this->input->post('mac_address'),
					'publisher_id' => $this->input->post('publisher_id'),
					'source_url' => $this->input->post('source_url'),
					'client_type' => $client_type,
					'wrong_form' =>'1',
					'send_otp' =>'1',
					'error_capcha' => 1,
					'err_mess' => $error['otp_code'],
					'postFL' => 1
					));
		}
	}
	
	 
    /**
     * Chuan hoa ngay thang
     *
     * @author: tienhm
     */
    private function standardlize_number($number){
        if(strlen($number) == 1)
            return '0'.$number;
        elseif(strlen($number) == 2)
            return $number;
        else
            return '00';
    }

   

    /**
     * Get url clientid to redirect after login
     * @author: hatt
     * @param $clientID
     * @return bool
     */
    public function get_client_info($clientID, $myID = null)
    {
		$info = $this->authen_interface->get_client_info($clientID, $myID);
		
        if (!empty($info)) {
            $response = json_decode($info);

            if ($response->status == '00') {

                $data = $this->id_encrypt->decode_access_token($response->data);

                log_message('error', 'REGISTER : get_client_info data giai ma ' . print_r($data, true) );
                if(is_null($myID)){
                    return $data->clientResponseUrl;
                } else {
                    return $data;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function security_code()
    {
        $this->load->library(array('simplecaptcha'));
        $this->simplecaptcha->CreateImage($this);

    }

    /**
     * Check su ton tai ten nguoi dung tren ht
     * Su dung redis cache
     * @author: hatt
     * @param $clientId
     */
    public function check_user()
    {
        $username = $this->input->post('username');
        $arrUser = array();
        $redis = new CI_Redis();
        $list_user = $redis->get('AvailableUsers');
        $arrUser = explode(',', $list_user);
        if (in_array($username, $arrUser)) {
            echo json_encode(array('success' => false, 'message' => 'Tên đã được sử dụng'));
        } else
            echo json_encode(array('success' => true, 'message' => 'Thành công'));
        die;
    }

    /**
     * Check su ton tai email dung tren ht
     * Su dung redis cache
     * @author: hatt
     * @param $clientId
     */
    public function check_email()
    {
        $email = $this->input->post('email');
        $arrEmail = array(); //mang chua cac dia chi mail user
        $redis = new CI_Redis();
        $list_user = $redis->get('AvailableUsers');
        $arrEmail = explode(',', $list_user);
        if (in_array($email, $arrEmail)) {
            return json_encode(array('success' => false, 'message' => 'Email đã được sử dụng'));
        } else
            return json_encode(array('success' => true, 'message' => 'Thành công'));
    }


    /**
     * Giai ma
     * @author: hatt
     * @created : 9/9/2014
     * @param $text
     */
    private function decode_accessToken($text)
    {
        $key = base64_decode(KEY_DECODE);
        $str = hex2bin($text);
        $size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($size, MCRYPT_RAND);
        $decrypted = mcrypt_decrypt(MCRYPT_3DES, $key, $str, MCRYPT_MODE_ECB, $iv);
        $info_user = json_decode(rtrim($this->pkcs5_unpad($decrypted)));
        return $info_user;
    }

    /**
     * Chuyen ve ASCII
     * @param $text
     * @return bool|string
     */
    private function pkcs5_unpad($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        $a = substr($text, 0, -1 * $pad);
        return substr($text, 0, -1 * $pad);
    }
    private function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen ( $text ) % $blocksize);
        return $text . str_repeat ( chr ( $pad ), $pad );
    }

    /**
     * Ma hoa du lieu
     * @param $text
     * @return string
     */
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
    private function hex2bin($hex_string)
    {
        $pos = 0;
        $result = '';
        while ($pos < strlen($hex_string)) {
            if (strpos(HEX2BIN_WS, $hex_string{$pos}) !== FALSE) {
                $pos++;
            } else {
                $code = hexdec(substr($hex_string, $pos, 2));
                $pos = $pos + 2;
                $result .= chr($code);
            }
        }
        return $result;
    }

    /**
     * Acctive tai khoan
     */
    public function active_account()
    {
        $curr_url = urldecode($_SERVER['QUERY_STRING']);
        $active = str_replace('active=','',$curr_url);
		//echo $active; die;
        if (empty($active)) {
            $this->megav_libs->page_result('Không tồn tại đường dẫn này');
        } else {
            $resp = $this->decode_accessToken($active);
            $key = $resp->key;
            $value = $resp->value;
            $clientId=$resp->clientid;
            $username=$resp->username;
            $email = $resp->email;
            $source_url = $resp->source_url;
            $tail = empty($source_url)?'':('&source_url='.$source_url);
            //check redis
			//echo $key; die;
            if ($this->checkredis($key, $value)) {
                //active code
				
				$info = $this->authen_interface->activeUsers($clientId, $username);
				
                log_message('error', 'thong tin response ' . $info);
                if (!empty($info)) {
                    $info_response = json_decode($info, true);
                    if ($info_response['status'] == '00' && $info_response['type'] == 'activeUsers') { //login bang email thanh cong
                        $urlclient = $this->get_client_info($clientId);
                        if (empty($urlclient)) {
                            $url_client = URL_CLIENT;
                        } else {
                            $url_client = $urlclient;
                        }

                        $data_email = array(
                            'username' => $username,
                            'url_client' => $url_client
                        );
						
						$mess_err = "Không thể gửi được email kích hoạt tới tài khoản email của bạn.";
						$mess_succ = "Bạn đã kích hoạt thành công tài khoản.";
						$this->call_ws_notify_openID($username, $email, $clientId, $mess_err, $mess_succ, $source_url, $url_client, 4);
						
                        //[27/7/2015]luanbv comment
						//$this->sendmail($data_email, $email, 'email/kichhoat_thanhcong', '', '', lang('kichhoat_thanhcong'));
							
					/*	$this->load->library('id_email');
						$email_content = $this->load->view('email/xac_thuc_email_hien_tai', array('data' => $data_email), true);
						$ret = $this->id_email->sendmail($email,$email_content,lang('kichhoat_thanhcong'));*/
						/*if(!$ret){
							if($mess_err!='')
							{
								log_message_flex('error', 'send_mail_failed: '.$mess_err, 'FATAL ERROR');
								$this->page_result($mess_err);
							}
						}else{
							if($mess_succ!='')
							{
								$this->page_result($mess_succ);
							}
						
						}	*/
							
                        //gui mot email thanh cong
                       // $this->page_result('Bạn đã kích hoạt thành công tài khoản.', 'login?appId=' . $clientId.$tail);
                    } else {
                        $this->megav_libs->page_result('Không tồn tại đường dẫn này. Hoặc link kích hoạt đã được sử dụng ', 'login?appId=' . $clientId.$tail);
                    }
                } else {
                    log_message_flex('error', 'active_account ' . $url, 'FATAL ERROR');
                    $this->megav_libs->page_result('Không tồn tại đường dẫn này. Hoặc link kích hoạt đã được sử dụng ', 'login?appId=' . $clientId.$tail);
                }
            } else {
                $this->megav_libs->page_result('Không tồn tại đường dẫn này', 'login?appId=' . $clientId.$tail);
            }
        }
    }
    public function checkredis($key, $value)
    {
        $redis = new CI_Redis();
        $str = $redis->get($key);

		log_message_flex('error', 'check redis: ' .'vaue:'.$str .'   key: '.$key);
        if ($str == $value)
            return true;
        else
            return false;
    }

	
    /**
     * Kiem tra url de redirect co hop le khong
     * Phan url hop le duoc khai bao trong file config de phuc vu truong hop thanh toan 247
     * va tranh viec truyen url khong hop le tao vong lap vo han
     * @param $url
     * @return bool
     */
    public function valid_url($url)
    {
        $this->config->load('valid_url');
        $valid_url = $this->config->item('thanhtoan247');
        if (isset($valid_url) && is_array($valid_url) && count($valid_url)) {
            if (in_array($url, $valid_url)) return true;
            else return false;
        } else {
            return true;
        }
    }

    /**
     * Tao session dang nhap cho OpenID
     * @author: tienhm
     *
     */
    public function set_info_user($frontend_accesstoken, $signature){
        $info_Authen = $this->id_encrypt->decode_access_token($frontend_accesstoken); //giai ma authenCode lay du lieu can thiet
        //Luu lai thong tin nguoi dung
        $arrUserinfo = array(
            'Id' => $info_Authen->UserId,
            'idNo' => $info_Authen->IdNo,
            'birthday' => $info_Authen->Birthday,
            'fullname' => $info_Authen->fullname,
            'mobileNo' => $info_Authen->Mobile,
            'createDate' => $info_Authen->IssueDate,
            'partnerID' => $info_Authen->PartnerId,
            'gender' => $info_Authen->Gender,
            'email' => $info_Authen->Email,
            'address' => $info_Authen->Address,
            'userID' => $info_Authen->UserName,
            'status' => $info_Authen->AccountType,
            'idNo_dateIssue' => $info_Authen->IdIssueDate,
            'idNo_where' => $info_Authen->IdIssuePlace,
            'access_token' => $info_Authen->AccessTokenStr,
            'Au_ExpiredDate' => $info_Authen->ExpiredDate,
            'signature' => (str_replace(array('\r','\n'),'',$signature))
        );
        $this->session_memcached->set_userdata('info_user', $arrUserinfo);
        return;
    }

    /**
     * Nghe thong tin dang nhap chuyen ve
     * @author: hatt
     * create on : 14/08/2014
     * Khi KH comfirm chap nhan
     */
    public function comfirm_code()
    {
//        $mess = array(); //thong diep nhan duoc sau comfirm
        //redis lay clientId
        $this->session_memcached->get_userdata();
        $redis = new CI_Redis();
        $clientId = htmlEntities($this->input->cookie('clientId'));
        $uname = $this->session_memcached->userdata['user_data']['username'];
        $data = $redis->get('buffer_' . $clientId . $uname . '_authen');
        
		$info = $this->authen_interface->user_confirm($clientId, $data);
        log_message('error', 'COMFIRM:||Thông tin nhan ve :' . $info);
        $mac_address = $this->input->cookie('mac_address');
        $redis->del('buffer_' . $clientId . $uname . '_authen');
        if ($this->input->post('source_url'))
            $source_url = $this->input->post('source_url');
        if (!empty($info)) {
            $response = json_decode($info, true);
            log_message('error', 'COMFIRM:||Thông tin comfirm tra ve :' . $info);

            if ($response['status'] == '00') {
                //tao cookie ghi lai authenCode
                $this->input->set_cookie($clientId . '_authen', $data, TIMELIFE_REDIS);
                $this->input->set_cookie(CLIENT_ID_OPENID . '_authen', CLIENT_ID_OPENID, TIMELIFE_REDIS);
                //check clientId co url redirect ko neu cho tra ve link ko thi hien thi thong bao
                $checkurl = $this->get_client_info($clientId);
                if (empty($checkurl) == true && $clientId != CLIENT_ID_OPENID) {
                    log_message('error', 'COMFIRM || chekurrl null ');
                    redirect(base_url().'login/finished');
                } else {
                    if ($clientId != CLIENT_ID_OPENID) {
                        $checkurl .= '/LoginOpenID/LoginID/ResultLogin';
                        $checkurl .= '?authenCode=' . $data;
                        if (isset($source_url) && $source_url != '')
                            $checkurl .= '&source_url=' . $source_url;
                        header('Location:' . $checkurl);
                        die();
                    } else {
                        $str_au = $this->id_encrypt->decode_access_token($data);
                        if (!empty($str_au)) {
                            redirect('login/edit_info?clientID=' . CLIENT_ID_OPENID, 'location');
                            die;

                        } else {
                            $this->session_memcached->unset_userdata('user_data');
                            $this->session_memcached->unset_userdata('info_user');
                            echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
                            echo "<script>alert('Chúc mừng bạn dã comfirm thành công!Xin vui lòng dang nhập lại thêm lần nữa');</script>";
                            echo "<script>location.href='" . base_url() . "'</script>";

                        }
                    }
                }
            } else {
                $this->page_result(lang('ERR_'.$response['status']));
            }

        } else {
            log_message_flex('error', 'comfirm_code: '.$url, 'FATAL ERROR');
            $this->page_result('Hệ thống bận xin vui lòng quay lại sau.');
        }
//        $this->session_memcached->unset_userdata($clientId.'_authen');

    }

    /**
     * Hien thi trang comfirm quyen truy cap cho app
     * @author: hatt
     * @created on : 19/08/2014
     */
    public function page_comfirm($source_url = null)
    {
        log_message('error', 'CATCH IN COMFIRM: ' . $source_url);
        $data = array(
            'source_url' => $source_url,
            'clientId' => htmlEntities($this->input->cookie('clientId'))
        );
        $this->view['content'] = $this->load->view('comfirm', $data, true);
        $this->load->view('Layout/layout', array('data' => $this->view));
    }
	
	//[7-9-2015] phongwm: detect thiet bi mobile
	public function detect_mobile($detect_name)
	{
		$detect = new Mobile_Detect;
		//$detect_name = array('WindowsPhoneOS', 'iOS', 'AndroidOS');
		$view_wap = 0;
		foreach($detect_name as $name)
		{
			$check = $detect->{'is'.$name}();
			$class='';
			if($check)
			{
				return $name;
				//echo $name;
			}
		}
		return "1";
	}
	//[7-9-2015] phongwm: detect thiet bi mobile
	
	/** 8-9-2015
     * get redis DKSD
     * @author: phongwm
     * 
     */
	 
	 function get_redis_dksd($id)
	 {
		$redis_dksd = new CI_Redis();
		$redis_dksd->select(REDIS_SELECT_INDEX);
		$data_dksd_key = "DKSD_".$id;
		$data_dksd = $redis_dksd->get($data_dksd_key);
		if(is_null($data_dksd))
			return ;
		$data = get_object_vars(json_decode($data_dksd));
		return $data;
	 }
	 
	 
	 public function call_ws_notify_openID($username, $email, $clientID, $mess_err, $mess_succ, $source_url, $link_client, $type)
	 {
		log_message('error', 'Goi service gui mail');
		
		$data_request = array('method' 		=> $type, 
							  'username' 	=> $username, 
							  'clientID' 	=> $clientID,
							  'source_url' 	=> $source_url,
							  'link_client' => $link_client,
							  'email' 		=> $email);
		
		$request = $this->encrypt_notify(json_encode($data_request));
		$url = URL_SERVICE_NOTIFY . "?request=$request";
		try{
			$result = $this->id_curl->get_curl($url);
			$result = json_decode($result);
			log_message('error', 'WS return : '.print_r($result, true));
			if ($result->status != '1') 
			{
				log_message('error', 'send_mail_failed: '.$mess_err, 'FATAL ERROR');
				$this->page_result($mess_err);
			} else {
				log_message('error', 'send mail success');
				$this->page_result($mess_succ, 'login?appId='.$clientID);
			}
		} catch (Exception $e){
			 $this->page_result(lang('login_email_send_failed'), 'login');
		}
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
	
	public function check_ip($action = null)
	{
		log_message('error', '123456');
		$this->load->config('warnning_ip');
		$wn_ip = $this->config->item('ip_access');
		$this->session_memcached->get_userdata();
		$ip_client = $this->session_memcached->userdata('ip_address');
		
		$time = date('Y-m-d h:i:s', time());
		$email = '';
		$username = isset($this->session_memcached->userdata['info_user']['userID']) ? $this->session_memcached->userdata['info_user']['userID'] : '' ;
		foreach($wn_ip as $ip)
		{
			if($ip_client == $ip)
			{
				$data_request = array('method' 		=> 6, 
							  'username' 	=> $username, 
							  'clientID' 	=> $ip_client,
							  'source_url' 	=> $time,
							  'link_client' => $action,
							  'email' 		=> $email);
		
				$request = $this->encrypt_notify(json_encode($data_request));
				$url = URL_SERVICE_NOTIFY . "?request=$request";
				log_message('error', 'URL: ' . $url);
				try{
					$result = $this->id_curl->get_curl($url);
					$result = json_decode($result);
					log_message('error', 'WS return : '.print_r($result, true));
				} catch (Exception $e){
					
				}
			}
		}
	}
	
}
