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
		
		<?php echo $data['content'] ?>
			 
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