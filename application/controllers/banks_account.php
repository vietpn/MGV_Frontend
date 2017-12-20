<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class banks_account extends CI_Controller
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
		$transId = "GBA" . date("Ymd") . rand();
		$listBank = $this->megav_core_interface->getListBankAccount($this->session_memcached->userdata['info_user']['email'],
																	$this->session_memcached->userdata['info_user']['mobileNo'],
																	$this->session_memcached->userdata['info_user']['userID'], $transId);
		
		if($listBank)
			$data['listBankAcc'] = $listBank;
		$this->load->view('bank_account/index', $data);
		/*
		$this->view['content'] = $this->load->view('bank_account/index', $data, true);
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
		));
		*/
	}
	
	public function createBankAccount()
	{
		$data = array();
		$post = $this->input->post();
		if($post)
		{
			$this->form_validation->set_rules('bank_code', 'Ngân hàng', 'required|trim|xss_clean');
			$this->form_validation->set_rules('bank_account_name', 'Tên tài khoản', 'max_length[255]|min_length[6]|required|trim|xss_clean');
			$this->form_validation->set_rules('bank_account', 'Số tài khoản', 'max_length[30]|min_length[8]|alpha_numeric|required|trim|xss_clean');
			$this->form_validation->set_rules('province_code', 'Tỉnh thành', 'required|trim|xss_clean');
			$this->form_validation->set_rules('bank_branch', 'Chi nhánh', 'max_length[255]|required|trim|xss_clean');
			if($this->form_validation->run() == true) 
			{
				$transId = "IBA" . date("Ymd") . rand();
				$requestInsertBank = $this->megav_core_interface->insertBankAccount($this->session_memcached->userdata['info_user']['userID'],
																					$post['bank_code'], $post['bank_account_name'], 
																					$post['bank_account'], $post['province_code'],  
																					$post['bank_branch'], $transId);
				if($requestInsertBank)
				{
					$response = json_decode($requestInsertBank);
					log_message('error', 'respone: ' . print_r($requestInsertBank, true));
					if(isset($response->status))
					{
						if($response->status == '00')
						{
							$error = 1;
							$this->megav_libs->page_result("Thêm thông tin ngân hàng thành công", '/banks_account', null, null, 1);
							delete_cookie("addBank");
							$arrUserinfo = $this->session_memcached->userdata['info_user'];
							$arrUserinfo['countUserbankAcc'] += 1;
							$this->session_memcached->set_userdata('info_user', $arrUserinfo);
						}
						else
						{
							$error = 1;
							$this->megav_libs->page_result(lang('MVM_'.$response->status), '/banks_account');
						}
					}
					else
					{
						$error = 1;
						$this->megav_libs->page_result("Có lỗi trong quá trình thêm mới thông tin ngân hàng. Vui lòng thử lại.", '/banks_account');
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
			$transId = $this->megav_libs->genarateTransactionId('GLP');
			$listBank = $this->megav_core_interface->getProvider($transId, SUB_DEPOSIT_BANK_REDIRECT);
			if($listBank)
				$data['listBank'] = $listBank;
			
			$listProvince = $this->megav_core_interface->getProvince($transId);
			
			if($listProvince)
				$data['listProvince'] = $listProvince;
			
			$this->load->view('bank_account/create', $data);
			/*
			$this->view['content'] = $this->load->view('bank_account/create', $data, true);
			$this->load->view('Layout/layout_info', array(
				'data' => $this->view,
				'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
			));
			*/
		}
	}
	
	public function updateBankAccount($rowId,$bankAccount, $bankCode)
	{
		$data = array();
		$post = $this->input->post();
		if($post)
		{
			$this->form_validation->set_rules('bank_account_name', 'Tên tài khoản', 'max_length[255]|min_length[6]|required|trim|xss_clean');
			$this->form_validation->set_rules('province_code', 'Tỉnh thành', 'required|trim|xss_clean');
			$this->form_validation->set_rules('bank_account', 'Số tài khoản', 'max_length[30]|min_length[8]|alpha_numeric|required|trim|xss_clean');
			$this->form_validation->set_rules('bank_branch', 'Chi nhánh', 'max_length[255]|required|trim|xss_clean');
			
			if($this->form_validation->run() == true) 
			{
				//print_r($post);
				//$transId = "GBA" . date("Ymd") . rand();
				$transId = $this->megav_libs->genarateTransactionId('GBA');
				$requestUpdateBank = $this->megav_core_interface->updateBankAccount($this->session_memcached->userdata['info_user']['userID'],
																					trim(addslashes($post['bank_code'])), $post['bank_account_name'], 
																					$post['bank_account'], $post['province_code'],  
																					$post['bank_branch'],trim(addslashes($post['row_id'])), $transId);
				if($requestUpdateBank)
				{
					$response = json_decode($requestUpdateBank);
					log_message('error', 'respone: ' . print_r($requestUpdateBank, true));
					if(isset($response->status))
					{
						if($response->status == '00')
						{
							$error = 1;
							$this->megav_libs->page_result("Cập nhật thông tin ngân hàng thành công", '/banks_account', null, null, 1);
						}
						else
						{
							$error = 1;
							$this->megav_libs->page_result(lang('MVM_'.$response->status), '/banks_account');
						}
					}
					else
					{
						$error = 1;
						$this->megav_libs->page_result("Có lỗi trong quá trình Cập nhật thông tin ngân hàng. Vui lòng thử lại.", '/banks_account');
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
			$transId = $this->megav_libs->genarateTransactionId('GLP');
			//$transId = "GBA" . date("Ymd") . rand();
			$listBank = $this->megav_core_interface->getListBankAccount($this->session_memcached->userdata['info_user']['email'],
																	$this->session_memcached->userdata['info_user']['mobileNo'],
																	$this->session_memcached->userdata['info_user']['userID'], $transId);

			if(!$listBank)
			{
				redirect('/banks_account');
				die;
			}
			else
			{
				
				foreach($listBank as $bank)
				{
					if($rowId == $bank->rowId)
						$data['bankInfo'] = $bank;
				}
				
			}
			
			//$transId = $this->megav_libs->genarateTransactionId('GLP');
			$data['listBank'] = $this->megav_core_interface->getProvider($transId, SUB_DEPOSIT_BANK_REDIRECT);
			$data['listProvince'] = $this->megav_core_interface->getProvince($transId);
			$this->load->view('bank_account/update', $data);
			/*
			$this->view['content'] = $this->load->view('bank_account/update', $data, true);
			$this->load->view('Layout/layout_info', array(
				'data' => $this->view,
				'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
			));
			*/
		}
	}
	
	public function deleteBankAcc($rowId,$bankAccount, $bankCode)
	{
		$transId = $this->megav_libs->genarateTransactionId('DBA');
		$requestDelBank = $this->megav_core_interface->deleteBankAccount($this->session_memcached->userdata['info_user']['userID'], 
																		$bankAccount, $bankCode, $rowId, $transId);
		if($requestDelBank)
		{
			$response = json_decode($requestDelBank);
			log_message('error', 'respone: ' . print_r($requestDelBank, true));
			if(isset($response->status))
			{
				if($response->status == '00')
				{
					$error = 1;
					$this->megav_libs->page_result("Xóa thông tin ngân hàng thành công", '/banks_account', null, null, 1);
				}
				else
				{
					$error = 1;
					$this->megav_libs->page_result(lang('MVM_'.$response->status), '/banks_account');
				}
			}
			else
			{
				$error = 1;
				$this->megav_libs->page_result("Có lỗi trong quá trình xóa thông tin ngân hàng. Vui lòng thử lại.", '/banks_account');
			}
		}
		else
		{
			$error = 1;
			$this->megav_libs->page_result("Hệ thống MegaV đang bận. Vui lòng thử lại sau.", '/banks_account');
		}
	}
	
}
?>