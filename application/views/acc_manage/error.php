<div class="row">
    <div class="row">

	

			<div class="col-md-12 text-center">
				<input type="hidden" id="get_balance_update" value="<?php if(isset($balance) && $balance !=''){
			echo number_format($balance);
		}?>"/>
				<img src="<?php echo base_url();?>images/resulr-warning.png">
			    
			    <p style="margin:20px 0px;">Có lỗi trong quá trình upload ảnh lên media server. Vui lòng thử lại.</p>
			    <?php if(isset($redirect_link)): ?>
					<a class="btn btn-accept" href="<?php echo $redirect_link; ?>"><?php echo isset($buttonNameRediect) ? $buttonNameRediect : 'QUAY LẠI' ?></a>
		        <?php else: ?>
					
		        <?php endif; ?>
				
				<?php if(isset($cancelUrl) && !empty($cancelUrl)): ?>
		        <a href="<?php echo base_url($cancelUrl); ?>" class="btn btn-accept"><?php echo isset($buttonNameCancel) ? $buttonNameCancel : '' ?></a>
		        <?php endif; ?>
				
				<?php if(isset($targetIframeLink)): ?>
					<a href="<?php echo $targetIframeLink; ?>" target="_parent" class="btn btn-accept">Tiếp tục</a>
				<?php endif ?>
			        		
							
		</div>

        
    </div>
</div>


	<?php if(isset($timeRedirect)): ?>
		<?php if(isset($redirect_link)): ?>
			<script>
					setTimeout(function () {
					   window.location.href = "<?php echo $redirect_link; ?>"; 
					}, <?php echo $timeRedirect; ?>);
			</script>
		<?php endif; ?>
		<?php if(isset($targetIframeLink)): ?>
			<script>
					setTimeout(function () {
					   window.top.location = "<?php echo $targetIframeLink; ?>"; 
					}, <?php echo $timeRedirect; ?>);
			</script>
		<?php endif; ?>
		<?php $second = $timeRedirect/1000 + 1; ?>
		<script>
			$(document).ready(function($) {
				countdown_number = <?php echo $second; ?>;
				countdown_trigger();
				
			});
			
			function countdown_trigger() {
			  if (countdown_number > 0) {
				  countdown_number--;
		 
				  $('#countdown_text').html(countdown_number);
		 
				  if(countdown_number > 0) {
					  countdown = setTimeout('countdown_trigger()', 1000);
				  }
			  }
			}
		</script>
	<?php endif; ?>

<script>
$(document).ready(function($) {

	$('#wrapper', parent.document).addClass('toggled');
	 
});

</script>
