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
<?php if($this->session_memcached->userdata['info_user']['email'] == ""): ?>
	<div style="text-align: center;">Chưa có thông tin Email. Vui lòng nhập thông tin bên dưới</div>
<?php else: ?>
	
<?php endif; ?>
<div class="col-md-12 form-center">
    <?php if(isset($sentOtp)): ?>
        <?php echo form_open('/change_email/updateEmail', array('method' => 'post', 'role' => 'form')); ?>
    <?php else: ?>
        <?php echo form_open('/change_email/changeEmail', array('method' => 'post', 'role' => 'form')); ?>
    <?php endif; ?>
    <?php if($this->session_memcached->userdata['info_user']['email_status'] == 1): ?>
        <?php if(isset($sentOtp)): ?>
            <?php if(isset($messSentOtp)): ?>
                <div class="form-group">
                    <label><?php echo $messSentOtp ?> Không nhận OTP <a id="resendOtp">Click gửi lại</a></label>
                    <div class="clearfix"></div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
        <div class="form-group">
            <label for="">Email đang sử dụng</label>
            <div class="div-input">
                <input readonly name="email" class="form-input" type="text" value="<?php echo $this->session_memcached->userdata['info_user']['email']; ?>">
                <span class="form-error"><?php echo form_error('email'); ?></span>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php if(isset($sentOtp)): ?>
            <div class="form-group">
                <label for="">Email mới</label>
                <div class="div-input">
                    <input name="newemail" class="form-input checkSpace" type="text" value="<?php echo set_value('newemail', ''); ?>" placeholder="Nhập email mới">
                    <span class="form-error"><?php echo (isset($wrong_email)) ? $wrong_email : ''; ?><?php echo form_error('newemail'); ?></span>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="form-group">
                <label for="">Nhập OTP</label>
                <div class="div-input">
                    <input name="otp" class="form-input" type="password" autocomplete="off">
                    <span class="form-error"><?php echo (isset($wrong_otp)) ? $wrong_otp : ''; ?><?php echo form_error('otp'); ?></span>
                </div>
                <div class="clearfix"></div>
            </div>
        <?php else: ?>
            <div class="form-group">
                <label>Hình thức xác thực</label>
                <div class="div-input">
                    <select name="sec_met" class="form-input">
                        <?php if($this->session_memcached->userdata['info_user']['email_status'] == 1): ?>
                            <option value="2"><?php echo $this->session_memcached->userdata['info_user']['email']; ?></option>
                        <?php endif; ?>
                        <?php if($this->session_memcached->userdata['info_user']['phone_status'] == 1): ?>
                            <option value="1"><?php echo $this->session_memcached->userdata['info_user']['mobileNo']; ?></option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="clearfix"></div>
            </div>
        <?php endif; ?>
        <div class="form-group button-group">
            <label>&nbsp;</label>
            <div class="div-input">
                <input type="submit" class="button button-main" value="Tiếp tục"/>
                <a class="button button-sub" href="/change_email/">Hủy</a>
            </div>
            <div class="clearfix"></div>
        </div>
        </form>
    <?php else: ?>
        <?php echo form_open('/change_email/changeEmail', array('method' => 'post', 'role' => 'form')); ?>
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
        <?php if(!empty($this->session_memcached->userdata['info_user']['email'])): ?>
            
            <div class="form-group">
                <label>Email đang sử dụng</label>
                <div class="div-input">
                    <input readonly name="email" class="form-input" type="text" value="<?php echo $this->session_memcached->userdata['info_user']['email']; ?>">
                    <span class="form-error"><?php echo form_error('email'); ?></span>
                </div>
                <div class="clearfix"></div>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label>Email mới</label>
            <div class="div-input">
                <input autocomplete="new-password" name="newemail" class="form-input checkSpace" type="text" placeholder="Nhập email mới" value="<?php echo set_value('newemail') ?>">
                <span class="form-error"><?php echo form_error('newemail'); ?></span>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="form-group">
            <label>Nhập mật khẩu</label>
            <div class="div-input">
                <input autocomplete="new-password" name="password" class="form-input checkSpace" type="password" autocomplete="off">
                <span class="form-error"><?php echo isset($error_pass) ? $error_pass : ""; ?><?php echo form_error('password'); ?></span>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="form-group button-group">
            <label>&nbsp;</label>
            <div class="div-input">
                <input type="submit" class="button button-main" value="Cập nhật"/>
                <a target="_parent" class="button button-sub" href="/acc_manage">Hủy bỏ</a>
            </div>
            <div class="clearfix"></div>
        </div>
        </form>
    <?php endif; ?>
</div>

<div id="loader-bg" style="display:none">
	<img class="loading" src="../../../images/ajax_loader_blue_64.gif">
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