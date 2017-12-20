<link href="<?php echo base_url('css/payment_card.css') ?>" rel="stylesheet" type="text/css"/>
<div class="container" id="form_input">
    <div class="row col-md-6 col-sm-12 col-xs-12 center-block float_none">
        <?php echo form_open($this->uri->uri_string()); ?>
        <div class="form-input">
            <div class="input-group col-xs-12">
                <label class="col-xs-12"><?php echo lang('payment_card_cardtype'); ?></label>
                <select class="input-box col-xs-12" name="provider">
                    <?php if (isset($card_list) && is_array($card_list) && count($card_list)): ?>
                        <?php foreach ($card_list as $key => $value): ?>
                            <option value="<?php echo $key ?>" <?php if(isset($fillter) && $fillter['provider'] == $key) echo "selected"; ?>><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <div class="form-input <?php echo form_error('serial') ? 'error' : ''; ?>">
            <div class="input-group col-xs-12">
                <label class="col-xs-12"><?php echo lang('payment_card_serial'); ?></label>
                <input class="input-box col-xs-12" name="serial" value="<?php echo set_value('serial'); ?>" autocomplete="off"/>
                <span class='help-block'><?php echo form_error('serial'); ?></span>
            </div>
        </div>
        <div class="form-input <?php echo form_error('pin') ? 'error' : ''; ?>">
            <div class="input-group col-xs-12">
                <label class="col-xs-12"><?php echo lang('payment_card_cardcode'); ?></label>
                <input class="input-box col-xs-12" name="pin" value="<?php echo set_value('pin'); ?>" autocomplete="off"/>
                <span class='help-block'><?php echo form_error('pin'); ?></span>
            </div>
        </div>
        <div class="form-submit">
            <input type="submit" name="submit" value="<?php echo lang('payment_card_submit') ?>" class="btn-block"/>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<div class="footer-view" style="height: 300px">

</div>