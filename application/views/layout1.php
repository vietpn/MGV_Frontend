<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hatt
 * Date: 9/18/14
 * Time: 2:21 PM
 * To change this template use File | Settings | File Templates.
 */
?>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Author </title>
        <link href="<?php echo base_url() . '../css/bootstrap/bootstrap.min.css' ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url() . '../css/bootstrap/bootstrap-theme.css' ?>" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="../css/bootstrap/bootstrap.css"/>
        <script type="text/javascript" src="<?php echo base_url() . '../js/jquery.js' ?>"></script>
<!--        <script type="text/javascript" src="--><?php //echo base_url() . '../js/jquery.min.js' ?><!--"></script>-->
        <script type="text/javascript" src="<?php echo base_url() . '../js/jquery.min.js' ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() . '../js/bootstrap/bootstrap.min.js' ?>"></script>
        <link href="<?php echo base_url() . 'css/layout.css' ?>" rel="stylesheet" type="text/css"/>

    </head>
    <style type="text/css">
        .navbar {
            margin-top: 20px;
        }
    </style>
    <body>
    <div class="container-fluid">
        <?php $this->load->view('sub_pages/main_userinfo') ?>
        <?php echo $data['content']; ?>
    </div>

    </div>
    </body>
    <!--    <script type="text/javascript" src="-->
    <?php //echo base_url() . '../js/bootstrap/bootstrap.js' ?><!--"></script>-->
    <!--    <script type="text/javascript" src="-->
    <?php //echo base_url() . '../js/bootstrap/bootstrap.min.js' ?><!--"></script>-->
    <!--    <script type="text/javascript" src="js/jquery.ui.core.js"></script>-->
    <!--    <script type="text/javascript" src="-->
    <?php //echo base_url() . '../js/bootstrap/bootstrap-datetimepicker.js' ?><!--"></script>-->

    </html>
<?php die(); ?>