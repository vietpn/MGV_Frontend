
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a href="#">Giao dịch</a></li>
		<li><a href="#">Thanh toán hóa đơn</a></li>
	</ul>

	<div class="col-md-12 form-center">
		
			<?php echo form_open('/payment_bill/index', array('method' => 'post', 'role' => 'form')); ?>
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			<div class="form-group">
				<label>Nhà cung cấp</label>
				<div class="div-input">
					<select name="provider_code" class="form-input">
						<option value="">Chọn nhà cung cấp</option>
						<?php if(isset($listTelco)): ?>
							<?php foreach($listTelco as $telco): ?>
								<?php if($telco->type == '4'): ?>
									<option value="<?php echo $telco->providerCode; ?>" <?php echo set_select('provider_code', $telco->providerCode, False); ?> <?php echo ($type_tel!=null && $type_tel == $telco->providerCode) ? 'selected="selected"' : '' ; ?>><?php echo $telco->providerName; ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
					<span class="form-error"><?php echo form_error('provider_code'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			 
			<div class="form-group">
				<label>Mã hợp đồng / Số thẻ tín dụng</label>
				<div class="div-input">
					<input name="bill_code" class="form-input" type="text" placeholder="Nhập mã hợp đồng" value="<?php echo set_value('bill_code') ?>">
					<span class="form-error"><?php echo form_error('bill_code'); ?><?php echo (isset($info_error) && $info_error!='') ? $info_error : ''; ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
						
			<div class="form-group button-group">
				<label>&nbsp </label>
				<div class="div-input">
					<input name="step1" type="submit" class="button button-main" value="Tra cứu"/>
					<!--a href="/transaction_manage" target="_parent" class="button button-sub"/>Hủy</a-->
				</div>
				<div class="clearfix"></div>
			</div>
		</form>
		
	</div>
