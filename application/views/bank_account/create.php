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
		<li><a href="javascript:void(0);" class="back_acc_manage">Quản trị tài khoản</a></li>
		<li><a>Tài khoản ngân hàng</a></li>
	</ul> 


	<div class="col-md-12 form-center">
		<div>Vui lòng nhập chính xác thông tin ngân hàng. Nếu thông tin sai, giao dịch không thể thực hiện (Nạp hoặc rút tiền)</div>
		<?php echo form_open('/banks_account/createBankAccount', array('method' => 'post', 'role' => 'form')); ?>
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			<div class="form-group">
				<label>Ngân hàng</label>
				<div class="div-input">
					<select name="bank_code" class="form-input">
						<option value="">Chọn ngân hàng</option>
					<?php if(isset($listBank)): ?>
						<?php foreach($listBank as $bank): ?>
							<?php if($bank->type == '2'): ?>
								<option value="<?php echo $bank->recId; ?>" <?php echo set_select('bank_code', $bank->recId, False); ?> >
									<?php echo $bank->providerName; ?>
								</option>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					</select>
					<span class="form-error"><?php echo form_error('bank_code'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="form-group">
				<label>Tên tài khoản</label>
				<div class="div-input">
					<input name="bank_account_name" class="form-input" type="text" value="<?php echo set_value('bank_account_name'); ?>">
					<span class="form-error"><?php echo form_error('bank_account_name'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="form-group">
				<label>Số tài khoản</label>
				<div class="div-input">
					<input name="bank_account" class="form-input" type="text" value="<?php echo set_value('bank_account'); ?>">
					<span class="form-error"><?php echo form_error('bank_account'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
		
			<div class="form-group">
				<label>Tỉnh thành</label>
				<div class="div-input">
					<select name="province_code" class="form-input">
						<option value="">Chọn tỉnh thành</option>
						<?php if(isset($listProvince)): ?>
							<?php foreach($listProvince as $province): ?>
								
								<option value="<?php echo $province->provinceId; ?>" <?php echo set_select('province_code', $province->provinceId, False); ?>>
									<?php echo $province->provinceName; ?>
								</option>
								
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
					<span class="form-error"><?php echo form_error('province_code'); ?></span>
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
					<a class="button button-sub" href="/banks_account" />Hủy bỏ</a>
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
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/script.js"; ?>'></script>
	
	
</body>
</html>