<div class="col-md-12 form-center">
    <div class="form-group" >
        <span class="txt_account col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">Lấy lại mật khẩu MegaV.vn</span>
    </div>
	
	<div class="col-md-12 col-sm-12 col-xs-12 form-center">
		
		
			<?php echo form_open('reset_password/change_pass',array('method'=>'post','class'=>'col-xs-12 col-sm-12','role'=>'form')); ?>
				<div class="form-group">
					<label>Mật khẩu mới</label>
					<div class="div-input">
						<input type="password" class="form-input" name="passwd" value="<?php echo set_value('passwd') ?>">
						<span class="form-error"><?php echo form_error('passwd'); ?></span>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="form-group">
					<label>Nhập lại mật khẩu mới</label>
					<div class="div-input">
						<input type="password" class="form-input" name="rpasswd" value="<?php echo set_value('rpasswd') ?>">
						<span class="form-error"><?php echo form_error('rpasswd'); ?></span>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="form-group button-group">
					<label> &nbsp </label>
					<div class="div-input">
						<input class="button button-main" type="submit" value="Gửi"/>
					</div>
					<div class="clearfix"></div>
				</div>
			</form>
		
	</div>
</div>