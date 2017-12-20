
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a>Chuyển tiền</a></li>
	</ul>


	<div class="form-center step-chose-three">

		<div class="step-by-step">
			<ol class="progtrckr" data-progtrckr-steps="4">

				<li class="progtrckr-todo step_one"><span class="hidden-xs hidden-sm">Chọn phương thức chuyển tiền</span></li>
				<li class="<?php echo isset($step) && $step == 2 ? 'progtrckr-done' : 'progtrckr-todo' ?> step_two"><span class="hidden-xs hidden-sm">Khai báo thông tin chuyển tiền</span></li>
				<li class="<?php echo isset($step) && $step == 3 ? 'progtrckr-done' : 'progtrckr-todo' ?> step_three"><span>Xác nhận giao dịch hoàn thành</span></li>

				
			</ol>
		</div>
		<?php if(isset($sentOtp)): ?>
			
			<div class="form-group msg-send-otp-finish">Hệ thống đã gửi OTP đến số điện thoại <?php echo substr_replace($userInfo['mobileNo'], '****', 0, (strlen($userInfo['mobileNo']) - 4)); ?>. Không nhận OTP <a id="resendOtp">Click gửi lại</a></div>
			
		<?php endif; ?>
			<?php echo form_open('/transfer/transfer_epurse', array('method' => 'post', 'role' => 'form')); ?>
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			<div class="form-group">
				<label>Số dư khả dụng (đ)</label>
				<div class="div-input">
					<span class="color-green balance-transfer"><?php echo isset($balance) ? number_format($balance) : 0 ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="form-group">
				<label>Tổng tiền thanh toán (đ)</label>
				<div class="div-input">
					<span class="color-green balance-transfer">
						<?php 
							if(isset($post_2['pay_fee']) && $post_2['pay_fee'] == '1')
							{
								echo (isset($fee)) ? number_format($post_2['amount'] + $fee) : 0;
							}
							else
							{
								echo (isset($fee)) ? number_format($post_2['amount']) : 0;
							}
							 
						?>
					</span>
				</div>
				<div class="clearfix"></div>
			</div>
		
			<?php if(isset($post['trans_met']) && $post['trans_met'] == '1'): ?>
				<div class="form-group">
					<label>Tài khoản nhận</label>
					<div class="div-input">
						<span ><?php echo isset($reciverAcc) ? $reciverAcc['username'] : "" ?></span>
					</div>
					<div class="clearfix"></div>
					
					<label>Họ và tên</label>
					<div class="div-input">
						<span ><?php echo isset($reciverAcc['fullname']) ? $reciverAcc['fullname'] : "" ?></span>
					</div>
					<div class="clearfix"></div>
					
				</div>
			<?php elseif(isset($post['trans_met']) && $post['trans_met'] == '2'): ?>
				<div class="form-group">
					<label>Dịch vụ</label>
					<div class="div-input">
						<span><?php echo isset($post['service_met']) ? $post['service_met'] : "" ?></span>
					</div>
					<div class="clearfix"></div>
				</div>
			<?php endif; ?>
			
			<?php if($userInfo['security_method'] == '1'): ?>
				<div class="form-group">
					<label>Nhập OTP</label>
					<div class=" div-input">
						<input maxlength="11" name="otp" class="form-input otp-new-check" type="password" placeholder="Nhập mã xác nhận" autocomplete="off">
						<span class="form-error hidden-xs hidden-sm"><?php echo isset($error_otp) ? $error_otp : ""; ?><?php echo form_error('otp'); ?></span>
						<div class="hidden-md hidden-lg"><?php echo form_error("otp")!="" || $error_otp!="" ? "<style>.otp-new-check{
							border:1px solid #b94207;
							}</style>" : ""; ?></div>
					</div>
					<div class="clearfix"></div>
				</div>
			<?php else: ?>
				<div class="form-group">
					<label>Mật khẩu cấp 2</label>
					<div class=" div-input">
						<input maxlength="11" name="passLv2" class="form-input password-lv-new" type="password" placeholder="Nhập mật khẩu cấp 2" autocomplete="off">
						<span class="form-error hidden-xs hidden-sm"><?php echo isset($error_passLv2) ? $error_passLv2 : ""; ?><?php echo form_error('passLv2'); ?></span>
						<div class="hidden-md hidden-lg"><?php echo form_error("passLv2")!="" || $error_passLv2!="" ? "<style>.password-lv-new{
							border:1px solid #b94207;
							}</style>" : ""; ?></div>
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
				<input name="step3" type="hidden" class="button button-main" value="3"/>
			<div class="form-group button-group">
				<label>&nbsp </label>
				<div class="div-input">
					<input name="step3" type="submit" class="button button-main" value="Hoàn thành"/>
					<!--a id='transfer-step' class="button button-main"> Tiếp tục </a-->
					<a href="/transfer" class="button button-sub">Hủy</a>
				</div>
				<div class="clearfix"></div>
			</div>
			
		<?php echo form_close(); ?>
		
	</div>
