<div class="col-md-12 comfirm_form" style="margin-bottom: 15px">
    <?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Hatt
     * Date: 8/13/14
     * Time: 4:30 PM
     * To change this template use File | Settings | File Templates.
     */
    echo form_open('login/comfirm_code',array('method'=>'post'));
    $clientId = isset($clientId)?$clientId:CLIENT_ID_OPENID;
    ?>
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    <input type="hidden" value="<?php echo $source_url ?>" name="source_url">
    <div class="lable_cf">
        <b><label class="lable_cf comfirm_mess">Bạn có cho phép app này sử dụng tài khoản của bạn không?</label></b>
    </div>
    <div class="butt_cf">
        <button type="submit" value="Đồng Ý" class="btn btn-primary">Đồng Ý</button>
        <a href="<?php echo base_url().'login?appId='.$clientId ?>" onclick="javascripts:ComfirmNo()" class="btn btn-primary ">
    </div>
    </form>
</div>
