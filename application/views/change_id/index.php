<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MegaV <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo '../images/megaid-favicon.png' ?>"/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/bootstrap.min.css" ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/sidebar.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/content.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/caroulsel-reponsive-style.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/layout_login.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/layout_info.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/slick.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/metisMenu.min.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/element/fonts.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/element/style.css"; ?>'/>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery-1.11.3.min.js"; ?>'></script>
</head>
<body>
	
	<style>
		.form-center .div-input {
			float: right;
			width: 350px;
		}
	</style>

	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a href="javascript:void(0);" class="back_acc_manage">Quản trị tài khoản</a></li>
		<li><a>Chứng minh nhân dân</a></li>
	</ul> 

<div class="col-md-12 form-center">
<?php echo form_open_multipart('change_id', array('method' => 'post', 'role' => 'form')); ?>
	<?php if(isset($message)): ?> <div class="form-group row error"><?php echo $message ?></div> <?php endif; ?>
	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
	<div class="form-group">
		<label>Họ và tên</label>
        <div class="div-input">
            <input class="form-input" type="text"
                   value="<?php if(!isset($post['fullname'])){ 
									if($this->session_memcached->userdata['info_user']['fullname'] != '') 
										echo $this->session_memcached->userdata['info_user']['fullname']; 
								} else {
									echo $post['fullname'];
								}
							?>"
					<?php if($this->session_memcached->userdata['info_user']['fullname'] == '') echo 'placeholder="Chưa có thông tin"'; ?>
                   name="fullname"  />
            <span class="form-error"><?php echo form_error('fullname') ?></span>
        </div>
        <div class="clearfix"></div>
	</div>
	<div class="form-group">
		<label>Chứng minh nhân dân</label>
		<div class="div-input">
			<input class="form-input" type="text"
				value="<?php if(!isset($post['idNo'])){
								if($this->session_memcached->userdata['info_user']['idNo'] != '') 
									echo $this->session_memcached->userdata['info_user']['idNo']; 
							} else {
								echo $post['idNo'];
							}
							?>" 
				<?php if($this->session_memcached->userdata['info_user']['idNo'] == '') echo 'placeholder="Chưa có thông tin"'; ?>
				name="idNo"/>
            <span class="form-error"><?php echo form_error('idNo') ?></span>
        </div>
        <div class="clearfix"></div>
	</div>
	<!--div class="form-group">
		<label>Ngày sinh</label>
		<div class="div-input">
			<input class="form-input cmnd-timepicker" type="text"
				value="<?php if(!isset($post['birthday'])){
								if($this->session_memcached->userdata['info_user']['birthday'] != '') 
									echo date('d-m-Y', strtotime($this->session_memcached->userdata['info_user']['birthday'])); 
							} else {
								echo $post['birthday'];
							}
							?>" 
				<?php if($this->session_memcached->userdata['info_user']['birthday'] == '') echo 'placeholder="Chưa có thông tin"'; ?>
				name="birthday"/>
            <span class="form-error"><?php echo form_error('birthday') ?></span>
        </div>
        <div class="clearfix"></div>
	</div-->
	<div class="form-group">
		<label>Ngày cấp CMND</label>
		<div class="div-input">
			<input class="form-input cmnd-timepicker" type="text"
				value="<?php if(!isset($post['iddate'])){
								if($this->session_memcached->userdata['info_user']['idNo_dateIssue'] != '') 
									echo date('d-m-Y', strtotime($this->session_memcached->userdata['info_user']['idNo_dateIssue'])); 
							} else {
								echo $post['iddate'];
							}
						?>" 
				<?php if($this->session_memcached->userdata['info_user']['idNo_dateIssue'] == '') echo 'placeholder="Chưa có thông tin"'; ?>
				name="iddate"/>
            <span class="form-error"><?php echo form_error('iddate') ?></span>
        </div>
		<div class="clearfix"></div>
	</div>
	<div class="form-group">
		<label>Nơi cấp CMND</label>
		<div class="div-input">
			<select name="idplace" class="form-input">
				<option value="">Chọn tỉnh thành</option>
				<?php if(isset($listProvince)): ?>
					<?php foreach($listProvince as $province): ?>
						
						<option value="<?php echo $province->provinceName; ?>" <?php echo set_select('idplace', $province->provinceName, False); ?>
							<?php if($province->provinceName == $this->session_memcached->userdata['info_user']['idNo_where']) echo "selected"; ?>>
							<?php echo $province->provinceName; ?>
						</option>
						
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
            <span class="form-error"><?php echo form_error('idplace') ?></span>
        </div>
        <div class="clearfix"></div>
	</div>
	<!--div class="form-group">
		<label>Ảnh CMND
			<?php $dataTooltip = 'Đính kèm đủ file ảnh mặt 1 và mặt 2 để hoàn tất việc xác thực thông tin chứng minh nhân dân'; ?>
			<i class="fa fa-exclamation-circle has-tooltip" data-placement="bottom" data-toggle="tooltip" data-original-title="<?php echo $dataTooltip ?>"></i>
		</label>
		<div class="div-input">
            <div class="link">
                <?php if($this->session_memcached->userdata['info_user']['validate_idno'] == '0' || $this->session_memcached->userdata['info_user']['validate_idno'] == '3'): ?>
                    <a id="openUploadDialogFront" href="javascript:void(0)" style="float: left;">Chọn ảnh 1
                        <img id="imagesFrontSide" class="images_id" style="cursor: pointer; <?php echo (empty($this->session_memcached->userdata['info_user']['id_img_f'])) ? 'display:none' : 'display:block' ; ?>"
                             src="<?php if(!empty($this->session_memcached->userdata['info_user']['id_img_f'])) echo $this->session_memcached->userdata['info_user']['id_img_f']; ?>" >
                    </a>
                    <input id="imgFrontSide" name="file[1]" style="display: none;" type="file">
                <?php else: ?>
                    <img id="imagesFrontSide" class="images_id" style="cursor: pointer;"
                         src="<?php if(!empty($this->session_memcached->userdata['info_user']['id_img_f'])) echo $this->session_memcached->userdata['info_user']['id_img_f']; ?>" >
                <?php endif; ?>
                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <?php if($this->session_memcached->userdata['info_user']['validate_idno'] == '0' || $this->session_memcached->userdata['info_user']['validate_idno'] == '3'): ?>
                    <a id="openUploadDialogBack" href="javascript:void(0)"  style="float: left; margin-left: 20px;">Chọn ảnh 2
                        <img id="imagesBackSide" class="images_id" style="cursor: pointer; <?php echo (empty($this->session_memcached->userdata['info_user']['id_img_b'])) ? 'display:none' : 'display:block'; ?> "
                             src="<?php if(!empty($this->session_memcached->userdata['info_user']['id_img_b'])) echo $this->session_memcached->userdata['info_user']['id_img_b']; ?>" >
                    </a>
                    <input id="imgBackSide" name="file[2]" style="display: none;" type="file">
                <?php else: ?>
                    <img id="imagesBackSide" class="images_id" style="cursor: pointer;"
                         src="<?php if(!empty($this->session_memcached->userdata['info_user']['id_img_b'])) echo $this->session_memcached->userdata['info_user']['id_img_b']; ?>" >
                <?php endif; ?>
            </div>
            <span class="form-error"><?php if(isset($invalidFile)) echo $invalidFile;  ?></span>
        </div>
		<div class="clearfix"></div>
	</div-->

	<?php if($this->session_memcached->userdata['info_user']['validate_idno'] == '0' || $this->session_memcached->userdata['info_user']['validate_idno'] == '3'): ?>
        <div class="form-group button-group">
            <div class="div-input">
				<input name="update" type="submit" class="button button-main" value="Cập nhật"/>
				<!--input name="confirm" type="submit" class="button button-sub button160" value="Xác thực ngay"/-->
			</div>
        </div>
	<?php endif; ?>
</form>
</div>

	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.min.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/datepicker.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/bootstrap-datetimepicker.min.css"; ?>'/>
	
	
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/jquery.flexisel.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/slick.min.js"; ?>'></script>
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/bootstrap.min.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/bootstrap-datepicker.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/metisMenu.min.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery.cookie.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/datepicker.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/cmnd.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/script.js"; ?>'></script>
	
	
</body>
</html>