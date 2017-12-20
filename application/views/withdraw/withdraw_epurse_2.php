
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a>Rút tiền</a></li>
	</ul>


	<div class="form-center step-chose-three">

		
		<div class="step-by-step">
			<ol class="progtrckr" data-progtrckr-steps="4">
				<li class="progtrckr-todo step_one"><span class="hidden-xs hidden-sm">Chọn phương thức rút tiền</span></li>
				<li class="<?php echo isset($step) && $step == 2 ? 'progtrckr-done' : 'progtrckr-todo' ?> step_two"><span class="<?php echo isset($step) && $step != 2 ? 'hidden-xs hidden-sm' : '' ?>">Khai báo thông tin rút</span></li>
				<li class="<?php echo isset($step) && $step == 3 ? 'progtrckr-done' : 'progtrckr-todo' ?> step_three"><span class="<?php echo isset($step) && $step != 3 ? 'hidden-xs hidden-sm' : '' ?>">Xác nhận giao dịch - hoàn thành</span></li>
			</ol>
		</div>
		
			<?php echo form_open('/withdraw/withdraw_epurse_offline', array('method' => 'post', 'role' => 'form','id'=> 'form_epurse_offline')); ?>
		<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
        <?php if(isset($sentOtp)): ?>
            <div class="form-group msg-otp-return" style="white-space: nowrap;">Hệ thống đã gửi OTP đến số điện thoại <?php echo substr_replace($userInfo['mobileNo'], '****', 0, (strlen($userInfo['mobileNo']) - 4)); ?>. Không nhận được OTP <a id="resendOtp">Click gửi lại</a></div>
        <?php endif; ?>

			<div class="form-group ">
				<label>Số dư khả dụng(đ)</label>
				<div class=" div-input">
					<span class="balance widthdraw_balance"><?php echo isset($balance) ? number_format($balance) : 0 ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="form-group ">
				<label>Số tiền rút(đ)</label>
				<div class=" div-input">
					<span class="balance widthdraw_balance"><?php echo isset($post['amount']) ? number_format($post['amount']) : 0 ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="form-group ">
				<label>Ngân hàng</label>
				<div class=" div-input">
					<span class="balance widthdraw_balance">
						<?php
						log_message('error', 'list bank view ' . print_r($listBankP2, true));
							foreach($listBankP2 as $bank){
								if(isset($post_act['widthdraw_met']) && $post_act['widthdraw_met']==1){
									if(isset($post['provider_code']) && $post['provider_code'] == $bank->recId)
									{
										echo $bank->providerName;
									}
								}elseif(isset($post_act['widthdraw_met']) && $post_act['widthdraw_met']==2) {
									if(isset($post['provider_code']) && $post['provider_code'] == $bank->bankCode)
									{
										echo $bank->bankName;
									}
								} 
								elseif(isset($post_act['widthdraw_met']) && $post_act['widthdraw_met']==3) {
									if(isset($post['provider_code']) && $post['provider_code'] == $bank->bankcode)
									{
										echo $bank->bankName;
									}
								}
							}
						?>
					</span>
				</div>
				<div class="clearfix"></div>
			</div>
			<?php if(isset($post_act['widthdraw_met']) && $post_act['widthdraw_met']!=3): ?>
			<div class="form-group ">
				<label><?php echo ($post_act['widthdraw_met']==2) ? 'Số thẻ' : 'Số tài khoản';?></label>
				<div class=" div-input">
					<span class="balance widthdraw_balance"><?php echo isset($post['bankAcc']) ? $post['bankAcc'] : 0 ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			<?php endif; ?>
			<?php if($this->session_memcached->userdata['info_user']['security_method'] == '1'): ?>
				<div class="form-group">
					<label>Nhập OTP</label>
					<div class=" div-input">
						<input name="otp" class="form-input" type="password" placeholder="Nhập mã xác nhận" autocomplete="off">
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
			<input name="step2" type="hidden"  value="2"/>
			<div class="form-group button-group">
				<label>&nbsp </label>
				<div class="div-input">
					<input name="step2" type="submit" class="button button-main" value="Tiếp tục"/>
					<a href="/withdraw" class="button button-sub"/>Hủy</a>
				</div>
				<div class="clearfix"></div>
			</div>
		</form>
		
	</div>
	<script type="text/javascript" language="javascript">
		
		$(document).ready(function() {
		    $('input[type=submit]').click(function() {
			    $(this).attr('disabled', 'disabled');
			    $(this).parents('form').submit()
			})
		});
	</script>
