
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a>Chuyển tiền</a></li>
	</ul>


	<div class="form-center step-chose-two two-transfer-new">

		<div class="step-by-step">
			<ol class="progtrckr" data-progtrckr-steps="4">
				<li class="progtrckr-done step_one"><span>Chọn phương thức chuyển tiền</span></li>
				<li class="<?php echo isset($step) && $step == 2 ? 'progtrckr-done' : 'progtrckr-todo' ?> step_two"><span class="hidden-xs hidden-sm">Khai báo thông tin chuyển tiền</span></li>
				<li class="<?php echo isset($step) && $step == 3 ? 'progtrckr-done' : 'progtrckr-todo' ?> step_three"><span class="hidden-xs hidden-sm">Xác nhận giao dịch hoàn thành</span></li>
				
			</ol>
		</div>
		
			<?php echo form_open('/transfer/transfer_epurse', array('method' => 'post', 'role' => 'form')); ?>
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			<div class="form-group ">
				<label>Số dư khả dụng (đ)</label>
				<div class=" div-input">
					<span class="color-green balance-transfer"><?php echo isset($balance) ? number_format($balance) : 0 ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
		
			<div class="form-group ">
				<label>Hình thức chuyển tiền</label>
				<div class="div-input">
					<select name="trans_met" class="form-input">
						<option value="1"><?php echo "Chuyển ví nội bộ"; ?></option>
						<!--option value="2"><?php echo "Chuyển ví dịch vụ"; ?></option-->
					</select>
				</div>
				<div class="clearfix"></div>
			</div>

			
			<!--div class="form-group ">
				<label>Dịch vụ</label>
				<div class="div-input">
					<select name="service_met" class="form-input">
						<option value="1"><?php echo "MGC"; ?></option>
						<option value="2"><?php echo "Thanh toán 247"; ?></option>
						<option value="3"><?php echo "MegaPay"; ?></option>
						<option value="4"><?php echo "Shipantoan"; ?></option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div-->
			
			<div class="form-group ">
				<label>Số điện thoại</label>
				<div class=" div-input">
					<input name="phone" id="acc-name-in" class="form-input acc-name-in" type="text" placeholder="Nhập số điện thoại của tài khoản ví nhận" 
							value="<?php echo set_value('phone') ?>">
					<span class="form-error"><?php echo isset($acc_error) ? $acc_error : ""; ?><?php echo form_error('phone'); ?></span>
					<div class="hidden-md hidden-lg"><?php echo form_error("phone")!="" || $acc_error !="" ? "<style>.acc-name-in{
						border:1px solid #b94207;
						}</style>" : ""; ?></div>
				</div>
				<div class="clearfix"></div>
			</div>
			<!--div class="form-group ">
				<label>Tên tài khoản</label>
				<div class=" div-input">
					<input name="accName" id="acc-name-in" class="form-input acc-name-in" type="text" placeholder="Nhập tên tài khoản ví nhận" 
							value="<?php echo set_value('accName') ?>">
					<span class="form-error"><?php echo isset($acc_error) ? $acc_error : ""; ?><?php echo form_error('accName'); ?></span>
					<div class="hidden-md hidden-lg"><?php echo form_error("accName")!="" || $acc_error !="" ? "<style>.acc-name-in{
						border:1px solid #b94207;
						}</style>" : ""; ?></div>
				</div>
				<div class="clearfix"></div>
			</div-->
			<input name="step1" type="hidden" class="button button-main" value="1"/>
			<div class="form-group button-group" style="margin-top: 5px;">
				<label class="hidden-xs hidden-sm">&nbsp </label>
				<div class="div-input">
					<input name="step1" type="submit" class="button button-main" value="Tiếp tục"/>
					<!--a id='transfer-step' class="button button-main"> Tiếp tục </a-->
					<a href="/transfer" class="button button-sub"> Hủy </a>
				</div>
				<div class="clearfix"></div>
			</div>
		</form>
		
	</div>

