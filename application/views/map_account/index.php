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
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/css/element/style.css?v=" . VERSION_WEB; ?>'/>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery-1.11.3.min.js"; ?>'></script>
</head>
<body>
<style>
		.form-center .form-group {
			width: 100%;
		}
	</style>
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a href="javascript:void(0);" class="back_acc_manage">Quản trị tài khoản</a></li>
		<li><a>Tài khoản ngân hàng</a></li>
	</ul> 
<div class="row form-center">
	<div class="form-group" style="text-align: center;">

			<ul class="tab-account-map" style="display: inline-block;">
				<li><a href="/banks_account">TK rút tiền theo phiên</a></li>
				<li><a href="/firm_banking">TK rút tiền nhanh</a></li>
			    <li class="active"><a>Liên kết tài khoản</a></li>
			</ul>
			
	</div>
	
	
</div>
<?php 
	if (!empty($listBankAcc)) { 
		?>
		<div class="row form-center" id="form-list-acc" data-count="<?php echo isset($listBankAcc) ? count($listBankAcc) : ''; ?>" style="overflow-y: auto;max-height: 350px;">
			<div class="form-group ">
					<?php 
					foreach($listBankAcc as $key => $bankAcc): ?>
						<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 bank_div">
							<div class="panel panel-default">
								<div class="panel-body" style="padding-left: 10px;padding-right: 10px;">
									<div class="row bank-info">
										<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">Ngân hàng</div>
										<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12"><?php echo $bankAcc->bankName; ?></div>
									</div>
									<?php if(isset($bankAcc->bankaccount)): ?>
									<div class="row bank-info">
										<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">Tài khoản ngân hàng</div>
										<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12"><?php echo $bankAcc->bankaccount; ?></div>
									</div>
									<?php endif; ?>
									<div class="row bank-info">
										<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">Ngày liên kết</div>
										<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12"><?php echo date('d-m-Y H:i:s',strtotime($bankAcc->createDate)); ?></div>
									</div>
									<div class="row bank-info">
										<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">Tài khoản</div>
										<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12"><?php echo $this->session_memcached->userdata['info_user']['userID']; ?></div>
									</div>
									<div class="row bank-info">
										<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">Trạng thái liên kết</div>
										<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12"><?php 
										switch ($bankAcc->status) {
											case '1':
												echo "Thành công.";
												break;
											case '99':
												echo "Đang xử lý.";
												break;
											
											default:
												echo "Thất bại.";
												break;
										}
										 ?></div>
									</div>
								</div>
								<div class="panel-footer">
									<?php 
									switch ($bankAcc->status) {
										case '1':
											echo '<a class="un_mapping" data-bank-name="'.$bankAcc->bankName.'" data-href="/map_account/unMapBankAccount/'.$bankAcc->bankcode.'" data-toggle="modal" data-target="#modal_confirm_unmap"><i class="fa fa-chain-broken" aria-hidden="true"></i> Hủy liên kết</a>';
											break;
										case '99':
											echo '<a href="#" class="disable_link">Hủy liên kết</a>';
											break;
										
										default:
											echo '<a class="un_mapping" data-bank-name="'.$bankAcc->bankName.'" data-href="/map_account/unMapBankAccount/'.$bankAcc->bankcode.'" data-toggle="modal" data-target="#modal_confirm_unmap"><i class="fa fa-chain-broken" aria-hidden="true"></i> Hủy liên kết</a>';
									}
									?>
								</div>
							</div>
						</div>
						<?php if($key != 0 && $key%2 != 0): ?>
						<div class="clearfix" data-count="<?php echo $key; ?>"></div>
						<?php endif; ?>
					<?php endforeach; ?>
			</div>
		</div>
		<div class="modal fade" id="modal_confirm_unmap" role="dialog" style="color: #333;">
		    <div class="modal-dialog modal-md">
		    
		      <!-- Modal content-->
		      <div class="modal-content" style="border-radius: 0px;margin-top: 150px;width: 80%;">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		          <h4 class="modal-title">Thông báo từ hệ thống</h4>
		        </div>
		        <div class="modal-body text-center">
		        	<div class="warning-msg-alert"></div>
		        	<div class="clearfix"></div>
					<p>Bạn có chắc chắn muốn hủy liên kết với ngân hàng <span></span>?</p>
		        </div>
		        <div class="modal-footer">
		          <div class="col-md-12 text-center">
					<?php echo form_open('', array('method' => 'post', 'role' => 'form', 'class' => 'send_un_map')); ?>
		          	<button type="submit" class="btn btn-success" style="font-family:'Roboto Regular';">Xác nhận</button>
					
					<a class="btn btn-default close-history-trans" style="font-family:'Roboto Regular';" data-dismiss="modal">Hủy bỏ</a>
					<?php echo form_close(); ?>
				  </div>
		          
		        </div>
		      </div>
		      
		    </div>
		  </div>
		<div class="row form-center" style="margin-top:10px;">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					
					<a class="button button-main" href="/map_account/mapBankAccount" style="width: 240px;">Thêm liên kết tài khoản</a>
				</div>
		</div>
<?php 
	}else{ ?>
		<div class="row form-center" id="form-list-acc" style="overflow-y: auto;max-height: 350px;">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
				<div style="display: inline-block;">
					<div class="text-center">
						<span style="color: #ccc;" class="warning-msg-map"></span>
						<span>Bạn chưa liên kết tài khoản nào.</span>
					</div>
				</div>
				<div class="clearfix"></div>
				<a class="button button-main" href="/map_account/mapBankAccount" style="width: 240px;">Liên kết tài khoản</a>
			</div>

		</div>
<?php 
	}
?>

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

	
<script type="text/javascript">
	
	$(document).ready(function(){
		$('body').on('click','.un_mapping',function(){
			var href = $(this).attr('data-href');
			var bank_name = $(this).attr('data-bank-name');
			$('#modal_confirm_unmap .modal-body p span').text(bank_name);
			$('#modal_confirm_unmap .send_un_map').attr('action',href);
		});
		
		$('body').on('click','.send_un_map button',function(){
			$('#modal_confirm_unmap .send_un_map button').attr('disabled', 'disabled');
			$('#modal_confirm_unmap .send_un_map button').style('cursor', 'not-allowed');
		});
		
	});
</script>
	
</body>
</html>