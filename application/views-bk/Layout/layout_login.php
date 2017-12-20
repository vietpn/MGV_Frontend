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

<body style="background-color: #fff; height: auto;">
			<?php 
				$ci = & get_instance();
				$ci->session_memcached->get_userdata();
				$redis = new CI_Redis;
				$numb_wrong_pass = $redis->get('WRONG_PASS_' . $ci->session_memcached->userdata('ip_address') . date('Ymd'));
				if(!empty($numb_wrong_pass) && $numb_wrong_pass >= NUM_OF_WRONG_PASS )
				{
					$show_capcha = 1;
				}
				else
				{
					$show_capcha = 0;
				}
			?>
		<div id="login-page">
			<div class="row">
				<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
					<div class="logoimages"></div>
				</div>
				<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
				<?php echo form_open('login/do_login', array('method' => 'post', 'name' => 'login', 'id' => 'login', 'class' => 'form-horizontal frm-register')); ?>
					<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
						<div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
							<span class="login-label">Số điện thoại</span>
							<input type="text" name="username" class="input-login" autocomplete="off"
								   placeholder="Số điện thoại" value="<?php echo set_value('username', isset($username)?$username:'') ?>"/>
							<span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation-error" id="username_error">
								<?php
									if(form_error('username'))
										echo form_error('username');
									elseif(isset($user_error))
										echo $user_error;
								?>
							</span>
						</div>
					</div>
					<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
						<div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
							<span class="login-label">Mật khẩu</span>
							<input type="password" name="password" class="input-login" autocomplete="off"
								   placeholder="Mật khẩu" value="<?php echo set_value('password', isset($password)?$password:'') ?>"/>
							<span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation-error" id="username_error">
								<?php
									if(form_error('password'))
										echo form_error('password');
									elseif(isset($pass_error))
										echo $pass_error;
								?>
							</span>
						</div>
					</div>
			
					<div class="col-md-6 col-lg-6 col-xs-6 col-sm-6 remember-pass">
						<label>
							<input style="float: left;" class="remember-pass-checkbox" type="checkbox" name="remember_pass" value="1" <?php echo set_checkbox('remember_pass', '1', isset($username)?TRUE:'') ?>/>
							<span class="remember-pass-text" style="margin-left: 10px;">Nhớ mật khẩu</span>
						</label>
					</div>
					
					<div class="col-md-6 col-lg-6 col-xs-6 col-sm-6 fogot-pass">
						<span class="remember-pass-text" style="margin-left: 10px;"><a target="_parent" href="/reset_password">Quên mật khẩu</a></span>
					</div>
					
					<?php if($show_capcha == 1): ?>
						<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12" style="margin-top: 15px; margin-bottom: 15px;">
							<script src='https://www.google.com/recaptcha/api.js'></script>
							<div style="width: 304px; margin: 0 auto; <?php if(isset($error_capcha) && $error_capcha == 1) echo "border: 1px solid red;" ?>">
								<div class="g-recaptcha" data-sitekey="<?php echo API_GOOGLE_RECAPTCHA_PUBLIC; ?>" ></div>
							</div>
						</div>
					<?php endif; ?>
					
						<div class="">
							<input name="login_account" class="col-md-12 col-lg-12 col-xs-12 col-sm-12 btn-login-register" id="site_button" type="submit"
								   value="Đăng Nhập"/>
						</div>
					
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
	<script>
		$(function () {
			
			$('input[name="username"]').keyup(function(){
				this.value = this.value.toLowerCase();
			});

			$("body").on("change","#login input[name='username']",function(e){
				var res = checkPhoneRegexReplace($(this).val());
				if (res!='') {
					$(this).val(res);
				}
			});
			function checkPhoneRegexReplace(phoneNo) {
			  var phoneRE = /^84/; 
			  if (phoneNo.match(phoneRE)) {
			  	var res = phoneNo.replace(phoneRE, '0');
			    return res; 

			  } else {
			    return false;
			  }
			}
			
		});
	</script>
	<style>
		input:-webkit-autofill, textarea:-webkit-autofill, select:-webkit-autofill {
			 -webkit-box-shadow: 0 0 0 30px #fff inset;
		}
		
		
	</style>
</body>
</html>