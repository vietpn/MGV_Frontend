<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hatt
 * Date: 8/11/14
 * Time: 10:31 AM
 * To change this template use File | Settings | File Templates.
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller
{
	public $deviceOS  = 'Unknow';
    public function __construct()
    {
        parent::__construct();
        $this->load->driver('cache');
        $this->load->helper('language');
        $this->load->library('session_memcached');
        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->library('redis');
        $this->load->library('authen_interface');
        $this->load->library('megav_libs');
        $this->lang->load('login');
        $this->lang->load('error_message');
		
		
		//if(MEGAID_WARNNING_IP == '1')
		//	$this->check_ip();
		
		//ini_set('display_errors', 'Off');
		//error_reporting(0);
		//define('MP_DB_DEBUG', false);
		
    }

    public function index()
	{
		$data = array();
		$this->load->view('Layout/layout_login', $data);
	}
	
	
    public function index_bk()
    {
        /*       B1: ktra session
         * Neu chua co session thi vao trang dn
         * Neu da co session thi ktra $clientID
         * ---Exit cookie tuong ung thi khong lam gi
         * ---cookie chua ton tai thi vao trang comfirm de tao cookie
         */
		/*
        delete_cookie('clientId');
        delete_cookie('mac_address');
        delete_cookie('publisher_id');
        delete_cookie('source_url');
        $curr_url = $_SERVER['QUERY_STRING'];
        $mac_address = '';
        $publisher_id = '';
        $source_url = '';
        $redirect_to_url = '';
        if (empty($curr_url)) {
            $clientId = CLIENT_ID_OPENID;
        } else {
            // edited by tienhm
            // get Publisher ID from url then add to redis
            parse_str($curr_url, $query_array);
            $clientId = isset($query_array['appId']) ? htmlEntities($query_array['appId']) : CLIENT_ID_OPENID;
            $mac_address = isset($query_array['mac_address']) ? htmlEntities($query_array['mac_address']) : '';
            $publisher_id = isset($query_array['publisher_id']) ? htmlEntities($query_array['publisher_id']) : '';
            $source_url = isset($query_array['source_url']) ? htmlEntities($query_array['source_url']) : '';
            $redirect_to_url = isset($query_array['redirect_to_url']) ? htmlEntities($query_array['redirect_to_url']) : '';

        }
		log_message('error', 'Check clientID index:' . $clientId);
        $this->load->library('user_agent');
        $this->input->set_cookie('clientId', $clientId, TIMELIFE_REDIS);
        $this->input->set_cookie('mac_address', $mac_address, TIMELIFE_REDIS);
        $this->input->set_cookie('publisher_id', $publisher_id, TIMELIFE_REDIS);
        $this->input->set_cookie('source_url', $source_url, TIMELIFE_REDIS);
        $this->input->set_cookie('redirect_to_url', $redirect_to_url, TIMELIFE_REDIS);
		*/
        //$clientInfo = $this->get_client_info($clientId, CLIENT_ID_OPENID);
        //if ($clientInfo == false) {
        //    log_message('error', 'CLIENT INFO NULL: ' . $clientId, 'FATAL ERROR');
        //    $this->page_result(lang('login_error'), 'login?appId=' . $clientId);
        //} else {
			
			
            //$client_type = $clientInfo->clientType;
            //$this->input->set_cookie('clientType', $clientInfo->clientType, TIMELIFE_REDIS);

            $this->session_memcached->get_userdata();
			/*
			// [2016-08-13] phongwm add: check sessin id da login tren memcache
			if(isset($this->session_memcached->userdata['info_user']['userID']))
			{
				$lis_sess_id_login = $this->cache->memcached->get('USER_SESS_ID'.$this->session_memcached->userdata['info_user']['userID']);
				$arr_sess_id_login = explode(",", $lis_sess_id_login);
				if(!in_array($this->session_memcached->userdata['session_id'], $arr_sess_id_login))
				{
					log_message('error', 'REMOVE DATA SESSION');
					$this->cache->memcached->save('USER_SESS_ID'.$this->session_memcached->userdata['info_user']['userID'], '', MEMCACHE_TTL);
					$this->remove_data();
				}
			}
			// [2016-08-13] phongwm add
			*/
            if (empty($this->session_memcached->userdata['info_user']['userID'])) { //neu nguoi dung chua dang nhap
			log_message('error', 'chua co data dang nhap');
                $data = array();
                // Neu co cookie Remember Password
                // Lay Username và Password fill vào form đăng nhập
                if ($this->input->cookie('epay_remember_me')) {
                    $remember_info = $this->decode_access_token($this->input->cookie('epay_remember_me'));
                    if ($remember_info->ip == $this->session_memcached->userdata['ip_address'] &&
                        $remember_info->user_agent == $this->session_memcached->userdata['user_agent'] &&
                        $remember_info->clientID == $clientId
                    ) {
                        $data['username'] = $remember_info->username;
                        $data['password'] = $remember_info->password;
                    }
                }
                //$data['clientId'] = $clientId;
                //$data['mac_address'] = $mac_address;
                //$data['publisher_id'] = $publisher_id;
                //$data['source_url'] = $source_url;
                //$data['url_google'] = "";
                //$this->load->library('user_agent');
                //$data['is_mobile'] = $this->agent->is_mobile();
                //$data['requireActiveUser'] = $clientInfo->requireActiveUser;
				
				//get redis
				//$redis_login 				= new CI_Redis();
				//$redis_login_key	 		= "$clientId"."_loginmethod";
				//$redis_login->select(REDIS_SELECT_INDEX);
				//$redis_login_value 		= $redis_login->get("$redis_login_key");
				//$redis_login_value_array 	= explode(',', $redis_login_value);
				//$lock_login_type = array();
				
				$this->load->view('Layout/layout_login', $data);
					
            } else { //neu nguoi dung da dang nhap vao va con session dang nhap
                $username = $this->session_memcached->userdata['info_user']['userID'];
                $login_from = isset($this->session_memcached->userdata['info_user']['login_from']) ? $this->session_memcached->userdata['info_user']['login_from'] : '-1';
                $non_epay_account_id = isset($this->session_memcached->userdata['info_user']['non_epay_account_id']) ? $this->session_memcached->userdata['info_user']['non_epay_account_id'] : '-1';
                $str_access_token = $this->encrypt($this->session_memcached->userdata['info_user']['access_token']);
                $response = $this->authen_interface->loginThroughUsername($username, $login_from, $mac_address, $non_epay_account_id, $publisher_id, $clientId, $str_access_token);

				log_message('error', ' thong tin nguoi dung da dang nhap truoc: '.print_r($response, true));
                // Lay Authen Code cho client tuong ung
                if ($response['status'] == '00') {
                    if ($response['confirmed'] == '0') {
                        $redis = new CI_Redis();
                        $redis->set('buffer_' . $clientId . $username . '_authen', $response['data']);
                        log_message('error', 'SOURCE B4 Redirect 1: ' . $source_url);
                        $this->page_comfirm($source_url);
                    } else {
                        log_message('error', 'LOGIN THROUGH USERNAME SUCCESS: ' . $clientId);
                        $this->login_processing($clientId, $response['data'], $source_url);
                    }
                } else {
                    if ($response['status'] == '001') {
                        $this->page_result(lang('login_error_001'), 'login');
                    } elseif ($response['status'] == '02') {
						
                        $this->remove_data();
                        //$this->page_result(lang('login_again'), 'login?' . $curr_url);
						redirect(base_url() . "login");
						//redirect("login?$curr_url");
                    } else {
                        $this->page_result(lang('login_failed'), 'login');
                    }
                }
            }
        //}
		/*
		if(MEGAID_WARNNING_IP == '1')
		{
			$this->load->library('checkip');
			$this->checkip->check_ip('Load trang index');
		}
		*/
    }


    /**
    **	do_login()
    **/
    public function do_login()
    {
		
		$data = array();
		$data['postFL'] = '';
		/*
		if(MEGAID_WARNNING_IP == '1')
		{
			$this->load->library('checkip');
			$this->checkip->check_ip('Login || do_login');
		}
		*/
        log_message('error', 'DANG NHAP || do_login: thong tin nguoi dung post luc dang nhap. Username : ' . $this->input->post('username'));
		log_message('error', 'ACTION DANG NHAP || do_login :' . print_r($this->session_memcached->userdata, true));
		// add by phongwm		
		// check phuong thuc login		
		$back_url = 'login?appId='.htmlEntities($this->input->cookie('clientId'));		
		
        // Luu mat khau nguoi dung vao cookie
        if ($this->input->post('remember_pass')) {
            $remember_data = $this->encrypt(json_encode(array(
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
                'user_agent' => substr($this->input->user_agent(), 0, 120),
                'ip' => $this->input->ip_address(),
                'clientID' => CLIENT_ID_OPENID)
            ));
            $this->input->set_cookie('epay_remember_me', $remember_data, TIMELIFE_REDIS);
        } else {
            delete_cookie('epay_remember_me');
        }

        $this->form_validation->set_rules('username', 'Số điện thoại', 'trim|required|is_numeric|xss_clean');
        $this->form_validation->set_rules('password', 'Mật khẩu', 'trim|required|xss_clean|min_length[3]');
        
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == true) 
		{
			log_message('error', 'form validation run success');
			$username = $this->input->post('username');
			// check so lan sai mat khau
			$verify_capcha = '1';
			//if(!$this->check_wrong_pass())
			//{
			//	// check capcha
			//	$this->load->library('recapcha_gg');
			//	if(!$this->recapcha_gg->verifyReCapcha($this->input->post('g-recaptcha-response')))
			//	{
			//		$verify_capcha = '0';
			//	}
			//}
				
				//      B2: a: gui thong tn dang nhap len server
				
			if($verify_capcha == '1')
			{
				$password = $this->input->post('password');
				//$mac_address = htmlEntities($this->input->cookie('mac_address'));
				//$publisher_id = htmlEntities($this->input->cookie('publisher_id'));
				//$source_url = htmlEntities($this->input->cookie('source_url'));
				//log_message('error', 'DO LOGIN: SOURCE:' . $source_url);
				$this->send_info_user($password, $username, '', '', '');
			}
			else
			{

				//$data = array();
				//$data['clientId'] = htmlEntities($this->input->post('clientId'));
				//$data['mac_address'] = $this->input->post('mac_address');
				//$data['publisher_id'] = $this->input->post('publisher_id');
				//$data['source_url'] = $this->input->post('source_url');
				////$data['url_google'] = $authUrl;
				//$data['url_google'] = "";
				//$this->load->library('user_agent');
				//$data['is_mobile'] = $this->agent->is_mobile();
				//$data['requireActiveUser'] = $this->input->post('requireActiveUser');
				
				$data['error_capcha'] = 1;		
				//[31-7-2015] Edit by phongwm
				// detect mobile doi thap thi load view wap
				
				$this->load->view('Layout/layout_login', $data);
					
				
			}
			
        } else {

            //$data = array();
            //$data['clientId'] = htmlEntities($this->input->post('clientId'));
            //$data['mac_address'] = $this->input->post('mac_address');
            //$data['publisher_id'] = $this->input->post('publisher_id');
            //$data['source_url'] = $this->input->post('source_url');
            ////$data['url_google'] = $authUrl;
            //$data['url_google'] = "";
            //$this->load->library('user_agent');
            //$data['is_mobile'] = $this->agent->is_mobile();
            //$data['requireActiveUser'] = $this->input->post('requireActiveUser');
			
			
			$this->load->view('Layout/layout_login', $data);
			
			
        }
    }

    /**
     * Chuyen thong tin nguoi dung theo duong dan sau den server
     * @author hatt
     * @param $pass
     * @param $uname
     * @param $clientId
     */
    private function send_info_user($pass, $uname, $mac_address, $publisher_id, $source_url)
    {
        log_message('error', 'SOURCE: ' . $source_url);
        $redis = new CI_Redis();
        $clientId = CLIENT_ID_OPENID;
        		
		$data = $this->authen_interface->login_authen_user_pas($clientId, $uname, $pass, $mac_address, $publisher_id, $this->deviceOS);
		
        log_message('error', 'DANG NHAP ||send_info_user:  KET QUA NHAN DUOC TU server login ' . $data);
		// add by phongwm
		$back_url = 'login';
		/*
		if (empty($source_url))
            $back_url = 'login?appId=' . $clientId;
        else
            $back_url = 'login?appId=' . $clientId . '&source_url=' . $source_url;
		*/
		// end add by phongwm
        if (!empty($data)) {
            $response = json_decode($data);
            if ($response->status == '00') {
                /* t?o session dang nhap
                 * Sau do redirect toi trang comfirm
                 */
				 
				 $this->delete_wrong_pass();
				 
                $user_data = array(
                    'username' => $uname
                );
                $this->session_memcached->set_userdata('user_data', $user_data);
				/*
                if ($clientId != CLIENT_ID_OPENID) {
                    log_message('error', 'FRONTEND ACCESS TOKEN: ' . $response->frontend_accesstoken);
                    $this->input->set_cookie(CLIENT_ID_OPENID . '_authen', CLIENT_ID_OPENID, TIMELIFE_REDIS);
                }
				*/
                $this->set_info_user($response->frontend_accesstoken, $response->frontend_signature, 1, '');
					
				/*
				// tao cache luu session id 
				$sess_id_cache = $this->cache->memcached->get('USER_SESS_ID'.$this->session_memcached->userdata['info_user']['userID']);
				if($sess_id_cache == false || empty($sess_id_cache))
				{
					$this->cache->memcached->save('USER_SESS_ID'.$this->session_memcached->userdata['info_user']['userID'], $this->session_memcached->userdata['session_id'], MEMCACHE_TTL);
				}
				else
				{
					$this->cache->memcached->save('USER_SESS_ID'.$this->session_memcached->userdata['info_user']['userID'], $sess_id_cache.','.$this->session_memcached->userdata['session_id'], MEMCACHE_TTL);
				}
				*/
				
                if ($response->confirmed == '0') {
                    $redis->set('buffer_' . $clientId . $uname . '_authen', $response->data);
                    log_message('error', 'SOURCE B4 Redirect 2: ' . $source_url);
                    $this->page_comfirm($source_url);
                } else {
                    $this->login_processing($clientId, $response->data, $source_url);
                }

            } else {
                log_message('error', 'DANG NHAP ||send_info_user:  ERROR '.$response->status);
				// login that bai sai mat khau
				// luu redis check so lan login that bai $uname
				if($response->status == 'CQ')
				{
					$this->wrong_pass();
				}
                $this->megav_libs->page_result_login(lang('ERR_' . $response->status), $back_url, null, null, 1);
            }
        } else {

            if (empty($source_url))
                $back_url = 'login';
            else
                $back_url = 'login';
            $this->megav_libs->page_result_login(lang('login_error'), $back_url, null, null, 1);
        }


    }


    /**
     * 
     *
     * Ham xu ly chung cho luong login khong request
     */
    private function login_processing($clientId, $client_authen_code, $source_url)
    {
        log_message('error', 'LOGIN PROCESSING||CLIENT ID: ' . $clientId);
        log_message('error', 'LOGIN PROCESSING||REDIRECT URL: ' . $this->input->cookie('redirect_to_url'));
        $this->session_memcached->get_userdata();
        $redis = new CI_Redis();
        // forced logout
        if ($this->input->cookie('forced_logout') && $this->input->cookie('forced_logout') == 1) {
            $payment_info = json_decode($this->input->cookie('payment_info'));
            if ($payment_info->userId == $this->session_memcached->userdata['info_user']['Id']) {
                delete_cookie('forced_logout');
                redirect(base_url().'payment?info='.urlencode($payment_info));
            } else {
                delete_cookie('forced_logout');
                //delete_cookie('payment_info');
                $this->remove_data();
                $this->page_result(lang('payment_card_account_not_match'), 'login');
            }
        } else {
            if ($clientId == CLIENT_ID_OPENID) {
				
				$session = $this->input->cookie("megav_session");
				$session = $this->megav_libs->_unserialize($session);
				$redis = new CI_Redis();
				$redirectUrl = $redis->get(SOURCE_URL_KEY_PREFIX . $session['session_id']);
				$redis->del(SOURCE_URL_KEY_PREFIX . $session['session_id']);
				
                if ($redirectUrl && $redirectUrl != null && $redirectUrl != '') {
                    $url = htmlEntities($this->input->cookie('redirect_to_url'));
                    delete_cookie('redirect_to_url');
                    //redirect($redirectUrl);
					
					$result = $this->megav_libs->page_result_login('Đăng nhập thành công', null, 500, $redirectUrl);
					echo $result;
					//echo "<script>window.top.location='" . base_url('/transaction_manage') . "'</script>";
                } else {
                    //redirect('login/edit_info?clientID=' . CLIENT_ID_OPENID, 'location');
                    //redirect('/', 'location');
                    //die;
					$result = $this->megav_libs->page_result_login('Đăng nhập thành công', null, 500, '/transaction_manage');
					echo $result;
					//echo "<script>window.top.location='" . base_url('/transaction_manage') . "'</script>";
                }
            } else {
                if ($this->input->cookie('redirect_to_url') && filter_var($this->input->cookie('redirect_to_url'), FILTER_VALIDATE_URL)) {
                    $url = $this->input->cookie('redirect_to_url');
					log_message('error', 'redirect_to_url: '.$url);
                    delete_cookie('redirect_to_url');
                    redirect($url);
                } else {
                    $client_info = $this->get_client_info($clientId, CLIENT_ID_OPENID); //url redirect web
                    $checkurl = $client_info->clientResponseUrl;
                    $required_active = $client_info->requireActiveUser;
                    // Neu client la yeu cau active ma email khong hop le
                    // redirect sang trang yeu cau cap nhat thong tin email
                    if ($required_active == 1 && $this->invalid_email($this->session_memcached->userdata['info_user']['email'])) {
                        $message = sprintf(lang('need_update'), base_url('login/edit_info?clientID=' . $clientId));
                        $this->page_result($message, 'login/edit_info?clientID=' . $clientId);
                    } else {
                        $this->input->set_cookie($clientId . '_authen', $client_authen_code, MEMCACHE_TTL);
                        log_message('error', 'CLIENT ID: '.$clientId);
                        if (!empty($checkurl)) {
                            log_message('error', 'CHECK URL NOT NULL: '.$checkurl);
                            $checkurl .= '/LoginOpenID/LoginID/ResultLogin';
							
                            $checkurl .= '?authenCode=' . $client_authen_code;
                            if (isset($source_url) && $source_url != '')
                                $checkurl .= '&source_url=' . $source_url;
                            $redis->del('_referer');
                            log_message('error', 'send_info_user |REDIRECT URL : ' . $checkurl);
							log_message('error', 'header redirect url: '. $checkurl);
							
								header("location: ".$checkurl);
                        } else {
                            log_message('error', $clientId . ' FINISHED');
							$event_client = $this->get_client_even($clientId);
							if(!empty($event_client))
							{
								$eventid = $event_client['eventid'];
								$popupimg = $event_client['popupimg'];
								$redirecturl = $event_client['redirecturl'];
								redirect("login/finished?event_id=$eventid&popupimg=$popupimg&redirecturl=$redirecturl");
							}
							else
							{
								redirect('login/finished');
							}
                        }
                    }
                }
            }
        }
    }

    /**
     * Hien thi trang comfirm quyen truy cap cho app
     * @author: hatt
     * @created on : 19/08/2014
     */
    public function page_comfirm($source_url = null)
    {
        $data = array(
            'source_url' => $source_url,
            'clientId' => htmlEntities($this->input->cookie('clientId'))
        );
        $this->view['content'] = $this->load->view('comfirm', $data, true);
        $this->load->view('Layout/layout', array('data' => $this->view));
    }

    /**
     * Kiem tra email co vi pham hay khong
     * - Neu email null, khong chua ky tu @ hoac la dang @facebook.com thi coi la vi pham
     *
     * @param $email
     * @return bool
     */
    public function invalid_email($email)
    {
        log_message('error', 'EMAIL NE: ' . $email);
        if (empty($email)) {
            return true;
        } else {
            if (!stripos($email, '@')) {
                return true;
            } else {
                if (stripos($email, '@facebook.com')) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }


	public function changepass()
    {
		$this->session_memcached->get_userdata();
		if (isset($this->session_memcached->userdata['info_user']['userID']) && !empty($this->session_memcached->userdata['info_user']['userID'])) {
            redirect();
        }
		log_message('error', 'ACTION QUEN MAT KHAU || changepass: '. print_r($this->session_memcached->userdata, true));
        $redis = new CI_Redis();
        $curr_url = $_SERVER['QUERY_STRING'];
        $curr_url = urldecode($curr_url);
        if (empty($curr_url)) {
            $this->page_result(lang('login_false_link'));
        } else {
			$activelink = trim(str_replace('active=', '', $curr_url));
			
			$this->session_memcached->set_userdata('activelink', $activelink);
			
			// goi ham verify active link cua Authen server
				$info = $this->authen_interface->check_link_reset_pass($activelink);
				
				$info_response = json_decode($info, true);
				if($info_response['status'] == '00')
				{
					$data = array();
					$data['source_url'] = htmlEntities($this->input->cookie('source_url'));
					$data['clientId'] = htmlEntities($this->input->cookie('clientId'));
					$data['is_reset'] = 1;
					$this->view['content'] = $this->load->view('sub_pages/reset_password', $data, true);
					$this->load->view('Layout/layout_info', array('data' => $this->view, 'reset' => true, 'clientId' => CLIENT_ID_OPENID));
				}
				else
				{
					$this->megav_libs->page_result_reset_pass(lang('ERR_'.$info_response['status']), '/reset_password');
				}
        }
    }
	
	public function reset_user_pass()
    {
        $this->session_memcached->get_userdata();
		log_message('error', 'ACTION RESET MAT KHAU || reset_user_pass: ' . print_r($this->session_memcached->userdata, true));
		if(isset($this->session_memcached->userdata['info_user']['userID'])) {
			log_message('error', 'co thong tin user');
            redirect();
        }
		
        $clientId = $this->input->post('clientId') ? htmlEntities($this->input->post('clientId')) : CLIENT_ID_OPENID;
		
        $this->form_validation->set_rules('password', 'Mật khẩu mới', 'trim|required|xss_clean|min_length[6]|max_length[20]');
        $this->form_validation->set_rules('re_pass', 'Nhập lại mật khẩu', 'trim|required|xss_clean|min_length[6]|max_length[20]|matches[password]');
        //$this->form_validation->set_rules('activecode', 'activecode', 'trim|xss_clean');
		/*
        $this->form_validation->set_message('alpha_dash', '%s không được chứa ký tự đặc biệt');
        $this->form_validation->set_message('required', '%s không được để trống');
        $this->form_validation->set_message('xss_clean', '%s chứa các thành phần đặc biệt. Sai định dạng');
        $this->form_validation->set_message('max_length', '%s vượt quá độ dài quy định');
        $this->form_validation->set_message('min_length', '%s quá ngắn so với quy định');
        $this->form_validation->set_message('matches', 'Mật khẩu nhập lại không khớp');
		*/
        if ($this->form_validation->run() == true) {
			
			$this->session_memcached->get_userdata();
			$active_code = $this->session_memcached->userdata('activelink');
			
			if (isset($this->session_memcached->userdata['info_user']['userID']) || $this->input->post('is_reset')) {
				$password = $this->input->post('password');
				
				$old_password = '';
				
				$info = $this->authen_interface->reset_password($password, $active_code);
				
				log_message('error', 'RESET MAT KHAU:||Thong tin reset nhan ve :' . $info);

				if (!empty($info)) {
					log_message('error', 'RESET MAT KHAU: info ko empty');

					$response = json_decode($info, true);
					/*
					 *Thay doi thong tin tk thanh cong
					 * redirect ve trang nhan ket qua cua web client
					 */
					$error_mess = '';
					if ($response['status'] == '00') {
						$tail = $this->input->post('source_url') ? ('&source_url=' . $this->input->post('source_url')) : '';
						$error_mess = lang('change_pass_success');
						
						//$this->cache->memcached->save('USER_SESS_ID'.$username, '', MEMCACHE_TTL);
						$this->session_memcached->unset_userdata('user_data');
						$this->session_memcached->unset_userdata('info_user');
						
						echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
						echo "<script>alert('Cập nhật thành công. Vui lòng đăng nhập lại');</script>";
						//echo "<script>location.href='" . base_url() . 'login?appId=' . $clientId . $tail . "'</script>";
						echo "<script>location.href='" . base_url() . "'</script>";
					} else {
						switch ($response['status']) {
							case '02':
								$error_mess = 'ClienID không hợp lệ';
								break;
							case '03':
								$error_mess = 'Client bị tạm khóa';
								break;
							default:
								$error_mess = lang('ERR_' . $response['status']);
								break;
						}
						log_message('error', 'respone status: '. $response['status']);
						$this->page_result($error_mess, 'login');
					}
				} else {
					$this->page_result("Hệ thống đang bận xin vui lòng thử lại sau", 'login');
				}
			} else {
				$tail = $this->input->post('source_url') ? ('&source_url=' . $this->input->post('source_url')) : '';
				echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
				echo "<script>alert('Tài khoản của bạn đã đăng xuất. Vui lòng đăng nhập lại');</script>";
				echo "<script>location.href='" . base_url() . 'login' . "'</script>";
				die;
			}
			
        } else {
            $data = array(
                'source_url' => $this->input->post('source_url'),
                'clientId' => $clientId
            );
			
			$data['is_reset'] = '1';
			/*
            $data['activecode'] = $this->input->post('activecode');
			
			if(empty($data['activecode']))
				redirect();
			*/
			
            $this->view['content'] = $this->load->view('sub_pages/reset_password', $data, true);
            $this->load->view('Layout/layout_info', array(
                'data' => $this->view,
                'reset' => true,
				'activecode' => '',
                'clientId' => $clientId,
                'source_url' => $this->input->post('source_url')
            ));
        }
		
		/*
		if(MEGAID_WARNNING_IP == '1')
		{
			$this->load->library('checkip');
			$this->checkip->check_ip('Reset mat khau');
		}
		*/
    }
	
	
    /**
     * Check su ton tai ten nguoi dung tren ht
     * Su dung redis cache
     * @author: hatt
     * @param $clientId
     */
    public function checkuser($uname)
    {
        $arrUser = array();
        $redis = new CI_Redis();
        $list_user = $redis->get('AvailableUsers');
        $arrUser = explode(',', $list_user);
        if (in_array($uname, $arrUser)) {
            return true;
        } else
            return false;
    }

    /**
     * Check su ton tai email dung tren ht
     * Su dung redis cache
     * @author: hatt
     * @param $clientId
     * return: true --> ton tai email trong ht
     *         fail--> chua ton tai email trong ht
     */
    public function check_email($email)
    {
        $arrEmail = array(); //mang chua cac dia chi mail user
        $redis = new CI_Redis();
        $list_user = $redis->get('AvailableEmails');
        $arrEmail = explode(',', $list_user);
        if (in_array($email, $arrEmail)) {
            return true;
        } else
            return false;
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
		
        log_message('error', 'LOGIN : get_client_info info nhan ve sau khi gui di ' . $info);
        if (!empty($info)) {
            $response = json_decode($info);
            if ($response->status == '00') {
                $data = $this->decode_access_token($response->data);
                log_message('error', 'LOGIN : get_client_info data giai ma ' . print_r($data, true));
                if (is_null($myID)) {
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

    /**
     * logout ra khoi he thong openID
     * @author: hatt
     * @create on : 22/08/2014
     */
    public function logout()
    {
		$user_info = $this->session_memcached->get_userdata();
		log_message('error', 'ACTION DANG XUAT || logout: ' . print_r($this->session_memcached->userdata, true));
        $redis = new CI_Redis();
        $curr_url = $_SERVER['QUERY_STRING'];
        if (empty($curr_url)) {
            $clientId = CLIENT_ID_OPENID;
        } else {
            $info = trim(str_replace('appId=', '', $curr_url));
            if (!empty($info)) {
                $info_user = json_decode(urldecode($info));
                $url = isset($info_user->url) ? $info_user->url : '';
                $clientId = isset($info_user->clientID) ? $info_user->clientID : CLIENT_ID_OPENID;
            } else {
                $clientId = CLIENT_ID_OPENID;
            }
        }
        $checkurl = $this->get_client_info($clientId);
        $this->remove_data();
        // add redirect url cho cac trang con cua thanh toan 247
        // Yeu cau: Cuong To
        if (isset($info_user->redirect_url) && !empty($info_user->redirect_url) && $this->valid_url($info_user->redirect_url)) {
            log_message('error', 'LOGOUT | URL sau thoat' . $info_user->redirect_url);
            header('Location:' . $info_user->redirect_url);
            die;
        } elseif (!empty($checkurl) && $clientId != CLIENT_ID_OPENID) {
            if (!empty($url)) {
                $url = $checkurl . '/' . $url;
            } else {
                $url = $checkurl;
            }
            log_message('error', 'LOGOUT | URL sau thoat' . $url);
            header('Location:' . $url);
            die;
        } else {
            //$this->page_result("Ðăng xuất thành công", 'login');
			redirect("/"); die;
        }

    }

    public function remove_data()
    {
		log_message('error', 'remove data');
        $this->session_memcached->unset_userdata('user_data');
        $this->session_memcached->unset_userdata('info_user');
        //del danh sach redis
        delete_cookie('clientId');
        delete_cookie('epay_session');
        delete_cookie('ci_session');
        foreach ($_COOKIE as $key => $value) {
            if (stripos($key, '_authen')) {
                delete_cookie($key);
            }
        }
		/*
        $facebook = new Facebook(array(
            'appId' => $this->config->item('appId'),
            'appSecret' => $this->config->item('appSecret'),
        ));
        $facebook->destroySession();
		*/
        return true;
    }

    /**
     * login cung email
     * @author: hatt
     * @date: 4/9/2014
     * @param $email
     */
    public function login_wemail($email, $mac_address, $publisher_id, $loginfrom, $idfb, $source_url)
    {
        log_message('error', '1212 ID FB: ' . $idfb);
        log_message('error', '1212 Login From: ' . $loginfrom);
        $redis = new CI_Redis();
        $curr_url = htmlEntities($this->input->cookie('clientId'));
        log_message('error', '0912 CLIENT_ID WEMAIL: ' . $curr_url);
        $mess = array();
        $clientId = '';
//        echo $curr_url;die;
        if ($curr_url == '' || $curr_url == null)
            $clientId = CLIENT_ID_OPENID;
        else
            $clientId = $curr_url;
        
		$info_res = $this->authen_interface->login_through_email($clientId, $email, $loginfrom, $mac_address, $publisher_id, $idfb);
		
        log_message('error', '0912 thong tin response ' . $info_res);
        if (!empty($info_res)) {
            $info_response = json_decode($info_res, true);
            if ($info_response['status'] == '00') { //login bang email thanh cong
                if ($info_response['confirmed'] == '0') {
                    $data = $info_response['data'];
                    
					$info = $this->authen_interface->user_confirm($clientId, $data);
					
                    log_message('error', 'login_wemail : info  ' . $info);
                    if (empty($info)) {
                        $this->page_result(lang('login_failed'));

                    } else {
                        $response = json_decode($info, true);
                        if ($response['status'] == '00') {
                            $this->input->set_cookie($clientId . '_authen', $data, TIMELIFE_REDIS);
                            $checkurl = $this->get_client_info($clientId); //url redirect web
                            log_message('error', 'login_wemail URL tra ve ' . $checkurl);
                            if (!empty($checkurl)) {
                                $this->get_user_info_to_email($email, $mac_address, $idfb);
                                if ($clientId == CLIENT_ID_OPENID) {
                                    redirect('login/edit_info?clientID=' . CLIENT_ID_OPENID, 'location');
                                    die;

                                } else {

                                    $checkurl .= '/LoginOpenID/LoginID/ResultLogin';
                                    $checkurl .= '?authenCode=' . $data;
                                    if (isset($source_url) && $source_url != '')
                                        $checkurl .= '&source_url=' . $source_url;
                                    log_message('error', 'login_wemail |REDIRECT URL  : ' . $checkurl);
                                    header('Location:' . $checkurl);
                                    die();

                                }

                            } else {
                                $this->page_result(lang('login_success'));
                            }


                        } else {
                            $this->page_result(lang('login_failed'), 'login?appId=' . $clientId);
                        }

                    }

                } else //neu comfirm bang 1
                {
                    if ($clientId == CLIENT_ID_OPENID) {
                        $this->get_user_info_to_email($email, $mac_address, $idfb);
                        redirect('login/edit_info?clientID=' . CLIENT_ID_OPENID, 'location');
                        die;

                    } else {
                        $this->input->set_cookie($clientId . '_authen', $info_response['data'], TIMELIFE_REDIS);
                        $checkurl = $this->get_client_info($clientId); //url redirect web
                        log_message('error', '0912 CHECKURL: ' . $checkurl);
                        if ($checkurl == '') {
                            log_message('error', '0912 END');
							$event_client = $this->get_client_even($clientId);
							if(!empty($event_client))
							{
								$eventid = $event_client['eventid'];
								$popupimg = $event_client['popupimg'];
								$redirecturl = $event_client['redirecturl'];
								redirect("login/finished?event_id=$eventid&popupimg=$popupimg&redirecturl=$redirecturl");
							}
							else
							{
								redirect('login/finished');
							}
                            die;
                        } else {
                            $checkurl .= '/LoginOpenID/LoginID/ResultLogin';
                            $checkurl .= '?authenCode=' . $info_response['data'];
                            if (isset($source_url) && $source_url != '')
                                $checkurl .= '&source_url=' . $source_url;
                            header('Location:' . $checkurl);
                            die;
                        }
                    }
                }
            } else {
                $this->page_result(lang('login_error'), 'login?appId=' . $clientId);
                die;

            }
        } else {
            $this->page_result(lang('login_error'), 'login?appId=' . $clientId);
        }

    }


    /**
     * @author tienhm
     *
     * Ham gui email cho tai khoan tao tu dong bang social
     */
    public function sendMail($email, $loginFrom)
    {
        $data_email = array(
            'accountName' => $email,
            'loginFrom' => $loginFrom,
            'activeLink' => base_url() . "login"
        );

        $email_content = $this->load->view('email/tao_tai_khoan_social', array('data' => $data_email), true);
		try{
			$client = new SoapClient(WS_SENDMAIL, array('encoding' => 'UTF-8'));
			$result = $client->sendEmail(SMTP_USER, SMTP_PASSWORD, lang('send_mail_change_pass'), $email_content, $email, 0);
			if ($result != '0') {
				log_message('error', 'send mail failed: ' . $result, 'FATAL ERROR');
				$this->page_result(lang('login_email_send_failed'), 'login');
			} else {
				$this->page_result(lang('login_check_mail'));
			}
		} catch (Exception $e){
			 $this->page_result(lang('login_email_send_failed'), 'login');
		}
    }

    /**
     * Trang ket thuc cho SDK
     */
    public function finished()
    {
        $data = array();
        $this->load->view('finished', array('data' => $data));
    }

    /**
     * Tao session dang nhap cho OpenID
     *
     * @author: tienhm
     *
     */
    public function set_info_user($frontend_accesstoken, $signature, $login_from, $non_epay_account_id)
    {
        $info_Authen = $this->decode_access_token($frontend_accesstoken); //giai ma authenCode lay du lieu can thiet
        //Luu lai thong tin nguoi dung
        log_message('error', 'data info user sau khi login thanh cong: ' . print_r($info_Authen, true));
		$arrUserinfo = array(
            'Id' => $info_Authen->UserId,
            'idNo' => $info_Authen->IdNo,
            'birthday' => $info_Authen->Birthday,
            'fullname' => $info_Authen->Fullname,
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
            'signature' => (str_replace(array('\r', '\n'), '', $signature)),
            'login_from' => $login_from,
            'non_epay_account_id' => $non_epay_account_id,
			'RefreshToken' => $info_Authen->RefreshToken, 
			'phone_status' => $info_Authen->phone_status,
			'email_status' => $info_Authen->email_status,
			'security_method' => $info_Authen->security_method,
			'validate_idno' => $info_Authen->validate_idno,
			'id_img_f'	=> $info_Authen->id_img_f,
			'id_img_b'	=> $info_Authen->id_img_b,
			'avatar_url' => $info_Authen->avatar_url,
			'countUserbankAcc' => $info_Authen->countUserbankAcc,
            'countUserInbox' => $info_Authen->countUserInbox
        );
		
		// tao cache luu session id 
		/*
		$sess_id_cache = $this->cache->memcached->get('USER_SESS_ID'.$arrUserinfo['userID']);
		if($sess_id_cache == false || empty($sess_id_cache))
		{
			$this->cache->memcached->save('USER_SESS_ID'.$arrUserinfo['userID'], $this->session_memcached->userdata['session_id'], MEMCACHE_TTL);
		}
		else
		{
			$this->cache->memcached->save('USER_SESS_ID'.$arrUserinfo['userID'], $sess_id_cache.','.$this->session_memcached->userdata['session_id'], MEMCACHE_TTL);
		}
		*/
        $this->session_memcached->set_userdata('info_user', $arrUserinfo);
        return;
    }

   
    /**
     * Lay thong tin accessToken
     * @param $authCode
     * @param $clientId
     */
    public function get_access_token($authCode, $clientId)
    {
        
		$info_response = $this->authen_interface->get_access_token($clientId, $authCode);
		
        log_message('error', 'get_access_token || Lay thong tin accessToken tra ve ' . $info_response);
        if (!empty($info_response)) {
            return $info_response;
        } else
            return false;

    }

    /**
     * Lay thong tin nguoi dung bang email 
     * 
     */
    public function get_user_info_to_email($email, $mac_address, $idfb = '')
    {

        $signature = '';
        $accessToken = '';
        $au_expireddate = '';

		$info = $this->authen_interface->get_user_info($email);
        log_message('error', 'get_user_info_to_email url NHAN VE ' . $info);

        // $info = $this->getUserSecureInfo($email);

		$info_res = $this->authen_interface->login_through_email(CLIENT_ID_OPENID, $email, 1, $mac_address, '', $idfb);
		
        log_message('error', 'THONG TIN NHAN VE ' . $info_res);

        if (!empty($info_res)) {
            $respon = json_decode($info_res);
            if ($respon->status == '00') {
                $authenCode = $respon->data;
                $accessTokens = $this->get_access_token($authenCode, CLIENT_ID_OPENID);
                $response = json_decode($accessTokens, true);
                if ($response['status'] == '00') {
                    $accToken = $response['data'];
                    $sign = $response['signature'];
                    $signature = json_decode(str_replace(array('\r', '\n'), '', json_encode($sign)));
                    $arr = $this->decode_access_token($accToken);
                    $accessToken = $arr->AccessTokenStr;
                    $au_expireddate = $arr->ExpiredDate;

                } else {
                    return false;
                }
            }
        } else {
            $this->page_result(lang('login_error'), 'login?appId=' . CLIENT_ID_OPENID);
        }
        if (!empty($info)) {
            $respons = json_decode($info);
            if ($respons->status == '00') {
                $data = $respons->data;
                log_message('error', 'get_user_info_to_email data ' . $data);
                $obInfo = $this->decode_access_token($data);
                $arrUserinfo = array(
                    'Id' => $obInfo->Id,
                    'idNo' => $obInfo->idNo,
                    'birthday' => $obInfo->birthday,
                    'fullname' => $obInfo->fullname,
                    'mobileNo' => $obInfo->mobileNo,
                    'createDate' => $obInfo->createDate,
                    'partnerID' => $obInfo->partnerID,
                    'gender' => $obInfo->gender,
                    'email' => $obInfo->email,
                    'address' => $obInfo->address,
                    'userID' => $obInfo->userID,
                    'status' => $obInfo->status,
                    'idNo_dateIssue' => $obInfo->idNo_Issuedate,
                    'idNo_where' => $obInfo->idNo_where,
                    'access_token' => $accessToken,
                    'Au_ExpiredDate' => $au_expireddate,
                    'signature' => $signature
                );
                $this->session_memcached->set_userdata('info_user', $arrUserinfo);
            } else {
				log_message('error', 'respone status: '. $respons->status);
                $this->page_result(lang('ERR_' . $respons->status));
            }
        } else {
            $this->page_result('Hệ thống đang bạn xin vui lòng quay lại sau');
        }
        return;
    }

    /**
     * Lay thong tin nguoi dung
     *
     * @author tienhm
     * Lay thong tin userinfo sau khi da dang nhap tu dong bang OpenID
     * voi thong tin truyen len duoc ma hoa thong qua email
     */
    public function getUserSecureInfo($email = '')
    {
        $data = array(
            'method' => 'getUserInfo',
            'clientid' => CLIENT_ID_OPENID,
            'email' => $email
        );
        $send_request_info = array(
            'processing_code' => 5000,
            'clientid' => CLIENT_ID_OPENID,
            'subscriber_id' => '',
            'subscriber_email' => '',
            'data' => $this->encrypt(json_encode($data)),
            'type' => 'GetUserInfo'
        );
        $url = URL_AUTHENSERVER . urlencode('request=' . json_encode($send_request_info));
        $return_data = json_decode($this->id_curl->get_curl($url)); // Gui thong tin thanh toan

        return $return_data;
    }

    /**
     * @author: hatt
     * C?p nh?t thông tin tài kho?n ngu?i dùng
     */
    public function edit_info_user()
    {
        $this->session_memcached->get_userdata();
		log_message('error', 'ACTION THAY DOI THONG TIN || edit_info_user: ' . print_r($this->session_memcached->userdata, true));
		/*
		// [2016-08-13] phongwm add: check sessin id da login tren memcache
		if(isset($this->session_memcached->userdata['info_user']['userID']))
		{
			$lis_sess_id_login = $this->cache->memcached->get('USER_SESS_ID'.$this->session_memcached->userdata['info_user']['userID']);
			$arr_sess_id_login = explode(",", $lis_sess_id_login);
			if(!in_array($this->session_memcached->userdata['session_id'], $arr_sess_id_login))
			{
				log_message('error', 'REMOVE DATA SESSION');
				$this->cache->memcached->save('USER_SESS_ID'.$this->session_memcached->userdata['info_user']['userID'], '', MEMCACHE_TTL);
				$this->remove_data();
				$this->page_result(lang('not_permissions'), 'login?appId=' . $query_array['clientID'].'&redirect_to_url='.urlencode(base_url().'login/edit_info?'.$curr_url));
			}
		}
		// [2016-08-13] phongwm add
		*/
        $redis = new CI_Redis(); //connect redis
        $this->form_validation->set_rules('gen', 'Giới tính', 'trim|xss_clean');
		if(strlen($this->input->post('idNo')) <= 9)
			$this->form_validation->set_rules('idNo', 'Số CMT', 'numeric|trim|xss_clean|exact_length[9]');
		else
			$this->form_validation->set_rules('idNo', 'Số CMT', 'numeric|trim|xss_clean|exact_length[12]');
		
		if($this->input->post('id_no_month') || $this->input->post('id_no_day') || $this->input->post('id_no_year'))
		{
			$this->form_validation->set_rules('id_no_month', 'Tháng cấp', 'required|numeric|trim|xss_clean');
			$this->form_validation->set_rules('id_no_day', 'Ngày cấp', 'required|numeric|trim|xss_clean');
			$this->form_validation->set_rules('id_no_year', 'Năm cấp', 'required|numeric|trim|xss_clean');
		}
		
		$this->form_validation->set_rules('idNo_address', 'Nơi cấp', 'trim|xss_clean');
			
        $this->form_validation->set_rules('fullname', 'Tên đầy đủ', 'trim|xss_clean');
		if($this->input->post('bday') || $this->input->post('select_month') || $this->input->post('year'))
		{
			$this->form_validation->set_rules('select_month', 'Ngày sinh', 'trim|xss_clean');
			$this->form_validation->set_rules('bday', 'Ngày sinh', 'trim|xss_clean');
			$this->form_validation->set_rules('year', 'Ngày sinh', 'trim|xss_clean');
		}
		
        $this->form_validation->set_rules('password', 'Mật khẩu', 'trim|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|valid_email');
        $this->form_validation->set_rules('address', 'Ðịa chỉ', 'trim|xss_clean');
        $this->form_validation->set_rules('fone', 'Số điện thoại', 'trim|xss_clean|is_natural');
		
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_message('required', '%s bắt buộc không được để trống');
        $this->form_validation->set_message('min_length', '%s phải lớn hơn hoặc bằng 9 ký tự');
        if ($this->form_validation->run() == true) {
            //      B2: a: gui thong tn dang nhap len server
            if (isset($this->session_memcached->userdata['info_user']['userID'])) {
                $username = $this->session_memcached->userdata['info_user']['userID'];
				$phone_status = $this->session_memcached->userdata['info_user']['phone_status'];
				$email_status = $this->session_memcached->userdata['info_user']['email_status'];
                $password = '-1';
                $fullname = ($this->input->post('fullname') == '') ? $this->session_memcached->userdata['info_user']['fullname'] : $this->input->post('fullname');
                $gen = ($this->input->post('gen') == '') ? '-1' : $this->input->post('gen');
                $idNo = ($this->input->post('idNo') == '') ? '-1' : $this->input->post('idNo');
                if ($this->input->post('bday') && $this->input->post('year') && $this->input->post('select_month')) {
                    $birthday = $this->input->post('year') . $this->standardlize_number($this->input->post('select_month')) . $this->standardlize_number($this->input->post('bday'));
                } else {
                    $birthday = '-1';
                }
				
				if ($this->input->post('id_no_day') && $this->input->post('id_no_month') && $this->input->post('id_no_year')) {
                    $id_date = $this->input->post('id_no_year') . $this->standardlize_number($this->input->post('id_no_month')) . $this->standardlize_number($this->input->post('id_no_day'));
                } else {
                    $id_date = '-1';
                }
				
				$post = $this->input->post();
				log_message('error', 'DATA POST: ' . print_r($post, true));
				
                //$email = ($this->input->post('email') == '') ? '-1' : $this->input->post('email');
				$email = '-1';
				
				if($phone_status == 0)
				{
					$fone = ($this->input->post('fone') == '') ? '-1' : $this->input->post('fone');
				}
				else
				{
					$fone = '-1';
				}
				
                $address = ($this->input->post('address') == '') ? '-1' : $this->input->post('address');
                //$id_date = ($this->input->post('idNo_dateIssue') == '') ? '-1' : date('Ymd', strtotime($this->input->post('idNo_dateIssue'))); //$this->input->post('idNo_dateIssue');
                $id_place = ($this->input->post('idNo_address') == '') ? '-1' : $this->input->post('idNo_address');
                $accessToken = $this->session_memcached->userdata['info_user']['access_token'];
                $clientId = $this->input->post('clientId') ? htmlEntities($this->input->post('clientId')) : (isset($this->session_memcached->userdata ['clientID']) ? $this->session_memcached->userdata ['clientID'] : CLIENT_ID_OPENID);
                $arr_acc_Token = $this->encrypt(json_encode(array(
                    'value' => $accessToken,
                    'clientID' => CLIENT_ID_OPENID,
                    'email' => $this->session_memcached->userdata ['info_user']['email'],
                    'expiretime' => $this->session_memcached->userdata ['info_user']['Au_ExpiredDate'],
                    'signature' => $this->session_memcached->userdata ['info_user']['signature']
                )));
                
				$info = $this->authen_interface->update_user_info($username, $password, $fullname, $email, $fone, $gen, $birthday, $phone_status, $email_status, $address, $idNo, $id_date, $id_place, $arr_acc_Token);
				
                log_message('error', 'UPDATE USER INFO:||Thong tin nhan ve :' . $info);
                if (!empty($info)) {
                    $response = json_decode($info);
                    if ($response->status == '00') {
						log_message('error', 'UPDATE USER INFO SUCCESS');
                        $this->session_memcached->unset_userdata('user_data');
                        $this->session_memcached->unset_userdata('info_user');
                        echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
                        echo "<script>alert('Cập nhật thành công. Vui lòng đăng nhập lại');</script>";
                        $return_url = $redis->get($clientId . '_url_info_return');
                        if ($return_url) {
                            echo "<script>location.href='" . base_url() . 'login?appId=' . $clientId . "&source_url=" . $return_url . "'</script>";
                        } else {
                            echo "<script>location.href='" . base_url() . 'login?appId=' . $clientId . "'</script>";
                        }
                        die;
                    } else {
						log_message('error', 'respone status: '. $response->status);
                        $this->page_result(lang('ERR_' . $response->status));
                    }
                } else {
                    log_message('error', 'edit_info_user: ' . $url, 'FATAL ERROR');
                    $tail = $this->input->post('source_url') ? ('&source_url=' . $this->input->post('source_url')) : '';
                    $this->page_result(lang('login_error'), 'login?appId=' . $clientId . $tail);
                }
            } else {
                $clientId = htmlEntities($this->input->post('clientId'));
                echo '<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">';
                echo "<script>alert('Tài khoản của bạn đã đăng xuất. Vui lòng đăng nhập lại');</script>";
                $return_url = $this->input->post('source_url');
                if ($return_url) {
                    echo "<script>location.href='" . base_url() . 'login?appId=' . $clientId . "&source_url=" . $return_url . "'</script>";
                } else {
                    echo "<script>location.href='" . base_url() . 'login?appId=' . $clientId . "'</script>";
                }
                die;
            }

        } else {
            $clientId = $this->input->post('clientId') ? htmlEntities($this->input->post('clientId')) : (isset($this->session_memcached->userdata ['clientID']) ? $this->session_memcached->userdata ['clientID'] : CLIENT_ID_OPENID);
            $data = array(
                'clientId' => $clientId,
                'source_url' => $this->input->post('source_url')
            );
            $this->load->helper('form');
            $this->view['content'] = $this->load->view('userinfo/info', $data, true);
            $this->load->view('Layout/layout_info', array(
                'data' => $this->view,
                'info' => true,
                'clientId' => $clientId,
                'source_url' => $this->input->post('source_url')
            ));

        }
		
		/*
		if(MEGAID_WARNNING_IP == '1')
		{
			$this->load->library('checkip');
			$this->checkip->check_ip('Login || edit_info_user');
		}
		*/
    }

    private function standardlize_number($number)
    {
        if (strlen($number) == 1)
            return '0' . $number;
        elseif (strlen($number) == 2)
            return $number;
        else
            return '00';
    }

    /**
     * Cau truc url gui sang phai theo dinh dang
     * http://localhost:7680/Register/edit_info?clientID=CLIENT_ID&source_url=REDIRECT_URL
     *  + Ðúng: vao trang chỉnh sửa
     *  + Sai: vào trang lỗi
     */
    public function edit_info()
    {
        log_message('error', 'Memcached: ' . print_r($this->cache->memcached->get($this->session_memcached->userdata('session_id')), true));
        $this->session_memcached->get_userdata();
		
		// [2016-08-13] phongwm add: check sessin id da login tren memcache
		/*
		if(isset($this->session_memcached->userdata['info_user']['userID']))
		{
			$lis_sess_id_login = $this->cache->memcached->get('USER_SESS_ID'.$this->session_memcached->userdata['info_user']['userID']);
			$arr_sess_id_login = explode(",", $lis_sess_id_login);
			if(!in_array($this->session_memcached->userdata['session_id'], $arr_sess_id_login))
			{
				log_message('error', 'REMOVE DATA SESSION');
				$this->cache->memcached->save('USER_SESS_ID'.$this->session_memcached->userdata['info_user']['userID'], '', MEMCACHE_TTL);
				$this->remove_data();
			}
		}
		*/
		// [2016-08-13] phongwm add
			
		
        log_message('error', 'SAU KHI REDIRECT: ' . print_r($this->session_memcached->userdata, true));
        if (!isset($this->session_memcached->userdata['info_user']['userID']) || empty($this->session_memcached->userdata['info_user']['userID'])) {
            $curr_url = $_SERVER['QUERY_STRING'];
            parse_str($curr_url, $query_array);
						
            if (empty($query_array['clientID']) || $this->invalid_clientID(htmlEntities($query_array['clientID']))) {
                //ve trang 404
                //$redis->set('clientID', $query_array['clientID']);
                $this->page_result(lang('non_existed_url'));
            } else {
				$clientId = (isset($this->session_memcached->userdata ['clientID']) ? $this->session_memcached->userdata ['clientID'] : CLIENT_ID_OPENID);
                $this->page_result(lang('not_permissions'), 'login?appId=' . htmlEntities($query_array['clientID']).'&redirect_to_url='.urlencode(base_url().'login/edit_info?'.$curr_url));
            }

        } else {
            $redis = new CI_Redis();
            $curr_url = $_SERVER['QUERY_STRING'];
            if ($curr_url != '') {
                parse_str($curr_url, $query_array);
				
                if (empty($query_array['clientID']) || $this->invalid_clientID(htmlEntities($query_array['clientID']))) {
                    //ve trang 404
                    //$redis->set('clientID', $query_array['clientID']);
                    $this->page_result(lang('non_existed_url'));
                } else {
                    $this->session_memcached->set_userdata('clientID', htmlEntities($query_array['clientID']));
                    log_message('error', 'THEM CLIENTID: ' . print_r($this->session_memcached->userdata, true));
                    if (isset($query_array['source_url']) && !empty($query_array['source_url'])) {
                        $redis->set(htmlEntities($query_array['clientID']) . '_url_info_return', htmlEntities($query_array['source_url'])); //duong link url se tra ve cho clientid
                    }
                    $source_url = isset($query_array['source_url']) ? htmlEntities($query_array['source_url']) : '';
                    $data = array(
                        'clientId' => htmlEntities($query_array['clientID']),
                        'source_url' => $source_url,
						'client_info' => $this->get_client_info(htmlEntities($query_array['clientID']), CLIENT_ID_OPENID)
                    );
                    $this->load->helper('form');
                    $this->load->library('form_validation');
                    $this->view['content'] = $this->load->view('userinfo/info', $data, true);
                    $this->load->view('Layout/layout_info', array(
                        'data' => $this->view,
                        'info' => true,
                        'clientId' => htmlEntities($query_array['clientID']),
                        'source_url' => $source_url
                    ));
                }
            } else {
                $this->page_result(lang('non_existed_url'), 'login');
            }
//                    $redis->set('access_token', $accessToken);

        }
		
		/*
		if(MEGAID_WARNNING_IP == '1')
		{
			$this->load->library('checkip');
			$this->checkip->check_ip('Login || vao form edit_info');
		}
		*/
    }

    /**
     * Kiem tra xem ClientID co vi pham khong
     * Neu vi pham tra ve TRUE, khong vi pham tra ve FALSE
     * @author tienhm
     * @param $clientID
     * @return bool
     */
    public function invalid_clientID($clientID)
    {
        $redis = new CI_Redis();
        $client_list = json_decode($redis->get('clientList'), true);
        if (isset($client_list) && is_array($client_list) && count($client_list)) {
            if (in_array($clientID, $client_list))
                return false;
            else return true;
        } else {
            return false;
        }
    }

    /**
     * Giai ma
     * @author: hatt
     * @created : 9/9/2014
     * @param $text
     */
    private function decode_access_token($text)
    {
//        $this->load->library('epurse_connector');

        $key = base64_decode(KEY_DECODE);
        $str = hex2bin($text);
		//log_message('error', 'str sau hex2bin: '.print_r($str, true));
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

    private function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    private function hex2bin($hex_string)
    {
        $pos = 0;
        $result = '';
        while ($pos < strlen($hex_string)) {
			$hex2bin = HEX2BIN_WS;
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
     * login bang email
     * @param $clientId
     * @param $email
     * @param $loginfrom
     * @param $mac_address
     * @param $non_epay_account_id
     * @param $checkurl
     * @return bool
     */
    public function login_through_email($clientId, $email, $loginfrom, $mac_address, $publisher_id, $non_epay_account_id, $checkurl, $source_url)
    {
        
		$info = $this->authen_interface->login_through_email($clientId, $email, $loginfrom, $mac_address, $publisher_id, $non_epay_account_id);
		
        if (!empty($info)) {
            log_message('error', 'thong tin response ' . $info);
            $info_response = json_decode($info, true);
            if ($info_response['status'] == '00') { //login bang email thanh cong
                if ($info_response['comfirm'] == '0') {
                    $this->page_comfirm($source_url);
                } else {
                    $authenCode = $info_response['data'];
                    $this->input->set_cookie($clientId . '_authen', $authenCode, TIMELIFE_REDIS);
                    $checkurl .= '/LoginOpenID/LoginID/ResultLogin';
                    $checkurl .= '?authenCode=' . $authenCode;
                    if (isset($source_url) && $source_url != '')
                        $checkurl .= '&source_url=' . $source_url;
                    header("Location:" . $checkurl);
                    die;
                }
            } else {
                $mess = lang('login_error');
                $this->page_result($mess, 'login?appId=' . $clientId);

            }
        } else
            $this->page_result(lang('login_error'), 'login?appId=' . $clientId);

    }

    /**
     * trang thong tin loi tra ve
     * @param $mess
     */
    public function page_result($messa, $redirect_link = null)
    {
        $mess = array();
        $mess['mess'] = $messa;
        if ($redirect_link != null)
            $mess['redirect_link'] = $redirect_link;
        $this->view['content'] = $this->load->view('result', $mess, true);
        $this->load->view('Layout/layout', array('data' => $this->view));
    }

    public function page_error()
    {
        log_message('error', ' PAGE ERROR');
        if ($this->input->get('message')) {
            if (stripos($this->input->get('message'), 'sms:') === false)
                $this->page_result($this->input->get('message'), $this->input->get('redirect_url'));
            else {
                $clientId = substr($this->input->get('redirect_url'), -6);
                redirect('login?appId=' . $clientId, 'location');
            }
        }
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

	public function get_client_even($client_id)
	{
		$redis_event = new CI_Redis();		
		$redis_event->select(REDIS_SELECT_INDEX);		
		$data_event_key = "EVT_KEY_PREFIX_".$client_id;	
		$data_event = $redis_event->get($data_event_key);		
		if(is_null($data_event))		
			return ;		
		$data = get_object_vars(json_decode($data_event));		
		return $data;	
	}
	


	private function wrong_pass()
	{
		$this->session_memcached->get_userdata();
		$ip_client = $this->session_memcached->userdata('ip_address');
		
		$redis = new CI_Redis;
		$numb_wrong = $redis->get('WRONG_PASS_' . $ip_client . date('Ymd'));
		
		$numb_wrong += 1;
		$redis->set('WRONG_PASS_' . $ip_client . date('Ymd'), $numb_wrong);
		
	}
	
	private function delete_wrong_pass()
	{
		$this->session_memcached->get_userdata();
		$ip_client = $this->session_memcached->userdata('ip_address');
		
		$redis = new CI_Redis;
		$redis->del('WRONG_PASS_' . $ip_client . date('Ymd'));
		
	}
	
	private function check_wrong_pass()
	{
		$this->session_memcached->get_userdata();
		$ip_client = $this->session_memcached->userdata('ip_address');
		
		$redis = new CI_Redis;
		$numb_wrong_pass = $redis->get('WRONG_PASS_' . $ip_client . date('Ymd'));
		if(!empty($numb_wrong_pass) && $numb_wrong_pass >= NUM_OF_WRONG_PASS )
		{
			return false;
		}
		else
		{
			return true;
		}
	}

}
?>