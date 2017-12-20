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
		<li><a href="#">Hình thức xác thực giao dịch.</a></li>
	</ul> 
	
<div id="userInfo">
	<div class="container">
		<div class="row">
			
				<div><i class="icon-seurity"></i>Bạn nên sử dụng hình thức xác thực giao dịch qua số điện thoại, để tăng tính bảo mật trong từng giao dịch sử dụng.</div>
				<?php echo form_open('/change_security', array('method' => 'post', 'role' => 'form')); ?>
					<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
					<div class="col-md-10 col-lg-10 col-xs-10 col-sm-10">
						<div class="row">
							<div class="row">
								<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
									<label>
										<input name="sec_type" value="1" <?php if($userInfo['security_method'] == '1') echo "checked" ?> type="radio">
										Số điện thoại
									</label>
								</div>
								<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
									<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
										Mô tả 
										
										<p>Số điện thoại đang sử dụng:  <?php echo substr_replace($userInfo['mobileNo'], '****', 0, (strlen($userInfo['mobileNo']) - 4)); ?></p>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
									<label>
										<input name="sec_type" value="2" <?php if($userInfo['security_method'] == '2') echo "checked" ?> type="radio">
										Mật khẩu cấp 2
									</label>
								</div>
								<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
									<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
										Mô tả 
									</div>
								</div>
								
								<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 ">
									<div class="form-center">
										<div class="form-group button-group">
											<div class="div-input">
												<?php if($userInfo['security_method'] == '1'): ?>
													<a class="button button-main" href="/change_security/updateSecuriyPass">Tiếp tục</a>
												<?php endif; ?>
												<?php if($userInfo['security_method'] == '2'): ?>
													<a class="button button-main" href="/change_security/updateSecurityOtp">Tiếp tục</a>
												<?php endif; ?>
												<a target="_parent" class="button button-sub" href="/acc_manage">Hủy</a>
											</div>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
			</form>
		</div>
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