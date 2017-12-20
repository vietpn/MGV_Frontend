
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a >Giao dịch</a></li>
		<li><a >Nạp điện thoại</a></li>
	</ul>

	<div class="col-md-12 form-center">
		
			<?php echo form_open('/payment_phone/index', array('method' => 'post', 'role' => 'form','id'=>'id_pay_phone')); ?>
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />	
			<div class="form-group">
				<label>Số điện thoại</label>
				<div class=" div-input">
					<input onkeyup="formatPhone(this, this.value);" id="phone" name="phone" class="form-input" type="text" placeholder="Số điện thoại" value="<?php echo set_value('phone') ?>" maxlength="11">
					<span class="form-error form-error-phone"><?php echo form_error('phone'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="form-group ">
				<label>Loại thuê bao</label>
				<div class="div-input">
					<select id="phone_type" name="phone_type" class="form-input">
						<option value=""><?php echo "Chọn hình thức thuê bao"; ?></option>
						<option value="1" <?php echo set_select('phone_type', '1', False); ?>><?php echo "Trả trước"; ?></option>
						<option value="2" <?php echo set_select('phone_type', '2', False); ?>><?php echo "Trả sau"; ?></option>
					</select>
					<span class="form-error"><?php echo form_error('phone_type'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>

			<div class="form-group">
                 <label>Nhà cung cấp mã thẻ</label>
                 <div class="div-input">
                     <select id="providerCDVToPhone" name="provider_code" class="form-input">
                         <option value="">Chọn nhà cung cấp thẻ</option>
                         <?php if(isset($listTelco)): ?>
                             <?php foreach($listTelco as $telco): ?>
                                 <?php // if($telco->type == '1'): ?>
                                     <option value="<?php echo $telco->providerCode; ?>" <?php echo set_select('provider_code', $telco->providerCode, False); ?>><?php echo $telco->providerName; ?></option>
                                 <?php // endif; ?>
                             <?php endforeach; ?>
                         <?php endif; ?>
                     </select>
                     <span class="form-error"><?php echo form_error('provider_code'); ?></span>
                 </div>
                 <div class="clearfix"></div>
             </div>
			
			<div class="form-group">
				<label>Mệnh giá</label>
				<div class="div-input" id="amountTopup">
					 <select id="topupAmountToPhone" name="amount" class="form-input">
						<?php echo isset($option) ? $option : '<option value="">Chưa có thông tin nhà cung cấp thẻ</option>'; ?>
					 </select>
                     <span class="form-error"><?php echo isset($err_amount) ? $err_amount : ""; ?><?php echo form_error('amount'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
						
			<div class="form-group">
                <label>Tiền thanh toán (đ)</label>
                <div class="div-input">
                    <span id="totalAmount" class="amount"><?php echo isset($totalAmount) ? number_format($totalAmount) : 0 ?></span>
                </div>
                <div class="clearfix"></div>
            </div>
			
			<div class="form-group button-group">
				<label>&nbsp </label>
				<div class="div-input">
					<input name="step1" type="submit" class="button button-main" value="Tiếp tục"/>
					<a href="/payment_phone" class="button button-sub"/>Hủy bỏ</a>
				</div>
				<div class="clearfix"></div>
			</div>
		</form>
		
	</div>


