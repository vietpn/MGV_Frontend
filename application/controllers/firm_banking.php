<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class firm_banking extends CI_Controller
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
		
    }

    
	public function index()
	{
		$data = array();
		$transId = $this->megav_libs->genarateTransactionId('GLP');
		$listBank = $this->megav_core_interface->getListBankAccount($this->session_memcached->userdata['info_user']['email'],
																	$this->session_memcached->userdata['info_user']['mobileNo'],
																	$this->session_memcached->userdata['info_user']['userID'], $transId,1);

		
		if($listBank)
			$data['listBankAcc'] = $listBank;
		$this->load->view('firm_banking/index', $data);
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
		log_message('error', 'data post: ' . print_r($post, true));
		if($post)
		{
			$iscard = '1'; // mac dinh la so the
			$redis = new CI_Redis();
			$listBank = $redis->sMembers(PROVIDER_KEY_REDIS);
			foreach($listBank as $bank)
			{
				$bank = json_decode($bank);
				if(isset($bank->epurseBankCode) && $bank->epurseBankCode == $post['bank_code'])
				{
					$iscard = isset($bank->isCard) ? $bank->isCard : "1" ;
					//break;
				}
			}
			
			if($iscard == '1')
			{
				$this->form_validation->set_rules('bank_account', 'Số thẻ', 'max_length[30]|min_length[8]|alpha_numeric|required|trim|xss_clean');
				$this->form_validation->set_rules('bank_account_name', 'Tên chủ thẻ', 'max_length[60]|min_length[6]|required|trim|xss_clean');
			}
			else
			{
				$this->form_validation->set_rules('bank_account', 'Số tài khoản', 'max_length[30]|min_length[8]|alpha_numeric|required|trim|xss_clean');
				$this->form_validation->set_rules('bank_account_name', 'Tên tài khoản', 'max_length[60]|min_length[6]|required|trim|xss_clean');
			}
			
			$this->form_validation->set_rules('bank_code', 'Ngân hàng', 'required|trim|xss_clean');
			$this->form_validation->set_rules('bank_branch', 'Chi nhánh', 'max_length[255]|required|trim|xss_clean');
			if($this->form_validation->run() == true) 
			{
				$accessToken = $this->megav_libs->genarateAccessToken();
				$requestInsertBank = $this->megav_core_interface->addBankFirmBanking($this->session_memcached->userdata['info_user']['userID'],trim($post['bank_code']) ,strtoupper(trim($post['bank_account_name'])),strtoupper(trim($post['bank_account'])),trim($post['bank_branch']),$accessToken);

				if($requestInsertBank)
				{
					$response = json_decode($requestInsertBank);
					log_message('error', 'respone: ' . print_r($requestInsertBank, true));
					if(isset($response->status))
					{
						if($response->status == STATUS_SUCCESS)
						{
							$error = 1;
							$this->megav_libs->page_result("Tạo thông tin ngân hàng thành công", '/firm_banking', null, null, 1);
							delete_cookie("addBank");
							$arrUserinfo = $this->session_memcached->userdata['info_user'];
							$arrUserinfo['countUserbankAcc'] += 1;
							$this->session_memcached->set_userdata('info_user', $arrUserinfo);
						}
						elseif($response->status == 'XH')
						{
							if($iscard == '1')
							{
								$data['bankAccNameError'] = "Tên chủ thẻ không đúng";
							}
							else
							{
								$data['bankAccNameError'] = "Tên chủ tải khoản sai";
							}
						}
						elseif($response->status == '62')
						{
							if($iscard == '1')
							{
								$data['bankAccError'] = "Sai số thẻ";
							}
							else
							{
								$data['bankAccError'] = "Sai số tài khoản";
							}
						}
						else
						{
							$error = 1;
							$this->megav_libs->page_result(lang('MVM_'.$response->status), '/firm_banking');
						}
					}
					else
					{
						$error = 1;
						$this->megav_libs->page_result('Tạo thông tin ngân hàng thất bại.', '/firm_banking');
					}
				}
				else
				{
					$error = 1;
					$this->megav_libs->page_result("Hệ thống MegaV đang bận. Vui lòng thử lại sau.", '/firm_banking');
				}
			}
			else
			{
				$data['isCard'] = $iscard;
			}
		}
		
		if(!isset($error))
		{
			$transId = $this->megav_libs->genarateTransactionId('GLP');
			$listBank = $this->megav_core_interface->getProvider($transId, SUB_WITHDRAW_TYPE_FAST);




			if($listBank)
				$data['listBank'] = $listBank;
			
			
			$this->load->view('firm_banking/create', $data);
			/*
			$this->view['content'] = $this->load->view('bank_account/create', $data, true);
			$this->load->view('Layout/layout_info', array(
				'data' => $this->view,
				'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
			));
			*/
		}
	}
	public function loadUnicode(){
		$unicode = $this->input->post('unicode');

		echo json_encode(array('status'=>true,'unicode'=> loadUnicode($unicode)));
	}

	
}
?>