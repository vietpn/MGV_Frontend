<div class="container">
	<div class="row">
		<div class="txt_account" >Đổi địa chỉ Email</div>
		<?php if(isset($sentOtp)): ?>
			<?php echo form_open('/change_email/updateEmail', array('method' => 'post', 'role' => 'form')); ?>
		<?php else: ?>
			<?php echo form_open('/change_email/changeEmail', array('method' => 'post', 'role' => 'form')); ?>
		<?php endif; ?>
		<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
		<?php if($this->session_memcached->userdata['info_user']['email_status'] == 1): ?>
			<?php if(isset($sentOtp)): ?>
				<?php if(isset($messSentOtp)): ?>
					<div><?php echo $messSentOtp ?> Không nhận OTP <a>Click gửi lại</a></div>
				<?php endif; ?>
			<?php endif; ?>
			<div class="form-group row">
				<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 none-padding"><b>Email đang sử dụng:</b></div>
				<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 txt-info">
					<input readonly name="email" class="form-control" type="text" value="<?php echo $this->session_memcached->userdata['info_user']['email']; ?>">
				</div>
				<div class="col-md-10 col-lg-10 col-xs-12 col-sm-12">
					<span class="error"><?php echo form_error('email'); ?></span>
				</div>
			</div>
			
			<?php if(isset($sentOtp)): ?>
				<div class="form-group row">
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 none-padding"><b>Email mới:</b></div>
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 txt-info">
						<input name="newemail" class="form-control" type="text" value="<?php echo set_value('newemail', ''); ?>">
					</div>
					<div class="col-md-10 col-lg-10 col-xs-12 col-sm-12">
						<span class="error"><?php echo (isset($wrong_email)) ? $wrong_email : ''; ?><?php echo form_error('newemail'); ?></span>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 none-padding"><b>Nhập OTP:</b></div>
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 txt-info">
						<input name="otp" class="form-control" type="password" autocomplete="off">
					</div>
					<div class="col-md-10 col-lg-10 col-xs-12 col-sm-12">
						<span class="error"><?php echo (isset($wrong_otp)) ? $wrong_otp : ''; ?><?php echo form_error('otp'); ?></span>
					</div>
				</div>
			<?php else: ?>
				<div class="form-group row">
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 none-padding"><b>Hình thức xác thực:</b></div>
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 txt-info">
						<select name="sec_met" class="form-control">
						<?php if($this->session_memcached->userdata['info_user']['email_status'] == 1): ?>
							<option value="2"><?php echo $this->session_memcached->userdata['info_user']['email']; ?></option>
						<?php endif; ?>
						<?php if($this->session_memcached->userdata['info_user']['phone_status'] == 1): ?>
							<option value="1"><?php echo $this->session_memcached->userdata['info_user']['mobileNo']; ?></option>
						<?php endif; ?>
						</select>
					</div>
				</div>
			<?php endif; ?>
			<div class="form-group row">
				<input type="submit" class="col-md-3 col-lg-3 col-xs-6 col-sm-6 btn btn-warning " value="Tiếp tục"/>
				<a class="col-md-3 col-lg-3 col-xs-6 col-sm-6 btn btn-default "/>Hủy bỏ</a>
			</div>
		</form>
		<?php else: ?>
			<?php echo form_open('/change_email/changeEmail', array('method' => 'post', 'role' => 'form')); ?>
				<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
				<?php if(!empty($this->session_memcached->userdata['info_user']['email'])): ?>
					<div class="form-group row">
						<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 none-padding"><b>Email đang sử dụng:</b></div>
						<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 txt-info">
							<input readonly name="email" class="form-control" type="text" value="<?php echo $this->session_memcached->userdata['info_user']['email']; ?>">
						</div>
						<div class="col-md-10 col-lg-10 col-xs-12 col-sm-12">
							<span class="error"><?php echo form_error('email'); ?></span>
						</div>
					</div>
				<?php endif; ?>
			
				<div class="form-group row">
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 none-padding"><b>Email mới:</b></div>
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 txt-info">
						<input name="newemail" class="form-control" type="text">
					</div>
					<div class="col-md-10 col-lg-10 col-xs-12 col-sm-12">
						<span class="error"><?php echo form_error('newemail'); ?></span>
					</div>
				</div>
			
				<div class="form-group row">
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 none-padding"><b>Nhập mật khẩu:</b></div>
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 txt-info">
						<input name="password" class="form-control" type="password" autocomplete="off">
					</div>
					<div class="col-md-10 col-lg-10 col-xs-12 col-sm-12">
						<span class="error"><?php echo form_error('password'); ?></span>
					</div>
				</div>
			
				<div class="form-group row">
					<input type="submit" class="col-md-3 col-lg-3 col-xs-6 col-sm-6 btn btn-warning " value="Cập nhật"/>
					<a class="col-md-3 col-lg-3 col-xs-6 col-sm-6 btn btn-default "/>Hủy bỏ</a>
				</div>
			</form>
		<?php endif; ?>
	</div>
</div>