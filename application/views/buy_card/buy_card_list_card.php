
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a href="#">Giao dịch</a></li>
		<li><a href="#">Mua mã thẻ</a></li>
	</ul>
	<input type="hidden" id="get_balance_update" value="<?php echo (isset($balance) && $balance !='') ? number_format($balance) : ''; ?>"/>
	<div class="col-md-12 form-center">
		Mua mã thẻ thành công. Thông tin mã thẻ:
		<div style="overflow: auto; max-height: 400px;">
			<table class="tbl-bank-info" style="width: 100%; margin: 0;">
				<thead>
					<tr>
						<td>Mã giao dịch</td>
						<td>Thời gian giao dịch</td>
						<td>Nhà cung cấp</td>
						<td>Mệnh giá(đ)</td>
						<td>Serial thẻ</td>
						<td>Mã thẻ</td>
						<td>Ngày hết hạn</td>
					</tr>
				</thead>
				<tbody>
					<?php if(isset($download_items)): ?>
						<?php foreach($download_items as $itemSofpin): ?>
							<tr>
								<td><?php echo isset($trans_id) ? $trans_id : ''; ?></td>
								<td><?php echo isset($timecreate) ? $timecreate : ''; ?></td>
								<td><?php 
									switch($itemSofpin->telco_code){
										case 'VTT': echo "Viettel";
										break;
										case 'VNP': echo "Vinaphone";
										break;
										case 'VMS': echo "Mobifone";
										break;
										default : echo "";
										break;
									}
								?></td>
								<td><?php echo number_format($amount); ?></td>
								<td><?php echo $itemSofpin->card_serial; ?></td>
								<td><?php echo $itemSofpin->card_pin; ?></td>
								<td><?php echo $itemSofpin->expired_date; ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		
		<div class="action-buycard">
			<a class="button button-main" href="/buy_card">Về trang mua mã thẻ</a>
			 <a id="re-trans-history" href="<?php echo base_url() . 'trans_history/index/topup/' . $trans_id; ?>" class="button button-sub button180">Xem lịch sử giao dịch</a>
			<form action="/buy_card/export_excel_listsofpin" accept-charset="utf-8" method="post" role="form" style="display: inline;">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			<button type="submit" class="btn btn-success btn-export-excel pull-right export-listcard" name="excel">Xuất Excel</button>
			</form>
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
<script>
		setTimeout(function () {
		   window.location.href = "/buy_card"; 
		}, <?php echo TIME_REDIRECT_LIST_CARD; ?>);
</script>
