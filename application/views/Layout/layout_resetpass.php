<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MegaV <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
    <!--link rel="shortcut icon" type="image/png" href="<?php echo '../images/LogoFaviion.png' ?>"/-->
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/bootstrap.min.css?v=" . VERSION_WEB ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/sidebar.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/content.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/caroulsel-reponsive-style.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/layout_login.css?v=" . VERSION_WEB; ?>'/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() . "assets/css/element/style_login.css?v=" . VERSION_WEB; ?>"/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/layout_info.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/slick.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/metisMenu.min.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/element/fonts.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/element/style.css?v=" . VERSION_WEB; ?>'/>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery-1.11.3.min.js?v=" . VERSION_WEB; ?>'></script>
</head>
<body>

<style>
	.mg-content {
		background: none;
	}
	.navbar-default {
		background: none;
		border: none;
	}
	.txt_account {
		background: none;
	}
	.panel-2 {
		border: none;
	}
	
	.lienhe {
		border: none;
	}
	
	.header-page {
		padding: 0;
	}
	
	.lienhe {
		border: none;
	}
</style>

<div id="wrapper" class="">

 <?php if(isset($nav_left)) echo $nav_left; ?>
 
<div id="page-content-wrapper" class="bg-login">
		<div class="header-page">
			<nav class="navbar navbar-default">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="navbar-header">
								<div class="lienhe"><i class="fa fa-phone" style="margin-right: 5px;"></i>HotLine: <span>19006470</span></div> 
							</div>
							
							<!--div class="logo-info"></div-->
							
							<div class="menu-nav">
							
								<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
									<a class='close' href="<?php echo base_url(); ?>"></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</nav>
		</div>
		
		<div id="mg-content" class="col-md-12 col-lg-12 panel-2 col-xs-12 col-sm-12 mg-content">
			 <?php echo $data['content'] ?>
		</div>
		
		<div id="lg-content" class="col-md-12 col-lg-12 panel-2 col-xs-12 col-sm-12 lg-content">
			 
		</div>
				
	</div>
</div>

<div id="loader-bg" style="display:none">
	<img class="loading" src="../../../images/ajax_loader_blue_64.gif">
</div>

	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.min.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/datepicker.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/bootstrap-datetimepicker.min.css"; ?>'/>
	
	
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/jquery.flexisel.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/slick.min.js?v=" . VERSION_WEB; ?>'></script>
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/bootstrap.min.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/bootstrap-datepicker.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/metisMenu.min.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery.cookie.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/datepicker.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/cmnd.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/script.js?v=" . VERSION_WEB; ?>'></script>
	
	
</body>
</html>