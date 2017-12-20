$(function () {
	
	$('#resendOtp').click(function(){
		var csrf = $('input[name="csrf_megav_name"]').val();    
		$('#loader-bg').css('display', 'block');
		
		$.post( "/resend_otp", 
			{
				resendOtp: true,
				csrf_megav_name:csrf
			},
			function(data, status) {
				if(status == 'success'){
					var array = data.split(',');
					//console.log(array);
					if(array[0] == '00' || array[0] == '32'){
						alert(array[1]);
					}
					else
					{
						alert("Không thể gửi lại OTP.");
					}
				}
				$('#loader-bg').css('display', 'none');
		});
    });
	
	$("#pass_sec").click(function(){
		$( ".security_info" ).css('display', 'block');
		$( ".phone_info" ).css('display', 'none');
	});
	
	$("#phone_sec").click(function(){
		$( ".security_info" ).css('display', 'none');
		$( ".phone_info" ).css('display', 'block');
	});
	
	$("#closemodal").click(function(){
		$( "#myModal" ).css('display', 'none');
		$( ".modal-backdrop" ).removeClass("modal-backdrop");
	});
	
	
	// veryfi mobile first time
	$('#sendOtpVerifyMobile').click(function(){
		var csrf = $('input[name="csrf_megav_name"]').val();    
		$('#loader-bg').css('display', 'block');
		
		$.post( "/page/index/sendOtp", 
			{
				resendOtp: true,
				csrf_megav_name:csrf
			},
			function(data, status) {
				if(status == 'success'){
					if(data != null)
					{
						var array = data.split(',');
						
						//alert(array[1]);
						if(array[0] == '00'){
							$('#btn-sendotp').css('display', 'none');
							$('#otp-form').css('display', 'block');
						}
					}
				}
				$('#loader-bg').css('display', 'none');
		});
    });
	
	/*
	$('#btn-login').click(function(){
		$('.form-login').css('display', '');
		$('.menu-nav').css('display', 'none');
		$('#loader-bg').css('display', 'block');
	});
	*/
	var ua = navigator.userAgent, 
	pickclick = (ua.match(/iPad/i) || ua.match(/iPhone/)) ? "touchstart" : "click";

	$('body').on(pickclick,'#btn-login',function(){
		//$('.form-login').css('display', '');
		$('.form-login').css('visibility','visible').hide().slideDown('slow');
		$('.form-login').fadeIn('slow');
		
		$('.form-register').css('display', 'none');
		$('#loader-bg').css('display', 'block');
	});

	$('body').on(pickclick,'#btn-register',function(){
		//$('.form-register').css('display', '');
		//$('.form-register').css('right', '144px');
		
		$('.form-register').slideDown('slow');
		$('.form-register').fadeIn('slow');
		
		//$('.menu-nav').css('display', 'none');
		$('#loader-bg').css('display', 'block');
	});
	
	

	$('body').on(pickclick,'#loader-bg',function(){
	//$('#loader-bg').click(function(){
		//$('.form-login').css('display', 'none');
		//$('.form-register').css('display', 'none');
		
		$('.form-register, .form-login').slideUp({duration: 'fast', queue: false});
		//$('.form-register, .form-login').fadeOut({duration: 'slow', queue: false});
		
		$('#loader-bg').css('display', 'none');
	});
	
	$("#amountPayment").blur(function(){
		var csrf = $('input[name="csrf_megav_name"]').val();
		var amount = $('#amountPayment').val();
		var bank_code = $('#providerCode').val();
		if(bank_code.length != 0)
		{
			
			if(amount.length != 0)
			{
				 $.post("/payment_epurse/get_fee_payment",
					{
						amount: amount,
						bank_code: bank_code,
						csrf_megav_name:csrf
					},
					function(data, status){
						if(status == 'success')
						{
							var array = data.split('|');
							//console.log(array);
							if(array.length == 2)
							{
								$("#feePayment").html(array[0]);
								$("#realAmount").html(array[1]);
							}
						}
					});
			}
		}
	});
	$("#amountPaymentMap").blur(function(){
		var csrf = $('input[name="csrf_megav_name"]').val();
		var amount = $('#amountPaymentMap').val();
		var bank_code = $('#bank_code_map').val();
		if(bank_code.length != 0)
		{
			
			if(amount.length != 0)
			{
				 $.post("/payment_epurse/get_fee_payment_mapping",
					{
						amount: amount,
						bank_code: bank_code,
						csrf_megav_name:csrf
					},
					function(data, status){
						if(status == 'success')
						{
							console.log(data);
							var array = data.split('|');
							console.log(array);
							if(array.length == 2)
							{
								$("#feePaymentMap2").val(array[0]);
								$("#feePaymentMap").html(array[0]);
								$("#realAmountMap").html(array[1]);
							}
						}
					});
			}
		}
	});
	
	$("#providerCode").change(function(){
		var csrf = $('input[name="csrf_megav_name"]').val();
		var amount = $('#amountPayment').val();
		var bank_code = $('#providerCode').val();
		if(bank_code.length != 0)
		{
			if(amount != 0)
			{
				 $.post("/payment_epurse/get_fee_payment",
					{
						amount: amount,
						bank_code: bank_code,
						csrf_megav_name:csrf
					},
					function(data, status){
						if(status == 'success')
						{
							var array = data.split('|');
							console.log(array);
							if(array.length == 2)
							{
								$("#feePayment").html(array[0]);
								$("#realAmount").html(array[1]);
							}
						}
					});
			}
		}
	});
	
	$("#amountTransfer").blur(function(){
		var csrf = $('input[name="csrf_megav_name"]').val();
		var amount = $('#amountTransfer').val();
		var payfee = $('input[name="pay_fee"]:checked').val();
		
		if(amount.length != 0)
		{
			$.post("/transfer/get_fee_transfer",
			{
				amount: amount,
				payfee: payfee,
				csrf_megav_name:csrf
			},
			function(data, status){
				if(status == 'success')
				{
					var array = data.split('|');
					console.log(array);
					if(array.length == 2)
					{
						$("#feeTransfer").html(array[0]);
						$("#realAmount").html(array[1]);
					}
				}
			});
		}
		
	});
	

	$('input.checkbox_payfee').click(function(){
		var csrf = $('input[name="csrf_megav_name"]').val();
		var amount = $('#amountTransfer').val();
		var payfee = $('input[name="pay_fee"]:checked').val();
		
		if(amount.length != 0)
		{
			$.post("/transfer/get_fee_transfer",
			{
				amount: amount,
				payfee: payfee,
				csrf_megav_name:csrf
			},
			function(data, status){
				if(status == 'success')
				{
					var array = data.split('|');
					console.log(array);
					if(array.length == 2)
					{
						$("#feeTransfer").html(array[0]);
						$("#realAmount").html(array[1]);
					}
				}
			});
		}
	});
	
	$("#withdrawAmount").blur(function(){
		var csrf = $('input[name="csrf_megav_name"]').val();
		var amount = $('#withdrawAmount').val();
		var withdraw_met = $('#widthdraw_met').val();
		var providerCode = $('#providerCode').val();
		if(amount.length != 0)
		{
			$.post("/withdraw/get_fee_widthdraw",
			{
				amount: amount,
				withdraw_met: withdraw_met,
				providerCode: providerCode,
				csrf_megav_name:csrf
			},
			function(data, status){
				if(status == 'success')
				{
					$("#feeWidthdraw").html(data);
					/*
					var array = data.split('|');
					console.log(array);
					if(array.length == 2)
					{
						
					}
					*/
				}
			});
		}
		
	});
	
	$("#buyCardQuantity").blur(function(){
		var csrf = $('input[name="csrf_megav_name"]').val();
		var amount = $('#buyCardAmount').val().replace(/\$|\,/g, "");
		var quantity = $('#buyCardQuantity').val().replace(/\$|\,/g, "");
		var providercode = $('#providerTopup').val();
		if(quantity.length != 0)
		{
			if(amount.length != 0)
			{
				if(providercode.length != 0)
				{
					$.post("/buy_card/get_commission",
					{
						amount: amount,
						quantity : quantity,
						providercode : providercode,
						csrf_megav_name:csrf
					},
					function(data, status){
						if(status == 'success')
						{
							$("#totalAmount").html(data);
						}
					});
				}
			}
		}
	});
	
	$("#buyCardAmount").change(function(){
		var csrf = $('input[name="csrf_megav_name"]').val();
		var amount = $('#buyCardAmount').val().replace(/\$|\,/g, "");
		var quantity = $('#buyCardQuantity').val().replace(/\$|\,/g, "");
		var providercode = $('#providerTopup').val();
		if(quantity.length != 0)
		{
			if(amount.length != 0)
			{
				if(providercode.length != 0)
				{
					$.post("/buy_card/get_commission",
					{
						amount: amount,
						quantity : quantity,
						providercode : providercode,
						csrf_megav_name:csrf
					},
					function(data, status){
						if(status == 'success')
						{
							$("#totalAmount").html(data);
						}
					});
				}
			}
		}
	});
	
	
	$("#providerTopup").change(function(){
		var csrf = $('input[name="csrf_megav_name"]').val();
		var providercode = $('#providerTopup').val();
		if(providercode.length != 0)
		{
			$.post("/buy_card/getAmountWithProvider",
			{
				providercode: providercode,
				csrf_megav_name:csrf
			},
			function(data, status){
				if(status == 'success')
				{
					if(data != 'false')
					{
						$('#buyCardAmount').html('');
						$('#buyCardAmount').html(data);
					}
				}
			});
		}
		else
		{
			$('#buyCardAmount').html('');
			$('#buyCardAmount').html('<option value="">Chưa có thông tin nhà cung cấp thẻ</option>');
		}
	});
	
	$("#providerTopupToGame").change(function(){
		var csrf = $('input[name="csrf_megav_name"]').val();
		var providercode = $('#providerTopupToGame').val();
		if(providercode.length != 0)
		{
			$.post("/payment_game/getAmountWithProvider",
			{
				providercode: providercode,
				csrf_megav_name:csrf
			},
			function(data, status){
				if(status == 'success')
				{
					if(data != 'false')
					{
						$('#topupAmountToGame').html('');
						$('#topupAmountToGame').html(data);
						$('#totalAmount').html('0');
					}
				}
			});
		}
		else
		{
			$('#topupAmountToGame').html('');
			$('#topupAmountToGame').html('<option value="">Chưa có thông tin nhà cung cấp thẻ</option>');
		}
	});
	
	
	$("#topupAmountToGame").change(function(){
		var csrf = $('input[name="csrf_megav_name"]').val();
		var amount = $('#topupAmountToGame').val();
		var providerCode = $('#providerTopupToGame').val();
		if(providerCode.length != 0)
		{
			if(amount.length != 0)
			{
				 $.post("/payment_phone/getDiscountAmount",
					{
						amount: amount,
						providerCode: providerCode,
						csrf_megav_name:csrf
					},
					function(data, status){
						if(status == 'success')
						{
							if(data.length != 0)
							{
								$("#totalAmount").html(data);
							}
						}
					});
			}
		}
	});
	
	$("#phone").blur(function(){
		var csrf = $('input[name="csrf_megav_name"]').val();
		var phone = $('#phone').val();
		if(phone.length != 0)
		{
			$.post("/payment_game/get_provider_code",
			{
				phone: phone,
				csrf_megav_name:csrf
			},
			function(data, status){
				if(status == 'success')
				{
					if(data != 'false')
					{
						$('#providerCDVToPhone option').attr('disabled', 'disabled');
						$('#providerCDVToPhone option').removeAttr('selected').filter('[value=' + data + ']').removeAttr('disabled').attr('selected', true);
						$("#providerCDVToPhone").val(data).change();
					}
				}
			});
			
		}
	});
	
	
	$("#phone_type").change(function(){
		var phone = $('#phone').val();
		if(phone.length == 0) {
			$('.form-error-phone').html('');
			$('.form-error-phone').html('Vui lòng nhập số điện thoại trước');
			$("#phone_type").val('').change();
			$("#providerCDVToPhone").val('').change();
		}
	});
	
	$("#providerCDVToPhone").change(function(){
		var csrf = $('input[name="csrf_megav_name"]').val();
		var phone = $('#phone').val();
		if(phone.length != 0) {
			var providercode = $('#providerCDVToPhone').val();
			if(providercode.length != 0) {
				$.post("/payment_game/getAmountWithProvider",
				{
					providercode: providercode,
					csrf_megav_name:csrf
				},
				function(data, status){
					if(status == 'success')
					{
						if(data != 'false')
						{
							$('#topupAmountToPhone').html('');
							$('#topupAmountToPhone').html(data);
							$('#totalAmount').html('0');
						}
					}
				});
			} else {
				$('#topupAmountToPhone').html('');
				$('#topupAmountToPhone').html('<option value="">Chưa có thông tin nhà cung cấp thẻ</option>');
				$('#totalAmount').html('0');
			}
		} else {
			$('.form-error-phone').html('');
			$('.form-error-phone').html('Vui lòng nhập số điện thoại trước');
			$("#providerCDVToPhone").val('').change();
			$("#phone_type").val('').change();
		}
	});
	
	$("#topupAmountToPhone").change(function(){
		var csrf = $('input[name="csrf_megav_name"]').val();
		var amount = $('#topupAmountToPhone').val();
		var providerCode = $('#providerCDVToPhone').val();
		if(providerCode.length != 0)
		{
			if(amount.length != 0)
			{
				 $.post("/payment_phone/getDiscountAmount",
					{
						amount: amount,
						providerCode: providerCode,
						csrf_megav_name:csrf
					},
					function(data, status){
						if(status == 'success')
						{
							if(data.length != 0)
							{
								$("#totalAmount").html(data);
							}
						}
					});
			}
		}
	});
	
	$("#paymentType").change(function(){
		var paymentType = $('#paymentType').val();
		if(paymentType == '1') {
			$(".provider").css('display', '');
		} else if(paymentType == '2') {
			$(".provider").css('display', 'none');
			var title = '99031';
			$('#providerCode').find("option").filter(function() {
				return $(this).val() == title;
			}).prop('selected', true);
		}
	});
	
	$('input[type=submit]').click(function() {
		var inputName = $(this).attr('name');
		$(this).parents('form').append('<input type="hidden" name="' + inputName + '">');
		$(this).attr('disabled', true);
		$('form input[type=submit]').attr('disabled', true);
		$(this).parents('form').submit();
		$(this).css('cursor','not-allowed');
	})
	
	
	$('#widthdraw_met').change(function() {
		var csrf = $('input[name="csrf_megav_name"]').val();
		var widthdraw_met = $('#widthdraw_met').val();
		if(widthdraw_met.length != 0)
		{
			$.post("/withdraw/get_list_bank_for_withdraw_method",
			{
				widthdraw_met: widthdraw_met,
				csrf_megav_name:csrf
			},
			function(data, status){
				if(status == 'success')
				{
					if(data.length != 0)
					{
						$("#providerCode").html(data);
					}
				}
			});
			
			if(widthdraw_met == '1')
			{
				$('#bank_acc').css('display', 'block');
				$('#bank_acc label').text('Số tài khoản');
				$('#bank_acc select').html('<option value="">Chọn tài khoản</option>');
				$('#add_bank_acc a').css('display', 'block');
				$('#add_bank_acc_firm a').css('display', 'none');
				$('#add_bank_map a').css('display', 'none');
				$('#feewithdraw').css('display', 'inline-block');
				$('#feewithdrawfast').css('display', 'none');
				$('#feeWidthdraw').html('0');
				$('#withdrawAmount').val('');
			}
			else if(widthdraw_met == '2')
			{
				$('#bank_acc').css('display', 'block');
				$('#bank_acc label').text('Số thẻ');
				$('#bank_acc select').html('<option value="">Chọn số thẻ</option>');
				$('#add_bank_acc a').css('display', 'none');
				$('#add_bank_acc_firm a').css('display', 'block');
				$('#add_bank_map a').css('display', 'none');
				$('#feewithdraw').css('display', 'none');
				$('#feewithdrawfast').css('display', 'inline-block');
				$('#feeWidthdraw').html('0');
				$('#withdrawAmount').val('');
			}
			else if(widthdraw_met == '3')
			{
				$('#bank_acc').css('display', 'none');
				$('#bank_acc label').text('Số tài khoản');
				$('#bank_acc select').html('<option value="">Chọn tài khoản</option>');
				$('#add_bank_acc a').css('display', 'none');
				$('#add_bank_acc_firm a').css('display', 'none');
				$('#add_bank_map a').css('display', 'block');
				$('#feewithdraw').css('display', 'none');
				$('#feewithdrawfast').css('display', 'none');
				$('#feeWidthdraw').html('0');
				$('#withdrawAmount').val('');
			}
		}
	});
	
	$(".check_card ").change(function(){
		var target = $(this).find('option:selected').data('target');
		
		if(target == "0"){
			$(".firmCard").html('Tên chủ tài khoản');
			$(".firmCardNumb").html('Số tài khoản');
		} else {
			$(".firmCard").html('Tên chủ thẻ');
			$(".firmCardNumb").html('Số thẻ');
		}
		
	});
	
});

function regetCaptcha() {
    var timestamp = new Date().getTime();
    $("#captcha_image").attr('src', '../register/security_code/' + timestamp);
}
function ComfirmNo() {
    var a = location.protocol + '//' + location.host;
    location.href = a;
}

function goback() {
    history.back(-1)
}

function toogle_info(id) {
    var a = ['group-fone', 'group-fullname', 'group-address','group-email','group-username','group-idNo','group-birthday'];
    a.splice(a.indexOf(id), 1);
    var e = document.getElementById(id);
    if (e.style.display == 'block')
        e.style.display = 'none';
    else{
        e.style.display = 'block';
        for(var i=1; i<6;i++)
        {
           var ev = document.getElementById(a[i]);
            ev.style.display='none';
        }
    }
}

/*
$(document).ready(function($) {
		var url = window.location;
		var str_url = url.toString();	
		var element = $('ul.nav-second-level a').filter(function() {
			var href =  this.href;
			if(str_url.indexOf(href) != -1){
				return true;
			}
		}).addClass('color_txt').parent();

		while(true){
			if (element.is('li')){
				element = element.parent().addClass('menu-open');
				element = element.css("display", "block").parent();
				element = element.addClass('active');
			} else {
				break;
			}
		}
	});
*/
$(document).ready(function () {

    $('#side-menu').metisMenu();

});


$(document).ready(function () {
	/*
    $(window).bind("load resize", function() {
        var topOffset = 50;
        var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        var height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });
	*/
    var url = window.location;
    var element = $('ul#side-menu a').filter(function() {
     return this.href == url;
    }).addClass('active').parent();

    while(true){
        if (element.is('li')){
			//console.log(element);
			
			
				element = element.parent().addClass('in').parent();
				element = element.addClass('active');

				$("#wrapper").addClass('toggled');
			

            
			
        } else {
            break;
        }
    }
});

	
$(document).ready(function () {
	var deviceWidth = viewport();
	if (deviceWidth.width <= '768') {
		$("#wrapper").removeClass('toggled');
	}
	
	$('.slidel-f-page').slick({
		infinite: true,
		slidesToShow: 4,
		slidesToScroll: 1,
		speed: 300,
		autoplay: true,
		autoplaySpeed: 2000,
		arrows: false,
		responsive: [
		{
		  breakpoint: 1200,
		  settings: {
			slidesToShow: 3,
			slidesToScroll: 1,
			infinite: true,
		  }
		},
		{
		  breakpoint: 1000,
		  settings: {
			slidesToShow: 2,
			slidesToScroll: 1
		  }
		},
		{
		  breakpoint: 590,
		  settings: {
			slidesToShow: 1,
			slidesToScroll: 1
		  }
		}
		]
	});
	
	
    $("#flexiselimg1").flexisel({
        visibleItems: 7,
        animationSpeed: 500,
        autoPlay: true,
        autoPlaySpeed: 3000,
        pauseOnHover: true,
        enableResponsiveBreakpoints: true,
        responsiveBreakpoints: {
            portrait: {
                changePoint:480,
                visibleItems: 1
            },
            landscape: {
                changePoint:640,
                visibleItems: 2
            },
            tablet: {
                changePoint:768,
                visibleItems: 3
            }
        }
    });
	
	$("#flexiselimg2").flexisel({
        visibleItems: 7,
        animationSpeed: 500,
        autoPlay: true,
        autoPlaySpeed: 3000,
        pauseOnHover: true,
        enableResponsiveBreakpoints: true,
        responsiveBreakpoints: {
            portrait: {
                changePoint:480,
                visibleItems: 1
            },
            landscape: {
                changePoint:640,
                visibleItems: 2
            },
            tablet: {
                changePoint:768,
                visibleItems: 3
            }
        }
    });
	
	
	
    $("#bday").change(function () {
        var date1 = $("#bday").val();
        var month1 = $("#select_month").val();
        var year1 = $("#year").val();
        if ($("#bday").val() > 31 || $("#bday").val() < 1) {
            alert("Thông tin ngày sinh không hợp lệ. Vui lòng kiểm tra lại.");
            $("#bday").val('');
            return false;
        } else if (month1 == '04' || month1 == '06' || month1 == '09' || month1 == '11')//Ngày sinh các tháng có 30 ngày.
        {
            if ($("#bday").val() > 30) {
                alert("Thông tin ngày sinh không hợp lệ. Vui lòng kiểm tra lại.");
                $("#bday").val('');
                return false;
            }
        }

        else if (month1 == '02')//Tháng 2 năm nhuận.
        {
            if (date1 > 29) {
                alert("Thông tin ngày sinh không hợp lệ. Vui lòng kiểm tra lại.");
                $("#bday").val('');
                return false;
            }
            else if (year1 % 4 == 0 && year1 != '') {
                if (date1 > 29) {
                    alert("Thông tin ngày sinh không hợp lệ. Vui lòng kiểm tra lại.");
                    $("#bday").val('');
                    return false;
                }
            }
            else {
                if (date1 > 28) {
                    alert("Thông tin ngày sinh không hợp lệ. Vui lòng kiểm tra lại.");
                    $("#bday").val('');
                    return false;
                }
            }
        }

    });
    $("#select_month").change(function () {
        var date1 = $("#bday").val();
        var month1 = $("#select_month").val();
        var year1 = $("#year").val();
        //Kiểm tra khi đã nhập ngày tháng.
        //alert(month1);
        if (month1.length == 0) {
            alert("Thông tin tháng sinh không hợp lệ. Vui lòng kiểm tra lại.");
            $("#select_month").val('');
            return false;
        }
        else if (month1 == '04' || month1 == '06' || month1 == '09' || month1 == '11')//Ngày sinh các tháng có 30 ngày.
        {
            if ($("#bday").val() > 30) {
                alert("Thông tin ngày sinh không hợp lệ. Vui lòng kiểm tra lại.");
                $("#bday").val('');
                $("#select_month").val('');
                return false;
            }
        }
        else if (month1 == '02')//Tháng 2 năm nhuận.
        {
            if (date1 > 29) {
                alert("Thông tin ngày sinh không hợp lệ. Vui lòng kiểm tra lại.");
                $("#bday").val('');
                return false;
            }
            else if ((year1 != null || year1 != '') && year1 % 4 == 0) {
                if (date1 > 29) {
                    alert("Thông tin ngày sinh không hợp lệ. Vui lòng kiểm tra lại.");
                    $("#bday").val('');
                    return false;
                }
            }
            else {
                if (date1 > 28) {
                    alert("Thông tin ngày sinh không hợp lệ. Vui lòng kiểm tra lại.");
                    $("#bday").val('');
                    return false;
                }
            }
        }

    });
    $("#year").change(function () {
        var now = new Date();
        var date1 = $("#bday").val();
        var month1 = $("#select_month").val();
        var year1 = $("#year").val();
        if ($("#year").val() > now.getFullYear()) {
            alert("Thông tin năm sinh không hợp lệ. Vui lòng kiểm tra lại.");
            $("#year").val('');
            return false;
        }
        else if ($("#year").val() < 1900) {
            alert("Thông tin năm sinh không hợp lệ. Vui lòng kiểm tra lại.");
            $("#year").val('');
            return false;
        }
//Kiểm tra khi đã nhập ngày tháng.
        else if (month1 == '02' && year1 != '')//Tháng 2 năm nhuận.
        {
            if (year1 % 4 == 0) {
                if (date1 > 29) {
                    alert("Thông tin ngày sinh không hợp lệ. Vui lòng kiểm tra lại.");
                    $("#bday").val('');
                    return false;
                }
            }
            else {
                if (date1 > 28) {
                    alert("Thông tin ngày sinh không hợp lệ. Vui lòng kiểm tra lại.");
                    $("#bday").val('');
                    return false;
                }
            }
        }
    });
		
});



$(document).ready(function(){


	// close menu left responsive
	$('body').on('click','.close_left_menu',function(){
		//alert(123);
		$('#wrapper').removeClass('toggled');
	});

	$('body').on('click','.back_acc_manage',function(e){

		$("body").removeClass('bg-trans');
		$("li.transaction > span > a").css("color","");
		var path = location.pathname;
		//console.log(path);
		var url = '/page/index/getAccPage';
		/*switch(path){
			case '/transaction_manage' : url = "/page/index/getTransPage";
			break;
			case '/acc_manage' : url = "/page/index/getAccPage";
			break;
			default: url = "/page/index/getTransPage";
			break;
		}*/
		
			var csrf = $('input[name="csrf_megav_name"]').val();
			$.ajax({
				url: url,
				type: 'GET',
				dataType : 'json',
				data: {csrf_megav_name:csrf},  
			}).done(function(data) {
				
				//$("#mg-content").toggleClass("toggled");
				//$('.close').css('display', 'block');
				$('#wrapper', parent.document).find("#lg-content").html();

				$('#wrapper', parent.document).find("#mg-content").html();
				$('#wrapper', parent.document).find("#mg-content").html(data.html);
								
				jQuery.each($('#wrapper', parent.document).find("#mg-content").attr("class").split(' '), function(index, item) {
					if(item == 'toggled'){
						$('#wrapper', parent.document).find("#mg-content").removeClass('toggled');
					}
				});
				
			});
		
		
		
		
		//$("#mg-content").toggleClass("toggled");
		
		//setTimeout(function () { $('#wrapper', parent.document).find("#mg-content").toggleClass("toggled"); }, 500);
		$('#wrapper', parent.document).find('.close').css('display', 'none');
	});



	$('.collapsed').each(function(i, obj) {
		var href = $(obj).attr('href');
		var classE = href.replace("#", ".");
		$(href).on("shown.bs.collapse", function(){
			$(classE).removeClass('fa-angle-up');
			$(classE).addClass('fa-angle-down');
		});
		$(href).on("hidden.bs.collapse", function(){
			$(classE).removeClass('fa-angle-down');
			$(classE).addClass('fa-angle-up');
		});
		
	});
	
	$("#menu-toggle").click(function(e) {
        e.preventDefault();
		
		$("#wrapper").removeAttr('style');
		$("#sidebar-wrapper").removeAttr('style');
		
		/*
        $("#wrapper").toggleClass("toggled").promise().done(function(){
			setTimeout(changeHeight, 350);
		});
		*/
		
		 $("#wrapper").toggleClass("toggled");
		 
		 if(!$("#wrapper").hasClass("toggled")) {
			 $("#menu-toggle").addClass('navbar-home-hover');
		 }else{
			 $("#menu-toggle").removeClass('navbar-home-hover');
		 }
		
    });
	

	$("input.checkSpace").on({
	  keydown: function(e) {
		if (e.which === 32)
		  return false;
	  },
	  change: function() {
		this.value = this.value.replace(/\s/g, "");
	  }
	});

});

function changeHeight()
{
	var width = $('.accinfo').width();
	$('.acc-item').animate({height:width},500);
}


$('body').on('change','.change_currency',function(){
	var csrf = $('input[name="csrf_megav_name"]').val();
	var _this = $(this);
	var number =  _this.val();
	number = number.trim().replace(/-/g, '');
	number = parseInt(number.replace(/,/g, ''));
	if (isNaN(number))
		_this.val('');
	if (number!='' && !isNaN(number)) {
		$.ajax({
			url: '/acc_manage/currentFormat',
			type: 'POST',
			dataType: 'json',
			data: {csrf_megav_name:csrf,number: number},
		})
		.done(function(data) {
			_this.val(data.number);
			_this.siblings(".form-error").html('');
			if(data.status == false){
				_this.siblings(".form-error").html(data.mess);
				$("body").find('input[type=submit]').attr('disabled', true).css('cursor','not-allowed');
			} else {
				$("body").find('input[type=submit]').attr('disabled', false).css('cursor','');
			}
		});
	}
});

function formatCurrency(input, num) {
			num = num.replace(/,/g, '');
			num = num.toString().replace(/\$|\,/g, '');
			if (isNaN(num))
				num = "";
			sign = (num == (num = Math.abs(num)));
			num = Math.floor(num * 100 + 0.50000000001);
			num = Math.floor(num / 100).toString();
			for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
				num = num.substring(0, num.length - (4 * i + 3)) + ',' +
					num.substring(num.length - (4 * i + 3));
			if (num == "0") num = "";
			new_value = (((sign) ? '' : '-') + num);
			input.value = new_value;
}

function formatPhone(input, num) {
	num = num.toString().replace(/\$|\,/g, '');
	
	if (num.match(/^0/)) {
		tddot = "0";
	} else {
		tddot = "";
	}
	
	if (isNaN(num))
		num = "";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num * 100 + 0.50000000001);
	num = Math.floor(num / 100).toString();
	if (num == "0") num = "";
	new_value = tddot + (((sign) ? '' : '-') + num);
	input.value = new_value;
}

function formatNumber(num) {

		num = num.toString().replace(/\$|\,/g, '');
		if (isNaN(num))
			num = "";
		sign = (num == (num = Math.abs(num)));
		num = Math.floor(num * 100 + 0.50000000001);
		num = Math.floor(num / 100).toString();
		for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
			num = num.substring(0, num.length - (4 * i + 3)) + ',' +
				num.substring(num.length - (4 * i + 3));
		if (num == "0") num = "";
		new_value = (((sign) ? '' : '-') + num);
		return new_value;
}
	
// Replace phone 84 to 0

$("body").on("change","#register input[name='fone']",function(e){
	var res = checkPhoneRegexReplace($(this).val());
	if (res!='') {
		$(this).val(res);
	}
});

$("body").on("change","input[name='newphone'], input[name='phone']",function(e){
	var res = checkPhoneRegexReplace($(this).val());
	if (res!='') {
		$(this).val(res);
	}
});


function checkPhoneRegexReplace(phoneNo) {
  var phoneRE = /^84/; 
  if (phoneNo.match(phoneRE)) {
  	var res = phoneNo.replace(phoneRE, '0');
    return res; 

  } else {
    return false;
  }
}

/*if (deviceWidth.width<='768' && deviceWidth.width > '480') {
	alert('nhỏ hơn hoặc bằng 768px');
}else if (deviceWidth.width<='480') {
	alert('nhỏ hơn hoặc bằng 480px');
}else{
	alert('lớn hơn 768px');
}*/
function viewport() {
    var e = window, a = 'inner';
    if (!('innerWidth' in window )) {
        a = 'client';
        e = document.documentElement || document.body;
    }
    return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
}
if( /iPad|iPhone|iPod|Android/i.test(navigator.userAgent) ){
    window.addEventListener("orientationchange", function() { 
        changeViewPort("maximum-scale", 1);
        changeViewPort("maximum-scale", 10);
    })
}

function changeViewPort(key, val) {
    var reg = new RegExp(key, "i"), oldval = document.querySelector('meta[name="viewport"]').content;
    var newval = reg.test(oldval) ? oldval.split(/,\s*/).map(function(v){ return reg.test(v) ? key+"="+val : v; }).join(", ") : oldval+= ", "+key+"="+val ;
    document.querySelector('meta[name="viewport"]').content = newval;
}

$('body').on('keydown','.name-unicode',function(e){
	var str = $(this).val();
	if (str.length > 0) {
		if (checkUnicode(str)==true) {
			//$(this).parents('.form-center').find('input[type=submit]').attr('disabled', true).removeClass('cursor-not-allowed').addClass('cursorOverride');
			$(this).parents('.div-input').find('.form-error').text('');
		}else{
			//delete
		    var removedChar = str.substring(str.length-1);
		    //console.log(removedChar); 
		    var code_A = ['Á','À','Ả','Ã','Ạ','Ă','Ắ','Ặ','Ằ','Ẳ','Ẵ','Â','Ấ','Ầ','Ẩ','Ẫ','Ậ'];
		    var code_a = ['á','à','ả','ã','ạ','ă','ắ','ặ','ằ','ẳ','ẵ','â','ấ','ầ','ẩ','ẫ','ậ'];
		    var code_D = ['Đ'];
		    var code_d = ['đ'];
		    var code_E = ['É','È','Ẻ','Ẽ','Ẹ','Ê','Ế','Ề','Ể','Ễ','Ệ'];
		    var code_e = ['é','è','ẻ','ẽ','ẹ','ê','ế','ề','ể','ễ','ệ'];
		    var code_I = ['Í','Ì','Ỉ','Ĩ','Ị'];
		    var code_i = ['í','ì','ỉ','ĩ','ị'];
		    var code_O = ['Ó','Ò','Ỏ','Õ','Ọ','Ô','Ố','Ồ','Ổ','Ỗ','Ộ','Ơ','Ớ','Ờ','Ở','Ỡ','Ợ'];
		    var code_o = ['ó','ò','ỏ','õ','ọ','ô','ố','ồ','ổ','ỗ','ộ','ơ','ớ','ờ','ở','ỡ','ợ'];
		    var code_U = ['Ú','Ù','Ủ','Ũ','Ụ','Ư','Ứ','Ừ','Ử','Ữ','Ự'];
		    var code_u = ['ú','ù','ủ','ũ','ụ','ư','ứ','ừ','ử','ữ','ự'];
		    var code_Y = ['Ý','Ỳ','Ỷ','Ỹ','Ỵ'];
		    var code_y = ['ý','ỳ','ỷ','ỹ','ỵ'];
		    if($.inArray(removedChar, code_a) !== -1 || $.inArray(removedChar, code_A) !== -1) {
			    new_char = 'A';
			}else if($.inArray(removedChar, code_d) !== -1 || $.inArray(removedChar, code_D) !== -1){
				new_char = 'D';
			}else if($.inArray(removedChar, code_e) !== -1 || $.inArray(removedChar, code_E) !== -1){
				new_char = 'E';
			}else if($.inArray(removedChar, code_i) !== -1 || $.inArray(removedChar, code_I) !== -1){
				new_char = 'I';
			}else if($.inArray(removedChar, code_o) !== -1 || $.inArray(removedChar, code_O) !== -1){
				new_char = 'O';
			}else if($.inArray(removedChar, code_u) !== -1 || $.inArray(removedChar, code_U) !== -1){
				new_char = 'U';
			}else if($.inArray(removedChar, code_y) !== -1 || $.inArray(removedChar, code_Y) !== -1){
				new_char = 'Y';
			}else{
				new_char = removedChar;
			}
		    var res = str.replace(removedChar, new_char);
		    $(this).val(res);
			//$(this).parents('.form-center').find('input[type=submit]').attr('disabled', false).removeClass('cursorOverride').addClass('cursor-not-allowed');
			$(this).parents('.div-input').find('.form-error').text('Vui lòng nhập chữ in hoa không dấu!');
		}
	}else{
		$(this).parents('.div-input').find('.form-error').text('');
	}
	
	//$(this).parents('li').addClass('active');
});
$('body').on('change','.name-unicode',function(e){
	var csrf = $('input[name="csrf_megav_name"]').val();
	var _this = $(this);
	var unicode = _this.val();
	if (unicode.length >0) {
		$.ajax({
			url: '/firm_banking/loadUnicode',
			type: 'POST',
			dataType : 'json',
			data: {csrf_megav_name:csrf,unicode:unicode},  
		}).done(function(data) {
			if (data.status==true) {
				_this.val(data.unicode);
				_this.parents('.div-input').find('.form-error').text('');
			}
			
		});
	}
	
});

function checkUnicode(str) {
    var patt = new RegExp("^([\x00-\x7F]|[\xC2-\xDF][\x80-\xBF]|\xE0[\xA0-\xBF][\x80-\xBF]|[\xE1-\xEC][\x80-\xBF]{2}|\xED[\x80-\x9F][\x80-\xBF]|[\xEE-\xEF][\x80-\xBF]{2}|\xF0[\x90-\xBF][\x80-\xBF]{2}|[\xF1-\xF3][\x80-\xBF]{3}|\xF4[\x80-\x8F][\x80-\xBF]{2})*$");
    var res = patt.exec(str);
    if(res){
		//alert(str.toUpperCase() + " - KO CÓ TIẾNG VIỆT"); // KO CÓ TIẾNG VIỆT
		return true;
	}else{
		return false;
    	//alert(str.toUpperCase() + " - XUẤT HIỆN TIẾNG VIỆT");// XUẤT HIỆN TIẾNG VIỆT
    }
}




$('body').on('click','.tab_trans_history li a',function(){
	$('.tab_trans_history li').removeClass('active');
	//$(this).parents('li').addClass('active');
});
$('body').on('click','.cash_in .payment_epurse_new li a',function(){
	$('.cash_in .payment_epurse_new li').removeClass('active');
	//$(this).parents('li').addClass('active');
})


$(document).ready(function(){
    $('.notify-list').hide();
});

$('body').on('click','.notify-box',function(){
    /*var _icon = $(this).find('i');
    if(_icon.hasClass('fa-plus')) {
        _icon.removeClass('fa-plus').addClass('fa-minus');
    }else{
        _icon.removeClass('fa-minus').addClass('fa-plus');
    }*/
    $(".notify-list").stop().slideToggle("slow");
});
