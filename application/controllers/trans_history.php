<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class trans_history extends CI_Controller
{
    const EC_MERCHANT_TEMP = 1;
    const DEPOSIT_IBK_TEMP = 3;
    const DEPOSIT_BANK_MAPPING_TEMP = 6;
    const WIDTHDRAW_TEMP = 4;
    const WIDTHDRAW_MAPPING_TEMP = 5;
    const WIDTHDRAW_FAST_TEMP = 11;
    const TRANSFER_LOCAL_TEMP = 4;
    const TRANSFER_TO_SERVICE_TEMP = 5;
    const TOPUP_GAME_TEMP = 8;
    const DOWNLOAD_SOFTPIN_TEMP = 9;
    const PAY_BILL_TEMP = 10;
    
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
		require_once APPPATH."libraries/PHPExcel/IOFactory.php";
        
		$this->session_memcached->get_userdata();
		if(!isset($this->session_memcached->userdata['info_user']['userID']))
		{
			echo "<script>window.top.location='" . base_url() . "'</script>";
			die;
		}
    }

    
	public function index($tab = null, $searchTransId = null)
	{
		
		$data = array();
		$data['tab'] = 'payment';
		$data['totalTrans'] = 0;
		$data['totalAmount'] = 0;
		$transId = $this->megav_libs->genarateTransactionId('GBA');
		$transIdGame = $this->megav_libs->genarateTransactionId('GLT');
		$listBank = $this->megav_core_interface->getProvider($transId);
		$listTelco = $this->megav_core_interface->getProvider($transIdGame);


		if($listBank)
			$data['listBank'] = $listBank;

		if($listTelco)
			$data['listTelco'] = $listTelco;
		
		$dataMenu['userinfo'] = array('userName' => $this->session_memcached->userdata['info_user']['userID'],
										'balance' => $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																											$this->megav_libs->genarateAccessToken()));
		
		if($tab != null && $searchTransId != null)
		{
			$data['tab'] = $tab;
			$data['redirect_trans_id'] = $searchTransId;
			$transSubType = "";
			$uname = $this->session_memcached->userdata['info_user']['userID'];
			switch($tab){
				case 'payment' : $transaction_type = 1;
				$transSubType = 2;
				$result = $this->megav_core_interface->getTransList($uname, $transaction_type, $searchTransId, '', '', '', '', 1, 10, 0,$transId, $transSubType);
				break;
				case 'transfer' : $transaction_type = 3;
				$result = $this->megav_core_interface->getTransList($uname, $transaction_type, $searchTransId, '', '', '', '', 1, 10, 0,$transId, $transSubType);
				break;
				case 'withdraw' : $transaction_type = 2;
				$result = $this->megav_core_interface->getTransList($uname, $transaction_type, $searchTransId, '', '', '', '', 1, 10, 0,$transId, $transSubType);
				break;
				case 'paymentphone' : $transaction_type = 7;
									$transSubType = 9;
				$result = $this->megav_core_interface->getTransList($uname, $transaction_type, $searchTransId, '', '', '', '', 1, 10, 0,$transId, $transSubType);
				break;
				case 'topup' : $transaction_type = 8;
				$result = $this->megav_core_interface->getTransList($uname, $transaction_type, $searchTransId, '', '', '', '', 1, 10, 0,$transId, $transSubType);
				break;
				case 'paymentgame' : $transaction_type = 6;
								$transSubType = 10;
				$result = $this->megav_core_interface->getTransList($uname, $transaction_type, $searchTransId, '', '', '', '', 1, 10, 0,$transId, $transSubType);
				break;
				case 'paymentbills' : $transaction_type = 5;
				$transSubType = 13;
				$result = $this->megav_core_interface->getTransList($uname, $transaction_type, $searchTransId, '', '', '', '', 1, 10, 0,'', $transSubType);
				break;
			}
			
			
			
			if($result){
				$data['listTrans'] = $result->listTrans;
				$data['totalTrans'] = $result->totalTrans;
				$data['totalAmount'] = number_format($result->totalAmount);
				$config['total_rows'] = $result->totalTrans;
			}
		}
		
		
		
		
		
		$this->load->view('trans_history/index', $data);
		/*
		$this->view['content'] = $this->load->view('trans_history/index', $data, true);
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', $dataMenu, true),
			'user_info' => $this->session_memcached->userdata['info_user']
		));
		*/
	}
	
	public function payment()
	{
		log_message('error', 'post to payment');
		$post = $this->input->post();
		$data = array();
		$data['tab'] = 'payment';
		$data['totalTrans'] = 0;
		$data['totalAmount'] = 0;
		$page = !empty($post['page']) ? (int)($post['page']) : 1;
		$this->config->load('pagination', TRUE);
		$config = $this->config->item('pagination', 'pagination');
		$config["base_url"] = base_url() . "/trans_history/payment";
		$config['cur_page'] = $page;
		
		if($post){
            $data['after_post'] = $post;
			$this->form_validation->set_rules('transId', '', 'trim|xss_clean');
			$this->form_validation->set_rules('fdate', '', 'trim|xss_clean');
			$this->form_validation->set_rules('tdate', '', 'trim|xss_clean|callback_date_greater_than['.$post['fdate'].']');
			$this->form_validation->set_rules('provider_code', '', 'trim|xss_clean');
			$this->form_validation->set_rules('status', '', 'trim|xss_clean');
			if($this->form_validation->run() == true) {
				$post = array_map('trim', $post);
				//$requestTransId = "GLT" . date("Ymd") . rand();
				$requestTransId = $this->megav_libs->genarateTransactionId('GLT');;
				$post['transaction_type'] = 1;
				$post['numbPage'] = $page;
				$post['pageSize'] = $config['per_page'];
				$post['transferType'] = 1;
				if($post['provider_code'] == '-1')
					$post['provider_code'] = "";
				if($post['status'] == '-1')
					$post['status'] = "";
                   $transSubType = ($post['hinh_thuc']=='-1') ? '' : $post['hinh_thuc'];
				
				$uname = $this->session_memcached->userdata['info_user']['userID'];
				//$uname = "test1998";
				$result = $this->megav_core_interface->getTransList($uname, 
																	trim($post['transaction_type']), trim($post['transId']), trim($post['provider_code']), 
																	trim($post['fdate']), trim($post['tdate']), trim($post['status']), trim($post['numbPage']), 
																	trim($post['pageSize']), trim($post['transferType']), $requestTransId, $transSubType);

				

				
				if($result){
					
					$data['listTrans'] = $result->listTrans;
					$data['totalTrans'] = $result->totalTrans;
					$data['totalAmount'] = number_format($result->totalAmount);
					$config['total_rows'] = $result->totalTrans;
				}
				
				if(isset($post['excel'])){
					//load Excel template file
        			$objTpl = PHPExcel_IOFactory::load(dirname(APPPATH)."/assets/template/BM_Bao_Cao_Lich_Su_Nap_Tien_MGV.xlsx");

        			$result2 = $this->megav_core_interface->getTransList($uname, 
																	trim($post['transaction_type']), trim($post['transId']), trim($post['provider_code']), 
																	trim($post['fdate']), trim($post['tdate']), trim($post['status']), 0, 0, trim($post['transferType']), $requestTransId, $transSubType);
        			if ($result2) {
        				log_message('error', 'data result123: ' . print_r($result2, true));
		        				$listTrans_excel = $result2->listTrans;
								$totalTrans_excel = $result2->totalTrans;
								$totalAmount_excel = number_format($result2->totalAmount);
								$total_rows_excel = $result2->totalTrans;

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
					             
					            //set gia tri cho cac cot du lieu
					            $objTpl->setActiveSheetIndex(0)->mergeCells('A1:J1')->setCellValue('A1', 'LỊCH SỬ NẠP TIỀN'.PHP_EOL.'Từ ngày '.$this->input->post('fdate').' đến ngày '.$this->input->post('tdate'));
					            $objTpl->setActiveSheetIndex(0)->mergeCells('A2:J2')->setCellValue('A2', ' Tổng số giao dịch: '.$totalTrans_excel.' - Tổng tiền thực nhận (đ): '.$totalAmount_excel);
					            $i = 4;
					            foreach ($listTrans_excel as $row){
						            $status_excel = '';
						            switch($row->status){
										case '-1': 
										$status_excel = 'Khởi tạo';
										break;
										case '00': 
										$status_excel = 'Thành công';
										break;
										case '01': 
										$status_excel = 'Đã redirect sang Bank';
										break;
										case '02': 
										$status_excel = 'Thất bại';
										break;
										case '03': 
										$status_excel = 'Trừ bank thành công, cộng ví lỗi';
										break;
										case '4': 
										$status_excel = 'Bắt đầu xử lý Update result';
										break;
										case '99': 
										$status_excel ='Chờ xử lý';
										break;
										default : 
										$status_excel = 'Thất bại';
										break;
									}
									$transThrough = '';
									switch ($row->transThrough) {
										case '1':
											$transThrough = 'Nạp tiền theo phiên';
											break;
										case '2':
											$transThrough = 'Nạp tiền nhanh';
											break;
										case '3':
											$transThrough = 'Nạp tiền tài khoản liên kết';
											break;
										default:
											$transThrough = '';
											break;
									}

						            $objTpl->setActiveSheetIndex(0)
						            ->setCellValue('A'.$i, $row->requestId)
						            ->setCellValue('B'.$i, $row->bankName)
						            ->setCellValue('C'.$i, $row->amount)
						            ->setCellValue('D'.$i, $row->fixFee)
						            ->setCellValue('E'.$i, $row->rateFee)
						            ->setCellValue('F'.$i, $row->feeAmount)
						            ->setCellValue('G'.$i, $row->realReceive)
						            ->setCellValue('H'.$i, (isset($row->timeCreate)) ? date('d/m/Y H:i:s', strtotime($row->timeCreate)) : "")
						            ->setCellValue('I'.$i, $transThrough)
						            ->setCellValue('J'.$i, $status_excel);
						            $objTpl->getActiveSheet(0)->getStyle('A'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('B'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('C'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('D'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('E'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('F'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('G'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('H'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('I'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('J'.$i)->applyFromArray($styleArray);
						            $objTpl->setActiveSheetIndex(0)->getStyle('C'.$i)->getNumberFormat()->setFormatCode('#,###');
						            $objTpl->setActiveSheetIndex(0)->getStyle('F'.$i)->getNumberFormat()->setFormatCode('#,###');
						            $objTpl->setActiveSheetIndex(0)->getStyle('G'.$i)->getNumberFormat()->setFormatCode('#,###');
						            $i++;
					            
					            }




					            //prepare download
					            $filename=' Bao_Cao_Lich_Su_Nap_Tien_MGV_'.date('d_m_Y').'.xls'; //just some random filename
					            header('Content-Type: application/vnd.ms-excel');
					            header('Content-Disposition: attachment;filename="'.$filename.'"');
					            header('Cache-Control: max-age=0');
					            //ghi du lieu vao file,định dạng file excel 2007
					            $objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
					            $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
        			}


					
				}
			}
		}
		
		$transId = $this->megav_libs->genarateTransactionId('GBA');
		$listBank = $this->megav_core_interface->getProvider($transId);
		if($listBank)
			$data['listBank'] = $listBank;
		
		$this->pagination->initialize($config);
        $data['paginationLinks'] = $this->pagination->create_links();
		
		$dataMenu['userinfo'] = array('userName' => $this->session_memcached->userdata['info_user']['userID'],
										'balance' => $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																											$this->megav_libs->genarateAccessToken()));
		
		$this->view['content'] = $this->load->view('trans_history/index', $data);
		/*
		$this->view['content'] = $this->load->view('trans_history/index', $data, true);
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', $dataMenu, true),
			'user_info' => $this->session_memcached->userdata['info_user']
		));
		*/
	}
	
	public function transfer()
	{
		$post = $this->input->post();
		$data = array();
		$data['tab'] = 'transfer';
		$data['totalTrans'] = 0;
		$data['totalAmount'] = 0;
		$page = !empty($post['page']) ? (int)($post['page']) : 1;
		$this->config->load('pagination', TRUE);
		$config = $this->config->item('pagination', 'pagination');
		$config["base_url"] = base_url() . "/trans_history/index";
		$config['cur_page'] = $page;
		
		if($post)
		{
			$data['after_post'] = $post;
			$this->form_validation->set_rules('transId', '', 'trim|xss_clean');
			$this->form_validation->set_rules('fdate', '', 'trim|xss_clean');
            $this->form_validation->set_rules('tdate', '', 'trim|xss_clean|callback_date_greater_than['.$post['fdate'].']');
			$this->form_validation->set_rules('provider_code', '', 'trim|xss_clean');
			$this->form_validation->set_rules('status', '', 'trim|xss_clean');
			$this->form_validation->set_rules('transfer_type', '', 'trim|xss_clean');
			if($this->form_validation->run() == true)
			{
                $post = array_map('trim', $post);
				$requestTransId = $this->megav_libs->genarateTransactionId('GLT');
				$post['transaction_type'] = 3;
				$post['numbPage'] = $page;
				$post['pageSize'] = $config['per_page'];
				$post['provider_code'] = "";
				if($post['status'] == '-1')
					$post['status'] = "";
				$transSubType = "";
				if ($post['transferType'] == TRANSFER_TO_USER) {
				    $transSubType = self::TRANSFER_LOCAL_TEMP;
                } else if ($post['transferType'] == TRANSFER_TO_SERVICES) {
				    $transSubType = self::TRANSFER_TO_SERVICE_TEMP;
                }
				
				//$post['fdate'] = "";
				//$post['tdate'] = "";

				/*echo "<pre>";
				print_r($post);
				echo "</pre>";die;*/
				
				$uname = $this->session_memcached->userdata['info_user']['userID'];
				//$uname = "test1998";
				$result = $this->megav_core_interface->getTransList($uname, 
																	trim($post['transaction_type']), trim($post['transId']), trim($post['provider_code']), 
																	trim($post['fdate']), trim($post['tdate']), trim($post['status']), trim($post['numbPage']), 
																	trim($post['pageSize']), trim($post['transfer_type']), $requestTransId, $transSubType);
				
				if($result){
					/*echo "<pre>";
					print_r($result);die;*/
					$data['listTrans'] = $result->listTrans;
					$data['totalTrans'] = $result->totalTrans;
					$data['totalAmount'] = number_format($result->totalAmount);
					$data['minusAmount'] = number_format($result->minusAmount);
					$data['plusAmount'] = number_format($result->plusAmount);
					$config['total_rows'] = $result->totalTrans;
					switch($post['transferType']){
						case TRANSFER_TO_SERVICES: 
						$data['trans_type'] = 'Chuyển tiền ví dịch vụ';
						break;
						case TRANSFER_TO_USER:
						$data['trans_type'] = 'Chuyển tiền ví nội bộ';
						break;
						default :
						$data['trans_type'] = '';
						break;
					}
				}
				
				if(isset($post['excel'])){
					//load Excel template file
        			$objTpl = PHPExcel_IOFactory::load(dirname(APPPATH)."/assets/template/BM_Bao_Cao_Lich_Su_Chuyen_Tien_MGV.xlsx");

        			$result2 = $this->megav_core_interface->getTransList($uname, 
																	trim($post['transaction_type']), trim($post['transId']), trim($post['provider_code']), 
																	trim($post['fdate']), trim($post['tdate']), trim($post['status']), 0, 0, trim($post['transferType']), $requestTransId, $transSubType);
        			if ($result2) {

        				//echo "<pre>";print_r($result2);die;

		        				$listTrans_excel = $result2->listTrans;
								$totalTrans_excel = $result2->totalTrans;
								$totalAmount_excel = number_format($result2->totalAmount);
								$minusAmount_excel = number_format($result2->minusAmount);
								$plusAmount_excel = number_format($result2->plusAmount);
								$total_rows_excel = $result2->totalTrans;

								switch($post['transferType']){
									case TRANSFER_TO_SERVICES: 
									$trans_type = 'Chuyển tiền ví dịch vụ';
									break;
									case TRANSFER_TO_USER:
									$trans_type = 'Chuyển tiền ví nội bộ';
									break;
									default :
									$trans_type = '';
									break;
								}


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
					             
					            //set gia tri cho cac cot du lieu
					            $objTpl->setActiveSheetIndex(0)->mergeCells('A1:H1')->setCellValue('A1', 'LỊCH SỬ CHUYỂN TIỀN VÍ NỘI BỘ '.PHP_EOL.' Từ ngày '.$this->input->post('fdate').' đến ngày '.$this->input->post('tdate'));
					            $objTpl->setActiveSheetIndex(0)->mergeCells('A2:H2')->setCellValue('A2', ' Tổng số giao dịch: '.$totalTrans_excel.' - Tổng tiền thực nhận (đ): '.$plusAmount_excel . ' - Tổng tiền thực chuyển (đ): '.$minusAmount_excel);
					            $i = 4;
					            foreach ($listTrans_excel as $row){
						            $status_excel = '';
						            switch($row->status){
										case '00': 
										$status_excel = 'Thành công';
										break;
										case '99':
										$status_excel ='Chờ xử lý';
										break;
										default : 
										$status_excel = 'Thất bại';
										break;
									}
									switch($row->transferType) {
                                        case self::TRANSFER_LOCAL_TEMP:
                                            $trans_type = 'Chuyển tiền ví nội bộ';
                                            break;
                                        case self::TRANSFER_TO_SERVICE_TEMP:
                                            $trans_type = 'Chuyển tiền ví dịch vụ';
                                            break;
                                        default:
                                            $trans_type = '';
                                            break;
                                    }

                                    $objTpl->setActiveSheetIndex(0)
                                    ->setCellValue('A'.$i, $row->transId)
                                    ->setCellValue('B'.$i, $trans_type)
                                    ->setCellValue('C'.$i, $row->amount)
                                    ->setCellValue('D'.$i, ($row->whoPayFee)==1 ? $row->feeAmount : '')
                                    ->setCellValue('E'.$i, $row->sendUserName)
                                    ->setCellValue('F'.$i, $row->receiveUserName)
                                    ->setCellValue('G'.$i, ($row->whoPayFee)==2 ? $row->feeAmount : '')
                                    ->setCellValue('H'.$i, date("d/m/Y H:i:s", strtotime($row->createdDate)))
                                    ->setCellValue('I'.$i, $status_excel);
                                    $objTpl->getActiveSheet(0)->getStyle('A'.$i)->applyFromArray($styleArray);
                                    $objTpl->getActiveSheet(0)->getStyle('B'.$i)->applyFromArray($styleArray);
                                    $objTpl->getActiveSheet(0)->getStyle('C'.$i)->applyFromArray($styleArray);
                                    $objTpl->getActiveSheet(0)->getStyle('D'.$i)->applyFromArray($styleArray);
                                    $objTpl->getActiveSheet(0)->getStyle('E'.$i)->applyFromArray($styleArray);
                                    $objTpl->getActiveSheet(0)->getStyle('F'.$i)->applyFromArray($styleArray);
                                    $objTpl->getActiveSheet(0)->getStyle('G'.$i)->applyFromArray($styleArray);
                                    $objTpl->getActiveSheet(0)->getStyle('H'.$i)->applyFromArray($styleArray);
                                    $objTpl->getActiveSheet(0)->getStyle('I'.$i)->applyFromArray($styleArray);
                                    $objTpl->setActiveSheetIndex(0)->getStyle('C'.$i)->getNumberFormat()->setFormatCode('#,###');
                                    $objTpl->setActiveSheetIndex(0)->getStyle('D'.$i)->getNumberFormat()->setFormatCode('#,###');
                                    $objTpl->setActiveSheetIndex(0)->getStyle('G'.$i)->getNumberFormat()->setFormatCode('#,###');
                                    $i++;
					            
					            }




					            //prepare download
					            $filename=' Bao_Cao_Lich_Su_Chuyen_Tien_MGV_'.date('d_m_Y').'.xls'; //just some random filename
					            header('Content-Type: application/vnd.ms-excel');
					            header('Content-Disposition: attachment;filename="'.$filename.'"');
					            header('Cache-Control: max-age=0');
					            //ghi du lieu vao file,định dạng file excel 2007
					            $objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
					            $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!

					    }
        			}
			}
		}
		
		$transId = $this->megav_libs->genarateTransactionId('GBA');
		$listBank = $this->megav_core_interface->getProvider($transId);
		if($listBank)
			$data['listBank'] = $listBank;
		
		$this->pagination->initialize($config);
        $data['paginationLinks'] = $this->pagination->create_links();
		
		$dataMenu['userinfo'] = array('userName' => $this->session_memcached->userdata['info_user']['userID'],
										'balance' => $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																											$this->megav_libs->genarateAccessToken()));
		
		$this->view['content'] = $this->load->view('trans_history/index', $data);
		/*
		$this->view['content'] = $this->load->view('trans_history/index', $data, true);
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', $dataMenu, true),
			'user_info' => $this->session_memcached->userdata['info_user']
		));
		*/
	}
	public function transfer_detail(){
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		if ($this->input->post('req_id')) {
			

			$this->form_validation->set_rules('transId', '', 'trim|xss_clean');
			$this->form_validation->set_rules('fdate', '', 'trim|xss_clean');
            $this->form_validation->set_rules('tdate', '', 'trim|xss_clean|callback_date_greater_than['.$this->input->post('fdate').']');
			$this->form_validation->set_rules('transfer_type', '', 'trim|xss_clean');
			if($this->form_validation->run() == true){
				$transId = trim($this->input->post('req_id'));
				$fdate = trim($this->input->post('fdate'));
				$tdate = trim($this->input->post('tdate'));
				$transfer_type = trim($this->input->post('transfer_type'));

				$requestTransId = $this->megav_libs->genarateTransactionId('GLT');
				$provider_code = "";

				
				$uname = $this->session_memcached->userdata['info_user']['userID'];
				//$uname = "test1998";
				$result = $this->megav_core_interface->getTransList($uname, 3, $transId, $provider_code,$fdate,$tdate, $transfer_type, $requestTransId);
				
				if($result->listTrans[0]){
					$result_res = $result->listTrans[0];
					switch($result_res->status){
						case '00': 
						$status = 'Thành công';
						break;
						case '01': 
						$status = 'Thất bại';
						case '99': 
						$status ='Chờ xử lý';
						break;
						default : 
						$status = '';
						break;
					}

					//echo "<pre>";print_r($result_res);die;
					switch ($result_res->whoPayFee) {
						case 1:
							$messPay = "Người chuyển chịu phí";
							break;
						case 2:
							$messPay = "Người nhận chịu phí";
							break;
						
						default:
							$messPay = "Chưa có";
							break;
					}
					switch($result_res->transferType) {
	                    case self::TRANSFER_LOCAL_TEMP:
	                        $trans_type = 'Chuyển tiền ví nội bộ';
	                        break;
	                    case self::TRANSFER_TO_SERVICE_TEMP:
	                        $trans_type = 'Chuyển tiền ví dịch vụ';
	                        break;
	                    default:
	                        $trans_type = '';
	                        break;
	                }
					
					$html = '<div class="form-group">
						      <label class="control-label col-sm-5 col-xs-5" for="email">Hình thức giao dịch:</label>
						      <div class="col-sm-7 col-xs-7">
						        '.$trans_type.'
						      </div>
						    </div><div class="clearfix"></div>
						    <div class="form-group">
						      <label class="control-label col-sm-5 col-xs-5" for="pwd">Id thanh toán:</label>
						      <div class="col-sm-7 col-xs-7">          
						        '.$result_res->transId.'
						      </div>
						    </div><div class="clearfix"></div>
						    <div class="form-group">
						      <label class="control-label col-sm-5 col-xs-5" for="pwd">Số tiền chuyển (đ):</label>
						      <div class="col-sm-7 col-xs-7">          
						        <span class="amount">'.number_format($result_res->amount).'</span>
						      </div>
						    </div><div class="clearfix"></div>
						    <div class="form-group">
						      <label class="control-label col-sm-5 col-xs-5" for="pwd">Tài khoản nhận:</label>
						      <div class="col-sm-7 col-xs-7">          
						        '.$result_res->receiveUserName.'
						      </div>
						    </div><div class="clearfix"></div>
						    <div class="form-group">
						      <label class="control-label col-sm-5 col-xs-5" for="pwd">Người chịu phí:</label>
						      <div class="col-sm-7 col-xs-7">          
						        '.$messPay.'
						      </div>
						    </div><div class="clearfix"></div>
						    <div class="form-group">
						      <label class="control-label col-sm-5 col-xs-5" for="pwd">Phí chuyển tiền:</label>
						      <div class="col-sm-7 col-xs-7">          
						        <span class="amount">'.number_format($result_res->feeAmount).'</span>
						      </div>
						    </div><div class="clearfix"></div>
						    <div class="form-group">
						      <label class="control-label col-sm-5 col-xs-5" for="pwd">Thời gian chuyển:</label>
						      <div class="col-sm-7 col-xs-7">          
						        '.date('d/m/Y H:i:s', strtotime($result_res->createdDate)).'
						      </div>
						    </div><div class="clearfix"></div>
						    <div class="form-group">
						      <label class="control-label col-sm-5 col-xs-5" for="pwd">Tiền thực chuyển:</label>
						      <div class="col-sm-7 col-xs-7">          
						        <span class="amount">'.number_format($result_res->minusAmount).'</span>
						      </div>
						    </div><div class="clearfix"></div>
						    <div class="form-group">
						      <label class="control-label col-sm-5 col-xs-5" for="pwd">Tiền thực nhận:</label>
						      <div class="col-sm-7 col-xs-7">          
						        <span class="amount">'.number_format($result_res->plusAmount).'</span>
						      </div>
						    </div><div class="clearfix"></div>
						    <div class="form-group">
						      <label class="control-label col-sm-5 col-xs-5" for="pwd">Nội dung chuyển:</label>
						      <div class="col-sm-7 col-xs-7" style="word-wrap:break-word;">          
						        '.$result_res->note.'
						      </div>
						    </div><div class="clearfix"></div>
						    <div class="form-group">
						      <label class="control-label col-sm-5 col-xs-5" for="pwd">Trạng thái:</label>
						      <div class="col-sm-7 col-xs-7">          
						        '.$status.'
						      </div>
						    </div><div class="clearfix"></div>';

					echo json_encode(
						array(
						"status"=> true,
						"html"=> $html
						)
					);
				}


			}

			
		}
	}
	
	public function withdraw()
	{
		$post = $this->input->post();
		$data = array();
		$data['tab'] = 'withdraw';
		$data['totalTrans'] = 0;
		$data['totalAmount'] = 0;
		$page = !empty($post['page']) ? (int)($post['page']) : 1;
		$this->config->load('pagination', TRUE);
		$config = $this->config->item('pagination', 'pagination');
		$config["base_url"] = base_url() . "/trans_history/index";
		$config['cur_page'] = $page;
		
		if($post)
		{
			$this->form_validation->set_rules('transId', '', 'trim|xss_clean');
			$this->form_validation->set_rules('fdate', '', 'trim|xss_clean');
            $this->form_validation->set_rules('tdate', '', 'trim|xss_clean|callback_date_greater_than['.$post['fdate'].']');
			$this->form_validation->set_rules('provider_code', '', 'trim|xss_clean');
			$this->form_validation->set_rules('withdraw_type', '', 'trim|xss_clean');
			$this->form_validation->set_rules('status', '', 'trim|xss_clean');
			if($this->form_validation->run() == true) 
			{
                $post = array_map('trim', $post);
				$requestTransId = $this->megav_libs->genarateTransactionId('GLT');
				$post['transaction_type'] = 2;
				$post['numbPage'] = $page;
				$post['pageSize'] = $config['per_page'];
				$post['transferType'] = 1;
				if($post['provider_code'] == '-1')
					$post['provider_code'] = "";
				if($post['status'] == '-1')
					$post['status'] = "";
				
				$uname = $this->session_memcached->userdata['info_user']['userID'];
				//$uname = "test1998";
				$result = $this->megav_core_interface->getTransList($uname, 
																	trim($post['transaction_type']), trim($post['transId']), trim($post['provider_code']), 
																	trim($post['fdate']), trim($post['tdate']), trim($post['status']), trim($post['numbPage']), 
																	trim($post['pageSize']), trim($post['transferType']), $requestTransId, trim($post['withdraw_type']));
				
				if($result)
				{
					//echo "<pre>";print_r($result);die;
					$data['listTrans'] = $result->listTrans;
					$data['totalTrans'] = $result->totalTrans;
					$data['totalAmount'] = number_format($result->totalAmount);
					$config['total_rows'] = $result->totalTrans;
				}
				
				if(isset($post['excel'])){
					//load Excel template file
        			$objTpl = PHPExcel_IOFactory::load(dirname(APPPATH)."/assets/template/BM_Bao_Cao_Lich_Su_Rut_Tien_MGV.xlsx");

        			$result2 = $this->megav_core_interface->getTransList($uname, 
																	trim($post['transaction_type']), trim($post['transId']), trim($post['provider_code']), 
																	trim($post['fdate']), trim($post['tdate']), trim($post['status']), 0, 0, trim($post['transferType']), $requestTransId);
        			if ($result2) {

        				//echo "<pre>";print_r($result2);die;

		        				$listTrans_excel = $result2->listTrans;
								$totalTrans_excel = $result2->totalTrans;
								$totalAmount_excel = number_format($result2->totalAmount);
								$total_rows_excel = $result2->totalTrans;

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
					             
					            //set gia tri cho cac cot du lieu
					            $objTpl->setActiveSheetIndex(0)->mergeCells('A1:J1')->setCellValue('A1', 'LỊCH SỬ RÚT TIỀN'.PHP_EOL.' Từ ngày '.$this->input->post('fdate').' đến ngày '.$this->input->post('tdate'));
					            $objTpl->setActiveSheetIndex(0)->mergeCells('A2:J2')->setCellValue('A2', ' Tổng số giao dịch: '.$totalTrans_excel.' - Tổng tiền rút (đ): '.$totalAmount_excel);
					            $i = 4;
					            $time_end = '';
					            foreach ($listTrans_excel as $row){
						            $status_excel = '';
						            switch($row->status){
										case '00': 
										$status_excel = 'Thành công';
										$time_end = date("d/m/Y H:i:s", strtotime($row->epurseResponseTime));
										break;
										case '99':
										$status_excel ='Chờ xử lý';
										$time_end = '';
										break;
										default : 
										$status_excel = 'Thất bại';
										$time_end = date("d/m/Y H:i:s", strtotime($row->epurseResponseTime));
										break;
									}
									
						            $objTpl->setActiveSheetIndex(0)
						            ->setCellValue('A'.$i, $row->orgTransId)
						            ->setCellValue('B'.$i, $row->bankName)
						            ->setCellValue('C'.$i, $row->amount)
						            ->setCellValue('D'.$i, $row->fixFee)
						            ->setCellValue('E'.$i, $row->rateFee)
						            ->setCellValue('F'.$i, $row->feeAmount)
						            ->setCellValue('G'.$i, $row->realMinus)
						            ->setCellValue('H'.$i, date("d/m/Y H:i:s", strtotime($row->epurseRequestTime)))
						            ->setCellValue('I'.$i, $time_end)
						            ->setCellValue('J'.$i, $status_excel);
						            $objTpl->getActiveSheet(0)->getStyle('A'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('B'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('C'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('D'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('E'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('F'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('G'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('H'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('I'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('J'.$i)->applyFromArray($styleArray);
						            $objTpl->setActiveSheetIndex(0)->getStyle('C'.$i)->getNumberFormat()->setFormatCode('#,###');
						            $objTpl->setActiveSheetIndex(0)->getStyle('F'.$i)->getNumberFormat()->setFormatCode('#,###');
						            $objTpl->setActiveSheetIndex(0)->getStyle('G'.$i)->getNumberFormat()->setFormatCode('#,###');
						            $i++;
					            
					            }




					            //prepare download
					            $filename=' Bao_Cao_Lich_Su_Rut_Tien_MGV_'.date('d_m_Y').'.xls'; //just some random filename
					            header('Content-Type: application/vnd.ms-excel');
					            header('Content-Disposition: attachment;filename="'.$filename.'"');
					            header('Cache-Control: max-age=0');
					            //ghi du lieu vao file,định dạng file excel 2007
					            $objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
					            $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!

					    }
        			}
			}
		}
		
		$transId = $this->megav_libs->genarateTransactionId('GBA');
		$listBank = $this->megav_core_interface->getProvider($transId);
		if($listBank)
			$data['listBank'] = $listBank;
		
		$this->pagination->initialize($config);
        $data['paginationLinks'] = $this->pagination->create_links();
		
		$dataMenu['userinfo'] = array('userName' => $this->session_memcached->userdata['info_user']['userID'],
										'balance' => $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																											$this->megav_libs->genarateAccessToken()));
		
		$this->view['content'] = $this->load->view('trans_history/index', $data);
		/*
		$this->view['content'] = $this->load->view('trans_history/index', $data, true);
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', $dataMenu, true),
			'user_info' => $this->session_memcached->userdata['info_user']
		));
		*/
	}
	
	public function paymentphone()
	{
		$post = $this->input->post();
		$data = array();
		$data['tab'] = 'paymentphone';
		$data['totalTrans'] = 0;
		$data['totalAmount'] = 0;
		$page = !empty($post['page']) ? (int)($post['page']) : 1;
		$this->config->load('pagination', TRUE);
		$config = $this->config->item('pagination', 'pagination');
		$config["base_url"] = base_url() . "/trans_history/index";
		$config['cur_page'] = $page;
		
		if($post)
		{
			$this->form_validation->set_rules('transId', '', 'trim|xss_clean');
			$this->form_validation->set_rules('fdate', '', 'trim|xss_clean');
            $this->form_validation->set_rules('tdate', '', 'trim|xss_clean|callback_date_greater_than['.$post['fdate'].']');
			$this->form_validation->set_rules('provider_code', '', 'trim|xss_clean');
			$this->form_validation->set_rules('status', '', 'trim|xss_clean');
			if($this->form_validation->run() == true) 
			{
				$requestTransId = $this->megav_libs->genarateTransactionId('GLT');
				$post['transaction_type'] = 7;
				$post['numbPage'] = $page;
				$post['pageSize'] = $config['per_page'];
				$post['transferType'] = 1;
				
				$provider_code = "";
				if(isset($post['provider_code']))
				{
					if($post['provider_code'] == '-1')
						$provider_code = "";
					else
						$provider_code = $post['provider_code'];
				}
				if($post['status'] == '-1')
					$post['status'] = "";
				
				
				$uname = $this->session_memcached->userdata['info_user']['userID'];
				//$uname = "test1998";
				$result = $this->megav_core_interface->getTransList($uname, 
																	trim($post['transaction_type']), trim($post['transId']), trim($provider_code), 
																	trim($post['fdate']), trim($post['tdate']), trim($post['status']), trim($post['numbPage']), 
																	trim($post['pageSize']), trim($post['transferType']), $requestTransId,9);


				
				if($result)
				{
					$data['listTrans'] = $result->listTrans;
					$data['totalTrans'] = $result->totalTrans;
					$data['totalAmount'] = number_format($result->totalAmount);
					$config['total_rows'] = $result->totalTrans;
				}
				
				if(isset($post['excel'])){


							//load Excel template file
		        			$objTpl = PHPExcel_IOFactory::load(dirname(APPPATH)."/assets/template/BM_Bao_Cao_Lich_Su_Nap_Dien_Thoai_MGV.xlsx");

		        			$result2 = $this->megav_core_interface->getTransList($uname, 
																			trim($post['transaction_type']), trim($post['transId']), trim($post['provider_code']), 
																			trim($post['fdate']), trim($post['tdate']), trim($post['status']), 0, 0, trim($post['transferType']), $requestTransId,9);
		        			if ($result2) {

		        				//echo "<pre>";print_r($result2);die;

				        				$listTrans_excel = $result2->listTrans;
										$totalTrans_excel = $result2->totalTrans;
										$totalAmount_excel = number_format($result2->totalAmount);
										$total_rows_excel = $result2->totalTrans;

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
							             
							            //set gia tri cho cac cot du lieu
							            $objTpl->setActiveSheetIndex(0)->mergeCells('A1:J1')->setCellValue('A1', 'LỊCH SỬ NẠP ĐIỆN THOẠI'.PHP_EOL.' Từ ngày '.$this->input->post('fdate').' đến ngày '.$this->input->post('tdate'));
							            $objTpl->setActiveSheetIndex(0)->mergeCells('A2:J2')->setCellValue('A2', ' Tổng số giao dịch: '.$totalTrans_excel.' - Tổng tiền thanh toán (đ): '.$totalAmount_excel);
							            $i = 4;
							            foreach ($listTrans_excel as $row){
								            $status_excel = '';
								            switch($row->status){
												case '00': 
												$status_excel = 'Thành công';
												break;
												case '99':
												$status_excel ='Chờ xử lý';
												break;
												default : 
												$status_excel = 'Thất bại';
												break;
											}

								            $objTpl->setActiveSheetIndex(0)
								            ->setCellValue('A'.$i, $row->requestId)
								            ->setCellValue('B'.$i, $row->target)
								            ->setCellValue('C'.$i, $row->amount)
								            ->setCellValue('D'.$i, $row->discountAmount)
								            ->setCellValue('E'.$i, $row->realMinus)
								            ->setCellValue('F'.$i, date("d/m/Y H:i:s", strtotime($row->createdDate)))
								            ->setCellValue('G'.$i, $status_excel);
								            $objTpl->getActiveSheet(0)->getStyle('A'.$i)->applyFromArray($styleArray);
								            $objTpl->getActiveSheet(0)->getStyle('B'.$i)->applyFromArray($styleArray);
								            $objTpl->getActiveSheet(0)->getStyle('C'.$i)->applyFromArray($styleArray);
								            $objTpl->getActiveSheet(0)->getStyle('D'.$i)->applyFromArray($styleArray);
								            $objTpl->getActiveSheet(0)->getStyle('E'.$i)->applyFromArray($styleArray);
								            $objTpl->getActiveSheet(0)->getStyle('F'.$i)->applyFromArray($styleArray);
								            $objTpl->getActiveSheet(0)->getStyle('G'.$i)->applyFromArray($styleArray);
								            $objTpl->setActiveSheetIndex(0)->getStyle('C'.$i)->getNumberFormat()->setFormatCode('#,###');
								            $objTpl->setActiveSheetIndex(0)->getStyle('D'.$i)->getNumberFormat()->setFormatCode('#,###');
								            $objTpl->setActiveSheetIndex(0)->getStyle('E'.$i)->getNumberFormat()->setFormatCode('#,###');
								            $i++;
							            
							            }




							            //prepare download
							            $filename=' Bao_Cao_Lich_Su_Nap_Dien_Thoai_MGV_'.date('d_m_Y').'.xls'; //just some random filename
							            header('Content-Type: application/vnd.ms-excel');
							            header('Content-Disposition: attachment;filename="'.$filename.'"');
							            header('Cache-Control: max-age=0');
							            //ghi du lieu vao file,định dạng file excel 2007
							            $objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
							            $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!

							    }



					
				}
			}
		}
		
		$transId = $this->megav_libs->genarateTransactionId('GBA');
		$listBank = $this->megav_core_interface->getProvider($transId);
		if($listBank)
			$data['listBank'] = $listBank;
		
		$this->pagination->initialize($config);
        $data['paginationLinks'] = $this->pagination->create_links();
		
		$dataMenu['userinfo'] = array('userName' => $this->session_memcached->userdata['info_user']['userID'],
										'balance' => $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																											$this->megav_libs->genarateAccessToken()));
		
		$this->view['content'] = $this->load->view('trans_history/index', $data);
		/*
		$this->view['content'] = $this->load->view('trans_history/index', $data, true);
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', $dataMenu, true),
			'user_info' => $this->session_memcached->userdata['info_user']
		));
		*/
	}
	
	public function topup()
	{
		$post = $this->input->post();
		$data = array();
		$data['tab'] = 'topup';
		$data['totalTrans'] = 0;
		$data['totalAmount'] = 0;
		$page = !empty($post['page']) ? (int)($post['page']) : 1;
		$this->config->load('pagination', TRUE);
		$config = $this->config->item('pagination', 'pagination');
		$config["base_url"] = base_url() . "/trans_history/index";
		$config['cur_page'] = $page;
		
		if($post)
		{
			$data['after_post'] = $post;
			$this->form_validation->set_rules('transId', '', 'trim|xss_clean');
			$this->form_validation->set_rules('fdate', '', 'trim|xss_clean');
            $this->form_validation->set_rules('tdate', '', 'trim|xss_clean|callback_date_greater_than['.$post['fdate'].']');
			$this->form_validation->set_rules('provider_code', '', 'trim|xss_clean');
			$this->form_validation->set_rules('status', '', 'trim|xss_clean');
			if($this->form_validation->run() == true) 
			{
				$requestTransId = $this->megav_libs->genarateTransactionId('GLT');
				$post['transaction_type'] = 8;
				$post['numbPage'] = $page;
				$post['pageSize'] = $config['per_page'];
				$post['transferType'] = 1;
				$providerCode = "";
				if(isset($post['provider_code']))
				{
					if($post['provider_code'] == '-1')
						$providerCode = "";
					else
						$providerCode = $post['provider_code'];
				}
				if($post['status'] == '-1')
					$post['status'] = "";
				
				//$post['fdate'] = "";
				//$post['tdate'] = "";
				
				$uname = $this->session_memcached->userdata['info_user']['userID'];
				//$uname = "test1998";
				$result = $this->megav_core_interface->getTransList($uname, 
																	trim($post['transaction_type']), trim($post['transId']), trim($providerCode), 
																	trim($post['fdate']), trim($post['tdate']), trim($post['status']), trim($post['numbPage']), 
																	trim($post['pageSize']), trim($post['transferType']), $requestTransId);



				
				if($result)
				{
					$data['listTrans'] = $result->listTrans;
					$data['totalTrans'] = $result->totalTrans;
					$data['totalAmount'] = number_format($result->totalAmount);
					$config['total_rows'] = $result->totalTrans;
				}
				
				if(isset($post['excel'])){
					
				}
			}
		}
		
		$transId = $this->megav_libs->genarateTransactionId('GBA');
		$listBank = $this->megav_core_interface->getProvider($transId);
		if($listBank)
			$data['listBank'] = $listBank;
		
		$this->pagination->initialize($config);
        $data['paginationLinks'] = $this->pagination->create_links();
		
		$dataMenu['userinfo'] = array('userName' => $this->session_memcached->userdata['info_user']['userID'],
										'balance' => $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																											$this->megav_libs->genarateAccessToken()));
		
		$this->view['content'] = $this->load->view('trans_history/index', $data);
		/*
		$this->view['content'] = $this->load->view('trans_history/index', $data, true);
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', $dataMenu, true),
			'user_info' => $this->session_memcached->userdata['info_user']
		));
		*/
	}
	public function topup_detail($transId,$fdate,$tdate,$numbPage, $pageSize){
			$transType = 2;
			$transSubType = 11;

			$requestTransId = $this->megav_libs->genarateTransactionId('GLT');
			$provider_code = "";

			
			$uname = $this->session_memcached->userdata['info_user']['userID'];
			//$uname = "test1998";
			$result = $this->megav_core_interface->getTransList($uname, 8, $transId, $provider_code,$fdate,$tdate,'',$numbPage,$pageSize, $transType, $requestTransId,$transSubType);

			if($result->listTrans){
				$result_res = $result->listTrans;
				switch($result_res->status){
					case '00': 
					$status = 'Thành công';
					break;
					case '01': 
					$status = 'Thất bại';
					case '99': 
					$status ='Chờ xử lý';
					break;
					default : 
					$status = '';
					break;
				}

				$xhtml ='<div class="table-responsive table_load_history">
									<table class="table table-bordered table-hover table-striped">
										<thead>
											<tr>
												<th>Mã giao dịch</th>
												<th>Thời gian giao dịch</th>
												<th>Nhà cung cấp</th>
												<th>Mệnh giá (đ)</th>
												<th>Serial thẻ</th>
												<th>Mã thẻ</th>
												<th>Ngày hết hạn</th>
											</tr>
										</thead>
										<tbody>';
				foreach ($result_res as $key => $value) {
					$xhtml .= '<tr>
								<td>'.$transId.'</td>
								<td>'.date("d/m/Y H:i:s", strtotime($value->createdDate)).'</td>
								<td>'.$value->providerCode.'</td>
								<td>'.number_format($value->price).'</td>
								<td>'.$value->serial.'</td>
								<td>'.$value->pincode.'</td>
								<td>'.date("d/m/Y H:i:s", strtotime($value->expireDate)).'</td>
							</tr>';
				}
				$btn_next ='';
				if ($result->totalTrans>10) {
					$btn_next = '<button class="btn btn-success view_continute" data-trans-id="'.$transId.'" data-fdate="'.$fdate.'" data-tdate="'.$tdate.'" data-numb-page="2" data-page-size="'.$pageSize.'">Xem tiếp</button>';
				}
							
		        $xhtml .= '</table>
									<div class="row">
										<div class="col-xs-12 text-center pagi-data" data-trans-id="'.$transId.'" data-fdate="'.$fdate.'" data-tdate="'.$tdate.'" data-numb-page="2" data-page-size="'.$pageSize.'">
											'.$btn_next.'
										</div>
									</div>
								</div>';

				return $xhtml;
			}
	}
	public function pagi_topup_detail(){
			if (checkAjaxRequest() == FALSE){
	                redirect(base_url());
		    }
			$this->form_validation->set_rules('transId', '', 'trim|xss_clean');
			$this->form_validation->set_rules('fdate', '', 'trim|xss_clean');
            $this->form_validation->set_rules('tdate', '', 'trim|xss_clean|callback_date_greater_than['.$this->input->post('fdate').']');
			$this->form_validation->set_rules('numbPage', '', 'trim|xss_clean');
			$this->form_validation->set_rules('pageSize', '', 'trim|xss_clean');
			if($this->form_validation->run() == true){
				$transId = trim($this->input->post('transId'));
				$transId = $this->security->xss_clean($transId);
				$fdate   = $this->input->post('fdate');
				$fdate = $this->security->xss_clean($fdate);
				$tdate   = $this->input->post('tdate');
				$tdate = $this->security->xss_clean($tdate);
				$numbPage  = $this->input->post('numbPage'); 
				$numbPage = $this->security->xss_clean($numbPage);
				$pageSize   = $this->input->post('pageSize');
				$pageSize = $this->security->xss_clean($pageSize);
				$transType = 2;
				$transSubType = 11;
				$requestTransId = $this->megav_libs->genarateTransactionId('GLT');
				$provider_code = "";

				
				$uname = $this->session_memcached->userdata['info_user']['userID'];
				//$uname = "test1998";
				$result = $this->megav_core_interface->getTransList($uname, 8, $transId, $provider_code,$fdate,$tdate,'',$numbPage,$pageSize, $transType, $requestTransId,$transSubType);

				$totalPage = ceil($result->totalTrans / 5);


				if($result->listTrans){
					$result_res = $result->listTrans;
					if ($result_res->status==00) {
						$xhtml ='';
						$data = array();
						if ($numbPage<=$totalPage) {
							foreach ($result_res as $key => $value) {
								$xhtml .= '<tr>
											<td>'.$transId.'</td>
											<td>'.date("d/m/Y H:i:s", strtotime($value->createdDate)).'</td>
											<td>'.$value->providerCode.'</td>
											<td>'.number_format($value->price).'</td>
											<td>'.$value->serial.'</td>
											<td>'.$value->pincode.'</td>
											<td>'.date("d/m/Y H:i:s", strtotime($value->expireDate)).'</td>
										</tr>';
							}
							$data['none'] = false;
						}else{
							$xhtml .= '<tr class="done_data">
			                  <td colspan="7">Dữ liệu đã tải hết.</td>
							</tr>';
							$data['none'] = true;
						}
						$data['status'] = true;
						$data['html'] = $xhtml;
						
						echo json_encode($data);
					}

					
				}

			}

			
	}


	public function export_topup_detail($transId,$fdate = null,$tdate = null){
		
		$transId = trim(addslashes($transId));
		if($fdate != null)
			$fdate   = trim(addslashes($fdate));
		else
			$fdate   = trim(addslashes(date('d-m-Y')));
		if($tdate != null)
			$tdate   = trim(addslashes($tdate));
		else
			$tdate   = trim(addslashes(date('d-m-Y')));

		if (isset($transId) && $fdate && $tdate) {
				

				$transType = 2;
				$transSubType =11;

				$requestTransId = $this->megav_libs->genarateTransactionId('GLT');
				$provider_code = "";

				
				$uname = $this->session_memcached->userdata['info_user']['userID'];
				//$uname = "test1998";
				$result = $this->megav_core_interface->getTransList($uname, 8, $transId, $provider_code,$fdate,$tdate,'',0,0, $transType, $requestTransId,$transSubType);



				if($result->listTrans){
					$result_res = $result->listTrans;
						if ($result_res->status==00) {

									if (!empty($result_res)) {

										//load Excel template file
										log_message('error', 'load file: ' . dirname(APPPATH)."/assets/template/BM_Bao_Cao_Lich_Su_Mua_Ma_The_MGV.xlsx");
		        			            $objTpl = PHPExcel_IOFactory::load(dirname(APPPATH)."/assets/template/BM_Bao_Cao_Lich_Su_Mua_Ma_The_MGV.xlsx");

				        				$listTrans_excel = $result->listTrans;
										$totalTrans_excel = $result->totalTrans;
										$totalAmount_excel = number_format($result->totalAmount);
										$total_rows_excel = $result->totalTrans;

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
							             
							            //set gia tri cho cac cot du lieu
							            $objTpl->setActiveSheetIndex(0)->mergeCells('A1:G1')->setCellValue('A1', 'LỊCH SỬ MUA MÃ THẺ'.PHP_EOL.' Từ ngày '.$fdate.' đến ngày '.$tdate);
							            $objTpl->setActiveSheetIndex(0)->mergeCells('A2:J2')->setCellValue('A2', ' Tổng số thẻ: '.$totalTrans_excel.' - Tổng tiền (đ): '.$totalAmount_excel);
							            $i = 4;
							            foreach ($listTrans_excel as $row){
								            $objTpl->setActiveSheetIndex(0)
								            ->setCellValue('A'.$i, $transId)
								            ->setCellValue('B'.$i, date("d/m/Y H:i:s", strtotime($row->createdDate)))
								            ->setCellValue('C'.$i, $row->providerCode)
								            ->setCellValue('D'.$i, $row->price)
								            ->setCellValue('E'.$i, ''.$row->serial.'')
								            ->setCellValue('F'.$i, ''.$row->pincode.'')
								            ->setCellValue('G'.$i, date("d/m/Y H:i:s", strtotime($row->expireDate)));
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
					
				}


		}

	}
	
	public function paymentgame()
	{
		$post = $this->input->post();
		$data = array();
		$data['tab'] = 'paymentgame';
		$data['totalTrans'] = 0;
		$data['totalAmount'] = 0;
		$page = !empty($post['page']) ? (int)($post['page']) : 1;
		$this->config->load('pagination', TRUE);
		$config = $this->config->item('pagination', 'pagination');
		$config["base_url"] = base_url() . "/trans_history/index";
		$config['cur_page'] = $page;
		
		
		if($post)
		{

			$this->form_validation->set_rules('transId', '', 'trim|xss_clean');
			$this->form_validation->set_rules('fdate', '', 'trim|xss_clean');
            $this->form_validation->set_rules('tdate', '', 'trim|xss_clean|callback_date_greater_than['.$post['fdate'].']');
			$this->form_validation->set_rules('provider_code', '', 'trim|xss_clean');
			$this->form_validation->set_rules('status', '', 'trim|xss_clean');
			if($this->form_validation->run() == true) 
			{
				$requestTransId = $this->megav_libs->genarateTransactionId('GLT');
				$post['transaction_type'] = 6;
				$post['numbPage'] = $page;
				$post['pageSize'] = $config['per_page'];
				$post['transferType'] = 1;
				if($post['provider_code'] == '-1')
					$post['provider_code'] = "";
				if($post['status'] == '-1')
					$post['status'] = "";
				
				
				$uname = $this->session_memcached->userdata['info_user']['userID'];
				//$uname = "test1998";
				$result = $this->megav_core_interface->getTransList($uname, 
																	trim($post['transaction_type']), trim($post['transId']), trim($post['provider_code']), 
																	trim($post['fdate']), trim($post['tdate']), trim($post['status']), trim($post['numbPage']), 
																	trim($post['pageSize']), trim($post['transferType']), $requestTransId,10);
				
				if($result)
				{
					
					$data['listTrans'] = $result->listTrans;
					$data['totalTrans'] = $result->totalTrans;
					$data['totalAmount'] = number_format($result->totalAmount);
					$config['total_rows'] = $result->totalTrans;
				}
				
				if(isset($post['excel']))
				{
							//load Excel template file
		        			$objTpl = PHPExcel_IOFactory::load(dirname(APPPATH)."/assets/template/BM_Bao_Cao_Lich_Su_Nap_Game_MGV.xlsx");

		        			$result2 = $this->megav_core_interface->getTransList($uname, 
																			trim($post['transaction_type']), trim($post['transId']), trim($post['provider_code']), 
																			trim($post['fdate']), trim($post['tdate']), trim($post['status']), 0, 0, trim($post['transferType']), $requestTransId,10);
		        			if ($result2) {

		        				//echo "<pre>";print_r($result2);die;

				        				$listTrans_excel = $result2->listTrans;
										$totalTrans_excel = $result2->totalTrans;
										$totalAmount_excel = number_format($result2->totalAmount);
										$total_rows_excel = $result2->totalTrans;

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
							             
							            //set gia tri cho cac cot du lieu
							            $objTpl->setActiveSheetIndex(0)->mergeCells('A1:J1')->setCellValue('A1', 'LỊCH SỬ NẠP TIỀN GAME'.PHP_EOL.' Từ ngày '.$this->input->post('fdate').' đến ngày '.$this->input->post('tdate'));
							            $objTpl->setActiveSheetIndex(0)->mergeCells('A2:J2')->setCellValue('A2', ' Tổng số giao dịch: '.$totalTrans_excel.' - Tổng tiền nạp game (đ): '.$totalAmount_excel);
							            $i = 4;
							            foreach ($listTrans_excel as $row){
								            $status_excel = '';
								            switch($row->status){
												case '00': 
												$status_excel = 'Thành công';
												break;
												case '99':
												$status_excel ='Chờ xử lý';
												break;
												default : 
												$status_excel = 'Thất bại';
												break;
											}

								            $objTpl->setActiveSheetIndex(0)
								            ->setCellValue('A'.$i, $row->requestId)
								            ->setCellValue('B'.$i, $row->target)
								            ->setCellValue('C'.$i, $row->providerCode)
								            ->setCellValue('D'.$i, $row->amount)
								            ->setCellValue('E'.$i, $row->discountAmount)
								            ->setCellValue('F'.$i, $row->realMinus)
								            ->setCellValue('G'.$i, date("d/m/Y H:i:s", strtotime($row->createdDate)))
								            ->setCellValue('H'.$i, $status_excel);
								            $objTpl->getActiveSheet(0)->getStyle('A'.$i)->applyFromArray($styleArray);
								            $objTpl->getActiveSheet(0)->getStyle('B'.$i)->applyFromArray($styleArray);
								            $objTpl->getActiveSheet(0)->getStyle('C'.$i)->applyFromArray($styleArray);
								            $objTpl->getActiveSheet(0)->getStyle('D'.$i)->applyFromArray($styleArray);
								            $objTpl->getActiveSheet(0)->getStyle('E'.$i)->applyFromArray($styleArray);
								            $objTpl->getActiveSheet(0)->getStyle('F'.$i)->applyFromArray($styleArray);
								            $objTpl->getActiveSheet(0)->getStyle('G'.$i)->applyFromArray($styleArray);
								            $objTpl->getActiveSheet(0)->getStyle('H'.$i)->applyFromArray($styleArray);
								            $objTpl->setActiveSheetIndex(0)->getStyle('D'.$i)->getNumberFormat()->setFormatCode('#,###');
								            $objTpl->setActiveSheetIndex(0)->getStyle('E'.$i)->getNumberFormat()->setFormatCode('#,###');
								            $objTpl->setActiveSheetIndex(0)->getStyle('F'.$i)->getNumberFormat()->setFormatCode('#,###');
								            $i++;
							            
							            }




							            //prepare download
							            $filename=' Bao_Cao_Lich_Su_Nap_Game_MGV_'.date('d_m_Y').'.xls'; //just some random filename
							            header('Content-Type: application/vnd.ms-excel');
							            header('Content-Disposition: attachment;filename="'.$filename.'"');
							            header('Cache-Control: max-age=0');
							            //ghi du lieu vao file,định dạng file excel 2007
							            $objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
							            $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!

							    }
				}
			}
		}
		
		$transId = $this->megav_libs->genarateTransactionId('GBA');
		$transIdGame = $this->megav_libs->genarateTransactionId('GLT');
		$listTelco = $this->megav_core_interface->getProvider($transIdGame);
		if($listTelco)
			$data['listTelco'] = $listTelco;
		$listBank = $this->megav_core_interface->getProvider($transId);
		if($listBank)
			$data['listBank'] = $listBank;
		
		$this->pagination->initialize($config);
        $data['paginationLinks'] = $this->pagination->create_links();
		
		$dataMenu['userinfo'] = array('userName' => $this->session_memcached->userdata['info_user']['userID'],
										'balance' => $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																											$this->megav_libs->genarateAccessToken()));
		
		$this->view['content'] = $this->load->view('trans_history/index', $data);
		/*
		$this->view['content'] = $this->load->view('trans_history/index', $data, true);
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', $dataMenu, true),
			'user_info' => $this->session_memcached->userdata['info_user']
		));
		*/
	}
	
	public function paymentbills()
	{
		log_message('error', 'post to payment');
		$post = $this->input->post();
		$data = array();
		$data['tab'] = 'paymentbills';
		$data['totalTrans'] = 0;
		$data['totalAmount'] = 0;
		$page = !empty($post['page']) ? (int)($post['page']) : 1;
		$this->config->load('pagination', TRUE);
		$config = $this->config->item('pagination', 'pagination');
		$config["base_url"] = base_url() . "/trans_history/index";
		$config['cur_page'] = $page;
		if($post)
		{
			$data['after_post'] = $post;
			$this->form_validation->set_rules('transId', '', 'trim|xss_clean');
			$this->form_validation->set_rules('epurse_trans_id', '', 'trim|xss_clean');
			$this->form_validation->set_rules('fdate', '', 'trim|xss_clean');
            $this->form_validation->set_rules('tdate', '', 'trim|xss_clean|callback_date_greater_than['.$post['fdate'].']');
			$this->form_validation->set_rules('provider_code', '', 'trim|xss_clean');
			$this->form_validation->set_rules('payment_type', '', 'trim|xss_clean');
			$this->form_validation->set_rules('status', '', 'trim|xss_clean');
			if($this->form_validation->run() == true) 
			{
                $post = array_map('trim', $post);
				$requestTransId = $this->megav_libs->genarateTransactionId('GLT');
				$post['transaction_type'] = 5;
				$post['numbPage'] = $page;
				$post['pageSize'] = $config['per_page'];
				$post['transferType'] = 1;
				$provider_code = "";
				if(isset($post['provider_code']))
				{
					if($post['provider_code'] == '-1')
						$provider_code = "";
					else
						$provider_code = $post['provider_code'];
				}
				if($post['status'] == '-1')
					$post['status'] = "";
				
				$transSubType = ($post['payment_type']==0) ? '' : $post['payment_type'];
				
				$uname = $this->session_memcached->userdata['info_user']['userID'];
				//echo $post['epurse_trans_id'];die;
				//$uname = "test1998";
				$result = $this->megav_core_interface->getTransList($uname, 
																	$post['transaction_type'], $post['epurse_trans_id'], $provider_code, 
																	$post['fdate'], $post['tdate'], $post['status'], $post['numbPage'], 
																	$post['pageSize'], $post['transferType'], $requestTransId, $transSubType, $post['transId']);

				if($result)
				{
					$data['listTrans'] = $result->listTrans;
					$data['totalTrans'] = $result->totalTrans;
					$data['totalAmount'] = number_format($result->totalAmount);
					$config['total_rows'] = $result->totalTrans;
				}

                if (isset($post['excel'])) {
                    //load Excel template file
                    $objTpl = PHPExcel_IOFactory::load(dirname(APPPATH)."/assets/template/BM_Bao_Cao_Lich_Su_Thanh_Toan_MGV.xlsx");

                    $result2 = $this->megav_core_interface->getTransList($uname,
                        $post['transaction_type'],$post['epurse_trans_id'], $post['provider_code'],
                        $post['fdate'], $post['tdate'], $post['status'], 0, 0, $post['transferType'], $requestTransId, $transSubType,  $post['transId']);
                    if ($result2) {
                        $listTrans_excel = $result2->listTrans;
                        $totalTrans_excel = $result2->totalTrans;
                        $totalAmount_excel = number_format($result2->totalAmount);

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

                        //set gia tri cho cac cot du lieu
                        $objTpl->setActiveSheetIndex(0)->mergeCells('A1:H1')->setCellValue('A1', 'LỊCH SỬ THANH TOÁN'.PHP_EOL.' Từ ngày '.$this->input->post('fdate').' đến ngày '.$this->input->post('tdate'));
                        $objTpl->setActiveSheetIndex(0)->mergeCells('A2:H2')->setCellValue('A2', ' Tổng số giao dịch: '.$totalTrans_excel.' - Tổng tiền giao dịch thành công (đ): '.$totalAmount_excel);
                        $i = 4;
                        foreach ($listTrans_excel as $row){
                            switch($row->status){
                                case '00':
                                    $status_excel = 'Thành công';
                                    break;
                                case '99':
                                    $status_excel ='Chờ xử lý';
                                    break;
                                case '97':
                                    $status_excel ='Bị hủy';
                                    break;
                                default :
                                    $status_excel = 'Thất bại';
                                    break;
                            }
                            $paymentType = "";
                            if (isset($row->transType) && $row->transType=='13') {
                                $paymentType = "Thanh toán hóa đơn";
                            }elseif (isset($row->transType) && $row->transType=='12') {
                                $paymentType = "EC-Merchant";
                            }elseif (isset($row->transType) && $row->transType=='10') {
                                $paymentType = "Nạp game";
                            }
                            elseif (isset($row->transType) && $row->transType=='11') {
                                $paymentType = "Mua mã thẻ";
                            }

                            $objTpl->setActiveSheetIndex(0)
                                ->setCellValue('A'.$i, $row->requestId)
                                ->setCellValue('B'.$i, $row->contractNo)
                                ->setCellValue('C'.$i, $row->coreTransid)
                                ->setCellValue('D'.$i, $row->providerCode)
                                ->setCellValue('E'.$i, $row->amount)
                                ->setCellValue('F'.$i, $paymentType)
                                ->setCellValue('G'.$i, $row->remark)
                                ->setCellValue('H'.$i, date("d/m/Y H:i:s", strtotime($row->createdDate)))
                                ->setCellValue('I'.$i, $status_excel);
                            $objTpl->getActiveSheet(0)->getStyle('A'.$i)->applyFromArray($styleArray);
							$objTpl->getActiveSheet(0)->getStyle('B'.$i)->applyFromArray($styleArray);
                            $objTpl->getActiveSheet(0)->getStyle('C'.$i)->applyFromArray($styleArray);
                            $objTpl->getActiveSheet(0)->getStyle('D'.$i)->applyFromArray($styleArray);
                            $objTpl->getActiveSheet(0)->getStyle('E'.$i)->applyFromArray($styleArray);
                            $objTpl->getActiveSheet(0)->getStyle('F'.$i)->applyFromArray($styleArray);
                            $objTpl->getActiveSheet(0)->getStyle('G'.$i)->applyFromArray($styleArray);
                            $objTpl->getActiveSheet(0)->getStyle('H'.$i)->applyFromArray($styleArray);
                            $objTpl->getActiveSheet(0)->getStyle('I'.$i)->applyFromArray($styleArray);
                            $objTpl->setActiveSheetIndex(0)->getStyle('E'.$i)->getNumberFormat()->setFormatCode('#,###');
                            $i++;

                        }

                        //prepare download
                        $filename=' Bao_Cao_Lich_Su_Thanh_Toan_MGV_'.date('d_m_Y').'.xls'; //just some random filename
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="'.$filename.'"');
                        header('Cache-Control: max-age=0');
                        //ghi du lieu vao file,định dạng file excel 2007
                        $objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
                        $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!

                    }
                }
			}
		}
		
		$transId = $this->megav_libs->genarateTransactionId('GBA');
		$listBank = $this->megav_core_interface->getProvider($transId);
		if($listBank)
			$data['listBank'] = $listBank;
		
		$this->pagination->initialize($config);
        $data['paginationLinks'] = $this->pagination->create_links();
		
		$dataMenu['userinfo'] = array('userName' => $this->session_memcached->userdata['info_user']['userID'],
										'balance' => $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																											$this->megav_libs->genarateAccessToken()));
		
		$this->view['content'] = $this->load->view('trans_history/index', $data);
		/*
		$this->view['content'] = $this->load->view('trans_history/index', $data, true);
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', $dataMenu, true),
			'user_info' => $this->session_memcached->userdata['info_user']
		));
		*/
	}

	public function date_greater_than($tdate, $fdate)
    {
        $tdate = preg_replace('@([0-9]{2})/([0-9]{2})/([0-9]{4})@', '$3$2$1', $tdate);
        $fdate = preg_replace('@([0-9]{2})/([0-9]{2})/([0-9]{4})@', '$3$2$1', $fdate);
        if ($fdate > $tdate) {
            $this->form_validation->set_message('date_greater_than', 'Ngày bắt đầu không được phép lớn hơn ngày kết thúc');
            return false;
        }
        return true;
    }
    public function senOTPHistoryCheck(){
		// genotp
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		if (isset($_POST['check'])) {

			$reqId = trim(addslashes($this->input->post('req_id')));
			$reqId 	= $this->security->xss_clean($reqId);
			//echo $transId;die;
			$username = $this->session_memcached->userdata['info_user']['userID'];
			if ($this->megav_libs->getFlashCachedOtp($reqId,$username)==false) {
				// gửi xác nhận
				$transId = "RP2" . date("Ymd") . rand();
				if($this->session_memcached->userdata['info_user']['security_method'] == '1') {
							//echo $this->session_memcached->userdata['info_user']['userID'];die;
							$requestSendOtp = $this->megav_core_interface->genOTP($this->session_memcached->userdata['info_user']['email'],$this->session_memcached->userdata['info_user']['mobileNo'], $this->session_memcached->userdata['info_user']['userID'], $transId);

							if($requestSendOtp){
										$response = json_decode($requestSendOtp);
										log_message('error', 'respone: ' . print_r($requestSendOtp, true));
										if(isset($response->status))
										{
											if($response->status == '00')
											{
												// gui OTP thanh cong
												$data['sentOtp'] = 1;

												$data = array(
													'status' => 1,
													'otp_pass' => 1,
													'mess'   => 'Hệ thống đã gửi OTP đến số điện thoại của bạn.',
													'phone'	 => substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 0, (strlen($this->session_memcached->userdata['info_user']['mobileNo']) - 4)),
													'transId' => $transId
												);

												echo json_encode($data);
												
												$redis = new CI_Redis();
												$dataReset = array('sec_met' => $post['sec_met'],'transid' => $transId);
												$redis->set($transId, json_encode($dataReset));
												unset($dataReset);
												
												$session = $this->input->cookie("megav_session");
												$session = $this->megav_libs->_unserialize($session);
												$session['user_data'] = $transId;
												$this->session_memcached->_set_cookie($session);
												unset($session);
											}
											else
											{
												$data = array(
													'status' => 0,
													'mess'   => 'Gửi OTP thất bại.'
												);

												echo json_encode($data);
											}
										}
										else
										{
											$mess = "Có lỗi trong quá trình gửi OTP. Vui lòng thử lại.";
											$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
											$data = array(
												'status' => 0,
												'mess'   => $mess
											);

											echo json_encode($data);
										}
									}
									else
									{					
										$mess = "Hệ thống MegaV đang bận. Vui lòng thử lại sau.";
										$data = array(
											'status' => 0,
											'mess'   => $mess
										);

										echo json_encode($data);
									}
				}else{
					$data = array(
						'status' => 1,
						'otp_pass' => 2,
						'mess'   => 'Nhập mật khẩu cấp 2.',
						'transId' => $transId
					);

					echo json_encode($data);
				}
			}else{

				$transId = trim(addslashes($this->input->post('req_id')));
				$transId = $this->security->xss_clean($transId);
				$fdate = trim(addslashes($this->input->post('fdate')));
				$fdate = $this->security->xss_clean($fdate);
				$tdate = trim(addslashes($this->input->post('tdate')));
				$tdate = $this->security->xss_clean($tdate);
				$numbPage = 1;
				$pageSize = 5;
				$html = $this->topup_detail($transId,$fdate,$tdate,$numbPage, $pageSize);
				$data = array(
					'status' => 3,
					'mess'   => 'Lấy chi tiết thành công.',
					'html'   => $html
				);
                echo json_encode($data);
			}

		}




				
				
	
		
	}
	public function sendConfirmOtpViewDetail(){
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		$dataTrans = $this->input->post('data');
		$dataTrans['req_id'] 	= $this->security->xss_clean($dataTrans['req_id']);
		$dataTrans['fdate'] 	= $this->security->xss_clean($dataTrans['fdate']);
		$dataTrans['tdate'] 	= $this->security->xss_clean($dataTrans['tdate']);
		$dataTrans['transId'] 	= $this->security->xss_clean($dataTrans['transId']);
		if (!empty($dataTrans)) {
			if($this->session_memcached->userdata['info_user']['security_method'] == '1') {
				$result = $this->megav_core_interface->validOtp($this->session_memcached->userdata['info_user']['email'],$this->session_memcached->userdata['info_user']['mobileNo'], $this->session_memcached->userdata['info_user']['userID'], $dataTrans['otp_lv'], $dataTrans['transId']);
				
			}else{
				$result = $this->megav_core_interface->validPassLv2($this->session_memcached->userdata['info_user']['userID'], $dataTrans['pass_lv'], $dataTrans['transId']);
			}
			$data = json_decode($result);
			if ($data->status==00) {

				$html = $this->topup_detail($dataTrans['req_id'],$dataTrans['fdate'],$dataTrans['tdate'],1, 5);
				$data_mess = array(
					'status'=> true,
					'mess'	=> 'Thành công.',
					'html'  => $html
				);
				// update là đã check xác nhân trong ngày
				$this->megav_libs->saveFlashCachedOtp($dataTrans['req_id'],$this->session_memcached->userdata['info_user']['userID']);
			}else{
				$data_mess = array(
					'status'=> false
				);
				if($this->session_memcached->userdata['info_user']['security_method'] == '1') {
					$data_mess['mess'] = 'Nhập sai otp.';
				}else{
					$data_mess['mess'] = 'Nhập sai mật khẩu cấp 2.';
				}
			}
			echo json_encode($data_mess);
		}
		
	}

	public function _unserialize($data)
	{
		$data = @unserialize(strip_slashes($data));
        if (is_array($data))
        {
            foreach ($data as $key => $val)
            {
                if (is_string($val))
                {
                    $data[$key] = str_replace('{{slash}}', '\\', $val);
                }
            }

            return $data;
        }

        return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
    }
		
}
?>