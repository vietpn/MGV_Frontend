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
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/select2.min.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/libs/metisMenu.min.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/element/fonts.css"; ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/element/style.css"; ?>'/>
    <script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery-1.11.3.min.js"; ?>'></script>
</head>
<body>

	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a>Nạp tiền</a></li>
	</ul> 
<div class="cash_in">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab1" style="line-height: 65px;">Nạp tiền nhanh</a></li>
        <li><a data-toggle="tab" href="#tab2">Nạp tiền tài khoản liên kết</a></li>
        <li><a data-toggle="tab" href="#tab3">Hỗ trợ thông tin nạp tiền theo phiên</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab1" class="tab-pane fade in active">
            <div class="tab-main">
                <div class="form-center">
                    <?php echo form_open('/payment_epurse/index', array('method' => 'post', 'role' => 'form')); ?>
                    
					<div class="form-group">
                        <label>Phương thức nạp</label>
                        <div class="div-input">
                            <?php $paymentType = array(array('val' => '1', 'name' => 'Thẻ ATM / Tài khoản ngân hàng'),
                                array('val' => '2', 'name' => 'Thẻ visa / Master card')
                            );
                            ?>
                            <select name="payment_type" class="form-input" id="paymentType">
                                <?php foreach($paymentType as $type): ?>
                                    <option value="<?php echo $type['val']; ?>" <?php echo set_select('payment_type', $type['val'], False); ?>><?php echo $type['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="form-error"><?php echo form_error('payment_type'); ?></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
					
					<div class="form-group provider">
                        <label>Ngân hàng</label>
                        <div class="div-input">
                            <select id="providerCode" name="provider_code" class="form-input" data-select-search="true">
								<option value="">Chọn ngân hàng</option>
                                <?php if(isset($listBank)): ?>
                                    <?php foreach($listBank as $bank): ?>
                                        <?php if($bank->type == '2'): ?>
                                            <option value="<?php echo $bank->recId; ?>" <?php echo set_select('provider_code', $bank->recId, False); ?>><?php echo $bank->providerName; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <span class="form-error"><?php echo form_error('provider_code'); ?></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="form-group">
                        <label>Số tiền nạp (đ)</label>
                        <div class="div-input">
                            <input id="amountPayment" class="form-input" type="text" name="amount" onkeyup="formatCurrency(this, this.value);" maxlength="11" placeholder="Nhập số tiền cần nạp" value="<?php echo set_value('amount') ?>">
                            <span class="form-error"><?php echo form_error('amount'); ?></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label>Phí nạp tiền (đ)</label>
                        <div class="div-input">
                            <span id="feePayment" class="amount">0</span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label>Số tiền trừ trên thẻ (đ)</label>
                        <div class="div-input">
                            <span id="realAmount" class="amount">0</span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group button-group">
                        <label>&nbsp;</label>
                        <div class="div-input">
                            <input name="paymentOnline" type="submit" class="button button-main" value="Nạp tiền"/>
                            <a class="button button-sub" target="_parent" href="/transaction_manage">Hủy</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        <div id="tab2" class="tab-pane fade">
            <div class="tab-main">
				<div class="form-center">
					<div class="form-group">
						<div class="warning-msg">
							<span style="color: #ccc;">Chức năng này đang trong quá trình nâng cấp và chưa sử dụng được. Vui lòng trở lại sau. <br> MegaV xin chân thành cảm ơn!</span>
						</div>
					</div>
				</div>
			</div>
        </div>
        <div id="tab3" class="tab-pane fade">
            <div class="tab-main">
			
				<!--div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"-->
					<?php if(isset($paymentOffline) && $paymentOffline != false): ?>
					
					<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
						<?php

                         foreach($paymentOffline as $epayBank): ?>
						
								<div class="panel panel-default">
									<!--div class="panel-heading" role="tab" id="heading-<?php echo $epayBank->rowId; ?>">
										<h4 class="panel-title">
											<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $epayBank->rowId; ?>" aria-expanded="false" aria-controls="collapse<?php echo $epayBank->rowId; ?>">
												<?php echo $epayBank->bankCode ?>
											<i class="collapse-<?php echo $epayBank->rowId; ?> pull-right fa fa-angle-up"></i>
											</a>
										</h4> 
									</div-->
									<!--div id="collapse-<?php echo $epayBank->rowId; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-<?php echo $epayBank->rowId; ?>"-->
										<div class="panel-body" style="border: medium none;">
											<p class="collapse-title-bank">Số tài khoản ngân hàng - Internet Banking</p>
												<table class="tbl-bank-info">
													<tr>
														<td class="tbl-th"><span>Số tài khoản</span></td>
														<td><span><?php echo $epayBank->bankAccount ?></span></td>
													</tr>
													<tr>
														<td class="tbl-th"><span>Chủ tài khoản</span></td>
														<td><span><?php echo $epayBank->bankAccountName ?></span></td>
													</tr>
													<tr>
														<td class="tbl-th"><span>Chi nhánh</span></td>
														<td><span><?php echo $epayBank->bankCode . ' - ' . $epayBank->branchName ?></span></td>
													</tr>
													<tr>
														<td class="tbl-th"><span>Nội dung</span></td>
														<td><span><?php echo $epayBank->description ?></span></td>
													</tr>
												</table>
											
										<!--/div-->
									</div>
								</div>
							<?php break; ?>
						<?php endforeach; ?>
					  
					  
						 
						  
						  
					</div>
					
					<?php endif;?>
				<!--/div-->

			</div>
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
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/select2.min.js"; ?>'></script>
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/metisMenu.min.js"; ?>'></script>
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery.cookie.js"; ?>'></script>
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/datepicker.js"; ?>'></script>
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/cmnd.js"; ?>'></script>
<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/script.js"; ?>'></script>
<script>

    $("#providerCode").select2({
        tags: "true",
        placeholder: "Tìm kiếm ngân hàng...",
        "language": {
           "noResults": function(){
               return "Không tìm thấy ngân hàng tìm kiếm.";
           }
       },
        escapeMarkup: function (markup) {
            return markup;
        }
    });
	
</script>
</body>
</html>
