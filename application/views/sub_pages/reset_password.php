
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <span class="txt_account col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;">Quên mật khẩu tài khoản MegaV.vn</span>
    
       <div class="clearfix"></div>
		<?php if(isset($is_reset)): ?>
			<?php   echo form_open('login/reset_user_pass',array('method'=>'post','class'=>'col-xs-12 col-sm-12','role'=>'form')); ?>
		<?php else: ?>
			<?php   echo form_open('login/change_pass',array('method'=>'post','class'=>'col-xs-12 col-sm-12','role'=>'form')); ?>
		<?php endif; ?>
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			<?php if(!isset($is_reset)): ?>
			<div class="form-group">
				<label class="pull-left col-md-6 col-lg-6 col-xs-12 col-sm-12" style="text-align: right">Mật khẩu cũ</label>
				<div class="div-input col-md-3 col-lg-3 col-xs-12 col-sm-12">
					<input type="password" class="form-input checkSpace" name="old_pass" value="<?php echo set_value('old_pass') ?>" maxlength='50' autocomplete="off">
					<?php if(!isset($is_reset)): ?>
					<span class="form-error" style='color: #b94207; font-family: "Roboto Light";'><?php echo form_error('old_pass') ?></span>
					<?php endif; ?>
				</div>
				<div class="clearfix"></div>
			</div>
			<?php endif; ?>
			<div class="form-group ">
				<label class="pull-left col-md-6 col-lg-6 col-xs-12 col-sm-12" style="text-align: right">Mật khẩu mới</label>
				<div class="div-input col-md-3 col-lg-3 col-xs-12 col-sm-12">
					<input type="password" class="form-input checkSpace" name="password" value="<?php echo set_value('password') ?>" autocomplete="off">
					<input type="hidden" class="form-control"  value="<?php echo $source_url ?>" name="source_url">
					<input type="hidden" class="form-control"  value="<?php echo $clientId ?>" name="clientId">
					<?php if(isset($is_reset)): ?>
					<input type="hidden" class="form-control"  value="<?php echo 1 ?>" name="is_reset">
					<?php endif; ?>
					<span class="form-error" style='color: #b94207; font-family: "Roboto Light";'><?php echo form_error('password') ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="form-group">
				<label class="pull-left col-md-6 col-lg-6 col-xs-12 col-sm-12" style="text-align: right">Nhập lại mật khẩu mới</label>
				<div class="div-input col-md-3 col-lg-3 col-xs-12 col-sm-12">
					<input type="password" class="form-input checkSpace" name="re_pass" value="<?php echo set_value('re_pass') ?>" autocomplete="off">
					<span class="form-error" style='color: #b94207; font-family: "Roboto Light";'>
						<?php echo form_error('re_pass') ?>
						<span class="col-xs-12" style="color:red"><?php echo isset($error)?$error:'' ?></span>
					</span>
				</div>
				<div class="clearfix"></div>
			</div>
	
			<div class="form-group">
				<div class="div-input">
						<input class="btn btn-accept col-md-2 col-lg-2 col-xs-6 col-sm-6 col-md-offset-6 col-lg-offset-5" type="submit"
							value="Gửi"/>
				</div>
				<div class="clearfix"></div>
			</div>

		<?php echo form_close(); ?>
		
</div>