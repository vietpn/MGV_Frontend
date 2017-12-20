<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class transfer extends CI_Controller
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
		$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																				$this->megav_libs->genarateAccessToken(), null, 1);
		
		$this->view['content'] = $this->load->view('transfer/transfer_1', $data, true);
		$this->load->view('Layout/layout_iframe', array(
			'data' => $this->view
		));
	}
	
    public function transfer_epurse()
    {
		
		$post = $this->input->post();
		$data = array();
		$accInfo = $this->session_memcached->userdata['info_user'];
		$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($accInfo['userID'], $this->megav_libs->genarateAccessToken(), null, 1);
		if($post)
		{
			if(isset($post['step1']))
			{
				$data['step'] = 1;
				$transId = $this->megav_libs->genarateTransactionId('TTA');
				$dataTransferRedis = array('transId' => $transId);
				
				if($post['trans_met'] == '1')
				{
					
						log_message('error', 'Chuyển tiền ví nội bộ ' . $accInfo['userID']);
						//$this->form_validation->set_rules('accName', 'Tên tài khoản ví nhận', 'required|trim|xss_clean|max_length[50]');
						$this->form_validation->set_rules('phone', 'Số điện thoại của tài khoản ví nhận', 'is_numeric|max_length[11]|min_length[10]|required|trim|xss_clean');
						$this->form_validation->set_rules('trans_met', 'Hình thức chuyển tiền', 'required|trim|xss_clean|max_length[50]');
						//$this->form_validation->set_rules('service_met', 'Dịch vụ', 'required|trim|xss_clean|max_length[50]');
						
						if($this->form_validation->run() == true)
						{
							//$post['accName'] = trim($post['accName']);
							//if($post['accName'] != $accInfo['userID'])
							//{
							$post['phone'] = trim($post['phone']);
							if($post['phone'] != $accInfo['mobileNo'])
							{
								//$userReciverInfo = $this->megav_core_interface->getUserInfoByName($post['accName'], $transId);
								$userReciverInfo = $this->megav_core_interface->getUserInfoByPhone($post['phone'], $this->megav_libs->genarateAccessToken(), $transId);
								
								$userReciverInfo = json_decode($userReciverInfo);
								if($userReciverInfo)
								{
									if($userReciverInfo->status == STATUS_SUCCESS)
									{
										//decode data
										$key3des = $this->megav_core_interface->getSessionKey();
										$userInfo = json_decode($this->megav_core_interface->decrypt3DES($userReciverInfo->data, $key3des));
										log_message('error', 'data user nhan tien: ' . print_r($userInfo, true));
										$loadView = 1;
										$data['step'] = 2;
										$data['post'] = $post;
										$data['reciverAcc'] = (array)$userInfo->uInfo;
										//$this->load->view('transfer/transfer_2', $data);
										$this->view['content'] = $this->load->view('transfer/transfer_2', $data, true);
										$this->load->view('Layout/layout_iframe', array(
											'data' => $this->view
										));
										$dataTransferRedis['post_1'] = $data['post'];
										$dataTransferRedis['reciverAcc'] = $userInfo->uInfo;
										//$dataTransferRedis['fee'] = 0; 
									}
									elseif($userReciverInfo->status == STATUS_INVALID_PHONE_EMAIL)
									{
										$data['acc_error'] = "Số điện thoại không tồn tại";
									}
									else
									{
										$data['acc_error'] = lang('MVM_'.$userReciverInfo->status);
									}
								}
								else
								{
									$data['acc_error'] = "Số điện thoại không tồn tại";
								}
							}
							else
							{
								$data['acc_error'] = "Không thể chuyển tiền đến tài khoản của bạn";
							}
						}
				}
				
				if($post['trans_met'] == '2')
				{
					log_message('error', 'Chuyển tiền ví dịch vụ ' . $accInfo['userID']);
				}
				
				if(isset($loadView))
				{
					$this->megav_libs->saveCookieUserData($transId, $dataTransferRedis);
				}
			}
			
			if(isset($post['step2']))
			{
				$data['step'] = 2;
				//array(3) { ["amount"]=> string(7) "100,000" ["pay_fee"]=> string(1) "1" ["step2"]=> string(12) "Tiếp tục" } 
				//var_dump($post);
				log_message('error', 'Chuyển tiền ví nội bộ bước 2: ' . $accInfo['userID'] . ' || data post: ' . print_r($post, true));
				$this->form_validation->set_rules('amount', 'Số tiền chuyển', 'required|trim|xss_clean|max_length[13]');
				$this->form_validation->set_rules('pay_fee', 'Người chịu phí', 'required|trim|xss_clean|max_length[50]');
				$this->form_validation->set_rules('note', 'Nội dung giao dịch', 'trim|xss_clean|max_length[255]');
				$dataTransferRedis = $this->megav_libs->getDataTransRedis();
				if($this->form_validation->run() == true)
				{
					
					$post['amount'] = str_replace(',', '', $post['amount']);
				//log_message('error', 'balance: ' . $data['balance'] . ' | amount: ' . $post['amount']);
					$feeTransfer =  $this->getFee($post['amount']);
					if($post['amount'] + $feeTransfer <= $data['balance'])
					{
						if(TRANSFER_MINIMUM_AMOUNT < $post['amount'] && $post['amount'] < TRANSFER_MAXIMUM_AMOUNT)
						{
							$data['userInfo'] = $accInfo;
							
							
							
							$data['post'] = $dataTransferRedis['post_1'];
							$data['post_2'] = $post;
							$data['fee'] = $feeTransfer;
							$data['reciverAcc'] = $dataTransferRedis['reciverAcc'];
							$dataTransferRedis['fee'] = $feeTransfer;
							$dataTransferRedis['post_2'] = $post;
							
							if($accInfo['security_method'] == '1')
							{
								$requestSendOtp = $this->megav_core_interface->genOTP($accInfo['email'], $accInfo['mobileNo'], $accInfo['userID'], $dataTransferRedis['transId']);
								$requestSendOtp = json_decode($requestSendOtp);
								if($requestSendOtp->status == STATUS_SUCCESS)
								{
									$loadView = 1;
									$data['sentOtp'] = 1;
									$data['step'] = 3;
									//$this->load->view('transfer/transfer_3', $data);
									$this->view['content'] = $this->load->view('transfer/transfer_3', $data, true);
									$this->load->view('Layout/layout_iframe', array(
										'data' => $this->view
									));
								}
								else
								{
									$loadView = 1;
									$mess = lang('MVM_'.$requestSendOtp->status);
									$this->megav_libs->page_result($mess, 'transfer');
								}
								
							}
							else
							{
								
								$loadView = 1;
								$data['step'] = 3;
								//$this->load->view('transfer/transfer_3', $data);
								$this->view['content'] = $this->load->view('transfer/transfer_3', $data, true);
								$this->load->view('Layout/layout_iframe', array(
									'data' => $this->view
								));
								
							}
							//log_message('error', '$dataTransferRedis = ' . print_r($dataTransferRedis, true));
							$this->megav_libs->saveCookieUserData($dataTransferRedis['transId'], $dataTransferRedis);
						}
						else
						{
							$data['amount_error'] = "Số tiền chuyển phải lớn hơn " . number_format(TRANSFER_MINIMUM_AMOUNT) . " đ và nhỏ hơn " . number_format(TRANSFER_MAXIMUM_AMOUNT) . ' đ';
							$data['post'] = $dataTransferRedis['post_1'];
							$data['reciverAcc'] = $dataTransferRedis['reciverAcc'];
							
							if(!empty($post['amount']))
							{
								$post['amount'] = str_replace(',', '', $post['amount']);
								$feeTransfer =  $this->getFee($post['amount']);
								$data['fee'] = $feeTransfer;
								$data['post']['amount'] = $post['amount'];
							}
						}
					}
					else
					{
						$data['amount_error'] = "Số dư không đủ.";
						$data['post'] = $dataTransferRedis['post_1'];
						$data['reciverAcc'] = $dataTransferRedis['reciverAcc'];
						
						if(!empty($post['amount']))
						{
							$post['amount'] = str_replace(',', '', $post['amount']);
							$feeTransfer =  $this->getFee($post['amount']);
							$data['fee'] = $feeTransfer;
							$data['post']['amount'] = $post['amount'];
						}
					}
				}
				else
				{
					//log_message('error', 'form error: ' . print_r($dataTransferRedis, true));
					$data['post'] = $dataTransferRedis['post_1'];
					$data['reciverAcc'] = $dataTransferRedis['reciverAcc'];
					
					if(!empty($post['amount']))
					{
						$post['amount'] = str_replace(',', '', $post['amount']);
						$feeTransfer =  $this->getFee($post['amount']);
						$data['fee'] = $feeTransfer;
						$data['post']['amount'] = $post['amount'];
					}
					
				}
			}
			
			if(isset($post['step3']))
			{
				$data['step'] = 3;
				if($accInfo['security_method'] == '1')
				{
					$this->form_validation->set_rules('otp', 'OTP', 'required|trim|xss_clean|max_length[10]');
					$otp = $post['otp'];
					$passLv2 = "";
					$data['sentOtp'] = 1;
				}
				else
				{
					$this->form_validation->set_rules('passLv2', 'Mật khẩu cấp 2', 'required|trim|xss_clean|max_length[20]|min_length[6]');
					$passLv2 = $post['passLv2'];
					$otp = "";
				}
				
				$dataTransferRedis = $this->megav_libs->getDataTransRedis();
				//log_message('error', 'step 3 | data transfer in redis: ' . print_r($dataTransferRedis, true));
				if($this->form_validation->run() == true)
				{
					// chuyen tien
					
					$accessToken = $this->megav_libs->genarateAccessToken();
					
					$merchantId = null;
					if(!empty($this->input->cookie("merchantId")))
						$merchantId = $this->input->cookie("merchantId");
					
					$transfer_megaV = $this->megav_core_interface->transferToAcc($accInfo['userID'], $dataTransferRedis['reciverAcc']['mobile'], $accInfo['email'],  
																		$dataTransferRedis['reciverAcc']['username'], $dataTransferRedis['reciverAcc']['account_epurse_id'],
																		$dataTransferRedis['post_2']['amount'], $dataTransferRedis['post_2']['pay_fee'], 
																		$dataTransferRedis['post_2']['note'], $otp, $passLv2, $accessToken, $dataTransferRedis['transId'],
																		$merchantId);
					$transfer_megaV = json_decode($transfer_megaV);
					if(isset($transfer_megaV->status))
					{
						
						if($transfer_megaV->status == STATUS_SUCCESS)
						{
							$loadView = 1;
							$dataPayment = '';
							$key3des = $this->megav_core_interface->getSessionKey();
							if(isset($transfer_megaV->data))
								$dataTransfer = json_decode($this->megav_core_interface->decrypt3DES($transfer_megaV->data, $key3des), true);
							log_message('error', 'data transfer: ' . print_r($dataTransfer, true));
							
							$mess = "Chuyển tiền thành công. ";
							if(isset($dataTransfer['trans_id']))
								$mess .= "Mã giao dịch tham chiếu. " . $dataTransfer['trans_id'];
							
							$balance = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																							$this->megav_libs->genarateAccessToken(), null, null);
							$this->megav_libs->page_result($mess, '/transfer', null, null, 1, null, null, null, $balance);
						} 
						elseif($transfer_megaV->status == STATUS_WRONG_OTP)
						{
							$data['error_otp'] = 'Sai OTP';
							$data['sentOtp'] = 1;
							$data['userInfo'] = $accInfo;
							$data['post'] = $dataTransferRedis['post_1'];
							$data['post_2'] = $dataTransferRedis['post_2'];
							$data['fee'] = $dataTransferRedis['fee'];
							$data['reciverAcc'] = $dataTransferRedis['reciverAcc'];
							//$this->load->view('transfer/transfer_3', $data);
						}
						elseif($transfer_megaV->status == STATUS_WRONG_PASSLV2)
						{
							$data['error_passLv2'] = 'Sai mật khẩu cấp 2';
							$data['userInfo'] = $accInfo;
							$data['post'] = $dataTransferRedis['post_1'];
							$data['post_2'] = $dataTransferRedis['post_2'];
							$data['fee'] = $dataTransferRedis['fee'];
							$data['reciverAcc'] = $dataTransferRedis['reciverAcc'];
						}
						elseif($transfer_megaV->status == STATUS_WRONG_AMOUNT)
						{
							$key3des = $this->megav_core_interface->getSessionKey();
							if(isset($transfer_megaV->data))
								$dataTransfer = json_decode($this->megav_core_interface->decrypt3DES($transfer_megaV->data, $key3des), true);
							
							$loadView = 1;
							$mess = "Số tiền chuyển tối thiểu là " . number_format($dataTransfer['minTransAmount']) . ' đ, tối đa là ' . number_format($dataTransfer['maxTransAmount']) . ' đ';
							$this->megav_libs->page_result($mess, '/withdraw');
						}
						else
						{
							$loadView = 1;
							$this->megav_libs->page_result(lang('MVM_'.$transfer_megaV->status), '/transfer');
						}
					}
					else
					{
						$loadView = 1;
						$this->megav_libs->page_result("Có lỗi trong quá trình chuyển tiền. Vui lòng thử lại.", '/transfer');
						
					}
				}
				else
				{
					$data['userInfo'] = $accInfo;
					$data['post'] = $dataTransferRedis['post_1'];
					$data['post_2'] = $dataTransferRedis['post_2'];
					$data['fee'] = $dataTransferRedis['fee'];
					$data['reciverAcc'] = $dataTransferRedis['reciverAcc'];
				}
			}
		}
		
		if(!isset($loadView))
		{
			if(isset($post['step2']))
			{
				
				$this->view['content'] = $this->load->view('transfer/transfer_2', $data, true);
				$this->load->view('Layout/layout_iframe', array(
					'data' => $this->view
				));
			}
			elseif(isset($post['step3']))
			{
				
				$this->view['content'] = $this->load->view('transfer/transfer_3', $data, true);
				$this->load->view('Layout/layout_iframe', array(
					'data' => $this->view
				));
			}
			else
			{
				//$userInfo = $this->megav_core_interface->getUserInfoByName('honda671231');
				
				$this->view['content'] = $this->load->view('transfer/transfer_1', $data, true);
				$this->load->view('Layout/layout_iframe', array(
					'data' => $this->view
				));
			}
		}
    }
	
	public function get_fee_transfer()
	{		
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		$post = $this->input->post();
		log_message('error', 'data post get fee transfer: ' . print_r($post, true));
		if($post)
		{
			$post['amount'] 	= $this->security->xss_clean($post['amount']);
			$post['payfee'] 	= $this->security->xss_clean($post['payfee']);
			$listfee = $this->megav_core_interface->getTempFee($this->session_memcached->userdata['info_user']['userID']);
			log_message('error', 'data list fee ' . print_r($listfee, true));
			foreach($listfee as $template)
			{
				if($template->templateType == TRANSFER_LOCAL_TEMP)
				{
					$post['amount'] = str_replace(',', '', $post['amount']);
					$feeAmount = $template->fixFee + $template->fixRate * $post['amount'] / 100;
					if(isset($post['payfee']) && $post['payfee'] == '1')
					{
						$realAmount = $post['amount'] + $feeAmount;
					}
					else
					{
						$realAmount = $post['amount'];
					}
					
					$feeAmountFormat = number_format($feeAmount);
					$realAmountFormat = number_format($realAmount);
					echo $feeAmountFormat . '|' . $realAmountFormat;
				}
			}
		}
	}
	
	public function getFee($amount)
	{
		$listfee = $this->megav_core_interface->getTempFee($this->session_memcached->userdata['info_user']['userID']);
		foreach($listfee as $template)
		{
			if($template->templateType == '2')
			{
				$amount = str_replace(',', '', $amount);
				$feeAmount = $template->fixFee + $template->fixRate * $amount / 100;
				return $feeAmount;
			}
		}
		return 0;
	}
}
?>