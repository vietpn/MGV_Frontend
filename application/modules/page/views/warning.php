<!DOCTYPE html>
<?php
$urlSecur = base_url() . '../register/security_code';
?>
<html lang="vi" xmlns="http://www.w3.org/1999/html" style="background-color: <?php echo (isset($background)) ? '#444' : 'transparent' ?> ;">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MegaV - Xác nhận truy cập</title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url().'../images/megav-favicon.png' ?>"/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/bootstrap.min.css" ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/caroulsel-reponsive-style.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . "css/layout/layout.css"; ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . "css/layout/layout_info.css"; ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . "css/layout/footer.css"; ?>"/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/element/fonts.css"; ?>'/>
    <script type="text/javascript" language="javascript" src="<?php echo base_url() . "assets/js/libs/jquery-1.11.3.min.js" ?>"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url() . 'js/bootstrap/bootstrap.min.js' ?>"></script>
    <!--script type="text/javascript" src="../js/script.js"></script-->
    <!--[if IE]>
    <link rel="stylesheet" type="text/css" href="../css/layout/ie.css"/>
    <![endif]-->
</head>
<body style="width: 100%; background-color: #444444;">
<div class="container-fluid layout">
    <div class="container-fluid wrapper web_view">
		
		<img src="<?php echo base_url() . "/images/resulr-warning.png"; ?>" class="img-responsive header">
			
			
        <div class="result-text">
            <?php echo form_open('', array('method' => 'post', 'role' => 'form')); ?>
				<button name="connect" class="btn btn-accept">Click để tiếp tục</button>
			<?php echo form_close(); ?>
        </div>

    </div>
</div>
</body>

</html>

