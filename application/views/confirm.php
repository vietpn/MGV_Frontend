<div class="col-md-12 comfirm_form ">
    <?php
    /**
     * Created by JetBrains PhpStorm.
     * User: tienhm
     * Date: 11/11/14
     * Time: 4:30 PM
     * To change this template use File | Settings | File Templates.
     */
    echo form_open($data['post_url'],array('method'=>'post'));

    ?>
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    <div class="lable_cf">
        <b><label class="lable_cf comfirm_mess"><?php echo $data['message']; ?></label></b>
    </div>
    <?php if(isset($url)): ?>
    <input type="hidden" name="url" value="<?php echo $url ?>" />
    <?php endif; ?>
    <div class="butt_cf" style="margin-bottom: 20px">
        <button type="submit" value="cf_yes" name="confirm_submit" class="btn btn-primary"><?php echo lang('yes') ?></button>
        <button type="submit" value="cf_no" name="confirm_submit" class="btn btn-primary"><?php echo lang('no') ?></button>
    </div>
    </form>
</div>
