	<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: block;">
	  <div class="modal-dialog" role="document">
		<div class="modal-content modal-security">
			<div class="modal-header modal-header-security">
				<h4 class="modal-title" id="myModalLabel" style="float: left;">Thông báo từ hệ thống</h4>
			</div>
			<div class="modal-body modal-body-security">
				<img src="<?php echo base_url() . "/images/Icon-Warning.png"; ?>" class="img-responsive header" style="width: 100px; margin: 0px auto;">
				<div class="txt_security" style="text-align: center;"><?php echo isset($mess) ? $mess : ''; ?></div>
			
				<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 form-group" style="text-align: center;">
					
					<a id="btn-add_bank" class="btn btn-accept" href="/acc_manage" >Thêm thông tin tài khoản ngân hàng</a>
					
				</div>				
			</div>
		</div>
	  </div>
	</div>
<div class="modal-backdrop fade in"  ></div>


<script>
	$(document).ready(function () {
		$('.close-modal').click(function() {
			$('.modal-backdrop, .modal').removeClass('in');
			setTimeout(function () { $('.modal').css('display', ''); }, 500);
			setTimeout(function () { $('.modal-backdrop').css('display', 'none'); }, 500);
		})
	});
</script>
