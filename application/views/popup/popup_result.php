	<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: block;">
	  <div class="modal-dialog" role="document">
		<div class="modal-content modal-security">
			<div class="modal-header modal-header-security">
				<h4 class="modal-title" id="myModalLabel" style="float: left;">Thông báo từ hệ thống</h4>
				<?php if(!isset($redirect)): ?>
					<button type="button" class="close-modal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<?php else: ?>
					<a href="<?php echo base_url() . 'transaction_manage' ?>"><button type="button" class="close-modal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></a>
				<?php endif; ?>
			</div>
			<div class="modal-body modal-body-security">
				<img src="<?php echo base_url() . "/images/Icon-Done.png"; ?>" class="img-responsive header" style="width: 100px; margin: 0px auto;">
				<div class="txt_security" style="text-align: center;"><?php echo isset($mess) ? $mess : ''; ?></div>
			</div>
		</div>
	  </div>
	</div>
<div class="modal-backdrop fade in"  style="background: transparent;"></div>

<style>
.close-modal{
	background: transparent none repeat scroll 0 0;
    border: medium none;
    float: right;
    font-size: 25px;
    line-height: 20px;
}
</style>

<script>
	$(document).ready(function () {
		$('.close-modal').click(function() {
			$('.modal-backdrop, .modal').removeClass('in');
			setTimeout(function () { $('.modal').css('display', ''); }, 500);
			setTimeout(function () { $('.modal-backdrop').css('display', 'none'); }, 500);
		})
	});
</script>
