<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class acc_manage extends CI_Controller
{
	public $deviceOS  = 'Unknow';
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('language');
        $this->load->library('session_memcached');
        $this->load->helper('url');
        $this->load->library('redis');
        $this->lang->load('error_message');
		$this->load->library('megav_libs');
		$this->load->library('megav_core_interface');
		$this->load->helper('cookie');
		
		$this->session_memcached->get_userdata();
		if(!isset($this->session_memcached->userdata['info_user']['userID']))
		{
			redirect();
			die;
		}
    }

    
    public function index()
    {
		//if()
		$data = array();
		$dataMenu = array();
		$dataMenu['userinfo'] = array('userName' => $this->session_memcached->userdata['info_user']['userID'],
										'mobileNo' => $this->session_memcached->userdata['info_user']['mobileNo'],
										'balance' => $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																											$this->megav_libs->genarateAccessToken()));
		
		$redis = new CI_Redis();
		$dataMenu['userinfo']['balance_behold'] = $redis->get('BALANCE_BEHOLD' . $dataMenu['userinfo']['userName']);
		
		$this->view['content'] = $this->load->view('acc_manage/info', $data, true);
		$this->load->view('Layout/layout_info', array(
			'data' => $this->view,
			'nav_left' => $this->load->view('Layout/layout_menu_left', $dataMenu, true),
			'user_info' => $this->session_memcached->userdata['info_user']
		));
		
    }
    public function uploadAvatarStepOne(){
    	if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
    	if ( 0 < $_FILES['file']['error'] ) {
    		echo json_encode(array('status'=>false,'mess'=>'Có lỗi xảy ra trong quá trình upload ảnh,vui lòng thử lại.'));die;
	    }else {
	    		$ex_file = strtolower(end(explode('.', $_FILES['file']['name'])));
		    	if ($ex_file=='jpg' || $ex_file=='jpeg' || $ex_file=='png' || $ex_file=='gif') { 
		    		if ($_FILES['file']['size'] <= 5000000) { 
		    			//dirname(BASEPATH).'images/accinfo/';die;
		    			$root_dir = dirname(BASEPATH).'/images/accinfo/'.$this->session_memcached->userdata['info_user']['userID'];
		    			if (!file_exists($root_dir)) {
						    mkdir($root_dir, 0777, true);
						}
		    			$move_dir = 'images/accinfo/'.$this->session_memcached->userdata['info_user']['userID'];
		    			if (!is_dir($move_dir)) {
						    mkdir($move_dir,0777, true);
						}
						//uploadMediaServer('file');
		    			if (move_uploaded_file($_FILES['file']['tmp_name'], $root_dir.'/' . $_FILES['file']['name'])) {
		    				echo json_encode(array('status'=>true,'src'=>base_url().$move_dir.'/' . $_FILES['file']['name']));
		    			}else{
		    				echo json_encode(array('status'=>false,'mess'=>'Có lỗi xảy ra,vui lòng thử lại sau.'));
		    			}
		    		}else{
		    			echo json_encode(array('status'=>false,'mess'=>'Dung lượng file giới hạn tối đa 5MB.'));
		    		}
		    	}else{
		    		echo json_encode(array('status'=>false,'mess'=>'Hệ thống chỉ chấp nhận file jpg, png, gif.'));die;
		    	}
	    	
	        
	    }
    }
    public function cropAction(){
    	$data = array();
		$dataMenu = array();
		$dataMenu['userinfo'] = array('userName' => $this->session_memcached->userdata['info_user']['userID'],
										'mobileNo' => $this->session_memcached->userdata['info_user']['mobileNo'],
										'balance' => $this->megav_core_interface->getBalaceUserWithBonusId($this->session_memcached->userdata['info_user']['userID'],
																											$this->megav_libs->genarateAccessToken()));
		
		$redis = new CI_Redis();
		$dataMenu['userinfo']['balance_behold'] = $redis->get('BALANCE_BEHOLD' . $dataMenu['userinfo']['userName']);

    	if (isset($_POST['ok'])) {

    		$targ_w = $targ_h = 100;
	        $jpeg_quality = 65;
	         
	        $src = trim($this->input->post('url_img'));
	        $src =$this->security->xss_clean($src);

	        $jcrop_x = $this->security->xss_clean(trim($this->input->post('jcrop_x')));
	        $jcrop_y = $this->security->xss_clean(trim($this->input->post('jcrop_y')));
	        $jcrop_w = $this->security->xss_clean(trim($this->input->post('jcrop_w')));
	        $jcrop_h = $this->security->xss_clean(trim($this->input->post('jcrop_h')));


	        $src = str_replace(base_url(), dirname(BASEPATH).'/', $src);
			log_message('error', 'cropAction --> src images: ' . $src);
	        $size = getimagesize($src);
			log_message('error', 'cropAction --> size images: ' . print_r($size, true));
	        if ($size["mime"]=="image/jpeg" || $size["mime"]=="image/jpg") {
	        	//jpeg file
	            @header('Content-type: image/jpeg');
		        $img_r = @imagecreatefromjpeg($src);
				log_message('error', 'cropAction --> after imagecreatefromjpeg: ' . print_r($img_r, true));
		        $dst_r = @ImageCreateTrueColor( $targ_w, $targ_h );
				log_message('error', 'cropAction --> after ImageCreateTrueColor: ' . print_r($dst_r, true));
		        @imagecopyresampled($dst_r,$img_r,0,0,$jcrop_x,$jcrop_y,$targ_w,$targ_h,$jcrop_w,$jcrop_h);
		       log_message('error', 'cropAction --> after imagecopyresampled: ' . print_r($dst_r, true));
			   $name_file = 'crop_'.time().'.jpg';
		        $avatar_url = $new_img = 'images/accinfo/'.$this->session_memcached->userdata['info_user']['userID'].'/'.$name_file;
		        @imagejpeg($dst_r,$new_img,$jpeg_quality);
				log_message('error', 'cropAction --> after imagejpeg: ' . print_r($dst_r, true));
		        $resultAvatar = $this->uploadMediaserver($name_file, base64_encode(file_get_contents(dirname(BASEPATH).'/'.$avatar_url)));
	        }else if($size["mime"]=="image/gif"){
	        	//gif file
	            @header('Content-type: image/gif');
		        $img_r = @imagecreatefromgif($src);
				log_message('error', 'cropAction --> after imagecreatefromgif : ' . print_r($img_r, true));
		        $dst_r = @ImageCreateTrueColor( $targ_w, $targ_h );
		        @imagecopyresampled($dst_r,$img_r,0,0,$jcrop_x,$jcrop_y,$targ_w,$targ_h,$jcrop_w,$jcrop_h);
		        log_message('error', 'cropAction --> after imagecopyresampled : ' . print_r($dst_r, true));
				$background = @imagecolorallocate($dst_r, 0, 0, 0); 
    			 log_message('error', 'cropAction --> after imagecopyresampled : ' . print_r($dst_r, true));
				@imagecolortransparent($dst_r, $background);
				 log_message('error', 'cropAction --> after imagecolortransparent : ' . print_r($dst_r, true));
		        $name_file = 'crop_'.time().'.gif';
		        $avatar_url = $new_img = 'images/accinfo/'.$this->session_memcached->userdata['info_user']['userID'].'/'.$name_file;
		        @imagegif($dst_r,$new_img);
				log_message('error', 'cropAction --> after imagegif : ' . print_r($dst_r, true));
		        $resultAvatar = $this->uploadMediaserver($name_file, base64_encode(file_get_contents(dirname(BASEPATH).'/'.$avatar_url)));
	        }else if ($size["mime"]=="image/png") {
	        	//png file
	            @header('Content-type: image/x-png');
		        $img_r = @imagecreatefrompng($src);
				log_message('error', 'cropAction --> after imagecreatefrompng : ' . print_r($img_r, true));
		        $dst_r = @ImageCreateTrueColor( $targ_w, $targ_h );
				log_message('error', 'cropAction --> after ImageCreateTrueColor : ' . print_r($dst_r, true));
		        @imagealphablending($dst_r, FALSE);
				log_message('error', 'cropAction --> after imagealphablending : ' . print_r($dst_r, true));
    			@imagesavealpha($dst_r, TRUE);
				log_message('error', 'cropAction --> after imagesavealpha : ' . print_r($dst_r, true));
		        @imagecopyresampled($dst_r,$img_r,0,0,$jcrop_x,$jcrop_y,$targ_w,$targ_h,$jcrop_w,$jcrop_h);
		        log_message('error', 'cropAction --> after imagecopyresampled : ' . print_r($dst_r, true));
				$name_file = 'crop_'.time().'.png';
		        $avatar_url = $new_img = 'images/accinfo/'.$this->session_memcached->userdata['info_user']['userID'].'/'.$name_file;
		        @imagepng($dst_r,$new_img);
		        log_message('error', 'cropAction --> after imagepng : ' . print_r($dst_r, true));
				$resultAvatar = $this->uploadMediaserver($name_file, base64_encode(file_get_contents(dirname(BASEPATH).'/'.$avatar_url)));
	        }else{
	        	$error = 1;
				header('Content-Type: text/html; charset=utf-8');
				$data['mess'] = 'Ảnh upload không đúng định dạng (jpg,png,gif). Vui lòng thử lại.';
	        	$data['redirect_link'] = base_url().'acc_manage';
	        	$this->view['content'] = $this->load->view('acc_manage/error', $data, true);
	        }
		    
	        
	        if (isset($resultAvatar['status']) && $resultAvatar['status'] == '1') {
	        	$data_result = $this->megav_core_interface->updateAvatar($this->session_memcached->userdata['info_user']['userID'], $resultAvatar['link_images']);
		        $new_data = json_decode($data_result);
		        if ($new_data->status==STATUS_SUCCESS) {
		        	$arrUserinfo = $this->session_memcached->userdata['info_user'];
		        	$arrUserinfo['avatar_url'] = $resultAvatar['link_images'];
		        	$this->session_memcached->set_userdata('info_user', $arrUserinfo);
		        	//unlink(dirname(BASEPATH).'/'.$avatar_url);// xóa link ảnh cũ
					//unlink(dirname(BASEPATH).'/'.str_replace(base_url(), '', $src));// xóa link ảnh đã cắt
					//xóa thư mục chứa ảnh
					$this->rrmdir(dirname(BASEPATH).'/images/accinfo/'.$this->session_memcached->userdata['info_user']['userID']);
					// redirect
		        	redirect('/acc_manage');
		        }else{
		        	$error = 1;
	        		header('Content-Type: text/html; charset=utf-8');
	        		$data['mess'] = 'Có lỗi trong quá trình update ảnh lên db. Vui lòng thử lại.';
	        		$data['redirect_link'] = base_url().'acc_manage';
	        		$this->view['content'] = $this->load->view('acc_manage/error', $data, true);
		        }
	        }else{
	        	$error = 1;
	        	header('Content-Type: text/html; charset=utf-8');
	        	$data['mess'] = 'Có lỗi trong quá trình upload ảnh lên media server. Vui lòng thử lại.';
	        	$data['redirect_link'] = base_url().'acc_manage';
	        	$this->view['content'] = $this->load->view('acc_manage/error', $data, true);
				//redirect('/acc_manage');
	        }

	        
	        
    	}else{
    		$no_post = 1;
    		redirect('/acc_manage');
    	}
    	if (!isset($error)) {
    		$this->view['content'] = $this->load->view('acc_manage', $data, true);
    	}
    	if (!isset($no_post)) {
    		$this->load->view('Layout/layout_info', array(
				'data' => $this->view,
				'nav_left' => $this->load->view('Layout/layout_menu_left', $dataMenu, true),
				'user_info' => $this->session_memcached->userdata['info_user']
			));
    	}
    	
    }

    public function uploadMediaserver($fileName, $dataImages){
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
	public function removeAvatarStepOne(){
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
		if (isset($_POST['src']) && $_POST['src']!='') {
			$src = trim($this->input->post('src'));
			$src =$this->security->xss_clean($src);

			$src = str_replace(base_url(), '', $src);

			$arr_src = explode("/", $src);
			if ($arr_src[0] == 'images' && $arr_src[1]=='accinfo') {
				if (file_exists(dirname(BASEPATH).'/'.$src)) {
					unlink(dirname(BASEPATH).'/'.$src);
					echo json_encode(array('status'));
				}
			}

		}
    	
    }
	public function rrmdir($src) {
	    $dir = opendir($src);
	    while(false !== ( $file = readdir($dir)) ) {
	        if (( $file != '.' ) && ( $file != '..' )) {
	            $full = $src . '/' . $file;
	            if ( is_dir($full) ) {
	                rrmdir($full);
	            }
	            else {
	                unlink($full);
	            }
	        }
	    }
	    closedir($dir);
	    rmdir($src);
	}

	public function currentFormat(){
		if (checkAjaxRequest() == FALSE){
                redirect(base_url());
	    }
	    $post_num = $this->input->post('number');
	    $post_num 	= $this->security->xss_clean($post_num);
	    if ($post_num) {
	    	$number = str_replace(',', '', trim($post_num));
			$checknum = $number%1000;
			if($number%1000 == 0)
			{
				if ($number=='' || !is_numeric($number)) {
					echo json_encode(array('status'=>true,'number'=> ''));
				}else{
					echo json_encode(array('status'=>true,'number'=> number_format($number)));
				}
	    	}
			else
			{
				echo json_encode(array('status'=>false,'mess'=> 'Số tiền phải là bội của 1000','number'=> number_format($number)));
			}
	    	
	    }
	}

	public function getListNotify(){
		if (checkAjaxRequest() == FALSE){
            redirect(base_url());
	    }	
		$data = array();
		$info_user = $this->session_memcached->userdata['info_user'];
		$user_id = $info_user['Id'];
		$userName = $info_user['userID'];
		$page_num = $this->security->xss_clean($this->input->post('page_num'));

		$data_res = $this->megav_core_interface->getListMessager($user_id,$userName, $page_num, 5);
		
		/*$data_res = array(
			'status' => '00',
    		'listUserInbox'=> array()
		);
		$data_res = (object) $data_res;
		$data_res->listUserInbox = array(
			0 => (object) array(
                    'id' => 16,
                    'userId' => '609482',
                    'userName' => '01636375048',
                    'createdDate' => 'Oct 24, 2017 4:28:36 PM',
                    'status' => '0',
                    'transId' => 'partnerTest_162800_520691',
                    'pcode' => '9999',
                    'title' => 'Cập nhật trạng thái cho giao dịch topup NAPD_201710121628000002',
                    'body' => 'Cập nhật trạng thái cho đơn hàng mua thẻ NAPD_201710121628000002',
                    'message' => 'Cập nhật trạng thái cho đơn hàng mua thẻ NAPD_201710121628000002',
                ),

            1 => (object) array(
                    'id' => 15,
                    'userId' => '609482',
                    'userName' => '01636375048',
                    'createdDate' => 'Oct 24, 2017 4:27:00 PM',
                    'status' => '0',
                    'transId' => 'partnerTest_162800_520691',
                    'pcode' => '9999',
                    'title' => 'Cập nhật trạng thái cho giao dịch topup NAPD_201710121628000002',
                    'body' => 'Cập nhật trạng thái cho đơn hàng mua thẻ NAPD_201710121628000002',
                    'message' => 'Cập nhật trạng thái cho đơn hàng mua thẻ NAPD_201710121628000002',
                ),

            2 => (object) array(
                    'id' => 14,
                    'userId' => '609482',
                    'userName' => '01636375048',
                    'createdDate' => 'Oct 24, 2017 4:26:07 PM',
                    'status' => '0',
                    'transId' => 'partnerTest_162800_520691',
                    'pcode' => '9999',
                    'title' => 'Cập nhật trạng thái cho giao dịch topup NAPD_201710121628000002',
                    'body' => 'Cập nhật trạng thái cho đơn hàng mua thẻ NAPD_201710121628000002',
                    'message' => 'Cập nhật trạng thái cho đơn hàng mua thẻ NAPD_201710121628000002',
                )
		);*/

		if ($data_res) {
			if ($data_res->status=='00') {
				if (!empty($data_res->listUserInbox)) {
					foreach ($data_res->listUserInbox as $key => $value) {
						$dateString = $value->createdDate;
						$myDate = new DateTime($dateString);
						$formattedDate = $myDate->format('d/m/Y H:i:s');
						$data_res->listUserInbox[$key]->createdDate = $formattedDate;
					}
					
				}

				$data = array(
					'status'=> true,
					'listMess' => $data_res->listUserInbox,
					'page_num' => $page_num+1
				);
				echo json_encode($data);
			}else{
				$data = array(
					'status'=> false,
					'page_num' => $page_num
				);
				echo json_encode($data);
			}
		}else{
			$data = array(
				'status'=> false,
				'page_num' => $page_num
			);
			echo json_encode($data);
		}
		
	}
	public function getViewMoreNotify(){
		if (checkAjaxRequest() == FALSE){
            redirect(base_url());
	    }
		$data = array();
		$info_user = $this->session_memcached->userdata['info_user'];
		$user_id = $info_user['Id'];
		$userName = $info_user['userID'];
		$page_num = $this->security->xss_clean($this->input->post('page_num'));

		$data_res = $this->megav_core_interface->getListMessager($user_id,$userName, $page_num, 5);
		if ($data_res) {
			if ($data_res->status=='00') {
				if (!empty($data_res->listUserInbox)) {
					foreach ($data_res->listUserInbox as $key => $value) {
						$dateString = $value->createdDate;
						$myDate = new DateTime($dateString);
						$formattedDate = $myDate->format('d/m/Y H:i:s');
						$data_res->listUserInbox[$key]->createdDate = $formattedDate;
					}
				}
				$data = array(
					'status'=> true,
					'listMess' => $data_res->listUserInbox,
					'page_num' => !empty($data_res->listUserInbox) ? $page_num+1 : $page_num
				);
				echo json_encode($data);
			}else{
				$data = array(
					'status'=> false,
					'page_num' => $page_num
				);
				echo json_encode($data);
			}
		}else{
			$data = array(
				'status'=> false,
				'page_num' => $page_num
			);
			echo json_encode($data);
		}
		
	}

	public function checkInboxNotify(){
		if (checkAjaxRequest() == FALSE){
            redirect(base_url());
	    }
	    $id_mess = $this->security->xss_clean($this->input->post('id_mess'));

		$data_res = $this->megav_core_interface->checkInboxMessager($id_mess);

		if ($data_res->status=='00') {
			$arrUserinfo = $this->session_memcached->userdata['info_user'];
			$arrUserinfo['countUserInbox'] = $arrUserinfo['countUserInbox'] - 1;
			$this->session_memcached->set_userdata('info_user', $arrUserinfo);

			$data = array(
				'status'=> true,
				'countUserInbox' => $arrUserinfo['countUserInbox']
			);
			echo json_encode($data);
		}else{
			$data = array(
				'status'=> false
			);
			echo json_encode($data);
		}
	}
	public function formatInboxNotify(){
		if (checkAjaxRequest() == FALSE){
            redirect(base_url());
	    }
	    $response = $this->security->xss_clean($this->input->post('response'));

	    if (!empty($response)) {
	    	$arrUserinfo = $this->session_memcached->userdata['info_user'];
			$arrUserinfo['countUserInbox'] = $arrUserinfo['countUserInbox'] + 1;
			$this->session_memcached->set_userdata('info_user', $arrUserinfo);

			// lấy xử lý ngày tháng start_date 20171215152602
            $start_year = substr($response['data']['createdDate'],0,4);
            $start_month = substr($response['data']['createdDate'],4,2);
            $start_date = substr($response['data']['createdDate'],6,2);
            $start_hour = substr($response['data']['createdDate'],8,2);
            $start_minus = substr($response['data']['createdDate'],10,2);
            $start_secon = substr($response['data']['createdDate'],12,2);
			$data = array(
				'status'=> true,
				'countUserInbox' => $arrUserinfo['countUserInbox'],
				'status_mess' => $response['status'],
				'title' => $response['title'],
				'body' => $response['body'],
				'id' => $response['data']['inboxId'],
				'createdDate' => $start_date.'/'.$start_month.'/'.$start_year.' '.$start_hour.':'.$start_minus.':'.$start_secon
			);
			echo json_encode($data);
	    }

	}

	
	

}
?>