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
		<li><a>Đổi địa chỉ email</a></li>
	</ul> 
<div class="col-md-12 form-center">
    <div class="form-group" style="text-align: center;">
        <label style="white-space: nowrap;">
            Địa chỉ email <?php echo $acc_info['email']; ?>
            <?php echo ($acc_info['email_status'] == 1) ? "đã xác thực" : "chưa xác thực" ?>.
            <?php if($acc_info['email_status'] == 1): ?>
				Bạn muốn đổi Email?
			<?php else: ?>
				<?php if(isset($sendmail) && $sendmail == '1'): ?>
					<p>Vui lòng check mail để xác thực email mới. Chưa nhận được link <a style="color: #2794cc; cursor: pointer;" href="/change_email/sendMailActive" >Click gửi lại link xác thực</a></p>
				<?php else: ?>
					Bạn muốn <a style="color: #2794cc; cursor: pointer;" href="/change_email/sendMailActive" >xác thực ngay</a>?
				<?php endif; ?>
			<?php endif; ?>
        </label>
        <a class="button button-main" href="/change_email/changeEmail">Đổi Email</a>
        <div class="clearfix"></div>
    </div>
	

	
</div>

<?php if(isset($popup) && !empty($popup)){
	 echo $popup; 
} ?>

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