<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class withdraw_mapping extends CI_Controller
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
		
		$this->session_memcached->get_userdata();
		if(!isset($this->session_memcached->userdata['info_user']['userID']))
		{
			//redirect();
			echo "<script>window.top.location='" . base_url() . "'</script>";
			die;
		}
    }

    /*
	public function index()
	{

		$data = array();
		$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'], null, 1);
		$this->view['content'] = $this->load->view('withdraw_mapping/withdraw_epurse_1', $data, true);
		$this->load->view('Layout/layout_iframe', array(
			'data' => $this->view
		));
	}
	*/
	/*
	public function withdraw_epurse_1()
	{

		$data = array();
		$this->load->view('withdraw/withdraw_epurse_1', $data);
	}
	*/
	
	public function withdraw_epurse()
	{

		$post = $this->input->post();
        $accInfo = $this->session_memcached->userdata['info_user'];

		$data = array();
		$data['step'] = 2;
		$transId = $this->megav_libs->genarateTransactionId('GBL');
		$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'], null, 1);
		
		$listBankACC = $this->megav_core_interface->getListBankAccount($this->session_memcached->userdata['info_user']['email'],
																		$this->session_memcached->userdata['info_user']['mobileNo'],
																		$this->session_memcached->userdata['info_user']['userID'], $transId);
		$listBank = $this->megav_core_interface->getProvider($transId);
		$bankProvider = array();
		foreach($listBankACC as &$bankAcc){
			
			foreach($listBank as $bank){
				//log_message('error', 'Withdraw || bank acc ' . print_r($bankAcc, true));
				if(isset($bankAcc->bankCode) && $bankAcc->bankCode == $bank->providerCode){
					$bankAcc->recId = $bank->recId;
					//log_message('error', 'Mapp Bank: ' . print_r($bankAcc, true));
					$bankProvider[] = $bankAcc;
				}
			}
		}
		
		$uniqueBank = array();
		foreach ($bankProvider as $object) {
			if (isset($uniqueBank[$object->recId])) {
				continue;
			}
			$uniqueBank[$object->recId] = $object;
		}
		
		
		
		
		$accInfo = $this->session_memcached->userdata['info_user'];
		if($post)
		{
			$data['post_act'] = $this->input->post();
			$this->form_validation->set_rules('widthdraw_met', 'Hình thức rút tiền', 'required|trim|xss_clean');
			$this->form_validation->set_rules('amount', 'Số tiền rút', 'required|trim|xss_clean|max_length[13]|callback_check_amount');
			//$this->form_validation->set_rules('bankAcc', 'Tài khoản ngân hàng', 'required|trim|xss_clean');
			$this->form_validation->set_rules('provider_code', 'Ngân hàng', 'required|trim|xss_clean');
			if($this->form_validation->run() == true)
			{
				$post['amount'] = str_replace(',', '', $post['amount']);
				$data['fee'] = $this->getFee($post['amount']);
				if($data['balance'] >= $post['amount'] + $data['fee'])
				{
					if(WITHDRAW_MINIMUM_AMOUNT < $post['amount'] && $post['amount'] < WITHDRAW_MAXIMUM_AMOUNT)
					{
						$transId = $this->megav_libs->genarateTransactionId('WDA');
						$dataWithdrawRedis = array('transId' => $transId);
						if(isset($post['widthdraw_met']) && $post['widthdraw_met'] == '3')
						{
							
							// luu session data post va phí
							
							$data['post'] = $post;
							
							$dataWithdrawRedis['post_1'] = $data['post'];
							$dataWithdrawRedis['fee'] = $data['fee'];
							
							// load view B2: data = datapost buoc 1
							$data['listBankP2'] = $listBank;
							if($accInfo['security_method'] == '1')
							{
								$requestSendOtp = $this->megav_core_interface->genOTP($accInfo['email'], $accInfo['mobileNo'], $accInfo['userID'], $dataWithdrawRedis['transId']);
								$requestSendOtp = json_decode($requestSendOtp);
								if($requestSendOtp->status == STATUS_SUCCESS)
								{
									$loadView = 1;
									$data['sentOtp'] = 1;
									$data['step'] = 3;
                                    $data['userInfo'] = $accInfo;
									$this->view['content'] = $this->load->view('withdraw_mapping/withdraw_epurse_2', $data, true);
									$this->load->view('Layout/layout_iframe', array(
										'data' => $this->view
									));
								}
								else
								{
									$loadView = 1;
									$mess = lang('MVM_'.$requestSendOtp->status);
									$this->megav_libs->page_result($mess, 'withdraw_mapping');
								}
								
							}
							else
							{
								$loadView = 1;
								$data['step'] = 3;
                                $data['userInfo'] = $accInfo;
								$this->view['content'] = $this->load->view('withdraw_mapping/withdraw_epurse_2', $data, true);
								$this->load->view('Layout/layout_iframe', array(
									'data' => $this->view
								));
							}
							
						}
						
						if(isset($post['widthdraw_met']) && $post['widthdraw_met'] == '2')
						{
							
						}
						
						$this->megav_libs->saveCookieUserData($dataWithdrawRedis['transId'], $dataWithdrawRedis);
					}
					else
					{
						$data['err_amount'] = "Số tiền yêu cầu rút quy định tối thiểu là " . number_format(WITHDRAW_MINIMUM_AMOUNT) . " đ, tối đa là " . number_format(WITHDRAW_MAXIMUM_AMOUNT) . ' đ';
					}
				}
				else
				{
					//$loadView = 1;
					//$data['err_amount'] = "Số tiền rút không được lớn hơn số dư khả dụng";
					$data['err_amount'] = "Số dư không đủ";
				}
			}
			else
			{
				$errorForm = $this->form_validation->error_array();
				//log_message('error', 'validation: ' . print_r($arror, true));
				if(!isset($errorForm['amount']))
					$data['fee'] = $this->getFee($post['amount']);
			}
		}
		
		if(!isset($loadView))
		{
			$transId = $this->megav_libs->genarateTransactionId('GLB');
			
			log_message('error', 'Withdraw || list provider ' . print_r($listBank, true));
			
			/*
			if($listBank)
				$data['listBank'] = $listBank;
			*/
			
			log_message('error', 'withdraw_mapping || list bank acc ' . print_r($listBankACC, true));
			
			if($listBankACC)
				$data['listBankAcc'] = $listBankACC;
			
			
			$data['listBank'] = $uniqueBank;
			$data['post_act']['widthdraw_met'] = 3;
			
			$this->view['content'] = $this->load->view('withdraw_mapping/withdraw_epurse', $data, true);
			$this->load->view('Layout/layout_iframe', array(
				'data' => $this->view
			));
		}
	}
	
	public function withdraw_epurse_mapping()
	{
		$data = array();
		$data['step'] = 3;
		$post = $this->input->post();
		$dataWithdrawRedis = $this->megav_libs->getDataTransRedis();
		$data['post'] = $dataWithdrawRedis['post_1'];
		
		
		$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'], null, 1);
		
		if($post)
		{
			if($this->session_memcached->userdata['info_user']['security_method'] == '1')
			{
				$this->form_validation->set_rules('otp', 'OTP', 'required|trim|xss_clean|max_length[10]');
				$otp = $post['otp'];
				$passLv2 = "";
			}
			else
			{
				$this->form_validation->set_rules('passLv2', 'Mật khẩu cấp 2', 'required|trim|xss_clean|max_length[20]|min_length[6]');
				$passLv2 = $post['passLv2'];
				$otp = "";
			}
			
			if($this->form_validation->run() == true)
			{

				$accessToken = $this->megav_libs->genarateAccessToken();
				$widthdraw_megaV = $this->megav_core_interface->withdrawOffline($this->session_memcached->userdata['info_user']['userID'],
																				$this->session_memcached->userdata['info_user']['mobileNo'],
																				$this->session_memcached->userdata['info_user']['email'],
																				$dataWithdrawRedis['post_1']['provider_code'], 
																				$dataWithdrawRedis['post_1']['bankAcc'], 
																				$dataWithdrawRedis['post_1']['amount'], $otp, $passLv2, $accessToken, $dataWithdrawRedis['transId']);
				$widthdraw_megaV = json_decode($widthdraw_megaV);
				if(isset($widthdraw_megaV->status))
				{
					
					if($widthdraw_megaV->status == STATUS_SUCCESS)
					{
						
						$loadView = 1;
						$key3des = $this->megav_core_interface->getSessionKey();
						if(isset($widthdraw_megaV->data))
							$dataWithdraw = json_decode($this->megav_core_interface->decrypt3DES($widthdraw_megaV->data, $key3des), true);
						log_message('error', 'data withdraw: ' . print_r($dataWithdraw, true));
						
						$mess = "Khởi tạo yêu cầu rút tiền thành công. ";
						if(isset($dataWithdraw['trans_id']))
							$mess .= "Mã giao dịch tham chiếu " . $dataWithdraw['trans_id'] . ".";
						$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'], null, null);
						$data['mess'] = $mess;
						
						$transDetail = $this->megav_core_interface->getTransList($this->session_memcached->userdata['info_user']['userID'], 2, 
																				$dataWithdraw['trans_id'], "", "", "", "", 0, 0, 1, $this->megav_libs->genarateTransactionId('GLT'), "");
						$data['detail'] = $transDetail->listTrans[0];
						$this->view['content'] = $this->load->view('withdraw_mapping/withdraw_epurse_mapping', $data, true);
						$this->load->view('Layout/layout_iframe', array(
							'data' => $this->view
						));
						
						//$this->megav_libs->page_result($mess, '/withdraw', null, null, 1, null, null, null, $balance);
					}
					elseif($widthdraw_megaV->status == STATUS_WRONG_OTP)
					{
						$data['error_otp'] = 'Sai OTP';
						$data['sentOtp'] = 1;
						
					}
					elseif($widthdraw_megaV->status == STATUS_WRONG_PASSLV2)
					{
						$data['error_passLv2'] = 'Sai mật khẩu cấp 2';
					}
					elseif($widthdraw_megaV->status == STATUS_WRONG_AMOUNT)
					{
						$key3des = $this->megav_core_interface->getSessionKey();
						if(isset($widthdraw_megaV->data))
							$dataWithdraw = json_decode($this->megav_core_interface->decrypt3DES($widthdraw_megaV->data, $key3des), true);
						
						$loadView = 1;
						$mess = "Số tiền rút tối thiểu là " . number_format($dataWithdraw['minTransAmount']) . ' đ, tối đa là ' . number_format($dataWithdraw['maxTransAmount']) . ' đ';
						$this->megav_libs->page_result($mess, '/withdraw_mapping');
					}
					else
					{
						$loadView = 1;
						$this->megav_libs->page_result(lang('MVM_'.$widthdraw_megaV->status), '/withdraw_mapping');
					}
				}
				else
				{
					$loadView = 1;
					$this->megav_libs->page_result("Có lỗi trong quá trình rút tiền. Vui lòng thử lại.", '/withdraw_mapping');
					
				}
			}
			else
			{
				$data['sentOtp'] = 1;
			}
		}
		
		
		if(!isset($loadView))
		{
			$listBank = $this->megav_core_interface->getProvider($transId);
			$data['listBankP2'] = $listBank;
			$this->view['content'] = $this->load->view('withdraw_mapping/withdraw_epurse_2', $data, true);
			$this->load->view('Layout/layout_iframe', array(
				'data' => $this->view
			));
		}
	}
	
	
	public function get_fee_widthdraw()
	{		
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		$post = $this->input->post();
		//log_message('error', 'data post get fee withdrawmapping: ' . print_r($post, true));
		if($post)
		{
			$listfee = $this->megav_core_interface->getTempFee($this->session_memcached->userdata['info_user']['userID']);
			foreach($listfee as $template)
			{
				if($template->templateType == WIDTHDRAW_MAPPING_TEMP)
				{
					$post['amount'] = str_replace(',', '', $post['amount']);
					$feeAmount = $template->fixFee + ($template->fixRate * $post['amount']/100);
					$feeAmountFormat = number_format($feeAmount);
					echo $feeAmountFormat;
				}
			}
		}
	}
	
	
	public function getFee($amount)
	{
		$listfee = $this->megav_core_interface->getTempFee($this->session_memcached->userdata['info_user']['userID']);
		foreach($listfee as $template)
		{
			if($template->templateType == WIDTHDRAW_MAPPING_TEMP)
			{
				$amount = str_replace(',', '', $amount);
				$feeAmount = $template->fixFee + $template->fixRate * $amount;
				return $feeAmount;
			}
		}
		return 0;
	}

	public function withdrawGetAjaxBankAcc(){
		if (isset($_POST['code']) && $_POST['code'] !='') {
			$transId = $this->megav_libs->genarateTransactionId('GLB');
			//$listBank = $this->megav_core_interface->getProvider($transId);

			$listBankAcc = $this->megav_core_interface->getListBankAccount($this->session_memcached->userdata['info_user']['email'],
																		$this->session_memcached->userdata['info_user']['mobileNo'],
																		$this->session_memcached->userdata['info_user']['userID'], $transId);
			log_message('error', 'withdrawGetAjaxBankAcc | list bak' . print_r($listBankAcc, true));
			$arr_acc_new = array();
			foreach ($listBankAcc as $key => $value) {
				log_message('error', 'withdrawGetAjaxBankAcc | bak' . print_r($value, true));
				if (isset($value->bankCode) && $value->bankCode == trim(addslashes($_POST['code']))) {
					$arr_acc_new[] = $listBankAcc[$key];
				}
			}
			$html = '<option value="">Chọn tài khoản</option>';
			if (!empty($arr_acc_new)) {
				foreach ($arr_acc_new as $bank) {
					$html .= '<option value="'.$bank->bankAccount.'" '.set_select('bankAcc', $bank->bankAccount, False).'>'.$bank->bankAccount.'</option>';
				}
			}
			$data = array(
				'status' => true,
				'html'		=> $html
			);
			echo json_encode($data);
		}
		

	}
	public function check_amount($amount){
		$amount = (int)trim(str_replace(',', '', $amount));
		$balance = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'], null, 1);
		if ($amount<=0) {
            $this->form_validation->set_message("check_amount","Số nhập vào phải lớn hơn 0!");
            return FALSE;
        }
        if($amount>$balance){
            $this->form_validation->set_message("check_amount","Số tiền rút phải nhỏ hơn hoặc bằng số dư khả dụng?");
            return FALSE;
        }else{
            return TRUE;
        }
    }
}
?>