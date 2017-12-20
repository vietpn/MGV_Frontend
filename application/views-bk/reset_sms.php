<div class="reset_sms col-xs-12" style="padding: 5px">
    <div style="font-weight: bold;">
        <?php if(!$this->input->get('request_sdk')) 
				echo form_open(base_url().'login/reset_sms'); 
			else
				echo form_open(base_url().'login/reset_sms?request_sdk=1'); 
		?>
        <h4><b><?php   echo 'Lấy lại mật khẩu bằng SMS'; ?></b></h4>
        Cú pháp nhắn tin: <br> <?php echo str_replace('%20', ' ', $body) ?> [Tên tài khoản] Gửi <?php echo $shortcode ?>
        <br><br>Hoặc nhập tên tài khoản để sinh tin nhắn tự động:  <br><input class="username" name="username"/>
        <br>
        <?php if(form_error('username')): ?>
            <span style="color:red"><?php echo form_error('username') ?></span>
        <?php endif; ?>
    </div>
    <div class="col-xs-12" style="margin-top: 10px; padding-left: 0;">
        <input type="submit" class="btn btn-success" value="GỬI">
		<?php if(!$this->input->get('request_sdk') || $this->input->get('request_sdk') != '1'): ?>
        <a href="<?php echo base_url('login?appId='.$clientId); ?>" class="btn btn-primary">QUAY LẠI</a>
		<?php endif; ?>
    </div>
</div>