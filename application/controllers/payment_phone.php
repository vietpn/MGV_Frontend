<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class payment_phone extends CI_Controller
{
    public function __construct()
    {
        parent::__construct(true);
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

    
	public function index()
	{
		$data = array();
		$transId = $this->megav_libs->genarateTransactionId('PMP');
		//$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'], null, 1);
		//$data['listAmount'] = $this->megav_core_interface->getAmountCDV();
		//$listTelco = $this->megav_core_interface->getProvider($transId);
		$listTelco = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, TOPUP_GAME_TEMP);
		
		$listTelcoInView = array();
		foreach($listTelco as $telco){
			$telcoInView = new stdclass();
			$telcoInView->providerCode = $telco->providerCode;
			$telcoInView->providerName = $telco->providerName;
			if(!in_array($telcoInView, $listTelcoInView))
				array_push($listTelcoInView, $telcoInView);
		}
		
		//log_message('error', 'data list telco in view:' . print_r($listTelcoInView, true));
		
		if($listTelco)
			$data['listTelco'] = $listTelcoInView;
		$post = $this->input->post();
		if($post)
		{
			$this->form_validation->set_rules('phone', 'Số điện thoại', 'trim|required|is_numeric|max_length[11]|min_length[10]|xss_clean');
			$this->form_validation->set_rules('phone_type', 'Loại thuê bao', 'trim|required|xss_clean');
			$this->form_validation->set_rules('provider_code', 'Nhà cung cấp mã thẻ', 'trim|required|xss_clean');
			$this->form_validation->set_rules('amount', 'Mệnh giá', 'trim|required|xss_clean');
			if($this->form_validation->run() == true)
			{
				$post['amount'] = str_replace(',', '', $post['amount']);
				$data['totalAmount'] = $this->getDiscountAmount2($post['amount'], $post['provider_code']);
				$data['post'] = $post;
				$balance = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																				$this->megav_libs->genarateAccessToken());
				if($balance >= $data['totalAmount'])
				{
					if($this->session_memcached->userdata['info_user']['security_method'] == '1')
					{
						$requestSendOtp = $this->megav_core_interface->genOTP($this->session_memcached->userdata['info_user']['email'], 
																			$this->session_memcached->userdata['info_user']['mobileNo'], 
																			$this->session_memcached->userdata['info_user']['userID'], 
																			$transId);
						$requestSendOtp = json_decode($requestSendOtp);
						if($requestSendOtp->status == STATUS_SUCCESS)
						{
							$loadView = 1;
							$data['sentOtp'] = 1;
							
						}
						else
						{
							$loadView = 1;
							$mess = lang('MVM_'.$response->status);
							$this->megav_libs->page_result($mess, 'payment_phone');
						}
						
					}
					
									
					
					
					
					//$transId = $this->megav_libs->genarateTransactionId('BLC');
					$dataTopupRedis = array('transId' => $transId, 'post' => $post);
					$this->megav_libs->saveCookieUserData($dataTopupRedis['transId'], $dataTopupRedis, $post['phone']);
					$loadView = 1;
					$this->view['content'] = $this->load->view('payment_phone/payment_topup', $data, true);
					$this->load->view('Layout/layout_iframe', array(
						'data' => $this->view
					));
				}
				else
				{
					$data['err_amount'] = "Số dư không đủ";
				}
			}
			else
			{
				$post['amount'] = str_replace(',', '', $post['amount']);
				$data['totalAmount'] = $this->getDiscountAmount2($post['amount'], $post['provider_code']);
			}
			$data['option'] = $this->getAmountWithProvider2($post['provider_code'], $post['amount']);
		}
		
		if(!isset($loadView)){
			$this->view['content'] = $this->load->view('payment_phone/index', $data, true);
			$this->load->view('Layout/layout_iframe', array(
				'data' => $this->view
			));
		}
	}
	
	public function payment_topup()
	{
		$post = $this->input->post();
		$data = array();
		$transId = $this->megav_libs->genarateTransactionId('GLP');
		//$listTelco = $this->megav_core_interface->getProvider($transId);
		$listTelco = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, TOPUP_GAME_TEMP);
		
		$listTelcoInView = array();
		foreach($listTelco as $telco){
			$telcoInView = new stdclass();
			$telcoInView->providerCode = $telco->providerCode;
			$telcoInView->providerName = $telco->providerName;
			if(!in_array($telcoInView, $listTelcoInView))
				array_push($listTelcoInView, $telcoInView);
		}
		
		if($listTelco)
			$data['listTelco'] = $listTelcoInView;
		$dataPaymentTopupRedis = $this->megav_libs->getDataTransRedis($post['prefix']);
		$data['post'] = $dataPaymentTopupRedis['post'];
				
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
				// payment topup
				
				$accessToken = $this->megav_libs->genarateAccessToken();
				
				$merchantId = null;
				if(!empty($this->input->cookie("merchantId")))
					$merchantId = $this->input->cookie("merchantId");
				
				$topup_megaV = $this->megav_core_interface->paymentTopupToPhone($this->session_memcached->userdata['info_user']['userID'],
																		$this->session_memcached->userdata['info_user']['mobileNo'],
																		$this->session_memcached->userdata['info_user']['email'],
																		$dataPaymentTopupRedis['post']['amount'], 
																		$otp, $passLv2, $dataPaymentTopupRedis['post']['phone'], 
																		$dataPaymentTopupRedis['post']['provider_code'], $accessToken,
																		$dataPaymentTopupRedis['transId'], $merchantId);
				$topup_megaV = json_decode($topup_megaV);
				if(isset($topup_megaV->status))
				{
					if($topup_megaV->status == STATUS_SUCCESS)
					{
						$loadView = 1;
						$key3des = $this->megav_core_interface->getSessionKey();
						if(isset($topup_megaV->data))
							$dataTopup = json_decode($this->megav_core_interface->decrypt3DES($topup_megaV->data, $key3des), true);
						log_message('error', 'data buy card: ' . print_r($dataTopup, true));
						
						
						$data['mess'] = "Giao dịch nạp điện thoại thành công. ";
						if(isset($dataTopup['trans_id']))
							$data['mess'] .= "Mã giao dịch tham chiếu " . $dataTopup['trans_id'];
						
						//load view suucess
						$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																								$this->megav_libs->genarateAccessToken());
						$data['detail'] = $dataTopup;
						$this->view['content'] = $this->load->view('payment_phone/payment_success', $data, true);
						$this->load->view('Layout/layout_iframe', array(
							'data' => $this->view
						));
						
					}
					elseif($topup_megaV->status == STATUS_WRONG_OTP)
					{
						$data['error_otp'] = 'Sai OTP';
						$data['sentOtp'] = 1;
						
					}
					elseif($topup_megaV->status == STATUS_WRONG_PASSLV2)
					{
						$data['error_passLv2'] = 'Sai mật khẩu cấp 2';
					}
					else
					{
						$loadView = 1;
						$this->megav_libs->page_result(lang('MVM_'.$topup_megaV->status), '/payment_phone');
					}
				}
				else
				{
					$loadView = 1;
					$this->megav_libs->page_result("Có lỗi trong quá trình nạp điện thoại. Vui lòng thử lại.", '/payment_phone');
					
				}
			}
			else
			{
				$loadView = 1;
				$this->view['content'] = $this->load->view('payment_phone/payment_topup', $data, true);
				$this->load->view('Layout/layout_iframe', array(
					'data' => $this->view
				));
			}
		}
		
		if(!isset($loadView))
		{
			$this->view['content'] = $this->load->view('payment_phone/payment_topup', $data, true);
			$this->load->view('Layout/layout_iframe', array(
				'data' => $this->view
			));
		}
	}
	
	public function getDiscountAmount()
	{
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		$post = $this->input->post();
		if($post && isset($post['amount']) && isset($post['providerCode']))
		{

			$post['amount'] 	= $this->security->xss_clean($post['amount']);
			$post['providerCode'] 	= $this->security->xss_clean($post['providerCode']);

			$post['amount'] = str_replace(',', '', $post['amount']);
			$transId = $this->megav_libs->genarateTransactionId('GLT');
			$discount = $this->megav_core_interface->getDiscountAmount($this->session_memcached->userdata['info_user']['userID'], $post['providerCode'], $post['amount'], TOPUP_GAME_TEMP, $transId);
			if($discount !== false)
			{
				$totalAmount = $post['amount'] - $post['amount'] * $discount / 100;
				echo number_format($totalAmount);
			}
		}
	}
	
	public function getDiscountAmount2($amount, $providerCode)
	{
		$totalAmount = 0;
		if(isset($amount) && isset($providerCode))
		{
			$amount = str_replace(',', '', $amount);
			$transId = $this->megav_libs->genarateTransactionId('GLT');
			$discount = $this->megav_core_interface->getDiscountAmount($this->session_memcached->userdata['info_user']['userID'], $providerCode, $amount, TOPUP_GAME_TEMP, $transId);
			if($discount !== false)
			{
				$totalAmount = $amount - $amount * $discount / 100;
				//$totalAmount = ($totalAmount);
			}
		}
		return $totalAmount;
	}
	
	public function getAmountWithProvider2($providercode, $amount)
	{
		$html = '';
		$transId = $this->megav_libs->genarateTransactionId('GLA');
		$requestGetAmount = $this->megav_core_interface->getAmountCDV($this->session_memcached->userdata['info_user']['userID'], $transId, $providercode, null, TOPUP_GAME_TEMP);
		usort($requestGetAmount, array($this, "cmp"));
		if($requestGetAmount)
		{
			$html = '<option value="">Chọn mệnh giá</option>';
			foreach($requestGetAmount as $amount)
			{
				//if($amount->templateId == '12')
				//{
					$html .= '<option value="' . $amount->amount . '" ' . set_select('amount', $amount->amount, $amount).'>'.number_format($amount->amount).'</option>';
				//}
			}
		}
		
		return $html;
	}
	
	public function cmp($a, $b)
	{
		return ($a->amount < $b->amount) ? -1 : 1;
	}
}
?>