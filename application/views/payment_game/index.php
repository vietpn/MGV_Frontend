
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a >Giao dịch</a></li>
		<li><a >Nạp tiền game</a></li>
	</ul>

	<div class="col-md-12 form-center">
		
			<?php echo form_open('/payment_game/index', array('method' => 'post', 'role' => 'form')); ?>
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			<div class="form-group">
				<label>Nhà cung cấp game</label>
				<div class="div-input">
					<select id="providerTopupToGame" name="provider_code" class="form-input">
						<option value="">Chọn nhà cung cấp</option>
						<?php if(isset($listTelco)): ?>
							<?php foreach($listTelco as $telco): ?>
								<?php if($telco->type == '3'): ?>
									<option value="<?php echo $telco->providerCode; ?>" <?php echo set_select('provider_code', $telco->providerCode, False); ?>><?php echo $telco->providerName; ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
					<span class="form-error"><?php echo form_error('provider_code'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			 
			<div class="form-group">
				<label>Tài khoản game</label>
				<div class=" div-input">
					<input name="acc_game" class="form-input" type="text" placeholder="Tài khoản game" value="<?php echo set_value('acc_game') ?>" maxlength="50">
					<span class="form-error"><?php echo form_error('acc_game'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="form-group">
				<label>Mệnh giá</label>
				<div class="div-input">
					<select id="topupAmountToGame" name="amount" class="form-input">
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
					<a href="/transaction_manage" target="_parent" class="button button-sub"/>Hủy</a>
				</div>
				<div class="clearfix"></div>
			</div>
		</form>
		
	</div>

