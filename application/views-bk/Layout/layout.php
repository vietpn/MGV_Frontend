<!DOCTYPE html>
<?php
$urlSecur = base_url() . '../register/security_code';
?>
<html lang="vi" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MegaV <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
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
<body style="width: 100%;">
<div class="container-fluid layout">
    <div class="container-fluid wrapper web_view">
		
			<?php if(isset($success)): ?>
				<img src="<?php echo base_url() . "/images/success.png"; ?>" class="img-responsive header">
			<?php else: ?>
				<img src="<?php echo base_url() . "/images/resulr-warning.png"; ?>" class="img-responsive header">
			<?php endif; ?>
			
        <div class="result-text">
            <?php echo $data['content']; ?>
        </div>

    </div>
</div>
</body>

</html>
