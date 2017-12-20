
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a href="#">Giao dịch</a></li>
		<li><a href="#">Thanh toán hóa đơn</a></li>
	</ul>

	<div class="col-md-12 form-center payment-bill-info">
		
			<?php echo form_open('/payment_bill/payment_topup', array('method' => 'post', 'role' => 'form')); ?>
				<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
				<div class="form-group">
			      <label class="col-md-6" for="trand_name">Mã hợp đồng/ Số thẻ tín dụng:</label>
			      <div class="col-md-6">
			        <input type="text" class="form-control bill_info_style" value="<?php echo (isset($info['constructId']) && $info['constructId']!='') ? $info['constructId'] : ''; ?>" id="constructId" disabled />
			        <input type="hidden" class="form-control bill_info_style" value="<?php echo (isset($info['transId']) && $info['transId']!='') ? $info['transId'] : ''; ?>" id="transId">
			      	<span class="error"></span> 
			      </div>
			    </div>
			    <div class="form-group">
			      <label class="col-md-6" for="trand_name">Họ và tên:</label>
			      <div class="col-md-6">
			        <input type="text" class="form-control bill_info_style" value="<?php echo (isset($info['fullname']) && $info['fullname']!='') ? $info['fullname'] : ''; ?>" id="fullname" disabled />
			      	<span class="error"></span> 
			      </div>
			    </div>
			    <div class="form-group">
			      <label class="col-md-6" for="trand_name">Tiền nợ cước (đ):</label>
			      <div class="col-md-6">
			        <input type="text" class="form-control bill_info_style change_currency" value="<?php echo (isset($info['currentPaymentNeed']) && $info['currentPaymentNeed']!='') ? $info['currentPaymentNeed'] : ''; ?>" id="amount" disabled />
			      	<span class="error"></span> 
			      </div>
			    </div>
			    <div class="form-group">
			      <label class="col-md-6" for="trand_name">Tiền thanh toán (đ):</label>
			      <div class="col-md-6">
			        <input type="text" class="form-control bill_info_style change_currency" id="amount_curent">
			      	<span class="error"></span>  
			      </div>
			    </div>
			    <div class="form-group">
			      <label class="col-md-6" for="trand_name"></label>
			      <div class="col-md-6">
			        <p class="exprire_date">Hạn thanh toán: <?php echo (isset($info['currentPaymentExpire']) && $info['currentPaymentExpire']!='') ? $info['currentPaymentExpire'] : ''; ?></p>
			      </div>
			    </div>
			    <div class="append_otp">
			    </div>
			    <div class="form-group"> 
			      <label class="col-md-6"></label>
			      <input type="hidden" class="form-control" id="providerCode" value="<?php echo $post['provider_code']; ?>" />
			      <input type="hidden" class="form-control" id="checkOtpOrPass" value="<?php echo $this->session_memcached->userdata['info_user']['security_method'];?>" />
			      <input type="hidden" class="form-control" id="transIdOtp" value="" />
			      <input type="hidden" class="form-control" id="balance_check" value="<?php echo (isset($balance_check)) ? $balance_check : ''; ?>" />
			      <div class="col-md-6">
			        <button type="button" class="btn btn-success btn_checkOTP">Thanh toán</button> <a href="/payment_bill/start" class="btn btn-default btn_reset">Làm lại</a>
			      </div>
			    </div>
		<?php echo form_close(); ?>
		
	</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('body').on('click','.btn_checkOTP',function(){
	    	var _this = $(this);
	    	var csrf = $('input[name="csrf_megav_name"]').val();
	    	var curent_amunt_check = _this.parents('.payment-bill-info').find('#amount_curent');
	    	if (curent_amunt_check.val() == null || curent_amunt_check.val() == '' || curent_amunt_check.val() == 'undefined') {
	    		curent_amunt_check.parents('.form-group').find('.error').text('Tiền thanh toán không được bỏ trống!');
	    	}else{

	    		if (parseInt($('#balance_check').val()) < parseInt(curent_amunt_check.val().trim().replace(/\,/g, ''))) {
	    			curent_amunt_check.parents('.form-group').find('.error').text('Tiền thanh toán không lớn hơn số dư khả dụng!');
	    		}else{
	    				curent_amunt_check.prop('disabled', true);
	    				curent_amunt_check.val(curent_amunt_check.val().trim());
			    		curent_amunt_check.parents('.form-group').find('.error').text('');

			    		var amount = $('#amount').val();
				    	var amount_curent = $('#amount_curent').val();
				    	var provider_code = $('#providerCode').val();
				    	var status = true;
				    	if (amount == '' || amount == null || amount == 'undefined') {
				    		$('#amount').parents('.col-md-6').find('.error').text('Bạn không được để trống tiền nợ cước.');
				    		status = false;
				    	}else{
				    		$('#amount').parents('.col-md-6').find('.error').text('');
				    	}
				    	if (amount_curent == '' || amount_curent == null || amount_curent == 'undefined') {
				    		$('#amount_curent').parents('.col-md-6').find('.error').text('Bạn không được để trống tiền thanh toán.');
				    		status = false;
				    	}else{
				    		$('#amount_curent').parents('.col-md-6').find('.error').text('');
				    	}
				    	
			    		if (status==true) {
			    			var data = {
						    	'amount' : amount,
						    	'amount_curent' : amount_curent,
						    	'provider_code' : provider_code
				    		}
				    		_this.prop('disabled', true);
					    	$.ajax({
								url: '/payment_bill/senOTPPaymentCheckOut',
								type: 'POST',
								dataType: 'json',
								data: {check: 1,data:data,csrf_megav_name:csrf},
							})
							.done(function(data) {
								if (data.status==true) {
									$('#transIdOtp').val(data.transId);
									var xhtml ='';
									if( data.otp_pass==1){
										xhtml += '<div class="form-group">'+
											      '<label class="col-md-6" for="trand_name">Nhập mã OTP:</label>'+
											      '<div class="col-md-6">'+
											        '<input type="password" autocomplete="off" class="form-control bill_info_style" id="otp_lv" placeholder="Nhập mã xác thực">'+
											      	'<span class="error"></span>' + 
											      '</div>'+
											    '</div>'+
											    '<div class="form-group">'+
											      '<div class="col-md-12 text-center mess_otp">'+
											        'Hệ thống đã gửi OTP tới số điện thoại '+data.phone+'.Không nhận được <a class="resendOtp" href="javascript:void(0)">Click gửi lại</a>'+
											      '</div>'+
											    '</div>';
									}else{
										xhtml += '<div class="form-group">'+
												      '<label class="col-md-6" for="trand_name">Nhập mật khẩu cấp 2:</label>'+
													  '<div class="col-md-6">'+
													    '<input type="password" autocomplete="off" class="form-control bill_info_style" id="pass_lv" placeholder="Mật khẩu cấp 2">'+
													  	'<span class="error"></span>' + 
													  '</div>'+
												    '</div>'+
												    '<div class="form-group">'+
												       '<label class="col-md-6" for="trand_name"></label>'+
												      '<div class="col-md-6 mess_otp reset_pass_how">'+
												        '<a class="resendMk" href="/reset_pass_lv2">Quên mật khẩu cấp 2?</a>'+
												      '</div>'+
												    '</div>';
									}

									$('.append_otp').fadeOut(100, function(){
							            $('.append_otp').html(xhtml).fadeIn();
							        });
									_this.removeClass('btn_checkOTP').addClass('btnConfirmPay').text('Xác nhận thanh toán');
									$('.btnConfirmPay').css('margin-top','10px');
									$('.btn_reset').css('margin-top','10px');
								}else{
									if(data.error==4 || data.error==6){
										$('#amount_curent').parents('.form-group').find('.error').text(data.mess);
									}else if(data.error==5){
										$('#amount').parents('.form-group').find('.error').text(data.mess);
									}
								}
								_this.prop('disabled', false);
							});
			    		}

	    		}



	    		


	    		
	    	}
	    	
				
	    });

		$('body').on('click','.resendOtp',function(){
			var csrf = $('input[name="csrf_megav_name"]').val();
	    	$.ajax({
				url: '/payment_bill/senOTPPaymentCheckOut',
				type: 'POST',
				dataType: 'json',
				data: {check: 1,csrf_megav_name:csrf},
			})
			.done(function(data) {
				if (data.status==true) {
					alert(data.mess);
					$('#transIdOtp').val(data.transId);
				}
			});
				
	    });


	    

	    $('body').on('click','.btnConfirmPay',function(){
	    	var csrf = $('input[name="csrf_megav_name"]').val();
	    	var constructId = $('#constructId').val();
	    	var transId = $('#transId').val();
	    	var fullname = $('#fullname').val();
	    	var amount = $('#amount').val();
	    	var amount_curent = $('#amount_curent').val();
	    	var requestId = $('#transIdOtp').val();
	    	var provider_code = $('#providerCode').val();
	    	var status = true;
	    	if (constructId == '' || constructId == null || constructId == 'undefined') {
	    		$('#constructId').parents('.col-md-6').find('.error').text('Bạn không được để trống Mã hợp đồng/Số thẻ tín dụng.');
	    		status = false;
	    	}else{
	    		$('#constructId').parents('.col-md-6').find('.error').text('');
	    	}
	    	if (fullname == '' || fullname == null || fullname == 'undefined') {
	    		$('#fullname').parents('.col-md-6').find('.error').text('Bạn không được để trống họ tên.');
	    		status = false;
	    	}else{
	    		$('#fullname').parents('.col-md-6').find('.error').text('');
	    	}
	    	if (amount == '' || amount == null || amount == 'undefined') {
	    		$('#amount').parents('.col-md-6').find('.error').text('Bạn không được để trống tiền nợ cước.');
	    		status = false;
	    	}else{
	    		$('#amount').parents('.col-md-6').find('.error').text('');
	    	}
	    	if (amount_curent == '' || amount_curent == null || amount_curent == 'undefined') {
	    		$('#amount_curent').parents('.col-md-6').find('.error').text('Bạn không được để trống tiền thanh toán.');
	    		status = false;
	    	}else{
	    		$('#amount_curent').parents('.col-md-6').find('.error').text('');
	    	}
	    	if ($('#checkOtpOrPass').val() == 1) {
	    		var otp_lv = $('#otp_lv').val();
			    if (otp_lv == '' || otp_lv == null || otp_lv == 'undefined') {
		    			$('#otp_lv').parents('.col-md-6').find('.error').text('Bạn phải nhập OTP.');
		    			status = false;
		    	}else{
		    		$('#otp_lv').parents('.col-md-6').find('.error').text('');
		    	}
			}
	    	if ($('#checkOtpOrPass').val() == 2) {
	    		var pass_lv = $('#pass_lv').val();
	    		if (pass_lv == '' || pass_lv == null || pass_lv == 'undefined') {
		    			$('#pass_lv').parents('.col-md-6').find('.error').text('Bạn phải nhập mật khẩu cấp 2.');
		    			status = false;		    		
		    	}else{
		    		$('#pass_lv').parents('.col-md-6').find('.error').text('');
		    	}
	    	}
	    	
	    	if (status==true) {
	    		var _this = $(this);
	    		_this.prop('disabled', true);
	    		_this.text('Đang xử lý...');
	    		var data = {
	    			'constructId' : constructId,
			    	'fullname' : fullname,
			    	'amount' : amount,
			    	'amount_curent' : amount_curent,
			    	'transId' : transId,
			    	'requestId' : requestId,
			    	'provider_code' : provider_code,
			    	'check' : 0
	    		}
	    		if ($('#checkOtpOrPass').val()==1) {
	    			data.otp_lv = otp_lv;
	    			data.check = 1;
	    		}
	    		if($('#checkOtpOrPass').val()==2){
	    			data.pass_lv = pass_lv;
	    			data.check = 2;
	    		}

	    		$.ajax({
					url: '/payment_bill/reqPaymentCheckOut',
					type: 'POST',
					dataType: 'json',
					data: {data: data,csrf_megav_name:csrf},
				})
				.done(function(data) {
					_this.prop('disabled', false);
					_this.text('Xác nhận thanh toán');
					if (data.status==1) {
						window.parent.document.getElementsByClassName("balance")[0].innerHTML = data.balance + ' đ';
						var html = '<div class="text-center">'+
										'<p><img style="width: 50px;" src="<?php echo base_url()."images/success.png";?>" /></p>'+
								    	'<p>'+data.mess+'</p>'+
								        '<a class="btn btn-success button-main" href="<?php echo base_url("payment_bill/start"); ?>" style="color:#333;">Về trang thanh toán</a>&nbsp;<a class="btn btn-default back-trans-history" href="<?php echo base_url() . "trans_history/index/paymentbills/"; ?>'+data.referenceId+'" class="button button-sub button180">Xem lịch sử giao dịch</a>'+
								    '</div>';
						_this.parents('.form-center').html(html);
					}else if(data.status==9){
						var html = '<div class="text-center">'+
										'<p><img style="width: 50px;" src="<?php echo base_url()."images/success.png";?>" /></p>'+
								    	'<p>'+data.mess+'</p>'+
								        '<a class="btn btn-success button-main" href="<?php echo base_url("payment_bill/start"); ?>" style="color:#333;">Về trang thanh toán</a>&nbsp;<a class="btn btn-default back-trans-history" href="<?php echo base_url() . "trans_history/index/paymentbills/"; ?>'+data.referenceId+'" class="button button-sub button180">Xem lịch sử giao dịch</a>'+
								    '</div>';
						_this.parents('.form-center').html(html);
					}else if(data.status==2){
						$('#pass_lv').parents('.form-group').find('.error').text(data.mess);
					}else if(data.status==3){
						$('#otp_lv').parents('.form-group').find('.error').text(data.mess);
					}else if(data.status==4 || data.status==6){
						$('#amount_curent').parents('.form-group').find('.error').text(data.mess);
					}else if(data.status==5){
						$('#amount').parents('.form-group').find('.error').text(data.mess);
					}else if(data.status==0){
						var html = '<div class="text-center">'+
								    	'<p>'+data.mess+'</p>'+
								        '<a class="btn btn-success button-main" href="<?php echo base_url("payment_bill/start"); ?>" style="color:#333;">Về trang thanh toán</a>&nbsp;<a class="btn btn-default back-trans-history" href="<?php echo base_url() . "trans_history/index/paymentbills"; ?>" class="button button-sub button180">Xem lịch sử giao dịch</a>'+
								    '</div>';
						_this.parents('.form-center').html(html);
					}
					
				});
	    	}
	    });


	$('body').on('click','.back-trans-history', function(event) {
		$('body', parent.document).removeClass("bg-trans");
		$("#side-menu > li > a", parent.document).removeClass("active");
		$('.trans-history', parent.document).addClass("active");
		$("li.transaction > span > a", parent.document).css("color","");
	});

	/*$('body').on('keyup change','.payment-bill-info #amount_curent',function() {
	   format_curency(this);
	});
	$('body').on('keyup change','.payment-bill-info #amount',function() {
	   format_curency(this);
	});

    function format_curency(a) {
    	a.value = a.value.replace(/\,/g, '');
		a.value = a.value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
	}*/

	});
</script>
