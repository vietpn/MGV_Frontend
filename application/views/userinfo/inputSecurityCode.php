
<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: block;">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
			<h4 class="modal-title" id="myModalLabel">Thông báo từ hệ thống</h4>
		  </div>
		  <div class="modal-body" style="min-height: 500px;">
			<?php echo form_open('/user_info/update_info', array('method' => 'post', 'role' => 'form', 'id' => 'frm_search_list')); ?>
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			 <?php if($this->session_memcached->userdata['info_user']['security_method'] == '1'): ?>
				<span>Nhập OTP để hoàn tất thủ tục thay đổi thông tin cá nhân</span>
				<div class="row">
					<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12 none-padding"><b>OTP</b></div>
					<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12 txt-info">
						<div class="form-group">
							<input name="otp" class="form-control" type="password" value="" autocomplete="off">
						</div>
						<span class="error"><?php echo (isset($message)) ? $message : ''; ?><?php echo form_error('fdate'); ?></span>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
						<input type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-warning " value="Cập nhật"/>
					</div>
					<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
						<a type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-default ">Hủy bỏ</a>
					</div>
				</div>
			 <?php else: ?>
				<span>Nhập mật khẩu cấp 2 để hoàn tất thủ tục thay đổi thông tin cá nhân</span>
				<div class="row">
					<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12 none-padding"><b>Mật khẩu cấp 2</b></div>
					<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12 txt-info">
						<div class="form-group">
							<input name="passLv2" class="form-control" type="password" value="" autocomplete="off">
						</div>
						<span class="error"><?php echo (isset($message)) ? $message : ''; ?><?php echo form_error('fdate'); ?></span>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
						<input type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-warning " value="Cập nhật"/>
					</div>
					<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
						<a id="closemodal" type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-default ">Hủy bỏ</a>
					</div>
				</div>
			 <?php endif; ?>
			 <?php echo form_close(); ?>
		  </div>
		</div>
	  </div>
	</div>
<div class="modal-backdrop fade in"></div>
