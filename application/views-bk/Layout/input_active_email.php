<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hatt
 * Date: 9/23/14
 * Time: 2:44 PM
 * To change this template use File | Settings | File Templates.
 */
?>

<div>
    <div id="txt_install_1">
        <span class="txt_intall">
			<strong class="bluecolor">
				Nhập Email để có thể tiếp tục sử dụng dịch vụ.
			</strong>
		</span>
    </div>
    <div >
        <ul class="list-unstyled list-inline" style="padding-top: 20px">
            <li>
                <img src="../images/info/key.jpg">
            </li>
            <li>|</li>
            <li>
                Nhập Email Active
            </li>
        </ul>
		<div id='part2'>
			<?php   echo form_open('login/active_mail',array('method'=>'post','class'=>'form_active_email','role'=>'form')); ?>
				<div class="form-group ">
					<label class="col-xs-3" style="padding-left: 0; text-align: left;" for="InputPassword">Email mới</label>
					<input class="col-xs-9" type="text" class="form-control" name="email" value="<?php echo set_value('email') ?>">
					<span class="col-xs-12" style="color:red"><?php echo form_error('email') ?></span>
					<span class="col-xs-12" style="color:red"><?php echo isset($error)?$error:'' ?></span>
					<input type="hidden" class="form-control "  value="<?php echo $username ?>" name="username">
					<input type="hidden" class="form-control "  value="<?php echo $clientId ?>" name="clientID">
					<input type="hidden" value="" name="source_url">
					<!-- add by phongwm -->
					<?php if(isset($active_mail)) {?>
					<input type="hidden" class="form-control "  value="<?php echo $active_mail ?>" name="active_mail">
					<?php } ?>
					<!-- end add by phongưm -->
					<input class="col-md-12 col-lg-12 col-xs-12 col-sm-12 btn btn-primary" style="margin-top: 15px; margin-bottom: 15px;" id="site_button" type="submit"
						   name="active" value="Gửi"/>
				</div>
			</form>
		</div>
	</div>
</div>