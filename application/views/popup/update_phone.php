<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: block;">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-security">
			<div class="modal-header modal-header-security">
				<h4 class="modal-title" id="myModalLabel">Thông báo từ hệ thống</h4>
			</div>
			
			<div class="modal-body modal-body-security">
				<?php echo form_open('transaction_manage/update_phone', array('method' => 'post', 'role' => 'form')); ?>
					<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
					<div class="txt_security" style="text-align: center; margin-bottom: 0;">Chưa có thông tin số điện thoại. </div>
					<div class="txt_security" style="text-align: center">Vui lòng cập nhật số điện thoại để xác thực trên MegaV</div>
				
					<div class="group-security">
						<div class="security_info" style="text-align: center;">
						<?php if(!isset($sendOtp)): ?>
							<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12" >
								<div class="form-group">
									<label style="text-align: center;">Số điện thoại</label>
									<div class="input-security">
										<input name='mobile' type="text">
										<span class="security-error"><?php echo (isset($error['mobile'])) ? $error['mobile'] : '' ?></span>
									</div>
									<div class="clearfix"></div>
								</div>
								<input type="hidden" name="sendOtp" value="Tiếp tục"/>
								<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
									<input type="submit" class="col-lg-offset-3 col-md-5 col-lg-5 col-xs-5 col-sm-5 btn btn-accept" name="sendOtp" value="Tiếp tục"/>
								</div>
							</div>
						<?php else: ?>
							<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12" >
								<?php echo form_open('transaction_manage/verify_phone', array('method' => 'post', 'role' => 'form')); ?>
									<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
									<span>Hệ thống đã gửi OTP tới số điện thoại <?php echo substr_replace($user_info['mobileNo'], '****', 3, 5); ?>. </span>
									<p>Không nhận được OTP <a id="resendOtp">Click gửi lại</a></p>
									<div class="form-group">
										<label style="margin-right: 25px; text-align: right;">Nhập OTP</label>
										<div class="input-security">
											<input name='otp' type="password" autocomplete="off">
											<span class="security-error"><?php echo (isset($error['otp'])) ? $error['otp'] : '' ?></span>
										</div>
										<div class="clearfix"></div>
									</div>
									<input type="hidden" name="updatePhone" value="Xác nhận"/>
									<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-group">
										<input style="margin: 0 auto;" type="submit" class="col-md-5 col-lg-5 col-xs-5 col-sm-5 btn btn-accept" name="updatePhone" value="Xác nhận"/>
									</div>
								<?php  echo form_close(); ?>
							</div>
						<?php endif; ?>
						</div>
					</div>
				<?php echo form_close(); ?>
			
			</div>
		</div>
	</div>
</div>
<div class="modal-backdrop fade in"></div>