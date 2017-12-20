<!DOCTYPE html>
<?php
$urlSecur = base_url() . '../register/security_code';
?>
<html lang="vi" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MegaID <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
    <link href="<?php echo base_url() . '../css/bootstrap/bootstrap.min.css' ?>" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="../css/layout/layout_register.css"/>
    <script type="text/javascript" src="<?php echo base_url() . '../js/jquery.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url() . '../js/jquery.min.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url() . '../js/bootstrap/bootstrap.min.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url() . '../js/login/login.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url() . '../js/register/register.js' ?>"></script>
    <script type="text/javascript" src="../js/script.js"></script>
</head>
<body>
<div class="container-fluid wrapper fast_register">
    <img src="../../../images/info/logo.png" class="img-responsive header">

    <div class="site-info">
        <center>ĐÂY LÀ WEBSITE THÀNH VIÊN CỦA VNPT EPAY</center>
    </div>
    <div class="row content">
        <?php echo form_open('register/dangkynhanh', array('method' => 'post', 'name' => 'register', 'id' => 'register', 'class' => 'form-horizontal frm-register')); ?>
        <!-- Thong tin dang ky tai khoan -->
        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
            <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                <span class="input-group-addon"><img src=<?php echo base_url(). '/images/register/user.png'?> /></span>
                <input type="text" name="username" class="col-md-12 col-lg-12 col-xs-12 col-sm-12" placeholder="Tên đăng nhập"/>
                <span style="color: red" id="username_error"> <?php echo form_error('username'); ?></span>
            </div>
        </div>
        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
            <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                <span class="input-group-addon"><img src=<?php echo base_url(). '/images/register/pass.png'?> /></span>
                <input type="password" name="password" class="col-md-12 col-lg-12 col-xs-12 col-sm-12" placeholder="Mật khẩu"/>
                <span style="color: red" id="password_error"> <?php echo form_error('password'); ?></span>
            </div>
        </div>
        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
            <div type="password" class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                <span class="input-group-addon last_addon"><img src=<?php echo base_url(). '/images/register/pass.png'?> /></span>
                <input type="password" name="re_password" class="col-md-12 col-lg-12 col-xs-12 col-sm-12 last_input" placeholder="Nhập lại mật khẩu"/>
                <span style="color: red" id="re_password_error"> <?php echo form_error('re_password'); ?></span>
            </div>
        </div>

        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-action">
            <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                <input class="col-md-12 col-lg-12 col-xs-12 col-sm-12 btn btn-primary" id="site_button" type="submit" value="Đăng ký" />
            </div>
        </div>
        </form>
    </div>


    <div class="row bottom-wrapper">
        <div class="signin col-md-6 col-lg-6 col-xs-6 col-sm-6">
            <img src="../../images/register/signin.png" />
            <a href="<?php echo base_url().'login' ?>">Đăng nhập</a>
        </div>
        <div class="forgot-password col-md-6 col-lg-6 col-xs-6 col-sm-6">
            <img src="../../images/register/forgot-pass.png" />
            <a href="<?php echo base_url().'login/changepass' ?>">Quên mật khẩu</a>
        </div>
    </div>
</div>
</body>
</html>
