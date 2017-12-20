
		<?php if(isset($sentMobileFl) || isset($sentEmailFl)): ?>
			<?php echo form_open('page/index/updatePassLv2', array('method' => 'post', 'role' => 'form')); ?>
				<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
				<div class="txt_account" >Chọn hình thức xác thực giao dịch</div>
				<div class="form-group ">
					<label><input id="pass_sec" type="radio" name="security" value="2" checked>Mật khẩu cấp 2 - Mật khẩu giao dịch</label>
					
					<p>Hệ thống đã gửi mã xác nhận tới 
					<?php if(isset($phoneSent)) echo "số điện thoại " . $phoneSent; ?>
					<?php if(isset($emailSent)) echo "email " . $emailSent; ?>
					. Không nhận được <a id="resendOtp">Click gửi lại</a>
					</p>
					<p>Mã xác nhận <input name="otp" type="password" class="checkSpace" autocomplete="off"></p>
					<p><?php echo (isset($wrong_otp)) ? $wrong_otp : ''; ?><?php echo form_error('otp'); ?></p>
				</div>
				<div class="col-md-8 col-lg-8 col-xs-12 col-sm-12">
					<input type="submit" class="col-md-5 col-lg-5 col-xs-6 col-sm-6 btn btn-warning " value="Đồng ý"/>
				</div>
			</form>
		<?php else: ?>
			<?php echo form_open('page/index/update_security', array('method' => 'post', 'role' => 'form')); ?>
				<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
				<div class="txt_account" >Vui lòng chọn hình thức xác thực giao dịch
					<?php $dataTooltip = 'Khi thực hiện giao dịch hoặc thay đổi thông tin tài khoản, hệ thống sẽ gửi một mã ngẫu nhiên vào email hoặc số điện thoại bạn đã đăng ký nếu bạn khai báo đúng mã với hệ thống thì thao tác mới được thực hiện'; ?>
					<i class="fa fa-exclamation-circle has-tooltip" data-placement="bottom" data-toggle="tooltip" data-original-title="<?php echo $dataTooltip ?>"></i>
				</div>
				<div class="group-security">
					<div class="form-group ">
						<label><input id="phone_sec" type="radio" name="security" value="1" <?php echo (isset($checkBox) && $checkBox == '1') ? 'checked' : ''; ?>>Số điện thoại</label>
						<div class="phone_info" style="<?php echo (isset($checkBox) && $checkBox == '1') ? 'display:block' : 'display:none'; ?>">
							<?php if($this->session_memcached->userdata['info_user']['phone_status'] == '1')
									echo "Số điện thoại : " . substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 3, 5);
							else
								echo "Số điện thoại chưa được xác thực";
							?>
						</div>
					</div>
					<div class="form-group ">
						<label><input id="pass_sec" type="radio" name="security" value="2" <?php echo (isset($checkBox) && $checkBox == '2') ? 'checked' : ''; ?>>Mật khẩu cấp 2 - Mật khẩu giao dịch</label>
						
						<div class="security_info" style="<?php echo (isset($checkBox) && $checkBox == '2') ? 'display:block' : 'display:none'; ?>">
							<p>Mật khẩu giao dịch<input name="passLv2" type="password" class="checkSpace" autocomplete="off"></p>
							<p><?php echo isset($error['passLv2']) ? $error['passLv2'] : ''; ?></p>
							<p>Nhập lại mật khẩu giao dịch<input name="rePasLv2" type="password" name="security" autocomplete="off"></p>
							<p><?php echo isset($error['rePasLv2']) ? $error['rePasLv2'] : ''; ?></p>
							Hình thức xác thực
							<select name='sub_met'>
								<?php if($this->session_memcached->userdata['info_user']['phone_status'] == '1') : ?>
									<option value='1'><?php echo substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 3, 5) ?></option>
								<?php endif; ?>
								<?php if($this->session_memcached->userdata['info_user']['email_status'] == '1') : ?>
									<option value='2'><?php echo substr_replace($this->session_memcached->userdata['info_user']['email'], '****', 3, 5) ?></option>
								<?php endif; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="">Nên lựa chọn hịnh thức xác thực số điện thoại để tăng tính bảo bật cho giao dịch </div>
				<div class="col-md-8 col-lg-8 col-xs-12 col-sm-12">
					<input type="submit" class="col-md-5 col-lg-5 col-xs-6 col-sm-6 btn btn-warning " value="Đồng ý"/>
				</div>
			</form>
		<?php endif; ?>
	

