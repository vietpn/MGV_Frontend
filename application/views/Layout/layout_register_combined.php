<!DOCTYPE html>
<html lang="vi">
<head>
<link rel="stylesheet" type="text/css" href="<?php echo  base_url() . '/css/bootstrap/bootstrap.min.css' ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() . 'assets/css/element/style_login.css'; ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() . 'assets/css/element/fonts.css'; ?>"/>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery-1.11.3.min.js"; ?>'></script>
   
	
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.min.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.css"; ?>'/>
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/jquery.flexisel.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/slick.min.js"; ?>'></script>
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/bootstrap.min.js"; ?>'></script>
</head>

<body style="background-color: #fff; <?php echo isset($postFL) ? "height: auto;" : ''; ?>">
	<div id="login-page">
		<div class="row">
			<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
				<div class="logoimages"></div>
			</div>
			<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
					<?php if(!isset($send_otp)): ?>
						<?php echo form_open('register/do_register', array('method' => 'post', 'name' => 'register', 'id' => 'register', 'class' => 'form-horizontal frm-register')); ?>
						<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
						<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
							<div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
								<span class="login-label">Số điện thoại</span>
								<input type="tel" name="fone" class="input-login" autocomplete="off"
									   placeholder="Số điện thoại" value="<?php echo set_value('fone') ?>"/>
								
								<span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation-error" style="color: #b94207" id="fone_error">
									<div class="error"><?php echo (isset($error_fone)) ? $error_fone : ''; ?>  <?php echo form_error('fone'); ?></div>
								</span>
							</div>
						</div>
						
						<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
							<div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
								<span class="login-label">Tên đầy đủ</span>
								<input type="text" name="fullname" class="input-login" autocomplete="off"
									   placeholder="Tên đầy đủ" value="<?php echo set_value('fullname') ?>"/>
								
								<span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation-error" style="color: #b94207" id="fone_error">
									<div class="error"><?php echo form_error('fullname'); ?></div>
								</span>
							</div>
						</div>
						
						<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
							<div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
								<span class="login-label">Mật khẩu</span>
								<input type="password" name="password" class="input-login checkSpace" placeholder="******" autocomplete="off"
									   value="<?php echo set_value('password') ?>" maxlength="20"/>
								<?php if(form_error('password')): ?>
									<span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation-error" style="color: #b94207"
										  id="fone_error"><div class="error"> <?php echo form_error('password'); ?></div></span>
								<?php endif; ?>
							</div>
						</div>
						<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
							<div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
								<span class="login-label">Nhập lại mật khẩu</span>
								<input type="password" name="re_password" class="input-login checkSpace" placeholder="******" autocomplete="off"
									   value="<?php echo set_value('re_password') ?>" maxlength="20"/>
								<?php if(form_error('re_password')): ?>
									<span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation-error" style="color: #b94207"
										  id="fone_error"><div class="error"><?php echo form_error('re_password'); ?></div></span>
								<?php endif; ?>
							</div>
						</div>
						
						<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
							<div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12" style="<?php if(form_error('accept_dksd')) echo "border: 1px solid #b94207;"; ?>">
								
									<input type="checkbox" name="accept_dksd" class="input-login" style="float: left; width: 30px;"/>
									<span style="float: left; width: 264px; text-align: justify; font-size: 11px;">
										
											Tôi đã đọc, hiểu rõ và đồng ý với <a id="dksd" data-toggle="modal" data-target="#myModal">Thỏa thuận người sử dụng</a> và <a id="csqrt" data-toggle="modal" data-target="#myModal1">Chính sách quyền riêng tư</a> của MegaV
										
									</span>
							</div>
						</div>
						
						<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12" style="margin-top: 15px; margin-bottom: 15px;">
							<script src='https://www.google.com/recaptcha/api.js'></script>
							<div style="width: 304px; margin: 0 auto; <?php if(isset($error_capcha) && $error_capcha == 1) echo "border: 1px solid #b94207;" ?>">
								<div class="g-recaptcha" data-sitekey="<?php echo API_GOOGLE_RECAPTCHA_PUBLIC; ?>" ></div>
							</div>
						</div>
						
						<div class="">
							<input name="login_account" class="col-md-12 col-lg-12 col-xs-12 col-sm-12 btn-login-register" id="site_button" data-check-otp="0" type="submit"
								   value="Đăng Ký"/>
						</div>
						
						<?php echo form_close(); ?>
					<?php else: ?>
						<?php echo form_open('register/confirm_register', array('method' => 'post', 'name' => 'register', 'id' => 'register', 'class' => 'form-horizontal frm-register')); ?>
						<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
							<span>Chúng tôi đã gửi mã kích hoạt vào số điện thoại đăng ký của bạn. Vui lòng nhập vào đây để hoàn tất đăng ký với MegaV.vn. 
								<p>Không nhận được mã vui lòng <a id="resendOtp">gửi lại mã kích hoạt</a></p>
							</span>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input " style="min-height: 160px;">
							<div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
								<span class="login-label">Nhập mã kích hoạt (OTP)</span>
								<input type="password" name="otp_code" class="input-login" placeholder=""
									   value="" autocomplete="off"/>
								<?php if(form_error('otp_code')): ?>
									<span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation-error"
										  id="fone_error"> </span>
								<?php endif; ?>
							</div>
							
							<span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation-error" style="color: #b94207">
								<div class="error"><?php if(isset($err_mess) && !empty($err_mess)) echo $err_mess; ?></div>
							</span>
							<div class="clearfix"></div>
						</div>
						<div class="">
							<input class="btn-login-register result_re" id="site_button" data-check-otp="1" type="submit"
								   value="Hoàn Thành"/>
						</div>
						<?php echo form_close(); ?>
					<?php endif; ?>
				</div>
		</div>
	</div>

		
	
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