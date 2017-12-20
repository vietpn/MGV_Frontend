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
		<li><a>Thay đổi mật khẩu cấp 2</a></li>
	</ul>
	
<div class="col-md-12 form-center">
    <?php echo form_open('change_pass_lv2', array('method' => 'post', 'role' => 'form')); ?>
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    <div class="form-group">
        <label>Mật khẩu cấp 2 hiện tại</label>
        <div class="div-input">
            <input name="passLv2" class="form-input checkSpace" type="password" autocomplete="off" placeholder="Nhập mật khẩu cấp 2 hiện tại">
            <span class="form-error"><?php echo form_error('passLv2'); ?></span>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <label>Mật khẩu cấp 2 mới</label>
        <div class="div-input">
            <input name="newpassLv2" class="form-input checkSpace" type="password" autocomplete="off" placeholder="Nhập mật khẩu cấp 2 mới">
            <span class="form-error"><?php echo form_error('newpassLv2'); ?></span>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <label>Nhập lại mật khẩu cấp 2 mới</label>
        <div class="div-input">
            <input name="repassLv2" class="form-input checkSpace" type="password" autocomplete="off" placeholder="Nhập lại mật khẩu cấp 2 mới">
            <span class="form-error"><?php echo form_error('repassLv2'); ?></span>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <label>&nbsp;</label>
        <div class="div-input">
            <div class="link">
                <a href="/reset_pass_lv2">Quên mật khẩu cấp 2?</a>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group button-group">
        <label>&nbsp </label>
        <div class="div-input">
            <input class="button button-main" type="submit" value="Cập nhật"/>
            <a class="button button-sub" href="/acc_manage" target="_parent" />Hủy bỏ</a>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php echo form_close(); ?>
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