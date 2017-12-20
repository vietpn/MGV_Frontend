	<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: block;">
	  <div class="modal-dialog" role="document">
		<div class="modal-content modal-security">
			<div class="modal-header modal-header-security">
			<h4 class="modal-title" id="myModalLabel">Thông báo từ hệ thống</h4>
		  </div>
		  <div class="modal-body modal-body-security">
		<?php if(isset($sentMobileFl) || isset($sentEmailFl)): ?>
			<?php echo form_open('transaction_manage/updatePassLv2', array('method' => 'post', 'role' => 'form')); ?>
				
					<div >Chọn hình thức xác thực giao dịch</div>
					<div class="group-security">
						
						<div class="form-group ">
							<label><input id="pass_sec" class="radio-security" type="radio" name="security" value="2" checked>Mật khẩu cấp 2 - Mật khẩu giao dịch</label>
							<div class="security_info">
								<div class="txt_security" >
									<p class="txt_security">Hệ thống đã gửi mã xác nhận tới 
									<?php if(isset($phoneSent)) echo "số điện thoại " . $phoneSent; ?>
									<?php if(isset($emailSent)) echo "email " . $emailSent; ?>
									. Không nhận được <a id="resendOtp">Click gửi lại</a>
									</p>
									<label style="text-align: center;">Mã xác nhận</label>
									<div class="input-security">
										<input name="otp" type="password" class="checkSpace">
										<span class="security-error"><?php echo (isset($wrong_otp)) ? $wrong_otp : ''; ?><?php echo form_error('otp'); ?></span>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
						<input type="submit" class="col-lg-offset-3 col-md-5 col-lg-5 col-xs-6 col-sm-6 btn btn-accept" value="Đồng ý"/>
					</div>
				
			</form>
		<?php else: ?>
			<?php echo form_open('transaction_manage/update_security', array('method' => 'post', 'role' => 'form')); ?>
				<div class="txt_security" >Vui lòng chọn hình thức xác thực giao dịch
					<?php $dataTooltip = 'Khi thực hiện giao dịch hoặc thay đổi thông tin tài khoản, hệ thống sẽ gửi một mã ngẫu nhiên vào email hoặc số điện thoại bạn đã đăng ký nếu bạn khai báo đúng mã với hệ thống thì thao tác mới được thực hiện'; ?>
					<i class="fa fa-exclamation-circle has-tooltip" data-placement="bottom" data-toggle="tooltip" data-original-title="<?php echo $dataTooltip ?>"></i>
				</div>
				<div class="group-security">
					<div class="form-group ">
						<label><input id="phone_sec" class="radio-security" type="radio" name="security" value="1" <?php echo (isset($checkBox) && $checkBox == '1') ? 'checked' : ''; ?>>Số điện thoại</label>
						<div class="phone_info" style="margin-left: 18px; <?php echo (isset($checkBox) && $checkBox == '1') ? 'display:block' : 'display:none'; ?>">
							<?php if($this->session_memcached->userdata['info_user']['phone_status'] == '1')
									echo "Số điện thoại : " . substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 0, (strlen($this->session_memcached->userdata['info_user']['mobileNo']) - 4));
							else
								echo "Số điện thoại chưa được xác thực";
							?>
						</div>
					</div>
					<div class="form-group ">
						<label><input id="pass_sec" class="radio-security" type="radio" name="security" value="2" <?php echo (isset($checkBox) && $checkBox == '2') ? 'checked' : ''; ?>>Mật khẩu cấp 2 - Mật khẩu giao dịch</label>
						
						<div class="security_info" style="margin-left: 18px; float: left; <?php echo (isset($checkBox) && $checkBox == '2') ? 'display:block' : 'display:none'; ?>">
							<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
								<div class="form-group">
									<label>Mật khẩu giao dịch</label>
									<div class="input-security">
										<input name="passLv2" type="password" class="checkSpace">
										<span class="security-error"><?php echo isset($error['passLv2']) ? $error['passLv2'] : ''; ?></span>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
								<div class="form-group">
									<label>Nhập lại mật khẩu giao dịch</label>
									<div class="input-security">
										<input name="rePasLv2" type="password" class="checkSpace">
										<span class="security-error"><?php echo isset($error['rePasLv2']) ? $error['rePasLv2'] : ''; ?></span>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
								<div class="form-group">
									<label>Hình thức xác thực</label>
									<div class="input-security">
										<select name='sub_met'>
											<?php if($this->session_memcached->userdata['info_user']['phone_status'] == '1') : ?>
												<option value='1' <?php echo (isset($selectBox) && $selectBox == 1) ? 'selected' : ''; ?> ><?php echo substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 3, 5) ?></option>
											<?php else: ?>
												<option value='1' <?php echo (isset($selectBox) && $selectBox == 1) ? 'selected' : ''; ?>>Số điện thoại</option>
											<?php endif; ?>
											<?php if($this->session_memcached->userdata['info_user']['email_status'] == '1') : ?>
												<option value='2' <?php echo (isset($selectBox) && $selectBox == 2) ? 'selected' : ''; ?>><?php echo substr_replace($this->session_memcached->userdata['info_user']['email'], '****', 3, 5) ?></option>
											<?php else: ?>
												<option value='2' <?php echo (isset($selectBox) && $selectBox == 2) ? 'selected' : ''; ?>>Email</option>
											<?php endif; ?>
										</select>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="" style="margin-top: 10px; margin-bottom: 20px;">Nên lựa chọn hình thức xác thực số điện thoại để tăng tính bảo bật cho giao dịch </div>
				<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
					<input type="submit" class="col-lg-offset-3 col-md-5 col-lg-5 col-xs-6 col-sm-6 btn btn-accept" value="Đồng ý"/>
				</div>
			</form>
		<?php endif; ?>
	
	</div>
		</div>
	  </div>
	</div>
<div class="modal-backdrop fade in"></div>
