<script type="text/javascript">
    window.fbAsyncInit = function () {
    //Initiallize the facebook using the facebook javascript sdk
		FB.init({
			appId: '<?php echo $this->config->item('appId') ?>', // App ID
			channelUrl : '//www.localhost:7860',
			cookie: true, // enable cookies to allow the server to access the session
			status: true, // check login status
			xfbml: true, // parse XFBML
			oauth: true //enable Oauth
		});
		facebookLogin();
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
    function facebookLogin() {
        FB.login(function (response) {
            if (response.authResponse) {
                parent.location = '<?php echo base_url() ?>login/loginfb'; //redirect uri after closing the facebook popup
            }
        }, {scope: 'ads_management,read_friendlists,email,read_stream,publish_stream,user_birthday,user_location'}); //permissions for facebook
    };
</script>
