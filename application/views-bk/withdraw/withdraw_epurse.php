<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MegaV <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo '../images/megaid-favicon.png' ?>"/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/bootstrap.min.css" ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/sidebar.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/content.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/caroulsel-reponsive-style.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/layout_login.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/layout_info.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/slick.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/metisMenu.min.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/element/fonts.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/element/style.css"; ?>'/>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery-1.11.3.min.js"; ?>'></script>
</head>
<body>
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a>Rút tiền</a></li>
	</ul>

	<div class="col-md-12 form-center">
		
		<div class="step-by-step">
			<ol class="progtrckr" data-progtrckr-steps="4">
				<li class="progtrckr-todo">Chọn phương thức rút tiền</li>
				<li class="<?php echo isset($step) && $step == 2 ? 'progtrckr-done' : 'progtrckr-todo' ?>">Khai báo thông tin rút</li>
				<li class="<?php echo isset($step) && $step == 3 ? 'progtrckr-done' : 'progtrckr-todo' ?>">Xác nhận giao dịch - hoàn thành</li>
				
			</ol>
		</div>
		
			<?php echo form_open('/withdraw/withdraw_epurse', array('method' => 'post', 'role' => 'form')); ?>
			
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
					<select name="widthdraw_met" class="form-input">
						<option value=""><?php echo "Chọn hình thức rút tiền"; ?></option>
						<option value="1" <?php echo (isset($post_act['widthdraw_met']) && $post_act['widthdraw_met']==1) ? 'selected="selected"' : '';?>><?php echo "Rút tiền theo phiên"; ?></option>
						
					</select>
					<span class="form-error"><?php echo form_error('widthdraw_met'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>

			<div class="form-group">
                 <label>Ngân hàng</label>
                 <div class="div-input">
                     <select id="providerCode" name="provider_code" class="form-input">
                         <option value="">Chọn ngân hàng</option>
                         <?php if(isset($listBank)): ?>
                             <?php foreach($listBank as $bank): ?>
                                 <?php // if($bank->type == '2'): ?>
                                     <option value="<?php echo $bank->recId; ?>" <?php echo set_select('provider_code', $bank->recId, False); ?> data-code="<?php echo $bank->bankCode; ?>"><?php echo $bank->bankName; ?></option>
                                 <?php // endif; ?>
                             <?php endforeach; ?>
                         <?php endif; ?>
                     </select>
                     <input type="hidden" value="<?php echo (isset($post_act['bankAcc']) && $post_act['bankAcc']!='') ? $post_act['bankAcc'] : '';?>" id="bankAccHidden" />
                     <span class="form-error"><?php echo form_error('provider_code'); ?></span>
                 </div>
				 <label class="create_realy"><a href="javascript:void(0);">Thêm tài khoản</a></label>
                 <div class="clearfix"></div>
             </div>
			
			<div class="form-group">
				<label>Số tài khoản</label>
				<div class=" div-input">
					 <select name="bankAcc" class="form-input" id="back-acc-new">
                         <option value="">Chọn tài khoản</option>
                         
                     </select>
                     <span class="form-error"><?php echo form_error('bankAcc'); ?></span>
				</div>
				
				<div class="clearfix"></div>
			</div>
			
			<div class="form-group">
				<label>Số tiền rút (đ)</label>
				<div class=" div-input">
					<input id="withdrawAmount" name="amount" class="form-input" type="text" placeholder="Nhập số tiền muốn rút"  onkeyup="formatCurrency(this, this.value);"
							maxlength="12" value="<?php echo set_value('amount') ?>">
					<span class="form-error"><?php echo isset($err_amount) ? $err_amount : "" ?><?php echo form_error('amount'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="form-group">
                <label>Phí rút tiền (đ)
                    <i class="fa fa-info-circle has-tooltip" data-placement="top" data-toggle="tooltip" data-original-title="Phí rút tiền: 11,000/ 1 giao dịch (không phụ thuộc giá trị giao dịch)"></i>
                </label>
                <div class="div-input">
                    <span id="feeWidthdraw" class="amount"><?php echo isset($fee) ? number_format($fee) : 0 ?></span>
                </div>
                <div class="clearfix"></div>
            </div>
			<input name="step1" type="hidden"  value="1"/>
			<div class="form-group button-group">
				<label>&nbsp </label>
				<div class="div-input">
					<input name="step1" type="submit" class="button button-main" value="Tiếp tục"/>
					<a href="/withdraw" class="button button-sub"/>Hủy</a>
				</div>
				<div class="clearfix"></div>
			</div>
		</form>
		
	</div>


<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.min.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/datepicker.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/bootstrap-datetimepicker.min.css"; ?>'/>
	
	
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/jquery.flexisel.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/slick.min.js"; ?>'></script>
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/bootstrap.min.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/bootstrap-datepicker.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/metisMenu.min.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery.cookie.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/datepicker.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/cmnd.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/script.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript">
		$(document).ready(function(){
			$('body').on('change','#providerCode',function(){
				var code = $('option:selected', this).attr('data-code'); 
				$.ajax({
					url: '/withdraw/withdrawGetAjaxBankAcc',
					type: 'POST',
					dataType: 'json',
					data: {code: code},
				})
				.done(function(data) {
					if (data.status==true) {
						$('#back-acc-new').html(data.html);
					}
				});


			});


			// load mặc định
				var code = $('option:selected', '#providerCode').attr('data-code'); 
				$.ajax({
					url: '/withdraw/withdrawGetAjaxBankAcc',
					type: 'POST',
					dataType: 'json',
					data: {code: code},
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

			$('body').on('click','.create_realy a',function(){
				window.location.href = '/banks_account/createBankAccount'; 
			});


		});
	</script>

</body>
</html>