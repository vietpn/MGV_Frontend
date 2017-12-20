<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-center">
    
        <span class="txt_account col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
			Lấy lại mật khẩu MegaV.vn
			<br>
			Để lấy lại mật khẩu, vui lòng nhập thông tin số điện thoại hoặc email đã đăng ký để lấy lại mật khẩu
		</span>
    
	<?php if(isset($otp_sent) && $otp_sent == "1"): ?>
	<?php echo form_open('reset_password/confirm_reset',array('method'=>'post','class'=>'col-xs-12 col-sm-12','role'=>'form')); ?>
		<span class="txt_account col-md-12 col-lg-12 col-xs-12 col-sm-12" style="text-align: center;">
			Hệ thống đã gửi tới số điện thoại 1 mã xác thực. Không nhận được mã, click <a id="resendOtp">gửi lại ngay</a>
		</span>
		<div class="clearfix"></div>
		<div class="form-group" style="width: 625px;">
			
			<div class="">
				<input style="width: 330px; margin: 0px auto;" type="password" class="form-input checkSpace" name="otp" value="<?php echo set_value('otp') ?>" placeholder="Nhập mã OTP">
				<span class="form-error" ><?php echo form_error('otp'); ?></span>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="form-group" style="width: 160px; margin-top: 20px;">
			<div class="">
					<input class="button button-main" type="submit"
						   value="Gửi"/>
			</div>
			<div class="clearfix"></div>
		</div>
	<?php echo form_close(); ?>
	<?php elseif(isset($many_user) && $many_user == "1"): ?>
	<?php echo form_open('reset_password',array('method'=>'post','class'=>'col-xs-12 col-sm-12','role'=>'form')); ?>
		<div class="form-group" style="width: 625px;">
			
			<div class="">
				<input style="width: 330px; margin: 0px auto;" type="text" class="form-input" name="uname" value="<?php echo set_value('uname') ?>" placeholder="Tài khoản đăng nhập">
				<span class="form-error"><?php echo form_error('uname'); ?></span>
			</div>
			<div class="clearfix"></div>
		</div>
		
		<div class="form-group" style="width: 160px; margin-top: 20px;">
			<div class="">
				<input class="button button-main" type="submit" value="Gửi"/>
			</div>
			<div class="clearfix"></div>
		</div>
	<?php echo form_close(); ?>
	<?php else: ?>
	<?php echo form_open('reset_password',array('method'=>'post','class'=>'col-xs-12 col-sm-12','role'=>'form')); ?>
		<div class="form-group" style="width: 625px;">
			
			<div class="">
				<input style="width: 330px; margin: 0px auto;" type="text" class="form-input" name="p_request" value="<?php echo set_value('p_request') ?>" placeholder="Số điện thoại hoặc email xác thực tài khoản">
				<span class="form-error" ><?php if(isset($form_error)) echo $form_error; ?><?php echo form_error('p_request'); ?></span>
			</div>
			<div class="clearfix"></div>
		</div>
		
			<div class="form-group" style="width: 160px; margin-top: 20px;">
				<div class="">
					<input class="button button-main" type="submit" value="Gửi"/>
				</div>
				<div class="clearfix"></div>
			</div>
			
	<?php echo form_close(); ?>
	<?php endif; ?>
</div>