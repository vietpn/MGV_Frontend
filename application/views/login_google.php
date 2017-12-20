<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hatt
 * Date: 8/13/14
 * Time: 4:30 PM
 * To change this template use File | Settings | File Templates.
 */
echo form_open('login/comfirm_code',array('method'=>'post'));

?>
<div class="col-md-10 container_right">
    <div class="lable_cf">
        <label class="lable_cf">Bạn có cho phép app này sử dụng tài khoản của bạn không?</label>
    </div>
    <div class="butt_cf">
        <button type="submit" value="Đồng Ý">Đồng Ý</button>
        <input type="button" name="cf_cancle" value="Không Đồng Ý" onclick="javascripts:ComfirmNo()" >
    </div>
    </form>
</div>
