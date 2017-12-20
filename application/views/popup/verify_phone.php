<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: block;">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-security">
			<div class="modal-header modal-header-security">
				<h4 class="modal-title" id="myModalLabel">Thông báo từ hệ thống</h4>
			</div>
			
			<div class="modal-body modal-body-security">

			<?php if($user_info['mobileNo'] == ''): ?>
				<?php echo form_open('transaction_manage/update_phone', array('method' => 'post', 'role' => 'form')); ?>
					<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
					<div class="txt_security" >Chưa có thông tin số điện thoại. Vui lòng cập nhật số điện thoại để xác thực trên MegaV</div>
					
					<div id="btn-sendotp" class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
						<a href="/" class="col-lg-offset-3 col-md-5 col-lg-5 col-xs-6 col-sm-6 btn btn-accept">Tiếp tục</a>
					</div>
				<?php echo form_close(); ?>
			<?php else: ?>
				<div class="security_info" style="float: left; text-align: center;">
					<div class="txt_security" >Vui lòng xác thực thông tin số điện thoại để hoàn tất đăng nhập trên MegaV</div>
					
					<div id="btn-sendotp" class="col-md-12 col-lg-12 col-xs-12 col-sm-12" style="<?php echo (isset($sendOtp)) ? 'display: none' : '' ; ?>">
						<p class="txt_security">Số điện thoại đang sử dụng <?php echo substr_replace($user_info['mobileNo'], '****', 3, 5); ?></p>
						<a style="margin-left: 30%;" id='sendOtpVerifyMobile' class="col-lg-offset-3 col-md-5 col-lg-5 col-xs-6 col-sm-6 btn btn-accept" />Gửi OTP</a>
					</div>
				
				<div id="otp-form" style="<?php echo (isset($sendOtp)) ? '' : 'display: none' ; ?>">
					<?php echo form_open('transaction_manage/verify_phone', array('method' => 'post', 'role' => 'form')); ?>
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
							<span class="txt_security">Hệ thống đã gửi OTP tới số điện thoại <?php echo substr_replace($user_info['mobileNo'], '****', 3, 5); ?>. </span>
							<p class="txt_security">Không nhận được OTP <a id="resendOtp">Click gửi lại</a></p>
							<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
								<div class="form-group ">
									<label style="text-align: right; padding-right: 25px;">Nhập OTP</label>
									<div class="input-security">
										<input name='otp' class="form-control" type="password" autocomplete="off">
										<span class="security-error"><?php echo (isset($error)) ? $error : '' ?></span>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						
						
						<div id="btn-sendotp" class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
							<input type="submit" class="btn btn-accept" value="Xác nhận"/>
						</div>
						
					<?php  echo form_close(); ?>
				</div>
			</div>
			<?php endif; ?>

			</div>
		</div>
	</div>
</div>
<div class="modal-backdrop fade in"></div>