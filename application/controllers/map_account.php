<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class map_account extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->driver('cache');
        $this->load->helper('language');
        $this->load->library('session_memcached');
        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->library('redis');
        $this->load->library('id_curl');
		$this->load->library('id_encrypt');
        $this->load->library('authen_interface');
        $this->load->helper('form');
		$this->lang->load('megav_message');
		$this->load->library('megav_core_interface');
		$this->load->library('megav_libs');
		$this->load->library('session');

		//$this->megav_libs->saveSourceUrl("123");
		
		$this->session_memcached->get_userdata();
		if(!isset($this->session_memcached->userdata['info_user']['userID']))
		{
			//redirect('login');
			echo "<script>window.top.location='" . base_url() . "'</script>";
			die;
		}
		delete_cookie("addBank");
    }

    
	public function index()
	{
		$data = array();
		$accessToken = $this->megav_libs->genarateAccessToken();
		$listBank = $this->megav_core_interface->getListBanksAcountMapping($this->session_memcached->userdata['info_user']['userID'], $accessToken);


		
		if($listBank)
			$data['listBankAcc'] = $listBank;
		$this->load->view('map_account/index', $data);
		/*
		$this->view['content'] = $this->load->view('bank_account/index', $data, true);
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
		));
		*/
	}
	
	public function mapBankAccount()
	{
		$data = array();
		$transId = "GLP" . date("Ymd") . rand();
		$listBank = $this->megav_core_interface->getProvider($transId, SUB_DEPOSIT_BANK_MAPPING);
		if($listBank)
			$data['listBank'] = $listBank;

		$accessToken = $this->megav_libs->genarateAccessToken();
		$listBankUesr = $this->megav_core_interface->getListBanksAcountMapping($this->session_memcached->userdata['info_user']['userID'], $accessToken);
		$listUserBankCode = array();
		foreach($listBankUesr as $bankUser)
		{
			if(isset($bankUser->bankcode))
				array_push($listUserBankCode, $bankUser->bankcode);
		}
		$data['listUserBankCode'] = $listUserBankCode;
		
		$post = $this->input->post();
		if($post)
		{
			$this->form_validation->set_rules('bank_code', 'Ngân hàng', 'required|trim|xss_clean');
			if($this->form_validation->run() == true) 
			{
				$transId = "IBA" . date("Ymd") . rand();
				$accessToken = $this->megav_libs->genarateAccessToken();
				$requestInitMappingBank = $this->megav_core_interface->initMappingBank($this->session_memcached->userdata['info_user']['userID'],$post['bank_code'], $accessToken);
				if($requestInitMappingBank)
				{
					$response = json_decode($requestInitMappingBank);
					log_message('error', 'respone: ' . print_r($requestInitMappingBank, true));
					
					//$this->session->set_userdata('bank_code',$post['bank_code']);
					$redis = new CI_Redis();
					$dataSaveInitBank = array('bankCode' => $post['bank_code']);
					$redis->set('InitBankMapping' . $this->session_memcached->userdata['info_user']['userID'], json_encode($dataSaveInitBank));
					
					$info_bank = array();
					foreach ($listBank as $key => $value) {
						if (isset($value->epurseBankCode) && $value->epurseBankCode==$post['bank_code']) {
							$info_bank =$value;
						}
					}
					if(isset($response->status))
					{
						if($response->status == '00')
						{
							
							$key3des = $this->megav_core_interface->getSessionKey();
							if(!$key3des)
								return false;
							$listBanks = json_decode($this->megav_core_interface->decrypt3DES($response->data, $key3des));
							if ($listBanks->status=='00') {
								if (isset($listBanks->redirect_url)) {
									echo "<script>window.top.location='" . $listBanks->redirect_url . "'</script>";
									die;
								}
								$data['mess'] = "Đã gửi yêu cầu liên kết sang ".$info_bank->providerCode." thành công với thông tin liên kết.";
					
					            $this->load->view('map_account/finish', $data);
							}

						}else{
							$error = 1;
							$this->megav_libs->page_result(lang('MVM_'.$response->status), '/map_account');
						}
					}else{
						$error = 1;
						$this->megav_libs->page_result("Có lỗi trong quá trình liên kết tài khoản với ngân hàng. Vui lòng thử lại.", '/map_account');
					}
				}
				else
				{
					$error = 1;
					$this->megav_libs->page_result("Hệ thống MegaV đang bận. Vui lòng thử lại sau.", '/banks_account');
				}
			}
		}
		
		if(!isset($error))
		{
			
			$this->load->view('map_account/create', $data);
		}
	}
	public function responseMappingAcount(){ // link giả lập BIDV trả về /map_account/responseMappingAcount?transId=000091_MGV17082311665800004&status=00
		log_message('error', 'responseMappingAcount URL QUERY_STRING: ' . print_r($_SERVER['QUERY_STRING'], true));
		$url = base_url() . 'map_account/bank_notify?' . $_SERVER['QUERY_STRING'];
		log_message('error', 'URL QUERY_STRING: ' . print_r($url, true));
		$dataMenu = array();
		$dataMenu['userinfo'] = array('userName' => $this->session_memcached->userdata['info_user']['userID'],
										'mobileNo' => $this->session_memcached->userdata['info_user']['mobileNo'],
										'balance' => $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																											$this->megav_libs->genarateAccessToken()));
		
		$redis = new CI_Redis();
		$dataMenu['userinfo']['balance_behold'] = $redis->get('BALANCE_BEHOLD' . $dataMenu['userinfo']['userName']);
		
		$this->load->view('Layout/layout_info', array(
			'nav_left' => $this->load->view('Layout/layout_menu_left', $dataMenu, true),
			'user_info' => $this->session_memcached->userdata['info_user'],
			'contentIframe' => $url
		));
		
    }
	
	public function mappingFail()
	{
		
		//($messa, $redirect_link = null, $timeRedirect = null, $redirectIframelink = null, 
		//						$success = null, $buttonNameRediect = null, $cancelUrl = null, $buttonNameCancel = null, 
		//						$balance=null, $background = null)
		$this->megav_libs->page_result("Liên kết ngân hàng thất bại", '/banks_account', null, null, null, null, null, null, null, 1);
	}
	
    public function bank_notify()
	{
		//$url = $_SERVER['QUERY_STRING'];
		//log_message('error', 'payment_bank_notify URL QUERY_STRING: ' . print_r($url, true));
		$trans_id = "GLP" . date("Ymd") . rand();
	    $listBank = $this->megav_core_interface->getProvider($trans_id);

		
		$get = $this->input->get();
		if($get)
		{
			$status = $this->security->xss_clean($this->input->get('status'));
			$transId = $this->security->xss_clean($this->input->get('transid'));
			$bank_acc = $this->security->xss_clean($this->input->get('bank_acc'));
			$bank_acc_name = $this->security->xss_clean($this->input->get('bank_acc_name'));
			$accessToken = $this->megav_libs->genarateAccessToken();
			
			$redis = new CI_Redis();
			$dataSaveInitBank = json_decode($redis->get('InitBankMapping' . $this->session_memcached->userdata['info_user']['userID']), true);
			log_message('error', 'data redis init mapping: ' . print_r($dataSaveInitBank, true));
			if ($dataSaveInitBank != null && isset($dataSaveInitBank['bankCode'])) {
				
				$bank_code = $dataSaveInitBank['bankCode'];
				//$bank_code = $this->session->userdata('bank_code');
				//$this->session->unset_userdata('bank_code');
				
				$confirmRequest = $this->megav_core_interface->confirmMappingBank($this->session_memcached->userdata['info_user']['userID'],$bank_code, $transId,$status, 
																				$bank_acc, $bank_acc_name, $accessToken);
				$mappingInfo = json_decode($confirmRequest);
				$data['finish'] = 1;
				if(isset($mappingInfo->status)){
					$dataMapping = '';
					$key3des = $this->megav_core_interface->getSessionKey();
					$dataMapping = json_decode($this->megav_core_interface->decrypt3DES($mappingInfo->data, $key3des), true);
					$info_bank = array();
					foreach ($listBank as $key => $value) {
						if (isset($value->epurseBankCode) && $value->epurseBankCode==$dataMapping['userBanksMapping'][0]['bankcode']) {
							$info_bank =$value;
						}
					}
					$data['data_result'] = array();
					if($dataMapping['status'] == STATUS_SUCCESS || $dataMapping['status'] == '99'){
						$data['data_result']['bank_name'] = $info_bank->providerCode;
						$data['data_result']['trans_id'] = $dataMapping['trans_id'];
						$data['data_result']['bank_acc'] = $bank_acc;
						$data['data_result']['acc_vi'] = $this->session_memcached->userdata['info_user']['userID'];
						
						switch ($dataMapping['userBanksMapping'][0]['status']) {
							case '00':
								$data['data_result']['status'] = 'Thành công.';
								break;
							case '99':
								$data['data_result']['status'] = 'Đang chờ.';
								break;
							
							default:
								$data['data_result']['status'] = 'Thất bại.';
								break;
						}
						
						delete_cookie("addBank");
						$arrUserinfo = $this->session_memcached->userdata['info_user'];
						$arrUserinfo['countUserbankAcc'] += 1;
						$this->session_memcached->set_userdata('info_user', $arrUserinfo);
					}
					$data['data_result']['createdDate'] = $dataMapping['userBanksMapping'][0]['createDate'];
					
					log_message('error', 'mappingInfo Info: ' . print_r($dataMapping, true));
					if($status == STATUS_SUCCESS){
						$data['mess'] = "Đã gửi yêu cầu liên kết sang BIDV thành công với thông tin liên kết.";
					} else {
						$data['mess'] = "Liên kết thất bại";
						$data['data_result']['status'] = 'Thất bại.';
					}
						
					
					$this->view['content'] = $this->load->view('map_account/finish', $data, true);
					$this->load->view('Layout/layout_iframe', array(
						'data' => $this->view
					));
				}else{
					
					$data['mess'] = "Liên kết thất bại.";
					$this->view['content'] = $this->load->view('map_account/finish', $data, true);
					$this->load->view('Layout/layout_iframe', array(
						'data' => $this->view
					));
				}
			}else{
				log_message('error', 'Khong co data innit maping trong redis ' . print_r($dataSaveInitBank, true));
				$data['mess'] = "Xảy ra lỗi. Vui lòng thử lại.";
				$this->view['content'] = $this->load->view('map_account/finish', $data, true);
				$this->load->view('Layout/layout_iframe', array(
					'data' => $this->view
				));
			}
		}
		else
		{
			// hiển thị chuyển sang trang giao dich
			log_message('error', 'Khong co get');
		}
	}
	
	public function unMapBankAccount($bank_code){
		if ($bank_code) {
			$accessToken = $this->megav_libs->genarateAccessToken();
			$requestUnMappingBank = $this->megav_core_interface->unMappingBank($this->session_memcached->userdata['info_user']['userID'],$bank_code, $accessToken);
			if($requestUnMappingBank){
				$response = json_decode($requestUnMappingBank);

				$key3des = $this->megav_core_interface->getSessionKey();
				$dataUnMap = json_decode($this->megav_core_interface->decrypt3DES($response->data, $key3des), true);
				$error = 1;
				$trans_id = "GLP" . date("Ymd") . rand();
	    		$listBank = $this->megav_core_interface->getProvider($trans_id);
	    		$info_bank = array();
				foreach ($listBank as $key => $value) {
					if (isset($value->epurseBankCode) && $value->epurseBankCode==$bank_code) {
						$info_bank =$value;
					}
				}
				if ($response->status==STATUS_SUCCESS) {
					//$this->megav_libs->page_result(lang('Hủy liên kết thành công.'), '/map_account');

					/*$data['data_result'] = array(
						'bank_name' => $info_bank->providerCode,
						'trans_id' => $dataMapping['trans_id'],
						'acc_vi' => $this->session_memcached->userdata['info_user']['userID'],
						'status' => lang('MVM_'.$dataMapping['userBanksMapping'][0]['status'])
					);*/					
					$data['mess'] = "Đã gửi yêu cầu hủy liên kết tài khoản sang ".$info_bank->providerCode." thành công.";
					$this->load->view('map_account/finish', $data);
				}else{
					$data['mess'] = "Đã xảy ra lỗi.Vui lòng thử lại sau.";
					$this->load->view('map_account/finish', $data);
				}
				
				
			}
		}
		
		


		if(!isset($error)){
			
			$this->load->view('map_account', $data);

		}
	}

	
}
?>