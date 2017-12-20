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
		<li><a>Chuyển tiền</a></li>
	</ul>

	<div class="col-md-12 form-center">
		<div class="step-by-step">
			<ol class="progtrckr" data-progtrckr-steps="4">
				<li class="progtrckr-todo">Chọn phương thức chuyển tiền</li>
				<li class="<?php echo isset($step) && $step == 2 ? 'progtrckr-done' : 'progtrckr-todo' ?>">Khai báo thông tin chuyển tiền</li>
				<li class="<?php echo isset($step) && $step == 3 ? 'progtrckr-done' : 'progtrckr-todo' ?>">Xác nhận giao dịch hoàn thành</li>
				
			</ol>
		</div>
		<?php if(isset($sentOtp)): ?>
			
			<div class="form-group" style="white-space: nowrap;">Hệ thống đã gửi OTP đến số điện thoại <?php echo substr_replace($userInfo['mobileNo'], '****', 0, (strlen($userInfo['mobileNo']) - 4)); ?>. Không nhận OTP <a id="resendOtp">Click gửi lại</a></div>
			
		<?php endif; ?>
			<?php echo form_open('/transfer/transfer_epurse', array('method' => 'post', 'role' => 'form')); ?>
			
			<div class="form-group">
				<label>Số dư khả dụng (đ)</label>
				<div class="div-input">
					<span class="color-green balance-transfer"><?php echo isset($balance) ? number_format($balance) : 0 ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="form-group">
				<label>Tổng tiền thanh toán (đ)</label>
				<div class="div-input">
					<span class="color-green balance-transfer">
						<?php 
							if(isset($post_2['pay_fee']) && $post_2['pay_fee'] == '1')
							{
								echo (isset($fee)) ? number_format($post_2['amount'] + $fee) : 0;
							}
							else
							{
								echo (isset($fee)) ? number_format($post_2['amount']) : 0;
							}
							 
						?>
					</span>
				</div>
				<div class="clearfix"></div>
			</div>
		
			<?php if(isset($post['trans_met']) && $post['trans_met'] == '1'): ?>
				<div class="form-group">
					<label>Tài khoản nhận</label>
					<div class="div-input">
						<span ><?php echo isset($reciverAcc) ? $reciverAcc['username'] : "" ?></span>
					</div>
					<div class="clearfix"></div>
					
					<label>Họ và tên</label>
					<div class="div-input">
						<span ><?php echo isset($reciverAcc) ? $reciverAcc['fullname'] : "" ?></span>
					</div>
					<div class="clearfix"></div>
					
				</div>
			<?php elseif(isset($post['trans_met']) && $post['trans_met'] == '2'): ?>
				<div class="form-group">
					<label>Dịch vụ</label>
					<div class="div-input">
						<span><?php echo isset($post['service_met']) ? $post['service_met'] : "" ?></span>
					</div>
					<div class="clearfix"></div>
				</div>
			<?php endif; ?>
			
			<?php if($userInfo['security_method'] == '1'): ?>
				<div class="form-group">
					<label>Nhập OTP</label>
					<div class=" div-input">
						<input maxlength="11" name="otp" class="form-input" type="password" placeholder="Nhập mã xác nhận">
						<span class="form-error"><?php echo isset($error_otp) ? $error_otp : ""; ?><?php echo form_error('otp'); ?></span>
					</div>
					<div class="clearfix"></div>
				</div>
			<?php else: ?>
				<div class="form-group">
					<label>Mật khẩu cấp 2</label>
					<div class=" div-input">
						<input maxlength="11" name="passLv2" class="form-input" type="password" placeholder="Nhập mật khẩu cấp 2">
						<span class="form-error"><?php echo isset($error_passLv2) ? $error_passLv2 : ""; ?><?php echo form_error('passLv2'); ?></span>
					</div>
					<div class="clearfix"></div>
				</div>
				
				<div class="form-group">
					<label> &nbsp </label>
						<div class="div-input">
							<div class="link">
							<a href="/reset_pass_lv2">Quên mật khẩu cấp 2</a>
							</div>
						</div>
					<div class="clearfix"></div>
				</div>
			<?php endif; ?>
				<input name="step3" type="hidden" class="button button-main" value="3"/>
			<div class="form-group button-group">
				<label>&nbsp </label>
				<div class="div-input">
					<input name="step3" type="submit" class="button button-main" value="Hoàn thành"/>
					<!--a id='transfer-step' class="button button-main"> Tiếp tục </a-->
					<a href="/transfer" class="button button-sub">Hủy</a>
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
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/script.js?v=" . VERSION_WEB; ?>'></script>

</body>
</html>