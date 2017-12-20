<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class balance_change extends CI_Controller
{
    const EC_MERCHANT_TEMP = 1;
    const DEPOSIT_IBK_TEMP = 3;
    const DEPOSIT_BANK_MAPPING_TEMP = 6;
    const WIDTHDRAW_TEMP = 4;
    const WIDTHDRAW_MAPPING_TEMP = 5;
    const WIDTHDRAW_FAST_TEMP = 11;
    const TRANSFER_LOCAL_TEMP = 2;
    const TRANSFER_TO_SERVICE_TEMP = 7;
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

    
	public function index()
	{
		

		
		log_message('error', 'post to payment');
		$post = $this->input->post();
		$data = array();
		$data['totalTrans'] = 0;
		$data['totalAmount'] = 0;
		$page = !empty($post['page']) ? (int)($post['page']) : 1;
		$this->config->load('pagination', TRUE);
		$config = $this->config->item('pagination', 'pagination');
		$config["base_url"] = base_url() . "/balance_change/index";
		$config['cur_page'] = $page;
		$transId = $this->megav_libs->genarateTransactionId('GBA');


		
		$dataMenu['userinfo'] = array('userName' => $this->session_memcached->userdata['info_user']['userID'],
										'balance' => $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																											$this->megav_libs->genarateAccessToken()));


		$data['type_deal'] = array(

			'1' => 'Nạp tiền',
			'2' => 'Rút tiền',
			'3' => 'Chuyển tiền',
			'4' => 'Trừ tiền',
			'5' => 'Thanh toán hóa đơn & EC-merchant',
			'6' => 'Nạp tiền game',
			'7' => 'Nạp tiền điện thoại',
			'8' => 'Mua mã thẻ',

			'14' => 'Hoàn tiền thanh toán hóa đơn thất bại',
			'15' => 'Hoàn tiền thanh toán hóa đơn(hủy thanh toán)',
			'16' => 'Hoàn tiền thanh toán EC-merchant thất bại',
			'17' => 'Hoàn tiền rút tiền thất bại',
			'18' => 'Hoàn tiền chuyển tiền thất bại',
			'19' => 'Hoàn tiền nạp tiền điện thoại, nạp game thất bại',
			'20' => 'Hoàn tiền mua mã thẻ thất bại'
		);
		
		$uname = $this->session_memcached->userdata['info_user']['userID'];




		if($post){

            $data['after_post'] = $post;
			$this->form_validation->set_rules('transId', '', 'trim|xss_clean');
			$this->form_validation->set_rules('fdate', '', 'trim|xss_clean');
			$this->form_validation->set_rules('tdate', '', 'trim|xss_clean|callback_date_greater_than['.$post['fdate'].']');
			$this->form_validation->set_rules('type_code', '', 'trim|xss_clean');
			$this->form_validation->set_rules('status', '', 'trim|xss_clean');
			if($this->form_validation->run() == true) {
				$post = array_map('trim', $post);


				//$requestTransId = "GLT" . date("Ymd") . rand();
				$post['numbPage'] = $page;
				$post['pageSize'] = $config['per_page'];

				
				//$uname = "test1998";
				$result = $this->megav_core_interface->getBalanceChange($uname, 
																	trim($post['type_code']), trim($post['transId']),trim($post['fdate']), trim($post['tdate']), trim($post['status']), trim($post['numbPage']), trim($post['pageSize']));

				/*echo "<pre>";
				print_r($result);die;*/
				

				
				if($result){
					$data['listTrans'] = $result->listTrans;
					$data['totalTrans'] = $result->totalTrans;
					$data['totalAmount'] = number_format($result->totalAmount);
					$config['total_rows'] = $result->totalTrans;
					$data['text_amount_total'] = '';
					if ($post['type_code']!='0'){
							if ($post['status']=='00') {
								$data['text_amount_total'] = '<span class="total-fix-responsive">Tổng tiền giao dịch thành công: <span class="amount-his">'.$data['totalAmount'].' vnđ</span>';
							}elseif ($post['status']=='01') {
								$data['text_amount_total'] = '<span class="total-fix-responsive">Tổng tiền giao dịch thất bại: <span class="amount-his">'.$data['totalAmount'].' vnđ</span>';
							}elseif ($post['status']=='99') {
								$data['text_amount_total'] = '<span class="total-fix-responsive">Tổng tiền giao dịch chờ xử lý: <span class="amount-his">'.$data['totalAmount'].' vnđ</span>';
							}else{
									$data['text_amount_total'] = '<span class="total-fix-responsive">Tổng tiền giao dịch thành công: <span class="amount-his">'.$data['totalAmount'].' vnđ</span>';
							}
					}
					

				}
				
				if(isset($post['excel'])){
					//load Excel template file
        			$objTpl = PHPExcel_IOFactory::load(dirname(APPPATH)."/assets/template/BM_Bao_Cao_Bien_Dong_So_Du_MGV.xlsx");

        			$result2 = $this->megav_core_interface->getBalanceChange($uname, 
																	trim($post['type_code']), trim($post['transId']),trim($post['fdate']), trim($post['tdate']), trim($post['status']), 0, 0);
        			if ($result2) {
		        				$listTrans_excel = $result2->listTrans;
								$totalTrans_excel = $result2->totalTrans;
								$total_rows_excel = $result2->totalTrans;
								$totalAmount_excel ='.';
								if ($post['type_code']!='0'){
									if ($post['status']=='00') {
										$totalAmount_excel = ' - Tổng tiền giao dịch thành công (đ): '.number_format($result2->totalAmount).'.';
									}elseif ($post['status']=='01') {
										$totalAmount_excel = ' - Tổng tiền giao dịch thất bại (đ): '.number_format($result2->totalAmount).'.';
									}elseif ($post['status']=='99') {
										$totalAmount_excel = ' - Tổng tiền giao dịch chờ xử lý (đ): '.number_format($result2->totalAmount).'.';
									}else{
										$totalAmount_excel = ' - Tổng tiền giao dịch thành công (đ): '.number_format($result2->totalAmount).'.';
									}
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
					            $objTpl->setActiveSheetIndex(0)->mergeCells('A1:F1')->setCellValue('A1', 'BIẾN ĐỘNG SỐ DƯ'.PHP_EOL.'Từ ngày '.$this->input->post('fdate').' đến ngày '.$this->input->post('tdate'));
					            $objTpl->setActiveSheetIndex(0)->mergeCells('A2:F2')->setCellValue('A2', ' Tổng số giao dịch: '.$totalTrans_excel.$totalAmount_excel);
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
									$trans_type = '';
									foreach ($data['type_deal'] as $key => $value) {
										if ($key==$row->transType) {
											$trans_type = $value;
										}
									}

						            $objTpl->setActiveSheetIndex(0)
						            ->setCellValue('A'.$i, $row->originalReqId)
						            ->setCellValue('B'.$i, $trans_type)
						            ->setCellValue('C'.$i, (isset($row->createdDate)) ? date('d/m/Y H:i:s', strtotime($row->createdDate)) : "")
						            ->setCellValue('D'.$i, $row->amount)
						            ->setCellValue('E'.$i, $row->balAfter)
						            ->setCellValue('F'.$i, $status_excel);
						            $objTpl->getActiveSheet(0)->getStyle('A'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('B'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('C'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('D'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('E'.$i)->applyFromArray($styleArray);
						            $objTpl->getActiveSheet(0)->getStyle('F'.$i)->applyFromArray($styleArray);
						            $objTpl->setActiveSheetIndex(0)->getStyle('D'.$i)->getNumberFormat()->setFormatCode('#,###');
						            $objTpl->setActiveSheetIndex(0)->getStyle('E'.$i)->getNumberFormat()->setFormatCode('#,###');
						            $i++;
					            
					            }




					            //prepare download
					            $filename='BM_Bao_Cao_Bien_Dong_So_Du_MGV'.date('d_m_Y').'.xls'; //just some random filename
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
		$this->pagination->initialize($config);
        $data['paginationLinks'] = $this->pagination->create_links();
		
		
		
		
		
		$this->load->view('balance_change/index', $data);
		/*
		$this->view['content'] = $this->load->view('trans_history/index', $data, true);
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', $dataMenu, true),
			'user_info' => $this->session_memcached->userdata['info_user']
		));
		*/
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