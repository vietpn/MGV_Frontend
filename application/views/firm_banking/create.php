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
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/element/style.css?v=" . VERSION_WEB; ?>'/>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery-1.11.3.min.js"; ?>'></script>
</head>
<body>
<style>
		.create-firm .form-group {
			width: 100%;
		}
	</style>
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a href="javascript:void(0);" class="back_acc_manage">Quản trị tài khoản</a></li>
		<li><a>Tài khoản ngân hàng</a></li>
	</ul> 

<div class="row form-center create-firm">
	<div class="form-group" style="text-align: center;">
	        
			<ul class="tab-account-map" style="display: inline-block;">
				<li><a href="/banks_account">TK rút tiền theo phiên</a></li>
				<li class="active"><a>TK rút tiền nhanh</a></li>
			    <li><a href="/map_account">Liên kết tài khoản</a></li>
			</ul>
			
	</div>
	
	
</div>

	<div class="col-md-12 form-center">
		<!--div class="text-center">Vui lòng nhập chính xác thông tin ngân hàng. Nếu thông tin sai, giao dịch không thể thực hiện (Nạp hoặc rút tiền)<br/>Lưu ý: Tên chủ thẻ phải viết hoa và không có dấu!</div-->
		<?php echo form_open('/firm_banking/createBankAccount', array('method' => 'post', 'role' => 'form')); ?>
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			<div class="form-group">
				<label>Ngân hàng</label>
				<div class="div-input">
					<select id="selectFirmBank" name="bank_code" class="form-input check_card">
						<option value="">Chọn ngân hàng</option>
					<?php if(isset($listBank)): ?>
						<?php foreach($listBank as $bank): ?>
								<option value="<?php echo $bank->epurseBankCode; ?>" <?php echo set_select('bank_code', $bank->epurseBankCode, False); ?> 
										data-target="<?php echo isset($bank->isCard) ? $bank->isCard : ''; ?>">
									<?php echo $bank->providerName; ?>
								</option>
						<?php endforeach; ?>
					<?php endif; ?>
					</select>
					<span class="form-error"><?php echo form_error('bank_code'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			
	
				<div class="form-group">
					<label class="firmCard" ><?php echo (isset($isCard) && $isCard == '0') ? "Tên chủ tài khoản" : "Tên chủ thẻ"; ?></label>
					<div class="div-input">
						<input style="text-transform: uppercase;" name="bank_account_name" class="form-input name-unicode" type="text" value="<?php echo set_value('bank_account_name'); ?>">
						<span class="form-error"><?php echo isset($bankAccNameError) ? $bankAccNameError : ''; ?><?php echo form_error('bank_account_name'); ?></span>
					</div>
					<div class="clearfix"></div>
				</div>
				
				<div class="form-group">
					<label class="firmCardNumb" ><?php echo (isset($isCard) && $isCard == '0') ? "Số tài khoản" : "Số thẻ"; ?></label>
					<div class="div-input">
						<input name="bank_account" class="form-input" type="text" value="<?php echo set_value('bank_account'); ?>">
						<span class="form-error"><?php echo form_error('bank_account'); ?></span>
					</div>
					<div class="clearfix"></div>
				</div>
			
			
			<div class="form-group">
				<label>Chi nhánh</label>
				<div class="div-input">
					<input name="bank_branch" class="form-input" type="text" value="<?php echo set_value('bank_branch'); ?>">
					<span class="form-error"><?php echo form_error('bank_branch'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="form-group button-group">
				<label> &nbsp; </label>
				<div class="div-input">
					<input type="submit" class="button button-main" value="Cập nhật"/>
					<a class="button button-sub" href="/firm_banking" />Hủy bỏ</a>
				</div>
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
	
	
</body>
</html>