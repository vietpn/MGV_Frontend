<!DOCTYPE html>
<?php
$urlSecur = base_url() . '../register/security_code';
$ar_month = array(
    '' => 'Tháng',
    '1' => 'Tháng 1',
    '2' => 'Tháng 2',
    '3' => 'Tháng 3',
    '4' => 'Tháng 4',
    '5' => 'Tháng 5',
    '6' => 'Tháng 6',
    '7' => 'Tháng 7',
    '8' => 'Tháng 8',
    '9' => 'Tháng 9',
    '10' => 'Tháng 10',
    '11' => 'Tháng 11',
    '12' => 'Tháng 12'
);
$ar_addr = array(
    '' => 'Nơi cấp',
    '5' => 'Hà Nội',
    '6' => 'Hồ Chí Minh',
    '7' => 'Hải Phòng',
    '8' => 'Đà Nẵng',
    '9' => 'An Giang',
    '10' => 'Bà Rịa - Vũng Tàu',
    '11' => 'Bắc Cạn',
    '12' => 'Bắc Giang',
    '13' => 'Bạc Liêu',
    '14' => 'Bắc Ninh',
    '15' => 'Bến Tre',
    '16' => 'Bình Dương',
    '17' => 'Bình Phước',
    '18' => 'Bình Thuận',
    '19' => 'Bình Định',
    '20' => 'Buôn Mê Thuột',
    '21' => 'Cà Mau',
    '22' => 'Cần Thơ',
    '23' => 'Cao Bằng',
    '24' => 'Gia Lai',
    '25' => 'Hà Giang',
    '26' => 'Hà Nam',
    '27' => 'Hà Tĩnh',
    '28' => 'Hải Dương',
    '29' => 'Hậu Giang',
    '30' => 'Hoà Bình',
    '31' => 'Hưng Yên',
    '32' => 'Khánh Hòa',
    '33' => 'Kiên Giang',
    '34' => 'Kon Tum',
    '35' => 'Lai Châu',
    '36' => 'Lâm Đồng',
    '37' => 'Lạng Sơn',
    '38' => 'Lào Cai',
    '39' => 'Long An',
    '40' => 'Nam Định',
    '41' => 'Nghệ An',
    '42' => 'Ninh Bình',
    '43' => 'Ninh Thuận',
    '44' => 'Phú Thọ',
    '45' => 'Phú Yên',
    '46' => 'Quảng Bình',
    '47' => 'Quảng Nam',
    '48' => 'Quảng Ngãi',
    '49' => 'Quảng Ninh',
    '50' => 'Quảng Trị',
    '51' => 'Sóc Trăng',
    '52' => 'Sơn La',
    '53' => 'Tây Ninh',
    '54' => 'Thái Bình',
    '55' => 'Thái Nguyên',
    '56' => 'Thanh Hoá',
    '57' => 'Thừa Thiên Huế',
    '58' => 'Tiền Giang',
    '59' => 'Trà Vinh',
    '60' => 'Tuyên Quang',
    '61' => 'Vĩnh Long',
    '62' => 'Vĩnh Phúc',
    '63' => 'Yên Bái',
    '64' => 'Đà Lạt',
    '65' => 'Đắc Lắc',
    '66' => 'Đắc Nông',
    '67' => 'Đồng Nai',
    '68' => 'Đồng Tháp'
);
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
    <link rel="stylesheet" type="text/css" href="../css/caroulsel-reponsive-style.css"/>
    <link rel="stylesheet" type="text/css" href="../css/layout/footer.css"/>
    <script type="text/javascript" src="<?php echo base_url() . '../js/jquery-1.6.3.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url() . '../js/jquery.min.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url() . '../js/jquery.flexisel.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url() . '../js/bootstrap/bootstrap.min.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url() . '../js/login/login.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url() . '../js/register/register.js' ?>"></script>
    <script type="text/javascript" src="../js/script.js"></script>
</head>
<body>
<div class="container-fluid register-wrapper">
    <div class="wrapper">
        <img src="../../../images/info/logo.png" class="img-responsive header">

        <div class="site-info">
            <center>ĐÂY LÀ WEBSITE THÀNH VIÊN CỦA VNPT EPAY</center>
        </div>
        <div class="row content">
            <?php echo form_open('register/dangky', array('method' => 'post', 'name' => 'register', 'id' => 'register', 'class' => 'form-horizontal frm-register')); ?>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
            <!-- Thong tin dang ky tai khoan -->
            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
                <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                    <span class="input-group-addon"><img
                            src="<?php echo base_url() . 'images/register/user.png' ?>"/></span>
                    <input type="text" name="username" class="col-md-12 col-lg-12 col-xs-12 col-sm-12"
                           placeholder="Tên đăng nhập" value="<?php echo set_value('username') ?>"/>
                    <?php if(form_error('username')): ?>
                    <span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation_error" style="color: red"
                          id="username_error"> <?php echo form_error('username'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
                <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                    <span class="input-group-addon"><img
                            src="<?php echo base_url() . 'images/register/pass.png' ?>"/></span>
                    <input type="password" name="password" class="col-md-12 col-lg-12 col-xs-12 col-sm-12"
                           placeholder="Mật khẩu" value="<?php echo set_value('password') ?>" autocomplete="off"/>
                    <?php if(form_error('password')): ?>
                    <span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation_error" style="color: red"
                          id="password_error"> <?php echo form_error('password'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
                <div type="password" class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                    <span class="input-group-addon"><img
                            src="<?php echo base_url() . 'images/register/pass.png' ?>"/></span>
                    <input type="password" name="re_password" class="col-md-12 col-lg-12 col-xs-12 col-sm-12"
                           placeholder="Nhập lại mật khẩu" value="<?php echo set_value('re_password') ?>" autocomplete="off"/>
                    <?php if(form_error('re_password')): ?>
                    <span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation_error" style="color: red"
                          id="re_password_error"> <?php echo form_error('re_password'); ?></span>
                    <?php endif; ?>
                    <?php if(isset($error) && !is_null($error)): ?>
                        <span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation_error" style="color: red"
                              id="re_password_error"> <?php echo $error; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
                <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                    <span class="input-group-addon"><img
                            src="<?php echo base_url() . 'images/register/mail.png' ?>"/></span>
                    <input type="text" name="email" class="col-md-12 col-lg-12 col-xs-12 col-sm-12" placeholder="Email"
                           value="<?php echo set_value('email') ?>"/>
                    <?php if(form_error('email')): ?>
                    <span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation_error" style="color: red"
                          id="email_error"> <?php echo form_error('email'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
                <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                    <span class="input-group-addon"><img
                            src="<?php echo base_url() . 'images/register/phone.png' ?>"/></span>
                    <input type="text" name="fone" class="col-md-12 col-lg-12 col-xs-12 col-sm-12"
                           placeholder="Số điện thoại" value="<?php echo set_value('fone') ?>"/>
                    <?php if(form_error('fone')): ?>
                    <span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation_error" style="color: red"
                          id="fone_error"> <?php echo form_error('fone'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
                <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                    <span class="input-group-addon"><img src="<?php echo base_url() . 'images/register/adress.png' ?>"/></span>
                    <input type="text" name="address" class="col-md-12 col-lg-12 col-xs-12 col-sm-12"
                           placeholder="Địa chỉ" value="<?php echo set_value('address') ?>"/>
                    <?php if(form_error('address')): ?>
                    <span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation_error" style="color: red"
                          id="address_error"> <?php echo form_error('address'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
                <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                    <span class="input-group-addon"><img
                            src="<?php echo base_url() . 'images/register/id.png' ?>"/></span>
                    <input type="text" name="idNo" class="col-md-12 col-lg-12 col-xs-12 col-sm-12"
                           placeholder="Số chứng minh thư" value="<?php echo set_value('idNo') ?>"/>
                    <?php if(form_error('idNo')): ?>
                    <span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation_error" style="color: red"
                          id="idNo_error"> <?php echo form_error('idNo'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
                <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                    <span class="input-group-addon"><img
                            src="<?php echo base_url() . 'images/register/id_adress.png' ?>"/></span>
                    <select name="add_IssueIdNo" class="col-md-12 col-lg-12 col-xs-12 col-sm-12" placeholder="Nơi cấp">
                        <?php foreach ($ar_addr as $key => $value): ?>
                            <option
                                value="<?php echo $key ?>" <?php echo set_select('add_IssueIdNo', $key) ?>><?php echo $value ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if(form_error('add_IssueIdNo')): ?>
                    <span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation_error" style="color: red"
                          id="add_IssueIdNo_error"> <?php echo form_error('add_IssueIdNo'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
                <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                    <span class="input-group-addon"><img
                            src="<?php echo base_url() . 'images/register/calendar.png' ?>"/></span>

                    <div>
                        <span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 addText">Ngày cấp</span>
                        <input class="col-md-3 col-lg-3 col-xs-3 col-sm-3" type="text" name="idIssueDate_day" id="bday"
                               maxlength="2" placeholder="Ngày" value="<?php echo set_value('idIssueDate_day') ?>"/>
                        <select class="col-md-5 col-lg-5 col-xs-5 col-sm-5" name="idIssueDate_month"
                                id="select_month" <?php echo set_select('idIssueDate_month') ?>>
                            <?php foreach ($ar_month as $key => $value): ?>
                                <option
                                    value="<?php echo $key ?>" <?php echo set_select('idIssueDate_month', $key); ?>><?php echo $value ?></option>
                            <?php endforeach ?>
                        </select>
                        <input class="col-md-4 col-lg-4 col-xs-4 col-sm-4" type="text" name="idIssueDate_year"
                               maxlength="4"
                               id="year" placeholder="Năm" value="<?php echo set_value('idIssueDate_year') ?>">
                    </div>
                    <?php if(form_error('idIssueDate_day')||form_error('idIssueDate_month')||form_error('idIssueDate_year')): ?>
        <span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation_error" style="color: red"
              id="idIssueDate_error">
            <?php
            if (form_error('idIssueDate_day')) {
                echo form_error('idIssueDate_day');
            } elseif (form_error('idIssueDate_month')) {
                echo form_error('idIssueDate_month');
            } elseif (form_error('idIssueDate_year')) {
                echo form_error('idIssueDate_year');
            }
            ?>
        </span>
        <?php endif; ?>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
                <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                    <span class="input-group-addon"><img
                            src="<?php echo base_url() . 'images/register/calendar.png' ?>"/></span>

                    <div>
                        <span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 addText">Ngày sinh</span>
                        <input class="col-md-3 col-lg-3 col-xs-3 col-sm-3" type="text" name="birthday_day" id="bday"
                               maxlength="2"
                               placeholder="Ngày" value="<?php echo set_value('birthday_day') ?>"/>
                        <select class="col-md-5 col-lg-5 col-xs-5 col-sm-5" name="birthday_month" id="select_month">
                            <?php foreach ($ar_month as $key => $value): ?>
                                <option
                                    value="<?php echo $key ?>" <?php echo set_select('birthday_month', $key); ?>><?php echo $value ?></option>
                            <?php endforeach ?>
                        </select>
                        <input class="col-md-4 col-lg-4 col-xs-4 col-sm-4" type="text" name="birthday_year"
                               maxlength="4" id="year"
                               placeholder="Năm" value="<?php echo set_value('birthday_year') ?>"/>
                    </div>
                    <?php if(form_error('birthday_day')||form_error('birthday_month')||form_error('birthday_year')): ?>
        <span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation_error" style="color: red" id="birthday_error">
            <?php
            if (form_error('birthday_day')) {
                echo form_error('birthday_day');
            } elseif (form_error('birthday_month')) {
                echo form_error('birthday_month');
            } elseif (form_error('birthday_year')) {
                echo form_error('birthday_year');
            }
            ?>
        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
                <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                    <span class="input-group-addon"><img
                            src="<?php echo base_url() . 'images/register/sex.png' ?>"/></span>
                    <select name="gen" class="col-md-12 col-lg-12 col-xs-12 col-sm-12"
                            placeholder="Giới tính" <?php echo set_select('gen') ?>>
                        <option value="M" <?php echo set_select('gen', 'M') ?>>Nam</option>
                        <option value="F" <?php echo set_select('gen', 'F') ?>>Nữ</option>
                    </select>
                    <?php if(form_error('gen')): ?>
                    <span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation_error" style="color: red"
                          id="gen_error"> <?php echo form_error('gen'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
                <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                    <span class="input-group-addon last_addon"><img
                            src="<?php echo base_url() . 'images/register/code.png' ?>"/></span>
                    <input type="text" name="security_captcha" class="col-md-8 col-lg-8 col-xs-8 col-sm-8"
                           placeholder="Mã xác thực"/>

                    <a class="recap" href="javascript: void(0)" alt="mã khác"
                       onclick="javascript:regetCaptcha();">
                        <img class="img_recapcha" src="../../images/recapcha.jpg" alt="recapcha">
                    </a>
                    <img class="cap col-md-3 col-lg-3 col-xs-3 col-sm-3" src="<?php echo $urlSecur; ?>"
                         id="captcha_image"/>
                    <br>
                    <?php if(form_error('security_captcha')): ?>
                    <span class="col-md-12 col-lg-12 col-xs-12 col-sm-12 validation_error" style="color: red"
                              id="security_captcha_error"> <?php echo form_error('security_captcha'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-action">
                <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                    <input class="col-md-12 col-lg-12 col-xs-12 col-sm-12 btn btn-primary" id="site_button"
                           type="submit"
                           value="Đăng ký"/>
                </div>
            </div>
            </form>
        </div>


        <div class="row bottom-wrapper">
            <div class="signin col-md-6 col-lg-6 col-xs-6 col-sm-6">
                <img src="../../images/register/signin.png"/>
                <a href="<?php echo base_url() . 'login?appId='.$this->input->cookie('clientId')?>">Đăng nhập</a>
            </div>
            <div class="forgot-password col-md-6 col-lg-6 col-xs-6 col-sm-6">
                <img src="../../images/register/forgot-pass.png"/>
                <a href="<?php echo base_url() . 'login/changepass' ?>">Quên mật khẩu</a>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row footer hidden-xs" style="background: #f0f0f0;">
        <div class="container footer-title" style="text-align: center">
            Chỉ cần một tài khoản bạn có thể đăng ký tất cả các dịch vụ của VNPT EPAY
        </div>
        <div class="hidden-xs">
            <div id="banners" class="container">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <?php $this->load->view('footer/scoll_images') ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
