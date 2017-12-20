<!DOCTYPE html>
<?php
$urlSecur = base_url() . '../register/security_code';
?>
<html lang="vi" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MegaID <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url().'../images/megaid-favicon.png' ?>"/>
    <link href="<?php echo base_url() . '../css/bootstrap/bootstrap.min.css' ?>" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="../css/caroulsel-reponsive-style.css"/>
    <link rel="stylesheet" type="text/css" href="../css/layout/layout_login_mobile.css"/>
	
    <script type="text/javascript" src="<?php echo base_url() . '../js/jquery.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url() . '../js/jquery.min.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url() . '../js/jquery.flexisel.js' ?>"></script>
    <script type="text/javascript" src="<?php echo base_url() . '../js/bootstrap/bootstrap.min.js' ?>"></script>
    <script type="text/javascript" src="../js/script.js"></script>
    <script type="text/javascript" src="../js/login/login.js"></script>
	
</head>
<body>
<div class="container-fluid">
<div class="mobile_view">
	
	<?php
		if(isset($lock_login_type) && !empty($lock_login_type))
		{
	?>
	<?php echo form_open('login/do_login', array('method' => 'post', 'name' => 'login', 'id' => 'login', 'class' => 'form-horizontal frm-register')); ?>
		<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
		<div class="row social">
	<?php
			if(!isset($lock_login_type['fb']))
			{
	?>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 facebook">
					<div class="facebook-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<!--a href="<?php // echo base_url() . 'login/login_direct' ?>">
						<span class="facebook-addon col-lg-2 col-md-2 col-sm-2 col-xs-2"><img
								src="<?php // echo base_url() . '/images/login/F.png' ?>"/></span>
							<span class="facebook-name-mini col-lg-10 col-md-10 col-sm-10 col-xs-10 hidden-xs">Đăng nhập Facebook</span>
							<span class="facebook-name-mini hidden-lg hidden-md hidden-sm col-xs-6 block-zip">Facebook</span>
						</a-->
						<span class="facebook-addon col-lg-2 col-md-2 col-sm-2 col-xs-2"><img
								src="<?php echo base_url() . '/images/login/F.png' ?>"/>
						</span>
						<span class="facebook-name-mini col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center hidden-xs">
							<input name="facebook_login" type="submit" value="Facebook" class="input_facebook btn"/>
						</span>
						<span class="facebook-name-mini hidden-lg hidden-md hidden-sm col-xs-8 block-zip text-center">
							<input name="facebook_login" type="submit" class="input_facebook btn" value="Facebook"/>
						</span>
					</div>
				</div>
	<?php
			}
			if(!isset($lock_login_type['gg']))
			{
	?>
				
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 google" >
						<div class="google-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<!--a href="<?php // if (isset($url_google)) echo $url_google ?>">
							<span class="google-addon col-lg-2 col-md-2 col-sm-2 col-xs-2"><img
									src="<?php // echo base_url() . '/images/login/g.png' ?>"/></span>
								<span class="google-name-mini col-lg-10 col-md-10 col-sm-10 col-xs-10 hidden-xs">Đăng nhập Google</span>
								<span class="google-name-mini hidden-lg hidden-md hidden-sm col-xs-6 block-zip">Google</span>
							</a-->
							<span class="google-addon col-lg-2 col-md-2 col-sm-2 col-xs-2"><img
									src="<?php echo base_url() . '/images/login/g.png' ?>"/></span>
							<span class="google-name-mini col-lg-10 col-md-10 col-sm-10 col-xs-10 text-center hidden-xs">
								<input name="google_login" type="submit" class="input_google btn"  value="Google"/>
							</span>
							<span class="google-name-mini hidden-lg hidden-md hidden-sm col-xs-8 block-zip text-center">
								<input name="google_login" type="submit" class="input_google btn" value="Google"/>
							</span>
						</div>
					</div>
				
	<?php
			}
	?>
	</div>
	<?php
			if(!isset($lock_login_type['id']))
			{
	?>
			<div class="row content-mobile">
				
				<!-- Thong tin dang ky tai khoan -->
				<input type="hidden" value="<?php echo $clientId ?>" name="clientId">
				<input type="hidden" value="<?php echo $mac_address ?>" name="mac_address">
				<input type="hidden" value="<?php echo $publisher_id ?>" name="publisher_id">
				<input type="hidden" value="<?php echo $source_url ?>" name="source_url">
				<input type="hidden" value="<?php echo $requireActiveUser ?>" name="requireActiveUser">
				<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
					<div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
						<span class="input-group-addon mobile-addon"><img
								src="<?php echo base_url() . 'images/register/user.png' ?>"/></span>
						<input type="text" name="username" class="col-md-12 col-lg-12 col-xs-12 col-sm-12 mobile-input"
							   placeholder="Tên đăng nhập" value="<?php echo set_value('username', isset($username)?$username:''); ?>"/>
						<span class="validation-error col-md-12 col-lg-12 col-xs-12 col-sm-12" id="gen_error"> <?php echo form_error('username'); ?></span>
					</div>
				</div>
				<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input password">
					<div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
						<span class="input-group-addon mobile-addon"><img
								src="<?php echo base_url() . 'images/register/pass.png' ?>"/></span>
						<input type="password" autocomplete="off" name="password" class="col-md-12 col-lg-12 col-xs-12 col-sm-12 mobile-input"
							   placeholder="Mật khẩu" value="<?php echo set_value('password', isset($password)?$password:''); ?>"/>
						<span class="validation-error col-md-12 col-lg-12 col-xs-12 col-sm-12" id="gen_error"> <?php echo form_error('password'); ?></span>
					</div>
				</div>
				
				<!-- add by phongwm -->
					<?php if(!is_null($data_dksd)){ ?>
						<div class="col-md-9 col-lg-9 col-xs-12 col-sm-12 remember-pass">
							<input id="DKSD" style="float:left; margin-right: 10px;" type="checkbox" name="accept_dksd" value="1" checked="checked" />
							<span class="span_dksd" data-toggle="modal" data-target="#myModal">
								Đồng ý với <a style="text-decoration: underline; color: #019828;">điều khoản</a> của dịch vụ
							</span>
						</div>
						<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						  <div class="modal-dialog">
							<div class="modal-content">
							  <div class="modal-header">
								<!--button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button-->
								<div class="logo_dksd">
									<img class="img_dksd_logo" src="<?php echo $data_dksd['logo']; ?>" > 
								</div>
									<h4 class="modal-title title_dksd" id="myModalLabel">ĐIỀU KHOẢN SỬ DỤNG</h4>
							  </div>
							  <div class="modal-body">
								<?php echo $data_dksd['content']; ?>
							  </div>
							  <div class="modal-footer">
								<button type="button" class="btn btn-access" data-dismiss="modal">Đồng ý</button>
								<button type="button" class="btn btn-default" data-dismiss="modal">Quay lại</button>
							  </div>
							</div>
						  </div>
						</div>
					<?php } ?>
					<!-- end add by phongwm -->
				
				<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 remember-me">
					<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12 remember-mobile">
						<input class="remember-pass-checkbox" type="checkbox" name="remember_pass" value="1" checked/><span onclick="setcheckbox()">Nhớ mật khẩu</span>
					</div>
				</div>


				<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-action">
					<div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
						<input class="col-md-12 col-lg-12 col-xs-12 col-sm-12 btn btn-primary" id="site_button" type="submit"
							   value="Đăng nhập"/>
					</div>
				</div>


				<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 bottom-wrapper">
					<div class="row">
					<div class="signin col-md-6 col-lg-6 col-xs-6 col-sm-6">
						<img src="../../images/register/signin-green.png"/>
						<?php $tail = empty($mac_address)?'':('&mac_address='.$mac_address) ?>
						<?php $tail .= empty($publisher_id)?'':('&publisher_id='.$publisher_id) ?>
						<?php $tail .= empty($source_url)?'':('&source_url='.$source_url) ?>
						<a href="<?php echo base_url() . 'register?appId='.$clientId.$tail ?>">Đăng ký</a>
					</div>
					<div class="forgot-password col-md-6 col-lg-6 col-xs-6 col-sm-6">
						<img src="../../images/register/forgot-green.png"/>

						<?php if($is_mobile){ ?>
							<a href="<?php echo base_url().'login/reset_sms?appId='.$clientId.$tail ?>">Quên mật khẩu</a>
						<?php }else{ ?>
							<a href="<?php echo base_url() . 'login/resetbyemail' ?>">Quên mật khẩu</a>
						<?php } ?>
					</div>
					</div>
				</div>
			</div>
	<?php
			} else {
	?>
			<!-- add by phongwm -->
			<?php if(!is_null($data_dksd)){ ?>
				<div class="row">
					<div class="dksd-display-loginid">
						<div class="remember-pass">
							<input id="DKSD" style="float:left; margin-right: 10px;" type="checkbox" name="accept_dksd" value="1" checked="checked" />
							<span class="span_dksd" data-toggle="modal" data-target="#myModal">
								Đồng ý với <a style="text-decoration: underline; color: #019828;">điều khoản</a> của dịch vụ
							</span>
						</div>
					</div>
				</div>
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
					<div class="modal-content">
					  <div class="modal-header">
						<!--button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button-->
						<div class="logo_dksd">
							<img class="img_dksd_logo" src="<?php echo $data_dksd['logo']; ?>" > 
						</div>
							<h4 class="modal-title title_dksd" id="myModalLabel">ĐIỀU KHOẢN SỬ DỤNG</h4>
					  </div>
					  <div class="modal-body">
						<?php echo $data_dksd['content']; ?>
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-access" data-dismiss="modal">Đồng ý</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Quay lại</button>
					  </div>
					</div>
				  </div>
				</div>
			<?php } ?>
			<!-- end add by phongwm -->
			<?php } ?>
	</form>
	<?php
		}
		else
		{
	?>
	<?php echo form_open('login/do_login', array('method' => 'post', 'name' => 'login', 'id' => 'login', 'class' => 'form-horizontal frm-register')); ?>
	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    <div class="row social">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 facebook">
            <div class="facebook-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!--a href="<?php // echo base_url() . 'login/login_direct' ?>">
                <span class="facebook-addon col-lg-2 col-md-2 col-sm-2 col-xs-2"><img
                        src="<?php // echo base_url() . '/images/login/F.png' ?>"/></span>
                    <span class="facebook-name-mini col-lg-10 col-md-10 col-sm-10 col-xs-10 hidden-xs">Đăng nhập Facebook</span>
                    <span class="facebook-name-mini hidden-lg hidden-md hidden-sm col-xs-6 block-zip">Facebook</span>
					
                </a-->
				<span class="facebook-addon col-lg-2 col-md-2 col-sm-2 col-xs-2"><img
                        src="<?php echo base_url() . '/images/login/F.png' ?>"/></span>
				<span class="facebook-name-mini col-lg-10 col-md-10 col-sm-10 col-xs-10 hidden-xs text-center">
					<input name="facebook_login" type="submit" value="Facebook" class="input_facebook btn"/>
				</span>
				<span class="facebook-name-mini hidden-lg hidden-md hidden-sm col-xs-8 block-zip text-center">
					<input name="facebook_login" type="submit" class="input_facebook btn" value="Facebook"/>
				</span>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 google">
            <div class="google-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!--a href="<?php // if (isset($url_google)) echo $url_google ?>">
                <span class="google-addon col-lg-2 col-md-2 col-sm-2 col-xs-2"><img
                        src="<?php // echo base_url() . '/images/login/g.png' ?>"/></span>
                    <span class="google-name-mini col-lg-10 col-md-10 col-sm-10 col-xs-10 hidden-xs">Đăng nhập Google</span>
                    <span class="google-name-mini hidden-lg hidden-md hidden-sm col-xs-6 block-zip">Google</span>
                </a-->
				<span class="google-addon col-lg-2 col-md-2 col-sm-2 col-xs-2"><img
                        src="<?php echo base_url() . '/images/login/g.png' ?>"/></span>
				<span class="google-name-mini col-lg-10 col-md-10 col-sm-10 col-xs-10 hidden-xs text-center">
					<input name="google_login" type="submit" class="input_google btn"  value="Google"/>
				</span>
				<span class="google-name-mini hidden-lg hidden-md hidden-sm col-xs-8 block-zip text-center">
					<input name="google_login" type="submit" class="input_google btn" value="Google"/>
				</span>
            </div>
        </div>
    </div>
    <div class="row content-mobile">
        
        <!-- Thong tin dang ky tai khoan -->
        <input type="hidden" value="<?php echo $clientId ?>" name="clientId">
        <input type="hidden" value="<?php echo $mac_address ?>" name="mac_address">
        <input type="hidden" value="<?php echo $publisher_id ?>" name="publisher_id">
        <input type="hidden" value="<?php echo $source_url ?>" name="source_url">
        <input type="hidden" value="<?php echo $requireActiveUser ?>" name="requireActiveUser">
        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input">
            <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                <span class="input-group-addon mobile-addon"><img
                        src="<?php echo base_url() . 'images/register/user.png' ?>"/></span>
                <input type="text" name="username" class="col-md-12 col-lg-12 col-xs-12 col-sm-12 mobile-input"
                       placeholder="Tên đăng nhập" value="<?php echo set_value('username', isset($username)?$username:''); ?>"/>
                <span class="validation-error col-md-12 col-lg-12 col-xs-12 col-sm-12" id="gen_error"> <?php echo form_error('username'); ?></span>
            </div>
        </div>
        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-input password">
            <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                <span class="input-group-addon mobile-addon"><img
                        src="<?php echo base_url() . 'images/register/pass.png' ?>"/></span>
                <input type="password" autocomplete="off" name="password" class="col-md-12 col-lg-12 col-xs-12 col-sm-12 mobile-input"
                       placeholder="Mật khẩu" value="<?php echo set_value('password', isset($password)?$password:''); ?>"/>
                <span class="validation-error col-md-12 col-lg-12 col-xs-12 col-sm-12" id="gen_error"> <?php echo form_error('password'); ?></span>
            </div>
        </div>
		
		<!-- add by phongwm -->
		<?php if(!is_null($data_dksd)){ ?>
			
			<div class="col-md-9 col-lg-9 col-xs-12 col-sm-12 remember-pass">
				<input id="DKSD" style="float:left; margin-right: 10px;" type="checkbox" name="accept_dksd" value="1" checked="checked" />
				<span class="span_dksd" data-toggle="modal" data-target="#myModal">
					Đồng ý với <a style="text-decoration: underline; color: #019828;">điều khoản</a> của dịch vụ
				</span>
			</div>
			
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<!--button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button-->
					<div class="logo_dksd">
						<img class="img_dksd_logo" src="<?php echo $data_dksd['logo']; ?>" > 
					</div>
						<h4 class="modal-title title_dksd" id="myModalLabel">ĐIỀU KHOẢN SỬ DỤNG</h4>
				  </div>
				  <div class="modal-body">
					<?php echo $data_dksd['content']; ?>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-access" data-dismiss="modal">Đồng ý</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Quay lại</button>
				  </div>
				</div>
			  </div>
			</div>
		<?php } ?>
		<!-- end add by phongwm -->
		
        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 remember-me">
            <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12 remember-mobile">
                <input class="remember-pass-checkbox" type="checkbox" name="remember_pass" value="1" checked/><span onclick="setcheckbox()">Nhớ mật khẩu</span>
            </div>
        </div>


        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-action">
            <div class="input-group col-md-12 col-lg-12 col-xs-12 col-sm-12">
                <input class="col-md-12 col-lg-12 col-xs-12 col-sm-12 btn btn-primary" id="site_button" type="submit"
                       value="Đăng nhập"/>
            </div>
        </div>


        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 bottom-wrapper">
            <div class="row">
            <div class="signin col-md-6 col-lg-6 col-xs-6 col-sm-6">
                <img src="../../images/register/signin-green.png"/>
                <?php $tail = empty($mac_address)?'':('&mac_address='.$mac_address) ?>
                <?php $tail .= empty($publisher_id)?'':('&publisher_id='.$publisher_id) ?>
                <?php $tail .= empty($source_url)?'':('&source_url='.$source_url) ?>
                <a href="<?php echo base_url() . 'register?appId='.$clientId.$tail ?>">Đăng ký</a>
            </div>
            <div class="forgot-password col-md-6 col-lg-6 col-xs-6 col-sm-6">
                <img src="../../images/register/forgot-green.png"/>

                <?php if($is_mobile){ ?>
                    <a href="<?php echo base_url().'login/reset_sms?appId='.$clientId.$tail ?>">Quên mật khẩu</a>
                <?php }else{ ?>
                    <a href="<?php echo base_url() . 'login/resetbyemail' ?>">Quên mật khẩu</a>
                <?php } ?>
            </div>
            </div>
        </div>
        
    </div>
	</form>
	<?php	//echo "khong co redis";
		}
	?>
    <div class="footer-view" style="height: 200px">

    </div>
</body>

</html>
