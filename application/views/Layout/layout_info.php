<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>MegaV <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
	<link rel="shortcut icon" type="image/png" href="<?php echo base_url() . '/images/megav-favicon.ico' ?>"/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/bootstrap.min.css?v=" . VERSION_WEB ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/sidebar.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/content.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/caroulsel-reponsive-style.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/layout_login.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/layout_info.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/slick.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/metisMenu.min.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/js/libs/jcrop/jquery.Jcrop.min.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/element/fonts.css?v=" . VERSION_WEB; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/element/style.css?v=" . VERSION_WEB; ?>'/>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery-1.11.3.min.js?v=" . VERSION_WEB; ?>'></script>
</head>
<body class="<?php echo isset($contentIframe) ? 'bg-trans' : ''; ?>">

<div id="wrapper" class="">

 <?php if(isset($nav_left)) echo $nav_left; ?>
 
<div id="page-content-wrapper">
		<div class="header-page">
			<nav class="navbar navbar-default">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="navbar-header">
								<?php if(isset($user_info['userID'])): ; ?>
								<div class="navbar-home" href="#menu-toggle" id="menu-toggle">
									<span class="sr-only"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</div>
								<?php endif; ?>
								<div class="top-info text-center">
									<a href=""><img class="top-logo-user" src="/../images/logoMegaV-loginpage.png"></a>
								</div>
							</div>
							
							<!--div class="logo-info"></div-->
							
							<div class="menu-nav">
							
								<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
									<div class='close' style="display:none"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</nav>
		</div>
		
		<div id="mg-content" class="col-md-12 col-lg-12 panel-2 col-xs-12 col-sm-12 mg-content <?php echo isset($contentIframe) ? 'toggled' : ''; ?>">
			 <?php echo isset($data['content']) ? $data['content'] : ''; ?>
		</div>
		
		<div id="lg-content" class="col-md-12 col-lg-12 panel-2 col-xs-12 col-sm-12 lg-content">
			 <?php if(isset($contentIframe)): ?>
				<iframe id="IframeId-info" src="<?php echo $contentIframe; ?>" ></iframe>
				<script>
					$(document).ready(function(){
						var height = $( window ).height();
						$('#IframeId-info').css('height', height);
					});
				</script>
			 <?php endif; ?>
		</div>
				
	</div>
</div>

<div id="loader-bg" style="display:none">
	<img class="loading" src="../../../images/ajax_loader_blue_64.gif">
</div>


<!-- Modal -->
  <div class="modal fade" id="myModalChooseAvatar" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content" style="border-radius: 0px;">
        <div class="modal-header">
          <button type="button" class="close_new" data-dismiss="modal">×</button>
          <h4>Cập nhật avatar</h4>
        </div>
        <div class="modal-body text-center">
        	<p style="color: #000;font-size: 14px;" class="mess_move hidden_load">Di chuyển khung hình đến vùng ảnh bạn thích và nhấn "Cập nhật" để hoàn tất cập nhât avatar.</p>
        	
        	<div class="can_img" style="width: 500px;height: 400px;overflow: scroll;margin: 0 auto;">
        		<div class="mess_choose">
        			<i class="fa fa-picture-o" aria-hidden="true"></i>
        			<p>Chọn ảnh từ máy tính của bạn.</p>
        		</div>
				<img src="<?php echo base_url().'images/img.png' ?>" data-src="" class="view_edit_img hidden_load" id="target_crop"/>
			</div>
			<div class="scroll_step step_left hidden_load"><a href="javascript:void(0);" class="block_step"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
			<div class="scroll_step step_right hidden_load"><a href="javascript:void(0);" class="block_step"><i class="fa fa-minus" aria-hidden="true"></i></a></div>
			<input type="hidden" id="scalex" value="500" />
			<input type="hidden" id="scaley" value="400" />
        </div>
        <div class="modal-footer" style="text-align: center;">

        	<p class="error_mess text-center"></p>
        	<form action="/acc_manage/cropAction" method="post">
        		<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
	    	    <input type="hidden" id="url_img" name="url_img" />
	    	    <input type="hidden" id="jcrop_x" name="jcrop_x" />
	            <input type="hidden" id="jcrop_y" name="jcrop_y" />
	            <input type="hidden" id="jcrop_w" name="jcrop_w" />
	            <input type="hidden" id="jcrop_h" name="jcrop_h" />
				<input type="submit" name="ok" value="Cập nhật" class="btn btn-success action_crop hidden_load" />
			

        	<p class="loadding_upload_img hidden_load"><img src="<?php echo base_url().'images/loader.gif'; ?>" /> Đang tải lên</p>
        	<span class="btn default btn-file">
				<span class="fileinput-exists">Chọn ảnh </span>
				<input type="file" name="img_avatar_choose" class="img_avatar_choose">
			</span>
          <button type="button" class="btn btn-default dismiss_choose_avatar">Hủy bỏ</button>
          </form>
        </div>
      </div>
      
    </div>
  </div> 


<!-- Modal -->
  <div class="modal fade" id="myModalMesager" role="dialog">
    <div class="modal-dialog modal-md">
    
      <!-- Modal content-->
      <div class="modal-content" style="border-radius: 0px;">
        <div class="modal-header">
          <button type="button" class="close_new" data-dismiss="modal">×</button>
          <h4>Thông báo:</h4>
        </div>
        <div class="modal-body">
        	<p class="title-mess"></p>
        	<p class="body-mess"></p>
        	<p class="footer-mess"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
          </form>
        </div>
      </div>
      
    </div>
  </div> 
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.min.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/datepicker.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/bootstrap-datetimepicker.min.css"; ?>'/>

    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/js/libs/fancybox/jquery.fancybox.css"; ?>'/>
    <script type="text/javascript" language="javascript" src="<?php echo base_url() . "js/node_modules/socket.io-client/dist/socket.io.js" ?>"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url() . "assets/js/libs/fancybox/jquery.fancybox.pack.js" ?>"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url() . "assets/js/libs/jcrop/jquery.Jcrop.min.js" ?>"></script>

    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/jquery.flexisel.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/slick.min.js?v=" . VERSION_WEB; ?>'></script>
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/bootstrap.min.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/bootstrap-datepicker.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/metisMenu.min.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery.cookie.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/datepicker.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/cmnd.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/script.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/ajax-info.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/notify.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript">
	window._sbzq||function(e){e._sbzq=[];var t=e._sbzq;t.push(["_setAccount",18144]);var n=e.location.protocol=="https:"?"https:":"http:";var r=document.createElement("script");r.type="text/javascript";r.async=true;r.src=n+"//static.subiz.com/public/js/loader.js?v=";var i=document.getElementsByTagName("script")[0];i.parentNode.insertBefore(r,i)}(window);
	</script>
	
</body>
</html>