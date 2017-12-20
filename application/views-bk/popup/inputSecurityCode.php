
<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: block;">
	  <div class="modal-dialog" role="document">
		<div class="modal-content modal-security">
			<div class="modal-header modal-header-security">
			<h4 class="modal-title" id="myModalLabel">Thông báo từ hệ thống</h4>
		  </div>
		  <div class="modal-body modal-body-security" >
			<?php echo form_open('/user_info/update_info', array('method' => 'post', 'role' => 'form', 'id' => 'update_info')); ?>
			 <?php if($this->session_memcached->userdata['info_user']['security_method'] == '1'): ?>
				<span>Nhập OTP để hoàn tất thủ tục thay đổi thông tin cá nhân</span>
				<div class="group-security">
					<div class="security_info" style="float: left">
						
						<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12" >
							<div class="form-group">
								<label style="text-align: center;">OTP</label>
								<div class="input-security">
									<input name="otp" type="password" value="">
									<span class="security-error"><?php echo (isset($message)) ? $message : ''; ?><?php echo form_error('fdate'); ?></span>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						
						<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
							<div class="col-md-6 col-lg-6 col-xs-6 col-sm-6" style="padding-right: 2px;">
								<input type="submit" class="btn btn-accept pull-right" value="Cập nhật">
							</div>
							<div class="col-md-6 col-lg-6 col-xs-6 col-sm-6" style="padding-left: 2px;">
								<a id="closemodal" class="btn btn-deny ">Hủy bỏ</a>
							</div>
						</div>
						
						
					</div>
				</div>
			 <?php else: ?>
				<span>Nhập mật khẩu cấp 2 để hoàn tất thủ tục thay đổi thông tin cá nhân</span>
				<div class="group-security">
					<div class="security_info" style="float: left">
					
						<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
							<div class="form-group">
								<label style="text-align: center;">Mật khẩu cấp 2</label>
								<div class="input-security">
									<input name="passLv2" class="form-control" type="password" value="">
									<span class="security-error"><?php echo (isset($message)) ? $message : ''; ?><?php echo form_error('fdate'); ?></span>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						
						<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
							<div class="col-md-6 col-lg-6 col-xs-6 col-sm-6" style="padding-right: 2px;">
								<input type="submit" class="btn btn-accept pull-right" value="Cập nhật">
							</div>
							<div class="col-md-4 col-lg-4 col-xs-6 col-sm-6" style="padding-left: 2px;">
								<a id="closemodal" class="btn btn-accept ">Hủy bỏ</a>
							</div>
						</div>
							
					</div>
				</div>
			 <?php endif; ?>
			 <?php echo form_close(); ?>
		  </div>
		</div>
	  </div>
	</div>
<div class="modal-backdrop fade in"></div>
<script>
	$(document).ready(function(){			
		$('#closemodal').click(function(){
			$('.modal-backdrop,#myModal').removeClass('in').css('display', 'none');
		});
		
	});
</script>