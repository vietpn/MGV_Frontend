/**
 * Created with JetBrains PhpStorm.
 * User: Hatt
 * Date: 10/7/14
 * Time: 9:53 AM
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function () {
$('#repass').blur(function () {
    if($('#repass').val()!=$('#pass').val())
    {
        $('#username_error').html('Mật khẩu xác nhận không đúng');
    }
});

function setcheckbox(){
    if($('.remember-pass-checkbox').is( ":checked" )){
        $('.remember-pass-checkbox').removeAttr('checked');
    } else {
        $('.remember-pass-checkbox').attr('checked', 'checked');
    }
};
$(".text-remember").on("tap",function(){
    if($('.remember-pass-checkbox').is( ":checked" )){
        $('.remember-pass-checkbox').removeAttr('checked');
    } else {
        $('.remember-pass-checkbox').attr('checked', 'checked');
    }
});
$(".btn-access").click(function(){
	$('#DKSD').attr('checked', true);
});

})

$(function() {
    $("form input").keypress(function (e) {
        if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
            $('#site_button').click();
			//alert('asdasda');
            return false;
        } else {
            return true;
        }
    });
});