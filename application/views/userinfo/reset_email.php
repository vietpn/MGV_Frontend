<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hatt
 * Date: 12/10/14
 * Time: 4:23 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<div class="txt_account">Thiết lập lại mật khẩu</div>
<div class="group-reset row" style="padding-top: 10px">
    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
        <?php echo form_open('login/input_email_reset', array('method' => 'post', 'class' => 'col-xs-12 col-sm-12', 'role' => 'form')); ?>
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
        <div class="form-group row ">
            <div class="col-md-3 col-lg-3 col-xs-6 col-sm-6"><label for="InputPassword">Email xác nhận</label></div>
            <div class="col-md-7 col-lg-7 col-xs-6 col-sm-6"><input type="email" class="form-control" name="email" placeholder="Nhập email để xác thực">
            </div>
            <button type="submit" id="site_button">Gửi</button>
        </div>
        </form>
    </div>
</div>
