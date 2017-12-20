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
	.create-map .form-group {
		width: 100%;
	}
</style>
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a href="javascript:void(0);" class="back_acc_manage">Quản trị tài khoản</a></li>
		<li><a>Tài khoản ngân hàng</a></li>
	</ul> 

<div class="row form-center create-map">
	<div class="form-group" style="text-align: center;">
	        
			<ul class="tab-account-map" style="display: inline-block;">
				<li><a href="/banks_account">TK rút tiền theo phiên</a></li>
				<li><a href="/firm_banking">TK rút tiền nhanh</a></li>
			    <li class="active"><a>Liên kết tài khoản</a></li>
			</ul>
			
	</div>
	<div class="col-md-12 form-center">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
				<div >
					<div class="text-center" style="display: inline-block;">
						<span style="color: #ccc; margin-right: 0px;" class="warning-msg-map"></span>
						<span style="line-height: 36px;"><?php echo isset($mess) ? $mess : '';?></span>
					</div>
				</div>
			</div>
	</div>
</div>
<?php 
	if (!empty($data_result)) { ?>
		<div class="col-md-12 form-center" style="margin-bottom: 15px;">
		        <div class="form-group">
		            <label>Ngân hàng</label>
		            <div class="div-input">
		                <input name="bank_account_name" class="form-input" type="text" value="<?php echo $data_result['bank_name']; ?>" disabled>
		                <span class="form-error"></span>
		            </div>
		            <div class="clearfix"></div>
		        </div>
				<div class="form-group">
		            <label>Số tài khoản</label>
		            <div class="div-input">
		                <input name="bank_account_name" class="form-input" type="text" value="<?php echo $data_result['bank_acc']; ?>" disabled>
		                <span class="form-error"></span>
		            </div>
		            <div class="clearfix"></div>
		        </div>
		        <div class="form-group">
		            <label>Tài khoản ví</label>
		            <div class="div-input">
		                <input name="bank_account_name" class="form-input" type="text" value="<?php echo $data_result['acc_vi']; ?>" disabled>
		                <span class="form-error"></span>
		            </div>
		            <div class="clearfix"></div>
		        </div>
		        <div class="form-group">
		            <label>Thời gian gửi yêu cầu</label>
		            <div class="div-input">
		                <input name="bank_account_name" class="form-input" type="text" value="<?php echo date('d-m-Y H:i:s',strtotime($data_result['createdDate'])); ?>" disabled>
		                <span class="form-error"></span>
		            </div>
		            <div class="clearfix"></div>
		        </div>
		        <div class="form-group">
		            <label>Trạng thái liên kết</label>
		            <div class="div-input">
		                <input name="bank_account_name" class="form-input" type="text" value="<?php echo $data_result['status']; ?>" disabled>
		                <span class="form-error"></span>
		            </div>
		            <div class="clearfix"></div>
		        </div>
		</div><br>
		<div class="col-md-12 form-center" >
			<div class="form-group">
				<div class="div-input">
					<a class="btn btn-accept" href="/map_account">Quay về trang danh sách</a>
				</div>
			</div>
		</div>
	<?php 
	} else {
?>
	<div class="row form-center">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
			<div class="clearfix"></div>
			<a class="button button-main" href="/map_account" style="width: 240px;">Quay về trang danh sách</a>
		</div>
	</div>
	<?php } ?>
	
		
	

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
	
	

<script type="text/javascript">
	$(document).ready(function(){
		//$('#wrapper').addClass('toggled');
		$('#wrapper', parent.document).addClass('toggled');
	});
</script>
</body>
</html>