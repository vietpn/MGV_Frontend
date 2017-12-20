<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MegaV <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo '../images/megaid-favicon.png' ?>"/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/bootstrap.min.css" ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/sidebar.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/content.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/caroulsel-reponsive-style.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/layout_login.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/layout_info.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/layout/slick.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/metisMenu.min.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/element/fonts.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/element/style.css"; ?>'/>
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery-1.11.3.min.js"; ?>'></script>
</head>
<body>


<div class="txt_account">Nạp tiền</div>
<div class="cash_in">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab1">Nạp tiền online bằng TK ngân hàng</a></li>
        <li><a data-toggle="tab" href="#tab2">Nạp tiền online bằng TK liên kết</a></li>
        <li><a data-toggle="tab" href="#tab3">Hỗ trợ thông tin nạp tiền offline</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab1" class="tab-pane fade in active">
            <div class="tab-main">
                <div class="form-center">
                    <?php echo form_open('/payment_epurse/payment_online', array('method' => 'post', 'role' => 'form')); ?>
                    <div class="form-group">
                        <label>Hình thức nạp tiền</label>
                        <div class="div-input">
                            <?php $transType = array(array('val' => '1', 'name' => 'Nạp qua MegaBank')
                                //,array('val' => '2', 'name' => 'Tài khoản liên kết')
                            );
                            ?>
                            <select name="trans_type" class="form-input">
                                <?php foreach($transType as $type): ?>
                                    <option value="<?php echo $type['val']; ?>" <?php echo set_select('trans_type', $type['val'], False); ?>><?php echo $type['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="form-error"><?php echo form_error('trans_type'); ?></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label>Ngân hàng</label>
                        <div class="div-input">
                            <select name="provider_code" class="form-input">
                                <option value="">Chọn ngân hàng</option>
                                <?php if(isset($listBank)): ?>
                                    <?php foreach($listBank as $bank): ?>
                                        <?php if($bank->type == '2'): ?>
                                            <option value="<?php echo $bank->recId; ?>" <?php echo set_select('provider_code', $bank->providerCode, False); ?>><?php echo $bank->providerName; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <span class="form-error"><?php echo form_error('provider_code'); ?></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label>Phương thức nạp</label>
                        <div class="div-input">
                            <?php $paymentType = array(array('val' => '1', 'name' => 'Thẻ ATM \ Tài khoản ngân hàng'),
                                array('val' => '2', 'name' => 'Thẻ visa / Master card')
                            );
                            ?>
                            <select name="payment_type" class="form-input">
                                <?php foreach($paymentType as $type): ?>
                                    <option value="<?php echo $type['val']; ?>" <?php echo set_select('payment_type', $type['val'], False); ?>><?php echo $type['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="form-error"><?php echo form_error('payment_type'); ?></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label>Số tiền nạp (vnđ)</label>
                        <div class="div-input">
                            <input class="form-input" type="text" name="amount" onkeyup="formatCurrency(this, this.value);" maxlength="14" placeholder="Nhập số tiền cần nạp">
                            <span class="form-error"><?php echo form_error('amount'); ?></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label>Phí nạp tiền (vnđ)</label>
                        <div class="div-input">
                            <span class="amount">0</span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label>Số tiền trừ trên thẻ (vnđ)</label>
                        <div class="div-input">
                            <span class="amount">500000</span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group button-group">
                        <label>&nbsp;</label>
                        <div class="div-input">
                            <input type="submit" class="button button-main" value="Nạp tiền"/>
                            <a class="button button-sub">Hủy</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        <div id="tab2" class="tab-pane fade">
            <div class="tab-main"></div>
        </div>
        <div id="tab3" class="tab-pane fade">
            <div class="tab-main"></div>
        </div>
    </div>
</div>


<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.min.css"; ?>'/>
<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/font-awesome.css"; ?>'/>
<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/datepicker.css"; ?>'/>
<link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/bootstrap-datetimepicker.min.css"; ?>'/>
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/jquery.flexisel.js"; ?>'></script>
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "js/slick.min.js"; ?>'></script>
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/bootstrap.min.js"; ?>'></script>
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/bootstrap-datepicker.js"; ?>'></script>
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/metisMenu.min.js"; ?>'></script>
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery.cookie.js"; ?>'></script>
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/datepicker.js"; ?>'></script>
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/cmnd.js"; ?>'></script>
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/script.js"; ?>'></script>
</body>
</html>
