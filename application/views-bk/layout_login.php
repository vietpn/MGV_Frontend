<!DOCTYPE html>
<?php
ini_set('zlib_output_compression','On');
?>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VNPTEPAY <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
    <link href="<?php echo base_url() . '../css/bootstrap/bootstrap.min.css' ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url() . '../css/bootstrap/bootstrap-theme.css' ?>" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap/bootstrap.css"/>
    <link href="<?php echo base_url() . '../css/login.css' ?>" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="<?php echo base_url() . '../js/jquery.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url() . '../js/jquery.min.js' ?>"></script>
<!--    <script type="text/javascript" src="--><?php //echo base_url() . '../js/bootstrap/bootstrap.js' ?><!--"></script>-->
    <script type="text/javascript" src="<?php echo base_url() . '../js/bootstrap/bootstrap.min.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url() . '../js/login/login.js' ?>"></script>
<!--    <script type="text/javascript" src="../js/jquery.ui.core.js"></script>-->
    <script type="text/javascript" src="../js/script.js"></script>




</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-md-offset-5 hidden-xs ">
            <a href="<?php echo base_url() ?>"> <img src="../../images/info/logo.png" alt="logo Epay" class="img_log"/></a>
        </div>
        <div style="background: #ffffff; height:20%" class="hidden-md hidden-sm hidden-lg hidden-xs">
            <h2 style="margin: 0px;padding: 5%;border-bottom: 1px #E4E4E6 inset;">
                <b><span id="txt_epay">EPAY</span> <span id="txt_Id">ID</span></b>
                <a href="../register" style="font-size: 50%;float: right;margin-top: 5%"><b> ĐĂNG KÝ</b></a>
            </h2>
        </div>
        <div class="col-md-3 hidden-xs  ">
            <h5> <?php  if (isset($this->session->userdata['info_user']['userID'])){ ?>
                <a href='../login/logout'>Đăng xuất</a>
                <?php }else{ ?>
                    <a href="../register">Đăng ký</a>
                <?php }?>
                <br>Hotline: <b>1900 64 70</b> <i>(7h30-22h)</i>
                <h5>
        </div>
<!--content website-->
        <?php echo $data['content'];?>

    </div>

    <div class="footer row hidden-xs" style="text-align: center">
    <span style="color: #666666; font-family: Arial;font-size: 13px;">@2014 BẢN QUYỀN CỦA CÔNG TY CỔ PHẦN THANH TOÁN ĐIỆN TỬ VNPT
           <br> Trụ sở Hà Nội : Tầng 3 - Tòa nhà Viễn Đông - 36 Hoàng Cầu - Quận Đống Đa - Hà Nội
           <br> Chi nhánh tại TPHCM : Địa chỉ: Lầu 3, số 96 – 98 Đào Duy Anh, Phường 9, Quận Phú Nhuận, TP HCM</span>
    </div>

</div>


<div id="fb-root"></div>


</body>

</html>
