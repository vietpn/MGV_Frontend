<link href="<?php echo base_url('css/payment_sms.css') ?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo base_url('js/payment/payment_bank.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('js/payment/jquery.number.js') ?>"></script>

<div class="container bank_form" id="form_input">
    <div class="row col-lg-6 col-md-12 col-sm-12 col-xs-12 center-block float_none">
        <?php if(isset($message)): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <!--            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                <?php echo $message; ?>
            </div>
        <?php endif ?>
        <?php if(isset($sms_list) && is_array($sms_list) && count($sms_list)): ?>
            <?php foreach($sms_list as $list): ?>
                <a class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-input sms-value" href="<?php echo 'sms:' . $list['shortcode'] . '?body=' . $list['keyword'] . '%20' . $clientId . '%20' . $username.'%20'.$trans_id;?>">
                   <div class="text-sms"><?php echo lang('payment_sms_value').': '.$list['fee'].'VND'; ?></div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
