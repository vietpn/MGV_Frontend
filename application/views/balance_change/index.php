<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>MegaV <?php if (isset($data['title']) && $data['title'] != '') echo "- " . $data['title']; ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo '../images/megaid-favicon.png' ?>"/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "css/bootstrap/bootstrap.min.css" ?>'/>
    <link rel="stylesheet" type="text/css" href='<?php echo base_url() . "assets/js/libs/owl.carousel/owl.carousel.css" ?>'/>
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
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/owl.carousel/owl.carousel.min.js"; ?>'></script>
	
</head>
<body>


<style>
	.pagination {
		margin: -10px 0 20px;
	}
	
	.container-fluid {
		padding-left: 0px;
		padding-right: 0px;
	}
	
	
</style>
<div class="container-fluid trans_history_container">
<div class="row">
	
	<div class="col-md-12">
		
		
				<ul class="breadcrumb">
					<li class="first">|</li>
					<li><a href="#">Biến động số dư</a></li>
				</ul> 
	
					<div class="panel panel-default">

					<div class="panel-body">
					  

					  <!-- Tab panes -->
					  <div class="tab-content">
					    <div role="tabpanel" class="tab-pane active" id="balance">
							<span class="error"><?php echo validation_errors() ?></span>
                            <?php echo form_open('/balance_change/index', array('method' => 'post', 'role' => 'form', 'id' => 'frm_search_list')); ?>
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
								<input type="hidden" id="hidden_page" name="page" />
								<div class="row">
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											
										
											<div class="form-group fix_width_control fix_width_balance">
												<select name="type_code" class="form-input">
													<option value="0">Loại giao dịch</option>
													<?php if(isset($type_deal)): ?>
														<?php foreach($type_deal as $key => $type): ?>
																<option value="<?php echo $key; ?>" <?php echo set_select('type_code', $key, False); ?>><?php echo $type; ?></option>
														<?php endforeach; ?>
													<?php endif; ?>
												</select>
											</div>
										
											<div class="form-group fix_width_control fix-right-control">
											<?php $arrStatus = array(array('val' => '', 'name' => 'Tất cả'),
																	array('val' => '00', 'name' => 'Thành công'),
																	array('val' => '99', 'name' => 'Chờ xử lý'),
																	array('val' => '01', 'name' => 'Thất bại')); ?>
												<select name="status" class="form-input">
													<?php foreach($arrStatus as $status): ?>
														<option value="<?php echo $status['val']; ?>" <?php echo set_select('status', $status['val'], False); ?>><?php echo $status['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
									
								
										<div class="form-group fix_width_control input-group pright">
											<input name="fdate" class="form-input has-datepicker" type="text" value="<?php echo (set_value('fdate')) ? set_value('fdate') : date('d/m/Y'); ?>">
											<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
										</div>
										<div class="form-group fix_width_control input-group w1">
											<hr>
										</div>
										<div class="form-group fix_width_control input-group pleft">
											<input name="tdate" class="form-input has-datepicker" type="text" value="<?php echo (set_value('tdate')) ? set_value('tdate') : date('d/m/Y'); ?>">
											<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
										</div>

										<div class="form-group fix_width_control pleft w20">
												<input name="transId" class="form-input respon-transid" type="text" value="<?php echo set_value('transId'); ?>" placeholder="Mã giao dịch">
											</div>

										<div class="form-group fix_width_control pleft pull-right">
											<input type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-default btn-search pull-right" value="Tìm kiếm"/>
										</div>

									</div>
									<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
										<div class="form-group fix_width_control no-margin" style="width: 80%;">
											<p class="heading-result-history">
												Tổng số giao dịch: <span class="total-his"><?php echo $totalTrans; ?></span><span class="hidden-sm hidden-xs">&nbsp;&nbsp;&nbsp;</span><span class="hidden-md hidden-lg"><br /></span>
	                                             <?php 
	                                             if (isset($text_amount_total)) {
	                                             	echo $text_amount_total;
	                                             }
	                                              ?>
												
												</p>
										</div>
                                        <div class="form-group fix_width_control pleft pull-right no-margin hidden-xs">
                                            <button type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-success btn-export-excel pull-right" name="excel">Xuất Excel</button>
                                        </div>
									</div>
								</div>
							
								<div class="table-responsive table_load_history">
									<table class="table table-bordered table-hover table-striped">
										<thead>
											<tr>
												<th>Mã giao dịch</th>
												<th class="hidden-sm hidden-xs">Loại giao dịch</th>
												<th class="hidden-sm hidden-xs">Thời gian giao dịch</th>
												<th>Tiền giao dịch (đ)</th>
												<th class="hidden-sm hidden-xs">Số dư khả dụng (đ)</th>
												<th>Trạng thái</th>
											</tr>
										</thead>
										<tbody>
												<?php if(isset($listTrans)): ?>

									

													<?php foreach($listTrans as $transaction): ?>
														<tr>
															<td><?php echo (isset($transaction->originalReqId)) ? $transaction->originalReqId : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php 
															foreach ($type_deal as $key => $value) {
																if ($key==$transaction->transType) {
																	echo $value;
																}
															}
															 ?></td>
															<td class="hidden-sm hidden-xs"><?php echo (isset($transaction->createdDate)) ? date('d/m/Y H:i:s', strtotime($transaction->createdDate)) : ""; ?></td>
															<td><?php echo (isset($transaction->amount)) ? number_format($transaction->amount) : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php
															if (isset($transaction->receiverUsername) && $transaction->receiverUsername==$this->session_memcached->userdata['info_user']['userID']) {
																echo number_format($transaction->receiverBalAf);
															}else{
															 echo (isset($transaction->balAfter)) ? number_format($transaction->balAfter) : "";
															}
															?>
															</td>
															<td>
																<?php 
																	//echo $transaction->status; 
																	//-1: Khởi tạo, 00: Thành công; 01: Đã redirect sang Bank; 
																	//02: Thất bại; 03: Trừ bank thành công, cộng ví lỗi; 
																	//04: Bắt đầu xử lý Update result; 99: Pending 
																	
																	switch($transaction->status)
																	{
																		case '-1': echo 'Khởi tạo';
																		break;
																		case '00': echo 'Thành công';
																		break;
																		case '01': echo 'Đã redirect sang Bank';
																		break;
																		case '02': echo 'Thất bại';
																		break;
																		case '03': echo 'Trừ bank thành công, cộng ví lỗi';
																		break;
																		case '4': echo 'Bắt đầu xử lý Update result';
																		break;
																		case '99': echo 'Chờ xử lý';
																		break;
																		default : echo 'Thất bại';
																		break;
																	}
																?>
															</td>
														</tr>
													<?php endforeach; ?>
												<?php endif; ?>
										</tbody>
									</table>
									<div class="row">
										<div class="col-xs-12 pull-left">
											<div class="dataTables_paginate paging_bootstrap">
													<?php if(!empty($paginationLinks)) echo $paginationLinks; ?>
											</div>
										</div>
									</div>
								</div>
							<?php echo form_close(); ?>
						</div>

					  
					  </div>


					</div>	
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
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/metisMenu.min.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/libs/jquery.cookie.js"; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/datepicker.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/cmnd.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript" language="javascript" src='<?php echo base_url() . "assets/js/elements/script.js?v=" . VERSION_WEB; ?>'></script>
	<script type="text/javascript">

		
		

	function viewportCustom() {
		    var e = window, a = 'inner';
		    if (!('innerWidth' in window )) {
		        a = 'client';
		        e = document.documentElement || document.body;
		    }
		    return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
		}


	var deviceWidth = viewportCustom();
	if (deviceWidth.width<'570'){


		$(".owl-carousel").each(function(index, el) {
          var config = $(this).data();
          config.navText = ['<i class="fa fa-angle-double-left" aria-hidden="true"></i>','<i class="fa fa-angle-double-right" aria-hidden="true"></i>'];
          config.smartSpeed="300";
          if($(this).hasClass('owl-style2')){
            config.animateOut="fadeOut";
            config.animateIn="fadeIn";    
            config.navigation=true;    
          }
          $(this).owlCarousel(config);
        });
	}
		

	
	</script>
</body>
</html>