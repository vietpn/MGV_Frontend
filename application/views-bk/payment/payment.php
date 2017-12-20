<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MegaID <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url().'../images/megaid-favicon.png' ?>"/>
    <link href="<?php echo base_url() . '../css/bootstrap/bootstrap.min.css' ?>" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="../css/caroulsel-reponsive-style.css"/>
    <link href="<?php echo base_url('css/payment_layout.css') ?>" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="<?php echo base_url() . '../js/jquery.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url() . '../js/jquery.min.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url() . '../js/jquery.flexisel.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url() . '../js/bootstrap/bootstrap.min.js' ?>"></script>
</head>
<body>
<div id="container-fluid">
    <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12 center-block float_none payment_header" style="text-align: center;">
        <a class="btn-back col-xs-1" href="<?php echo base_url().'payment?info='.urlencode($this->input->cookie('payment_info_back'))  ?>"><img
                src="<?php echo base_url('images/payment/forma_back.png') ?>"/></a>
        <label class="payment_title col-sm-10 col-xs-11"><?php echo $module_title ?></label>
    </div>
    <div class="row col-lg-12 col-md-12 col-sm-12 col-xs-12 center-block float_none payment_body" id="body">
        <div>
            <?php echo $data['content']; ?>
        </div>
    </div>
</body>
</html>