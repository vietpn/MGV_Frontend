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
				<li class="active"><a>TK rút tiền nhanh</a></li>
			    <li><a href="/map_account">Liên kết tài khoản</a></li>
			</ul>
			
	</div>
	
	
</div>
<?php 
	if (!empty($listBankAcc)) { ?>
		<div class="row form-center" id="form-list-acc" data-count="<?php echo isset($listBankAcc) ? count($listBankAcc) : ''; ?>" style="overflow-y: auto;max-height: 350px;">
			<div class="form-group ">
			
					<?php foreach($listBankAcc as $key => $bankAcc){ 
						if ($bankAcc->status=='00') { ?>
							
								<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 bank_div">
									<div class="panel panel-default">
											<div class="panel-body" style="padding-left: 10px;padding-right: 10px;">
												<div class="row bank-info">
													<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">Ngân hàng</div>
													<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12"><?php echo $bankAcc->bankName; ?></div>
												</div>
												<div class="row bank-info">
													<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
														<?php if(isset($bankAcc->isCard)): ?>
															<?php if($bankAcc->isCard == '1'): ?>
																Số thẻ
															<?php elseif($bankAcc->isCard == '0'): ?>
																Số tài khoản
															<?php endif; ?>
														<?php else: ?>
															Số thẻ
														<?php endif; ?>
													</div>
													<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12"><?php echo $bankAcc->bankAccount; ?></div>
												</div>
												<div class="row bank-info">
													<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">Ngày tạo</div>
													<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12"><?php echo date('d-m-Y', strtotime($bankAcc->timeCreate)); ?></div>
												</div>
												<div class="row bank-info">
													<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
														<?php if(isset($bankAcc->isCard)): ?>
															<?php if($bankAcc->isCard == '1'): ?>
																Tên chủ thẻ
															<?php elseif($bankAcc->isCard == '0'): ?>
																Tên chủ tài khoản
															<?php endif; ?>
														<?php else: ?>
															Tên chủ thẻ
														<?php endif; ?>
														
													</div>
													<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12"><?php echo $bankAcc->bankAccountName; ?></div>
												</div>
												<div class="row bank-info">
													<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">Trạng thái</div>
													<div class="col-md-6 col-lg-6 col-sm-12 col-xs-12"><?php 
													switch ($bankAcc->status) {
														case '00':
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
										</div>
									</div>
							<?php if($key != 0 && $key%2 != 0): ?>
							<div class="clearfix" data-count="<?php echo $key; ?>"></div>
							<?php endif; ?>

						<?php 
						}
						?>
						
					<?php 
					}

					 ?>
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
		          	<a type="button" href="#" class="btn btn-success send_un_map" style="font-family:'Roboto Regular';">Xác nhận</a>
		          <button type="button" class="btn btn-default close-history-trans" style="font-family:'Roboto Regular';" data-dismiss="modal">Hủy bỏ</button>
		          </div>
		          
		        </div>
		      </div>
		      
		    </div>
		  </div>
		<div class="row form-center" style="margin-top:10px;">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					
					<a class="button button-main" href="/firm_banking/createBankAccount" style="width: 240px;">Thêm tài khoản ngân hàng</a>
				</div>
		</div>
<?php 
	}else{ ?>
		<div class="row form-center" id="form-list-acc" style="overflow-y: auto;max-height: 350px;">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
				<div style="display: inline-block;">
					<div class="text-center">
						<span style="color: #ccc;" class="warning-msg-map"></span>
						<span>Bạn chưa có tài khoản ngân hàng nào.</span>
					</div>
				</div>
				<div class="clearfix"></div>
				<a class="button button-main" href="/firm_banking/createBankAccount" style="width: 240px;">Tạo tài khoản ngân hàng</a>
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
			$('#modal_confirm_unmap .send_un_map').attr('href',href);
		});
	});
</script>
	
</body>
</html>