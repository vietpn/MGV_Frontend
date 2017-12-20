<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MegaV <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo '../images/megaid-favicon.png' ?>"/>
    <link href="<?php echo '../css/bootstrap/bootstrap.min.css' ?>" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="../css/caroulsel-reponsive-style.css"/>
    <link rel="stylesheet" type="text/css" href="../css/layout/layout_login.css"/>
   
    <script type="text/javascript" src="<?php echo '../js/jquery-1.6.3.js' ?>"></script>
    <script type="text/javascript" src="<?php echo '../js/jquery.min.js' ?>"></script>
    <script type="text/javascript" src="<?php echo '../js/jquery.flexisel.js' ?>"></script>
    <script type="text/javascript" src="<?php echo '../js/bootstrap/bootstrap.min.js' ?>"></script>
    <script type="text/javascript" src="../js/script.js"></script>
	
</head>
<body>
	<div class="header-page">
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					
					<a href="/">
						<img src="../../../images/info/logo.png" class="img-responsive header">
						Slogan
					</a>
				</div>
				<div class="navbar-header">
					HotLine: 190010000
				</div>
				<div class="collapse navbar-collapse pull-right" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="active"><a href="/">Trang Chủ</a></li>
						<li><a href="#">Chính Sách</a></li>
						<li><a href="#">Hỏi & đáp</a></li>
						<li><a href="#">Khuyến mại</a></li>
						<li><a href="#">Liên hệ</a></li>
						<li><a href="/register">Đăng ký</a></li>
						<li> <a href="/login">Đăng nhập</a></li>
					</ul>
				</div>
			</div>
		</nav>
	</div>

	<div class="banner-page">
		<img src="/images/bg_login.jpg" >
	</div>
	
	<div class="l-f-page container">
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 f-page">
			<img src="/f-page.jpg">
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 f-page">
			<img src="/f-page.jpg">
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 f-page">
			<img src="/f-page.jpg">
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 f-page">
			<img src="/f-page.jpg">
		</div>
	</div>
	
	<div class="slidel-f-page container">
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 f-page">
			<img src="/f-page.jpg">
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 f-page">
			<img src="/f-page.jpg">
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 f-page">
			<img src="/f-page.jpg">
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 f-page">
			<img src="/f-page.jpg">
		</div>
	</div>
	<div class="hidden-xs">
		<div class="container">
			<?php
			$imgdir = 'images/footer/'; //Pick your folder
			?>
			<?php
			$footer_img = array();
			$footer_img = explode(',', FOOTER_IMG);
			echo "<ul id='flexiselDemo3' class='nav nav-pills'>";
			foreach ($footer_img as $img) {
				$img_info = explode('|', $img);
				$img_name = $img_info['0'];
				$img_link = $img_info['1'];
				?>
				<li>
					<a href="<?php echo $img_link; ?>" target="_blank" rel="nofollow" title="<?php echo $img_name; ?>">
						<img src="../<?php echo $imgdir . $img_name; ?>" alt="<?php echo $img_name; ?>"/>
					</a>
				</li>
			<?php } 
				echo "<ul/>"
			?>
		</div>
	</div>
	
	<div class="footer-page">
		<div class="footer-info container">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
				<label>Về MeGaV</label>
				<p>Giới thiệu nhanh về MegaV.Giới thiệu nhanh về MegaV.Giới thiệu nhanh về MegaV.Giới thiệu nhanh về MegaV.Giới thiệu nhanh về MegaV.Giới thiệu nhanh về MegaV.</p>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
				<label>MeGaV.VN</label>
				<p>Trang chủ</p>
				<p>Chính sách</p>
				<p>Hỏi đáp</p>
				<p>Khuyến mãi</p>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
				<label>Thông tin liên hệ</label>
				<p>Hà Nội: tầng 3 toàn nhà Viễn Đông Hoàng Cầu, Đống Đa, Hà Nộ</p>
				<p>TP HCM: tầng 3 toàn nhà Viễn Đông Hoàng Cầu, Đống Đa, Hà Nộ</p>
			</div>
		</div>
	</div>
	
	<div class="hidden-xs">
		<div class="container">
			<?php
			$imgdir = 'images/footer/'; //Pick your folder
			?>
			<?php
			$footer_img = array();
			$footer_img = explode(',', FOOTER_IMG);
			echo "<ul id='flexiselDemo2' class='nav nav-pills'>";
			foreach ($footer_img as $img) {
				$img_info = explode('|', $img);
				$img_name = $img_info['0'];
				$img_link = $img_info['1'];
				?>
				<li>
					<a href="<?php echo $img_link; ?>" target="_blank" rel="nofollow" title="<?php echo $img_name; ?>">
						<img src="../<?php echo $imgdir . $img_name; ?>" alt="<?php echo $img_name; ?>"/>
					</a>
				</li>
			<?php } 
				echo "<ul/>"
			?>
		</div>
	</div>
</body>
</html>
