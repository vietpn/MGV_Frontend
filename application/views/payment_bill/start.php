<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/js/libs/owl.carousel/owl.carousel.css" ?>'/>
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/owl.carousel/owl.carousel.min.js"; ?>'></script>
	<ul class="breadcrumb">
		<li class="first">|</li>
    <li><a>Giao dịch</a></li>
		<li><a>Thanh toán hoá đơn</a></li>
	</ul> 
<div class="cash_in cash_in_new_choose">
    <ul class="nav nav-tabs payment_epurse_new" role="tablist" data-dots="false" data-loop="false" data-nav="false" data-margin="0" data-autoplayTimeout="1000" data-autoplayHoverPause="true" data-responsive='{"0":{"items":3},"600":{"items":3}}'>
        <li><a href="/payment_bill/index/HOME_CREDIT">VAY TRẢ GÓP THẺ TÍN DỤNG HOME CREDIT</a></li>
        <li><a href="/payment_bill/index/FE_CREDIT">VAY TRẢ GÓP THẺ TIÊU DÙNG FECREDIT</a></li>
        <li><a data-toggle="tab" href="#tab3">EC-Merchant</a></li>
    </ul>
</div>

<script>
function viewport() {
    var e = window, a = 'inner';
    if (!('innerWidth' in window )) {
        a = 'client';
        e = document.documentElement || document.body;
    }
    return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
}

var deviceWidth = viewport();
if (deviceWidth.width<'570'){
	$('.payment_epurse_new').addClass('owl-carousel');

	$(".owl-carousel").each(function(index, el) {
        var config = $(this).data();
        config.navText = ['<i class="fa fa-angle-double-left" aria-hidden="true"></i>','<i class="fa fa-angle-double-right" aria-hidden="true"></i>'];
        config.smartSpeed="300";
        if($(this).hasClass('owl-style2')){
          config.animateOut="fadeOut";
          config.animateIn="fadeIn";    
          config.navigation=true;    
        }
        $(this).owlCarousel(config);
      });
}
</script>

