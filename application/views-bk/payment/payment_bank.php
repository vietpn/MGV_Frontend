<link href="<?php echo base_url('css/payment_bank.css') ?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo base_url('js/payment/payment_bank.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/payment/jquery.number.js') ?>"></script>
<?php echo form_open(base_url().'payment/payment_bank'); ?>
<div class="container bank_form" id="form_input">
    <div class="row col-lg-6 col-md-12 col-sm-12 col-xs-12 center-block float_none">
        <?php if(isset($message)): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <!--            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                <?php echo $message; ?>
            </div>
        <?php endif ?>
        <div class="form-input">
            <div class="input-group col-xs-12">
                <label class="col-xs-12"><?php echo lang('payment_bank_bankname'); ?></label>
                <select class="input-box col-xs-12" name="bankid">
                    <?php if (isset($bank_list) && is_array($bank_list) && count($bank_list)): ?>
                        <?php foreach ($bank_list as $key => $value): ?>
                            <option value="<?php echo $key ?>" <?php if(isset($fillter) && $fillter['bankid'] == $key) echo "selected"; ?>><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <div class="form-input <?php echo form_error('amount') ? 'error' : ''; ?>">
            <div class="input-group col-xs-12">
                <label class="col-xs-12"><?php echo lang('payment_bank_amount'); ?></label>
                <input type="text" class="input-box col-xs-12" id="amount" name="amount" value="<?php echo set_value('amount'); ?>" autocomplete="off"/>
                <span class='help-block'><?php echo form_error('amount'); ?></span>
            </div>
        </div>
        <div class="form-input">
            <div class="input-group col-xs-12">
                <label class="col-lg-5 col-md-5 col-sm-3 col-xs-5"><?php echo lang('payment_bank_fee'); ?></label>
                <label class="col-lg-7 col-md-7 col-sm-9 col-xs-7" id="fee" name="fee" value="0">0</label>
            </div>
        </div>
        <div class="row col-md-12 col-sm-12 col-xs-12 center-block float_none" id="bank_bills">
            <div class="input-group col-xs-12 billing">
                    <label class="col-lg-5 col-md-5 col-sm-3 col-xs-5"><?php echo lang('payment_bank_billing'); ?></label>
                    <label class="col-lg-7 col-md-7 col-sm-9 col-xs-7" id="billing"></label>
            </div>
        </div>
        <div class="form-submit">
            <input type="submit" name="submit" value="<?php echo lang('payment_card_submit') ?>" class="btn-block"/>
        </div>
    </div>
</div>

<?php echo form_close(); ?>

<div class="footer-view" style="height: 300px">

</div>