
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a>Giao dịch</a></li>
		<li><a>Nạp điện thoại</a></li>
	</ul>

<div class="col-md-12 form-center">
	<input type="hidden" id="get_balance_update" value="<?php echo (isset($balance) && $balance !='') ? number_format($balance) : ''; ?>"/>
    <img src="<?php echo base_url() . "/images/success.png"; ?>" class="img-responsive header" style="width:100px;margin-top:0;">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 msg-done" style="font-size:14px;margin-bottom: 20px;text-align:center;color:#48b14a;"><?php echo $mess; ?></div>
        <div class="col-xs-12">
            <div class="form-group" style="padding-left:70px;">
                <a class="button button-main button180" href="<?php echo base_url('payment_phone'); ?>" style="color:#333;">Về trang nạp điện thoại</a>
                <a id="re-trans-history" href="<?php echo base_url() . 'trans_history/index/paymentphone/' . $detail['trans_id']; ?>" class="button button-sub button180">Xem lịch sử giao dịch</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" language="javascript">
    $(document).ready(function($) {
        var balance = document.getElementById("get_balance_update").value;
        if (balance != '') {
            window.parent.document.getElementsByClassName("balance")[0].innerHTML = balance + ' đ';
        }
		
		$('#re-trans-history').bind('click', function(event) { 
			$('body', parent.document).removeClass("bg-trans");
			$("#side-menu > li > a", parent.document).removeClass("active");
			$('.trans-history', parent.document).addClass("active");
			$("li.transaction > span > a", parent.document).css("color","");
		});
		
    });
</script>