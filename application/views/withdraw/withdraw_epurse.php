
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a>Rút tiền</a></li>
	</ul>


	<div class="form-center step-chose-two">

		
		<div class="step-by-step">
			<ol class="progtrckr" data-progtrckr-steps="4">
				<li class="progtrckr-todo step_one"><span class="hidden-xs hidden-sm">Chọn phương thức rút tiền</span></li>
				<li class="<?php echo isset($step) && $step == 2 ? 'progtrckr-done' : 'progtrckr-todo' ?> step_two"><span>Khai báo thông tin rút</span></li>
				<li class="<?php echo isset($step) && $step == 3 ? 'progtrckr-done' : 'progtrckr-todo' ?> step_three"><span class="hidden-xs hidden-sm">Xác nhận giao dịch - hoàn thành</span></li>
				
			</ol>
		</div>
		
			<?php echo form_open('/withdraw/withdraw_epurse', array('method' => 'post', 'role' => 'form')); ?>
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			<div class="form-group ">
				<label>Số dư khả dụng (đ)</label>
				<div class=" div-input">
					<span class="balance widthdraw_balance"><?php echo isset($balance) ? number_format($balance) : 0 ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
		
			<div class="form-group ">
				<label>Hình thức rút tiền</label>

				<div class="div-input">
					<select id="widthdraw_met" name="widthdraw_met" class="form-input">
						<option value=""><?php echo "Chọn hình thức rút tiền"; ?></option>
						
						<option value="2" <?php echo (isset($post_act['widthdraw_met']) && $post_act['widthdraw_met']==2) ? 'selected="selected"' : '';?>><?php echo "Rút tiền nhanh"; ?></option>
						<option value="3" <?php echo (isset($post_act['widthdraw_met']) && $post_act['widthdraw_met']==3) ? 'selected="selected"' : '';?>><?php echo "Rút tiền qua tài khoản liên kết"; ?></option>
						
					</select>
					<span class="form-error"><?php echo form_error('widthdraw_met'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>

			<div class="form-group">
                 <label>Ngân hàng</label>
                 <div class="div-input">
                     <select id="providerCode" name="provider_code" class="form-input check_card">
                         <option value="">Chọn ngân hàng</option>
                         <?php if(isset($listBank)): ?>
                             <?php foreach($listBank as $bank): ?>
                                 <?php // if($bank->type == '2'): ?>
										<option value="<?php 	if(isset($post_act['widthdraw_met']) && $post_act['widthdraw_met']=='1'){
													echo $bank->recId; 
												}elseif(isset($post_act['widthdraw_met']) && $post_act['widthdraw_met']=='2') {
													echo $bank->epurseBankCode;
												} elseif(isset($post_act['widthdraw_met']) && $post_act['widthdraw_met']=='3') {
													echo $bank->bankcode;
												}  ?>"  

												<?php 
												if(isset($post_act['widthdraw_met']) && $post_act['widthdraw_met']=='1') {
												  $bank_id = $bank->recId;
												  $bank_code = $bank->bankCode;
												  $bank_name = $bank->bankName;
												 }elseif (isset($post_act['widthdraw_met']) && $post_act['widthdraw_met']=='2') {
												 	$bank_id = $bank->epurseBankCode;
												 	$bank_code = $bank->epurseBankCode;
												 	$bank_name = $bank->providerName;
												 } else{
												 	$bank_id = $bank->bankcode;
												 	$bank_code = $bank->bankcode;
												 	$bank_name = $bank->bankName;
												 }
												echo set_select('provider_code', $bank_id , False); ?>
										data-target="<?php echo isset($bank->isCard) ? $bank->isCard : '0'; ?>"
										data-code="<?php echo $bank_code; ?>" data-check="<?php if(isset($post_act['widthdraw_met'])){ echo $post_act['widthdraw_met'];}?>" ><?php echo $bank_name; ?></option>
									
								 <?php // endif; ?>
                             <?php endforeach; ?>
                         <?php endif; ?>
                     </select>
                     <input type="hidden" value="<?php echo (isset($post_act['bankAcc']) && $post_act['bankAcc']!='') ? $post_act['bankAcc'] : '';?>" id="bankAccHidden" />
                     <span class="form-error"><?php echo form_error('provider_code'); ?></span>
                 </div>
				 <label id="add_bank_acc" class="create_realy hidden-xs hidden-sm" ><a href="javascript:void(0);" style="<?php echo (isset($post_act['widthdraw_met']) && $post_act['widthdraw_met']=='1') ? 'display:block;' : 'display:none;';?>">Thêm tài khoản</a></label>
				 <label id="add_bank_acc_firm" class="create_realy_firm hidden-xs hidden-sm" ><a href="javascript:void(0);" style="color: #2794cc; <?php echo (isset($post_act['widthdraw_met']) && $post_act['widthdraw_met']=='2') ? 'display:block;' : 'display:none;';?>">Thêm tài khoản</a></label>
				 <label id="add_bank_map" style="margin-right: -420px; float: right; font-size: 14px;" class="hidden-xs hidden-sm" ><a  style="color: #2794cc; <?php echo (isset($post_act['widthdraw_met']) && $post_act['widthdraw_met']=='3') ? 'display:block;' : 'display:none;';?>" href="javascript:void(0);" >Liên kết tài khoản</a></label>
                 <div class="clearfix"></div>
             </div>
			
			<div class="form-group" id="bank_acc" style="<?php echo (isset($post_act['widthdraw_met']) && $post_act['widthdraw_met']==3) ? 'display:none;' : '';?>">
				<label class="firmCardNumb"><?php echo ($post_act['widthdraw_met']==2) ? 'Số thẻ' : 'Số tài khoản';?></label>
				<div class=" div-input">
					 <select name="bankAcc" class="form-input" id="back-acc-new">
                         <option value=""><?php echo ($post_act['widthdraw_met']==2) ? 'Chọn số thẻ' : 'Chọn tài khoản';?></option>
                         
                     </select>
                     <span class="form-error"><?php echo form_error('bankAcc'); ?></span>
				</div>
				
				<div class="clearfix"></div>
			</div>
			
			<div class="form-group">
				<label>Số tiền rút (đ)</label>
				<div class=" div-input">
					<input id="withdrawAmount" name="amount" class="form-input change_currency" type="text" placeholder="Nhập số tiền muốn rút" maxlength="12" value="<?php echo set_value('amount') ?>">
					<span class="form-error"><?php echo isset($err_amount) ? $err_amount : "" ?><?php echo form_error('amount'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="form-group">
                <label>Phí rút tiền (đ)
				
                    <!--i id="feewithdraw" style="<?php echo ($post_act['widthdraw_met']!='1') ? "display:none" : '' ?>" class="fa fa-info-circle has-tooltip" data-placement="top" data-toggle="tooltip" data-original-title="Phí rút tiền: 7,700 đ/ 1 giao dịch (không phụ thuộc giá trị giao dịch)"></i-->
				
                    <!--i id="feewithdrawfast" style="<?php echo ($post_act['widthdraw_met']!='2') ? "display:none" : '' ?>" class="fa fa-info-circle has-tooltip" data-placement="top" data-toggle="tooltip" data-original-title="Phí rút tiền: 3,300đ/ 1 giao dịch (không phụ thuộc giá trị giao dịch)"></i-->
				
                </label>
                <div class="div-input">
                    <span id="feeWidthdraw" class="amount"><?php echo isset($fee) ? number_format($fee) : 0 ?></span>
                </div>
                <div class="clearfix"></div>
            </div>
			<input name="step1" type="hidden"  value="1"/>
			<div class="form-group button-group">
				<label class="hidden-xs hidden-sm">&nbsp </label>
				<div class="div-input">
					<input name="step1" type="submit" class="button button-main" value="Tiếp tục"/>
					<a href="/withdraw" class="button button-sub"/>Hủy</a>
				</div>
				<div class="clearfix"></div>
			</div>
		</form>
		
	</div>


	<script type="text/javascript" language="javascript">
		$(document).ready(function(){
			$('body').on('change','#providerCode',function(){
				var csrf = $('input[name="csrf_megav_name"]').val();
				var code = $('option:selected', this).attr('data-code'); 
				var withdraw = $('option:selected', this).attr('data-check'); 
				$.ajax({
					url: '/withdraw/withdrawGetAjaxBankAcc',
					type: 'POST',
					dataType: 'json',
					data: {code: code,withdraw:withdraw,csrf_megav_name:csrf},
				})
				.done(function(data) {
					if (data.status==true) {
						$('#back-acc-new').html(data.html);
					}
				});


			});

			<?php 
			if (isset($post_act['widthdraw_met']) && $post_act['widthdraw_met']==2) { ?>
				// load mặc định
				var csrf = $('input[name="csrf_megav_name"]').val();
				var code = $('option:selected', '#providerCode').attr('data-code'); 
				$.ajax({
					url: '/withdraw/withdrawGetAjaxBankAcc',
					type: 'POST',
					dataType: 'json',
					data: {code: code,withdraw:2,csrf_megav_name:csrf},
				})
				.done(function(data) {
					if (data.status==true) {
						$('#back-acc-new').html(data.html);
						if ($('#bankAccHidden').val()!='') {
							$('#back-acc-new').find('option').each(function() {
							    if($(this).val()==$('#bankAccHidden').val()){
							    	$(this).attr('selected','selected');
							    } 
							});
						}
					}
				});
			<?php 
			}else{ ?>
				// load mặc định
				var csrf = $('input[name="csrf_megav_name"]').val();
				var code = $('option:selected', '#providerCode').attr('data-code'); 
				$.ajax({
					url: '/withdraw/withdrawGetAjaxBankAcc',
					type: 'POST',
					dataType: 'json',
					data: {code: code,csrf_megav_name:csrf},
				})
				.done(function(data) {
					if (data.status==true) {
						$('#back-acc-new').html(data.html);
						if ($('#bankAccHidden').val()!='') {
							$('#back-acc-new').find('option').each(function() {
							    if($(this).val()==$('#bankAccHidden').val()){
							    	$(this).attr('selected','selected');
							    } 
							});
						}
					}
				});
			<?php 
			}

			?>
			

			$('body').on('click','.create_realy a',function(){
				window.location.href = '/banks_account/createBankAccount'; 
			});
			$('body').on('click','.create_realy_firm a',function(){
				window.location.href = '/firm_banking/createBankAccount'; 
			});

			


		});
	</script>

