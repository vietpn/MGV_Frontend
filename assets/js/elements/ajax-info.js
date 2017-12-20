$(document).ready(function(){
	$.removeCookie("redirect");
	/*
	$(".acc-item-info").click(function(e) {
		e.preventDefault();
		
        $.post( "/user_info", {},
			function(data, status) {
				if(status == 'success'){
					
					$(".lg-content").html(data);
					$("#mg-content").toggleClass("toggled");
					$('.close').css('display', 'block');
				}
		});
    });
	*/
	var height = $( window ).height();
	
	$('body').on('click','.acc-item-info',function(e){
		e.preventDefault();
        var htmldata = '<iframe id="IframeId-info" src="/user_info" style="height:' + height + 'px"></iframe>';
		$(".lg-content").html(htmldata);
		$("#mg-content").toggleClass("toggled");
		$('.close').css('display', 'block');
		$.cookie('slideContent', 'user_info', {expires : 7, path:'/'});
		setTimeout(function () { $("#mg-content").html(''); }, 500);
		//$.removeCookie("page");
    });
	
	$('body').on('click','.acc-item-pass',function(e){
		e.preventDefault();
		var htmldata = '<iframe id="IframeId-info" src="/change_pass" style="height:' + height + 'px"></iframe>';
		$(".lg-content").html(htmldata);
		$("#mg-content").toggleClass("toggled");
		$('.close').css('display', 'block');
		$.cookie('slideContent', 'change_pass', {expires : 7, path:'/'});
		setTimeout(function () { $("#mg-content").html(''); }, 500);
    });
	
	$('body').on('click','.acc-item-pass-lv2',function(e){
		e.preventDefault();
		var htmldata = '<iframe id="IframeId-info" src="/change_pass_lv2" style="height:' + height + 'px"></iframe>';
		$(".lg-content").html(htmldata);
		$("#mg-content").toggleClass("toggled");
		$('.close').css('display', 'block');
		$.cookie('slideContent', 'change_pass_lv2', {expires : 7, path:'/'});
		setTimeout(function () { $("#mg-content").html(''); }, 500);
    });
	
	$('body').on('click','.acc-item-email',function(e){
		e.preventDefault();
		var htmldata = '<iframe id="IframeId-info" src="/change_email" style="height:' + height + 'px"></iframe>';
		$(".lg-content").html(htmldata);
		$("#mg-content").toggleClass("toggled");
		$('.close').css('display', 'block');
		$.cookie('slideContent', 'change_email', {expires : 7, path:'/'});
		setTimeout(function () { $("#mg-content").html(''); }, 500); 
    });
	
	$('body').on('click','.acc-item-phone',function(e){
		e.preventDefault();
		var htmldata = '<iframe id="IframeId-info" src="/change_phone" style="height:' + height + 'px"></iframe>';
		$(".lg-content").html(htmldata);
		$("#mg-content").toggleClass("toggled");
		$('.close').css('display', 'block');
		$.cookie('slideContent', 'change_phone', {expires : 7, path:'/'});
		setTimeout(function () { $("#mg-content").html(''); }, 500);
    });
	
	$('body').on('click','.acc-item-id',function(e){
		e.preventDefault();
		var htmldata = '<iframe id="IframeId-info" src="/change_id" style="height:' + height + 'px"></iframe>';
		$(".lg-content").html(htmldata);
		$("#mg-content").toggleClass("toggled");
		$('.close').css('display', 'block');
		$.cookie('slideContent', 'change_id', {expires : 7, path:'/'});
		setTimeout(function () { $("#mg-content").html(''); }, 500);
    });

	
	$('body').on('click','.acc-item-card',function(e){
		e.preventDefault();
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		var htmldata = '<iframe id="IframeId-info" src="/firm_banking" style="height:' + height + 'px"></iframe>';
		$(".lg-content").html(htmldata);
		$("#mg-content").toggleClass("toggled");
		$('.close').css('display', 'block');
		$.cookie('slideContent', 'banks_account', {expires : 7, path:'/'});
		setTimeout(function () { $("#mg-content").html(''); }, 500);
    });
	
	$('body').on('click','.acc-item-check',function(e){
		e.preventDefault();
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		var htmldata = '<iframe id="IframeId-info" src="/change_security" style="height:' + height + 'px"></iframe>';
		$(".lg-content").html(htmldata);
		$("#mg-content").toggleClass("toggled");
		$('.close').css('display', 'block');
		$.cookie('slideContent', 'change_security', {expires : 7, path:'/'});
		setTimeout(function () { $("#mg-content").html(''); }, 500);
    });
	
	$('body').on('click','.trans-item-payment',function(e){
	//$(".trans-item-payment").click(function(e) {
		e.preventDefault();
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		var htmldata = '<iframe id="IframeId-info" src="/payment_epurse" style="height:' + height + 'px"></iframe>';
		$(".lg-content").html(htmldata);
		$("#mg-content").toggleClass("toggled");
		$('.close').css('display', 'block');
		$.cookie('slideContent', 'payment_epurse', {expires : 7, path:'/'});
		$("li.transaction > span > a").css("color","");
		$("#side-menu > li > a").removeClass("active");
		$(".trans-item-payment-sidebar").css("color","#48b14b");
		$("body").addClass('bg-trans');
		setTimeout(function () { $("#mg-content").html(''); }, 500);
    }); 
    $('body').on('click','.trans-item-payment-sidebar',function(e){
    	e.preventDefault();
    	$("#mg-content").removeClass("toggled");
    	$(this).addClass('hello');
    	var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		
		if(!$("#mg-content").hasClass("toggled")) {
			var htmldata = '<iframe id="IframeId-info" src="/payment_epurse" style="height:' + height + 'px"></iframe>';
			$(".lg-content").html(htmldata);
			$("#mg-content").toggleClass("toggled");
			$('.close').css('display', 'block');
			$.cookie('redirect', '1', {expires : 1, path:'/'});
			$.cookie('slideContent', 'payment_epurse', {expires : 7, path:'/'});
			$("li.transaction > span > a").css("color","");
			$("#side-menu > li > a").removeClass("active");
			$(".trans-item-payment-sidebar").css("color","#48b14b");
			$("body").addClass('bg-trans');
			setTimeout(function () { $("#mg-content").html(''); }, 500);
			
		} else {
			var slideContent = $.cookie('slideContent');
			if(slideContent != 'payment_epurse') {
				$(".lg-content").html();
				var htmldata = '<iframe id="IframeId-info" src="/payment_epurse" style="height:' + height + 'px"></iframe>';
				$(".lg-content").html(htmldata);
				$.cookie('slideContent', 'payment_epurse', {expires : 7, path:'/'});
				$.cookie('redirect', '1', {expires : 1, path:'/'});
				$("li.transaction > span > a").css("color","");
				$("#side-menu > li > a").removeClass("active");
				$(".trans-item-payment-sidebar").css("color","#48b14b");
				$("body").addClass('bg-trans');
				setTimeout(function () { $("#mg-content").html(''); }, 500);
			}
		}
    });
	
	$('body').on('click','.trans-item-withdraw',function(e){
	//$(".trans-item-withdraw").click(function(e) {
		e.preventDefault();
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		var htmldata = '<iframe id="IframeId-info" src="/withdraw" style="height:' + height + 'px"></iframe>';
		$(".lg-content").html(htmldata);
		$("#mg-content").toggleClass("toggled");
		$('.close').css('display', 'block');
		$("li.transaction > span > a").css("color","");
		$("#side-menu > li > a").removeClass("active");
		$(".trans-item-withdraw-sidebar").css("color","#48b14b");
		$("body").addClass('bg-trans');
		setTimeout(function () { $("#mg-content").html(''); }, 500);
    });
	$(".trans-item-withdraw-sidebar").click(function(e) {
		e.preventDefault();
		$("#mg-content").removeClass("toggled");
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		if(!$("#mg-content").hasClass("toggled")) {
			var htmldata = '<iframe id="IframeId-info" src="/withdraw" style="height:' + height + 'px"></iframe>';
			$(".lg-content").html(htmldata);
			$("#mg-content").toggleClass("toggled");
			$('.close').css('display', 'block');
			$("li.transaction > span > a").css("color","");
			$("#side-menu > li > a").removeClass("active");
			$(".trans-item-withdraw-sidebar").css("color","#48b14b");
			$("body").addClass('bg-trans');
			setTimeout(function () { $("#mg-content").html(''); }, 500);
		}
    });
	
	$('body').on('click','.trans-item-transfer',function(e){
		e.preventDefault();
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		var htmldata = '<iframe id="IframeId-info" src="/transfer" style="height:' + height + 'px"></iframe>';
		$(".lg-content").html(htmldata);
		$("#mg-content").toggleClass("toggled");
		$('.close').css('display', 'block');
		$("li.transaction > span > a").css("color","");
		$("#side-menu > li > a").removeClass("active");
		$(".trans-item-transfer-sidebar").css("color","#48b14b");
		$("body").addClass('bg-trans');
		setTimeout(function () { $("#mg-content").html(''); }, 500);
		
    });
	
	$(".trans-item-transfer-sidebar").click(function(e) {
		e.preventDefault();
		$("#mg-content").removeClass("toggled");
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		if(!$("#mg-content").hasClass("toggled")) {
			var htmldata = '<iframe id="IframeId-info" src="/transfer" style="height:' + height + 'px"></iframe>';
			$(".lg-content").html(htmldata);
			$("#mg-content").toggleClass("toggled");
			$('.close').css('display', 'block');
			$("li.transaction > span > a").css("color","");
			$("#side-menu > li > a").removeClass("active");
			$(".trans-item-transfer-sidebar").css("color","#48b14b");
			$("body").addClass('bg-trans');
			setTimeout(function () { $("#mg-content").html(''); }, 500);
		}
		
    });
	
	$('body').on('click','.trans-item-bank-map',function(e){
		e.preventDefault();
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		var htmldata = '<iframe id="IframeId-info" src="/map_account" style="height:' + height + 'px"></iframe>';
		$(".lg-content").html(htmldata);
		$("#mg-content").toggleClass("toggled");
		$('.close').css('display', 'block');
		$("li.transaction > span > a").css("color","");
		$("#side-menu > li > a").removeClass("active");
		$(".trans-item-bank-map-sidebar").css("color","#48b14b");
		
		setTimeout(function () { $("#mg-content").html(''); }, 500);
    });
	
	$(".trans-item-bank-map-sidebar").click(function(e) {
		e.preventDefault();
		$("#mg-content").removeClass("toggled");
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		if(!$("#mg-content").hasClass("toggled")) {
			var htmldata = '<iframe id="IframeId-info" src="/map_account" style="height:' + height + 'px"></iframe>';
			$(".lg-content").html(htmldata);
			$("#mg-content").toggleClass("toggled");
			$('.close').css('display', 'block');
			$("li.transaction > span > a").css("color","");
			$("#side-menu > li > a").removeClass("active");
			$(".trans-item-bank-map-sidebar").css("color","#48b14b");
			setTimeout(function () { $("#mg-content").html(''); }, 500);
		}
    });
	
	$('body').on('click','.trans-item-payment-mobile',function(e){
		e.preventDefault();
		$("#mg-content").removeClass("toggled");
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		if(!$("#mg-content").hasClass("toggled")) {
			var htmldata = '<iframe id="IframeId-info" src="/payment_phone" style="height:' + height + 'px"></iframe>';
			$(".lg-content").html(htmldata);
			$("#mg-content").toggleClass("toggled");
			$('.close').css('display', 'block');
			$("li.transaction > span > a").css("color","");
			$("#side-menu > li > a").removeClass("active");
			$(".trans-item-payment-mobile-sidebar").addClass('active');
			$(".trans-item-payment-mobile-sidebar").css("color","#48b14b");
			$("body").addClass('bg-trans');
			setTimeout(function () { $("#mg-content").html(''); }, 500);
		}
		
    });
	
	$(".trans-item-payment-mobile-sidebar").click(function(e) {
		e.preventDefault();

		$("#mg-content").removeClass("toggled");
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		if(!$("#mg-content").hasClass("toggled")) {
			var htmldata = '<iframe id="IframeId-info" src="/payment_phone" style="height:' + height + 'px"></iframe>';
			$(".lg-content").html(htmldata);
			$("#mg-content").toggleClass("toggled");
			$('.close').css('display', 'block');
			$("li.transaction > span > a").css("color","");
			$("#side-menu > li > a").removeClass("active");
			$(".trans-item-payment-mobile-sidebar").addClass('active');
			$(".trans-item-payment-mobile-sidebar").css("color","#48b14b");
			$("body").addClass('bg-trans');
			setTimeout(function () { $("#mg-content").html(''); }, 500);
		}
    });
	
	$('body').on('click','.trans-item-payment-game',function(e){
		e.preventDefault();
		$("#mg-content").removeClass("toggled");
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		if(!$("#mg-content").hasClass("toggled")) {
			var htmldata = '<iframe id="IframeId-info" src="/payment_game" style="height:' + height + 'px"></iframe>';
			$(".lg-content").html(htmldata);
			$("#mg-content").toggleClass("toggled");
			$('.close').css('display', 'block');
			$("li.transaction > span > a").css("color","");
			$("#side-menu > li > a").removeClass("active");
			$(".trans-item-payment-game-sidebar").addClass("active");
			$(".trans-item-payment-game-sidebar").css("color","#48b14b");
			$("body").addClass('bg-trans');
			setTimeout(function () { $("#mg-content").html(''); }, 500);
		}
    });
	$(".trans-item-payment-game-sidebar").click(function(e) {
		e.preventDefault();

		$("#mg-content").removeClass("toggled");
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		if(!$("#mg-content").hasClass("toggled")) {
			var htmldata = '<iframe id="IframeId-info" src="/payment_game" style="height:' + height + 'px"></iframe>';
			$(".lg-content").html(htmldata);
			$("#mg-content").toggleClass("toggled");
			$('.close').css('display', 'block');
			$("li.transaction > span > a").css("color","");
			$("#side-menu > li > a").removeClass("active");
			$(".trans-item-payment-game-sidebar").addClass("active");
			$(".trans-item-payment-game-sidebar").css("color","#48b14b");
			$("body").addClass('bg-trans');
			setTimeout(function () { $("#mg-content").html(''); }, 500);
		}
    });
	
	$('body').on('click','.trans-item-topup',function(e){
		e.preventDefault();
		$("#mg-content").removeClass("toggled");
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		if(!$("#mg-content").hasClass("toggled")) {
			var htmldata = '<iframe id="IframeId-info" src="/buy_card" style="height:' + height + 'px"></iframe>';
			$(".lg-content").html(htmldata);
			$("#mg-content").toggleClass("toggled");
			$('.close').css('display', 'block');
			$("li.transaction > span > a").css("color","");
			$("#side-menu > li > a").removeClass("active");
			$(".trans-item-topup-sidebar").addClass("active");
			$(".trans-item-topup-sidebar").css("color","#48b14b");
			$("body").addClass('bg-trans');
			setTimeout(function () { $("#mg-content").html(''); }, 500);
		}
    });
	
	$(".trans-item-topup-sidebar").click(function(e) {
		e.preventDefault();
		$("#mg-content").removeClass("toggled");
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		if(!$("#mg-content").hasClass("toggled")) {
			var htmldata = '<iframe id="IframeId-info" src="/buy_card" style="height:' + height + 'px"></iframe>';
			$(".lg-content").html(htmldata);
			$("#mg-content").toggleClass("toggled");
			$('.close').css('display', 'block');
			$("li.transaction > span > a").css("color","");
			$("#side-menu > li > a").removeClass("active");
			$(".trans-item-topup-sidebar").addClass("active");
			$(".trans-item-topup-sidebar").css("color","#48b14b");
			$("body").addClass('bg-trans');
			setTimeout(function () { $("#mg-content").html(''); }, 500);
		}
    });
	
	$('body').on('click','.trans-item-thanh-toan',function(e){ 
		e.preventDefault();
		$("#mg-content").removeClass("toggled");
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		if(!$("#mg-content").hasClass("toggled")) {
			var htmldata = '<iframe id="IframeId-info" src="/payment_bill/start" style="height:' + height + 'px"></iframe>';
			$(".lg-content").html(htmldata);
			$("#mg-content").toggleClass("toggled");
			$('.close').css('display', 'block');
			$("li.transaction > span > a").css("color","");
			$("#side-menu > li > a").removeClass("active");
			$(".trans-item-thanh-toan-sidebar").css("color","#48b14b");
			$("body").addClass('bg-trans');
			setTimeout(function () { $("#mg-content").html(''); }, 500);
		}
    });
    $('body').on('click','.trans-item-thanh-toan-sidebar',function(e){
		e.preventDefault();
		//reqAjaxPayment();
		//reqAppendChoosePartner();


		$("#mg-content").removeClass("toggled");
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		if(!$("#mg-content").hasClass("toggled")) {
			var htmldata = '<iframe id="IframeId-info" src="/payment_bill/start" style="height:' + height + 'px"></iframe>';
			$(".lg-content").html(htmldata);
			$("#mg-content").toggleClass("toggled");
			$('.close').css('display', 'block');
			$("li.transaction > span > a").css("color","");
			$("#side-menu > li > a").removeClass("active");
			$(".trans-item-thanh-toan-sidebar").css("color","#48b14b");
			$("body").addClass('bg-trans');
			setTimeout(function () { $("#mg-content").html(''); }, 500);
		}
		
		
    });

	$('body').on('click','.trans-history',function(e){
		e.preventDefault();
		//reqAjaxPayment();
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		//$("#mg-content").css("display","none!important");
		
		$("#mg-content").removeClass("toggled");
		if(!$("#mg-content").hasClass("toggled")) {
			var htmldata = '<iframe id="IframeId-info" src="/trans_history" style="height:' + height + 'px"></iframe>';
			$(".lg-content").html(htmldata);
			$("#mg-content").toggleClass("toggled");
			$('.close').css('display', 'block');
			$("li.transaction > span > a").css("color","");
			$("#side-menu > li > a").removeClass("active");
			$(".trans-history").addClass("active");
			$("body").removeClass("bg-trans");
			setTimeout(function () { $("#mg-content").html(''); }, 500);
			//$("#page-content-wrapper").css("width","73%");
		}
		
	});

	// biến động số dư 
	$('body').on('click','.balance-change',function(e){
		e.preventDefault();
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		
		$("#mg-content").removeClass("toggled");
		if(!$("#mg-content").hasClass("toggled")) {
			var htmldata = '<iframe id="IframeId-info" src="/balance_change" style="height:' + height + 'px"></iframe>';
			$(".lg-content").html(htmldata);
			$("#mg-content").toggleClass("toggled");
			$('.close').css('display', 'block');
			$("li.transaction > span > a").css("color","");
			$("#side-menu > li > a").removeClass("active");
			$(".balance-change").addClass("active");
			$("body").removeClass("bg-trans");
			setTimeout(function () { $("#mg-content").html(''); }, 500);
			//$("#page-content-wrapper").css("width","73%");
		}
		
	});


	$('body').on('click','.support-center-sidebar',function(e){
		e.preventDefault();
		//reqAjaxPayment();
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		//$("#mg-content").css("display","none!important");
		
		$("#mg-content").removeClass("toggled");
		if(!$("#mg-content").hasClass("toggled")) {
			var htmldata = '<iframe id="IframeId-info" src="/support_center" style="height:' + height + 'px"></iframe>';
			$(".lg-content").html(htmldata);
			$("#mg-content").toggleClass("toggled");
			$('.close').css('display', 'block');
			$("li.transaction > span > a").css("color","");
			$("#side-menu > li > a").removeClass("active");
			$(".support-center-sidebar").addClass("active");
			$("body").removeClass("bg-trans");
			setTimeout(function () { $("#mg-content").html(''); }, 500);
			
		}
		
	});
	
	$('body').on('click','.news-promotions-sidebar',function(e){
		/*
		e.preventDefault();
		var deviceWidth = viewport();
		if (deviceWidth.width <= '768') {
			$("#wrapper").removeClass('toggled');
		}
		$("#mg-content").removeClass("toggled");
		if(!$("#mg-content").hasClass("toggled")) {
			var htmldata = '<iframe id="IframeId-info" src="/news_promotions" style="height:' + height + 'px"></iframe>';
			$(".lg-content").html(htmldata);
			$("#mg-content").toggleClass("toggled");
			$('.close').css('display', 'block');
			$("li.transaction > span > a").css("color","");
			$("#side-menu > li > a").removeClass("active");
			$(".news-promotions-sidebar").addClass("active");
			$("body").removeClass("bg-trans");
			setTimeout(function () { $("#mg-content").html(''); }, 500);
			
		}
		*/
		
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

		$('body').on('click','.breadcrumb_parent',function(e){
            e.preventDefault();
			alert( "bla bla" );
        }); 


    $('body').on('click','.item-thanh-toan',function(e){
    	//reqAjaxPayment();
    });


    $('body').on('click','.active_homecredit',function(){
    	
    	genFormTraCuu('VAY TRẢ GÓP THẺ TÍN DỤNG HOME CREDIT','HOME_CREDIT');
    });
    $('body').on('click','.active_fecredit',function(){
    	genFormTraCuu('VAY TRẢ GÓP THẺ TIÊU DÙNG FECREDIT','FE_CREDIT');
    });
    $('body').on('click','.active_merchant',function(){
    	genFormTraCuu('EC-Merchant');
    });



    $('body').on('keyup','.bill_info #amount_curent',function() {
	   format_curency(this);
	});
	$('body').on('keyup','.bill_info #amount',function() {
	   format_curency(this);
	});

	

	

	

    



    /*function reqAjaxPayment(){
    	$(".mg-content").addClass('toggled');
    	$(".lg-content").html('');
    }*/

    function reqAppendChoosePartner(){
    	$(".mg-content").addClass('toggled');
    	$(".lg-content").html('');
    	var html = '<ul class="breadcrumb">'+
						'<li class="first">|</li>'+
						'<li><a href="#">Thanh toán</a></li>'+
					'</ul>';
    	html += '<div class="row"><div class="cash_in cash_in_new_choose">'+
    					'<ul class="nav nav-tabs col-md-offset-3">' +
				        '<li><a class="active_homecredit">VAY TRẢ GÓP THẺ TÍN DỤNG HOME CREDIT</a></li>'+
				        '<li><a class="active_fecredit">VAY TRẢ GÓP THẺ TIÊU DÙNG FECREDIT</a></li>'+
				        '<li><a class="active_merchant">EC-Merchant</a></li>'+
				    '</ul></div></div>';
	    $('.lg-content').fadeOut(100, function(){
            $('.lg-content').html(html).fadeIn();
        });
    }

    $('body').on('click','.btn_check',function(){
    	var csrf = $('input[name="csrf_megav_name"]').val();
    	var trand_id = $('.trand_name').val();
    	$.ajax({
			url: '/payment_epurse/getPayment',
			type: 'POST',
			dataType: 'json',
			data: {trand_id: trand_id,csrf_megav_name:csrf},
		})
		.done(function(data) {
			
		});
    });

    




    /*function reqAjaxPayment(){
    	$(".mg-content").addClass('toggled');
    	$(".lg-content").html('');
    }*/

    function reqAppendChoosePartner(){
    	$(".mg-content").addClass('toggled');
    	$(".lg-content").html('');
    	var html = '<ul class="breadcrumb">'+
						'<li class="first">|</li>'+
						'<li><a href="#">Thanh toán</a></li>'+
					'</ul>';
    	html += '<div class="row"><div class="cash_in cash_in_new_choose">'+
    					'<ul class="nav nav-tabs col-md-offset-3">' +
				        '<li><a class="active_homecredit">VAY TRẢ GÓP THẺ TÍN DỤNG HOME CREDIT</a></li>'+
				        '<li><a class="active_fecredit">VAY TRẢ GÓP THẺ TIÊU DÙNG FECREDIT</a></li>'+
				        '<li><a class="active_merchant">EC-Merchant</a></li>'+
				    '</ul></div></div>';
	    $('.lg-content').fadeOut(100, function(){
            $('.lg-content').html(html).fadeIn();
        });
    }


    function format_curency(a) {
    	a.value = a.value.replace(/\./g, '');
		a.value = a.value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
	}

	
	
	$('.close').click(function(e) {
		$("body").removeClass('bg-trans');
		$("li.transaction > span > a").css("color","");
		var path = location.pathname;
		//console.log(path);
		var url = '';
		switch(path){
			case '/transaction_manage' : url = "/page/index/getTransPage";
			break;
			case '/acc_manage' : url = "/page/index/getAccPage";
			break;
			default: url = "/page/index/getTransPage";
			break;
		}
		
		var csrf = $('input[name="csrf_megav_name"]').val();
			$.ajax({
				url: url,
				type: 'POST',
				dataType : 'json',
				data:{csrf_megav_name:csrf}
			}).done(function(data) {
				$("#mg-content").html();
				$("#mg-content").html(data.html);
				//$("#mg-content").toggleClass("toggled");
				//$('.close').css('display', 'block');
			});
		
		
		
		$(".lg-content").html('');
		//$("#mg-content").toggleClass("toggled");
		
		setTimeout(function () { $("#mg-content").toggleClass("toggled"); }, 500);
		$('.close').css('display', 'none');
	});



	$('body').on('click','#myModalChooseAvatar .dismiss_choose_avatar', function() {
		$('#myModalChooseAvatar .mess_move').addClass('hidden_load');
		$('#myModalChooseAvatar .view_edit_img').addClass('hidden_load');
		$('#myModalChooseAvatar .mess_choose').removeClass('hidden_load');

		$('#myModalChooseAvatar .scroll_step').addClass('hidden_load');
		$('#myModalChooseAvatar .scroll_step').addClass('hidden_load');

		//$('#myModalChooseAvatar .jcrop-holder').remove();
		//$('#myModalChooseAvatar .view_edit_img').removeAttr('style');
		if ($('#target_crop').data('Jcrop')) {
		    $('#target_crop').data('Jcrop').destroy();
		    $('#target_crop').removeAttr('style');
		}

		//var _this = $(this);
		var src = $('#myModalChooseAvatar .view_edit_img').attr('data-src');
		var csrf = $('input[name="csrf_megav_name"]').val();
		removeAvatar(src,csrf);
		$("#myModalChooseAvatar").modal("hide");
	});

	$('body').on('click','#myModalChooseAvatar .close_new', function() {
		$('#myModalChooseAvatar .mess_move').addClass('hidden_load');
		$('#myModalChooseAvatar .view_edit_img').addClass('hidden_load');
		$('#myModalChooseAvatar .mess_choose').removeClass('hidden_load');

		$('#myModalChooseAvatar .scroll_step').addClass('hidden_load');
		$('#myModalChooseAvatar .scroll_step').addClass('hidden_load');

		//$('#myModalChooseAvatar .jcrop-holder').remove();
		//$('#myModalChooseAvatar .view_edit_img').removeAttr('style');
		if ($('#target_crop').data('Jcrop')) {
		    $('#target_crop').data('Jcrop').destroy();
		    $('#target_crop').removeAttr('style');
		}

		//var _this = $(this);
		var src = $('#myModalChooseAvatar .view_edit_img').attr('data-src');
		var csrf = $('input[name="csrf_megav_name"]').val();
		
		removeAvatar(src,csrf);
	});


	var jcrop_api;
	$('body').on('change','.img_avatar_choose', function() {
		if ($('#target_crop').data('Jcrop')) {
		    $('#target_crop').data('Jcrop').destroy();
		    $('#target_crop').removeAttr('style');
		}
		var src = $('#myModalChooseAvatar .view_edit_img').attr('data-src');
		var csrf = $('input[name="csrf_megav_name"]').val();    
		removeAvatar(src,csrf);
		$('.loadding_upload_img').removeClass('hidden_load');
		var _this = $(this);
		$('#myModalChooseAvatar .error_mess').text('');
	    var file_data = $(this).prop('files')[0];   
	    var form_data = new FormData();                  
	    form_data.append('file', file_data);
	    form_data.append('csrf_megav_name', csrf);
	    //console.log(form_data);         
	                    
	     $.ajax({
			url: '/acc_manage/uploadAvatarStepOne',
			type: 'POST',
			dataType: 'json',
			cache: false,
            contentType: false,
            processData: false,
            data: form_data,  
		})
		.done(function(data) {
			if (data.status==true) {
				//console.log(data.src);
				_this.parents('#myModalChooseAvatar').find('.view_edit_img').attr('src',data.src);
				_this.parents('#myModalChooseAvatar').find('.view_edit_img').attr('data-src',data.src);
				_this.parents('#myModalChooseAvatar').find('#url_img').val(data.src);
				$('#target_crop').Jcrop({
			          aspectRatio: 1,
			          minSize: 50,
			          onSelect: updateCoords,
			          boxWidth: 500, 
       				  boxHeight: 400
			          //onSelect: applyCoords,
			          //onChange: applyCoords
			      },function(){
				    jcrop_api = this;
				  });
				$('.mess_move').removeClass('hidden_load');
				$('.mess_choose').addClass('hidden_load');
				$('.view_edit_img').removeClass('hidden_load');

				$('#myModalChooseAvatar .scroll_step').removeClass('hidden_load');
				$('#myModalChooseAvatar .scroll_step').removeClass('hidden_load');
			}else{
				$('.mess_move').addClass('hidden_load');
				$('.mess_choose').removeClass('hidden_load');
				$('.view_edit_img').addClass('hidden_load');
				$('#myModalChooseAvatar .error_mess').text(data.mess);
			}
			$('.loadding_upload_img').addClass('hidden_load');
			
		});
	});

	$('body').on('click','.step_left .block_step', function() {
		var scalex = $('#myModalChooseAvatar #scalex').val();
		var scaley = $('#myModalChooseAvatar #scaley').val();

		var current_img_w = $('#myModalChooseAvatar .view_edit_img').width();
		var current_img_h = $('#myModalChooseAvatar .view_edit_img').height();

		//if (scalex <= current_img_w && scaley<= current_img_h) {
			$('#myModalChooseAvatar #scalex').val(parseInt(scalex)+10);
			$('#myModalChooseAvatar #scaley').val(parseInt(scaley)+10);
			var scalex_new = $('#myModalChooseAvatar #scalex').val();
			var scaley_new = $('#myModalChooseAvatar #scaley').val();

			/*console.log(current_img_w);
			console.log(current_img_h);
			console.log("=========");
			console.log(scalex_new);
			console.log(scaley_new);*/

			if ($('#target_crop').data('Jcrop')) {
			    $('#target_crop').data('Jcrop').destroy();
			    $('#target_crop').removeAttr('style');
			}
			$('#target_crop').Jcrop({
		          aspectRatio: 1,
		          minSize: 50,
		          onSelect: updateCoords,
		          boxWidth: scalex_new, 
				  boxHeight: scaley_new
		          //onSelect: applyCoords,
		          //onChange: applyCoords
		      });
		//}else{
			//$('#myModalChooseAvatar .error_mess').text('Đã phóng to ảnh hết cỡ.');
		//}

		
	});

	$('body').on('click','.step_right .block_step', function() {
		$('#myModalChooseAvatar .error_mess').text('');
		var scalex = $('#myModalChooseAvatar #scalex').val();
		var scaley = $('#myModalChooseAvatar #scaley').val();
		$('#myModalChooseAvatar #scalex').val(parseInt(scalex)-10);
		$('#myModalChooseAvatar #scaley').val(parseInt(scaley)-10);
		var scalex_new = $('#myModalChooseAvatar #scalex').val();
		var scaley_new = $('#myModalChooseAvatar #scaley').val();

		//console.log(scalex_new);
		if ($('#target_crop').data('Jcrop')) {
		    $('#target_crop').data('Jcrop').destroy();
		    $('#target_crop').removeAttr('style');
		}
		$('#target_crop').Jcrop({
	          aspectRatio: 1,
	          minSize: 50,
	          onSelect: updateCoords,
	          boxWidth: scalex_new, 
			  boxHeight: scaley_new
	          //onSelect: applyCoords,
	          //onChange: applyCoords
	      });
	});

	$('body').on('click','#btn-add_bank', function() {
		$.cookie('addBank', '1', {expires : 1, path:'/'});
	});
	
	
	// require add bank account
		var addBank = $.cookie('addBank');
		//alert(addBank);
		var path = location.pathname;
		//alert(path);
		if(addBank != null && addBank == '1' && path != '/transaction_manage')	{
			
			var deviceWidth = viewport();
			if (deviceWidth.width <= '768') {
				$("#wrapper").removeClass('toggled');
			}
			var htmldata = '<iframe id="IframeId-info" src="/banks_account" style="height:' + height + 'px"></iframe>';
			$(".lg-content").html(htmldata);
			$("#mg-content").toggleClass("toggled");
			$('.close').css('display', 'block');
			$.cookie('slideContent', 'banks_account', {expires : 7, path:'/'});
			setTimeout(function () { $("#mg-content").html(''); }, 500);
		}
          
		
});

function removeAvatar(src,csrf){
	   
	$.ajax({
			url: '/acc_manage/removeAvatarStepOne',
			type: 'POST',
			dataType: 'json',
            data: {src:src,csrf_megav_name:csrf},  
		})
		.done(function(data) {
			if (data.status==true) {
				//console.log(data.src);
				$('#myModalChooseAvatar').find('.view_edit_img').attr('data-src','');
			}
			
		});
}

function viewport() {
    var e = window, a = 'inner';
    if (!('innerWidth' in window )) {
        a = 'client';
        e = document.documentElement || document.body;
    }
    return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
}


function updateCoords(c){
        $('#myModalChooseAvatar #jcrop_x').val(c.x);
        $('#myModalChooseAvatar #jcrop_y').val(c.y);
        $('#myModalChooseAvatar #jcrop_w').val(c.w);
        $('#myModalChooseAvatar #jcrop_h').val(c.h);
        $('#myModalChooseAvatar .action_crop').removeClass('hidden_load');
        $('#myModalChooseAvatar .btn-file').addClass('hidden_load');
        
};
