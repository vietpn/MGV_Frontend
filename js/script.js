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