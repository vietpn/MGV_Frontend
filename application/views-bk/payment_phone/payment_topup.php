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
		<li><a href="#">Giao dịch</a></li>
		<li><a href="#">Nạp điện thoại</a></li>
	</ul>

	<div class="col-md-12 form-center">
		
			<?php echo form_open('/payment_phone/payment_topup', array('method' => 'post', 'role' => 'form')); ?>
			<?php if($this->session_memcached->userdata['info_user']['security_method'] == '1'): ?>
				<div>Hệ thống đã gửi OTP đến số điện thoại <?php echo substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 0, (strlen($this->session_memcached->userdata['info_user']['mobileNo']) - 4)); ?>. Không nhận được OTP <a id="resendOtp">Click gửi lại</a></div>
			<?php endif; ?>
			<div class="form-group">
                 <label>Nhà cung cấp mã thẻ</label>
                 <div class="div-input">
                     <span><?php echo $post['provider_code']; ?></span>
                 </div>
                 <div class="clearfix"></div>
             </div>
			 
			 <div class="form-group">
                 <label>Mệnh giá thẻ (đ)</label>
                 <div class="div-input">
                     <span><?php echo $post['amount']; ?></span>
                 </div>
                 <div class="clearfix"></div>
             </div>
						
			<?php if($this->session_memcached->userdata['info_user']['security_method'] == '1'): ?>
				<div class="form-group">
					<label>OTP</label>
					<div class=" div-input">
						<input name="otp" class="form-input" type="password" placeholder="Nhập OTP" >
						<span class="form-error"><?php echo isset($error_otp) ? $error_otp : ""; ?><?php echo form_error('otp'); ?></span>
					</div>
					<div class="clearfix"></div>
				</div>
			<?php else: ?>
				<div class="form-group">
					<label>Mật khẩu cấp 2</label>
					<div class=" div-input">
						<input name="passLv2" class="form-input" type="password" placeholder="Nhập mật khẩu cấp 2" >
						<span class="form-error"><?php echo isset($error_passLv2) ? $error_passLv2 : ""; ?><?php echo form_error('passLv2'); ?></span>
					</div>
					<div class="clearfix"></div>
				</div>
			<?php endif; ?>
			
			<div class="form-group button-group">
				<label>&nbsp </label>
				<div class="div-input">
					<input name="step1" type="submit" class="button button-main" value="Hoàn thành"/>
					<a href="/payment_game" class="button button-sub"/>Hủy bỏ</a>
				</div>
				<div class="clearfix"></div>
			</div>
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