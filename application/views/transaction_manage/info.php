<div class="out-breadcrumb">
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a href="#">Giao dịch</a></li>
	</ul>
</div>
<div class="parent-transaction-acc">
	<div class="list-acc-item">
		<div class="accinfo">
			<div class="trans-item item-payment trans-hover">
				<div class="trans-item-payment">
                    <a class="help" href="javascript:;" title="Hướng dẫn nạp tiền" data-media="<?php echo base_url('images/help/dieukiensudung.png') ?>|<?php echo base_url('images/help/Naptien1.png') ?>|<?php echo base_url('images/help/Naptien2.png') ?>|<?php echo base_url('images/help/Naptien3.png') ?>|<?php echo base_url('images/help/Naptien4.png') ?>">
							 <span class="text">Hướng dẫn <i class="fa fa-angle-double-right"></i></span>
							<span class="icon">?</span>
							</a>
					<div id="trans-manage-text-item-payment" class="trans-manage-text upper ">
						Nạp tiền
					</div>
				</div>
			</div>
		</div>
		<div class="accinfo">
			<div class="trans-item item-withdraw trans-hover">
				<div class="trans-item-withdraw">
                    <a class="help" href="javascript:;" title="Hướng dẫn rút tiền" data-media="<?php echo base_url('images/help/Ruttien1.png') ?>|<?php echo base_url('images/help/Ruttien2.png') ?>|<?php echo base_url('images/help/Ruttien3.png') ?>">
                        <span class="text">Hướng dẫn <i class="fa fa-angle-double-right"></i></span>
                        <span class="icon">?</span>
                    </a>
					<div id="trans-manage-text-item-withdraw" class="trans-manage-text upper ">
						Rút tiền
					</div>
				</div>
			</div>
		</div>
		<div class="accinfo">
			<div class="trans-item item-transfer trans-hover">
				<div class="trans-item-transfer">
                    <a class="help" href="javascript:;" title="Hướng dẫn chuyển tiền" data-media="<?php echo base_url('images/help/Chuyentien1.png') ?>|<?php echo base_url('images/help/Chuyentien2.png') ?>|<?php echo base_url('images/help/Chuyentien3.png') ?>">
                        <span class="text">Hướng dẫn <i class="fa fa-angle-double-right"></i></span>
                        <span class="icon">?</span>
                    </a>
					<div id="trans-manage-text-item-transfer" class="trans-manage-text upper ">
						Chuyển tiền
					</div>
				</div>
			</div>
		</div>
		<div class="accinfo">
			<div class="trans-item item-bank-map trans-hover">
				<div class="trans-item-bank-map">
					<div id="trans-manage-text-item-bank-map" class="trans-manage-text upper ">
						Liên kết Bank
					</div>
				</div>
			</div>
		</div>
		<div class="accinfo">
			<div class="trans-item item-payment-mobile trans-hover">
				<div class="trans-item-payment-mobile">
					<div id="trans-manage-text-item-payment-mobile" class="trans-manage-text upper ">
						Nạp điện thoại
					</div>
				</div>
			</div>
		</div>
		<div class="accinfo">
			<div class="trans-item item-payment-game trans-hover">
				<div class="trans-item-payment-game">
					<div id="trans-manage-text-item-payment-game" class="trans-manage-text upper ">
						Nạp Game
					</div>
				</div>
			</div>
		</div>
		<div class="accinfo">
			<div class="trans-item item-topup trans-hover">
				<div class="trans-item-topup">
					<div id="trans-manage-text-item-topup" class="trans-manage-text upper ">
						Mua mã thẻ
					</div>
				</div>
			</div>
		</div>
		<div class="accinfo">
			<div class="trans-item item-thanh-toan trans-hover">
				<div class="trans-item-thanh-toan">
					<div id="trans-manage-text-item-thanh-toan" class="trans-manage-text upper ">
						Thanh toán
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	echo (isset($popup)) ? $popup : "" ;
?>
<script type="text/javascript">
    $(function() {
        $(".help").click(function(e) {
            e.stopPropagation();
            var title = $(this).attr('title');
            var data = [];
            var media = $(this).data('media').split('|');
            for (var i in media) {
                data.push({
                    href: media[i],
                    title: title
                });
            }
            $.fancybox.open(data, {
                prevEffect : 'none',
                nextEffect : 'none',
                loop: false
            });
        });
		
    });
</script>