<link href="<?php echo '../css/payment.css' ?>" rel="stylesheet" type="text/css"/>

<div class=" row col-md-12 col-sm-12 col-xs-12 center-block " style="text-align: center;">
    <label class="txt_title"> CÁC LOẠI HÌNH THANH TOÁN</label>
</div>
<div class=" row col-md-12 col-sm-12 col-xs-12 center-block float_none ">
    <ul class="list-group" id="list_tt">
        <li class="list-group-item item_tt container-fluid" style="background: #1192d8">
             <div class="row">
                 <div class="col-md-1 col-sm-1 col-xs-1"> <img src="../images/payment/forma.png" style="width: 20px; height: 20px" /></div>
                 <a href="<?php echo site_url('payment/payment_card') ?>" class="col-md-10 col-sm-10 col-xs-10">
                     <label class="txt_contain col-xs-12">Thanh toán qua thẻ cào</label>
                 </a>
             </div>
        </li>
        <li class="list-group-item item_tt container-fluid" style="background: #1192d8">
            <div class="row">
                <div class="col-md-1 col-sm-1 col-xs-1"> <img src="../images/payment/Forma_hou.png" style="width: 20px; height: 20px" /></div>
                <a href="<?php echo site_url('payment/payment_bank') ?>" class="col-md-10 col-sm-10 col-xs-10">
                    <label class="txt_contain col-xs-12">Thanh toán qua ngân hàng</label>
                </a>
            </div>
        </li>
<!--                <li class="list-group-item item_tt container-fluid" style="background: #1192d8">-->
<!--                    <div class="row">-->
<!--                        <div class="col-md-2 col-sm-1 col-xs-1"> <img src="../images/payment/Forma_mobi.png" style="width: 20px; height: 20px" /></div>-->
<!--                        <div class="col-md-10 col-sm-10 col-xs-10"> <label class="txt_contain">Thanh toan qua the cao</label></div>-->
<!--                    </div>-->
<!--                </li>-->
    </ul>
</div>
