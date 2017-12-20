<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hatt
 * Date: 12/10/14
 * Time: 4:34 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<div class="txt_account">Thiết lập lại mật khẩu</div>
<div class="group-reset row">
    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
        <?php   echo form_open('login/reset_user_pass',array('method'=>'post','class'=>'col-xs-12 col-sm-12','role'=>'form')); ?>
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
        <div class="form-group ">
            <label for="InputPassword">Mật khẩu mới</label>
            <input type="password" class="form-control ipt-pass" name="password" autocomplete="off">
<!--            <input type="hidden" class="form-control "  value="--><?php //echo $username ?><!--" name="username">-->

        </div>
        <div class="form-group">
            <label for="InputPassword">Nhập lại mật khẩu mới</label>
            <input type="password" class="form-control ipt-pass " name="re_pass" autocomplete="off"></div>
        <button type="submit" class="btn btn-primary btn-save">LƯU LẠI</button>
        </form>
    </div>
</div>
