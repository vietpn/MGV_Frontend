<link href="<?php echo '../css/payment.css' ?>" rel="stylesheet" type="text/css"/>
<?php if(!isset($payment_type[4])): ?>
<div class="row col-md-12 col-sm-12 col-xs-12 center-block " style="text-align: center;">
    <label class="txt_title"> CÁC LOẠI HÌNH THANH TOÁN</label>
</div>
<?php endif; ?>
<div class="row col-md-12 col-sm-12 col-xs-12 center-block float_none ">
    <ul class="list-group" id="list_tt">
        <?php if(isset($payment_type[2])): ?>
        <li class="list-group-item item_tt container-fluid" style="background: #1192d8">
            <div class="overflow row">
                <div class="po_absolute col-md-1 col-sm-1 hidden-xs"> <img src="../images/payment/forma.png" style="width: 20px; height: 20px" /></div>
                <a href="<?php echo site_url('payment/payment_card') ?>" class=" payment_item col-md-10 col-sm-10 col-xs-12">
                    <p class="txt_contain col-xs-12">Thanh toán qua thẻ cào</p>
                </a>
            </div>
        </li>
        <?php endif; ?>
        
        <?php if(isset($payment_type[3])): ?>
        <li class="list-group-item item_tt container-fluid" style="background: #1192d8">
            <div class="overflow row">
                <div class="po_absolute col-md-1 col-sm-1 hidden-xs"> <img src="../images/payment/Forma_hou.png" style="width: 20px; height: 20px" /></div>
                <a href="<?php echo site_url('payment/payment_bank') ?>" class="payment_item col-md-10 col-sm-10 col-xs-12">
                    <p class="txt_contain col-xs-12">Thanh toán qua ngân hàng</p>
                </a>
            </div>
        </li>
        <?php endif; ?>
        <?php if(isset($payment_type[1]) && !$this->agent->is_mobile('iphone') && !$this->agent->is_mobile('ipad')  && !$this->agent->is_mobile('ipod')): ?>
        <li class="list-group-item item_tt container-fluid" style="background: #1192d8">
            <div class="overflow row">
                <div class="po_absolute col-md-1 col-sm-1 hidden-xs"> <img src="../images/payment/Forma_mobi.png" style="width: 20px; height: 20px" /></div>
                <a href="<?php echo site_url('payment/payment_sms') ?>" class="payment_item col-md-10 col-sm-10 col-xs-12">
                    <p class="txt_contain col-xs-12">Thanh toán qua SMS</p>
                </a>
            </div>
        </li>
        <?php endif; ?>
		
		<?php if(isset($payment_type[4])): ?>
        <li class="list-group-item item_tt container-fluid" style="background: #1192d8">
            <div class="overflow row">
                <div class="po_absolute col-md-1 col-sm-1 hidden-xs"> <img src="../images/payment/Forma_mobi.png" style="width: 20px; height: 20px" /></div>
                <a href="<?php echo site_url('payment/payment_iap') ?>" class="payment_item col-md-10 col-sm-10 col-xs-12">
                    <p class="txt_contain col-xs-12">In app purchase</p>
                </a>
            </div>
        </li>
        <?php endif; ?>
		
    </ul>
	<?php if(!isset($payment_type[4])): ?>
	<div style="  color: red; font-size: 16px; text-align: center; font-weight: bold;" >
		hotline: 1900 1255
	</div>
	<?php endif; ?>
</div>