    function view_message(obj,pcode,inbox_id,csrf_value){
        $('#loading_image').show();
        var status = $(obj).attr('status');
        var count = $('.notify').html();
        if(pcode == '9999'){
            /*if(status == 0){
                count--;
                if(count >= 0) $('.notify').html(count);
                $(obj).find('img').attr('src','/images/icon_daxem.png');
                $(obj).find('.notify_title').addClass('daxem')
            }
            var mes = $(obj).attr('mes');
            $('.home-user-info .mgc-popup-wapper').show();
            $('.notify_content').html(mes);
            $('#loading_image').fadeOut(300);
            return;*/
        }
        var transid = $(obj).attr('transid');
        $.ajax({
            url: '/user/user/view_message',
            type: 'post',
            dataType: 'json',
            data: {'pcode':pcode,'inbox_id':inbox_id,'transid':transid,'csrf_token_mgcv8':csrf_value},
            success: function(res){
                if(res.suc){
                    if(status == 0){
                        count--;
                        if(count >= 0) $('.notify').html(count);
                        $(obj).find('img').attr('src','/images/icon_daxem.png');
                        $(obj).find('.notify_title').addClass('daxem');
                        
                    }
                    if(pcode == '9999'){
                        var mes = $(obj).attr('mes');
                        $('.home-user-info .mgc-popup-wapper').show();
                        $('.notify_content').html(mes);
                        $('#loading_image').fadeOut(300);
                    }else{
                        $('.home-user-info .mgc-popup-wapper').show();
                        $('.doithe_error_popup_title').html(res.title);
                        $('.notify_content').html(res.content);
                    } 
                }             
                $('#loading_image').fadeOut(300);
            },
            error: function(){
                $('.notify_content').html('Có lỗi trong quá trình lấy thông tin, Vui lòng liên hệ Admin');
                $('#loading_image').fadeOut(300);
            }
        })
    }

$(function(){
	var socket = io.connect( address_server_nodejs );
    var name = $('.account_name').val();
    var user_data = {user_name:name,device:'web'};
    // Add a connect listener
    socket.on('connect', function() {
        console.log('Connected!');
        socket.emit('new user', JSON.stringify(user_data));
    });
	socket.on('push message', function(response){
        var csrf_token = $('.csrf_token').val();
        var count_mes = $('.notify').html();
        count_mes = parseInt(count_mes)+1;
        $('.notify').html(count_mes);
        var data = response.data;
		console.log(data);
		/*
        var myDate=data.createdDate;
		
        //myDate=myDate.split(" ");
        var myTime = myDate[0];
        myDate_1 = myDate[1].split("/");
        var newDate=myDate_1[1]+"/"+myDate_1[0]+"/"+myDate_1[2]+ " " +myTime;
        var mes_date = new Date(newDate).getTime()/1000;
        var mes = '';
        var transid = ''
        if(data.pcode == 9999) mes = data.message;
        else transid = data.transid;
        console.log(response);
		*/
        var html = '<li><a href="javascript:void(0)" onclick="view_message(this,'+data.pcode+','+data.inboxId+',\''+csrf_token+'\')" mes="'+mes+'" transid="'+transid+'" status="0">';
        html += '<img src="/images/icon_chuaxem.png"/>';
        html += '<div class="notify_title chuaxem"><p title="'+response.title+'">'+response.title+'</p>';
        html += '<p class="notify_time" data-livestamp="'+mes_date+'"></p></div></a></li>';
		
		$('.home-user-inbox').prepend(html);
        $.ajax({
            type: "POST",
            url: "/user/user/get_user_info",
            dataType: 'json',
            success: function(msg){
                console.log(msg);
                if(msg.status){
                    var data = msg.data;                    
                    var bal = data.Balance;
                    bal_format = bal.toLocaleString('en-US', {minimumFractionDigits: 0});                  
                    $('.user_balance').html(bal_format);
                }else{
                    
                }
            }
        });
        /*var fiveMinutes = 60 * 5,
        display = $('.header-top-right');
        startTimer(fiveMinutes, display);  */      
	});
    
});
function startTimer(duration, display) {// countdowm time
    var timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10)
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        if(minutes==4 && seconds == 50){
            display.append('đã hết giờ');
        }

        if (--timer < 0) {
            timer = duration;
        }
    }, 1000);
}
