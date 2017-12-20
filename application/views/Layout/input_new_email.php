<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hatt
 * Date: 9/23/14
 * Time: 2:44 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<!--<link rel="stylesheet" type="text/css" href="../css/bootstrap/bootstrap-datetimepicker.min.css"/>-->

<div class="col-md-10 container_right" style="margin-left: 25px;">
    <div class="col-md-12" id="txt_install_1">
        <span class="txt_intall"><strong>CÀI ĐẶT EMAIL</strong></span>
    </div>
    <div class="col-md-12 au_container">
        <ul class="list-unstyled list-inline" style="padding-top: 20px">
            <li>
                <img src="../images/info/key.jpg">
            </li>
            <li>|</li>
            <li>
                Nhập lại Email mới
            </li>
        </ul>
    <div id='part2'>
        <?php   echo form_open('change_mail/change_email',array('method'=>'post','class'=>'col-xs-12 col-sm-12','role'=>'form')); ?>
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
            <div class="form-group ">
                <label class="col-xs-3" style="padding-left: 0; text-align: left;" for="InputPassword">Email mới</label>
                <input class="col-xs-9" type="text" class="form-control" name="email" value="<?php echo set_value('email') ?>">
                <span class="col-xs-12" style="color:red"><?php echo form_error('email') ?></span>
                <span class="col-xs-12" style="color:red"><?php echo isset($error)?$error:'' ?></span>
                <input type="hidden" class="form-control "  value="<?php echo $activecode ?>" name="activecode">
				<!-- add by phongwm -->
				<?php if(isset($when_login)) {?>
				<input type="hidden" class="form-control "  value="<?php echo $when_login ?>" name="when_login">
				<?php } ?>
				<!-- end add by phongwm -->
                <input class="col-md-12 col-lg-12 col-xs-12 col-sm-12 btn btn-primary" style="margin-top: 15px; margin-bottom: 15px;" id="site_button" type="submit"
                       value="Gửi"/>
            </div>
        </form>
    </div>
</div>
</div>