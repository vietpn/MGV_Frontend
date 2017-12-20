<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hatt
 * Date: 10/29/14
 * Time: 1:54 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<div class="col-md-4 col-md-offset-4" id="txt_center">
    <div class="title_login">
        <span id="txt_gray" class="hidden-xs"><b>CHỈ CẦN MỘT TÀI KHOẢN</b></span>
        <br>
        <span id="txt_17" class="hidden-xs">Để đăng nhập tất cả các website thành viên của VNPT EPAY</span>
    </div>
    <div class="signin_containt logo_padding">
        <ul class="list-inline">
            <li class="hd_logo_pd">
                <a href="<?php echo base_url().'login/login_direct' ?>">
                    <img src="<?php echo base_url() . 'images/login/fb.png' ?>" alt="fb" class="img-responsive"/>
                </a>
            </li>
            <li class="hd_logo_pd">
                <a class="login" href="<?php if (isset($url_google)) echo $url_google ?>">
                    <img src="<?php echo base_url() . 'images/login/google.png' ?>" alt="google"
                         class="img-responsive"/>
                </a>
            </li>
            <li class="hd_logo_pd" onclick="javasripts:alert('Hệ thống chưa hỗ trợ);"><img
                    src="<?php echo base_url() . 'images/login/twitter.png' ?>" alt="twitter" class="img-responsive"/>
            </li>
            <li><img onclick="javasripts:alert('Hệ thống chưa hỗ trợ);"
                     src="<?php echo base_url() . 'images/login/yahoo.png' ?>" alt="yahoo" class="img-responsive"/></li>
        </ul>
    </div>


    <?php echo form_open(base_url() . 'login/do_login', array('method' => 'post', 'class' => 'form-signin', 'role' => 'form')); ?>
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    <div class="signin_containt login_main">
        <h3 class="form-signin-heading txt_20  hidden-xs" id="mg_00">Đăng nhập
                    <span class="signin-heading-item"> (Chưa có tài khoản? <a href="../register"> <span
                                style="color: #2684dc">Đăng ký</span></a>)
                    </span>
        </h3>        <input name="username" type="username" class="form-control " placeholder="Username" maxlength="25" required autofocus>
        <input name="password" type="password" class="form-control" autocomplete="off" placeholder="Password" maxlength="25" required>
        <input type="hidden" value="<?php echo $clientId ?>" name="client">
        <input type="hidden" value="<?php echo $mac_address ?>" name="mac_address">
        <input type="hidden" value="<?php echo $publisher_id ?>" name="publisher_id">
        <input type="hidden" value="<?php echo $source_url ?>" name="source_url">
        <button class="btn btn-lg btn-primary btn-block form-login-butt" type="submit" name="submit" id="login_butt">
            <b class="hidden-md hidden-lg">LOGIN</b>
            <b class="hidden-xs">ĐĂNG NHẬP</b>
        </button>

        <div class="checkbox txt_login" style="text-align: left;color: #333333;font-size: 14px">
            <label style="float: left" class="hidden-md hidden-lg hidden-sm">
                <input type="checkbox" name="remmember"> <span>Remmember login</span>
            </label>
            <label style="float: left" class="hidden-xs">
                <input type="checkbox" name="remmember"> <span>Nhớ mật khẩu</span>
            </label>
            <label style="float: right" class="hidden-md hidden-lg hidden-sm">
                <a href="../login/resetbyemail">Fogot password?</a>
            </label>
            <label style="float: right" class="hidden-xs">
                <a href="../login/resetbyemail">Quên mật khẩu?</a>
            </label>
        </div>
    </div>
    </form>
    <div class="hidden-md hidden-lg hidden-sm" style="padding-top:10px">
        <a href="<?php echo base_url() . 'register' ?>"> <span class="txt_register"> Register ID </span></a>
    </div>

</div>

<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-30624525-1']);
    _gaq.push(['_trackPageview']);

    (function () {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();

    window.fbAsyncInit = function () {
        //Initiallize the facebook using the facebook javascript sdk
        FB.init({
            appId: '<?php echo $this->config->item('appId') ?>', // App ID
            cookie: true, // enable cookies to allow the server to access the session
            status: true, // check login status
            xfbml: true, // parse XFBML
            oauth: true //enable Oauth
        });
    };
    //Read the baseurl from the config.php file
    (function (d) {
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement('script');
        js.id = id;
        js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);
    }(document));
    //Onclick for fb login
	/*
	FB.login(function (response) {
		if (response.authResponse) {
			parent.location = '<?php echo base_url() ?>login/loginfb'; //redirect uri after closing the facebook popup
		}
	}, {scope: 'ads_management,read_friendlists,email,read_stream,publish_stream,user_birthday,user_location'}); //permissions for facebook
	*/
	
	
    $('.facebook').click(function (e) {
        FB.login(function (response) {
            if (response.authResponse) {
                parent.location = '<?php echo base_url() ?>login/loginfb'; //redirect uri after closing the facebook popup
            }
        }, {scope: 'ads_management,read_friendlists,email,read_stream,publish_stream,user_birthday,user_location'}); //permissions for facebook
    });
	
</script>

