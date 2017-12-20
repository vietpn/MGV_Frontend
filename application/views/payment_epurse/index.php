<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/js/libs/owl.carousel/owl.carousel.css" ?>'/>
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/owl.carousel/owl.carousel.min.js"; ?>'></script>
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/select2.min.js"; ?>'></script>
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a>Nạp tiền</a></li>
	</ul> 
<div class="cash_in">
    <ul class="nav nav-tabs payment_epurse_new" role="tablist" data-dots="false" data-loop="false" data-nav="false" data-margin="0" data-autoplayTimeout="1000" data-autoplayHoverPause="true" data-responsive='{"0":{"items":3},"600":{"items":3}}'>
        <li class="<?php echo (isset($step) && $step=='0') ? 'active' : ''; ?>">
			
			<a data-toggle="tab" href="#tab1">
				Nạp tiền nhanh
			</a>
		</li>
        <li class="<?php echo ((isset($step) && ($step=='1' ||  $step=='2' ||  $step=='3')) || (isset($check_valid1) && $check_valid1 == '1')) ? 'active' : ''; ?>"><a data-toggle="tab" href="#tab2">Nạp tiền tài khoản liên kết</a></li>
        <li><a data-toggle="tab" href="#tab3">Hỗ trợ thông tin nạp tiền theo phiên</a></li>
    </ul>
<?php // log_message('error', 'data view step: ' . print_r($step, true)); ?>
    <div class="tab-content">
        <div id="tab1" class="tab-pane fade <?php echo (isset($step) && $step=='0') ? 'in active' : ''; ?>">
            <div class="tab-main">
                <div class="form-center">
					<?php if(isset($finish)): ?>
						<input type="hidden" id="get_balance_update" value="<?php echo (isset($balance) && $balance !='') ? number_format($balance) : ''; ?>"/>
						<img src="<?php echo base_url() . "/images/success.png"; ?>" class="img-responsive header" style="width:125px;padding-left:45px;margin-top:0;">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px;text-align:center;color:#48b14a;"><?php echo $mess; ?></div>
							<?php if(isset($detail)): ?>
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
												<td>Hình thức nạp tiền</td>
												<td class="value">Nạp tiền nhanh</td>
											</tr>
											<tr>
												<td>Thời gian giao dịch</td>
												<td class="value"><?php echo date("d/m/Y H:i:s", strtotime($detail->timeCreate)) ?></td>
											</tr>
											<tr>
												<td>Mã giao dịch</td>
												<td class="value"><?php echo $detail->requestId ?></td>
											</tr>
											<tr>
												<td>Số tiền nạp (đ)</td>
												<td class="avalue"><?php echo number_format($detail->amount) ?></td>
											</tr>
											<tr>
												<td>Phí giao dịch (đ)</td>
												<td class="avalue"><?php echo number_format($detail->feeAmount) ?></td>
											</tr>
											<tr>
												<td>Tiền thanh toán (đ)</td>
												<td class="avalue"><?php echo number_format($detail->realReceive) ?></td>
											</tr>
											<tr>
												<td>Trạng thái</td>
												<td class="value">
													<?php 
														switch($detail->status)
														{
															case "00": echo "Thành công";
															break;
															case "99": echo "Chờ xử lý";
															break;
															default: echo "Thất bại";
															break;
														}
													?>
												</td>
											</tr>
										</table>
									</div>
								</div>
							<?php endif; ?>
							<div class="success-pay">
								<div class="form-group" style="width:400px;padding-left:45px;">
									<a class="button button-main" href="<?php echo base_url('payment_epurse'); ?>" style="color:#333;">Về trang nạp tiền</a>
									<a id="re-trans-history" href="<?php echo base_url() . 'trans_history/index/payment/' . $detail->requestId; ?>" class="button button-sub button180">Xem lịch sử giao dịch</a>
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
					<?php else: ?>
						<?php echo form_open('/payment_epurse/index', array('method' => 'post', 'role' => 'form')); ?>
						<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
						<div class="form-group">
							<label>Phương thức nạp</label>
							<div class="div-input">
								<?php $paymentType = array(array('val' => '1', 'name' => 'Thẻ ATM / Tài khoản ngân hàng'),
									array('val' => '2', 'name' => 'Thẻ visa / Master card')
								);
								?>
								<select name="payment_type" class="form-input" id="paymentType">
									<?php foreach($paymentType as $type): ?>
										<option value="<?php echo $type['val']; ?>" <?php echo set_select('payment_type', $type['val'], False); ?>><?php echo $type['name']; ?></option>
									<?php endforeach; ?>
								</select>
								<span class="form-error"><?php echo form_error('payment_type'); ?></span>
							</div>
							<div class="clearfix"></div>
						</div>
						
						<div class="form-group provider">
							<label>Ngân hàng</label>
							<div class="div-input">
								<select id="providerCode" name="provider_code" class="form-input" data-select-search="true">
									<option value="">Chọn ngân hàng</option>
									<?php if(isset($listBank)): ?>
										<?php log_message('error', 'list bank view: ' . print_r($listBank, true)); ?>
										<?php foreach($listBank as $bank): ?>
											<?php // if($bank->type == '2'): ?>
												<option value="<?php echo $bank->providerId; ?>" <?php echo set_select('provider_code', $bank->providerId, False); ?>><?php echo isset($bank->providerName) ? $bank->providerName : ""; ?></option>
											<?php // endif; ?>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
								<span class="form-error"><?php echo form_error('provider_code'); ?></span>
							</div>
							<div class="clearfix"></div>
						</div>
						
						<div class="form-group">
							<label>Số tiền nạp (đ)</label>
							<div class="div-input">
								<input id="amountPayment" class="form-input change_currency" type="text" name="amount" maxlength="11" placeholder="Nhập số tiền cần nạp" value="<?php echo set_value('amount') ?>">
								<span class="form-error"><?php echo form_error('amount'); ?></span>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="form-group">
							<label>Phí nạp tiền (đ)</label>
							<div class="div-input">
								<span id="feePayment" class="amount">0</span>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="form-group">
							<label>Số tiền trừ trên thẻ (đ)</label>
							<div class="div-input">
								<span id="realAmount" class="amount">0</span>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="form-group button-group">
							<label class="hidden-xs hidden-sm">&nbsp;</label>
							<div class="div-input">
								<input name="paymentOnline" type="submit" class="button button-main" value="Nạp tiền"/>
								<a class="button button-sub" target="_parent" href="/transaction_manage">Hủy</a>
							</div>
							<div class="clearfix"></div>
						</div>
						<?php echo form_close(); ?>
					<?php endif; ?>

                </div>
            </div>
        </div>
        <div id="tab2" class="tab-pane fade <?php echo ((isset($step) && ($step=='1' ||  $step=='2' ||  $step=='3')) || (isset($check_valid1) && $check_valid1 == '1')) ? 'in active' : ''; ?>">
            <div class="tab-main">
				<div class="form-center">
					<div class="form-group">
						<p class="text-center" style="white-space: nowrap;"><?php
						//if (isset($step) && $step == '1') {
							if($this->session_memcached->userdata['info_user']['security_method'] == '1') {
							 		echo isset($mess_otp_success) ? $mess_otp_success : ''; 
							}
						//}
						 ?></p>
					</div>
					<!-- <div class="form-group">
						<div class="warning-msg">
							<span style="color: #ccc;">Chức năng này đang trong quá trình nâng cấp và chưa sử dụng được. Vui lòng trở lại sau. <br> MegaV xin chân thành cảm ơn!</span>
						</div>
					</div> -->
					<?php echo form_open('/payment_epurse/index', array('method' => 'post', 'role' => 'form')); ?>
					<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
				    <?php 
				    if (isset($step) && $step == '3') { ?>
				    	<div class="row text-center">
										    <img src="<?php echo base_url();?>images/resulr-warning.png" class="img-responsive header" style="width:70px;height: 70px;margin-top: -30px;" />

										    <div class="result-text">
										            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 monney_text_color text-center" style="margin-bottom: 20px;padding-left: 20px;padding-right: 20px;">
														Đây là yêu cầu nhập OTP do giao dịch của bạn vượt ngưỡng giao dịch theo quy định của ngân hàng <?php echo isset($result_info['bankName']) ? $result_info['bankName'] : '';?>.<br />
														Bạn vui lòng nhập OTP nhận được của ngân hàng <?php echo isset($result_info['bankName']) ? $result_info['bankName'].' ' : '';?>dưới đây.
										            </div>
										            <div class="row">
										                <input type="hidden" id="get_balance_update" value="<?php echo isset($balance) ? $balance : ''; ?>">
										            </div>

										    </div>
					    		<div class="form-group">
										<label style="margin: 5px 0 0 120px;">Nhập OTP</label>
										<div class="div-input div-input-map">
											<input type="password" class="form-control bill_info_style" name="otp" placeholder="Nhập mã xác nhận" autocomplete="off"/>
											<input type="hidden" name="trans_id" value="<?php echo isset($result_info['requestId']) ? $result_info['requestId'] : ''; ?>" />
											<input type="hidden" name="amount" value="<?php echo isset($result_info['realReceive']) ? $result_info['realReceive'] : ''; ?>" />
											<input type="hidden" name="bank_name" value="<?php echo isset($result_info['bankName']) ? $result_info['bankName'] : ''; ?>" />
											<span style="float: left;" class="form-error"><?php echo isset($error_lv2) ? $error_lv2 : ""; ?><?php echo form_error('otp'); ?></span>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="form-group button-group">
									<label class="hidden-xs hidden-sm">&nbsp;</label>
									<div class="div-input div-input-map" style="text-align: left;margin-top: 0px;">
										<input name="paymentMappingStep3" type="submit" class="button button-main" value="Hoàn thành"/>
										<a class="button button-sub" target="_parent" href="/transaction_manage">Hủy</a>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>

				    	<?php }elseif (isset($step) && $step == '2') { ?>

										<div class="row text-center">
										    <img src="<?php echo base_url();?>images/success.png" class="img-responsive header" style="width:70px;height: 70px;margin-top: -30px;" />

										    <div class="result-text">
										        <div class="row">
										            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 monney_text_color text-center" style="margin-bottom: 20px;">
														<?php echo isset($result_info['bankName']) ? 'Bạn đã nạp tiền vào ví thành công từ '.$result_info['bankName'].'.' : ''; ?>
										            </div>
										            <div class="row">
										                <input type="hidden" id="get_balance_update" value="<?php echo isset($balance) ? $balance : ''; ?>">
										            </div>
										        </div>

										    </div>
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
													.withdraw-success-tbl tr > td {
														font-family: "Roboto Light";
													}
													.withdraw-success-tbl .value {
														color: #fff;
														font-size: 14px;
													}
													.withdraw-success-tbl .avalue {
														color: #48b14a;
													}
													.monney_text_color{
														color: #48b14a;
													}
												</style>
												<table class="withdraw-success-tbl">
													<tr>
														<td>Hình thức nạp tiền</td>
														<td class="value">Nạp tiền qua liên kết tài khoản</td>
													</tr>
													<tr>
														<td>Thời gian giao dịch</td>
														<td class="value"><?php echo date("d/m/Y H:i:s", strtotime($result_info['timeCreate'])); ?></td>
													</tr>
													<tr>
														<td>Mã giao dịch</td>
														<td class="value"><?php echo $result_info['requestId']; ?></td>
													</tr>
													<tr>
														<td>Số tiền nạp (vnđ)</td>
														<td class="monney_text_color"><?php echo number_format($result_info['amount']); ?></td>
													</tr>
													<tr>
														<td>Phí giao dịch (vnđ)</td>
														<td class="monney_text_color"><?php echo number_format($result_info['feeAmount']); ?></td>
													</tr>
													<tr>
														<td>Tiền thanh toán (vnđ)</td>
														<td class="monney_text_color"><?php echo number_format($result_info['realReceive']); ?></td>
													</tr>
													<tr>
														<td>Trạng thái</td>
														<td class="value"><?php echo $result_info['status']; ?></td>
													</tr>
												</table>
											</div>
											<div class="text-center">
												<a class="button button-main" href="/payment_epurse">Về trang nạp tiền</a>
												<a id="re-trans-history" href="/trans_history/index/payment/<?php echo isset($result_info['requestId']) ? $result_info['requestId'] : ''; ?>" class="button button-sub button180">Xem lịch sử giao dịch</a>
											</div>
										</div>
										<script type="text/javascript" language="javascript">
										    $(document).ready(function($) {
												
										        var balance = document.getElementById("get_balance_update").value;
										        if (balance != '') {
										            window.parent.document.getElementsByClassName("balance")[0].innerHTML = balance + ' đ';
										        }
										    });
										</script>


	
					    <?php 
					    }elseif (isset($step) && $step == '1') { ?>
					    	   <div class="form-group">
									<label>Tài khoản ngân hàng liên kết</label>
									<div class="div-input div-input-map">
										<span><?php echo isset($bank_name) ? $bank_name : ''; ?></span>
										<input type="hidden" name="bank_name" value="<?php echo isset($bank_name) ? $bank_name : ''; ?>" />
										<input type="hidden" name="bank_code" value="<?php echo isset($bank_code) ? $bank_code : ''; ?>" />
									</div>
									<div class="clearfix"></div>
								</div>
								
								
								<div class="form-group">
									<label>Số tiền nạp (đ)</label>
									<div class="div-input div-input-map">
										<span><?php echo isset($amount) ? $amount : ''; ?></span>
										<input type="hidden" name="amount" value="<?php echo isset($amount) ? $amount : ''; ?>" />
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="form-group">
									<label>Phí nạp tiền (đ)</label>
									<div class="div-input div-input-map">
										<span><?php echo isset($feePaymentMap) ? $feePaymentMap : ''; ?></span>
										<input type="hidden" name="feePaymentMap" value="<?php echo isset($feePaymentMap) ? $feePaymentMap : ''; ?>" />
									</div>
									<div class="clearfix"></div>
								</div>
								<?php 
								if($this->session_memcached->userdata['info_user']['security_method'] == '1') { ?>
									<div class="form-group">
										<label>Nhập OTP</label>
										<div class="div-input div-input-map">
											<input type="password" class="form-control bill_info_style" name="otp" placeholder="Nhập mã xác nhận" autocomplete="off"/>
											<input type="hidden" name="trans_id" value="<?php echo isset($trans_id) ? $trans_id : ''; ?>" id="trans_id_map"/>
											<span class="form-error"><?php echo isset($error_lv2) ? $error_lv2 : $error_lv2; ?><?php echo form_error('otp'); ?></span>
										</div>
										<div class="clearfix"></div>
									</div>
								<?php 
								}else{ ?>
									<div class="form-group">
										<label>Mật khẩu cấp 2</label>
										<div class="div-input div-input-map">
											<input type="password" class="form-control bill_info_style" name="pass" placeholder="Nhập mật khẩu cấp 2" autocomplete="off"/>
											<input type="hidden" name="trans_id" value="<?php echo isset($trans_id) ? $trans_id : ''; ?>" />
											<span class="form-error"><?php echo isset($error_lv2) ? $error_lv2 : ""; ?><?php echo form_error('pass'); ?></span>
											<span><a class="resendMk" href="/reset_pass_lv2">Quên mật khẩu cấp 2?</a></span>
										</div>
										<div class="clearfix"></div>
									</div>
								<?php 
								}
								?>
								

								<div class="form-group button-group">
									<label class="hidden-xs hidden-sm">&nbsp;</label>
									<div class="div-input div-input-map">
										<input name="paymentMappingStep2" type="submit" class="button button-main" value="Hoàn thành"/>
										<a class="button button-sub" target="_parent" href="/transaction_manage">Hủy</a>
									</div>
									<div class="clearfix"></div>
								</div>
						<?php 
					    }else{ ?>
									
								<div class="form-group">
									<label>Ngân hàng</label>
									<div class="div-input">
										<?php $paymentType = array(array('val' => '1', 'name' => 'Thẻ ATM / Tài khoản ngân hàng'),
											array('val' => '2', 'name' => 'Thẻ visa / Master card')
										);
										?>
										<select name="bank_code" class="form-input" id="bank_code_map">
											<?php foreach($listBankMap as $value): ?>
												<option value="<?php echo $value->bankcode; ?>" <?php echo set_select('bank_code', $value->bankcode, False); ?>><?php echo $value->bankName; ?></option>
											<?php endforeach; ?>
										</select>
										<span class="form-error"><?php echo form_error('bank_code'); ?></span>
									</div>
									<div class="clearfix"></div>
								</div>
								
								
								<div class="form-group">
									<label>Số tiền nạp (đ)</label>
									<div class="div-input">
										<input id="amountPaymentMap" class="form-input change_currency" type="text" name="amount" maxlength="11" placeholder="Nhập số tiền cần nạp" value="<?php echo set_value('amount') ?>">
										<span class="form-error"><?php echo form_error('amount'); ?></span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="form-group">
									<label>Phí nạp tiền (đ)</label>
									<div class="div-input">
										<span id="feePaymentMap" class="amount">0</span>
										<input type="hidden" id="feePaymentMap2" class="amount" name="feePaymentMap" value="" />
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="form-group">
									<label>Số tiền trừ trên thẻ (đ)</label>
									<div class="div-input">
										<span id="realAmountMap" class="amount">0</span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="form-group button-group">
									<label class="hidden-xs hidden-sm">&nbsp;</label>
									<div class="div-input">
										<input name="paymentMappingStep1" type="submit" class="button button-main" value="Nạp tiền"/>
										<a class="button button-sub" target="_parent" href="/transaction_manage">Hủy</a>
									</div>
									<div class="clearfix"></div>
								</div>

							<?php 
					    }

					    ?>
						
						<?php echo form_close(); ?>
				</div>
			</div>
        </div>
        <div id="tab3" class="tab-pane fade">
            <div class="tab-main">
			
				<!--div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"-->
					<?php if(isset($paymentOffline) && $paymentOffline != false): ?>
					
					<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
						<?php

                         foreach($paymentOffline as $epayBank): ?>
						
								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="heading-<?php echo $epayBank->rowId; ?>">
										<h4 class="panel-title">
											<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $epayBank->rowId; ?>" aria-expanded="false" aria-controls="collapse<?php echo $epayBank->rowId; ?>">
												<?php echo $epayBank->bankCode ?>
											<i class="collapse-<?php echo $epayBank->rowId; ?> pull-right fa fa-angle-up"></i>
											</a>
										</h4> 
									</div>
									<div id="collapse-<?php echo $epayBank->rowId; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-<?php echo $epayBank->rowId; ?>">
										<div class="panel-body" style="border: medium none;">
											<p class="collapse-title-bank">Số tài khoản ngân hàng - Internet Banking</p>
												<table class="tbl-bank-info">
													<tr>
														<td class="tbl-th"><span>Số tài khoản</span></td>
														<td><span><?php echo $epayBank->bankAccount ?></span></td>
													</tr>
													<tr>
														<td class="tbl-th"><span>Chủ tài khoản</span></td>
														<td><span><?php echo $epayBank->bankAccountName ?></span></td>
													</tr>
													<tr>
														<td class="tbl-th"><span>Chi nhánh</span></td>
														<td><span><?php echo $epayBank->bankCode . ' - ' . $epayBank->branchName ?></span></td>
													</tr>
													<tr>
														<td class="tbl-th"><span>Nội dung</span></td>
														<td><span><?php echo $epayBank->description ?></span></td>
													</tr>
												</table>
											
										</div>
									</div>
								</div>
							<?php // break; ?>
						<?php endforeach; ?>
					  
					  
						 
						  
						  
					</div>
					
					<?php endif;?>
				<!--/div-->

			</div>
        </div>
		
    </div>
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
$(document).ready(function($) {
	
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
	
	/*var deviceWidth = viewport();
	if (deviceWidth.width <= '768') {
		$("#amountPayment").attr('type','number');
	}*/


    $("#providerCode").select2({
        tags: "true",
        width: "100%",
        placeholder: "Tìm kiếm ngân hàng...",
        "language": {
           "noResults": function(){
               return "Không tìm thấy ngân hàng tìm kiếm.";
           }
       },
        escapeMarkup: function (markup) {
            return markup;
        }
    });
});

	$("body").on('click','.re-sendOTPMap',function(){
		var csrf = $('input[name="csrf_megav_name"]').val();
		$.ajax({
				url: '/payment_epurse/resenOTPMap',
				type: 'POST',
				dataType: 'json',
				data: {check: 1,csrf_megav_name:csrf},
			})
			.done(function(data) {
				if (data.status==true) {
					alert(data.mess);
					$('#trans_id_map').val(data.transId);
				}
			});

		});




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

