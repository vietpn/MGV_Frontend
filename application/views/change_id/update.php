<div class="container">
	<div class="row">
		<div class="txt_account" >Đổi số điện thoại di động</div>
		
		<?php echo form_open('/change_phone/confirmOtp', array('method' => 'post', 'role' => 'form')); ?>
			<?php if(isset($sentOtp)): ?>
				<?php if(isset($messSentOtp)): ?>
					<div><?php echo $messSentOtp ?> Không nhận OTP <a>Click gửi lại</a></div>
				<?php endif; ?>
			<?php endif; ?>
			<div class="form-group row">
				<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 none-padding"><b>Số điện thoại đang sử dụng:</b></div>
				<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 txt-info">
					<input readonly name="newphone" class="form-control" type="text" value="<?php echo substr_replace($newphone, '****', 0, 6); ?>">
					<span class="error"><?php echo form_error('newphone'); ?></span>
				</div>
			</div>
			<div class="form-group row">
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 none-padding"><b>Nhập OTP:</b></div>
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 txt-info">
						<input name="otp" class="form-control" type="password" autocomplete="off">
					</div>
					<div class="col-md-10 col-lg-10 col-xs-12 col-sm-12">
						<span class="error"><?php echo (isset($wrong_otp)) ? $wrong_otp : ''; ?><?php echo form_error('otp'); ?></span>
					</div>
				</div>
			<div class="form-group row">
				<input type="submit" class="col-md-3 col-lg-3 col-xs-6 col-sm-6 btn btn-warning " value="Tiếp tục"/>
				<a class="col-md-3 col-lg-3 col-xs-6 col-sm-6 btn btn-default "/>Hủy bỏ</a>
			</div>
		</form>
	</div>
</div>