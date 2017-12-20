
<div class="container-fluid trans_history_container">
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li class="first">|</li>
				<li><a href="#">Trung tâm hỗ trợ</a></li>
			</ul> 

			<ul class="nav nav-tabs tab_trans_history owl-carousel" role="tablist" data-dots="false" data-loop="false" data-nav="true" data-margin="0" data-autoplayTimeout="1000" data-autoplayHoverPause="true" data-responsive='{"0":{"items":3},"420":{"items":5},"600":{"items":7}}'>
				<!--li role="presentation" class="<?php echo (isset($tab) && $tab == "video" ) ? 'active' : ""; ?>"><a href="javascript:void(0)" data-target="#video" aria-controls="video" role="tab" data-toggle="tab">Video hướng dẫn</a></li-->
				<li role="presentation" class="<?php echo (isset($tab) && $tab == "info" ) ? 'active' : ""; ?>"><a href="javascript:void(0)" data-target="#info" aria-controls="info" role="tab" data-toggle="tab">Thông tin liên hệ</a></li>
				<li role="presentation" class="disabled <?php echo (isset($tab) && $tab == "chinhsach" ) ? 'active' : ""; ?>"><a href="javascript:void(0)" data-target="#chinhsach" >Chính sách</a></li>
				<li role="presentation" class="disabled <?php echo (isset($tab) && $tab == "quydinh" ) ? 'active' : ""; ?>"><a href="javascript:void(0)" data-target="#quydinh" >Quy định</a></li>
				<li role="presentation" class="disabled <?php echo (isset($tab) && $tab == "hoidap" ) ? 'active' : ""; ?>"><a href="javascript:void(0)" data-target="#hoidap" >Hỏi & đáp</a></li>
			</ul>
			
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="tab-content" style="overflow-y: auto; max-height: 445px;">
						<!--div role="tabpanel" class="tab-pane <?php echo (isset($tab) && $tab == "video" ) ? 'active' : ""; ?>" id="video">
							<div class="video col-lg-6 col-md-6 col-sm-6 col-xs-12">
								
							</div>
							
						</div-->
						
						<div role="tabpanel" class="tab-pane <?php echo (isset($tab) && $tab == "info" ) ? 'active' : ""; ?>" id="info">
							<div class="information">
								<div class="branch">
									<span class="branch-name">Hotline: </span><span style="font-family: Roboto Medium; font-size: 20px;">19006470</span>
								</div>
								<div class="branch">
									<span class="branch-name">Email: </span>support@vnptepay.com.vn
								</div>
								<div class="branch">
									<p class="branch-name">Trụ sở Hà Nội:</p>
									<p>Tầng 3 - Tòa nhà Viễn Đông 36 Hoàng Cầu, Quận Đống Đa, Hà Nội</p>
									<!--p><span class="info-phone">Điện thoại</span><span>: + 844-39335133</span></p>
									<p><span class="info-phone">Fax</span><span>: + 844-39335166</span></p-->
								</div>
								
								<div class="branch">
									<p class="branch-name">Chi nhánh tại TPHCM:</p>
									<p>Lầu 3 - số 96 - 98 Đào Duy Anh, Phường 9, Quận Phú Nhuận, TP Hồ Chí Minh</p>
									<!--p><span class="info-phone">Điện thoại</span><span>: + 848-39976677</span></p>
									<p><span class="info-phone">Fax</span><span>: + 844-38443786</span></p-->
								</div>
							</div>
						</div>
						
						<!--div role="tabpanel" class="tab-pane <?php echo (isset($tab) && $tab == "chinhsach" ) ? 'active' : ""; ?>" id="chinhsach">
						</div>
						
						<div role="tabpanel" class="tab-pane <?php echo (isset($tab) && $tab == "quydinh" ) ? 'active' : ""; ?>" id="quydinh">
						</div>
						
						<div role="tabpanel" class="tab-pane <?php echo (isset($tab) && $tab == "hoidap" ) ? 'active' : ""; ?>" id="hoidap">
						</div-->
						
					</div>
				</div>	
			</div>
		</div>
	</div>
</div>

<style>
.information{
	color: #cccccc;
	font-family: roboto light;
	padding: 0 30px;
}
	

.information > .branch > .branch-name{
	font-family: roboto medium;
	color: #fff;
}

.information > .branch{
	margin-bottom: 30px;
}

.info-phone{
	display: inline-block;
    width: 80px;
	color: #999999;
}

.video{
	height: 400px;
}

@media screen and (min-width:768px){
	.video{
		height: 350px;
	}
}
@media screen and (max-width:578px){
	.video{
		height: 250px;
	}
}
</style>
