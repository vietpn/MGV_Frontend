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
		.form-center .form-group {
			width: 100%;
		}
	</style>
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a href="javascript:void(0);" class="back_acc_manage">Quản trị tài khoản</a></li>
		<li><a>Tài khoản ngân hàng</a></li>
	</ul> 
<div class="row form-center">
	<div class="form-group" style="text-align: center;">
	        
			<ul class="tab-account-map" style="display: inline-block;">
				<li class="active"><a href="/banks_account">Khai báo TK ngân hàng</a></li>
				<li><a href="/firm_banking">TK rút tiền nhanh</a></li>
			    <li><a href="/map_account">Liên kết tài khoản</a></li>
			</ul>
			
	</div>
	
	
</div>
<div class="row form-center" id="form-list-acc" data-count="<?php echo isset($listBankAcc) ? count($listBankAcc) : ''; ?>" style="overflow-y: auto;max-height: 350px;">
	<div class="form-group ">
		<?php if(isset($listBankAcc)): ?>

			<?php foreach($listBankAcc as $key => $bankAcc): ?>
				<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 bank_div">
					<div class="panel panel-default">
						<div class="panel-body" style="padding-left: 10px;padding-right: 10px;">
							<div class="row bank-info">
								<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">Ngân hàng</div>
								<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12"><?php echo $bankAcc->bankName; ?></div>
							</div>
							<div class="row bank-info">
								<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">Số tài khoản</div>
								<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12"><?php echo $bankAcc->bankAccount; ?></div>
							</div>
							<div class="row bank-info">
								<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">Chi nhánh - tỉnh thành</div>
								<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
									<?php 
										if(isset($bankAcc->bankBranch))
											echo $bankAcc->bankBranch;
										if(isset($bankAcc->provinceName) && $bankAcc->provinceName != '') 
											echo ' - ' . $bankAcc->provinceName; 
									?>
								</div>
							</div>
							<div class="row bank-info">
								<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">Họ và tên chủ thẻ</div>
								<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12"><?php echo $bankAcc->bankAccountName; ?></div>
							</div>
						</div>
						<div class="panel-footer">
							<a href="/banks_account/deleteBankAcc/<?php echo $bankAcc->rowId; ?>/<?php echo $bankAcc->bankAccount . '/' . $bankAcc->bankCode; ?>">Xóa</a> | <a href="/banks_account/updateBankAccount/<?php echo $bankAcc->rowId; ?>/<?php echo $bankAcc->bankAccount . '/' . $bankAcc->bankCode; ?>">Sửa</a>
						</div>
					</div>
				</div>
				<?php if($key != 0 && $key%2 != 0): ?>
				<div class="clearfix" data-count="<?php echo $key; ?>"></div>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php else: ?>
			
			<div class="form-group">
				<label  style="text-align: center; float: none;">
					Bạn chưa khai báo thông tin ngân hàng.
				</label>
				<div class="clearfix"></div>
			</div>
		<?php endif; ?>
		
	</div>
</div>
<div class="row form-center" style="margin-top:10px;">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
			
			<a class="button button-main" href="/banks_account/createBankAccount" style="width: 240px;">Thêm thông tin ngân hàng</a>
		</div>
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