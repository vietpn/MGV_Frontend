<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
	<link href="../css/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap/bootstrap.min.css"/>
    <style>
		body {
			font-family: "avenir";
			font-size: 17px;
			margin: 0;
			width: 100%;
			overflow-x: hidden;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="row" style="color:black; margin: 0 auto; width: 590px;">
		<?php if(isset($sentMobileFl) || isset($sentEmailFl)): ?>
			<?php echo form_open('security_manage/updatePassLv2', array('method' => 'post', 'role' => 'form')); ?>
				<div class="txt_account" >Chọn hình thức xác thực giao dịch</div>
				<div class="form-group ">
					<label><input id="pass_sec" type="radio" name="security" value="2" checked>Mật khẩu cấp 2 - Mật khẩu giao dịch</label>
					
					<p>Hệ thống đã gửi mã xác nhận tới 
					<?php if(isset($phoneSent)) echo "số điện thoại " . $phoneSent; ?>
					<?php if(isset($emailSent)) echo "email " . $emailSent; ?>
					. Không nhận được <a id="resendOtp">Click gửi lại</a>
					</p>
					<p>Mã xác nhận <input name="otp" type="password" name="security" ></p>
				</div>
				<div class="col-md-8 col-lg-8 col-xs-12 col-sm-12">
					<input type="submit" class="col-md-5 col-lg-5 col-xs-6 col-sm-6 btn btn-warning " value="Đồng ý"/>
				</div>
			</form>
		<?php else: ?>
			<?php echo form_open('security_manage/update_security', array('method' => 'post', 'role' => 'form')); ?>
				<div class="txt_account" >Vui lòng chọn hình thức xác thực giao dịch</div>
				<div class="group-username row">
					<div class="form-group ">
						<label><input id="phone_sec" type="radio" name="security" value="1">Số điện thoại</label>
						<div class="phone_info"></div>
					</div>
					<div class="form-group ">
						<label><input id="pass_sec" type="radio" name="security" value="2">Mật khẩu cấp 2 - Mật khẩu giao dịch</label>
						
						<div class="security_info"></div>
					</div>
				</div>
				<div class="col-md-8 col-lg-8 col-xs-12 col-sm-12">
					<input type="submit" class="col-md-5 col-lg-5 col-xs-6 col-sm-6 btn btn-warning " value="Đồng ý"/>
				</div>
			</form>
		<?php endif; ?>
		</div>
	</div>
	
	<script type="text/javascript" src="../js/jquery-1.6.3.js"></script>
	<script type="text/javascript" src="../assets/js/elements/securitymanage.js"></script>
</body>
</html>