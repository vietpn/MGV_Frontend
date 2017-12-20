
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a>Chuyển tiền</a></li>
	</ul>


	<div class="form-center-ex step-chose-two">

		<div class="step-by-step">
			<ol class="progtrckr" data-progtrckr-steps="4">

				<li class="progtrckr-todo step_one"><span class="hidden-xs hidden-sm">Chọn phương thức chuyển tiền</span></li>
				<li class="<?php echo isset($step) && $step == 2 ? 'progtrckr-done' : 'progtrckr-todo' ?> step_two"><span>Khai báo thông tin chuyển tiền</span></li>
				<li class="<?php echo isset($step) && $step == 3 ? 'progtrckr-done' : 'progtrckr-todo' ?> step_three"><span class="hidden-xs hidden-sm">Xác nhận giao dịch hoàn thành</span></li>

				
			</ol>
		</div>
			<?php echo form_open('/transfer/transfer_epurse', array('method' => 'post', 'role' => 'form')); ?>
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			<div class="form-group group-step-height">
				<label class="group-step-height">Số dư khả dụng(đ)</label>
				<div class="div-input group-step-height">
					<span class="color-green balance-transfer"><?php echo isset($balance) ? number_format($balance) : 0 ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
		
			<div class="form-group group-step-height">
				<label class="group-step-height">Hình thức chuyển tiền</label>
				<div class="div-input group-step-height">
					<span><?php if(isset($post['trans_met']))
								{
									switch($post['trans_met'])
									{
										case '1' : echo "Chuyển ví nội bộ";
										break;
										case '2' : echo "Chuyển ví dịc vụ";
										break;
										default: echo "";
										break;
									}
								}
						?>
					</span>
				</div>
				<div class="clearfix"></div>
			</div>

			<?php if(isset($post['trans_met']) && $post['trans_met'] == '1'): ?>
				<div class="form-group group-step-height">
					<label class="group-step-height">Tài khoản nhận</label>
					<div class="div-input group-step-height" style="white-space: nowrap;">
						<span class="hidden-xs hidden-sm" style="display: inline-block; width: 250px;"><?php echo isset($reciverAcc) ? $reciverAcc['username'] : 0 ?></span>
						<span class="hidden-md hidden-lg"><?php echo isset($reciverAcc) ? $reciverAcc['username'] : 0 ?></span>
						<span class="hidden-xs hidden-sm">Họ và tên</span>
						<span class="hidden-xs hidden-sm" style="margin-left: 40px;"><?php echo isset($reciverAcc['fullname']) ? $reciverAcc['fullname'] : "" ?></span>
					</div>
					<div class="clearfix"></div>
					
				</div>
			<?php elseif(isset($post['trans_met']) && $post['trans_met'] == '2'): ?>
				<div class="form-group group-step-height">
					<label class="group-step-height">Dịch vụ</label>
					<div class="div-input group-step-height">
						<span><?php echo isset($post['service_met']) ? $post['service_met'] : "" ?></span>
					</div>
					<div class="clearfix"></div>
				</div>
			<?php endif; ?>
			
			<div class="form-group group-step-height">
				<label class="group-step-height">Số tiền chuyển (đ)</label>
				<div class=" div-input group-step-height">
					<input id="amountTransfer" maxlength="11" name="amount" class="form-input change_currency" 
						type="text" placeholder="Nhập số tiền chuyển" value="<?php echo set_value('amount') ?>" >
					<span class="form-error hidden-xs hidden-sm"><?php echo isset($amount_error) ? $amount_error : ''; ?><?php echo form_error('amount'); ?></span>
					<div class="hidden-md hidden-lg"><?php echo form_error("amount")!="" || $amount_error !="" ? "<style>#amountTransfer{
						border:1px solid #b94207;
						}</style>" : ""; ?></div>
				</div>
				<div class="clearfix"></div>
			</div>
			<?php if(isset($post['trans_met']) && $post['trans_met'] == '1'): ?>
				<div class="form-group group-step-height-fix">
					<label>Người chịu phí</label>
					<div class="div-input group-step-height-fix-child">
					
						<label class="lbl_sm"><input name="pay_fee" class="checkbox_payfee" value="1" checked <?php echo  set_radio('pay_fee', '1', TRUE); ?> type="radio">Người chuyển</label>
						<div class="hidden-md hidden-lg"><br /></div>
						<label class="lbl_sm" style="margin-left: 40px;"><input name="pay_fee" class="checkbox_payfee" value="0" <?php echo  set_radio('pay_fee', '2'); ?> type="radio">Người nhận</label>
						
					</div>
					<div class="clearfix"></div>
				</div>

			<?php endif; ?>
			
			<div class="form-group group-step-height">
				<label class="group-step-height">Phí chuyển tiền (đ)</label>
				<div class="div-input group-step-height">
					<span id="feeTransfer" class="color-green balance-transfer"><?php echo isset($fee) ? number_format($fee) : '0'; ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="form-group group-step-height">
				<label class="group-step-height">Tổng tiền thanh toán (đ)</label>
				<div class="div-input group-step-height">
					<span id="realAmount" class="color-green balance-transfer"><?php echo (isset($fee) && isset($post['amount']) && !empty($post['amount'])) ? number_format($fee + $post['amount']) : '0' ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
				
			<div class="form-group group-step-height">
				<label>Nội dung</label>
				<div class="div-input">
					<input name="note" class="form-input" placeholder="Nội dung chuyển tiền" value="<?php echo set_value('note') ?>" />
					<span class="form-error"><?php echo form_error('note'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
				<input name="step2" type="hidden" class="button button-main" value="2"/>
			<div class="form-group button-group" style="margin-top: 5px;">
				<label class="hidden-xs hidden-sm">&nbsp </label>
				<div class="div-input">
					<input name="step2" type="submit" class="button button-main" value="Tiếp tục"/>
					<!--a id='transfer-step' class="button button-main"> Tiếp tục </a-->
					<a href="/transfer" class="button button-sub"> Hủy </a>
				</div>
				<div class="clearfix"></div>
			</div>
			
		<?php echo form_close(); ?>
		
	</div>

