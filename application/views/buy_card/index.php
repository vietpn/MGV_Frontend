
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a href="#">Giao dịch</a></li>
		<li><a href="#">Mua mã thẻ</a></li>
	</ul>

	<div class="col-md-12 form-center">
		
			<?php echo form_open('/buy_card/buy_card_cdv', array('method' => 'post', 'role' => 'form')); ?>
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			<!--div class="form-group ">
				<label>Nhà cung cấp mã thẻ</label>
				<div class="div-input">
					<select name="widthdraw_met" class="form-input">
						<option value="0"><?php echo "Chọn hình thức rút tiền"; ?></option>
						<option value="1"><?php echo "Tài khoản ngân hàng"; ?></option>
						<option value="2"><?php echo "Tài khoản liên kết ví"; ?></option>
					</select>
				</div>
				<div class="clearfix"></div>
			</div-->

			<div class="form-group">
                 <label>Nhà cung cấp mã thẻ</label>
                 <div class="div-input">
                     <select id="providerTopup" name="provider_code" class="form-input">
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
				<div class="div-input">
					 
					 <select id="buyCardAmount" name="amount" class="form-input">
						<?php echo isset($option) ? $option : '<option value="">Chưa có thông tin nhà cung cấp thẻ</option>'; ?>
					 </select>
					 
                     <span class="form-error"><?php echo form_error('amount'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="form-group">
				<label>Số lượng</label>
				<div class=" div-input">
					<input id="buyCardQuantity" name="quantity" class="form-input" type="text" placeholder="Nhập số thẻ" maxlength="12" value="<?php echo set_value('quantity', '1') ?>">
					<span class="form-error"><?php echo isset($err_quantity) ? $err_quantity : ''; ?><?php echo form_error('quantity'); ?></span>
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
					<input <?php echo (isset($balance) && $balance == 0) ? 'disabled="disabled" style="cursor: not-allowed;"' : ''; ?> name="step1" type="submit" class="button button-main" value="Tiếp tục"/>
					<a href="/transaction_manage" target="_parent" class="button button-sub"/>Hủy</a>
				</div>
				<div class="clearfix"></div>
			</div>
		</form>
		
	</div>
