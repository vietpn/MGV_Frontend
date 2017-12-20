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
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a>Chuyển tiền</a></li>
	</ul>

	<div class="col-md-12 form-center-ex">
		<div class="step-by-step">
			<ol class="progtrckr" data-progtrckr-steps="4">
				<li class="progtrckr-todo">Chọn phương thức chuyển tiền</li>
				<li class="<?php echo isset($step) && $step == 2 ? 'progtrckr-done' : 'progtrckr-todo' ?>">Khai báo thông tin chuyển tiền</li>
				<li class="<?php echo isset($step) && $step == 3 ? 'progtrckr-done' : 'progtrckr-todo' ?>">Xác nhận giao dịch hoàn thành</li>
				
			</ol>
		</div>
			<?php echo form_open('/transfer/transfer_epurse', array('method' => 'post', 'role' => 'form')); ?>
			
			<div class="form-group">
				<label>Số dư khả dụng(đ)</label>
				<div class="div-input">
					<span class="color-green balance-transfer"><?php echo isset($balance) ? number_format($balance) : 0 ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
		
			<div class="form-group">
				<label>Hình thức chuyển tiền</label>
				<div class="div-input">
					<span><?php if(isset($post['trans_met']))
								{
									switch($post['trans_met'])
									{
										case '1' : echo "Chuyển ví nội bộ";
										break;
										case '2' : echo "Chuyển ví dịc vụ";
										break;
										default: echo "";
										break;
									}
								}
						?>
					</span>
				</div>
				<div class="clearfix"></div>
			</div>

			<?php if(isset($post['trans_met']) && $post['trans_met'] == '1'): ?>
				<div class="form-group">
					<label>Tài khoản nhận</label>
					<div class="div-input" style="white-space: nowrap;">
						<span style="display: inline-block; width: 250px;"><?php echo isset($reciverAcc) ? $reciverAcc['username'] : 0 ?></span>
						<span>Họ và tên</span>
						<span style="margin-left: 40px;"><?php echo isset($reciverAcc) ? $reciverAcc['fullname'] : 0 ?></span>
					</div>
					<div class="clearfix"></div>
					
				</div>
			<?php elseif(isset($post['trans_met']) && $post['trans_met'] == '2'): ?>
				<div class="form-group">
					<label>Dịch vụ</label>
					<div class="div-input">
						<span><?php echo isset($post['service_met']) ? $post['service_met'] : "" ?></span>
					</div>
					<div class="clearfix"></div>
				</div>
			<?php endif; ?>
			
			<div class="form-group">
				<label>Số tiền chuyển (đ)</label>
				<div class=" div-input">
					<input id="amountTransfer" maxlength="11" name="amount" class="form-input" 
						type="text" placeholder="Nhập số tiền chuyển" onkeyup="formatCurrency(this, this.value);"
						value="<?php echo set_value('amount') ?>" >
					<span class="form-error"><?php echo isset($amount_error) ? $amount_error : ''; ?><?php echo form_error('amount'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			<?php if(isset($post['trans_met']) && $post['trans_met'] == '1'): ?>
				<div class="form-group">
					<label>Người chịu phí</label>
					<div class="div-input">
					
						<label><input name="pay_fee" class="checkbox_payfee" value="1" checked <?php echo  set_radio('pay_fee', '1', TRUE); ?> type="radio">Người chuyển chịu</label>
						<label style="margin-left: 40px;"><input name="pay_fee" class="checkbox_payfee" value="2" <?php echo  set_radio('pay_fee', '2'); ?> type="radio">Người nhận chịu</label>
						
					</div>
					<div class="clearfix"></div>
				</div>
			<?php endif; ?>
			
			<div class="form-group">
				<label>Phí chuyển tiền (đ)</label>
				<div class="div-input">
					<span id="feeTransfer" class="color-green balance-transfer"><?php echo isset($fee) ? number_format($fee) : '0'; ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="form-group">
				<label>Tổng tiền thanh toán (đ)</label>
				<div class="div-input">
					<span id="realAmount" class="color-green balance-transfer"><?php echo (isset($fee) && isset($post['amount']) && !empty($post['amount'])) ? number_format($fee + $post['amount']) : '0' ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
				
			<div class="form-group">
				<label>Nội dung</label>
				<div class="div-input">
					<textarea name="note" class="form-input" maxlength="255" placeholder="Nội dung chuyển tiền"><?php echo set_value('note') ?></textarea>
					<span class="form-error"><?php echo form_error('note'); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
				<input name="step2" type="hidden" class="button button-main" value="2"/>
			<div class="form-group button-group">
				<label>&nbsp </label>
				<div class="div-input">
					<input name="step2" type="submit" class="button button-main" value="Tiếp tục"/>
					<!--a id='transfer-step' class="button button-main"> Tiếp tục </a-->
					<a href="/transfer" class="button button-sub"> Hủy </a>
				</div>
				<div class="clearfix"></div>
			</div>
			
		<?php echo form_close(); ?>
		
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
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/script.js?v=" . VERSION_WEB; ?>'></script>

</body>
</html>