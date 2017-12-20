<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hatt
 * Date: 8/11/14
 * Time: 11:35 AM
 * To change this template use File | Settings | File Templates.
 */
 echo form_open('login/doLogin',array('method'=>'post','class'=>'form-signin'));
?>
<div>
<!--    <label style="color: red">--><?php //echo validation_errors(); ?><!--</label>-->
    <div>
        <label style="padding-right: 25px">Tên Đăng Nhập:</label>
        <input type="text" name="username" maxlength="250" class="form-control" onkeypress="javascript:checkuser();"/>
        <input type="hidden" value="<?php echo $clientId ?>" name="client" >
       <span style="color: red"> <?php echo form_error('username'); ?></span>
    </div>
    <div style="padding: 15px 0px">
        <label style="padding-right: 25px">Mật khẩu:</label>
        <input type="password" name="password"  maxlength="25" class="form-control" />
        <span style="color: red"> <?php echo form_error('password'); ?></span>

    </div>
    <div>
        <input type="submit" name="submit" value="Đăng nhập" class="btn btn-lg btn-primary btn-block"/>
    </div>
</div>
</form>
