
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a>Chuyển tiền</a></li>
	</ul> 

<div class="col-md-12 form-center">
	<div class="form-group">
		<div class="notify-msg" style="white-space: nowrap; margin-bottom: 15px;"><span style="color: #ccc; font-family: roboto light;">MegaV có hỗ trợ tính năng chuyển tiền trong nội bộ ví và giữa 2 ngăn ví.</span></div>
		
		<div class="transfer-balance">
			<span class="">Số dư khả dụng (đ)</span><span class="balance"><?php echo isset($balance) ? number_format($balance) : 0;?> đ</span>
		</div>
		<div class="transfer-balance">
			<span class="">Số dư tạm giữ (đ)</span><span class="balance">0 đ</span>
		</div>
		
		<a class="button-main transfer-btn" href="/transfer/transfer_epurse" id='transfer'>Chuyển tiền</a>
		<!--a class="button-main transfer-btn" id='transfer'>Chuyển tiền</a-->
		<div class="clearfix"></div>
	</div>
</div>

