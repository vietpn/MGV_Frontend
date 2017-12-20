<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>MegaV <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo '../images/megaid-favicon.png' ?>"/>
    <link href="<?php echo  base_url() . '/css/bootstrap/bootstrap.min.css' ?>" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() . '/css/bootstrap/bootstrap.min.css'; ?> "/>
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() . '/css/bootstrap/sidebar.css'; ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() . '/css/caroulsel-reponsive-style.css'; ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() . '/css/layout/layout_login.css'; ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() . '/css/layout/slick.css'; ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo  base_url() . '/assets/css/libs/metisMenu.min.css'; ?>"/>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery-1.11.3.min.js"; ?>'></script>
   
</head>
<body >
<div id="wrapper" class="">

 <?php if(isset($nav_left)) echo $nav_left; ?>

<div id="page-content-wrapper">
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
					<?php if(isset($user_info['userID'])): ?>
					<a href="#menu-toggle" class="btn btn-default" id="menu-toggle">Menu</a>
					<?php endif; ?>
				</div>
				<div class="menu-nav">
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<?php if (!isset($user_info)) { ?>
						<ul class="nav navbar-nav">
							<li class="active nav-item"><a class="nav-link" href="/">Trang Chủ</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Chính Sách</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Hỏi & đáp</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Khuyến mại</a></li>
							<li class="nav-item"><a class="nav-link" href="#">Liên hệ</a></li>
							<li class="nav-item"><a class="nav-link" href="/register">Đăng ký</a></li>
							<li class="nav-item"><a class="nav-link" href="/login">Đăng nhập</a></li>
						</ul>
						<?php } else { ?>
						<div class="">Khả dụng</div>
						<div class="">Tạm giữ</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</nav>
	</div>
	<?php if (!isset($user_info)) { ?>
	<div class="banner-page">
		<img src="/images/bg_login.jpg" >
	</div>
	<?php } ?>
	
	<div class="container-fluid">
		<div class="row">
			<div class="l-f-page">
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 f-page">
					<img src="../../../images/f-page.jpg">
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 f-page">
					<img src="../../../images/f-page.jpg">
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 f-page">
					<img src="../../../images/f-page.jpg">
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 f-page">
					<img src="../../../images/f-page.jpg">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="slidel-f-page">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 f-page">
					<img src="../../../images/f-page.jpg">
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 f-page">
					<img src="../../../images/f-page.jpg">
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 f-page">
					<img src="../../../images/f-page.jpg">
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 f-page">
					<img src="../../../images/f-page.jpg">
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 f-page">
					<img src="../../../images/f2-page.jpg">
				</div>
			</div>
		</div>
	</div>
	<?php if (!isset($user_info)) { ?>
	
	<div class="hidden-xs">
		<div class="container">
			<?php
			$imgdir = 'images/footer/'; //Pick your folder
			?>
			<?php
			$footer_img = array();
			$footer_img = explode(',', FOOTER_IMG);
			echo "<ul id='flexiselimg1' class='nav nav-pills'>";
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
				echo "</ul>"
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
			echo "<ul id='flexiselimg2' class='nav nav-pills'>";
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
				echo "</ul>"
			?>
		</div>
	</div>
	<?php } ?>
</div>
</div>
<!--
<?php // check hinh thuc xac thuc giao dich
if(isset($user_info['userID'])):
	//if($user_info['security_method'] == "0" && $user_info['phone_status'] == '1'):
	if($user_info['security_method'] == "0"):
 ?>
	<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: block;">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
			<h4 class="modal-title" id="myModalLabel">Thông báo từ hệ thống</h4>
		  </div>
		  <div class="modal-body" style="min-height: 500px;">
			 <?php 
				$this->ci = & get_instance();
				$data = (isset($view_data)) ? $view_data : '';
				$this->ci->load->view('security_manage/security', $data);
			 ?>
		  </div>
		</div>
	  </div>
	</div>
<div class="modal-backdrop fade in"></div>
<?php
	endif;
 endif; ?>
-->
<!--
<?php // check hinh thuc xac thuc giao dich
if(isset($user_info['userID'])):
	if($user_info['phone_status'] == '0'):
 ?>
	<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: block;">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">Thông báo từ hệ thống</h4>
				</div>
				<div class="modal-body" style="min-height: 500px;">
					<?php 
						$this->ci = & get_instance();
						$data = (isset($view_data)) ? $view_data : '';
						$this->ci->load->view('popup/verify_phone', $data);
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-backdrop fade in"></div>
<?php
	endif;
 endif; ?>
 
-->

<?php
	echo (isset($popup)) ? $popup : "" ;
?>
 
<div id="loader-bg" style="display:none">
	<img class="loading" src="../../../images/ajax_loader_blue_64.gif">
</div>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.min.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/datepicker.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/bootstrap-datetimepicker.min.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/layout_info.css"; ?>'/>
	
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/jquery.flexisel.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/slick.min.js"; ?>'></script>
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/bootstrap.min.js"; ?>'></script>
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/script.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/bootstrap-datepicker.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/metisMenu.min.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/datepicker.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/script.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/cmnd.js"; ?>'></script>
	<script type="text/javascript" src='<?php echo  base_url() . "/assets/js/elements/securitymanage.js" ?>'></script>
	
	
	<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>
</body>
</html>
