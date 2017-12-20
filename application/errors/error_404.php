<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>MegaV <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
    
    <link rel="stylesheet" type="text/css" href='<?php echo "../css/bootstrap/bootstrap.min.css?v=" . VERSION_WEB ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo "../css/bootstrap/sidebar.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo "../css/bootstrap/content.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo "../css/caroulsel-reponsive-style.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo "../css/layout/layout_login.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo "../css/layout/layout_info.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo "../css/layout/slick.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo "../assets/css/libs/metisMenu.min.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo "../assets/js/libs/jcrop/jquery.Jcrop.min.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo "../assets/css/element/fonts.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo "../assets/css/element/style.css?v=" . VERSION_WEB; ?>'/>
	<script type="text/javascript" language="javascript" src='<?php echo "../assets/js/libs/jquery-1.11.3.min.js?v=" . VERSION_WEB; ?>'></script>
</head>
<body>

	<div class="container-fluid layout">
		<div class="container-fluid wrapper web_view" style="background-color: transparent; border: medium none;">
				<img src="../../images/404.png" class="img-responsive header" style="width: 200px;">
			<div class="result-text" style="text-align: center; margin-top: 40px;">
				Hệ thống sẽ quay trở về trang chủ sau <b id="countdown_text"></b> giây
			</div>
			
			<div class="row" style="text-align: center; margin-top: 40px;">
				<a href="/transaction_manage" target="_parent" class="btn btn-accept">Về trang chủ</a>
			</div>
		</div>
	</div>
	
	<script>
		$(document).ready(function($) {
			countdown_number = 31;
			countdown_trigger();
			
		});
		
		function countdown_trigger() {
		  if (countdown_number > 0) {
			  countdown_number--;
	 
			  $('#countdown_text').html(countdown_number);
	 
			  if(countdown_number > 0) {
				  countdown = setTimeout('countdown_trigger()', 1000);
			  }
		  }
		}
		
		setTimeout(function () {
		   window.location.href = "/transaction_manage"; 
		}, 30000);
	</script>
</body>
</html>