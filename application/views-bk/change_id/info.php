<div class="container">
	<div class="row">
		<div class="txt_account" >Đổi số điện thoại di động</div>
		<?php if($this->session_memcached->userdata['info_user']['phone_status'] == 1): ?>
			<div></div>
		<?php endif; ?>
		<?php if(isset($sentOtp)): ?>
			<?php echo form_open('/change_phone/updatePhone', array('method' => 'post', 'role' => 'form')); ?>
		<?php else: ?>
			<?php echo form_open('/change_phone/changePhone', array('method' => 'post', 'role' => 'form')); ?>
		<?php endif; ?>
		
		<?php if($this->session_memcached->userdata['info_user']['phone_status'] == 1): ?>
			<?php if(isset($sentOtp)): ?>
				<?php if(isset($messSentOtp)): ?>
					<div><?php echo $messSentOtp ?> Không nhận OTP <a>Click gửi lại</a></div>
				<?php endif; ?>
			<?php endif; ?>
			<div class="form-group row">
				<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 none-padding"><b>Số điện thoại đang sử dụng:</b></div>
				<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 txt-info">
					<input readonly name="phone" class="form-control" type="text" value="<?php echo substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 0, 6); ?>">
					<span class="error"><?php echo form_error('phone'); ?></span>
				</div>
				<div class="col-md-10 col-lg-10 col-xs-12 col-sm-12">
					
				</div>
			</div>
			
			<?php if(isset($sentOtp)): ?>
				<div class="form-group row">
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 none-padding"><b>Số điện thoại mới:</b></div>
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 txt-info">
						<input name="newphone" class="form-control" type="text" value="<?php echo set_value('newphone', ''); ?>">
					</div>
					<div class="col-md-10 col-lg-10 col-xs-12 col-sm-12">
						<span class="error"><?php echo (isset($wrong_phone)) ? $wrong_phone : ''; ?><?php echo form_error('newphone'); ?></span>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 none-padding"><b>Nhập OTP:</b></div>
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 txt-info">
						<input name="otp" class="form-control" type="password">
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
						<?php if($this->session_memcached->userdata['info_user']['phone_status'] == 1): ?>
							<option value="1"><?php echo "Xác thực qua số điện thoại cũ"; //$this->session_memcached->userdata['info_user']['mobileNo']; ?></option>
						<?php endif; ?>
						<?php if($this->session_memcached->userdata['info_user']['email_status'] == 1): ?>
							<option value="2"><?php echo $this->session_memcached->userdata['info_user']['email']; ?></option>
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
			<?php echo form_open('/change_phone/changePhone', array('method' => 'post', 'role' => 'form')); ?>
			
				<?php if(!empty($this->session_memcached->userdata['info_user']['mobileNo'])): ?>
					<div class="form-group row">
						<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 none-padding"><b>Số điện thoại đang sử dụng:</b></div>
						<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 txt-info">
							<input readonly name="phone" class="form-control" type="text" value="<?php echo $this->session_memcached->userdata['info_user']['mobileNo']; ?>">
						</div>
						<div class="col-md-10 col-lg-10 col-xs-12 col-sm-12">
							<span class="error"><?php echo form_error('phone'); ?></span>
						</div>
					</div>
				<?php endif; ?>
			
				<div class="form-group row">
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 none-padding"><b>Số điện thoại mới:</b></div>
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 txt-info">
						<input name="newphone" class="form-control" type="text">
					</div>
					<div class="col-md-10 col-lg-10 col-xs-12 col-sm-12">
						<span class="error"><?php echo form_error('newphone'); ?></span>
					</div>
				</div>
			
				<div class="form-group row">
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 none-padding"><b>Nhập mật khẩu:</b></div>
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 txt-info">
						<input name="password" class="form-control" type="password">
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