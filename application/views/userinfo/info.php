<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MegaV <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() . '/images/megav-favicon.ico' ?>"/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/bootstrap.min.css" ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/sidebar.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/content.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/caroulsel-reponsive-style.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/layout_login.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/layout_info.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/slick.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/metisMenu.min.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/element/fonts.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/element/style.css"; ?>'/>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery-1.11.3.min.js"; ?>'></script>
</head>
<body>
<style type='text/css'>
@media screen and (max-width:768px) {
	.tab_trans_history {
		height: 35px;
		line-height: 35px;
		margin-bottom: 5px;
		margin-top: 0px;
	}
}
</style>

<?php

if(!stripos($userInfo['email'], '@') || stripos($userInfo['email'], '@facebook.com')) {
    $email = '';
} else {
    $email = $userInfo['email'];
}
?>

	<!--ul class="breadcrumb">
		<li class="first">|</li>
		<li><a href="javascript:void(0);" class="back_acc_manage">Quản trị tài khoản</a></li>
		<li><a>Thông tin tài khoản</a></li>
	</ul-->
	<div >
		<ul style="border-bottom: none;" class="breadcrumb nav nav-tabs tab_trans_history owl-carousel" role="tablist" data-dots="false" data-loop="false" data-nav="true" data-margin="0" data-autoplayTimeout="1000" data-autoplayHoverPause="true" data-responsive='{"0":{"items":3},"420":{"items":5},"600":{"items":7}}'>
			<li role="presentation" class="info active"><a href="javascript:void(0)" data-target="#info" aria-controls="info" role="tab" data-toggle="tab">Thông tin tài khoản</a></li>
			<li role="presentation" class="mapping"><a href="javascript:void(0)" data-target="#mapping" aria-controls="mapping" role="tab" data-toggle="tab">Thông tin liên kết ví</a></li>
		</ul>
	</div>
	
<div class="tab-content">
	<div role="tabpanel" class="tab-pane info active" id="info">
		<div id="userInfo">
			<div class="balance-info col-md-12 col-lg-12 col-xs-12 col-sm-12">
				<p><span class="user-balance" style="width:125px">Số dư khả dụng </span><span class="balance"><?php echo number_format($balance); ?> đ</span></p>
				<p><span class="user-balance" style="width:125px">Số dư tạm giữ </span><span class="balance"><?php echo number_format($balance_behold) ?> đ</span></p>
			</div>
			<?php echo form_open('user_info/edit_info_user', array('method' => 'post', 'role' => 'form', 'id' => 'infoUser')); ?>
				<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
				<div class="group-username row">
					<div class="form-group ">
						<div class="col-md-2 col-lg-2 col-xs-6 col-sm-6 txt-info">Tên đăng nhập</div>
						<div class="col-md-4 col-lg-4 col-xs-6 col-sm-6">
							<span><?php echo ($userInfo['userID'] == '')?'':$userInfo['userID'] ?></span>
						</div>
					</div>
				</div>
				<div class="group-username row">
					<div class="form-group ">
						<div class="col-md-2 col-lg-2 col-xs-6 col-sm-6 txt-info">Họ và tên</div>
						<div class="col-md-4 col-lg-4 col-xs-6 col-sm-6">
							<span class="infoupdate <?php if($userInfo['address'] == '') echo 'txt-info'; ?>"><?php if($userInfo['fullname'] == '') echo 'Chưa có thông tin'; else echo $userInfo['fullname']; ?></span>
							<input style="display:none" class="form-input inputinfo" type="text" placeholder="<?php if($userInfo['fullname'] == '') echo 'chưa có thông tin'; ?>"
									value="<?php if($userInfo['fullname'] == '') echo ''; else echo $userInfo['fullname']; ?>" name="fullname"/>
						</div>
						
						<div class="col-md-2 col-lg-2 col-xs-6 col-sm-6 txt-info hidden-xs hidden-sm">Ngày sinh</div>
						<div class="col-md-4 col-lg-4 col-xs-6 col-sm-6 hidden-xs hidden-sm">
							<span><?php if($userInfo['birthday'] == '') echo 'Chưa có thông tin'; else echo date('d-m-Y', strtotime($userInfo['birthday'])); ?></span>
						</div>
					</div>
					<div class="col-md-10 col-lg-10 col-xs-10 col-sm-10" id="idNo">
						<span class="error"><?php echo form_error('fullname'); ?></span>
					</div>
				</div>

				<div class="group-address row">
					<div class="form-group ">
						<div class="col-md-2 col-lg-2 col-xs-6 col-sm-6 txt-info">Địa chỉ</div>
						<div class="col-md-4 col-lg-4 col-xs-6 col-sm-6">
							<span class="infoupdate <?php if($userInfo['address'] == '') echo 'txt-info'; ?>"><?php if($userInfo['address'] == '') echo 'Chưa có thông tin'; else echo $userInfo['address']; ?></span>
							<input style="display:none" class="form-input col-xs-12 inputinfo" type="text" placeholder="<?php if($userInfo['address'] == '') echo 'chưa có thông tin'; ?>"
								value="<?php if($userInfo['address'] == '') echo ''; else echo $userInfo['address']; ?>" name="address"/>
						</div>
						
						<div class="col-md-2 col-lg-2 col-xs-6 col-sm-6 txt-info hidden-xs hidden-sm">Giới tính </div>
						<div class="col-md-3 col-lg-3 col-xs-6 col-sm-6 hidden-xs hidden-sm">
							<span class="infoupdate"><?php echo ($userInfo['gender']=='M') ? 'Nam' : 'Nữ'; ?></span>
							<div style="display:none" class="inputinfo">
								<label><input type="radio" value="M" name="gen" <?php if($userInfo['gender']=='M') echo 'checked'?> />Nam</label>
								<label><input type="radio" value="F" name="gen" <?php if($userInfo['gender']=='F') echo 'checked' ?> />Nữ</label>
								<span class="error"> <?php echo form_error('gen'); ?></span>
							</div>
						</div>
						
						<div class="col-md-2 col-lg-2 col-xs-6 col-sm-6 hidden-xs hidden-sm">
							<span class="error"><?php echo form_error('address') ?></span>
						</div>
					</div>
					
					
				</div>
				
				<div class="group-fone row">
					<div class="form-group ">
						<div class="col-md-2 col-lg-2 col-xs-6 col-sm-6 txt-info">Điện thoại di động</div>
						<div class="col-md-4 col-lg-4 col-xs-6 col-sm-6">
							<span>
								<?php 
									if($userInfo['mobileNo'] == '') 
										echo '<chưa có thông tin>'; 
									else
										echo substr_replace($userInfo['mobileNo'], '****', 3, 5);
								?>
							</span>
						</div>
						<a href="<?php echo base_url().'change_phone' ?>" class="col-md-1 col-lg-1 col-xs-12 col-sm-12 btn change-info pull-right">Sửa</a>
					</div>
				</div>
				
				<div class="group-email row">
					<div class="form-group ">
						<div class="col-md-2 col-lg-2 col-xs-6 col-sm-6 txt-info">Địa chỉ Email</div>
						<div class="col-md-4 col-lg-4 col-xs-6 col-sm-6">
							<span>
								<?php 	if(trim($email) != '')
										{
											echo $email;
										}
										else
										{
											echo 'chưa có thông tin';
										}
								?>
							</span>
						</div>	
						<a href="<?php echo base_url().'change_email' ?>" class="col-md-1 col-lg-1 col-xs-12 col-sm-12 btn change-info pull-right">Sửa</a>
					</div>
				</div>
				
				<div class="group-idNo row">
					<div class="form-group ">
						<div class="col-md-2 col-lg-2 col-xs-6 col-sm-6 txt-info">Số CMND</div>
						<div class="col-md-4 col-lg-4 col-xs-6 col-sm-6">
							<span>
								<?php if($userInfo['idNo'] == '') : ?>
										chưa có thông tin
								<?php else: ?>
									<?php echo substr_replace($userInfo['idNo'], "****", 0, 5); ?>
								<?php endif; ?>
							</span>
							<span>
						</div>
						<a href="<?php echo base_url().'change_id' ?>" class="col-md-1 col-lg-1 col-xs-12 col-sm-12 btn change-info pull-right">Sửa</a>
						
					</div>
				</div>
				<div class="group-iddate row hidden-xs hidden-sm">
					<div class="form-group ">
						<div class="col-md-2 col-lg-2 col-xs-6 col-sm-6 txt-info">Ngày cấp</div>
						<div class="col-md-4 col-lg-4 col-xs-6 col-sm-6">
							<span><?php if($userInfo['idNo_where']== '') echo 'chưa có thông tin'; else echo date('d-m-Y', strtotime($userInfo['idNo_dateIssue'])); ?></span>
						</div>
					</div>
				</div>
				<div class="group-idPlace row hidden-xs hidden-sm">
					<div class="form-group ">
						<div class="col-md-2 col-lg-2 col-xs-6 col-sm-6 txt-info">Nơi cấp</div>
						<div class="col-md-4 col-lg-4 col-xs-6 col-sm-6">
							<span><?php if($userInfo['idNo_where'] == '') echo 'chưa có thông tin'; else echo $userInfo['idNo_where']; ?></span>
						</div>
					</div>
				</div>
					
				<div class="row">
					<div class="form-group button-group">
						<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
							<div class="form-center">
								<div class="div-input">
									<a id="updateinfo" class="button button-main">Cập nhật</a>
									<input id="submitUpdate" style="display:none" type="submit" class="button button-main" value="Lưu thông tin">
									<a id="cancelupdateinfo" style="display:none" class="button button-sub ">Hủy bỏ</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>

	</div>
	
	
	<div role="tabpanel" class="tab-pane mapping" id="mapping">
		<div class="form-group ">
		<?php if($listMerchantMapping): ?>
			<?php foreach($listMerchantMapping as $merchantMapping): ?>
				<?php $merchantMapping = json_decode($merchantMapping);
					//print_r($merchantMapping->mappingAcc);
					//echo "<br>";
					//print_r($merchantMapping->ecInfo->MLogo);
					
				?>
				<!--img class="merchant-logo" src="<?php echo $merchantMapping->ecInfo->MLogo; ?>" -->
				
				<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 bank_div">
					<div class="panel panel-default">
						<div class="panel-body" style="padding-left: 10px;padding-right: 10px;">
							<div class="row bank-info">
								<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">Số điện thoại</div>
								<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12"><?php echo $userInfo['mobileNo'] ?></div>
							</div>
							<div class="row bank-info">
								<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">Đối tác liên kết</div>
								<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12"><?php echo $merchantMapping->ecInfo->merchantName ?></div>
							</div>
							<div class="row bank-info">
								<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">Thời gian liên kết</div>
								<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
									<?php echo date('d/m/Y h:i:s', strtotime($merchantMapping->mappingAcc->createdDate)) ?>
								</div>
							</div>
							<div class="row bank-info">
								<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">Trạng thái:</div>
								<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
									<?php  switch($merchantMapping->mappingAcc->processStatus){
										case '00' : echo "Thành công";
										break;
										case '99' : echo "Đang xủ lý";
										break;
										default : echo "Thất bại";
										break;
									} ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			<?php endforeach; ?>
		<?php else: ?>
			<div class="form-group">
				<label  style="text-align: center; float: none;">
					Bạn chưa khai báo thông tin ngân hàng.
				</label>
				<div class="clearfix"></div>
			</div>
		<?php endif; ?>
		</div>
	</div>

</div>
<?php
	if(isset($popup))
		echo $popup;
?>
<script>
	$(document).ready(function(){			
		$('#updateinfo').click(function(){
			$('.infoupdate,#updateinfo').css('display', 'none');
			$('.inputinfo,#submitUpdate,#cancelupdateinfo').css('display', '');
		});
		
		$('#cancelupdateinfo').click(function(){
			$('.infoupdate,#updateinfo').css('display', '');
			$('.inputinfo,#submitUpdate,#cancelupdateinfo').css('display', 'none');
		});
		
		
	});
</script>

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