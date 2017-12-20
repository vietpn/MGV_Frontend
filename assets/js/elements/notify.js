
// notify
$(function(){

	// get list notify
	$('body').on('click','.notify-box',function(){

	    var count_mess = 0;
     	$(".notify-list .panel-body .view-box").each(function() {
     			count_mess = count_mess + 1;
     	});
		if (count_mess==0) {
			var csrf = $('input[name="csrf_megav_name"]').val();
			var url  = '/acc_manage/getListNotify';
			var page_num = $('.notify-list .page_num_messager').val();
			//console.log(csrf);
			$.ajax({
				url: url,
				type: 'POST',
				dataType : 'json',
				data:{csrf_megav_name:csrf,page_num:page_num}
			}).done(function(data) {

				if (data.status==true) {
					var xhtml ='';
					var response = data.listMess;
					$.each(response, function(index, value){
						if (value.status==1) {
							xhtml += '<div class="view-box viewed" data-title="'+value.title+'" data-date="'+value.createdDate+'" data-body="'+value.body+'" data-status="'+value.status+'" data-id="'+value.id+'">'+value.title+' | '+value.createdDate+'</div>';
						}else{
							xhtml += '<div class="view-box" data-title="'+value.title+'" data-date="'+value.createdDate+'" data-body="'+value.body+'" data-status="'+value.status+'" data-id="'+value.id+'">'+value.title+' | '+value.createdDate+'</div>';
						}
						
			            //$("#result").append(index + ": " + value + '<br>');
			        });
			        $('.notify-list .panel-body').append(xhtml);

				}else{
					$('.notify-list .panel-body').html('<div class="error-notify">Có lỗi trong quá trình lấy thông tin, Vui lòng liên hệ Admin</div>');
                	$('.notify-list').fadeOut(3000);
				}
				$('.notify-list .page_num_messager').val(data.page_num);
			});
		}

	});

	// get view more notify
	$('body').on('click','.notify-all',function(){
		var csrf = $('input[name="csrf_megav_name"]').val();
		var url  = '/acc_manage/getViewMoreNotify';
		var page_num = $('.notify-list .page_num_messager').val();
		//console.log(csrf);
		$.ajax({
			url: url,
			type: 'POST',
			dataType : 'json',
			data:{csrf_megav_name:csrf,page_num:page_num}
		}).done(function(data) {

			if (data.status==true) {
				var xhtml ='';
				var response = data.listMess;
				$.each(response, function(index, value){
					if (value.status==1) {
						xhtml += '<div class="view-box viewed" data-title="'+value.title+'" data-date="'+value.createdDate+'" data-body="'+value.body+'" data-status="'+value.status+'" data-id="'+value.id+'">'+value.title+' | '+value.createdDate+'</div>';
					}else{
						xhtml += '<div class="view-box" data-title="'+value.title+'" data-date="'+value.createdDate+'" data-body="'+value.body+'" data-status="'+value.status+'" data-id="'+value.id+'">'+value.title+' | '+value.createdDate+'</div>';
					}
					
		            //$("#result").append(index + ": " + value + '<br>');
		        });
		        $('.notify-list .panel-body').append(xhtml);
		        $('.notify-list .page_num_messager').val(data.page_num);

			}else{
				$('.notify-list .panel-body').html('<div class="error-notify">Có lỗi trong quá trình lấy thông tin, Vui lòng liên hệ Admin</div>');
            	$('.notify-list').fadeOut(3000);
			}
			
		});

	});

	// view check inbox 
	$('body').on('click','.notify-list .panel-body .view-box',function(){ 

		var csrf = $('input[name="csrf_megav_name"]').val();
		var url  = '/acc_manage/checkInboxNotify';
		var _this = $(this);
		var id_mess  = _this.attr('data-id');
		var status  = _this.attr('data-status');
		var body  = _this.attr('data-body');
		var title  = _this.attr('data-title');
		var date  = _this.attr('data-date');
		//console.log(csrf);
		if (status==0) {
			$.ajax({
				url: url,
				type: 'POST',
				dataType : 'json',
				data:{csrf_megav_name:csrf,id_mess:id_mess}
			}).done(function(data) {
				if (data.status) {
					_this.attr('data-status',1);
					_this.addClass('viewed');
					$('.notify-box span').text(data.countUserInbox);
				}
			});
		}
		$('#myModalMesager .modal-body .title-mess').html('<strong>Tiêu đề</strong>: '+title);	
		$('#myModalMesager .modal-body .body-mess').html('<strong>Nội dung</strong>: '+body);	
		$('#myModalMesager .modal-body .footer-mess').html('<strong>Ngày gửi</strong>: '+date);	
		$('#myModalMesager').modal('show');	

	});


	var socket = io.connect('http://172.16.10.160:5008');//'http://172.16.10.160:5008');
    // gửi đi từ client

    var name = $('.account_name').val();

	var user_data = {user_name:name,device:'web'};
	// Add a connect listener
	//console.log(user_data);
	socket.on('connect', function() {
	    console.log('Connected!');
	    socket.emit('new user', JSON.stringify(user_data));
	});



    // nhận về push message từ server
    socket.on('push message', function(response){
    	//console.log(response);
	    var csrf = $('input[name="csrf_megav_name"]').val();
		var url  = '/acc_manage/formatInboxNotify';
		//console.log(csrf);
		$.ajax({
			url: url,
			type: 'POST',
			dataType : 'json',
			data:{csrf_megav_name:csrf,response:response}
		}).done(function(data) {
			if (data.status==true) {
				$('.notify-box span').text(data.countUserInbox);
				var count_mess = 0;
		     	$(".notify-list .panel-body .view-box").each(function() {
		     			count_mess = count_mess + 1;
		     	});
				if (count_mess>0) {
					if (data.status_mess==1) {
						var xhtml = '<div class="view-box viewed" data-title="'+data.title+'" data-date="'+data.createdDate+'" data-body="'+data.body+'" data-status="'+data.status_mess+'" data-id="'+data.id+'">'+data.title+' | '+data.createdDate+'</div>';
					}else{
						var xhtml = '<div class="view-box" data-title="'+data.title+'" data-date="'+data.createdDate+'" data-body="'+data.body+'" data-status="'+data.status_mess+'" data-id="'+data.id+'">'+data.title+' | '+data.createdDate+'</div>';
					}
					$('.notify-list .panel-body').prepend(xhtml);
				}
				
				playSound();
			}
		});



        /*$.ajax({
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
        });*/
    });


});


var playSound = (function beep() {
    var snd = new Audio("data:audio/wav;base64,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qJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");  
    return function() {     
        snd.play(); 
    }
})();