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
<ul class="breadcrumb">
    <li class="first">|</li>
    <li><a>Rút tiền</a></li>
</ul>

<div class="col-md-12 form-center">
    <div class="step-by-step">
        <ol class="progtrckr" data-progtrckr-steps="4">
            <li class="progtrckr-todo">Chọn phương thức rút tiền</li>
            <li class="progtrckr-todo">Khai báo thông tin rút</li>
            <li class="progtrckr-done">Xác nhận giao dịch - hoàn thành</li>

        </ol>
    </div>
    <input type="hidden" id="get_balance_update" value="<?php echo (isset($balance) && $balance !='') ? number_format($balance) : ''; ?>"/>
    <img src="<?php echo base_url() . "/images/success.png"; ?>" class="img-responsive header" style="width:125px;padding-left:45px;margin-top:0;">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px;text-align:center;color:#48b14a;"><?php echo $mess; ?></div>
        <div class="row">
            <div class="form-group">
                <style type="text/css">
                    .withdraw-success-tbl {
                        width: 100%;
                        margin-bottom: 17px;
                    }
                    .withdraw-success-tbl td {
                        border: 1px solid #525252;
                        padding: 4px 15px;
                    }
                    .withdraw-success-tbl tr > td:first-child {
                        font-family: "Roboto Light";
                    }
                    .withdraw-success-tbl .value {
                        color: #fff;
                    }
                    .withdraw-success-tbl .avalue {
                        color: #48b14a;
                    }
                </style>
                <table class="withdraw-success-tbl">
                    <tr>
                        <td>Hình thức rút tiền</td>
                        <td class="value">Tài khoản ngân hàng</td>
                    </tr>
                    <tr>
                        <td>Thời gian giao dịch</td>
                        <td class="value"><?php echo date("d/m/Y H:i:s", strtotime($detail->timeCreate)) ?></td>
                    </tr>
                    <tr>
                        <td>Mã giao dịch</td>
                        <td class="value"><?php echo $detail->orgTransId ?></td>
                    </tr>
                    <tr>
                        <td>Số tiền rút (vnđ)</td>
                        <td class="avalue"><?php echo number_format($detail->realMinus) ?></td>
                    </tr>
                    <tr>
                        <td>Phí giao dịch (vnđ)</td>
                        <td class="avalue"><?php echo number_format($detail->feeAmount) ?></td>
                    </tr>
                    <tr>
                        <td>Tiền thanh toán (vnđ)</td>
                        <td class="avalue"><?php echo number_format($detail->amount) ?></td>
                    </tr>
                    <tr>
                        <td>Trạng thái</td>
                        <td class="value">Thành công</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="form-group" style="width:400px;padding-left:45px;">
                <a class="button button-main" href="<?php echo base_url('withdraw'); ?>" style="color:#333;">Về trang rút tiền</a>
                <a href="<?php echo base_url('trans_history'); ?>" class="button button-sub button180">Xem lịch sử giao dịch</a>
            </div>
        </div>
    </div>
</div>


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
<script type="text/javascript" language="javascript">
    $(document).ready(function($) {
        var balance = document.getElementById("get_balance_update").value;
        if (balance != '') {
            window.parent.document.getElementsByClassName("balance")[0].innerHTML = balance + ' đ';
        }
    });
</script>
</body>
</html>