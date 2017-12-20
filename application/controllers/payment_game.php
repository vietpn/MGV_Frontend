<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class payment_game extends CI_Controller
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
		log_message('error', 'payment game index: ');
		$data = array();
		$transId = $this->megav_libs->genarateTransactionId('GLT');
		//$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'], null, 1);
		//$data['listAmount'] = $this->megav_core_interface->getAmountCDV();
		//$listTelco = $this->megav_core_interface->getProvider($transId);
		$listTelco = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, TOPUP_GAME_TEMP);
		if($listTelco)
			$data['listTelco'] = $listTelco;
		$post = $this->input->post();
		if($post)
		{
			log_message('error', 'payment game index data post: ' . print_r($post, true));
			$this->form_validation->set_rules('acc_game', 'Tài khoản game', 'trim|required|max_length[50]|xss_clean');
			$this->form_validation->set_rules('provider_code', 'Nhà cung cấp mã thẻ', 'trim|required|xss_clean');
			$this->form_validation->set_rules('amount', 'Mệnh giá', 'trim|required|xss_clean');
			if($this->form_validation->run() == true)
			{
				$post['amount'] = str_replace(',', '', $post['amount']);
				$data['totalAmount'] = $this->getDiscountAmount($post['amount'], $post['provider_code']);
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
							$this->megav_libs->page_result($mess, 'payment_game');
						}
						
					}
					
					//$transId = $this->megav_libs->genarateTransactionId('BLC');
					$dataTopupRedis = array('transId' => $transId, 'post' => $post);
					$this->megav_libs->saveCookieUserData($dataTopupRedis['transId'], $dataTopupRedis);
					$loadView = 1;
					$this->view['content'] = $this->load->view('payment_game/payment_topup', $data, true);
					$this->load->view('Layout/layout_iframe', array(
						'data' => $this->view
					));
				}
				else
				{
					$data['err_amount'] = "Số dư không đủ";
					$data['option'] = $this->getAmountWithProvider2($post['provider_code'], $post['amount']);
				}
			}
			else
			{
				log_message('error', 'payment game index form validation false: ' . print_r($post, true));
				$data['totalAmount'] = $this->getDiscountAmount($this->session_memcached->userdata['info_user']['userID'], $post['amount'], $post['provider_code']);
				$data['option'] = $this->getAmountWithProvider2($post['provider_code'], $post['amount']);
			}
		}
		
		if(!isset($loadView)){
			log_message('error', 'payment game load view');
			$this->view['content'] = $this->load->view('payment_game/index', $data, true);
			$this->load->view('Layout/layout_iframe', array(
				'data' => $this->view
			));
		}
	}
	
	public function payment_topup()
	{
		$post = $this->input->post();
		$data = array();
		//$listTelco = $this->megav_core_interface->getProvider($transId);
		$listTelco = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, TOPUP_GAME_TEMP);
		if($listTelco)
			$data['listTelco'] = $listTelco;
		$dataPaymentTopupRedis = $this->megav_libs->getDataTransRedis();
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
				
				$topup_megaV = $this->megav_core_interface->paymentTopupToAccGame($this->session_memcached->userdata['info_user']['userID'],
																		$this->session_memcached->userdata['info_user']['mobileNo'],
																		$this->session_memcached->userdata['info_user']['email'],
																		$dataPaymentTopupRedis['post']['amount'], 
																		$otp, $passLv2, $dataPaymentTopupRedis['post']['acc_game'], 
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
						
						
						$data['mess'] = "Giao dịch nạp game thành công. ";
						if(isset($dataTopup['trans_id']))
							$data['mess'] .= "Mã giao dịch tham chiếu " . $dataTopup['trans_id'];
						
						$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																								$this->megav_libs->genarateAccessToken());
						$data['detail'] = $dataTopup;
						$this->view['content'] = $this->load->view('payment_game/payment_success', $data, true);
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
						$this->megav_libs->page_result(lang('MVM_'.$topup_megaV->status), '/payment_game');
					}
				}
				else
				{
					$loadView = 1;
					$this->megav_libs->page_result("Có lỗi trong quá trình nạp tiền game. Vui lòng thử lại.", '/payment_game');
					
				}
			}
			else
			{
				$loadView = 1;
				$this->view['content'] = $this->load->view('payment_game/payment_topup', $data, true);
				$this->load->view('Layout/layout_iframe', array(
					'data' => $this->view
				));
			}
		}
		
		if(!isset($loadView))
		{
			$this->view['content'] = $this->load->view('payment_game/payment_topup', $data, true);
			$this->load->view('Layout/layout_iframe', array(
				'data' => $this->view
			));
		}
	}

	
	public function get_provider_code()
	{
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		$post = $this->input->post();
		if($post && isset($post['phone']))
		{

			$phone = $this->security->xss_clean($post['phone']);
			
			$viettel_10 = array('098', '097', '096', '086');
			$viettel_11 = array('0162', '0163', '0164', '0165', '0166', '0167', '0168', '0169');
			
			$vina_10 = array('091', '094', '088');
			$vina_11 = array('0123', '0124', '0125', '0127', '0129');
			
			$mobi_10 = array('090', '093', '089');
			$mobi_11 = array('0120', '0121', '0122', '0126', '0128');
			
			switch(strlen($phone))
			{
				case 10:
					if(in_array(substr($phone, 0, 3), $viettel_10))
					{
						echo 'VTT';
					}
					if(in_array(substr($phone, 0, 3), $vina_10))
					{
						echo 'VNP';
					}
					if(in_array(substr($phone, 0, 3), $mobi_10))
					{
						echo 'VMS';
					}
				break;
				
				case 11:
					if(in_array(substr($phone, 0, 4), $viettel_11))
					{
						echo 'VTT';
					}
					if(in_array(substr($phone, 0, 4), $vina_11))
					{
						echo 'VNP';
					}
					if(in_array(substr($phone, 0, 4), $mobi_11))
					{
						echo 'VMS';
					}
				break;
				
				default:
					echo 'false';
			}
		}
	}
	
	public function getAmountWithProvider()
	{
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		$post = $this->input->post();
		$post['providercode'] = $this->security->xss_clean($post['providercode']);
		$html = '';
		log_message('error', 'get getAmountWithProvider payment game: ' . print_r($post, true));
		$transId = $this->megav_libs->genarateTransactionId('GLA');
		$requestGetAmount = $this->megav_core_interface->getAmountCDV($this->session_memcached->userdata['info_user']['userID'], $transId, $post['providercode'], null, TOPUP_GAME_TEMP);
		//log_message('error', 'list amount SDV: ' . print_r($requestGetAmount, true));
		if($requestGetAmount)
		{
			usort($requestGetAmount, array($this, "cmp"));
			$html = '<option value="">Chọn mệnh giá</option>';
			foreach($requestGetAmount as $amount)
			{
				//if($amount->templateId == '12')
				//{
					$html .= '<option value="'.$amount->amount.'" '.set_select('amount', $amount->amount, False).'>'.number_format($amount->amount).'</option>';
				//}
			}
		}
		else
		{
			$html = '<option value="">Chưa có thông tin nhà cung cấp thẻ</option>';
		}
		
			echo $html;
	}
	
	public function cmp($a, $b)
	{
		return ($a->amount < $b->amount) ? -1 : 1;
	}
	
	public function getAmountWithProvider2($providercode, $amount)
	{
		$html = '';
		$transId = $this->megav_libs->genarateTransactionId('GLA');
		$requestGetAmount = $this->megav_core_interface->getAmountCDV($this->session_memcached->userdata['info_user']['userID'], $transId, $providercode, null, TOPUP_GAME_TEMP);
		if($requestGetAmount)
		{
			usort($requestGetAmount, array($this, "cmp"));
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
	
	public function getDiscountAmount($amount, $providerCode)
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
}
?>