<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class change_id extends CI_Controller
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
		$post = $this->input->post();
		$data = array();
		if($post)
		{
			if($this->session_memcached->userdata['info_user']['validate_idno'] == '0' || $this->session_memcached->userdata['info_user']['validate_idno'] == '3')
			{
				$this->form_validation->set_rules('fullname', 'Họ và tên', 'required|trim|xss_clean');
				$this->form_validation->set_rules('idNo', 'Số chứng minh nhân dân', 'numeric|required|trim|xss_clean');
				//$this->form_validation->set_rules('birthday', 'Ngày sinh', 'required|trim|xss_clean');
				$this->form_validation->set_rules('iddate', 'Ngày cấp', 'required|trim|xss_clean');
				$this->form_validation->set_rules('idplace', 'Nơi cấp', 'required|trim|xss_clean');
				if($this->form_validation->run() == true) 
				{
					if(isset($post['update']))
					{ // cập nhật thông tin CMT
						//if(isset($_FILES['file']['name'][1]) && !empty($_FILES['file']['name'][1]) && isset($_FILES['file']['name'][2]) && !empty($_FILES['file']['name'][1]))
						//{
							/*
							if($_FILES['file']['tmp_name'][1] != '')
							{
								$uploadFront = $this->uploadMediaserver($_FILES['file']['name'][1], base64_encode(file_get_contents($_FILES['file']['tmp_name'][1])));
								if(isset($uploadFront['status']) && $uploadFront['status'] == '1')
								{
									$urlIdImgF = $uploadFront['link_images'];
								}
								else
								{
									$uploadErr = 1;
								}
							}
							else
							{
								$urlIdImgF = $this->session_memcached->userdata['info_user']['id_img_f'];
							}
							
							if($_FILES['file']['tmp_name'][2] != '')
							{
								$uploadBack = $this->uploadMediaserver($_FILES['file']['name'][2], base64_encode(file_get_contents($_FILES['file']['tmp_name'][2])));
								if(isset($uploadBack['status']) && $uploadBack['status'] == '1')
								{
									$urlIdImgB = $uploadBack['link_images'];
								}
								else
								{
									$uploadErr = 1;
								}
							}
							else
							{
								$urlIdImgB = $this->session_memcached->userdata['info_user']['id_img_b'];
							}
							*/
							
							//if(isset($urlIdImgF) && !empty($urlIdImgF) && isset($urlIdImgB) && !empty($urlIdImgB))
							//{
								
								$accessToken = $this->megav_libs->genarateAccessToken();
								$transId = "UII" . date("Ymd") . rand();
								/*
								$requestUpdateCMT = $this->megav_core_interface->updateIdInfo($this->session_memcached->userdata['info_user']['userID'],
																								$post['fullname'], date('Ymd', strtotime($post['birthday'])), 
																								$post['idNo'], $post['idplace'],
																								date('Ymd', strtotime($post['iddate'])), $urlIdImgF, 
																								$urlIdImgB, $accessToken, $transId);
								*/	
								$urlIdImgF = "";
								$urlIdImgB = "";
								$requestUpdateCMT = $this->megav_core_interface->updateIdInfo($this->session_memcached->userdata['info_user']['userID'],
																								$post['fullname'], "", 
																								$post['idNo'], $post['idplace'],
																								date('Ymd', strtotime($post['iddate'])), $urlIdImgF, 
																								$urlIdImgB, $accessToken, $transId);
								if($requestUpdateCMT)
								{
									$response = json_decode($requestUpdateCMT);
									log_message('error', 'respone: ' . print_r($requestUpdateCMT, true));
									if(isset($response->status))
									{
										if($response->status == '00')
										{
											// update thành công lưu lại thong tin user trong redis
											//echo "Update thont tin thanh cong";
											$error = 1;
											$this->megav_libs->page_result("Cập nhật thông tin chứng minh thư thành công.", '/change_id', null, null, 1);
											
											// luu redis user
											$arrUserinfo = $this->session_memcached->userdata['info_user'];
											$arrUserinfo['validate_idno'] 	= '0';
											//$arrUserinfo['id_img_f'] 		= $urlIdImgF;
											//$arrUserinfo['id_img_b'] 		= $urlIdImgB;
											$arrUserinfo['fullname'] 		= $post['fullname'];
											$arrUserinfo['idNo'] 			= $post['idNo'];
											//$arrUserinfo['birthday'] 		= date('Ymd', strtotime($post['birthday']));
											$arrUserinfo['idNo_where'] 		= $post['idplace'];
											$arrUserinfo['idNo_dateIssue'] 	= date('Ymd', strtotime($post['iddate']));
											$this->session_memcached->set_userdata('info_user', $arrUserinfo);
											
										}
										else
										{
											$error = 1;
											$this->megav_libs->page_result(lang('MVM_'.$response->status), '/change_id');
										}
									}
									else
									{
										$error = 1;
										$this->megav_libs->page_result("Có lỗi trong quá trình cập nhật CMT. Vui lòng thử lại.", '/change_id');
									}
								}
								else
								{
									$error = 1;
									$this->megav_libs->page_result("Hệ thống MegaV đang bận. Vui lòng thử lại sau.", '/change_id');
								}
							/*
							}
							elseif(isset($uploadErr))
							{
								$data['invalidFile'] = "Upload ảnh CMT thất bại";
							}
							else
							{
								$data['invalidFile'] = "Bạn phải chọn đủ 2 ảnh";
								$data['post'] = $post;
							}
							*/
						//}
						//else
						//{
						//	$data['invalidFile'] = "Bạn phải chọn đủ 2 ảnh";
						//}
					}
					
					if(isset($post['confirm']))
					{ 	// yeu cau xac thuc idNo idNo_dateIssue idNo_where birthday
						if(!empty($this->session_memcached->userdata['info_user']['fullname']) && 
							!empty($this->session_memcached->userdata['info_user']['idNo']) && 
							!empty($this->session_memcached->userdata['info_user']['idNo_dateIssue']) && 
							!empty($this->session_memcached->userdata['info_user']['idNo_where']) && 
							!empty($this->session_memcached->userdata['info_user']['birthday']) &&
							!empty($this->session_memcached->userdata['info_user']['id_img_f']) &&
							!empty($this->session_memcached->userdata['info_user']['id_img_b']) )
						{
							$transId = "VRI" . date("Ymd") . rand();
							$requestVerifyCMT = $this->megav_core_interface->verifyId($this->session_memcached->userdata['info_user']['userID'], $transId);
							
							if($requestVerifyCMT)
							{
								$response = json_decode($requestVerifyCMT);
								log_message('error', 'respone: ' . print_r($requestVerifyCMT, true));
								if(isset($response->status))
								{
									if($response->status == '00')
									{
										$error = 1;
										$mess = "Gửi yêu cầu xác thực CMT thành công. Vui lòng chờ quản trị viên xác thực.";
										$this->megav_libs->page_result($mess, '/user_info');
										
										// update thành công lưu lại thong tin user trạng thái CMT trong redis
										$arrUserinfo = $this->session_memcached->userdata['info_user'];
										$arrUserinfo['validate_idno'] = '1';
										$this->session_memcached->set_userdata('info_user', $arrUserinfo);
									}
									else
									{
										$error = 1;
										$this->megav_libs->page_result(lang('MVM_'.$response->status), '/change_id');
									}
								}
								else
								{
									$error = 1;
									$this->megav_libs->page_result("Có lỗi trong quá trình xác thực CMT. Vui lòng thử lại.", '/change_id');
								}
							}
							else
							{
								$error = 1;
								$this->megav_libs->page_result("Hệ thống MegaV đang bận. Vui lòng thử lại sau.", '/change_id');
							}
						}
						else
						{
							$data['message'] = "Bạn phải cập nhật đầy dủ thông tin CMT trước.";
						}
					}
				} else {
					$data['post'] = $post;
				}
			}
		}
		
		if(!isset($error))
		{
			$transId = "GLP" . date("Ymd") . rand();
			$listProvince = $this->megav_core_interface->getProvince($transId);
			if($listProvince)
				$data['listProvince'] = $listProvince;
			$this->load->view('change_id/index', $data);
			/*
			$this->view['content'] = $this->load->view('change_id/index', $data, true);
			$this->load->view('Layout/layout_info', array(
				'data' => $this->view,
				'nav_left' => $this->load->view('Layout/layout_menu_left', "", true)
			));
			*/
		}
	}
	
	public function uploadMediaserver($fileName, $dataImages)
	{
		log_message('error','INFO: UPLOAD INFO: '.$dataImages, false, true);
		$leng_data_images = strlen($dataImages);
		$cutleng = $leng_data_images - 5;
		$kytudau = substr($dataImages, 0, 5);
		$kytucuoi = substr($dataImages, $cutleng, 5);
		$private_key = md5(md5(md5(MEDIA_SERVER_KEY.$kytudau.$kytucuoi)));
		$url_upload = URL_MEDIA_SERVER ;
		$data = array( 'key' => $private_key,
			'file_name' => $fileName,
			'data_images' => $dataImages);
		$query = http_build_query($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url_upload);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec ($ch);
		curl_close ($ch);
		log_message('error','INFO: UPLOAD IMAGE: Data request: '.print_r($data, true).' | link mediaserver: '.$url_upload.' | result: '.$result, false, true);
		$result = json_decode($result, true);
        return $result;
	}

	
}
?>