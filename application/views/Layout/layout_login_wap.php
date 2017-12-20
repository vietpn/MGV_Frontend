<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>MegaID <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
<link rel="shortcut icon" type="image/png" href="<?php echo base_url().'../images/megaid-favicon.png' ?>"/>
<!--link  rel="stylesheet" type="text/css" href="style_login_wap.css" /-->
<link rel="stylesheet" type="text/css" href="../css/layout/style_login_wap.css"/>
</head>
<body>
<div class="wap_wrapper">
    <div class="wap_container">
        <div class="wap_logo">
            <div class="wap_logo_img">
                <img src="../../../images/info/logo.png"/>
            </div>
        </div>
		
        <div class="wap_form_login_wrap">
			<?php echo form_open('login/do_login', array('method' => 'post', 'name' => 'login', 'id' => 'login', 'class' => 'form-horizontal frm-register')); ?>
				<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
				<input type="hidden" value="<?php echo $clientId ?>" name="clientId">
				<input type="hidden" value="<?php echo $mac_address ?>" name="mac_address">
				<input type="hidden" value="<?php echo $publisher_id ?>" name="publisher_id">
				<input type="hidden" value="<?php echo $source_url ?>" name="source_url">
				<input type="hidden" value="<?php echo $requireActiveUser ?>" name="requireActiveUser">
				<label class="wap_label">Tên đăng nhập</label>
				<input class="wap_input_text wap_username" name="username" type="text" 
						placeholder="Tên đăng nhập" value="<?php echo set_value('username', isset($username)?$username:''); ?>"/>
				<span><?php echo form_error('username'); ?></span>
				<label class="wap_label">Mật khẩu</label>
				<input type="password" name="password" class="wap_input_text wap_password"
						placeholder="Mật khẩu" value="<?php echo set_value('password', isset($password)?$password:''); ?>" autocomplete="off"/>
				<span><?php echo form_error('password'); ?></span>
				<label class="wap_label">
					<input class="remember-pass-checkbox wap_checkbox" type="checkbox" name="remember_pass" value="1" checked/>
					<span onclick="setcheckbox()">Nhớ mật khẩu</span>
				</label>
				<input type="submit" class="wap_submit" value="ĐĂNG NHẬP"/>
			</form>
        </div>
        <div class="wap_actions">
            <a href="<?php echo base_url() . 'register?wap=1' ?>" class="wap_register">ĐĂNG KÝ</a>
        </div>
    </div>
    <div class="wap_footer">
        <p>@2011 BẢN QUYỀN CỦA CÔNG TY CỔ PHẦN THANH TOÁN ĐIỆN TỬ VNPT</p>
        <p>Trụ sở Hà Nội : Tầng 3 - Tòa nhà Viễn Đông - 36 Hoàng Cầu - Quận Đống Đa - Hà Nội</p>
        
        <p style="margin-top: 17px;">Chi nhánh tại TPHCM : Địa chỉ: Lầu 3, số 96 – 98 Đào Duy Anh, Phường 9, Quận Phú Nhuận, TP HCM</p>
    </div>
</div>
</body>
</html>