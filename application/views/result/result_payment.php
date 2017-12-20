<div>
    <div>
    <label>
        <h4><b><?php   echo $mess; ?></b></h4>
    </label>
    </div>
    <div class="row">
        <?php if(isset($redirect_link) && !empty($redirect_link)): ?>
        <!--a href="<?php echo $redirect_link; ?>" class="btn btn-accept"><?php echo isset($buttonNameRediect) ? $buttonNameRediect : '' ?></a-->
        
        <?php endif; ?>
		
		<?php if(isset($cancelUrl) && !empty($cancelUrl)): ?>
        <a href="<?php echo base_url($cancelUrl); ?>" class="btn btn-accept"><?php echo isset($buttonNameCancel) ? $buttonNameCancel : '' ?></a>
        <?php endif; ?>
		
		<?php if(isset($schema_url) && !empty($schema_url)): ?>
		<a href="<?php echo $schema_url; ?>" class="btn btn-accept">Quay lại</a>
		<?php endif; ?>
    </div>
</div>
<?php if(isset($timeRedirect)): ?>
<script>
		setTimeout(function () {
			<?php if(isset($redirect_link) && !empty($redirect_link)): ?>
				window.location.href = "<?php echo $redirect_link; ?>"; 
			<?php elseif(isset($schema_url) && !empty($schema_url)): ?>
				window.location = "<?php echo $schema_url; ?>"; 
			<?php endif; ?>
		}, <?php echo $timeRedirect; ?>);
</script>
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