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
		<li><a>Đổi mật khẩu đăng nhập</a></li>
	</ul> 
<div class="col-md-12 form-center">
    <?php echo form_open('change_pass',array('method'=>'post','class'=>'col-xs-12 col-sm-12','role'=>'form')); ?>
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    <div class="form-group">
        <label for="InputPassword">Mật khẩu hiện tại</label>
        <div class="div-input">
            <input type="password" class="form-input checkSpace" placeholder="Nhập mật khẩu hiện tại" name="old_pass" value="<?php echo set_value('old_pass') ?>" maxlength='50' autocomplete="off">
            <span class="form-error">
                <?php echo form_error('old_pass') ?>
                <?php echo (isset($wrongPass)) ? $wrongPass : ''; ?>
            </span>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <label for="InputPassword">Mật khẩu mới</label>
        <div class="div-input">
            <input type="password" class="form-input checkSpace" placeholder="Nhập mật khẩu mới" name="password" value="<?php echo set_value('password') ?>" autocomplete="off">
            <span class="form-error"><?php echo form_error('password') ?></span>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <label for="InputPassword">Nhập lại mật khẩu mới</label>
        <div class="div-input">
            <input type="password" class="form-input checkSpace" placeholder="Nhập lại mật khẩu mới" name="re_pass" value="<?php echo set_value('re_pass') ?>" autocomplete="off">
            <span class="form-error"><?php echo form_error('re_pass') ?></span>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <span class="form-error"><?php echo isset($error)?$error:'' ?></span>
    </div>
    <div class="form-group">
        <label>&nbsp;</label>
        <div class="div-input">
            <div class="link">
                <a target="_parent" href="change_pass/reset_pass">Quên mật khẩu?</a>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group button-group">
        <label>&nbsp </label>
        <div class="div-input">
            <input class="button button-main" type="submit" value="Cập nhật"/>
            <a class="button button-sub " href="/acc_manage" target="_parent" />Hủy bỏ</a>
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