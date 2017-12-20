<ul class="breadcrumb">
    <li class="first">|</li>
    <li><a>Rút tiền</a></li>
</ul>

<div class="form-center step-chose-three">
    <div class="step-by-step">
        <ol class="progtrckr" data-progtrckr-steps="4">
            <li class="progtrckr-todo step_one"><span class="hidden-xs hidden-sm">Chọn phương thức rút tiền</span></li>
            <li class="progtrckr-todo step_two"><span class="hidden-xs hidden-sm">Khai báo thông tin rút</span></li>
            <li class="progtrckr-done step_three"><span>Xác nhận giao dịch - hoàn thành</span></li>

        </ol>
    </div>
    <input type="hidden" id="get_balance_update" value="<?php echo (isset($balance) && $balance !='') ? number_format($balance) : ''; ?>"/>
    <img src="<?php echo base_url() . "/images/success.png"; ?>" class="img-responsive header" style="width:100px;padding-left:45px;margin-top:0;">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 msg-done" style="font-size:14px;margin-bottom: 20px;text-align:center;color:#48b14a;"><?php echo $mess; ?></div>
        <div class="col-xs-12">
			<?php if(isset($detail)): ?>
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
							<td class="value">
								<?php 
									switch($detail->withdrawType){
										case '6': echo "Rút tiền theo phiên";
										break;
										case '7': echo "Rút tiền nhanh";
										break;
										case '8': echo "Rút tiền qua tài khoản liên kết";
										break;
									}
								?>
							</td>
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
			<?php endif; ?>
        </div>
        <div class="col-xs-12">
            <div class="form-group" style="padding-left:45px;">
                <a class="button button-main" href="<?php echo base_url('withdraw'); ?>" style="color:#333;">Về trang rút tiền</a>
                <a id="re-trans-history" href="<?php echo base_url() . 'trans_history/index/withdraw/' . $detail->orgTransId; ?>" class="button button-sub button180">Xem lịch sử giao dịch</a>
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
