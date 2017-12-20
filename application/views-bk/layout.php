<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Author <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'css/register.css' ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/js/libs/jquery-1.11.3.min.js' ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'css/bootstrap/bootstrap.min.css' ?>"/>
    <script type="text/javascript" language="javascript" src="<?php echo base_url() . "js/script.js"; ?>"></script>
</head>
<body>

<div id="container-fluid">
    <div class="row">
        <div class="col-md-3 col-md-offset-5 hidden-xs ">
           <a href=<?php echo base_url() ?> <img src="../../images/info/logo.png" alt="logo Epay" style="padding-top: 10%" /></a>
<!--            <h2><b><span id="txt_epay">EPAY</span> <span id="txt_Id">ID</span></b></h2>-->
        </div>
        <div style="background: #ffffff; height:20%" class="hidden-md hidden-sm hidden-lg">
            <h2 style="margin: 0px;padding: 5%;border-bottom: 1px #E4E4E6 inset;">
                <b><span id="txt_epay">EPAY</span> <span id="txt_Id">ID</span></b>
                <a href="./register" style="font-size: 50%;float: right;margin-top: 5%"><b> ĐĂNG KÝ</b></a>
            </h2>
        </div>
        <div class="col-md-3 hidden-xs  ">
            <h5><?php
                if (!isset($this->session->userdata['info_user']['userID'])){ ?>
                <a href='<?php echo base_url() ?>'>Đăng nhập</a>
                <?php }else{ ?>
                <a href="../register">Đăng ký</a>
                <?php }?>
                <br>Hotline: <b>1900 64 70</b> <i>(7h30-22h)</i>
                <h5>
        </div>
    </div>
    <div class="row" id="body">
        <div>
            <?php echo $data['content']; ?>
        </div>
    </div>
</body>
</html>
<?php die(); ?>