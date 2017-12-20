<!DOCTYPE html>
<html lang="vi">
<head>
<link rel="stylesheet" type="text/css" href="<?php echo  base_url() . '/css/bootstrap/bootstrap.min.css' ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() . 'assets/css/element/style_login.css'; ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() . 'assets/css/element/fonts.css'; ?>"/>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery-1.11.3.min.js"; ?>'></script>
   
	
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.min.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.css"; ?>'/>
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/jquery.flexisel.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/slick.min.js"; ?>'></script>
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/bootstrap.min.js"; ?>'></script>
</head>

<body style="background-color: #fff; ">	
<div>
	<div class="row">
	
		<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
			<div class="logoimages"></div>
		</div>
		
		<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
			<?php if(isset($error)): ?>
				<div class="loginfail"></div>
			<?php else: ?>
				<div class="loginsuccess"></div>
			<?php endif; ?>
		</div>
		
		<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
			<p style="text-align: center; margin: 30px auto 76px;"><?php  echo $mess; ?></p>
		</div>
		
		<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
			<?php if(isset($targetIframeLink)): ?>
			<a href="<?php echo $targetIframeLink; ?>" target="_parent" class="col-md-12 col-lg-12 col-xs-12 col-sm-12 btn-login-register result_a">Tiếp tục</a>
			<?php endif ?>
			
			<?php if(isset($redirect_link)): ?>
			<a href="<?php echo $redirect_link; ?>" class="col-md-12 col-lg-12 col-xs-12 col-sm-12 btn-login-register result_a">Quay lại</a>
			<?php endif ?>
		</div>
		
	</div>
	
</div>
	<?php if(isset($targetIframeLink)): ?>
	<script>
		setTimeout(function () {
		window.top.location = "<?php echo $targetIframeLink; ?>";
		}, <?php echo isset($timeRedirect) ? $timeRedirect : '5000'; ?>);
	</script>
	<?php endif; ?>
</body>
</html>