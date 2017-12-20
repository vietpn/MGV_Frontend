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

	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a>Quản trị tài khoản</a></li>
		<li><a>Quên mật khẩu cấp 2</a></li>
	</ul>


<div class="col-md-12 form-center">
	<div class="row">
		<?php if(isset($sentOtp)): ?>
			<?php if(isset($messSentOtp)): ?>
				<div style="text-align: center;"><?php echo $messSentOtp ?> 
					<p>Không nhận OTP <a id="resendOtp">Click gửi lại</a></p>
				</div>
			<?php endif; ?>
			<?php echo form_open('reset_pass_lv2/resetPassLv2', array('method' => 'post', 'role' => 'form')); ?>
				
					<div class="form-group">
						<label>Nhập OTP</label>
						<div class="div-input">
							<input name="otp" class="form-input" type="password">
							<span class="form-error"><?php echo (isset($wrongOtp)) ? $wrongOtp : ''; ?><?php echo form_error('otp'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>
				
					<div class="form-group ">
						<label>Mật khẩu cấp 2 mới</label>
						<div class="div-input">
							<input name="newpassLv2" class="form-input" type="password">
							<span class="form-error"><?php echo form_error('newpassLv2'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>
				
					<div class="form-group ">
						<label>Nhập lại mật khẩu cấp 2 mới</label>
						<div class="div-input">
							<input name="repassLv2" class="form-input" type="password">
							<span class="form-error"><?php echo form_error('repassLv2'); ?></span>
						</div>
						<div class="clearfix"></div>
					</div>
				
				<div class="form-group button-group">
					<div class="div-input">
						<input type="submit" class="button button-main" value="Cập nhật"/>
						<a class="button button-sub" href="/change_pass_lv2"/>Hủy bỏ</a>
					</div>
				</div>
				
			</form>
		<?php else: ?>
			<?php echo form_open('reset_pass_lv2', array('method' => 'post', 'role' => 'form')); ?>
				
				<div class="form-group">
					<label>Hình thức xác thực</label>
					<div class="div-input">
						<select name="sec_met" class="form-input">
						<?php if($this->session_memcached->userdata['info_user']['phone_status'] == 1): ?>
							<option value="1"><?php /* echo "Xác thực qua số điện thoại cũ"; */ echo $this->session_memcached->userdata['info_user']['mobileNo']; ?></option>
						<?php endif; ?>
						<?php if($this->session_memcached->userdata['info_user']['email_status'] == 1): ?>
							<option value="2"><?php echo $this->session_memcached->userdata['info_user']['email']; ?></option>
						<?php endif; ?>
						</select>
					</div>
					 <div class="clearfix"></div>
				</div>

				<div class="form-group button-group">
					<div class="div-input">
						<input type="submit" class="button button-main" value="Tiếp tục"/>
						<a class="button button-sub" href="/change_pass_lv2"/>Hủy bỏ</a>
					</div>
				</div>
				
			</form>
		<?php endif; ?>
	</div>
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