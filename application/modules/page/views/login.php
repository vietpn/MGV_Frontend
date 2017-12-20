<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>MegaV <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url() . '/images/megav-favicon.ico' ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() . '/css/bootstrap/bootstrap.min.css'; ?> "/>
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() . '/css/bootstrap/login-page.css'; ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() . '/css/caroulsel-reponsive-style.css'; ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() . 'assets/css/element/style_login.css'; ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() . 'assets/css/element/fonts.css'; ?>"/>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery-1.11.3.min.js"; ?>'></script>
   
</head>
<body style="height: 100%">

<div id="wrapper" class="">
	<div id="page-content-wrapper" class="bg-login">
		<div class="header-page">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="navbar-header">
							<div class="lienhe text-center"><i class="fa fa-phone" style="margin-right: 5px;"></i>HotLine: <span>19006470</span></div>
						</div>
						<div class="menu-nav">
							<?php if(isset($user_info['Id'])) : ?>
								<div class="col-sm-12 col-xs-12 hidden-md hidden-lg text-center">
	                                 <image style="height: 50px;" src="/../../images/avatar-default.png" />
								</div>
								<image class="logo-user hidden-xs hidden-sm" src="<?php echo (isset($this->session_memcached->userdata['info_user']['avatar_url']) && $this->session_memcached->userdata['info_user']['avatar_url'] !='') ? $this->session_memcached->userdata['info_user']['avatar_url'] : '/../../images/avatar-default.png'; ?>" style="border-radius: 50%;">
								<div class="header-info-user hidden-sm hidden-xs">
									<div class="user-name"><?php echo $user_info['mobileNo'] ; ?></div>
									<div class="user-balance">Khả dụng: </div><div style="color:#48b14a; float: left; margin-left: 10px;"><?php echo number_format($balance); ?></div>
								</div>
								<div class="col-sm-12 col-xs-12 hidden-md hidden-lg text-center hidden-fix-profile">
	                                 	<div class="user-name"><?php echo $user_info['mobileNo'] ; ?></div>
										<p>
											<span style="color:#fff;">Khả dụng: </span><span style="color:#48b14a;margin-left: 5px;"><?php echo number_format($balance); ?></span>
										</p>
								</div>
							<?php else: ?>
								<div id="btn-register" class="btn menu-nav-item">Đăng ký</div>
								<div id="btn-login" class="btn menu-nav-item">Đăng nhập</div>
							<?php endif; ?>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<?php if(!isset($user_info['Id'])) : ?>
		<div class="form-login" style="<?php echo isset($showlogin) ? "" : "visibility :hidden" ?>">
			<iframe id="IframeId-login" src="<?php echo base_url() . 'login' ?>" style="min-height: 401px;"></iframe>
		</div>
            <script type="text/javascript">
                $(function() {
                    setInterval(function() {
                        var currentHeight = $('#IframeId-login').css('height');
                        var contentHeight = document.getElementById('IframeId-login').contentWindow.document.body.scrollHeight + 'px';
                        if (currentHeight != contentHeight) {
                            $('#IframeId-login').css('height', contentHeight);
                        }
					}, 1000);
                });
            </script>
		<div class="form-register" style="display:none">
			<iframe id="IframeId-register" src="<?php echo base_url() . 'register' ?>" style="min-height: 500px;"></iframe> 
		</div>
			<script type="text/javascript">
                
				$(function() {
                    setInterval(function() {
						if($('.form-register').css('display') != 'none')
						{
							var currentHeight = $('#IframeId-register').css('height');
							var contentHeight = document.getElementById('IframeId-register').contentWindow.document.body.scrollHeight + 'px';
							if (currentHeight != contentHeight) {
								$('#IframeId-register').css('height', contentHeight);
							}
						}
                    }, 1000);
                });
				
            </script>
		<?php endif; ?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="clearfix"></div>
				
				<div class="content-login">
					<?php if(isset($user_info['Id'])) : ?>
						<div class="arrow-left">
							<a href="/transaction_manage"><div class="arrow"></div></a>
						</div>
					<?php endif; ?>
						<div class="content-login-middle">
							<div class="content-login-inner">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="logoMegaV">
										<img src="/../images/logoMegaV-loginpage.png" style="width: 100%;">
									</div>
								</div>
								<div class="context-login text-center col-lg-6 col-md-6 col-lg-offset-3 col-md-offset-3 col-sm-12 col-xs-12">
										<p>
											Ví điện tử MegaV là sản phẩm của Công ty Cổ phần Thanh toán điện tử VNPT (VNPT EPAY), được cấp phép theo Quyết định số 21/GP-NHNN ngày 22/01/2016 của Ngân hàng nhà nước Việt Nam về Giấy phép hoạt động cung ứng dịch vụ trung gian thanh toán.
											<br>
											<br>
											Với các tính năng nạp tiền, chuyển tiền, rút tiền và thanh toán linh hoạt, MegaV hướng tới xây dựng mô hình Ví điện tử cùng hệ sinh thái dịch vụ đa dạng, tiện lợi cho người dùng.
										</p>
								
								</div>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
		
</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="margin-top: 50px;">
    <div class="modal-content" style="border-radius: 0px;">
      <div class="modal-header" style="background-color: rgb(72, 177, 74); color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <!--h4 class="modal-title" id="myModalLabel">Điều khoản sử dụng</h4-->
		<p style="text-align: center; margin: 0px;"><strong>CHÍNH SÁCH QUYỀN RIÊNG TƯ KHI SỬ DỤNG MEGAV</strong></p>
      </div>
      <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
			

			<p>&nbsp;</p>

			<p><strong>ĐIỀU 1. CÁC QUYỀN RIÊNG TƯ CỦA NGƯỜI SỬ DỤNG</strong></p>

			<ol>
				<li>Chính sách quyền riêng tư nhằm mô tả các quyền riêng tư của người sử dụng liên quan đến việc EPAY thu thập, xử lý, sử dụng, lưu trữ, chia sẻ và bảo vệ thông tin cá nhân của Khách hàng khi sử dụng MegaV.</li>
				<li>Thông tin cá nhân được hiểu là thông tin người sử dụng và tất các các thông tin khác liên quan đến Người sử dụng, được EPAY thu thập, nghi nhận, xử lý, sử dụng, lưu trữ, chia sẻ trong quá trình Người sử dụng truy cập và sử dụng MegaV. Thuật ngữ “Thông tin cá nhân” được sử dụng theo Chính sách này là một và được dùng cho cả người sử dụng là cá nhân và tổ chức.</li>
				<li>Ch&iacute;nh s&aacute;ch Quyền ri&ecirc;ng tư nhằm &aacute;p dụng cho MegaV v&agrave; tất cả c&aacute;c phương tiện, c&ocirc;ng cụ, ứng dụng, dịch vụ li&ecirc;n quan đến việc cung ứng MegaV, bất kể kh&aacute;ch h&agrave;ng sử dụng phương thức n&agrave;o để truy cập hoặc sử dụng.</li>
				<li>Kh&aacute;ch h&agrave;ng được mặc định l&agrave; đồng &yacute; v&agrave; chấp nhận ch&iacute;nh s&aacute;ch quyền ri&ecirc;ng tư khi kh&aacute;ch h&agrave;ng đăng k&yacute; để truy cập v&agrave; sử dụng MegaV. Đ&acirc;y l&agrave; ch&iacute;nh s&aacute;ch điều chỉnh việc sử dụng MegaV của người sử dụng trừ trường hợp c&oacute; thỏa thuận cụ thể kh&aacute;c.</li>
				<li>EPAY c&oacute; thể điểu chỉnh Ch&iacute;nh s&aacute;ch quyền ri&ecirc;ng tư n&agrave;y v&agrave;o bất kỳ thời điểm n&agrave;o th&ocirc;ng qua việc đăng tải ch&iacute;nh s&aacute;ch được điều chỉnh tr&ecirc;n website của EPAY. Bản điều chỉnh c&oacute; hiệu lực kể từ thời điểm được đăng tải.</li>
			</ol>

			<p><strong>ĐIỀU 2. THU THẬP TH&Ocirc;NG TIN</strong></p>

			<ol>
				<li>Để phục vụ cho việc cung ứng dịch vụ MegaV, ph&acirc;n t&iacute;ch th&ocirc;ng tin phục vụ việc bảo mật v&agrave; giữ an to&agrave;n cho hệ thống, tạo cơ sở x&aacute;c thực th&ocirc;ng tin, chăm s&oacute;c kh&aacute;ch h&agrave;ng, cải thiện dịch vụ, EPAY được quyền thu thập c&aacute;c loại th&ocirc;ng tin c&aacute; nh&acirc;n từ c&aacute;c nguồn kh&aacute;c nhau khi Kh&aacute;ch h&agrave;ng sử dụng MegaV.</li>
				<li>Th&ocirc;ng tin được thu thập tự động:
				<ul>
					<li>Khi Kh&aacute;ch h&agrave;ng sử dụng MegaV, EPAY thu thập những th&ocirc;ng tin được gửi đến EPAY từ m&aacute;y t&iacute;nh, điện thoại của Kh&aacute;ch h&agrave;ng hoặc c&aacute;c thiết bị được Kh&aacute;ch h&agrave;ng sử dụng để truy cập. C&aacute;c th&ocirc;ng tin n&agrave;y, bao gồm nhưng kh&ocirc;ng giới hạn, c&aacute;c nội dung sau: Dữ liệu về c&aacute;c trang Kh&aacute;ch h&agrave;ng truy cập, địa chỉ IP của m&aacute;y t&iacute;nh, ID nhận dạng thiết bị, loại thiết bị, th&ocirc;ng tin m&aacute;y t&iacute;nh v&agrave; thiết bị mạng, số liệu thống k&ecirc; kiểm thị của trang truy cập v&agrave; c&aacute;c dữ liệu cơ bản kh&aacute;c.</li>
					<li>Khi bạn truy cập v&agrave; sử dụng MegaV, ch&uacute;ng t&ocirc;i (hoặc c&aacute;c c&ocirc;ng cụ theo d&otilde;i hoặc thống k&ecirc; hoạt động của website do đối t&aacute;c cung cấp) sẽ đặt một số cơ sở dữ liệu gọi chung l&agrave; Cookies (th&ocirc;ng qua chương tr&igrave;nh định vị người d&ugrave;ng; những chương tr&igrave;nh/tr&igrave;nh duyệt ứng dụng tương tự kh&aacute;c như: fflash, cookies, pixcel tags&hellip;) l&ecirc;n m&aacute;y t&iacute;nh hoặc c&aacute;c thiết bị của Kh&aacute;ch h&agrave;ng. Một trong số những Cookies n&agrave;y c&oacute; thể tồn tại l&acirc;u để thuận tiện cho Kh&aacute;ch h&agrave;ng trong qu&aacute; tr&igrave;nh sử dụng, v&iacute; dụ như: Lưu email của kh&aacute;ch h&agrave;ng trong trang đăng nhập để bạn kh&ocirc;ng phải nhập lại&hellip;</li>
					<li>EPAY sử dụng những c&ocirc;ng nghệ n&oacute;i tr&ecirc;n với mục đ&iacute;ch nhận diện Kh&aacute;ch h&agrave;ng như một Người sử dụng của EPAY; phục vụ cho việc đ&aacute;p ứng dịch vụ MegaV, nội dung v&agrave; quảng c&aacute;o tương ứng cho từng đối tượng kh&aacute;ch h&agrave;ng kh&aacute;c nhau; đo lường hiệu ứng tiếp thị, quảng c&aacute;o; hỗ trợ đảm bảo an ninh, bảo mật t&agrave;i khoản của kh&aacute;ch h&agrave;ng; hạn chế rủi ro v&agrave; ngăn ngừa gian lận; n&acirc;ng cao độ tin cậy v&agrave; an to&agrave;n trong hoạt động kết nối giữa kh&aacute;ch h&agrave;ng v&agrave; EPAY.</li>
					<li>Bạn c&oacute; quyền từ chối nhận những Cookies n&agrave;y nếu những tr&igrave;nh duyệt của kh&aacute;ch h&agrave;ng c&oacute; chức năng từ chối đ&oacute;, trừ khi c&aacute;c Cookies được EPAY d&ugrave;ng để ngăn chặn gian lận v&agrave; đảm bảo an to&aacute;n đối với c&aacute;c website của EPAY v&agrave; dịch vụ MegaV.</li>
				</ul>
				</li>
				<li>Th&ocirc;ng tin được khai b&aacute;o từ Kh&aacute;ch h&agrave;ng:
				<ul>
					<li>EPAY thu thập v&agrave; lưu trữ bất kỳ th&ocirc;ng tin n&agrave;o Kh&aacute;ch h&agrave;ng cung cấp cho EPAY khi sử dụng dịch vụ MegaV, bao gồm th&ocirc;ng tin Kh&aacute;ch h&agrave;ng cung cấp tr&ecirc;n mẫu đăng k&yacute; v&agrave; được cập nhật trong qu&aacute; tr&igrave;nh sử dụng MegaV; c&aacute;c th&ocirc;ng tin về c&aacute;c giao dịch v&agrave; hoạt động của Kh&aacute;ch h&agrave;ng; c&aacute;c th&ocirc;ng tin t&agrave;i ch&iacute;nh như c&aacute;c t&agrave;i khoản ng&acirc;n h&agrave;ng v&agrave;/hoặc số thẻ t&iacute;n dụng m&agrave; Kh&aacute;ch h&agrave;ng sử dụng hoặc li&ecirc;n kết với MegaV.</li>
					<li>EPAY cũng thu thập những th&ocirc;ng tin từ hoặc về Kh&aacute;ch h&agrave;ng th&ocirc;ng qua nhiều nguồn kh&aacute;c nhau, chẳng hạn như th&ocirc;ng qua c&aacute;c li&ecirc;n lạc giữa Kh&aacute;ch h&agrave;ng v&agrave; EPAY; C&aacute;c th&ocirc;ng tin Kh&aacute;ch h&agrave;ng phản hồi cho c&aacute;c cuộc khảo s&aacute;t; C&aacute;c th&ocirc;ng tin trong qu&aacute; tr&igrave;nh Kh&aacute;ch h&agrave;ng tham gia v&agrave;o c&aacute;c cuộc trao đổi, thảo luận, giải quyết tranh chấp hoặc c&aacute;c th&ocirc;ng tin ph&ugrave; hợp kh&aacute;c được Kh&aacute;ch h&agrave;ng cung cấp li&ecirc;n quan đến việc sử dụng dịch vụ MegaV.</li>
					<li>Để thực hiện v&agrave; đảm bảo chất lượng, hướng dẫn MegaV, EPAY c&oacute; thể gi&aacute;m s&aacute;t hoặc ghi &acirc;m c&aacute;c cuộc đ&agrave;m thoại với Kh&aacute;ch h&agrave;ng hoặc bất kỳ ai nh&acirc;n danh Kh&aacute;ch h&agrave;ng. Khi giao tiếp, kết nối với EPAY, Kh&aacute;ch h&agrave;ng nhận thức rằng c&aacute;c quy tr&igrave;nh giao tiếp, kết nối đ&oacute; c&oacute; thể được gi&aacute;m s&aacute;t, ghi &acirc;m m&agrave; kh&ocirc;ng c&oacute; th&ecirc;m bất kỳ lưu &yacute; hay khuyến c&aacute;o n&agrave;o ngo&agrave;i c&aacute;c quy định tại Ch&iacute;nh s&aacute;ch n&agrave;y.</li>
					<li>Kh&aacute;ch h&agrave;ng c&oacute; thể thay đổi Th&ocirc;ng tin Người sử dụng bất kỳ l&uacute;c n&agrave;o bằng c&aacute;ch sử dụng chức năng tương ứng. Tuy nhi&ecirc;n, EPAY sẽ lưu lại những th&ocirc;ng tin bị thay đổi để chống lại c&aacute;c h&agrave;nh vi x&oacute;a dấu vết gian lận.</li>
				</ul>
				</li>
				<li>Th&ocirc;ng tin từ c&aacute;c nguồn kh&aacute;c:
				<ul>
					<li>
					<p>Kh&aacute;ch h&agrave;ng c&oacute; thể cung cấp th&ocirc;ng tin th&ocirc;ng qua việc truy cập th&ocirc;ng tin c&aacute; nh&acirc;n được lưu trữ bởi b&ecirc;n thứ ba, chẳng hạn như b&ecirc;n cung cấp dịch vụ x&aacute;c thực th&ocirc;ng tin, c&aacute;c nh&agrave; cung cấp h&agrave;ng h&oacute;a/dịch vụ, c&aacute;c trang th&ocirc;ng tin điện tử, mạng x&atilde; hội (v&iacute; dụ: Facebook&hellip;). Từ đ&oacute;, EPAY c&oacute; thể nhận được c&aacute;c th&ocirc;ng tin được kiểm so&aacute;t từ c&aacute;c b&ecirc;n thứ ba n&agrave;y, v&agrave; Kh&aacute;ch h&agrave;ng đồng &yacute; rằng EPAY c&oacute; thể thu thập, lưu trữ v&agrave; sử dụng c&aacute;c th&ocirc;ng tin n&agrave;y căn cứ theo Ch&iacute;nh s&aacute;ch quyền ri&ecirc;ng tư.</p>
					</li>
				</ul>
				</li>
				<li>Th&ocirc;ng qua việc x&aacute;c thực v&agrave; ph&aacute;t hiện gian lận: Để hỗ trợ trong việc bảo vệ Kh&aacute;ch h&agrave;ng khỏi những h&agrave;nh vi gian lận v&agrave; sử dụng sai mục đ&iacute;ch c&aacute;c th&ocirc;ng tin của Kh&aacute;ch h&agrave;ng, EPAY c&oacute; thể thu thập th&ocirc;ng tin về Kh&aacute;ch h&agrave;ng v&agrave; c&aacute;c tương t&aacute;c của Kh&aacute;ch h&agrave;ng đối với dịch vụ MegaV. EPAY c&oacute; thể đ&aacute;nh gi&aacute; m&aacute;y t&iacute;nh, điện thoại v&agrave; c&aacute;c thiết bị kh&aacute;c m&agrave; Kh&aacute;ch h&agrave;ng d&ugrave;ng để truy cập để nhận dạng bất kỳ những phần mềm hay hoạt động x&acirc;m phạm n&agrave;o.</li>
				<li>Th&ocirc;ng qua việc sử dụng thiết bị di động: EPAY c&oacute; thể đề nghị Kh&aacute;ch h&agrave;ng sử dụng thiết bị di động hoặc ứng dụng, website được tối ưu h&oacute;a ph&ugrave; hợp. C&aacute;c quy định của ch&iacute;nh s&aacute;ch n&agrave;y được &aacute;p dụng đối với việc truy cập, sử dụng c&aacute;c thiết bị, ứng dụng, website n&agrave;y.</li>
				<li>Khi Kh&aacute;ch h&agrave;ng tải v&agrave; sử dụng c&aacute;c ứng dụng hoặc khi truy cập những website n&oacute;i tr&ecirc;n của EPAY, EPAY c&oacute; thể nhận được c&aacute;c th&ocirc;ng tin về định vị, về thiết bị của Kh&aacute;ch h&agrave;ng. EPAY c&oacute; thể sử dụng v&agrave; căn cứ c&aacute;c th&ocirc;ng tin n&agrave;y để cung cấp dịch vụ đến Kh&aacute;ch h&agrave;ng (V&iacute; dụ như những nội dung về quảng c&aacute;o, kết quả t&igrave;m kiếm hay những nội dung kh&aacute;c). Hầu hết c&aacute;c thiết bị di động đều c&oacute; chức năng cho ph&eacute;p Kh&aacute;ch h&agrave;ng kiểm so&aacute;t hoặc v&ocirc; hiệu h&oacute;a chức năng định vị, hoặc trong trường hợp cần thiết, Kh&aacute;ch h&agrave;ng c&oacute; thể li&ecirc;n hệ với nh&agrave; cung cấp để từ đ&oacute; kiểm so&aacute;t việc th&ocirc;ng tin cho b&ecirc;n thứ ba c&aacute;c th&ocirc;ng tin n&agrave;y.</li>
			</ol>

			<p><strong>ĐIỀU 3: SỬ DỤNG TH&Ocirc;NG TIN</strong></p>

			<p>EPAY sử dụng th&ocirc;ng tin của Kh&aacute;ch h&agrave;ng để:</p>

			<ol>
				<li>Cung cấp dịch vụ MegaV v&agrave; hỗ trợ kh&aacute;ch h&agrave;ng.</li>
				<li>Xứ l&yacute; giao dịch; gửi th&ocirc;ng b&aacute;o về c&aacute;c giao dịch của Kh&aacute;ch h&agrave;ng.</li>
				<li>X&aacute;c thực th&ocirc;ng tin Người sử dụng.</li>
				<li>Giải quyết sự cố, tranh chấp, thu ph&iacute;.</li>
				<li>Quản l&yacute; rủi ro, tra so&aacute;t, ngăn chặn hoặc khắc phục c&aacute;c h&agrave;nh vi gian lận hay những hoạt động vi phạm, bất hợp ph&aacute;p kh&aacute;c</li>
				<li>Tra so&aacute;t, ngăn chặn hoặc khắc phục những vi phạm của Kh&aacute;ch h&agrave;ng đối với ch&iacute;nh s&aacute;ch, quy định hoặc thỏa thuận với EPAY.</li>
				<li>Cải thiện dịch vụ MegaV th&ocirc;ng qua th&oacute;i quen sử dụng dịch vụ của Kh&aacute;ch h&agrave;ng.</li>
				<li>Đo lường, đ&aacute;nh gi&aacute; hiệu ứng của dịch vụ MegaV; Cải thiện nội dung, thiết kế dịch vụ MegaV.</li>
				<li>Quản l&yacute; v&agrave; bảo vệ hạ tầng kỹ thuật th&ocirc;ng tin của EPAY.</li>
				<li>Cung cấp c&aacute;c mục ti&ecirc;u, đề nghị quảng c&aacute;o, tiếp thị, c&aacute;c th&ocirc;ng b&aacute;o cập nhật dịch vụ dựa tr&ecirc;n sở th&iacute;ch, thị hiếu của Kh&aacute;ch h&agrave;ng.</li>
				<li>Kiểm tra khả năng thanh to&aacute;n, đối chiếu kiểm tra t&iacute;nh chuẩn x&aacute;c của th&ocirc;ng tin v&agrave; x&aacute;c thực th&ocirc;ng tin với b&ecirc;n thứ ba.</li>
			</ol>

			<p><strong>ĐIỀU 4: TIẾP THỊ (MARKETING)</strong></p>

			<ol>
				<li>EPAY kh&ocirc;ng b&aacute;n hoặc cho b&ecirc;n thứ ba thu&ecirc; cho nhu cầu tiếp thị khi chưa c&oacute; sự đồng &yacute; r&otilde; r&agrave;ng của Kh&aacute;ch h&agrave;ng.</li>
				<li>EPAY c&oacute; thể kết hợp c&aacute;c th&ocirc;ng tin của Kh&aacute;ch h&agrave;ng c&ugrave;ng với c&aacute;c th&ocirc;ng tin EPAY thu thập được từ những nguồn hợp t&aacute;c kh&aacute;c nhau, sử dụng để quảng c&aacute;o v&agrave; cải thiện dịch vụ MegaV. Trường hợp Kh&aacute;ch h&agrave;ng kh&ocirc;ng muốn nhận những th&ocirc;ng tin quảng c&aacute;o từ EPAY, cũng như kh&ocirc;ng muốn tham gia bất kỳ chương tr&igrave;nh quảng c&aacute;o d&agrave;nh cho kh&aacute;ch h&agrave;ng n&agrave;o. Kh&aacute;ch h&agrave;ng c&oacute; thể l&agrave;m theo những hướng dẫn được EPAY cung cấp để từ chối nhận v&agrave; tham gia c&aacute;c th&ocirc;ng tin n&agrave;y.</li>
			</ol>

			<p><strong>ĐIỀU 5: BẢO VỆ V&Agrave; LƯU TRỮ TH&Ocirc;NG TIN</strong></p>

			<ol>
				<li>Th&ocirc;ng qua ch&iacute;nh s&aacute;ch quyền ri&ecirc;ng tư n&agrave;y, EPAY sẽ sử dụng thuật ngữ &ldquo;Th&ocirc;ng tin c&aacute; nh&acirc;n&rdquo; để m&ocirc; tả những th&ocirc;ng tin li&ecirc;n quan đến Người sử dụng cụ thể, v&agrave; được sử dụng để nhận biết Người sử dụng đ&oacute;. EPAY kh&ocirc;ng xem x&eacute;t, thừa nhận c&aacute;c th&ocirc;ng tin nặc danh kh&aacute;c kh&ocirc;ng c&oacute; yếu tố nhận dạng Người sử dụng.</li>
				<li>EPAY lưu giữ v&agrave; bảo mật th&ocirc;ng tin c&aacute; nh&acirc;n của Kh&aacute;ch h&agrave;ng tại c&aacute;c m&aacute;y chủ hệ thống v&agrave; được bảo đảm &nbsp;an to&agrave;n bằng hệ thống tường lửa, đồng thời thiết lập v&agrave; bảo vệ c&aacute;c kết nối trao đổi th&ocirc;ng tin, giao dịch. To&agrave;n bộ th&ocirc;ng tin của Người sử dụng khi truyền đi tr&ecirc;n mạng hay lưu trữ đều được m&atilde; h&oacute;a, &aacute;p dụng cơ chế bảo mệt đường truyền, chữ k&yacute; điện tử.</li>
				<li>Để bảo vệ th&ocirc;ng tin c&aacute; nh&acirc;n, hạn chế những rủ ro mất m&aacute;t, ch&iacute;nh sửa, tiết lộ, sử dụng sai mục đich, truy cập kh&ocirc;ng đ&uacute;ng thẩm quyền, EPAY sử dụng những c&ocirc;ng nghệ phần cứng ti&ecirc;n tiến nhất tr&ecirc;n thế giới của c&aacute;c nh&agrave; &nbsp;cung cấp l&agrave; đối t&aacute;c của EPAY để x&acirc;y dựng một hệ thống hạ tầng theo chuẩn quốc tế v&agrave; đủ sức phục vụ dịch vụ MegaV với y&ecirc;u cầu cao nhất. Hằng năm, hệ thống trang thiết bị của EPAY đều được đ&aacute;nh gi&aacute; v&agrave; tiến h&agrave;nh n&acirc;ng cấp nếu cần thiết.</li>
			</ol>

			<p><strong>ĐIỀU 6. CHIA SẺ TH&Ocirc;NG TIN C&Aacute; NH&Acirc;N VỚI NGƯỜI SỬ DỤNG KH&Aacute;C.</strong></p>

			<ol>
				<li>Khi xử l&yacute; c&aacute;c giao dịch, EPAY c&oacute; thể cung cấp cho c&aacute;c b&ecirc;n tham gia giao dịch c&aacute;c th&ocirc;ng tin cần thiết của Người sử dụng, v&iacute; dụ như t&ecirc;n người sử dụng, ID T&agrave;i khoản, chi tiết li&ecirc;n lạc, địa chỉ giao h&agrave;ng, gi&aacute; trị giao dịch hoặc c&aacute;c th&ocirc;ng tin cần thiết kh&aacute;c để x&aacute;c thực th&ocirc;ng tin, n&acirc;ng cao độ tin cậy v&agrave; an to&agrave;n của giao dịch. B&ecirc;n nhận được th&ocirc;ng tin kh&ocirc;ng được ph&eacute;p sử dụng th&ocirc;ng tin nhận được cho những mục đ&iacute;ch kh&ocirc;ng li&ecirc;n quan đến việc thực hiện giao dịch, trừ khi c&oacute; sự đồng &yacute; của b&ecirc;n cung cấp th&ocirc;ng tin. Mọi trao đổi, li&ecirc;n lạc mang t&iacute;nh đe dọa giữa c&aacute;c b&ecirc;n được xem l&agrave; h&agrave;nh vi vi phạm ch&iacute;nh s&aacute;ch n&agrave;y v&agrave; thỏa thuận người sử dụng.</li>
				<li>Khi c&oacute; một người thanh to&aacute;n cho Kh&aacute;ch h&agrave;ng v&agrave; nhập địa chỉ email hoặc số điện thoai của Kh&aacute;ch h&agrave;ng, EPAY sẽ cung cấp cho người đ&oacute; t&ecirc;n đăng k&yacute; của Kh&aacute;ch h&agrave;ng để họ c&oacute; thể x&aacute;c thực việc thanh to&aacute;n đến đ&uacute;ng t&agrave;i khoản.</li>
				<li>EPAY cho ph&eacute;p b&ecirc;n thứ ba, bao gồm cả Người b&aacute;n, c&oacute; quyền chấp nhận thanh to&aacute;n của Kh&aacute;ch h&agrave;ng hoặc gửi y&ecirc;u cầu thanh to&aacute;n đến Kh&aacute;ch h&agrave;ng th&ocirc;ng qua việc sử dụng dịch vụ MegaV. Để thực hiện quyền n&agrave;y, b&ecirc;n thứ ba c&oacute; thể chia sẻ th&ocirc;ng tin của Kh&aacute;ch h&agrave;ng cho ch&uacute;ng t&ocirc;i (V&iacute; dụ địa chỉ email hay số điện thoại của kh&aacute;ch h&agrave;ng), để EPAY th&ocirc;ng b&aacute;o y&ecirc;u cầu thanh to&aacute;n cho Kh&aacute;ch h&agrave;ng hoặc th&ocirc;ng b&aacute;o khi Kh&aacute;ch h&agrave;ng thanh to&aacute;n cho Người b&aacute;n/B&ecirc;n thứ 3</li>
				<li>EPAY sử dụng c&aacute;c th&ocirc;ng tin để x&aacute;c nhận Kh&aacute;ch h&agrave;ng l&agrave; Người sử dụng v&agrave; được ph&eacute;p sử dụng dịch vụ MegaV hoặc để gửi đến Kh&aacute;ch h&agrave;ng th&ocirc;ng b&aacute;o về t&igrave;nh trạng giao dịch. Ngo&agrave;i ra, EPAY cũng sẽ sử dụng th&ocirc;ng tin n&agrave;y để x&aacute;c nhận với b&ecirc;n thứ ba về tư c&aacute;ch Người sử dụng của Kh&aacute;ch h&agrave;ng theo đề nghị của Kh&aacute;ch h&agrave;ng.</li>
				<li>Kh&aacute;ch h&agrave;ng cần lưu &yacute;: Những người b&aacute;n hoặc Người sử dụng, b&ecirc;n thứ ba kh&aacute;c m&agrave; Kh&aacute;ch h&agrave;ng giao dịch, hợp t&aacute;c cũng sẽ c&oacute; những ch&iacute;nh s&aacute;ch quyền ri&ecirc;ng tư c&aacute;c b&ecirc;n n&agrave;y quy định, v&agrave; EPAY kh&ocirc;ng chịu tr&aacute;ch nhiệm cho những h&agrave;nh động n&agrave;y, bao gồm cả thực tiễn bảo vệ th&ocirc;ng tin m&agrave; c&aacute;c b&ecirc;n n&oacute;i tr&ecirc;n thực hiện, mặc d&ugrave; thảo thuận người sử dụng kh&ocirc;ng cho ph&eacute;p c&aacute;c b&ecirc;n tham gia giao dịch sử dụng những th&ocirc;ng tin của b&ecirc;n kia cho bất kỳ mục đ&iacute;ch n&agrave;o kh&aacute;c ngo&agrave;i mục đ&iacute;ch thực hiện dịch vụ MegaV. Ngo&agrave;i ra, trừ khi truy cập c&aacute;c địa chỉ website v&agrave; c&aacute;c ứng dụng ch&iacute;nh thức của EPAY, Kh&aacute;ch h&agrave;ng lưu &yacute; bảo vệ v&agrave; kh&ocirc;ng cung cấp cho b&ecirc;n thứ ba c&aacute;c th&ocirc;ng tin c&aacute; nh&acirc;n như: Số thẻ t&iacute;n dụng, mật khẩu đăng nhập V&iacute; điện tử MegaV, t&agrave;i khoản ng&acirc;n h&agrave;ng&hellip;</li>
				<li>Trừ khi c&oacute; sự đồng &yacute; của Kh&aacute;ch h&agrave;ng hoặc theo quy định về thanh to&aacute;n của ng&acirc;n h&agrave;ng, y&ecirc;u cầu của cơ quan c&oacute; thẩm quyền hoặc c&aacute;c quy định ph&aacute;p l&yacute; kh&aacute;c, EPAY sẽ kh&ocirc;ng tiết lộ số thẻ t&iacute;n dụng hoặc t&agrave;i khoản ng&acirc;n h&agrave;ng của Kh&aacute;ch h&agrave;ng đến b&ecirc;n thực hiện giao dịch với Kh&aacute;ch h&agrave;ng th&ocirc;ng qua dịch vụ MegaV.</li>
			</ol>

			<p><strong>ĐIỀU 7. CHIA SẺ TH&Ocirc;NG TIN C&Aacute; NH&Acirc;N CHO B&Ecirc;N THỨ 3</strong></p>

			<ol>
				<li>Chia sẻ cho c&aacute;c cổ đ&ocirc;ng, c&ocirc;ng ty th&agrave;nh vi&ecirc;n, đối t&aacute;c li&ecirc;n kết, hệ thống đại l&yacute; của EPAY nhằm cung cấp những sản phẩm, dịch vụ, nội dung m&agrave; thuộc phạm vi li&ecirc;n kết của c&aacute;c b&ecirc;n li&ecirc;n quan đến dịch vụ MegaV, hỗ trợ ph&aacute;t hiện v&agrave; ngăn chặn những vi phạm hoặc những h&agrave;nh vi bất hợp ph&aacute;p đối với c&aacute;c quy định, c&aacute;c ch&iacute;nh s&aacute;ch của EPAY, đồng thời đưa ra những quyết định c&oacute; li&ecirc;n quan. C&aacute;c &nbsp;b&ecirc;n thứ ba được đề cập theo đ&acirc;y chỉ sử dụng Th&ocirc;ng tin c&aacute; nh&acirc;n để gửi đến Kh&aacute;ch h&agrave;ng c&aacute;c th&ocirc;ng tin tiếp thị khi Kh&aacute;ch h&agrave;ng c&oacute; yều sử dụng c&aacute;c dịch vụ của c&aacute;c đơn vị n&agrave;y.</li>
				<li>Cung cấp, chia sẻ th&ocirc;ng tin cho t&ograve;a &aacute;n, c&aacute;c cơ quan c&oacute; thầm quyển theo quy định của ph&aacute;p luật hoặc c&aacute;c y&ecirc;u cầu ph&ugrave; hợp của b&ecirc;n thứ ba kh&aacute;c với EPAY hoặc đối với một trong c&aacute;c cổ đ&ocirc;ng/th&agrave;nh vi&ecirc;n của EPAY khi EPAY cần phải chia sẻ th&ocirc;ng tin theo quy định của ph&aacute;p luật hoặc c&aacute;c quy định li&ecirc;n quan đến hoạt động trung gian thanh to&aacute;n, hoạt động thanh to&aacute;n qua ng&acirc;n h&agrave;ng hoặc khi EPAY nhận định được rằng cần thiết phải tiết lộ Th&ocirc;ng tin c&aacute; nh&acirc;n để ngăn chặn những thiệt hại vật chất hoặc thất tho&aacute;t về t&agrave;i ch&iacute;nh, b&aacute;o c&aacute;o/th&ocirc;ng b&aacute;o về những h&agrave;nh vi đ&aacute;ng nghi ngờ, điều tra những vi phạm đối với Thỏa thuận người sử dụng.</li>
				<li>Chia sẻ th&ocirc;ng tin cho c&aacute;c b&ecirc;n thứ ba kh&aacute;c nhằm:</li>
			</ol>

			<ul>
				<li>Hỗ trợ ngăn ngừa gian lận v&agrave; quản l&yacute; rủi ro: V&iacute; dụ, nếu Kh&aacute;ch h&agrave;ng sử dụng dịch vụ MegaV th&ocirc;ng qua c&aacute;c website, đơn vị li&ecirc;n kết với EPAY để mua/nhận hoặc b&aacute;n/cung cấp h&agrave;ng h&oacute;a/dịch vụ, EPAY c&oacute; thể chia sẻ th&ocirc;ng tin V&iacute; điện tử để hỗ trợ bảo vệ V&iacute; điện tử của Kh&aacute;ch h&agrave;ng tr&aacute;nh khỏi những h&agrave;nh vi, hoạt động lừa đảo, gian lận, cảnh b&aacute;o cho Kh&aacute;ch h&agrave;ng nếu EPAY ph&aacute;t hiện những h&agrave;nh vi, hoạt động lừa đảo, gian lận đ&oacute;, đồng thời đ&aacute;nh gi&aacute; c&aacute;c rủi ro về t&agrave;i ch&iacute;nh.</li>
				<li>Nhằm nỗ lực ngăn ngừa gian lận v&agrave; quản l&yacute; rủi ro, EPAY cũng c&oacute; thể sẽ chia sẻ những th&ocirc;ng tin cần thiết đến c&aacute;c Website, đơn vị li&ecirc;n kết n&oacute;i tr&ecirc;n trong trường hợp EPAY đang &aacute;p dụng c&aacute;c biện ph&aacute;p hạn chế n&agrave;o đ&oacute; đối với V&iacute; điện tử của Kh&aacute;ch h&agrave;ng căn cứ tr&ecirc;n c&aacute;c tranh chấp, khiếu nại, ho&agrave;n tiền hay c&aacute;c trường hợp li&ecirc;n quan. Ngo&agrave;i ra, EPAY cũng c&oacute; thể chia sẻ th&ocirc;ng tin đến c&aacute;c website, đơn vị li&ecirc;n kết n&oacute;i tr&ecirc;n nhằm cho ph&eacute;p họ sử dụng cho c&aacute;c chương tr&igrave;nh đ&aacute;nh gi&aacute; người mua hoặc người b&aacute;n.</li>
				<li>Nhằm mục đ&iacute;ch hỗ trợ kh&aacute;ch h&agrave;ng, bao gồm việc hỗ trợ việc&nbsp; giao dịch của của Kh&aacute;ch h&agrave;ng, hỗ trợ giải quyết tranh chấp.</li>
				<li>Hỗ trợ vận chuyển li&ecirc;n quan đến việc sử dụng dịch vụ MegaV.</li>
				<li>Hỗ trợ b&ecirc;n thứ ba tu&acirc;n thủ quy định của ph&aacute;p luật về ph&ograve;ng, chống rửa tiền v&agrave; t&agrave;i trợ khủng bố, tu&acirc;n thủ c&aacute;c quy định ph&aacute;p luật kh&aacute;c.</li>
				<li>Phối hợp với c&aacute;c Nh&agrave; cung cấp nhằm phụ vụ cho hoạt động cung ứng dịch vụ MegaV.</li>
				<li>C&aacute;c b&ecirc;n thứ ba kh&aacute;ch khi c&oacute; sự đồng &yacute; của Kh&aacute;ch h&agrave;ng.</li>
			</ul>

			<p><strong>ĐIỀU 8. C&Aacute;CH THỨC TRUY CẬP HOẶC ĐIỂU CHỈNH TH&Ocirc;NG TIN C&Aacute; NH&Acirc;N.</strong></p>

			<p>Kh&aacute;ch h&agrave;ng c&oacute; thể kiểm tra v&agrave; điều chỉnh Th&ocirc;ng tin c&aacute; nh&acirc;n của kh&aacute;ch h&agrave;ng v&agrave;o bất kỳ l&uacute;c n&agrave;o bằng c&aacute;ch truy cập v&agrave;o MegaV v&agrave; kiểm tra hiện trạng nội dung đ&atilde; c&agrave;i đặt. Trường hợp Kh&aacute;ch h&agrave;ng đ&oacute;ng V&iacute; điện tử MegaV, EPAY sẽ đ&aacute;nh dấu đ&oacute;ng t&agrave;i khoản n&agrave;y trong dữ liệu nhưng c&oacute; thể sẽ vẫn lưu giữ th&ocirc;ng tin c&aacute; nh&acirc;n của Kh&aacute;ch h&agrave;ng trong một khoảng thời gian n&agrave;o đ&oacute; nhằm phục vụ cho việc truy thu nợ, giải quyết tranh chấp, khắc phục sự cố, hỗ trợ điều tra, ngăn ngừa rủi ro, tu&acirc;n thủ Thỏa thuận người sử dụng, hoặc nhằm thực hiện những hoạt động kh&aacute;c theo y&ecirc;u cầu của ph&aacute;p luật hoặc kh&ocirc;ng tr&aacute;i ph&aacute;p luật.</p>

			<p><strong>ĐIỀU 9. C&Aacute;C QUY ĐỊNH KH&Aacute;C.</strong></p>

			<ol>
				<li>Đơn vị thu thập th&ocirc;ng tin:
				<p>EPAY l&agrave; đơn vị thu thập, lưu trữ th&ocirc;ng tin của kh&aacute;ch h&agrave;ng khi sử dụng dịch vụ MegaV. Địa chỉ như sau: C&ocirc;ng ty cổ phần Thanh to&aacute;n điện tử VNPT EPAY &ndash; Tầng 3 T&ograve;a nh&agrave; Viễn đ&ocirc;ng, số 36 Ho&agrave;ng Cầu, quận Đống Đa, H&agrave; Nội</p>
				</li>
				<li>Thời gian lưu trữ của th&ocirc;ng tin thu thập được sẽ theo quy địn nội bộ của EPAY</li>
				<li>EPAY cam kết bảo mật theo đ&uacute;ng c&aacute;c quy định được đề cập đến trong ch&iacute;nh s&aacute;c n&agrave;y. h&agrave;ng trong một khoảng thời gian n&agrave;o đ&oacute; nhằm phục vụ cho việc truy thu nợ, giải quyết tranh chấp, khắc phục sự cố, hỗ trợ điều tra, ngăn ngừa rủi ro, tu&acirc;n thủ Thỏa thuận người sử dụng, hoặc nhằm thực hiện những hoạt động kh&aacute;c theo y&ecirc;u cầu của ph&aacute;p luật hoặc kh&ocirc;ng tr&aacute;i ph&aacute;p luật.</li>
			</ol>

			<p>&nbsp;</p>

			<p>&nbsp;</p>

			
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="margin-top: 50px;">
    <div class="modal-content" style="border-radius: 0px;">
      <div class="modal-header" style="background-color: rgb(72, 177, 74); color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <!--h4 class="modal-title" id="myModalLabel">Điều khoản sử dụng</h4-->
		<p style="text-align: center; margin: 0px;"><strong>THỎA THUẬN NGƯỜI SỬ DỤNG DỊCH VỤ MEGAV</strong></p>
      </div>
      <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
		
<p>&nbsp;</p>

<p>Thỏa thuận Người sử dụng (c&ograve;n gọi tắt l&agrave; &ldquo;Thỏa thuận&rdquo;) n&agrave;y được hiểu l&agrave; sự thỏa thuận giữa Người sử dụng (c&ograve;n được gọi l&agrave; &ldquo;Kh&aacute;ch h&agrave;ng&rdquo;) v&agrave; C&ocirc;ng ty cổ phần Thanh to&aacute;n điện tử VNPT (được gọi tắt l&agrave; &ldquo;EPAY&rdquo; hoặc &ldquo;VNPT EPAY&rdquo;), được &aacute;p dụng khi Người sử dụng sử dụng V&iacute; điện tử MegaV (được gọi tắt l&agrave; &ldquo;MegaV&rdquo;) của EPAY.</p>

<p>C&aacute;c dẫn chiếu li&ecirc;n quan đến Thỏa thuận; Ch&iacute;nh s&aacute;ch Quyền Ri&ecirc;ng tư; Ch&iacute;nh s&aacute;ch giải quyết khiếu nại; Biểu ph&iacute;; C&aacute;c quy định kh&aacute;c c&oacute; li&ecirc;n quan được hiểu l&agrave; Thỏa thuận n&agrave;y; Ch&iacute;nh s&aacute;ch quyền ri&ecirc;ng tư; Biếu ph&iacute; v&agrave; C&aacute;c quy định kh&aacute;c c&oacute; li&ecirc;n quan do EPAY ban h&agrave;nh.</p>

<p>C&aacute;c thuật ngữ được giải th&iacute;ch v&agrave; sử dụng trong Thỏa thuận n&agrave;y cũng sẽ được hiểu thống nhất khi sử dụng tại c&aacute;c văn bản li&ecirc;n quan do EPAY ban h&agrave;nh.</p>

<p>&ldquo;Kh&aacute;ch h&agrave;ng&rdquo; hay &ldquo;Người sử dụng&rdquo; trong Thỏa Thuận n&agrave;y l&agrave; những tổ chức, c&aacute; nh&acirc;n sử dụng MegaV, d&ugrave; theo phương thức trực tiếp hay gi&aacute;n tiếp trong suốt tiến tr&igrave;nh kinh doanh thương mại.</p>

<p>Thỏa thuận l&agrave; một văn bản quan trọng m&agrave; Kh&aacute;ch h&agrave;ng cần xem x&eacute;t cẩn trọng, đồng &yacute; v&agrave; chấp nhận tất cả c&aacute;c quy định trong Thỏa thuận khi sử dụng MegaV. Trừ trường hợp c&oacute; thỏa thuận kh&aacute;c, khi Người sử dụng sử dụng MegaV c&oacute; nghĩa l&agrave; Người sử dụng đ&atilde; ho&agrave;n to&agrave;n tự nguyện đồng &yacute;, chấp nhận cũng như tu&acirc;n thủ tất cả c&aacute;c quy định trong Thỏa thuận v&agrave; c&aacute;c quy định ch&iacute;nh s&aacute;ch v&agrave; điều khoản li&ecirc;n quan đến ch&iacute;nh s&aacute;ch của EPAY trong qu&aacute; tr&igrave;nh sử dụng dịch vụ, bao gồm nhưng kh&ocirc;ng giới hạn: Ch&iacute;nh s&aacute;ch Quyền ri&ecirc;ng tư; Biểu ph&iacute;; Ch&iacute;nh s&aacute;ch giải quyết khiếu nại của EPAY.</p>

<p>EPAY c&oacute; thể điểu chỉnh Thỏa thuận n&agrave;y v&agrave;o bất kỳ thời điểm n&agrave;o th&ocirc;ng qua việc đăng tải Thỏa thuận được điểu chỉnh tr&ecirc;n website của EPAY v&agrave;/hoặc tất cả c&aacute;c phương tiện, c&ocirc;ng cụ, ứng dụng, dịch vụ li&ecirc;n quan đến việc cung ứng dịch vụ MegaV. Bản điều chỉnh c&oacute; hiệu lực kể từ thời điểm được đăng tải. VNPT EPAY kh&ocirc;ng c&oacute; tr&aacute;ch nhiệm th&ocirc;ng b&aacute;o thay đổi cho từng Kh&aacute;ch h&agrave;ng. Những quy định trọng yếu của Thỏa thuận sẽ được VNPT EPAY th&ocirc;ng b&aacute;o tr&ecirc;n website trước 15 (mười năm) ng&agrave;y &nbsp;t&iacute;nh tới ng&agrave;y &aacute;p dụng hiệu lực. Kh&aacute;ch h&agrave;ng ho&agrave;n to&agrave;n tự chịu tr&aacute;ch nhiệm trong việc nhận thức v&agrave; tu&acirc;n thủ c&aacute;c quy định của ph&aacute;p luật khi sử dụng Dịch vụ MegaV, bao gồm nhưng kh&ocirc;ng giới hạn, đối với những quy định về t&iacute;nh hợp ph&aacute;p của giao dịch, h&agrave;ng h&oacute;a/dịch vụ cung cấp, nguồn gốc gi&aacute; trị tiền tệ thanh to&aacute;n.</p>

<p><strong>ĐIỀU 1. DỊCH VỤ V&Iacute; ĐIỆN TỬ MEGAV</strong></p>

<p>MegaV l&agrave; dịch vụ V&iacute; điện tử do EPAY cung cấp cho Người sử dụng, cho ph&eacute;p lưu giữ một gi&aacute; trị tiền tệ được đảm bảo bằng gi&aacute; trị tiền gửi tương đương với số tiền được Người sử dụng chuyển v&agrave;o t&agrave;i khoản đảm bảo thanh to&aacute;n của EPAY theo tỷ lệ 1:1, v&agrave; được sử dụng l&agrave;m phương tiện thanh to&aacute;n kh&ocirc;ng d&ugrave;ng tiền mặt.</p>

<p>Người sử dụng c&oacute; thể sử dụng MegaV để thanh to&aacute;n/nhận thanh to&aacute;n cho h&agrave;ng h&oacute;a/dịch vụ ph&aacute;t sinh từ c&aacute;c giao dịch giữa c&aacute;c Người b&aacute;n v&agrave; Người mua. C&aacute;c dịch vụ MegaV cung cấp cho Người sử dụng bao gồm:</p>

<ul>
	<li>Nạp r&uacute;t tiền MegaV</li>
	<li>Thanh to&aacute;n trực tuyến</li>
	<li>Thanh to&aacute;n h&oacute;a đơn</li>
	<li>Thanh to&aacute;n game, điện thoại, topup</li>
	<li>C&aacute;c dịch vụ kh&aacute;c</li>
</ul>

<p>Đơn vị tiền tệ quy định sử dụng trong&nbsp; MegaV l&agrave; đồng Việt Nam (VNĐ).</p>

<p>Gi&aacute; trị tiền tệ trong suốt thời gian lưu trữ trong V&iacute; điện tử kh&ocirc;ng ph&aacute;t sinh gi&aacute; trị tăng th&ecirc;m (kh&ocirc;ng được trả l&atilde;i tr&ecirc;n số dư V&iacute; điện tử hoặc bất kỳ h&agrave;nh động n&agrave;o c&oacute; thể l&agrave;m tăng gi&aacute; trị tiền tệ tr&ecirc;n V&iacute; điện tử).</p>

<p>EPAY l&agrave; một đơn vị độc lập với Người sử dụng, EPAY kh&ocirc;ng phải l&agrave; đại l&yacute; hay đơn vị nhận ủy quyền từ Người sử dụng. EPAY cũng kh&ocirc;ng kiểm so&aacute;t hay chịu tr&aacute;ch nhiệm cho bất kỳ h&agrave;ng h&oacute;a/dịch vụ n&agrave;o được thanh to&aacute;n th&ocirc;ng qua MegaV.</p>

<p>Khi sử dụng MegaV, Người sử dụng cần lưu &yacute; những rủi ro đ&atilde; được thể hiện chi tiết tại c&aacute;c tiểu mục li&ecirc;n quan trong Thỏa thuận n&agrave;y, như:</p>

<ul>
	<li>Số tiền đ&atilde; được thanh to&aacute;n v&agrave;o t&agrave;i khoản của Kh&aacute;ch h&agrave;ng sau đ&oacute; c&oacute; thể bị ho&agrave;n chuyển trả lại. V&iacute; dụ như trong c&aacute;c trường hợp bồi ho&agrave;n khi thanh to&aacute;n bằng thẻ t&iacute;n dụng, khiếu nại hoặc c&aacute;c trường hợp giao dịch v&ocirc; hiệu kh&aacute;c. N&oacute;i c&aacute;ch kh&aacute;c, trong trường hợp Kh&aacute;ch h&agrave;ng l&agrave; Người b&aacute;n, số tiền Kh&aacute;ch h&agrave;ng nhận được c&oacute; thể bị ho&agrave;n trả lại cho người thanh to&aacute;n, hoặc c&oacute; thể bị phong tỏa/thu giữ kể cả khi h&agrave;ng h&oacute;a/dịch vụ đ&atilde; được cung cấp;</li>
	<li>Số dư trong V&iacute; điện tử của Kh&aacute;ch h&agrave;ng c&oacute; thể phải đ&aacute;p ứng c&aacute;c mức giới hạn tối thiểu theo quy định của EPAY.</li>
	<li>EPAY c&oacute; thể đ&oacute;ng, phong tỏa, tạm kh&oacute;a V&iacute; đi&ecirc;n tử hoặc giới hạn quyền truy cập của Kh&aacute;ch h&agrave;ng v&agrave;o V&iacute; điện tử MegaV (theo quy định của EPAY hoặc của cơ quan c&oacute; thẩm quyền) trong trường hợp Kh&aacute;ch h&agrave;ng vi phạm Thỏa thuận n&agrave;y v&agrave;/hoặc bất kỳ quy định n&agrave;o kh&aacute;ch của EPAY m&agrave; Kh&aacute;ch h&agrave;ng đ&atilde; tham gia v&agrave;o.</li>
</ul>

<p>&nbsp;</p>

<ol>
	<li>Mở V&iacute; điện tử MegaV
	<ul>
		<li>
		<p>EPAY ph&aacute;t h&agrave;nh V&iacute; điện tử MegaV cho Kh&aacute;ch h&agrave;ng dựa tr&ecirc;n y&ecirc;u cầu đăng k&yacute; tr&ecirc;n cơ sở đ&aacute;p ứng c&aacute;c điều kiện được thực hiện tại c&aacute;c Website cung cấp dịch vụ MegaV hoặc c&aacute;c phương tiện c&oacute; ứng dụng MegaV.</p>

		<p>MegaV được ph&aacute;t h&agrave;nh v&agrave; được sử dụng khi đ&aacute;p ứng đủ c&aacute;c điều kiện sau:</p>

		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; + Kh&aacute;ch h&agrave;ng phải c&oacute; t&agrave;i khoản ng&acirc;n h&agrave;ng trước khi sử dụng V&iacute; điện tử MegaV.</p>

		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; + Mỗi t&agrave;i khoản ng&acirc;n h&agrave;ng chỉ được đăng k&yacute; cho 01 t&agrave;i khoản MegaV.</p>

		<p>Người sử dụng khi được cấp V&iacute; điện tử c&ograve;n được gọi l&agrave; Chủ V&iacute; điện tử MegaV (Chủ V&iacute;).</p>

		<p>Số dư tối thiểu l&agrave; số tiền tối thiểu m&agrave; Chủ V&iacute; phải duy tr&igrave; tr&ecirc;n V&iacute; điện tử tại mọi thời điểm, t&ugrave;y theo từng trường hợp thỏa thuận cụ thể (nếu c&oacute;).</p>
		Số dư được ph&eacute;p sử dụng (số dư khả dụng) l&agrave; số tiền Chủ V&iacute; c&oacute; thể sử dụng để chi ti&ecirc;u v&agrave; thanh to&aacute;n từ V&iacute; điện tử của m&igrave;nh. Số dư được ph&eacute;p sử dụng bằng số dư c&oacute; tr&ecirc;n V&iacute; điện tử trừ đi c&aacute;c khoản tạm kh&oacute;a/phong tỏa, số dư tối thiểu (nếu c&oacute;).</li>
	</ul>
	</li>
	<li>Phong tỏa V&iacute; điện tử MegaV
	<ul>
		<li>EPAY c&oacute; quyền phong tỏa &ndash; tạm dừng giao dịch &ndash; một phần hoặc to&agrave;n bộ số tiền tr&ecirc;n MegaV
		<ul>
			<li>Khi c&oacute; quyết định hoặc y&ecirc;u cầu bằng văn bản của cơ quan c&oacute; thẩm quyền theo quy định của ph&aacute;p luật;</li>
			<li>Khi EPAY ph&aacute;t hiện c&oacute; mẫu thuẫn, nhầm lẫn, sai s&oacute;t, chưa r&otilde; r&agrave;ng trong việc thực hiện c&aacute;c giao dịch;</li>
			<li>Khia EPAY ph&aacute;t hiện c&oacute; dấu hiệu phức tạp, bất thường, gian lận, vi phạm ph&aacute;p luật li&ecirc;n quan đến hoạt động thanh to&aacute;n;</li>
			<li>Khi ph&aacute;t sinh tranh chấp li&ecirc;n quan đến c&aacute;c giao dịch của MegaV.</li>
		</ul>
		</li>
		<li>Số tiền bị phong tỏa được bảo to&agrave;n v&agrave; kiểm so&aacute;t chặt chẽ theo nội dung phong tỏa. Trường hợp bị phong tỏa một phần th&igrave; phần kh&ocirc;ng bị phong tỏa vẫn được sử dụng b&igrave;nh thường.</li>
		<li>EPAY chấm dứt phong tỏa MegaV khi c&oacute; một trong c&aacute;c điều kiện sau:
		<ul>
			<li>Kết th&uacute;c thời hạn phong tỏa;</li>
			<li>C&oacute; văn bản y&ecirc;u cầu của cơ quan c&oacute; thẩm quyền về việc chấm dứt phong tỏa t&agrave;i khoản;</li>
			<li>EPAY đ&atilde; xử l&yacute; xong sai s&oacute;t, nhầm lẫn, mẫu thuẫn hay c&aacute;c nguy&ecirc;n nh&acirc;n kh&aacute;c dẫn đến việc phong tỏa;</li>
			<li>Sau khi x&aacute;c minh giao dịch được thực hiện ph&ugrave; hợp, kh&ocirc;ng thuộc trường hợp gian lận, vi phạm ph&aacute;p luật;</li>
			<li>Tranh chấp li&ecirc;n quan đến c&aacute;c giao dich của MegeV được giải quyết.</li>
		</ul>
		</li>
	</ul>
	</li>
	<li>Tạm kh&oacute;a V&iacute; điện tử MegaV
	<ul>
		<li>Để đảm bảo an to&agrave;n cho Chủ V&iacute; điện tử, EPAY sẽ tạm kh&oacute;a chức năng đăng nhập V&iacute; điện tử của Kh&aacute;ch h&agrave;ng trong c&aacute;c trường hợp:
		<ul>
			<li>Sau 5 lần đăng nhập MegaV kh&ocirc;ng th&agrave;nh c&ocirc;ng li&ecirc;n tiếp. Trong trường hợp n&agrave;y, thời gian tạm kh&oacute;a l&agrave; 2h kể từ sau lần đăng nhập kh&ocirc;ng th&agrave;nh &nbsp;c&ocirc;ng thứ 5 v&agrave; Kh&aacute;ch h&agrave;ng c&oacute; thể đăng nhập lại b&igrave;nh thường sau khi kết th&uacute;c thời gian tạm kh&oacute;a.</li>
			<li>Khi EPAY nhận được th&ocirc;ng b&aacute;o trực tiếp th&ocirc;ng qua số Hotline của Bộ phận Hỗ trợ kh&aacute;ch h&agrave;ng của EPAY hoặc bằng văn bản của Chủ V&iacute; về việc thiết bị x&aacute;c thực bị thất lạc, hoặc lộ th&ocirc;ng tin đăng nhập, mật khẩu giao dịch của Chủ V&iacute;. Trong trường hợp n&agrave;y, chức năng đăng nhập V&iacute; điện tử của Kh&aacute;ch h&agrave;ng bị tạm kh&oacute;a cho đến khi EPAY nhận được th&ocirc;ng b&aacute;o x&aacute;c nhận bằng văn bản của Kh&aacute;ch h&agrave;ng về việc đồng &yacute; mở lại chức năng đăng nhập MegaV.</li>
		</ul>
		</li>
		<li>EPAY c&oacute; quyền tạm kh&oacute;a&nbsp; - tạm dừng giao dịch - một phần hoặc to&agrave;n bộ số dư tr&ecirc;n MegaV của Người sử dụng trong c&aacute;c trường hợp sau:
		<ul>
			<li>Theo y&ecirc;u cầu của Chủ V&iacute;, trừ trường hợp thuộc phạm vi quyền từ chối của EPAY;</li>
			<li>Kh&aacute;ch h&agrave;ng c&oacute; bất kỳ sự thay đổi li&ecirc;n quan đến việc sử dụng MegaV đ&atilde; đăng k&yacute; v&agrave; sự thay đổi n&agrave;y kh&ocirc;ng đ&aacute;p ứng đ&uacute;ng c&aacute;c quy định tại Thỏa thuận n&agrave;y;</li>
			<li>MegaV của kh&aacute;ch h&agrave;ng kh&ocirc;ng ph&aacute;t sinh giao dịch n&agrave;o trong thời hạn 06 (s&aacute;u) th&aacute;ng li&ecirc;n tục hoặc một thời hạn kh&aacute;c do EPAY quy định t&ugrave;y từng thời điểm;</li>
			<li>MegaV của Kh&aacute;ch h&agrave;ng c&oacute; số dư thấp hơn số dư tối thiểu để duy tr&igrave;nh V&iacute; điện tử;</li>
			<li>V&igrave; l&yacute; do bất kỳ m&agrave; EPAY cho l&agrave; ph&ugrave; hợp để giảm thiểu mọi rủi ro c&oacute; thể xảy ra.</li>
		</ul>
		</li>
		<li>
		<p>EPAY kh&ocirc;ng c&oacute; nghĩa vụ phải giải th&iacute;ch hay đưa ra bằng chứng bất kỳ n&agrave;o cho Người sử dụng về việc n&agrave;y. Trong trường hợp MegaV c&ograve;n số dư, EPAY sớm th&ocirc;ng b&aacute;o cho người sử dụng sau khi tạm kh&oacute;a V&iacute; điện tử đ&oacute;. Người sử dụng đồng &yacute; kh&ocirc;ng quy tr&aacute;ch nhiệm cho EPAY về bất kỳ tổn thất hoặc thiệt hại n&agrave;o ph&aacute;t sinh từ việc tạm kh&oacute;a, v&agrave;/hoặc chấm dứt/thu hồi/hủy bỏ việc tạm kh&oacute;a V&iacute; điện tử.</p>
		Việc tạm kh&oacute;a V&iacute; điện tử MegaV được chấm dứt khi c&aacute;c điều kiện ph&aacute;t sinh việc tạm kh&oacute;a chấm dứt.</li>
	</ul>
	</li>
	<li>Đ&oacute;ng V&iacute; điện tử</li>
</ol>

<p>Đ&oacute;ng V&iacute; điện tử MegaV l&agrave; việc EPAY đ&oacute;ng hồ sơ V&iacute; điện tử, th&ocirc;ng tin ph&aacute;t h&agrave;nh V&iacute; điện tử bị đ&oacute;ng c&oacute; thể đươc sử dụng để ph&aacute;t h&agrave;nh cho Người sử dụng kh&aacute;c. Sau &nbsp;khi đ&oacute;ng V&iacute; điện tử MegaV, kh&aacute;ch h&agrave;ng muốn sử dụng V&iacute; điện tử phải l&agrave;m thủ tục mở v&agrave; sử dụng V&iacute; điện tử mới theo quy định tại Thỏa thuận n&agrave;y.</p>

<ul>
	<li>EPAY được quyền đ&oacute;ng MegaV của Kh&aacute;ch h&agrave;ng trong c&aacute;c trường hợp sau:
	<ul>
		<li>Theo y&ecirc;u cầu của Chủ V&iacute;, trừ trường hợp thuộc phạm vi quyền từ chối của EPAY;</li>
		<li>Kh&aacute;ch h&agrave;ng vi phạm ph&aacute;p luật hoặc vi phạm bất kỳ điều n&agrave;o trong Thỏa thuận n&agrave;y, hoặc bất kỳ điều khoản v&agrave; điều kiện n&agrave;o kh&aacute;c được quy định ri&ecirc;ng cho từng loại t&agrave;i khoản cụ thể, bao gồm nhưng kh&ocirc;ng giới hạn ở việc Người sử dụngNgười sử dụng kh&ocirc;ng nộp, sửa đổi, cập nhật th&ocirc;ng tin, chứng từ cho EPAY; kh&ocirc;ng phản hồi/thực hiện c&aacute;c y&ecirc;u cầu hoặc phản hồi/thực hiện c&aacute;c y&ecirc;u cầu kh&ocirc;ng đ&uacute;ng thời hạn sau khi c&oacute; y&ecirc;u cầu của EPAY;</li>
		<li>Kh&aacute;ch h&agrave;ng kh&ocirc;ng phản hồi/thực hiện c&aacute;c y&ecirc;u cầu hoặc phản hồi/thực hiện c&aacute;c y&ecirc;u cầu kh&ocirc;ng đ&uacute;ng thời hạn sau khi c&oacute; y&ecirc;u cầu của EPAY li&ecirc;n quan đến c&aacute;c trường hợp &ldquo;Tạm kh&oacute;a V&iacute; điện tử&rdquo; của Kh&aacute;ch h&agrave;ng.</li>
		<li>Chủ V&iacute; điện tử l&agrave; c&aacute; nhận bị chết, mất t&iacute;ch hoặc mất năng lực h&agrave;nh vi d&acirc;n sự;</li>
		<li>Tổ chức c&oacute; V&iacute; điện tử chấm dứt hoạt động theo quy định của ph&aacute;p luật;</li>
		<li>Chủ V&iacute; điện tử vi phạm quy định mở v&agrave; sử dụng MegaV của EPAY;</li>
		<li>Chủ V&iacute; điện tử vi phạm c&aacute;c quy định ph&aacute;p luật kh&aacute;c trong hoạt động thanh to&aacute;n;</li>
		<li>C&aacute;c trường hợp kh&aacute;c theo quy định của ph&aacute;p luật.</li>
	</ul>
	</li>
	<li>Xử l&yacute; số dư khi đ&oacute;ng V&iacute; điện tử:
	<ul>
		<li>Số dư c&ograve;n lại sau khi đ&oacute;ng V&iacute; điện tử được EPAY xem x&eacute;t xử l&yacute; như sau:
		<ul>
			<li>Chi trả theo y&ecirc;u cầu của Chủ V&iacute;, người gi&aacute;m hộ, người đại diện hợp ph&aacute;p của Chủ V&iacute; (trong trường hợp Chủ V&iacute; l&agrave; người chưa đủ 15 tuổi, người đủ từ 15-18 tuổi kh&ocirc;ng c&oacute; t&agrave;i sản ri&ecirc;ng, người hạn chế năng lực h&agrave;nh vi d&acirc;n sự, người mất năng lực h&agrave;nh vi d&acirc;n sự) hoặc người được thừa kế, đại diện thừa kế trong trường hợp Chủ V&iacute; thanh to&aacute;n của c&aacute; nh&acirc;n bị chết, bị tuy&ecirc;n bố l&agrave; đ&atilde; chết, mất t&iacute;ch;</li>
			<li>Chi trả theo quyết định của t&ograve;a &aacute;n;</li>
			<li>EPAY xử l&yacute; theo quy đinh của ph&aacute;p luật đối với trường hợp người thụ hưởng hợp ph&aacute;p tr&ecirc;n số dư tr&ecirc;n V&iacute; điện tử đ&atilde; được th&ocirc;ng b&aacute;o m&agrave; kh&ocirc;ng đến nhận hoặc theo thỏa thuận trước bằng văn bản với Chủ V&iacute;, ph&ugrave; hợp với quy định của ph&aacute;p luật hiện h&agrave;nh;</li>
			<li>Số dư chỉ được chi trả sau khi trừ đi c&aacute;c khoản ph&iacute; kh&aacute;ch theo Biểu ph&iacute; của EPAY v&agrave; căn cứ tr&ecirc;n cơ sở đ&aacute;p ứng c&aacute;c điều kiện li&ecirc;n quan đến việc tiếp nhận.</li>
		</ul>
		</li>
	</ul>
	</li>
	<li>Tại thời điểm đ&oacute;ng MegaV, Người sử dụng được y&ecirc;u cầu ho&agrave;n trả ngay cho EPAY tất cả t&agrave;i sản của EPAY do Người sử dụng nắm giữ, bao gồm nhưng kh&ocirc;ng giới hạn, những chứng từ do EPAY ph&aacute;t h&agrave;nh trong từng thời điểm.</li>
	<li>Kh&aacute;ch h&agrave;ng c&oacute; bất kỳ sự thay đổi li&ecirc;n quan đến việc sử dụng dịch vụ MegaV đ&atilde; đăng k&yacute; v&agrave; sự thay đổi n&agrave;y kh&ocirc;ng đ&aacute;p ứng đ&uacute;ng c&aacute;c quy định tại Thỏa thuận n&agrave;y;</li>
	<li>MegaV của kh&aacute;ch h&agrave;ng kh&ocirc;ng ph&aacute;t sinh giao dịch n&agrave;o trong thời hạn 06 (s&aacute;u) th&aacute;ng li&ecirc;n tục t&iacute;nh từ ng&agrave;y ph&aacute;t sinh giao dịch cuối c&ugrave;ng &nbsp;hoặc một thời hạn kh&aacute;c do EPAY quy định t&ugrave;y từng thời điểm;</li>
	<li>MegaV của Kh&aacute;ch h&agrave;ng c&oacute; số dư thấp hơn số dư tối thiểu theo quy định để duy tr&igrave; V&iacute; điện tử;</li>
	<li>V&igrave; l&yacute; do bất kỳ m&agrave; EPAY cho l&agrave; ph&ugrave; hợp để giảm thiểu mọi rủi ro c&oacute; thể xả ra.</li>
</ul>

<p><strong>ĐIỀU 2. GIỚI HẠN GIAO DỊCH, CẢNH B&Aacute;O GIAO DỊCH</strong></p>

<ol>
	<li>&nbsp;Để hạn chế rủi ro v&agrave; tu&acirc;n thủ quy định của ph&aacute;p luật, EPAY c&oacute; quyền chủ động thực hiện c&aacute;c biện ph&aacute;p ngăn chặn, x&aacute;c minh (kể cả sử dụng c&aacute;c biện ph&aacute;p phong tỏa/tạm kh&oacute;a V&iacute; điện tử, tạm dừng giao dịch đ&atilde; hoặc đang thực hiện) đối với c&aacute;c trường hợp m&agrave; theo đ&aacute;nh gi&aacute; của EPAY rằng c&oacute; t&iacute;nh chất bất thường, phức tạp</li>
	<li>Giao dịch c&oacute; gi&aacute; trị lớn thường l&agrave; giao dịch kh&ocirc;ng ph&ugrave; hợp với mức gi&aacute; trị giao dịch thường xuy&ecirc;n của Người sử dụng qua MegaV r&otilde; r&agrave;ng hoặc kh&ocirc;ng tương xứng với thu nhập dựa tr&ecirc;n cảnh b&aacute;o của Ng&acirc;n h&agrave;ng.</li>
	<li>Giao dịch phức tạp l&agrave; giao dịch được thực hiện th&ocirc;ng qua phương thức kh&ocirc;ng ph&ugrave; hợp với bản chất của giao dịch như: Giao dịch được thực hi&ecirc;n th&ocirc;ng qua nhiều b&ecirc;n trung gian, nhiều t&agrave;i khoản kh&ocirc;ng cần thiết, giao dịch được thực hiện giữa nhiều t&agrave;i khoản/V&iacute; điện tử kh&aacute;c nhau của c&ugrave;ng một chủ t&agrave;i khoản/Chủ V&iacute; tại c&aacute;c khu vực địa &yacute; kh&aacute;c nhau; bất kỳ giao dịch n&agrave;o do EPAY nhận định kh&ocirc;ng b&igrave;nh thường v&agrave; cần c&oacute; sư gi&aacute;m s&aacute;t chặt chẽ.</li>
</ol>

<p><strong>ĐIỀU 3. TH&Ocirc;NG TIN NGƯỜI SỬ DỤNG</strong></p>

<ol>
	<li>Để mở, duy tr&igrave; v&agrave; sử dụng MegaV, Người sử dụng phải cung cấp v&agrave; cập nhật th&ocirc;ng tin c&aacute; nh&acirc;n ch&iacute;nh x&aacute;c cho EPAY theo quy định v&agrave; hướng dẫn cụ thể.</li>
	<li>Th&ocirc;ng tin Người sử dụng l&agrave; th&ocirc;ng tin để x&aacute;c định một c&aacute;c nh&acirc;n/tổ chức cụ thể, giao gồm nhưng kh&ocirc;ng giới hạn, th&ocirc;ng tin định danh của c&aacute;c nh&acirc;n/tổ chức đ&oacute; (t&ecirc;n, ng&agrave;y/th&aacute;ng/năm sinh, thời điểm th&agrave;nh lập, CMND, Giấy chứng nhận ĐKKD,&hellip;), th&ocirc;ng tin li&ecirc;n lạc (di động, email,&hellip;) th&ocirc;ng tin t&agrave;i khoản ng&acirc;n h&agrave;ng v&agrave; c&aacute;c th&ocirc;ng tin chi tiết cần thiết kh&aacute;c. Chi tiết Th&ocirc;ng tin người sử dụng cần được k&ecirc; khai theo biểu mẫu hướng dẫn cụ thể của EPAY.</li>
</ol>

<p><strong>ĐIỀU 4. T&Iacute;NH CHUẨN X&Aacute;C CỦA TH&Ocirc;NG TIN</strong></p>

<p>EPAY lưu &yacute; rằng th&ocirc;ng tin của Kh&aacute;ch h&agrave;ng cung cấp l&agrave; cần thiết để Kh&aacute;ch h&agrave;ng c&oacute; thể truy cập v&agrave; sử dụng đầy đủ dịch vụ MegaV, l&agrave; cơ sở bắt buộc để EPAY kiểm tra/x&aacute;c thực th&ocirc;ng tin đăng k&yacute; sử dụng MegaV v&agrave; xử l&yacute; dữ liệu. Do đ&oacute;, Kh&aacute;ch h&agrave;ng cam kết bảo đảm t&iacute;nh ch&iacute;nh x&aacute;c, đầy đủ của th&ocirc;ng tin cung cấp cho EPAY, kịp thời cập nhật ngay khi c&oacute; thay đổi. Ch&uacute;ng t&ocirc;i kh&ocirc;ng chịu tr&aacute;ch nhiệm giải quyết bất cứ tranh chấp/khiếu nại n&agrave;o nếu th&ocirc;ng tin Kh&aacute;ch h&agrave;ng cung cấp c&oacute; sự sai lệch; kh&ocirc;ng ch&iacute;nh x&aacute;c hoặc giả mạo.</p>

<ol>
	<li>Th&ocirc;ng tin định danh</li>
	<li>T&agrave;i khoản ng&acirc;n h&agrave;ng
	<ul>
		<li>Kh&aacute;ch h&agrave;ng phải khai b&aacute;o 01 t&agrave;i khoản ng&acirc;n h&agrave;ng tương ứng cho 01 MegaV m&agrave; Kh&aacute;ch h&agrave;ng sử dụng.</li>
		<li>Kh&aacute;ch h&agrave;ng lưu &yacute; rằng, bất kỳ thời điểm n&agrave;o, EPAY c&oacute; quyền tạm kh&oacute;a/đ&oacute;ng V&iacute; điện tử khi ph&aacute;t hiện bất kỳ nội dung kh&ocirc;ng ch&iacute;nh x&aacute;c li&ecirc;n quan đến th&ocirc;ng tin t&agrave;i khoản ng&acirc;n h&agrave;ng m&agrave; Kh&aacute;ch h&agrave;ng đ&atilde; khai b&aacute;o.</li>
	</ul>
	</li>
	<li>Th&ocirc;ng tin li&ecirc;n lạc</li>
</ol>

<p>Kh&aacute;ch h&agrave;ng c&oacute; tr&aacute;ch nhiệm cung cấp email, số điện thoại v&agrave; c&aacute;c th&ocirc;ng tin li&ecirc;n lạc kh&aacute;c, c&oacute; gi&aacute; trị sử dụng v&agrave; đảm bảo khả năng tiếp nhận th&ocirc;ng tin từ EPAY. Kh&aacute;ch h&agrave;ng c&oacute; thể cập nhật th&ocirc;ng tin li&ecirc;n lạc của Kh&aacute;ch h&agrave;ng bất kỳ l&uacute;c n&agrave;o theo hướng dẫn của EPAY.</p>

<p>Kh&aacute;ch h&agrave;ng c&oacute; tr&aacute;ch nhiệm bảo mật c&aacute;c th&ocirc;ng tin, tin nhắn từ EPAY gửi đến v&agrave; đồng &yacute; miễn trừ tr&aacute;ch nhiệm cho EPAY trong c&aacute;c trường hợp th&ocirc;ng tin hoặc tin nhắn tr&ecirc;n thiết bị truy cập hoặc sử dụng bởi một b&ecirc;n kh&ocirc;ng c&oacute; thẩm quyền.</p>

<p><strong>ĐIỀU 5. VAI TR&Ograve;, QUYỀN, TR&Aacute;CH NHIỆM V&Agrave; CAM KẾT CỦA NGƯỜI SỬ DỤNG</strong></p>

<ol>
	<li>Việc sử dụng Dịch Vụ MegaV phải tu&acirc;n thủ c&aacute;c quy định của EPAY v&agrave; của ph&aacute;p luật.</li>
	<li>Được lựa chọn v&agrave; sử dụng c&aacute;c dịch vụ hỗ trợ thanh to&aacute;n bằng MegaV.</li>
	<li>Đối với MegaV, Người sử dụng ch&iacute;nh l&agrave; Người thụ hưởng đối với V&iacute; điện tử của Người sử dụng:
	<ul>
		<li>Mọi giao dịch được thực hiện th&ocirc;ng qua V&iacute; điện tử của Người sử dụng được hiểu mặc định l&agrave; nh&acirc;n danh ch&iacute;nh Người sử dụng, v&agrave; Người sử dụng ho&agrave;n to&agrave;n tự kiểm so&aacute;t, quản l&yacute; mọi giao dịch ph&aacute;t sinh tr&ecirc;n MegaV của Người sử dụng;</li>
		<li>C&oacute; quyền sử dụng số tiền tr&ecirc;n MegaV th&ocirc;ng qua c&aacute;c lệnh thanh to&aacute;n hợp ph&aacute;p, hợp lệ; được EPAY tạo mọi điều kiện để sử dụng MegaV của m&igrave;nh một c&aacute;ch an to&agrave;n v&agrave; hiệu quả nhất.</li>
		<li>Được y&ecirc;u cầu EPAY thực hiện c&aacute;c lệnh thanh to&aacute;n ph&aacute;t sinh hợp ph&aacute;p, hợp lệ trong phạm vi số dư được ph&eacute;p sử dụng;</li>
		<li>Đảm bảo c&oacute; đủ số dư tối thiểu theo quy định của EPAY v&agrave; c&oacute; đủ số dư được ph&eacute;p sử dụng tr&ecirc;n V&iacute; điện tử để thực hiện c&aacute;c lệnh thanh to&aacute;n hợp ph&aacute;p v&agrave; trả c&aacute;c khoản ph&iacute; theo quy định của EPAY;</li>
		<li>Tự tổ chức hạch to&aacute;n, theo d&otilde;i số dư tr&ecirc;n MegaV đối chiếu với chứng từ do sử dụng MegaV do EPAY ph&aacute;t h&agrave;nh;</li>
		<li>Được y&ecirc;u cầu EPAY đ&oacute;ng hoặc tạm kh&oacute;a MegaV của m&igrave;nh khi cần thiết, trừ trường hợp c&aacute;c y&ecirc;u cầu n&agrave;y thuộc phạm vi quyền từ chối của EPAY;</li>
		<li>Được y&ecirc;u cầu cung cấp th&ocirc;ng tin về c&aacute;c giao dịch thanh to&aacute;n v&agrave; số dư tr&ecirc;n MegaV của m&igrave;nh;</li>
		<li>Trường hợp khi Người sử dụng đăng k&yacute; dịch vụ tr&iacute;ch nợ tự động. Người sử dụng cam kết ủy quyền cho EPAY được tự động tr&iacute;ch nợ từ MegaV để thanh to&aacute;n cho c&aacute;c h&oacute;a đơn h&agrave;ng h&oacute;a, dịch vụ với c&aacute;c Nh&agrave; cung cấp. Việc thanh to&aacute;n n&agrave;y c&oacute; thể được thực hiện theo y&ecirc;u cầu của Người sử dụng;</li>
		<li>Đồng &yacute; cho EPAY tr&iacute;ch từ t&agrave;i khoản để thanh to&aacute;n c&aacute;c gi&aacute; trị giao dịch, c&aacute;c khoản nợ đến hạn, qu&aacute; hạn, c&aacute;c loại ph&iacute; li&ecirc;n quan đến giao dịch, c&aacute;c loại ph&iacute; li&ecirc;n quan đến khiếu nại, tranh chấp theo quy định của EPAY v&agrave; ph&aacute;p luật, c&aacute;c chi ph&iacute; hợp lệ kh&aacute;c ph&aacute;t sinh trong qu&aacute; tr&igrave;nh quản l&yacute; v&agrave; cung ứng MegaV theo quy định;</li>
		<li>Trong trường hợp EPAY trừ tiền từ MegaV của Người sử dụng để ho&agrave;n trả tiền cho một giao dịch kh&aacute;c nếu số dư t&agrave;i khoản của MegaV tại thời điểm đ&oacute; kh&ocirc;ng đủ cho giao dịch, EPAY sẽ y&ecirc;u cầu Người sử dụng nạp tiền bổ sung v&agrave;o MegaV để ho&agrave;n tất giao dịch. Nếu Người sử dụng kh&ocirc;ng thực hiện y&ecirc;u cầu theo đ&uacute;ng thời hạn m&agrave; EPAY th&ocirc;ng b&aacute;o số tiền được bổ sung sẽ chịu l&atilde;i suất chậm trả theo quy định (nếu c&oacute;). Mức l&atilde;i suất n&agrave;y c&oacute; thể được điều chỉnh để kh&ocirc;ng vượt qu&aacute; mức trần l&atilde;i suất cho vay theo quy định của Ng&acirc;n h&agrave;ng Nh&agrave; nước (nếu c&oacute;) tại thời điểm ph&aacute;t sinh l&atilde;i suất chậm thanh to&aacute;n;</li>
		<li>Người sử dụng kh&ocirc;ng được tạo bất kỳ h&igrave;nh thức cầm cố, thế chấp hoặc biện ph&aacute;p bảo đảm n&agrave;o kh&aacute;c đối với MegaV;</li>
	</ul>
	</li>
	<li>Tu&acirc;n thủ c&aacute;c thủ tục đăng k&yacute;, tr&igrave;nh tự giao dịch v&agrave; c&aacute;c hướng dẫn kh&aacute;c của EPAY, sử dụng đ&uacute;ng mục đ&iacute;ch những th&ocirc;ng tin m&agrave; dịch vụ cung cấp; kh&ocirc;ng được sử dụng MegaV cho c&aacute;c mục đ&iacute;ch rửa tiền, t&agrave;i trợ khủng bố, lừa đảo, gian lận hoặc c&aacute;c h&agrave;nh vi vi phạm ph&aacute;p luật kh&aacute;c.</li>
	<li>Cung cấp tất cả th&ocirc;ng tin m&agrave; EPAY y&ecirc;u cầu phục vụ cho việc cung ứng dịch vụ; nội dung th&ocirc;ng tin phải kịp thời cập nhật thay đổi đầy đủ, ch&iacute;nh x&aacute;c, trung thực, khớp đ&uacute;ng với hồ sơ đăng k&yacute; mở V&iacute; điện tử tại EPAY trong c&aacute;c giao dịch thanh to&aacute;n; Chịu mọi tr&aacute;ch nhiệm về những sai s&oacute;t hay h&agrave;nh vi lợi dụng khi sử dụng MegaV do lỗi của m&igrave;nh.</li>
	<li>Người sử dụng phải đảm bảo an to&agrave;n v&agrave; b&iacute; mật đối với bất kỳ t&ecirc;n truy cập, mật khẩu truy cập v&agrave; c&aacute;c yếu tố định danh kh&aacute;c m&agrave; EPAY cung cấp. Người sử dụng ho&agrave;n to&agrave;n tự chịu tr&aacute;ch nhiệm trong trường hợp t&ecirc;n truy cập, mật khẩu truy cập v&agrave; c&aacute;c yếu tố định danh kh&aacute;c bị mất, lợi dụng, tiết lộ cho b&ecirc;n thứ ba bất kỳ v&agrave; sẽ chịu mọi rủi ro, thiệt hải g&acirc;y ra do việc sử dụng tr&aacute;i ph&eacute;p của b&ecirc;n thứ ba đ&oacute;.</li>
	<li>Người sử dụng phải th&ocirc;ng b&aacute;o ngay cho EPAY để kịp thời xử l&yacute; khi t&ecirc;n truy cập, mật khẩu v&agrave;/hoặc c&aacute;c yếu tố định danh kh&aacute;c bị mất, đ&aacute;nh cắp, bị lộ hoặc nghi bị lộ, đồng thời Người sử dụng phải chịu tr&aacute;ch nhiệm về những thiệt hại, tổn thất v&agrave; rủi ro kh&aacute;c trước khi EPAY đưa ra c&aacute;c giải ph&aacute;p xử l&yacute; ph&ugrave; hợp (giới hạn quyền truy cập,...).</li>
	<li>Trừ khi đ&atilde; th&ocirc;ng b&aacute;o cho EPAY về việc chấm dứt sử dụng dịch vụ &nbsp;theo đ&uacute;ng quy định tại Thỏa thuận n&agrave;y v&agrave; đ&atilde; nhận được x&aacute;c nhận việc chấm dứt từ EPAY. Người sử dụng thừa nhận rằng đối với bất cứ h&agrave;nh động truy cập n&agrave;o v&agrave;o dịch vụ MegaV, nếu EPAY kiểm tra thấy đ&uacute;ng, đủ mật khẩu v&agrave;/hoặc c&aacute;c yếu tố định danh kh&aacute;c theo quy định sẽ mặc nhi&ecirc;n coi đ&oacute; l&agrave; &yacute; ch&iacute; của Người sử dụng, cho d&ugrave; sau đ&oacute; ph&aacute;t hiện việc truy cập th&ocirc;ng tin n&agrave;y l&agrave; được thực hiện bởi người kh&ocirc;ng c&oacute; thẩm quyền. Người sử dụng phải ho&agrave;n to&agrave;n chịu tr&aacute;ch nhiệm về những rủi ro v&agrave; thiệt hại do việc sử dụng tr&aacute;i ph&eacute;p đ&oacute; g&acirc;y ra.</li>
	<li>Trong trường hợp thanh to&aacute;n qua dịch vụ MegaV, Người sử dụng c&oacute; thể sử dụng dịch vụ MegaV theo nhu cầu của Người sử dụng nhưng phải đảm bảo số tiền thanh to&aacute;n kh&ocirc;ng vượt qu&aacute; hạn mức được ph&eacute;p sử dụng v&agrave; số dư t&agrave;i khoản MegaV của Người sử dụng v&agrave;o thời điểm thực hiện giao dịch.</li>
	<li>Chịu tr&aacute;ch nhiệm trước ph&aacute;p luật về t&iacute;nh ch&iacute;nh x&aacute;c, trung thực của c&aacute;c th&ocirc;ng tin v&agrave; chứng từ thanh to&aacute;n m&agrave; Người sử dụng cung cấp hoặc c&aacute;c hoạt động của Người sử dụng c&oacute; li&ecirc;n quan đến dịch vụ MegaV.</li>
	<li>Đồng &yacute; v&agrave; thừa nhận việc thu thập, ghi nhận, xử l&iacute;, sử dụng, lưu trữ, chia sẻ th&ocirc;ng tin li&ecirc;n quan của EPAY li&ecirc;n quan đến người sử dụng theo c&aacute;c quy định của Thỏa thuận n&agrave;y.</li>
	<li>Ho&agrave;n trả hoặc phối hợp với EPAY ho&agrave;n trả đầy đủ số tiền thụ hưởng do EPAY chuyển thừa, chuyển nhầm (bao gồm cả lỗi t&aacute;c nghiệp, sự cố hệ thống của EPAY).</li>
	<li>Thực hiện đ&uacute;ng c&aacute;c hướng dẫn an to&agrave;n của EPAY, tự bảo mật th&ocirc;ng tin MegaV, giao dịch do m&igrave;nh quản l&yacute; để đảm bảo an to&agrave;n, bảo mật trong giao dịch thanh to&aacute;n. Người sử dụng ho&agrave;n to&agrave;n tự chịu tr&aacute;ch nhiệm đối với những rủi ro ph&aacute;t sinh do việc r&ograve; rỉ, thất tho&aacute;t th&ocirc;ng tin n&agrave;y.</li>
	<li>Th&ocirc;ng b&aacute;o kịp thời cho EPAY khi ph&aacute;t hiện thấy c&oacute; sai s&oacute;t, nhầm lẫn trong giao dịch thanh to&aacute;n hoặc nghi ngờ th&ocirc;ng tin giao dịch bị lợi dụng.</li>
	<li>Bằng việc cung cấp th&ocirc;ng tin li&ecirc;n lạc khi sử dụng MegaV. Người sử dụng đ&atilde; đồng &yacute; cho ph&eacute;p EPAY được gửi thư điện tử, tin nhắn SMS/MMS hoặc gọi đến số điện thoại Người sử dụng cung cấp để:
	<ul>
		<li>Th&ocirc;ng b&aacute;o biến động số dư t&agrave;i khoản trong trường hợp Người sử dụng đăng k&yacute; sử dụng;</li>
		<li>Th&ocirc;ng b&aacute;o OTP (mật khẩu giao dịch điện tử);</li>
		<li>Giới thiệu, tư vấn, hỗ trợ, th&ocirc;ng b&aacute;o, quảng b&aacute; MegaV v&agrave;/hoặc c&aacute;c dịch vụ kh&aacute;c của EPAY;</li>
		<li>Th&ocirc;ng b&aacute;o c&aacute;c sản phẩm dịch vụ mới, chương tr&igrave;nh khuyến mại;</li>
		<li>Th&ocirc;ng b&aacute;o khắc phục vụ cho việc cung ứng dịch vụ MegaV đến Người sử dụng.</li>
	</ul>
	</li>
	<li>Nếu Người sử dụng cho rằng c&oacute; một sự nhầm lẫn hoặc sai s&oacute;t trong việc xử l&yacute; chỉ dẫn thanh to&aacute;n của EPAY, &nbsp;Người sử dụng c&oacute; thể li&ecirc;n lạc trực tiếp v&agrave; ngay khi ph&aacute;t sinh sự việc tr&ecirc;n với Bộ phận Hỗ trợ kh&aacute;ch h&agrave;ng hoặc c&aacute;c Điểm giao dịch của EPAY m&agrave; Người sử dụng đ&atilde; thực hiện giao dịch để được giải quyết. Những vấn đề c&oacute; thể ph&aacute;t sinh l&agrave;:
	<ul>
		<li>Bất kỳ sự chậm trễ hoặc sai s&oacute;t n&agrave;o trong việc xử l&yacute; chỉ dẫn thanh to&aacute;n của Người sử dụng, hoặc;</li>
		<li>Nếu ph&aacute;t hiện t&agrave;i khoản của Người sử dụng c&oacute; ph&aacute;t sinh giao dịch m&agrave; kh&ocirc;ng do Người sử dụng thưc hiện, hoặc;</li>
		<li>Người sử dụng cho rằng c&oacute; sự gian lận trong việc sử dụng MegaV.</li>
	</ul>
	</li>
	<li>Người sử dụng đồng &yacute; c&oacute; tr&aacute;ch nhiệm nộp/bổ sung/cập nhật bất kỳ th&ocirc;ng tin/chứng từ, phối hợp điều tra khi c&oacute; y&ecirc;u cầu của EPAY. T&ugrave;y thuộc v&agrave;o quyết định của EPAY v&agrave;o từng thời điểm việc chậm trễ v&agrave;/hoặc kh&ocirc;ng nộp/bổ sung/cập nhật bấy kỳ th&ocirc;ng tin/chứng từ, phối hợp điều tra theo y&ecirc;u cầu của EPAY sẽ đến việc EPAY phong tỏa/tạm kh&oacute;a/đ&oacute;ng t&agrave;i khoản hoặc tạm dừng giao dịch đ&atilde; hoặc đang thực hiện. Trong c&aacute;c trường hợp n&agrave;y. Người sử dụng đồng &yacute; EPAY được to&agrave;n quyền xử l&yacute; số tiền li&ecirc;n quan, đồng thời cam kết tu&acirc;n thủ c&aacute;c kết quả xử l&yacute; của EPAY.</li>
	<li>Người sử dụng c&oacute; nghĩa vụ bằng chi ph&iacute; của m&igrave;nh, trang bị đầy đủ, bảo dưỡng thường xuy&ecirc;n nhằm đảm bảo chất lượng cho c&aacute;c loại m&aacute;y m&oacute;c, thiết bị kết nối, phần mềm hệ thống, phần mềm ứng dụng,... để c&oacute; thể kết nối, truy cập an to&agrave;n v&agrave;o dịch vụ.</li>
	<li>Người sử dụng chịu tr&aacute;ch nhiệm &aacute;p dụng mọi biện ph&aacute;p hợp l&yacute; v&agrave;/hoặc theo đ&uacute;ng hướng dẫn của EPAY nhằm đảm bảo an to&agrave;n, đảm bảo t&iacute;nh tương th&iacute;ch cho c&aacute;c loại m&aacute;y m&oacute;c, thiết bị kết nối, phần mềm hệ thống, phần mềm ứng dụng... do Người sử dụng sử dụng khi kết nối truy cập v&agrave;o dịch vụ nhằm kiểm so&aacute;t, ph&ograve;ng ngừa v&agrave; ngăn chặn việc sử dụng hoặc truy cập tr&aacute;i ph&eacute;p dịch vụ .</li>
	<li>Nếu Người sử dụng tiếp nhận được th&ocirc;ng tin về kh&aacute;ch h&agrave;ng của EPAY khi sử dụng MegaV d&ugrave; dưới bất k&igrave; h&igrave;nh thức n&agrave;o, theo bất kỳ phương thức n&agrave;o. Người sử dụng phải bảo mật th&ocirc;ng tin, kh&ocirc;ng được tiết lộ hoặc ph&aacute;t t&aacute;n th&ocirc;ng tin n&agrave;y đến người kh&aacute;c v&agrave;/hoặc d&ugrave;ng n&oacute; v&agrave;o mục đ&iacute;ch kh&aacute;c trừ khi c&oacute; sự đồng &yacute; của ch&iacute;nh người đ&oacute;.</li>
	<li>Người sử dụng cần hiểu rằng Người sử dụng phải chịu tr&aacute;ch nhiệm với mọi giao dịch xuất ph&aacute;t từ Người sử dụng khi sử dụng dịch vụ MegaV. Trường hợp Người sử dụng vi phạm Thỏa Thuận v&agrave; g&acirc;y tổn thất/thiệt hạn. Người sử dụng c&oacute; tr&aacute;ch nhiệm bồi thường cho EPAY, Kh&aacute;ch h&agrave;ng kh&aacute;c, b&ecirc;n thứ ba c&oacute; li&ecirc;n quan.</li>
	<li>C&aacute;c tr&aacute;ch nhiệm kh&aacute;c theo quy định của ph&aacute;p luật v&agrave; EPAY.</li>
</ol>

<p><strong>ĐIỀU 6. CAM KẾT CỦA NGƯỜI SỬ DỤNG MEGAV VỚI VAI TR&Ograve; NGƯỜI B&Aacute;N </strong></p>

<p>Khi sử dụng MegaV với v&agrave;i tr&ograve; l&agrave; Người B&aacute;n, ngo&agrave;i c&aacute;c tr&aacute;ch nhiệm v&agrave; cam kết chung của Người Sử dụng, Kh&aacute;ch h&agrave;ng l&agrave; Người b&aacute;n phải cam kết:</p>

<ol>
	<li>Cung cấp cho Người mua đầy đủ th&ocirc;ng tin về h&agrave;ng h&oacute;a/dịch vụ v&agrave; ch&iacute;nh s&aacute;ch b&aacute;n h&agrave;ng trung thực, b&aacute;n h&agrave;ng đ&uacute;ng gi&aacute; trị đ&atilde; thanh to&aacute;n, giao h&agrave;ng h&oacute;a/dịch vụ theo đ&uacute;ng thời hạn quy định.</li>
	<li>Ho&agrave;n to&agrave;n chịu tr&aacute;ch nhiệm về h&agrave;ng h&oacute;a/dịch vụ giao dịch vứi Người mua; chỉ sử dụng MegaV để chấp nhận thanh to&aacute;n cho c&aacute;c h&agrave;ng h&oacute;a/dịch vụ hợp ph&aacute;p; tuyệt đối tu&acirc;n thủ c&aacute;c quy định, ch&iacute;nh s&aacute;ch li&ecirc;n quan đến việc sử dụng v&agrave; thanh to&aacute;n th&ocirc;ng qua MegaV; kh&ocirc;ng thực hiện c&aacute;c h&agrave;nh vi vi phạm ph&aacute;p luật, mua b&aacute;n c&aacute;c h&agrave;ng h&oacute;a/dịch vụ c&oacute; nguồn gốc kh&ocirc;ng hợp ph&aacute;p.</li>
	<li>KH&Ocirc;NG y&ecirc;u cầu Người mua cung cấp c&aacute;c th&ocirc;ng tin c&aacute;c nh&acirc;n như: Số thẻ t&iacute;n dụng, mật khẩu đăng nhập MegaV, t&agrave;i khoản ng&acirc;n h&agrave;ng,...</li>
	<li>Gi&aacute; trị thanh to&aacute;n phải đ&atilde; bao gồm đầy đủ c&aacute;c loại ph&iacute; hợp lệ nếu c&oacute; (vận chuyển, dịch vụ&hellip;) v&agrave; thuế theo quy định của ph&aacute;p luật.</li>
	<li>Cung cấp cho Người mua những ch&iacute;nh s&aacute;ch đổi/trả v&agrave; bảo h&agrave;nh một c&aacute;ch r&otilde; r&agrave;ng v&agrave; hợp l&yacute;, cung cấp cho người Mua v&agrave;/hoặc EPAY nội dung c&aacute;c ch&iacute;nh s&aacute;ch đ&oacute; khi được y&ecirc;u cầu v&agrave; c&oacute; tr&aacute;ch nhiệm th&ocirc;ng b&aacute;o mỗi khi c&oacute; thay đổi hoặc cập nhật.</li>
	<li>Trường hợp xảy ra sai s&oacute;t trong qu&aacute; tr&igrave;nh thanh to&aacute;n, cung ứng h&agrave;ng h&oacute;a/dịch vụ do lỗi của Kh&aacute;ch h&agrave;ng th&igrave; Kh&aacute;ch h&agrave;ng phải chịu tr&aacute;ch nhiệm ho&agrave;n trả, bồi thường v&agrave; giải tr&igrave;nh cho Người Mua v&agrave; EPAY. Việc ho&agrave;n trả, bồi thường phải được thực hiện theo quy định của EPAY hoặc theo thỏa thuận giữa EPAY v&agrave; Kh&aacute;ch h&agrave;ng.</li>
	<li>Khi nhận được th&ocirc;ng b&aacute;o của EPAY về giao dịch Thanh to&aacute;n Kh&aacute;ch h&agrave;ng c&oacute; tr&aacute;ch nhiệm kiểm tra v&agrave; phải chuyển h&agrave;ng h&oacute;a hoăc thực hiện dịch vụ cho người mua theo đ&uacute;ng chất lượng v&agrave; thời hạn đ&atilde; cam kết đồng thời lưu lại c&aacute;c chứng từ hợp lệ như h&oacute;a đơn b&aacute;n h&agrave;ng hoặc phiếu giao h&agrave;ng c&oacute; chữ k&yacute; Người Mua, vận đơn của h&agrave;ng vận chuyển &hellip; để sử dụng trong trường hợp xảy ra khiếu nại.</li>
	<li>Trong trường hợp tiền đ&atilde; được chuyển đến Người B&aacute;n nhưng sau đ&oacute; bị Người Mua khiếu nại l&agrave; kh&ocirc;ng thực hiện giao dịch một c&aacute;ch nghi&ecirc;m t&uacute;c theo cam kết th&igrave; khi đ&oacute;, khiếu nại sẽ được giải quyết theo quy định về giải quyết khiếu nại của EPAY.</li>
	<li>Kh&aacute;ch h&agrave;ng sẽ phải bồi thường cho EPAY v&agrave; tr&aacute;nh cho EPAY kh&ocirc;ng bị tổn hại từ bất kỳ tổn thất, thiệt hại v&agrave; c&aacute;c tr&aacute;ch nhiệm li&ecirc;n quan đến bấy kỳ h&agrave;nh động, khiếu nại, kiện c&aacute;o hoặc tố tụng của người Mua trong phạm vi c&aacute;c tổn thất, thiệt hại v&agrave; tr&aacute;ch nhiệm n&agrave;y xuất ph&aacute;t từ hoạt động cung ứng h&agrave;ng h&oacute;a/dịch vụ của Kh&aacute;ch h&agrave;ng, bao gồm nhưng kh&ocirc;ng giới hạn từ c&aacute;c tr&aacute;ch nhiệm v&agrave; cam kết n&ecirc;u tại Thỏa thuận n&agrave;y.</li>
	<li>KH&Ocirc;NG sử dụng hoặc lợi dụng việc sử dụng MegaV v&agrave; c&aacute;c hoạt động vi phạm ph&aacute;p luật, chịu sự gi&aacute;m s&aacute;t của EPAY đối với c&aacute;c hoạt động kinh doanh trực tuyến được thanh to&aacute;n th&ocirc;ng qua MegaV, đồng thời chịu tr&aacute;ch nhiệm trước ph&aacute;p luật về c&aacute;c h&agrave;nh vi vi phạm (nếu c&oacute;).</li>
	<li>Tuần thủ những quy định li&ecirc;n quan kh&aacute;c được EPAY &aacute;p dụng cho Người b&aacute;n khi thực hiện thanh to&aacute;n quay MegaV.</li>
	<li>C&aacute;c tr&aacute;ch nhiệm, cam kết kh&aacute;c theo quy định tại c&aacute;c hợp đồng hợp t&aacute;c cụ thể.</li>
	<li>Bồi thường to&agrave;n bộ thiệt hại cho EPAY khi vi phạm c&aacute;c tr&aacute;ch nhiệm quy định tại Thỏa thuận n&agrave;y.</li>
</ol>

<p><strong>ĐIỀU 7. QUYỀN CỦA EPAY</strong></p>

<ol>
	<li>EPAY được tự động tr&iacute;ch nợ từ MegaV của Kh&aacute;ch h&agrave;ng trong trường hợp:</li>
	<li>Từ chối c&aacute;c lệnh thanh to&aacute;n của Kh&aacute;ch h&agrave;ng trong c&aacute;c trường hợp:</li>
	<li>Từ chối y&ecirc;u cầu tạm kh&oacute;a, đ&oacute;ng V&iacute; điện tử của &nbsp;Chủ V&iacute; khi &nbsp;Chủ V&iacute; chưa ho&agrave;n th&agrave;nh nghĩa vụ thanh to&aacute;n theo quyết định của cơ quan nh&agrave; nước c&oacute; thẩm quyền hoặc chưa thanh to&aacute;n xong c&aacute;c khoản nợ phải trả cho EPAY.</li>
	<li>Quy định về số dư tối thiểu tr&ecirc;n MegaV t&ugrave;y theo từng trường hợp sử dụng.</li>
	<li>Quy định v&agrave; &aacute;p dụng c&aacute;c biện ph&aacute;p đảm bảo an to&agrave;n, bảo mật trong qu&aacute; tr&igrave;nh mở v&agrave; sử dụng t&agrave;i khoản thanh to&aacute;n ph&ugrave; hợp của ph&aacute;p luật.</li>
	<li>EPAY kh&ocirc;ng chịu tr&aacute;ch nhiệm đối với những thiệt hại, tổn thất của Người sử dụng ph&aacute;t sinh trong qu&aacute; tr&igrave;nh sử dụng dịch vụ, trừ những thiệt hại tổn thất n&agrave;y l&agrave; do lỗi cố &yacute; của EPAY.</li>
	<li>EPAY kh&ocirc;ng chịu tr&aacute;ch nhiệm đối với những thiệt hại trực tiếp hoặc gi&aacute;n tiếp m&agrave; Người sử dụng phải chịu ph&aacute;t sinh từ hoặc do:
	<ol>
		<li>Việc sử dụng MegaV hoặc tiếp cận c&aacute;c th&ocirc;ng tin m&agrave; của những người được Người sử&nbsp; dụng MegaV ủy quyền; hoặc</li>
		<li>Việc Người sử dụng để mất, mất cắp, lộ t&ecirc;n truy cập, mật khẩu v&agrave;/hoặc c&aacute;c yếu tố định danh kh&aacute;c m&agrave; EPAY cung cấp dẫn đến người kh&aacute;c d&ugrave;ng những th&ocirc;ng tin n&agrave;y để sử dụng MegaV hoặc tiếp cận những th&ocirc;ng tin m&agrave; dịch vụ MegaV cung ứng; hoặc</li>
		<li>Bất kỳ sự chậm trễ n&agrave;o trong việc gửi tin nhắn, hoặc Người sử dụng kh&ocirc;ng nhận được tin nhắn; hoặc</li>
		<li>T&iacute;nh to&agrave;n vẹn, t&iacute;nh x&aacute;c thực của tin nhắn được gửi cho Người sử dụng; hoặc</li>
		<li>Việc tin nhắn được thực hiện bởi một b&ecirc;n thứ ba m&agrave; người n&agrave;y, bằng bất cứ c&aacute;ch n&agrave;o, thực hiện việc kết nối thiết bị của họ tới số điện thoại m&agrave; Người sử dụng đ&atilde; đăng k&yacute;; hoặc</li>
		<li>Sự ngắt qu&atilde;ng, tr&igrave; ho&atilde;n, chậm trễ, t&igrave;nh trạng kh&ocirc;ng sẵn s&agrave;ng sử dụng hoặc bất kỳ sự cố n&agrave;o xảy ra trong qu&aacute; tr&igrave;nh cung cấp dịch vụ do c&aacute;c nguy&ecirc;n nh&acirc;n ngo&agrave;i khả năng kiểm so&aacute;t hợp l&yacute; của EPAY, bao gồm nhưng kh&ocirc;ng giới hạn ở t&igrave;nh trạng gi&aacute;n đoạn do MegaV cần được n&acirc;ng cấp, sửa chữa, lỗi đường truyền của nh&agrave; cung cấp dịch vụ Internet; lỗi gi&aacute;n đoạn do nh&agrave; cung cấp h&agrave;ng h&oacute;a/dịch vụ; hoặc</li>
		<li>Bất cứ trường hợp ph&aacute;t sinh c&aacute;c sự kiện bất khả kh&aacute;ng n&agrave;o, bao gồm nhưng kh&ocirc;ng giới hạn bởi c&aacute;c sự kiện thi&ecirc;n tai như lũ lụt, hỏa hoạn, hạn h&aacute;n, b&atilde;o, động đất; c&aacute;c sự kiện x&atilde; hội như: biểu t&igrave;nh, bạo động, bạo loạn v&agrave; chiến tranh (tuy&ecirc;n bố hoặc kh&ocirc;ng tuy&ecirc;n bố), c&aacute;c hoạt động của c&aacute;c ch&iacute;nh phủ trong phạm vi thẩm quyền.</li>
	</ol>
	</li>
	<li>C&aacute;c quyền kh&aacute;c theo quy định của ph&aacute;p luật hoặc theo thỏa thuận trước bằng văn bản giữa &nbsp;Chủ V&iacute; với EPAY kh&ocirc;ng tr&aacute;i với quy định của ph&aacute;p luật hiện h&agrave;nh.</li>
</ol>

<p><strong>ĐIỀU 8. TR&Aacute;CH NHIỆM CỦA EPAY</strong></p>

<ol>
	<li>Thực hiện lệnh thanh to&aacute;n của người sở hữu MegaV sau khi đ&atilde; kiểm tra, kiểm so&aacute;t t&iacute;nh hợp ph&aacute;p, hợp lệ của lệnh thanh to&aacute;n.</li>
	<li>Thực hiện đầy đủ, kịp thời c&aacute;c lệnh thanh to&aacute;n, c&aacute;c y&ecirc;u cầu sử dụng MegaV của Người sử dụng ph&ugrave; hợp quy định v&agrave; thỏa thuận giữa EPAY v&agrave; Người sử dụng; ho&agrave;n trả kịp thời c&aacute;c khoản tiền do sai s&oacute;t, nhầm lẫn trong việc thực hiện c&aacute;c lệnh thanh to&aacute;n; cung ứng đầy đủ kịp thời phương tiện thanh to&aacute;n cần thiết phục vụ nhu cầu giao dịch bằng MegaV của Người sử dụng.</li>
	<li>Th&ocirc;ng tin đầy đủ, kịp thời về số dư v&agrave; c&aacute;c giao dịch ph&aacute;t sinh tr&ecirc;n MegaV theo thỏa thuận với Người sở hữu V&iacute; v&agrave; chịu tr&aacute;ch nhiệm về t&iacute;nh ch&iacute;nh x&aacute;c đối với những th&ocirc;ng tin m&agrave; m&igrave;nh cung cấp.</li>
	<li>Cập nhật kịp thời c&aacute;c th&ocirc;ng tin khi c&oacute; th&ocirc;ng b&aacute;o thay đổi nội dung trong hồ sơ mở MegaV của Chủ V&iacute;. Bảo quản, lưu trữ hồ sơ mở MegaV v&agrave; c&aacute;c chứng từ giao dịch V&iacute; điện tử theo đ&uacute;ng quy định của ph&aacute;p luật.</li>
	<li>Đảm bảo b&iacute; mật c&aacute;c th&ocirc;ng tin li&ecirc;n quan đến MegaV v&agrave; giao dịch tr&ecirc;n MegaV của &nbsp;Chủ V&iacute; theo quy định của ph&aacute;p luật.</li>
	<li>Chịu tr&aacute;ch nhiệm về những thiệt hại do sai s&oacute;t hoặc bị lợi dụng, lừa đảo tr&ecirc;n MegaV của Người sử dụng do lỗi của m&igrave;nh.</li>
	<li>Tu&acirc;n thủ quy định ph&aacute;p luật về ph&ograve;ng, chống rửa tiền.</li>
	<li>X&acirc;y dựng quy tr&igrave;nh nội bộ về việc cung ứng dịch vụ MegaV; hướng dẫn, th&ocirc;ng b&aacute;o c&ocirc;ng khai để Người sử dụng biết v&agrave; giải đ&aacute;p, xử l&yacute; kịp thời c&aacute;c thắc mắc, khiếu nại trong qu&aacute; tr&igrave;nh đăng k&yacute; v&agrave; sử dụng MegaV.</li>
</ol>

<p><strong>ĐIỀU 9. CHẤM DỨT SỬ DỤNG DỊCH VỤ MEGAV</strong></p>

<ol>
	<li>EPAY c&oacute; quyền thay đổi, ngừng hoặc chấm dứt cung cấp dịch vụ MegaV v&agrave;o bất kỳ thời điểm n&agrave;o m&agrave; kh&ocirc;ng cần c&oacute; sự đồng &yacute; của Kh&aacute;ch h&agrave;ng. Tuy nhi&ecirc;n, EPAY sẽ th&ocirc;ng b&aacute;o cho Kh&aacute;ch h&agrave;ng về việc thay đổi, ngưng hoặc chấm dứt cung cấp dịch vụ MegaV.</li>
	<li>Kh&aacute;ch h&agrave;ng c&oacute; thể chấm dứt sử dụng dịch vụ MegaV v&agrave;o bất kỳ l&uacute;c n&agrave;o sau khi đ&atilde; gửi cho EPAY văn bản y&ecirc;u cầu ngưng sử dụng dịch vụ MegaV theo mẫu v&agrave; hướng dẫn m&agrave;&nbsp; EPAY cung cấp tại website hoặc c&aacute;c điểm giao dịch của MegaV. Sau khi chấm dứt sử dụng MegaV, Kh&aacute;ch h&agrave;ng kh&ocirc;ng được tiếp tục sử dụng t&ecirc;n truy cập, mật khẩu v&agrave;/hoặc c&aacute;c yếu tố định danh kh&aacute;c do EPAY cung cấp.</li>
	<li>Tất cả c&aacute;c điều khoản v&agrave; điều kiện n&agrave;y vẫn c&oacute; hiệu lực sau khi Kh&aacute;ch h&agrave;ng ngưng hoặc chấm dứt d&ugrave;ng MegaV. Ngay cả khi đ&atilde; ngừng, chấm dứt sử dụng MegaV, Kh&aacute;ch h&agrave;ng vẫn bị r&agrave;ng buộc bởi c&aacute;c điều khoản v&agrave; điều kiện n&agrave;y ở phạm vi li&ecirc;n quan đến quyền v&agrave; tr&aacute;ch nhiệm của Kh&aacute;ch h&agrave;ng trong thời gian sử dụng MegaV.</li>
</ol>

<p><strong>ĐIỀU 10. CHẾ ĐỘ PH&Iacute;</strong></p>

<ol>
	<li>Ph&iacute; sử dụng dịch vụ MegaV căn cứ theo biểu ph&iacute; được EPAY c&ocirc;ng bố cho từng thời kỳ t&ugrave;y theo đặc điểm của từng loại dịch vụ.</li>
	<li>Kh&aacute;ch h&agrave;ng ủy quyền cho EPAY được quyền tự động tr&iacute;ch nợ ph&iacute; sử dụng MegaV tr&ecirc;n bất kỳ V&iacute; điện tử n&agrave;o của Kh&aacute;ch h&agrave;ng được mở tại EPAY để thanh to&aacute;n tiền ph&iacute; sử dụng dịch vụ n&agrave;y.</li>
	<li>Ph&iacute; Dịch vụ c&oacute; thể được thu trước, sau hay ngay khi ph&aacute;t sinh giao dịch t&ugrave;y v&agrave;o từng loại h&igrave;nh dịch vụ m&agrave; EPAY cung cấp. Đối với h&igrave;nh thức thu ph&iacute; dịch vụ trước, Kh&aacute;ch h&agrave;ng c&oacute; thể kh&ocirc;ng được ho&agrave;n trả lại số tiền chưa sử dụng hết trong trường hợp Kh&aacute;ch h&agrave;ng kh&ocirc;ng c&oacute; nhu cầu sử dụng tiếp MegaV. Kh&aacute;ch h&agrave;ng c&oacute; những thay đổi về th&ocirc;ng tin định danh dẫn đến việc kh&ocirc;ng thể sử dụng tiếp số tiền chưa sử dụng.</li>
	<li>C&aacute;c khoản tiền bồi ho&agrave;n/ho&agrave;n trả sẽ được trừ c&aacute;c khoản ph&iacute; li&ecirc;n quan đến việc bồi ho&agrave;n/ho&agrave;n trả trước khi bồi ho&agrave;n/ho&agrave;n trả.</li>
</ol>

<p><strong>ĐIỀU 11 QUYỀN SỞ HỮU TR&Iacute; TUỆ</strong></p>

<p>C&aacute;c website (bao gồm v&agrave; kh&ocirc;ng giới hạn c&aacute;c t&ecirc;n miền), ứng dụng, giải ph&aacute;p, c&aacute;c h&agrave;ng h&oacute;a/dịch vụ, logo v&agrave; những nội dung kh&aacute;c li&ecirc;n quan đến EPAY v&agrave; MegaV đều thuộc quyền quản l&yacute;, sở hữu v&agrave; sử dụng hợp ph&aacute;p, to&agrave;n vẹn v&agrave; kh&ocirc;ng chuyển giao của EPAY. Kh&aacute;ch h&agrave;ng phải t&ocirc;n trọng c&aacute;c quyền n&agrave;y v&agrave; EPAY nghi&ecirc;m cấm mọi h&agrave;nh vi x&acirc;m phạm quyền sở hữu tr&iacute; tuệ của EPAY.</p>

<p><strong>ĐIỀU 12. CHUYỀN NHƯỢNG</strong></p>

<p>Khi chưa c&oacute; sự đồng &yacute; bằng văn bản của EPAY, Kh&aacute;ch h&agrave;ng kh&ocirc;ng được quyền chuyển nhượng bất cứ quyền v&agrave; nghĩa vụ n&agrave;o của Kh&aacute;ch h&agrave;ng đ&atilde; x&aacute;c định trong bản Thỏa thuận n&agrave;y.</p>

<p><strong>ĐIỀU 13. PH&Aacute;P LUẬT ĐIỀU CHỈNH V&Agrave; GIẢI QUYẾT TRANH CHẤP</strong></p>

<ol>
	<li>Thỏa thuận n&agrave;y được điểu chỉnh theo ph&aacute;p luật Việt Nam.</li>
	<li>Mọi tranh chấp, bất đồng ph&aacute;t sinh từ, v&agrave;/hoặc li&ecirc;n quan đến MegaV, c&aacute;c b&ecirc;n li&ecirc;n quan sẽ c&ugrave;ng nhau b&agrave;n bạc giải quyết tr&ecirc;n cơ sở thương lượng đảm bảo quyền lợi của c&aacute;c b&ecirc;n. Nếu kh&ocirc;ng giải quyết được bằng thương lượng, c&aacute;c b&ecirc;n thống nhất tranh chấp hoặc bất đồng đ&oacute; sẽ được giải quyết tại t&ograve;a &aacute;n c&oacute; thẩm quyền. Quyết định của T&ograve;a &aacute;n c&oacute; gi&aacute; trị rằng buộc c&aacute;c thi h&agrave;nh. Trong thời gian t&ograve;a &aacute;n chưa đưa ra ph&aacute;n quyết, c&aacute;c b&ecirc;n vẫn phải tiếp tục thực hiện nghĩa vụ v&agrave; tr&aacute;ch nhiệm của mỗi b&ecirc;n theo quy định của Thỏa thuận. C&aacute;c chi ph&iacute; li&ecirc;n quan đến việc giải quyết tranh chấp do b&ecirc;n thua kiện theo theo ph&aacute;n quyết của t&ograve;a &aacute;n phải chịu.</li>
</ol>

<p>&nbsp;</p>

<p>&nbsp;</p>

			
      </div>
    </div>
  </div>
</div>

 
<div id="loader-bg" style="<?php echo isset($showlogin) ? 'display:block' : 'display:none'; ?>">
	
</div>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.min.css"; ?>'/>
	<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.css"; ?>'/>
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/jquery.flexisel.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/slick.min.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/metisMenu.min.js"; ?>'></script>
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/bootstrap.min.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/script.js"; ?>'></script>
	
	<script>
		$('#IframeId-login').load(function() {
			this.style.height =
			this.contentWindow.document.body.offsetHeight + 'px';
			
		});
		
		$('#IframeId-register').load(function() {
			//this.style.height = this.contentWindow.document.body.offsetHeight + 'px'; 
			if ($(this).contents().find('#site_button').attr('data-check-otp')==1) {
				$(this).addClass('regis-new-layout');
			}
		
		});
		
		var ua = navigator.userAgent, 
		pickclick = (ua.match(/iPad/i) || ua.match(/iPhone/)) ? "touchstart" : "click";

		//var iframeContent = $('#IframeId-register').contents().find('#dksd');
		
		$('#IframeId-register').load(function(){

			var iframe = $('#IframeId-register').contents();
				//var height =  iframe.height();
				iframe.find("#dksd").click(function(){
					$('#myModal1').modal();
				});
				
				iframe.find("#csqrt").click(function(){
					$('#myModal').modal();
				});
		});
		
		
	</script>
	<script type="text/javascript" language="javascript">
	window._sbzq||function(e){e._sbzq=[];var t=e._sbzq;t.push(["_setAccount",18144]);var n=e.location.protocol=="https:"?"https:":"http:";var r=document.createElement("script");r.type="text/javascript";r.async=true;r.src=n+"//static.subiz.com/public/js/loader.js?v=";var i=document.getElementsByTagName("script")[0];i.parentNode.insertBefore(r,i)}(window);
	</script>
	
	<style>
		.modal{
			z-index: 16000011
		}
		.modal-backdrop{
			z-index: 16000010
		}
		
	</style>
	
</body>
</html>
