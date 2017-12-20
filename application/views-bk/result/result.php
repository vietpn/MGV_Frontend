<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px;">
    
        <?php  echo $mess; ?>
    
    </div>
    <div class="row">
	<input type="hidden" id="get_balance_update" value="<?php if(isset($balance) && $balance !=''){
			echo number_format($balance);
		}?>"/>
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
	var balance = document.getElementById("get_balance_update").value;

	if (balance != '') {
		window.parent.document.getElementsByClassName("balance")[0].innerHTML = balance + ' đ';
	}
	
	 
});

</script>
