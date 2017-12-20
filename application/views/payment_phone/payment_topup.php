
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a>Giao dịch</a></li>
		<li><a>Nạp điện thoại</a></li>
	</ul>

	<div class="col-md-12 form-center">
		
			<?php echo form_open('/payment_phone/payment_topup', array('method' => 'post', 'role' => 'form')); ?>
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			<input type="hidden" name="prefix" value="<?php echo $post['phone']; ?>">
			<?php if($this->session_memcached->userdata['info_user']['security_method'] == '1'): ?>
				<div class="form-group" style="width: 630px;">Hệ thống đã gửi OTP đến số điện thoại <?php echo substr_replace($this->session_memcached->userdata['info_user']['mobileNo'], '****', 0, (strlen($this->session_memcached->userdata['info_user']['mobileNo']) - 4)); ?>. Không nhận được OTP <a id="resendOtp">Click gửi lại</a></div>
			<?php endif; ?>
			<div class="form-group">
                 <label>Nhà cung cấp mã thẻ</label>
                 <div class="div-input">
                     <span style="line-height: 40px;"><?php 
						if(isset($listTelco))
						{
							foreach($listTelco as $telco)
							{
								if($telco->providerCode == $post['provider_code'])
									echo $telco->providerName;
							}
						}
					 ?></span>
                 </div>
                 <div class="clearfix"></div>
             </div>
			 
			 <div class="form-group">
                 <label>Mệnh giá thẻ (đ)</label>
                 <div class="div-input">
                     <span style="line-height: 40px;"><?php echo number_format($post['amount']); ?></span>
                 </div>
                 <div class="clearfix"></div>
             </div>
						
			<?php if($this->session_memcached->userdata['info_user']['security_method'] == '1'): ?>
				<div class="form-group">
					<label>OTP</label>
					<div class=" div-input">
						<input name="otp" class="form-input" type="password" placeholder="Nhập mã xác thực" autocomplete="off">
						<span class="form-error"><?php echo isset($error_otp) ? $error_otp : ""; ?><?php echo form_error('otp'); ?></span>
					</div>
					<div class="clearfix"></div>
				</div>
			<?php else: ?>
				<div class="form-group">
					<label>Mật khẩu cấp 2</label>
					<div class=" div-input">
						<input name="passLv2" class="form-input" type="password" placeholder="Nhập mật khẩu cấp 2" autocomplete="off">
						<span class="form-error"><?php echo isset($error_passLv2) ? $error_passLv2 : ""; ?><?php echo form_error('passLv2'); ?></span>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="form-group">
					<label> &nbsp </label>
						<div class="div-input">
							<div class="link">
							<a href="/reset_pass_lv2">Quên mật khẩu cấp 2</a>
							</div>
						</div>
					<div class="clearfix"></div>
				</div>
			<?php endif; ?>
			
			<div class="form-group button-group">
				<label>&nbsp </label>
				<div class="div-input">
					<input name="step1" type="submit" class="button button-main" value="Hoàn thành"/>
					<a href="/payment_phone" class="button button-sub"/>Hủy bỏ</a>
				</div>
				<div class="clearfix"></div>
			</div>
		</form>
		
	</div>

