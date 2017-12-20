
	<ul class="breadcrumb">
		<li></li>
		<li><a>Thanh toán</a></li>
		<li><a>EC - Merchant</a></li>
	</ul> 
<div class="col-md-12 form-center">
    <div class="notify-msg form-group">
        <?php if (isset($message)): ?>
            <span><?php echo $message ?></span>
        <?php endif; ?>
    </div>
    <?php if(!isset($notEnoughMoney)) : ?>
        <?php echo form_open('/ec_payment/index', array('method' => 'post', 'role' => 'form')); ?>
    <?php endif; ?>
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    <div class="form-group relative">
        <label for="">Nhà cung cấp</label>
        <div class="div-input">
            <input type="text" class="form-input" readonly value="<?php echo (isset($merchantInfo['merchantName'])) ? $merchantInfo['merchantName'] : ''; ?>">
        </div>
        <?php if(isset($merchantInfo['MLogo'])) : ?>
            <img class="merchant-logo" src="<?php echo $merchantInfo['MLogo']; ?>" >
        <?php endif; ?>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <label for="">Mã giao dịch</label>
        <div class="div-input">
            <input type="text" class="form-input" readonly value="<?php echo (isset($paymentInfo['requestId'])) ? $paymentInfo['requestId'] : ''; ?>">
        </div>
        <div class="clearfix"></div>
    </div>
	<div class="form-group">
        <label for="">Họ và tên khách hàng</label>
        <div class="div-input">
            <input type="text" class="form-input" readonly value="<?php echo (isset($paymentInfo['userAgent'])) ? $paymentInfo['userAgent'] : ''; ?>">
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <label for="">Số tiền thanh toán (vnđ)</label>
        <div class="div-input">
            <input type="text" class="form-input amount" readonly value="<?php echo (isset($paymentInfo['amount'])) ? number_format($paymentInfo['amount']) : ''; ?>">
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <label for="">Số dư khả dụng (vnđ)</label>
        <div class="div-input">
            <input type="text" class="form-input amount" readonly value="<?php echo (isset($paymentInfo['epurseBalance'])) ? number_format($paymentInfo['epurseBalance']) : ''; ?>">
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <label for="">Nội dung thanh toán</label>
        <div class="div-input">
            <textarea class="form-input" readonly><?php echo (isset($paymentInfo['remark'])) ? $paymentInfo['remark'] : ''; ?></textarea>
        </div>
        <div class="clearfix"></div>
    </div>

	<?php if($userInfo['security_method'] == '1'): ?>
		<?php if (isset($SendOTP)): ?>
			<div class="form-group">
				<p class="italic">Hệ thống đã gửi OTP tới số điện thoại. Không nhận được <a id="resendOtp">Click gửi lại</a></p>
			</div>
			<div class="form-group">
				<label>Nhập OTP</label>
				<div class="div-input">
					<input name="otp" class="form-input" type="password" value="<?php echo set_value('otp'); ?>" placeholder="Nhập OTP" autocomplete="off">
					<span class="form-error"><?php echo (isset($otpErr)) ? $otpErr : ''; ?><?php echo form_error('otp'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
		
	<?php if (isset($passLv2)): ?>
		<?php if($userInfo['security_method'] == '2'): ?>
        <div class="form-group">
            <label>Nhập mật khẩu cấp 2</label>
            <div class="div-input">
                <input name="passLv2" class="form-input" type="password" value="<?php echo set_value('passLv2'); ?>" placeholder="Nhập mật khẩu cấp 2" autocomplete="off">
                <span class="form-error"><?php echo (isset($passLv2Err)) ? $passLv2Err : ''; ?><?php echo form_error('passLv2'); ?></span>
            </div>
            <div class="clearfix"></div>
        </div>
		<div class="form-group">
			<label>&nbsp;</label>
			<div class="div-input">
				<div class="link">
					<a target="_blank" href="/reset_pass_lv2">Quên mật khẩu cấp 2?</a>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<?php endif; ?>
	<?php endif; ?>

    <div class="form-group button-group">
        <label for="">&nbsp;</label>
        <div class="div-input">
            <?php if(isset($btnPayment)): ?>
                <input name="payment" type="submit" class="button button-main button180" value="Xác nhận thanh toán"/>
            <?php else: ?>
                <input <?php echo isset($notEnoughMoney) ? "disabled" : ""; ?>  name="accept" type="submit" class="button button-main" value="Thanh toán"/>
            <?php endif; ?>

            <?php if(!isset($notEnoughMoney)) : ?>
                <input name="reject" type="submit" class="button button-sub" value="Hủy"/>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php if(!isset($notEnoughMoney)) : ?>
        <?php echo form_close(); ?>
    <?php endif; ?>
</div>
<?php if(isset($timeRedirect)): ?>
<script>
		setTimeout(function () {
			var redirect = $.cookie('redirect');
			if(redirect == null)
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
<?php
	echo (isset($popup)) ? $popup : "" ;
?>