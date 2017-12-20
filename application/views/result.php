<div>
    <div>
    <label>
        <h4><b><?php   echo $mess; ?></b></h4>
    </label>
    </div>
    <div class="row">
        <?php if(isset($redirect_link)): ?>
        <a href="<?php echo base_url($redirect_link); ?>" class="btn btn-warning">QUAY LẠI</a>
        <?php else: ?>
        <button onclick="javascript:goback()" class="btn btn-warning">QUAY LẠI</button>
        <?php endif; ?>
    </div>
</div>

<?php if(isset($timeRedirect)): ?>
<script>
		setTimeout(function () {
		   window.location.href = "<?php echo $redirect_link; ?>"; 
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