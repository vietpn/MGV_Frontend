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
	<div class="form-group">
		<div class="notify-msg" style="margin-bottom: 15px;">
			<span style="color: #ccc; font-family: roboto light; text-align: justify;">
		MegaV hỗ trợ rút tiền theo 3 hình thức: 
		<p style="padding-left: 20px; text-indent: -14px;">1. Rút tiền theo phiên: rút tiền ra bất kì tài khoản ngân hàng nào, tiền nổi theo thời gian giao dịch của ngân hàng</p>
		<p style="padding-left: 20px; text-indent: -14px;">2. (Chưa sẵn sàng) Rút tiền nhanh: rút tiền ra bất kì tài khoản ngân hàng nào, tiền nổi ngay khi giao dịch thành công
		<p style="padding-left: 20px; text-indent: -14px;">3. (Chưa sẵn sàng) Rút tiền qua tài khoản liên kết: rút tiền ra tài khoản ngân hàng đã liên kết với Ví, tiền sẽ nổi ngay khi giao dịch thành công 
			</span></div>
		
		<div class="transfer-balance">
			<span class="">Số dư khả dụng</span><span class="balance"><?php echo isset($balance) ? number_format($balance) : 0;?> đ</span>
		</div>
		<div class="transfer-balance">
			<span class="">Số dư tạm giữ</span><span class="balance">0 đ</span>
		</div>
		
		<!--a class="button-main transfer-btn" href="/withdraw/withdraw_epurse">Rút tiền</a-->
		<a class="button-main transfer-btn" href="/withdraw/withdraw_epurse_1">Rút tiền</a>
		<div class="clearfix"></div>
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
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/script.js"; ?>'></script>
</body>
</html>