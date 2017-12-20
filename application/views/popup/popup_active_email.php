	<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: block;">
	  <div class="modal-dialog" role="document">
		<div class="modal-content modal-security">
			<div class="modal-header modal-header-security">
				<h4 class="modal-title" id="myModalLabel" style="float: left;">Thông báo từ hệ thống</h4>
				 <!--button type="button" class="close-modal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button-->
			</div>
			<div class="modal-body modal-body-security">
				<div class="group-security">
					<div class="security_info" style="float: left">
				
						<div class="txt_security" ><?php echo isset($mess) ? $mess : ''; ?></div>
						
						<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
							<div class="col-md-6 col-lg-6 col-xs-6 col-sm-6" style="padding-right: 2px;">
								<a class="btn btn-accept pull-right" href="/change_email/sendMailActive" >Xác thực</a>
							</div>
							<div class="col-md-6 col-lg-6 col-xs-6 col-sm-6" style="padding-left: 2px;">
								<a id="closemodal" class="btn btn-deny close-modal">Chưa xác thực</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	  </div>
	</div>
<div class="modal-backdrop fade in"  style="background: transparent;"></div>

<!--style>
.close-modal{
	background: transparent none repeat scroll 0 0;
    border: medium none;
    float: right;
    font-size: 25px;
    line-height: 20px;
}
</style-->

<script>
	$(document).ready(function () {
		$('.close-modal').click(function() {
			$('.modal-backdrop, .modal').removeClass('in');
			setTimeout(function () { $('.modal').css('display', ''); }, 500);
			setTimeout(function () { $('.modal-backdrop').css('display', 'none'); }, 500);
		})
	});
</script>
