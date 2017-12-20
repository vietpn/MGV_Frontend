<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class buy_card extends CI_Controller
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
		$transId = $this->megav_libs->genarateTransactionId('GLT');
		//$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'], null, 1);
		$data['listAmount'] = $this->megav_core_interface->getAmountCDV($this->session_memcached->userdata['info_user']['userID'], $transId, null, null, DOWNLOAD_SOFTPIN_TEMP);
		$data['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																				$this->megav_libs->genarateAccessToken());
		
		//$listTelco = $this->megav_core_interface->getProvider($transId);
		$listTelco = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, DOWNLOAD_SOFTPIN_TEMP);
		
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
		$this->view['content'] = $this->load->view('buy_card/index', $data, true);
		$this->load->view('Layout/layout_iframe', array(
			'data' => $this->view
		));
	}
	
	public function buy_card_cdv()
	{
		$post = $this->input->post();
		$data = array();
		if($post)
		{
			$transId = $this->megav_libs->genarateTransactionId('GLT');
			//$listTelco = $this->megav_core_interface->getProvider($transId);
			$listTelco = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, DOWNLOAD_SOFTPIN_TEMP);
			
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
			
			$this->form_validation->set_rules('provider_code', 'Nhà cung cấp mã thẻ', 'required|trim|xss_clean');
			$this->form_validation->set_rules('amount', 'Mệnh giá', 'required|trim|xss_clean');
			$this->form_validation->set_rules('quantity', 'Số lượng', 'required|trim|xss_clean|max_length[13]');
			if($this->form_validation->run() == true)
			{
				$post['amount'] = str_replace(',', '', $post['amount']);
				$post['quantity'] = str_replace(',', '', $post['quantity']);
				if($post['quantity'] > 0)
				{
					$data['post'] = $post;
					$data['totalAmount'] = 0;
					
					$redis = new CI_Redis();
					$list_commission = json_decode($redis->get('DETAIL_CARD_COMMISSION' . $post['provider_code']));
					$commission_amount = 0;
					foreach($list_commission as $commission)
					{
						//if($commission->templateId == '7')
						//{
							if($commission->providerCode == $post['provider_code'])
							{
								if($commission->amount == $post['amount'])
								{
									$data['totalAmount'] = $post['quantity'] * ($commission->amount - ($commission->amount * $commission->rateDiscount / 100));
								}
							}
						//}
					}
					
					//
					$balance = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																					$this->megav_libs->genarateAccessToken());
					if($data['totalAmount'] <= $balance)
					{
						$transId = $this->megav_libs->genarateTransactionId('BLC');
						$dataBuyCardRedis = array('transId' => $transId, 'post' => $post);
						$this->megav_libs->saveCookieUserData($dataBuyCardRedis['transId'], $dataBuyCardRedis);
						if($this->session_memcached->userdata['info_user']['security_method'] == '1')
						{
							$focus = "số điện thoại";
							$requestSendOtp = $this->megav_core_interface->genOTP($this->session_memcached->userdata['info_user']['email'],
																					$this->session_memcached->userdata['info_user']['mobileNo'], 
																					$this->session_memcached->userdata['info_user']['userID'], $transId);
							if($requestSendOtp)
							{
								$response = json_decode($requestSendOtp);
								log_message('error', 'respone: ' . print_r($requestSendOtp, true));
								if(isset($response->status))
								{
									if($response->status == '00')
									{
										$this->view['content'] = $this->load->view('buy_card/buy_card_cdv', $data, true);
									}
									else
									{
										$loadView = 1;
										$view = $this->megav_libs->page_result(lang('MVM_'.$response->status), '/buy_card');
										echo $view;
									}
								}
								else
								{
									$loadView = 1;
									$mess = "Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
									$view = $this->megav_libs->page_result($mess, '/buy_card');
									echo $view;
								}
							}
							else
							{
								$loadView = 1;
								$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
								$view = $this->megav_libs->page_result($mess, '/buy_card');
								echo $view;
							}
						}
						else
						{
							$this->view['content'] = $this->load->view('buy_card/buy_card_cdv', $data, true);
						}
					}
					else
					{
						$data['err_quantity'] = "Số dư không đủ";
						$data['option'] = $this->getAmountWithProvider2($post['provider_code']);
						$this->view['content'] = $this->load->view('buy_card/index', $data, true);
					}
				}
				else
				{
					$data['err_quantity'] = "Số lượng thẻ phải lớn hơn 0";
					$data['option'] = $this->getAmountWithProvider2($post['provider_code']);
					$this->view['content'] = $this->load->view('buy_card/index', $data, true);
				}
			}
			else
			{
				//$transId = $this->megav_libs->genarateTransactionId('GLT');
				//$data['listAmount'] = $this->megav_core_interface->getAmountCDV();
				
				$data['option'] = $this->getAmountWithProvider2($post['provider_code']);
				
				$this->view['content'] = $this->load->view('buy_card/index', $data, true);
			}
			if(!isset($loadView))
			{
				$this->load->view('Layout/layout_iframe', array(
					'data' => $this->view
				));
			}
		}
	}
	
	public function buy_list_card()
	{
		$post = $this->input->post();
		$data = array();
		
		$dataBuyCardRedis = $this->megav_libs->getDataTransRedis();
		$data['post'] = $dataBuyCardRedis['post'];
		$data['totalAmount'] = '0';
		
		//$redis = new CI_Redis();
		//$list_commission = json_decode($redis->get('DETAIL_CARD_COMMISSION' . $post['provider_code']));
		//$commission_amount = 0;
		//foreach($list_commission as $commission)
		//{
		//	if($commission->providerCode == $post['provider_code'])
		//	{
		//		if($commission->amount == $post['amount'])
		//		{
		//			$data['totalAmount'] = $post['quantity'] * ($commission->amount - ($commission->amount * $commission->rateDiscount / 100));
		//		}
		//	}
		//}
		
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
				
				// buy list card
				
				//$data['post'] = $dataBuyCardRedis['post_1'];
				$accessToken = $this->megav_libs->genarateAccessToken();
				
				$merchantId = null;
				if(!empty($this->input->cookie("merchantId")))
					$merchantId = $this->input->cookie("merchantId");
				
				$buycard_megaV = $this->megav_core_interface->buyCardCDV($this->session_memcached->userdata['info_user']['userID'],
																		$this->session_memcached->userdata['info_user']['mobileNo'],
																		$this->session_memcached->userdata['info_user']['email'],
																		$dataBuyCardRedis['post']['amount'], 
																		$otp, $passLv2, $dataBuyCardRedis['post']['quantity'], 
																		$dataBuyCardRedis['post']['provider_code'], $accessToken,
																		$dataBuyCardRedis['transId'], $merchantId);
				$buycard_megaV = json_decode($buycard_megaV);
				if(isset($buycard_megaV->status))
				{
					if($buycard_megaV->status == STATUS_SUCCESS)
					{
						$loadView = 1;
						$key3des = $this->megav_core_interface->getSessionKey();
						if(isset($buycard_megaV->data))
							$dataSofpin = json_decode($this->megav_core_interface->decrypt3DES($buycard_megaV->data, $key3des), true);
						log_message('error', 'data buy card: ' . print_r($dataSofpin, true));
						
						// luu redis datasofpin sau download
						$dataBuyCardRedis['list_card'] = $dataSofpin;
						$this->megav_libs->saveCookieUserData($dataBuyCardRedis['transId'], $dataBuyCardRedis);
						
						//$listSofpin = $dataSofpin;
						$listSofpin = array();
						$listSofpin['download_items'] = json_decode($dataSofpin['download_items']);
						$listSofpin['amount'] = $dataBuyCardRedis['post']['amount'];
						if(isset($dataSofpin['termTxnDateTime']))
							$listSofpin['timecreate'] = $dataSofpin['termTxnDateTime'];
						if(isset($dataSofpin['trans_id']))
							$listSofpin['trans_id'] = $dataSofpin['trans_id'];
						
						$listSofpin['balance'] = $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																									$this->megav_libs->genarateAccessToken());
						
						$this->view['content'] = $this->load->view('buy_card/buy_card_list_card', $listSofpin, true);
						$this->load->view('Layout/layout_iframe', array(
							'data' => $this->view
						));
					}
					elseif($buycard_megaV->status == STATUS_WRONG_OTP)
					{
						$data['error_otp'] = 'Sai OTP';
						$data['sentOtp'] = 1;
						
					}
					elseif($buycard_megaV->status == STATUS_WRONG_PASSLV2)
					{
						$data['error_passLv2'] = 'Sai mật khẩu cấp 2';
					}
					else
					{
						$loadView = 1;
						$this->megav_libs->page_result(lang('MVM_'.$buycard_megaV->status), '/buy_card');
					}
				}
				else
				{
					$loadView = 1;
					$this->megav_libs->page_result("Có lỗi trong quá trình mua mã thẻ. Vui lòng thử lại.", '/buy_card');
					
				}
			}
			else
			{
				
				//$this->view['content'] = $this->load->view('buy_card/buy_card_cdv', $data, true);
			}
		}
		
		if(!isset($loadView))
		{
			$transId = $this->megav_libs->genarateTransactionId('GLP');
			//$listTelco = $this->megav_core_interface->getProvider($transId);
			$listTelco = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, DOWNLOAD_SOFTPIN_TEMP);
			
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
			$dataBuyCardRedis = $this->megav_libs->getDataTransRedis();
			$redis = new CI_Redis();
			$list_commission = json_decode($redis->get('DETAIL_CARD_COMMISSION' . $dataBuyCardRedis['post']['provider_code']));
			$commission_amount = 0;
			foreach($list_commission as $commission)
			{
				//if($commission->templateId == '7')
				//{
					if($commission->providerCode == $dataBuyCardRedis['post']['provider_code'])
					{
						if($commission->amount == $dataBuyCardRedis['post']['amount'])
						{
							$data['totalAmount'] = $dataBuyCardRedis['post']['quantity'] * ($commission->amount - ($commission->amount * $commission->rateDiscount / 100));
							break;
						}
					}
				//}
			}
			
			$this->view['content'] = $this->load->view('buy_card/buy_card_cdv', $data, true);
			$this->load->view('Layout/layout_iframe', array(
				'data' => $this->view
			));
		}
		
	}
	
	public function export_excel_listsofpin()
	{
		log_message('error', 'export_excel_listsofpin');
		$post = $this->input->post();
		if($post)
		{
			$dataBuyCardRedis = $this->megav_libs->getDataTransRedis();
			log_message('error', 'data redis: ' . print_r($dataBuyCardRedis, true));
			$listTrans_excel = json_decode($dataBuyCardRedis['list_card']['download_items']);
			
			require_once APPPATH."libraries/PHPExcel/IOFactory.php";
			$objTpl = PHPExcel_IOFactory::load(dirname(APPPATH)."/assets/template/BM_Bao_Cao_Lich_Su_Mua_Ma_The_MGV.xlsx");
			$styleArray = array(
							'borders' => array(
										'left' => array(
											'style' => PHPExcel_Style_Border::BORDER_THIN,
											'color' => array('argb' => '999'),
										),
										'right' => array(
											'style' => PHPExcel_Style_Border::BORDER_THIN,
											'color' => array('argb' => '999'),
										),
										'bottom' => array(
											'style' => PHPExcel_Style_Border::BORDER_DASHED,
											'color' => array('argb' => '999'),
										),
							));
			$styleArrayTitle = array(
									'font'  => array(
									    'bold'  => true,
									    'size'  => 16
									));
			
			$redis = new CI_Redis();
			$list_commission = json_decode($redis->get('DETAIL_CARD_COMMISSION' . $dataBuyCardRedis['post']['provider_code']));
			$commission_amount = 0;
			foreach($list_commission as $commission)
			{
				if($commission->templateId == '7')
				{
					if($commission->providerCode == $dataBuyCardRedis['post']['provider_code'])
					{
						if($commission->amount == $dataBuyCardRedis['post']['amount'])
						{
							$commission_amount = $dataBuyCardRedis['post']['quantity'] * ($commission->amount - ($commission->amount * $commission->rateDiscount / 100));
						}
					}
				}
			}
			//$listTelco = $this->megav_core_interface->getProvider($transId);
			$listTelco = $this->megav_core_interface->getProviderWithFee($this->session_memcached->userdata['info_user']['userID'], $transId, DOWNLOAD_SOFTPIN_TEMP);
			
			foreach($listTelco as $telco)
			{
				if($telco->providerCode == $dataBuyCardRedis['post']['provider_code'])
					$telcoName = $telco->providerName;
			}
			

			$objTpl->setActiveSheetIndex(0)->mergeCells('A1:G1')->setCellValue('A1', 'LỊCH SỬ MUA MÃ THẺ'.PHP_EOL.' Từ ngày '.date('Y/m/d').' đến ngày '.date('Y/m/d'));
			$objTpl->setActiveSheetIndex(0)->mergeCells('A2:J2')->setCellValue('A2', ' Tổng số thẻ: '.$dataBuyCardRedis['post']['quantity'].' - Tổng tiền (đ): '.number_format($commission_amount));
			$i = 4;
			foreach ($listTrans_excel as $row){
				
			    $objTpl->setActiveSheetIndex(0)
			    ->setCellValue('A'.$i, $dataBuyCardRedis['list_card']['trans_id'])
			    ->setCellValue('B'.$i, $dataBuyCardRedis['list_card']['termTxnDateTime'])
			    ->setCellValue('C'.$i, $telcoName)
			    ->setCellValue('D'.$i, $dataBuyCardRedis['post']['amount'])
			    ->setCellValue('E'.$i, ''.$row->card_serial.'')
			    ->setCellValue('F'.$i, ''.$row->card_pin.'')
			    ->setCellValue('G'.$i, date("d/m/Y H:i:s", strtotime($row->expired_date)));
			    $objTpl->getActiveSheet(0)->getStyle('A'.$i)->applyFromArray($styleArray);
			    $objTpl->getActiveSheet(0)->getStyle('B'.$i)->applyFromArray($styleArray);
			    $objTpl->getActiveSheet(0)->getStyle('C'.$i)->applyFromArray($styleArray);
			    $objTpl->getActiveSheet(0)->getStyle('D'.$i)->applyFromArray($styleArray);
			    $objTpl->getActiveSheet(0)->getStyle('E'.$i)->applyFromArray($styleArray);
			    $objTpl->getActiveSheet(0)->getStyle('F'.$i)->applyFromArray($styleArray);
			    $objTpl->getActiveSheet(0)->getStyle('G'.$i)->applyFromArray($styleArray);
			    $objTpl->setActiveSheetIndex(0)->getStyle('D'.$i)->getNumberFormat()->setFormatCode('#,###');
			    $i++;
			
			}
			$objTpl->getActiveSheet(0)->getStyle('E')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
			$objTpl->getActiveSheet(0)->getStyle('F')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);

			//prepare download
			$filename='Bao_Cao_Lich_Su_Mua_Ma_The_MGV_'.date('d_m_Y').'.xls'; //just some random filename
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');
			//ghi du lieu vao file,định dạng file excel 2007
			$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
			$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!

		}
	}
	
	public function get_commission()
	{
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		$post = $this->input->post();
		log_message('error', 'data post get_commission: ' . print_r($post, true));
		if($post)
		{
			$post['amount'] 	= $this->security->xss_clean($post['amount']);
			$post['providercode'] 	= $this->security->xss_clean($post['providercode']);
			$post['quantity'] 	= $this->security->xss_clean($post['quantity']);
			
			$redis = new CI_Redis();
			$list_commission = json_decode($redis->get('DETAIL_CARD_COMMISSION' . $post['providercode']));
			$commission_amount = 0;
			foreach($list_commission as $commission)
			{
				//if($commission->templateId == '7')
				//{
					if($commission->providerCode == $post['providercode'])
					{
						if($commission->amount == $post['amount'])
						{
							log_message('error', 'radiscount: ' . print_r($commission, true));
						
							$commission_amount = $post['quantity'] * ($commission->amount - ($commission->amount * $commission->rateDiscount / 100));
						}
					}
				//}
			}
			echo number_format($commission_amount);
		}
	}
	
	public function getAmountWithProvider()
	{
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		$post = $this->input->post();
		$post['providercode'] 	= $this->security->xss_clean($post['providercode']);
		$html = '';
		log_message('error', 'get getAmountWithProvider: ' . print_r($post, true));
		$transId = $this->megav_libs->genarateTransactionId('GLA');
		$requestGetAmount = $this->megav_core_interface->getAmountCDV($this->session_memcached->userdata['info_user']['userID'], $transId, $post['providercode'], null, DOWNLOAD_SOFTPIN_TEMP);
		if($requestGetAmount)
		{
			usort($requestGetAmount, array($this, "cmp"));
			$html = '<option value="">Chọn mệnh giá</option>';
			foreach($requestGetAmount as $amount)
			{
				//if($amount->templateId == '7')
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
	
	public function getAmountWithProvider2($providercode)
	{
		$html = '';
		$transId = $this->megav_libs->genarateTransactionId('GLA');
		//$requestGetAmount = $this->megav_core_interface->getAmountCDV($transId, $providercode, null, 9);
		$requestGetAmount = $this->megav_core_interface->getAmountCDV($this->session_memcached->userdata['info_user']['userID'], $transId, $providercode, null, DOWNLOAD_SOFTPIN_TEMP);
		if($requestGetAmount)
		{
			usort($requestGetAmount, array($this, "cmp"));
			$html = '<option value="">Chọn mệnh giá</option>';
			foreach($requestGetAmount as $amount)
			{
				//if($amount->templateId == '7')
				//{
					$html .= '<option value="'.$amount->amount.'" '.set_select('amount', $amount->amount, False).'>'.number_format($amount->amount).'</option>';
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