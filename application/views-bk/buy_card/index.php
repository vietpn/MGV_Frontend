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
		<li><a href="#">Giao dịch</a></li>
		<li><a href="#">Mua mã thẻ</a></li>
	</ul>

	<div class="col-md-12 form-center">
		
			<?php echo form_open('/buy_card/buy_card_cdv', array('method' => 'post', 'role' => 'form')); ?>
					
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
                     <select id="providerCode" name="provider_code" class="form-input">
                         <option value="">Chọn nhà cung cấp thẻ</option>
                         <?php if(isset($listTelco)): ?>
                             <?php foreach($listTelco as $telco): ?>
                                 <?php if($telco->type == '1'): ?>
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
				<label>Mệnh giá</label>
				<div class="div-input">
					 <select id="buyCardAmount" name="amount" class="form-input">
                         <option value="">Chọn mệnh giá</option>
                         <?php if(isset($listAmount)): ?>
                             <?php foreach($listAmount as $amount): ?>
								<?php if($amount->visible == '1'): ?>
									<option value="<?php echo $amount->amount; ?>" <?php echo set_select('amount', $amount->amount, False); ?>><?php echo $amount->amount; ?></option>
								<?php endif; ?>
							 <?php endforeach; ?>
                         <?php endif; ?>
                     </select>
                     <span class="form-error"><?php echo form_error('amount'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="form-group">
				<label>Số lượng</label>
				<div class=" div-input">
					<input id="buyCardQuantity" name="quantity" class="form-input" type="text" placeholder="Nhập số tiền muốn rút"  onkeyup="formatCurrency(this, this.value);"
							maxlength="11" value="<?php echo set_value('quantity') ?>">
					<span class="form-error"><?php echo form_error('quantity'); ?></span>
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
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/script.js"; ?>'></script>

</body>
</html>