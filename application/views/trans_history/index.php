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
					<li><a href="#">Lịch sử giao dịch</a></li>
				</ul> 

					<!--<div class="get-height">-->
						<!-- Nav tabs -->
						<!---->
						  <ul class="nav nav-tabs tab_trans_history owl-carousel" role="tablist" data-dots="false" data-loop="false" data-nav="true" data-margin="0" data-autoplayTimeout="1000" data-autoplayHoverPause="true" data-responsive='{"0":{"items":3},"420":{"items":5},"600":{"items":7}}'>
						    <li role="presentation" class="<?php echo (isset($tab) && $tab == "payment" ) ? 'active' : ""; ?>"><a href="javascript:void(0)" data-target="#payment" aria-controls="payment" role="tab" data-toggle="tab">Nạp tiền</a></li>
						    <li role="presentation" class="<?php echo (isset($tab) && $tab == "transfer" ) ? 'active' : ""; ?>"><a href="javascript:void(0)" data-target="#transfer" aria-controls="transfer" role="tab" data-toggle="tab">Chuyển tiền</a></li>
						    <li role="presentation" class="<?php echo (isset($tab) && $tab == "withdraw" ) ? 'active' : ""; ?>"><a href="javascript:void(0)" data-target="#withdraw" aria-controls="withdraw" role="tab" data-toggle="tab">Rút tiền</a></li>
	                        <li role="presentation" class="<?php echo (isset($tab) && $tab == "paymentphone" ) ? 'active' : ""; ?>"><a href="javacsript:void(0)" data-target="#paymentphone" aria-controls="withdraw" role="tab" data-toggle="tab">Nạp điện thoại</a></li>
						    <li role="presentation" class="<?php echo (isset($tab) && $tab == "topup" ) ? 'active' : ""; ?>"><a href="javacsript:void(0)" data-target="#topup" aria-controls="withdraw" role="tab" data-toggle="tab">Mua mã thẻ</a></li>
						    <li role="presentation" class="<?php echo (isset($tab) && $tab == "paymentgame" ) ? 'active' : ""; ?>"><a href="javacsript:void(0)" data-target="#paymentgame" aria-controls="withdraw" role="tab" data-toggle="tab">Nạp Game</a></li>
	                          <li role="presentation" class="<?php echo (isset($tab) && $tab == "paymentbills" ) ? 'active' : ""; ?>"><a href="javacsript:void(0)" data-target="#paymentbills" aria-controls="paymentbills" role="tab" data-toggle="tab">Thanh toán</a></li>
	                      </ul>
					<!--</div>-->
					<!--<a href="javascript:void(0);" class="next-tab-history hidden-lg hidden-md"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>-->
	
					<div class="panel panel-default">

					<div class="panel-body">
					  

					  <!-- Tab panes -->
					  <div class="tab-content">
					    <div role="tabpanel" class="tab-pane <?php echo (isset($tab) && $tab == "payment" ) ? 'active' : ""; ?>" id="payment">
							<span class="error"><?php echo validation_errors() ?></span>
                            <?php echo form_open('/trans_history/payment', array('method' => 'post', 'role' => 'form', 'id' => 'frm_search_list')); ?>
                            	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
								<input type="hidden" id="hidden_page" name="page" />
								<div class="row">
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											
										
											<div class="form-group fix_width_control">
												<select name="provider_code" class="form-input">
													<option value="-1">Ngân hàng</option>
													<?php if(isset($listBank)): ?>
														<?php foreach($listBank as $bank): ?>
															<?php if($bank->type == '2'): ?>
																<option value="<?php echo $bank->recId; ?>" <?php echo $tab == "payment" ? set_select('provider_code', $bank->recId, False) : ''; ?>><?php echo $bank->providerName; ?></option>
															<?php endif; ?>
														<?php endforeach; ?>
													<?php endif; ?>
												</select>
											</div>
										
											<div class="form-group fix_width_control fix-right-control">
											<?php $arrStatus = array(array('val' => '-1', 'name' => 'Trạng thái'),
																	array('val' => '99', 'name' => 'Chờ xử lý'),
																	array('val' => '00', 'name' => 'Thành công'),
																	array('val' => '01', 'name' => 'Thất bại')); ?>
												<select name="status" class="form-input">
													<?php foreach($arrStatus as $status): ?>
														<option value="<?php echo $status['val']; ?>" <?php echo $tab == "payment" ? set_select('status', $status['val'], False) : ''; ?>><?php echo $status['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>


											<div class="form-group fix_width_control form-fix-responsive">
											<?php $arrHT = array(
											        array('val' => '-1', 'name' => 'Hình thức'),
                                                    array('val' => '1', 'name' => 'Nạp tiền theo phiên'),
                                                    array('val' => '2', 'name' => 'Nạp tiền nhanh'),
                                                    array('val' => '3', 'name' => 'Nạp tiền tài khoản liên kết'),
                                            ); ?>
												<select name="hinh_thuc" class="form-input">
													<?php foreach($arrHT as $name): ?>
														<option value="<?php echo $name['val']; ?>"<?php echo ($tab == 'payment' && $after_post['hinh_thuc']==$name['val']) ? 'selected="selected"' : ''; ?>><?php echo $name['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
									
								
										<div class="form-group fix_width_control input-group pright">
											<input name="fdate" class="form-input has-datepicker" type="text" value="<?php echo ($tab == 'payment' && set_value('fdate')) ? set_value('fdate') : date('d/m/Y'); ?>">
											<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
										</div>
										<div class="form-group fix_width_control input-group w1">
											<hr>
										</div>
										<div class="form-group fix_width_control input-group pleft">
											<input name="tdate" class="form-input has-datepicker" type="text" value="<?php echo ($tab == 'payment' && set_value('tdate')) ? set_value('tdate') : date('d/m/Y'); ?>">
											<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
										</div>

										<div class="form-group fix_width_control pleft w20">
												<input name="transId" class="form-input respon-transid" type="text" value="<?php echo (isset($redirect_trans_id) && $tab == 'payment') ? $redirect_trans_id : ""; ?><?php echo $tab == 'payment' ? set_value('transId') : '' ?>" placeholder="Mã giao dịch">
											</div>

										<div class="form-group fix_width_control pleft pull-right">
											<input type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-default btn-search pull-right" value="Tìm kiếm"/>
										</div>

									</div>
									<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
										<div class="form-group fix_width_control no-margin" style="width: 80%;">
											<p class="heading-result-history">
												Tổng số giao dịch: <span class="total-his"><?php echo $tab == 'payment' ? $totalTrans : 0; ?></span><span class="hidden-sm hidden-xs">&nbsp;&nbsp;&nbsp;</span><span class="hidden-md hidden-lg"><br /></span><span class="total-fix-responsive">Tổng tiền thực nhận: <span class="amount-his"><?php echo $tab == 'payment' ? $totalAmount : 0; ?> vnđ</span>
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
												<th class="hidden-sm hidden-xs">Ngân hàng</th>
												<th>Số tiền (đ)</th>
												<th style="width: 130px;" class="hidden-sm hidden-xs">Phí cố định theo giao dịch (đ)</th>
												<th class="hidden-sm hidden-xs">Phí theo tỉ lệ</th>
												<th class="hidden-sm hidden-xs">Tổng phí giao dịch (đ)</th>
												<th class="hidden-sm hidden-xs">Thực nhận (đ)</th>
												<th class="hidden-sm hidden-xs">Thời gian yêu cầu</th>
												<th>Trạng thái</th>
											</tr>
										</thead>
										<tbody>
											<?php if(isset($tab) && $tab == "payment"): ?>
												<?php if(isset($listTrans)): ?>
													<?php foreach($listTrans as $transaction): ?>
														<tr>
															<td><?php echo (isset($transaction->requestId)) ? $transaction->requestId : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php echo (isset($transaction->bankName)) ? $transaction->bankName : ""; ?></td>
															<td><?php echo (isset($transaction->amount)) ? number_format($transaction->amount) : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php echo (isset($transaction->fixFee)) ? number_format($transaction->fixFee) : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php echo (isset($transaction->rateFee)) ? $transaction->rateFee : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php echo (isset($transaction->feeAmount)) ? number_format($transaction->feeAmount) : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php echo (isset($transaction->realReceive)) ? number_format($transaction->realReceive) : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php echo (isset($transaction->timeCreate)) ? date('d/m/Y H:i:s', strtotime($transaction->timeCreate)) : ""; ?></td>
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
											<?php endif; ?>
										</tbody>
									</table>
									<div class="row">
										<div class="col-xs-12 pull-left">
											<div class="dataTables_paginate paging_bootstrap">
												<?php if(isset($tab) && $tab == "payment"): ?>
													<?php if(!empty($paginationLinks)) echo $paginationLinks; ?>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
							<?php echo form_close(); ?>
						</div>
						
					    <div role="tabpanel" class="tab-pane <?php echo (isset($tab) && $tab == "transfer" ) ? 'active' : ""; ?>" id="transfer">
                            <span class="error"><?php echo validation_errors() ?></span>
							<?php echo form_open('/trans_history/transfer', array('method' => 'post', 'role' => 'form', 'id' => 'frm_search_list')); ?>
								<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
								<input type="hidden" id="hidden_page" name="page" />
								<div class="row">
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											
											
											<div class="form-group fix_width_control">
											<?php $arrTransferType = array(
																		array('val' => '-1', 'name' => 'Hình thức'),
																		array('val' => TRANSFER_TO_USER , 'name' => 'Chuyển tiền ví nội bộ'),
																		array('val' => TRANSFER_TO_SERVICES, 'name' => 'Chuyển tiền ví dịch vụ')
																); 
											?>
												

												<select name="transferType" class="form-input">
													<?php foreach($arrTransferType as $star): ?>
														<option value="<?php echo $star['val']; ?>" <?php echo ($tab == 'transfer' && $after_post['transferType']==$star['val']) ? 'selected="selected"' : ''; ?>><?php echo $star['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="form-group fix_width_control fix-right-control">
											<?php $arrStatus = array(array('val' => '-1', 'name' => 'Trạng thái'),
																	array('val' => '99', 'name' => 'Chờ xử lý'),
																	array('val' => '00', 'name' => 'Thành công'),
																	array('val' => '01', 'name' => 'Thất bại')); ?>
												<select name="status" class="form-input">
													<?php foreach($arrStatus as $status): ?>
														<option value="<?php echo $status['val']; ?>" <?php echo $tab == 'transfer' ? set_select('status', $status['val'], False) : ''; ?>><?php echo $status['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
                                            <div class="form-group fix_width_control form-fix-responsive">
                                                <?php
                                                $transferTypes = array(
                                                    '0' => 'Loại giao dịch',
                                                    '1' => 'Chuyển tiền',
                                                    '2' => 'Nhận tiền',
                                                );
                                                ?>
                                                <select name="transfer_type" class="form-input">
                                                    <?php foreach ($transferTypes as $key => $value): ?>
                                                        <option value="<?php echo $key; ?>" <?php echo $tab == 'transfer' ? set_select('transfer_type', $key, False) : ''; ?>><?php echo $value; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
											<div class="form-group fix_width_control input-group pright">
												<input name="fdate" class="form-input has-datepicker" type="text" value="<?php echo ($tab == 'transfer' && set_value('fdate')) ? set_value('fdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control input-group w1">
												<hr>
											</div>
											<div class="form-group fix_width_control input-group pleft">
												<input name="tdate" class="form-input has-datepicker" type="text" value="<?php echo ($tab == 'transfer' && set_value('tdate')) ? set_value('tdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control w20 pleft">
												<input name="transId" class="form-input" type="text" value="<?php echo (isset($redirect_trans_id) && $tab == 'transfer') ? $redirect_trans_id : ""; ?><?php echo $tab == 'transfer' ? set_value('transId'): '' ?>" placeholder="Mã giao dịch">
											</div>
											<div class="form-group fix_width_control input-group pleft pull-right">
												<input type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-default btn-search pull-right" value="Tìm kiếm"/>
											</div>
										</div>
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											<div class="form-group fix_width_control input-group no-margin" style="width: 80%;">
											<p class="heading-result-history">
												Tổng số giao dịch: <span class="total-his"><?php echo $tab == 'transfer' ? $totalTrans : 0; ?></span><span class="hidden-sm hidden-xs">&nbsp;&nbsp;&nbsp;</span><span class="hidden-md hidden-lg"><br /></span><span class="total-fix-responsive"></span>Tổng tiền thực nhận: <span class="amount-his"><?php echo $tab == 'transfer' ? $plusAmount : 0; ?> vnđ&nbsp;&nbsp;&nbsp;</span>Tổng tiền thực chuyển: <span class="amount-his"><?php echo $tab == 'transfer' ? $minusAmount : 0; ?> vnđ</span>
												</p>
											</div>
                                            <div class="form-group fix_width_control input-group pleft pull-right no-margin hidden-xs">
                                                <button type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-success btn-export-excel pull-right" name="excel">Xuất Excel</button>
                                            </div>
										</div>
									
								</div>
							
							
								<div class="table-responsive table_load_history">
									<table class="table table-bordered table-hover table-striped">
										<thead>
											<tr>
												<th>Mã giao dịch</th>
												<th class="hidden-sm hidden-xs">Hình thức</th>
												<th class="hidden-sm hidden-xs">Số tiền chuyển (đ)</th>
												<th class="hidden-sm hidden-xs">Tài khoản chuyển</th>
												<th>Tài khoản nhận</th>
												<th class="hidden-sm hidden-xs">Thời gian chuyển</th>
												<th>Trạng thái</th>
												<th><span class="hidden-sm hidden-xs">Thao tác</span></th>
											</tr>
										</thead>
										<tbody>
											<?php if(isset($tab) && $tab == "transfer"): ?>
												<?php if(isset($listTrans)): ?>
													<?php foreach($listTrans as $transaction): ?>
														<tr>
															<td><?php echo (isset($transaction->transId)) ? $transaction->transId : ""; ?></td>
															<td class="hidden-sm hidden-xs">
																<?php 
                                                                switch($transaction->transferType) {
                                                                    case 4:
                                                                        echo "Chuyển tiền ví nội bộ";
                                                                        break;
                                                                    case TRANSFER_TO_SERVICES:
                                                                        echo "Chuyển tiền ví dịch vụ";
                                                                        break;
                                                                    default:
                                                                        break;
                                                                }
																?>
															</td>
															<td class="hidden-sm hidden-xs"><?php echo (isset($transaction->amount)) ? number_format($transaction->amount) : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php echo (isset($transaction->sendUserName)) ? $transaction->sendUserName : ""; ?></td>
															<td><?php echo (isset($transaction->receiveUserName)) ? $transaction->receiveUserName : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php echo (isset($transaction->createdDate)) ? date('d/m/Y H:i:s', strtotime($transaction->createdDate)) : ""; ?></td>
															<td>
																<?php 
																	if(isset($transaction->status))
																	{
																		switch($transaction->status)
																		{
																			case '00': echo 'Thành công';
																			break;
																			//case '01': echo 'Thất bại';
																			//break;
																			case '99': echo 'Chờ xử lý';
																			break;
																			default : echo 'Thất bại';
																			break;
																		}
																	}
																?>
															</td>
															<td><?php echo "<a  data-toggle='modal' data-target='#modal_transfer' href='javascript:void(0);' class='detail_transfer' data-request-id='".$transaction->transId."' data-fdate='".$after_post['fdate']."' data-tdate='".$after_post['tdate']."' data-transfer-type='".$after_post['transferType']."'><span class='hidden-sm hidden-xs'>Xem chi tiết</span><i class='fa fa-eye hidden-lg hidden-md' aria-hidden='true'></i></a>"; ?></td>
														</tr>
													<?php endforeach; ?>
												<?php endif; ?>
											<?php endif; ?>
										</tbody>
									</table>
									<div class="row">
										<div class="col-xs-12 pull-left">
											<div class="dataTables_paginate paging_bootstrap">
												<?php if(isset($tab) && $tab == "transfer"): ?>
													<?php if(!empty($paginationLinks)) echo $paginationLinks; ?>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
							<?php echo form_close(); ?>
						</div>

						<div class="modal fade" id="modal_transfer" role="dialog" style="color: #333;">
						    <div class="modal-dialog">
						    
						      <!-- Modal content-->
						      <div class="modal-content" style="border-radius: 0px;">
						        <div class="modal-header">
						          <h4 class="modal-title">Chi tiết giao dịch</h4>
						        </div>
						        <div class="modal-body">
						        </div>
						        <div class="modal-footer">
						          <button type="button" class="btn btn-success close-history-trans" data-dismiss="modal">Đóng lại</button>
						        </div>
						      </div>
						      
						    </div>
						  </div>

					    <div role="tabpanel" class="tab-pane <?php echo (isset($tab) && $tab == "withdraw" ) ? 'active' : ""; ?>" id="withdraw">
                            <span class="error"><?php echo validation_errors() ?></span>
							<?php echo form_open('/trans_history/withdraw', array('method' => 'post', 'role' => 'form', 'id' => 'frm_search_list')); ?>
								<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
								<input type="hidden" id="hidden_page" name="page" />
								<div class="row">
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											
											<div class="form-group fix_width_control">
												<select name="provider_code" class="form-input">
													<option value="-1">Ngân hàng</option>
													<?php if(isset($listBank)): ?>
														<?php foreach($listBank as $bank): ?>
															<?php if($bank->type == '2'): ?>
																<option value="<?php echo $bank->recId; ?>" <?php echo $tab == 'withdraw' ? set_select('provider_code', $bank->recId, False) : ''; ?>><?php echo $bank->providerName; ?></option>
															<?php endif; ?>
														<?php endforeach; ?>
													<?php endif; ?>
												</select>
											</div>
											<div class="form-group fix_width_control fix-right-control">
											<?php $arrStatus = array(array('val' => '-1', 'name' => 'Trạng thái'),
																	array('val' => '99', 'name' => 'Chờ xử lý'),
																	array('val' => '00', 'name' => 'Thành công'),
																	array('val' => '01', 'name' => 'Thất bại')); ?>
												<select name="status" class="form-input">
													<?php foreach($arrStatus as $status): ?>
														<option value="<?php echo $status['val']; ?>" <?php echo $tab == 'withdraw' ? set_select('status', $status['val'], False) : ''; ?>><?php echo $status['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
                                            <div class="form-group fix_width_control form-fix-responsive">
                                                <?php
                                                $withdrawTypes = array(
                                                    '' => 'Hình thức',
                                                    '6' => 'Rút tiền theo phiên',
                                                    '7' => 'Rút tiền nhanh',
                                                    '8' => 'Rút tiền qua tài khoản liên kết',
                                                );
                                                ?>
                                                <select name="withdraw_type" class="form-input">
                                                    <?php foreach($withdrawTypes as $key => $value): ?>
                                                        <option value="<?php echo $key ?>" <?php echo $tab == 'withdraw' ? set_select('withdraw_type', $key, False) : ''; ?>><?php echo $value ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
											<div class="form-group fix_width_control input-group pright">
												<input name="fdate" class="form-input has-datepicker" type="text" value="<?php echo ($tab == 'withdraw' && set_value('fdate')) ? set_value('fdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control input-group w1">
												<hr>
											</div>
											<div class="form-group fix_width_control input-group pleft">
												<input name="tdate" class="form-input has-datepicker" type="text" value="<?php echo ($tab == 'withdraw' && set_value('tdate')) ? set_value('tdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control w20 pleft">
												<input name="transId" class="form-input" type="text" value="<?php echo (isset($redirect_trans_id) && $tab == 'withdraw') ? $redirect_trans_id : ""; ?><?php echo $tab == 'withdraw' ? set_value('transId') : '' ?>" placeholder="Mã giao dịch">
											</div>
											<div class="form-group fix_width_control input-group pleft pull-right">
												<input type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-default btn-search pull-right" value="Tìm kiếm"/>
											</div>
									</div>
									<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
										<div class="form-group fix_width_control input-group no-margin" style="width: 80%;">
											<p class="heading-result-history">
												Tổng số giao dịch: <span class="total-his"><?php echo $tab == 'withdraw' ? $totalTrans : 0; ?></span><span class="hidden-sm hidden-xs">&nbsp;&nbsp;&nbsp;</span><span class="hidden-md hidden-lg"><br /></span><span class="total-fix-responsive"></span>Tổng tiền thực nhận: <span class="amount-his"><?php echo $tab == 'withdraw' ? $totalAmount : 0; ?> vnđ</span>
												</p>
										</div>
                                        <div class="form-group fix_width_control input-group pleft pull-right no-margin hidden-xs">
                                            <button type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-success btn-export-excel pull-right" name="excel">Xuất Excel</button>
                                        </div>
									</div>
								</div>
							
							
								<div class="table-responsive table_load_history">
									<table class="table table-bordered table-hover table-striped">
										<thead>
											<tr>
												<th>Mã giao dịch</th>
												<th class="hidden-sm hidden-xs">Ngân hàng</th>
												<th>Số tiền (đ)</th>
												<th style="width:130px;" class="hidden-sm hidden-xs">Phí cố định theo giao dịch (đ)</th>
												<th class="hidden-sm hidden-xs">Phí theo tỉ lệ</th>
												<th class="hidden-sm hidden-xs">Tổng phí giao dịch (đ)</th>
												<th class="hidden-sm hidden-xs">Thực nhận (đ)</th>
												<th class="hidden-sm hidden-xs">Thời gian yêu cầu</th>
												<th>Trạng thái</th>
											</tr>
										</thead>
										<tbody>
											<?php if(isset($tab) && $tab == "withdraw"): ?>
												<?php if(isset($listTrans)): ?>
													<?php foreach($listTrans as $transaction): ?>
														<tr>
															<td><?php echo (isset($transaction->orgTransId)) ? $transaction->orgTransId : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php echo (isset($transaction->bankName)) ? $transaction->bankName : ""; ?></td>
															<td><?php echo (isset($transaction->amount)) ? number_format($transaction->amount) : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php echo (isset($transaction->fixFee)) ? number_format($transaction->fixFee) : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php echo (isset($transaction->rateFee)) ? $transaction->rateFee : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php echo (isset($transaction->feeAmount)) ? number_format($transaction->feeAmount) : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php echo (isset($transaction->realMinus)) ? number_format($transaction->realMinus) : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php echo (isset($transaction->timeCreate)) ? date('d/m/Y H:i:s', strtotime($transaction->timeCreate)) : ""; ?></td>
															<td>
																<?php 
																	if(isset($transaction->status))
																	{
																		switch($transaction->status)
																		{
																			case '00': echo 'Thành công';
																			break;
																			//case '02': echo 'Thất bại';
																			//break;
																			case '99': echo 'Chờ xử lý';
																			break;
																			default : echo 'Thất bại';
																			break;
																		}
																	}
																?>
															</td>
														</tr>
													<?php endforeach; ?>
												<?php endif; ?>
											<?php endif; ?>
										</tbody>
									</table>
									<div class="row">
										<div class="col-xs-12 pull-left">
											<div class="dataTables_paginate paging_bootstrap">
												<?php if(isset($tab) && $tab == "withdraw"): ?>
													<?php if(!empty($paginationLinks)) echo $paginationLinks; ?>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
							<?php echo form_close(); ?>
						</div>
					    
						<div role="tabpanel" class="tab-pane <?php echo (isset($tab) && $tab == "paymentphone" ) ? 'active' : ""; ?>" id="paymentphone">
                            <span class="error"><?php echo validation_errors() ?></span>
							<?php echo form_open('/trans_history/paymentphone', array('method' => 'post', 'role' => 'form', 'id' => 'frm_search_list')); ?>
								<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
								<input type="hidden" id="hidden_page" name="page" />
								<div class="row">
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											<div class="form-group fix_width_control form-fix-responsive">
											<?php $arrStatus = array(array('val' => '-1', 'name' => 'Trạng thái'),
																	array('val' => '99', 'name' => 'Chờ xử lý'),
																	array('val' => '00', 'name' => 'Thành công'),
																	array('val' => '01', 'name' => 'Thất bại')); ?>
												<select name="status" class="form-input">
													<?php foreach($arrStatus as $status): ?>
														<option value="<?php echo $status['val']; ?>" <?php echo ($tab == "paymentphone" && set_select('status', $status['val'], False)) ? set_select('status', $status['val'], False) : ''; ?>><?php echo $status['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="form-group fix_width_control input-group pright">
												<input name="fdate" class="form-input has-datepicker" type="text" value="<?php echo ($tab == "paymentphone" && set_value('fdate')) ? set_value('fdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control input-group w1">
												<hr>
											</div>
											<div class="form-group fix_width_control input-group pleft">
												<input name="tdate" class="form-input has-datepicker" type="text" value="<?php echo ($tab == "paymentphone" && set_value('tdate')) ? set_value('tdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control pleft w20">
												<input name="transId" class="form-input" type="text" value="<?php echo (isset($redirect_trans_id) && $tab == 'paymentphone') ? $redirect_trans_id : ""; ?><?php echo ( $tab == "paymentphone" && set_value('transId')) ? set_value('transId') : '' ?>" placeholder="Mã giao dịch">
											</div>
											<div class="form-group fix_width_control input-group pleft pull-right no-margin hidden-xs">
	                                            <button type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-success btn-export-excel pull-right" name="excel">Xuất Excel</button>
	                                        </div>
                                            <div class="form-group fix_width_control input-group pleft pull-right">
                                                <input type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-default btn-search pull-right" value="Tìm kiếm"/>
                                            </div>
                                            

										</div>

										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											<div class="form-group fix_width_control input-group no-margin" style="width: 80%;">
												<p class="heading-result-history">
													Tổng số giao dịch: <span class="total-his"><?php echo $tab == 'paymentphone' ? $totalTrans : 0; ?></span><span class="hidden-sm hidden-xs">&nbsp;&nbsp;&nbsp;</span><span class="hidden-md hidden-lg"><br /></span><span class="total-fix-responsive"></span>Tổng tiền thanh toán: <span class="amount-his"><?php echo $tab == 'paymentphone' ? $totalAmount : 0; ?> vnđ</span>
													</p>
											</div>
	                                        
										</div>


									
								</div>
							
							
								<div class="table-responsive table_load_history">
									<table class="table table-bordered table-hover table-striped">
										<thead>
											<tr>
												<th>Mã giao dịch</th>
												<th>Số điện thoại nhận</th>
												<th>Mệnh giá (đ)</th>
												<th>Tiền chiết khấu (đ)</th>
												<th>Tiền thanh toán (đ)</th>
												<th>Thời gian yêu cầu</th>
												<th>Trạng thái</th>
											</tr>
										</thead>
										<tbody>
											<?php if(isset($tab) && $tab == "paymentphone"): ?>
												<?php if(isset($listTrans)): ?>


													<?php foreach($listTrans as $transaction): ?>
														<tr>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->target; ?></td>
															<td><?php echo number_format($transaction->amount); ?></td>
															<td><?php echo number_format($transaction->discountAmount); ?></td>
															<td><?php echo number_format($transaction->realMinus); ?></td>
															<td><?php echo isset($transaction->createdDate) ? date('d/m/Y H:i:s', strtotime($transaction->createdDate)) : ''; ?></td>
															<td>
																<?php 
																	if(isset($transaction->status))
																	{
																		switch($transaction->status)
																		{
																			case '00': echo 'Thành công';
																			break;
																			//case '01': echo 'Thất bại';
																			//break;
																			case '99': echo 'Chờ xử lý';
																			break;
																			default : echo 'Thất bại';
																			break;
																		}
																	}
																?>
															</td>
														</tr>
													<?php endforeach; ?>
												<?php endif; ?>
											<?php endif; ?>
										</tbody>
									</table>
									<div class="row">
										<div class="col-xs-12 pull-left">
											<div class="dataTables_paginate paging_bootstrap">
												<?php if(isset($tab) && $tab == "paymentphone"): ?>
													<?php if(!empty($paginationLinks)) echo $paginationLinks; ?>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
							<?php echo form_close(); ?>
						</div>
					    
						<div role="tabpanel" class="tab-pane <?php echo (isset($tab) && $tab == "topup" ) ? 'active' : ""; ?>" id="topup">
                            <span class="error"><?php echo validation_errors() ?></span>
							<?php echo form_open('/trans_history/topup', array('method' => 'post', 'role' => 'form', 'id' => 'frm_search_list')); ?>
								<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
								<input type="hidden" id="hidden_page" name="page" />
								<div class="row">
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											
											<div class="form-group fix_width_control form-fix-responsive fix_topup_res_input">
											<?php $arrStatus = array(array('val' => '-1', 'name' => 'Trạng thái'),
																	array('val' => '99', 'name' => 'Chờ xử lý'),
																	array('val' => '00', 'name' => 'Thành công'),
																	array('val' => '01', 'name' => 'Thất bại')); ?>
												<select name="status" class="form-input">
													<?php foreach($arrStatus as $status): ?>
														<option value="<?php echo $status['val']; ?>" <?php echo ($tab == "topup" && set_select('status', $status['val'], False)) ? set_select('status', $status['val'], False) : ''; ?>><?php echo $status['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="form-group fix_width_control input-group pright fix_topup_res_input">
												<input name="fdate" class="form-input has-datepicker" type="text" value="<?php echo ($tab == "topup" && set_value('fdate')) ? set_value('fdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control input-group w1">
												<hr>
											</div>
											<div class="form-group fix_width_control input-group pleft fix_topup_res_input">
												<input name="tdate" class="form-input has-datepicker" type="text" value="<?php echo ($tab == "topup" && set_value('tdate')) ? set_value('tdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control pleft w20">
												<input name="transId" class="form-input" type="text" value="<?php echo (isset($redirect_trans_id) && $tab == 'topup') ? $redirect_trans_id : ""; ?><?php echo($tab == "topup" && set_value('transId')) ? set_value('transId') : ''; ?>" placeholder="Mã giao dịch">
											</div>
											<div class="form-group fix_width_control pleft pull-right fix_topup_res_input">
												<input type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-default btn-search pull-right" value="Tìm kiếm"/>
											</div>
										</div>

										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											<div class="form-group fix_width_control input-group no-margin" style="width: 80%;">
												<p class="heading-result-history">
													Tổng số giao dịch: <span class="total-his"><?php echo $tab == 'topup' ? $totalTrans : 0; ?></span><span class="hidden-sm hidden-xs">&nbsp;&nbsp;&nbsp;</span><span class="hidden-md hidden-lg"><br /></span><span class="total-fix-responsive"></span>Tổng tiền thanh toán: <span class="amount-his"><?php echo $tab == 'topup' ? $totalAmount : 0; ?> vnđ</span>
													</p>
											</div>
	                                        
										</div>
									
								</div>
							
								<div class="table-responsive table_load_history">
									<table class="table table-bordered table-hover table-striped">
										<thead>
											<tr>
												<th>Mã giao dịch</th>
												<th>Mệnh giá (đ)</th>
												<th>Số lượng</th>
												<th>Tiền chiết khấu (đ)</th>
												<th>Tiền thanh toán (đ)</th>
												<th>Thời gian yêu cầu</th>
												<th>Trạng thái</th>
												<th><span class="hidden-sm hidden-xs">Thao tác</span></th>
											</tr>
										</thead>
										<tbody>
											<?php if(isset($tab) && $tab == "topup"): ?>
												<?php if(isset($listTrans)): ?>
													<?php foreach($listTrans as $transaction): ?>
														<tr>
															<td><?php echo $transaction->orgTransId; ?></td>
															<td><?php echo number_format($transaction->amount); ?></td>
															<td><?php echo $transaction->totalQuantity; ?></td>
															<td><?php echo number_format($transaction->totalDiscount); ?></td>
															<td><?php echo number_format($transaction->totalAmount); ?></td>
															<td><?php echo isset($transaction->createdDate) ? date('d/m/Y H:i:s', strtotime($transaction->createdDate)) : ''; ?></td>
															<td>
																<?php 
																if(isset($transaction->status))
																	{
																		switch($transaction->status)
																		{
																			case '00': echo 'Thành công';
																			break;
																			//case '01': echo 'Thất bại';
																			//break;
																			case '99': echo 'Chờ xử lý';
																			break;
																			default : echo 'Thất bại';
																			break;
																		}
																	}
																 ?>
															</td>
															<td><?php echo ($transaction->status=='00') ? '<a href="javascript:void(0);"" class="detail_topup" data-request-id="'.$transaction->orgTransId.'" data-fdate="'.$after_post['fdate'].'" data-tdate="'.$after_post['tdate'].'"><span class="hidden-sm hidden-xs">Xem chi tiết</span><i class="fa fa-eye hidden-lg hidden-md" aria-hidden="true"></i></a>' : ''; ?></td>
														</tr>
													<?php endforeach; ?>
												<?php endif; ?>
											<?php endif; ?>
										</tbody>
									</table>
									<div class="row">
										<div class="col-xs-12 pull-left">
											<div class="dataTables_paginate paging_bootstrap">
												<?php if(isset($tab) && $tab == "topup"): ?>
													<?php if(!empty($paginationLinks)) echo $paginationLinks; ?>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
							<?php echo form_close(); ?>
						</div>
						<div class="modal fade" id="modal_topup" role="dialog" style="color: #333;">
						    <div class="modal-dialog modal-md">
						    
						      <!-- Modal content-->
						      <div class="modal-content" style="border-radius: 0px;margin-top: 150px;width: 80%;">
						        <div class="modal-header">
						          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						          <h4 class="modal-title">Xác thực giao dịch</h4>
						          <input type="hidden" class="form-control" id="transIdOtpHistory" value="" />
						          <input type="hidden" class="form-control" id="requestIdHistory" value="" />
						          <input type="hidden" class="form-control" id="fDayHistory" value="" />
						          <input type="hidden" class="form-control" id="tDaypHistory" value="" />
						          <input type="hidden" class="form-control" id="checkOptionHistory" value="<?php echo $this->session_memcached->userdata['info_user']['security_method'];?>" />
						        </div>
						        <div class="modal-body">

						        </div>
						        <div class="modal-footer">
						          <div class="col-md-12 text-right">
						          	<button type="button" class="btn btn-success sendOtpViewDetail" style="font-family:'Roboto Regular';">Xác nhận</button>
						          <button type="button" class="btn btn-default close-history-trans" style="font-family:'Roboto Regular';" data-dismiss="modal">Hủy bỏ</button>
						          </div>
						          
						        </div>
						      </div>
						      
						    </div>
						  </div>
						 <div class="modal fade" id="modal_topup_detail" role="dialog" style="color: #333;">
						    <div class="modal-dialog modal-lg">
						    
						      <!-- Modal content-->
						      <div class="modal-content" style="border-radius: 0px;background: #3d3d3d;color: #ccc;">
						        <div class="modal-header">
						          <h4 class="modal-title">Chi tiết giao dịch</h4>
						          <input type="hidden" class="form-control" id="requestIdHistoryDetail" value="" />
						          <input type="hidden" class="form-control" id="fDayHistoryDetail" value="" />
						          <input type="hidden" class="form-control" id="tDaypHistoryDetail" value="" />
						        </div>
						        <div class="modal-body">

						        </div>
						        <div class="modal-footer">
						          <div class="form-group fix_width_control input-group pleft pull-right no-margin hidden-xs">
                                   <button type="button" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-success btn-export-excel pull-right" style="border-radius: 4px;">Xuất Excel</button>
                                </div>
						          <button type="button" class="btn btn-default close-history-trans" style="font-family:'Roboto Regular';" data-dismiss="modal">Hủy bỏ</button>
						        </div>
						      </div>
						      
						    </div>
						  </div>
					    
						<div role="tabpanel" class="tab-pane <?php echo (isset($tab) && $tab == "paymentgame" ) ? 'active' : ""; ?>" id="paymentgame">
                            <span class="error"><?php echo validation_errors() ?></span>
							<?php echo form_open('/trans_history/paymentgame', array('method' => 'post', 'role' => 'form', 'id' => 'frm_search_list')); ?>
								<input type="hidden" id="hidden_page" name="page" />
								<div class="row">
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											<div class="form-group fix_width_control">
											<?php $arrStatus = array(array('val' => '-1', 'name' => 'Trạng thái'),
																	array('val' => '99', 'name' => 'Chờ xử lý'),
																	array('val' => '00', 'name' => 'Thành công'),
																	array('val' => '01', 'name' => 'Thất bại')); ?>
												<select name="status" class="form-input">
													<?php foreach($arrStatus as $status): ?>
														<option value="<?php echo $status['val']; ?>" <?php echo  (isset($tab) && $tab == "paymentgame" ) ? set_select('status', $status['val'], False) : ''; ?>><?php echo $status['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="form-group fix_width_control fix-right-control fix-game-provider">
												<select id="providerCDV" name="provider_code" class="form-input">
													<option value="">Chọn nhà cung cấp</option>
													<?php if(isset($listTelco)): ?>
														<?php foreach($listTelco as $telco): ?>
															<?php if($telco->type == '3'): ?>
																<option value="<?php echo $telco->providerCode; ?>" <?php echo  (isset($tab) && $tab == "paymentgame" ) ?set_select('provider_code', $telco->providerCode, False) : ''; ?>><?php echo $telco->providerName; ?></option>
															<?php endif; ?>
														<?php endforeach; ?>
													<?php endif; ?>
												</select>
											</div>
											<div class="form-group fix_width_control input-group">
												<input name="fdate" class="form-input has-datepicker" type="text" value="<?php echo ($tab == "paymentgame" && set_value('fdate')) ? set_value('fdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control input-group w1">
												<hr>
											</div>
											<div class="form-group fix_width_control input-group pleft">
												<input name="tdate" class="form-input has-datepicker" type="text" value="<?php echo ($tab == "paymentgame" && set_value('tdate')) ? set_value('tdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control w20 pleft">
												<input name="transId" class="form-input" type="text" value="<?php echo (isset($redirect_trans_id) && $tab == 'paymentgame') ? $redirect_trans_id : ""; ?><?php echo ($tab == "paymentgame" && set_value('transId')) ? set_value('transId') : ''; ?>" placeholder="Mã giao dịch">
											</div>
											<div class="form-group fix_width_control input-group w16 pleft pull-right">
												<input type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-default btn-search pull-right" value="Tìm kiếm"/>
											</div>
                                            
										</div>

										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											<div class="form-group fix_width_control input-group no-margin" style="width: 80%;">
											<p class="heading-result-history">
												Tổng số giao dịch: <span class="total-his"><?php echo $tab == 'paymentgame' ? $totalTrans : 0; ?></span><span class="hidden-sm hidden-xs">&nbsp;&nbsp;&nbsp;</span><span class="hidden-md hidden-lg"><br /></span><span class="total-fix-responsive"></span>Tổng tiền thanh toán: <span class="amount-his"><?php echo $tab == 'paymentgame' ? $totalAmount : 0; ?> vnđ</span>
												</p>
											</div>
                                            <div class="form-group fix_width_control input-group w16 pleft pull-right no-margin hidden-xs">
                                                <button type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-success btn-export-excel pull-right" name="excel">Xuất Excel</button>
                                            </div>
										</div>

								</div>
							
							
								<div class="table-responsive table_load_history">
									<table class="table table-bordered table-hover table-striped">
										<thead>
											<tr>
												<th>Mã giao dịch</th>
												<th>Tài khoản game</th>
												<th>Loại thẻ</th>
												<th>Mệnh giá (đ)</th>
												<th>Tiền chiết khấu (đ)</th>
												<th>Tiền thanh toán (đ)</th>
												<th>Thời gian yêu cầu</th>
												<th>Trạng thái</th>
											</tr>
										</thead>
										<tbody>
											<?php if(isset($tab) && $tab == "paymentgame"): ?>
												<?php if(isset($listTrans)): ?>
													<?php foreach($listTrans as $transaction): ?>
														<tr>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->target; ?></td>
															<td><?php echo $transaction->providerCode; ?></td>
															<td><?php echo number_format($transaction->amount); ?></td>
															<td><?php echo number_format($transaction->discountAmount); ?></td>
															<td><?php echo number_format($transaction->realMinus); ?></td>
															<td><?php echo isset($transaction->createdDate) ? date('d/m/Y H:i:s', strtotime($transaction->createdDate)) : ''; ?></td>
															<td>
																<?php 
																	if(isset($transaction->status))
																	{
																		switch($transaction->status)
																		{
																			case '00': echo 'Thành công';
																			break;
																			//case '01': echo 'Thất bại';
																			//break;
																			case '99': echo 'Chờ xử lý';
																			break;
																			default : echo 'Thất bại';
																			break;
																		}
																	}
																?>
															</td>
														</tr>
													<?php endforeach; ?>
												<?php endif; ?>
											<?php endif; ?>
										</tbody>
									</table>
									<div class="row">
										<div class="col-xs-12 pull-left">
											<div class="dataTables_paginate paging_bootstrap">
												<?php if(isset($tab) && $tab == "paymentgame"): ?>
													<?php if(!empty($paginationLinks)) echo $paginationLinks; ?>
												<?php endif ?>
											</div>
										</div>
									</div>
								</div>
							<?php echo form_close(); ?>
						</div>
					    
						<div role="tabpanel" class="tab-pane <?php echo (isset($tab) && $tab == "paymentbills" ) ? 'active' : ""; ?>" id="paymentbills">
                            <span class="error"><?php echo validation_errors() ?></span>
							<?php echo form_open('/trans_history/paymentbills', array('method' => 'post', 'role' => 'form', 'id' => 'frm_search_list')); ?>
								<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
								<input type="hidden" id="hidden_page" name="page" />
								<div class="row">
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
                                            <div class="form-group fix_width_control w10">
                                                <?php
                                                $paymentTypes = array(
                                                    '0' => 'Loại thanh toán',
                                                    '12' => 'EC-Merchant',
                                                    '13' => 'Thanh toán hóa đơn',
                                                );
                                                ?>
                                                <select id="payment_type" name="payment_type" class="form-input">
                                                    <?php foreach($paymentTypes as $key => $value): ?>
                                                        <option value="<?php echo $key; ?>" <?php echo $tab == "paymentbills" ? set_select('payment_type', $key, False) : ""; ?>><?php echo $value; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <script type="text/javascript">
                                                    $(function() {
                                                        if ($('#payment_type').val() == 1 || $('#payment_type').val() == 0) {
                                                            $('#provider_code').val("-1").change();
                                                            $('#provider_code').prop('disabled', true);
                                                        }
                                                        $('#payment_type').change(function() {
                                                            if (this.value == 1 || this.value == 0) {
                                                                $('#provider_code').val("-1").change();
                                                                $('#provider_code').prop('disabled', true);
                                                            } else {
                                                                $('#provider_code').prop('disabled', false);
                                                            }
                                                        });
                                                    });
                                                </script>
                                            </div>
											<div class="form-group fix_width_control w10 fix-right-control">
												<select id="provider_code" name="provider_code" class="form-input">
													<option value="-1">Nhà cung cấp</option>
													<?php if(isset($listBank)): ?>
														<?php foreach($listBank as $bank): ?>
															<?php if($bank->type == '4'): ?>
																<option value="<?php echo $bank->providerCode; ?>" <?php echo $tab == "paymentbills" ? set_select('provider_code', $bank->providerCode, False) : ""; ?>><?php echo $bank->providerName; ?></option>
															<?php endif; ?>
														<?php endforeach; ?>
													<?php endif; ?>
												</select>
											</div>
											<div class="form-group fix_width_control w10 respon-pay form-fix-responsive">
											<?php
                                            $arrStatus = array(
                                                array('val' => '', 'name' => 'Trạng thái'),
                                                array('val' => '99', 'name' => 'Chờ xử lý'),
                                                array('val' => '00', 'name' => 'Thành công'),
                                                array('val' => '97', 'name' => 'Bị hủy'),
                                                array('val' => '01', 'name' => 'Thất bại')
                                            );
											?>
												<select name="status" class="form-input">
													<?php foreach($arrStatus as $status): ?>
														<option value="<?php echo $status['val']; ?>" <?php echo $tab == "paymentbills" ? set_select('status', $status['val'], False) : ""; ?>><?php echo $status['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="form-group fix_width_control input-group pright">
												<input name="fdate" class="form-input has-datepicker" type="text" value="<?php echo ($tab == "paymentbills" && set_value('fdate')) ? set_value('fdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control input-group w1">
												<hr>
											</div>
											<div class="form-group fix_width_control input-group pleft">
												<input name="tdate" class="form-input has-datepicker" type="text" value="<?php echo ($tab == "paymentbills" && set_value('tdate')) ? set_value('tdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control w16 pleft fix-respon-epurse">
												<input name="epurse_trans_id" class="form-input" type="text" value="<?php echo (isset($redirect_trans_id) && $tab == 'paymentbills') ? $redirect_trans_id : ""; ?><?php echo $tab == "paymentbills" ? set_value('epurse_trans_id') : "" ?>" placeholder="Mã giao dịch EPAY">
											</div>
                                            <div class="form-group fix_width_control w16 pleft marginTopE">
                                                <input name="transId" class="form-input id_payment_bills" type="text" value="<?php echo $tab == "paymentbills" ? set_value('transId') : "" ?>" placeholder="<?php
                                                if (!isset($after_post['payment_type']) || $after_post['payment_type']!=1) {
                                                	echo 'Mã hợp đồng';
                                                }else{
                                                	echo 'Mã giao dịch đối tác';
                                                }
                                                 ?>">
                                                
                                            </div>
											<div class="form-group fix_width_control input-group pleft pull-right w10 marginTopE">
												<input type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-default btn-search pull-right" value="Tìm kiếm"/>
											</div>
										</div>
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
											<div class="form-group fix_width_control input-group no-margin" style="width: 80%;">
												<p class="heading-result-history">
												Tổng số giao dịch: <span class="total-his"><?php echo $tab == "paymentbills" ? $totalTrans : 0; ?></span><span class="hidden-sm hidden-xs">&nbsp;&nbsp;&nbsp;</span><span class="hidden-md hidden-lg"><br /></span><span class="total-fix-responsive">Tổng tiền giao dịch thành công: <span class="amount-his"><?php echo $tab == "paymentbills" ? $totalAmount : 0; ?> vnđ</span>
												</p>
											</div>
                                            <div class="form-group fix_width_control input-group pleft pull-right no-margin w10 hidden-xs">
                                                <button type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-success btn-export-excel pull-right" name="excel">Xuất Excel</button>
                                            </div>
										</div>
									
								</div>

								<div class="table-responsive table_load_history">
									<table class="table table-bordered table-hover table-striped">
										<thead>
											<tr>
												<th>Mã giao dịch EPAY</th>
												<th class="hidden-sm hidden-xs">Mã hợp đồng</th>
												<th>Mã giao dịch đối tác</th>
												<th class="hidden-sm hidden-xs">Nhà cung cấp</th>
												<th class="hidden-sm hidden-xs">Tiền thanh toán (đ)</th>
												<th class="hidden-sm hidden-xs">Loại thanh toán</th>
												<th class="hidden-sm hidden-xs">Ngày giao dịch</th>
												<th>Trạng thái</th>
											</tr>
										</thead>
										<tbody>
											<?php if(isset($tab) && $tab == "paymentbills"): ?>
												<?php if(isset($listTrans)): ?>
													<?php foreach($listTrans as $transaction): ?>
														<tr>
															<td><?php echo isset($transaction->requestId) ? $transaction->requestId : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php echo isset($transaction->contractNo) ? $transaction->contractNo : ""; ?></td>
															<td><?php echo isset($transaction->coreTransid) ? $transaction->coreTransid : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php echo isset($transaction->providerCode) ? $transaction->providerCode : ""; ?></td>
															<td class="hidden-sm hidden-xs"><?php echo isset($transaction->amount) ? number_format($transaction->amount) : ""; ?></td>
															<td class="hidden-sm hidden-xs">
                                                                <?php
                                                                	if (isset($transaction->transType) && $transaction->transType=='13') {
	                                                                    echo "Thanh toán hóa đơn";
	                                                                }elseif (isset($transaction->transType) && $transaction->transType=='12') {
	                                                                    echo "EC-Merchant";
	                                                                }elseif (isset($transaction->transType) && $transaction->transType=='10') {
	                                                                    echo "Nạp game";
	                                                                }
	                                                                elseif (isset($transaction->transType) && $transaction->transType=='11') {
	                                                                    echo "Mua mã thẻ";
	                                                                }
                                                                ?>
                                                            </td>
															<td class="hidden-sm hidden-xs"><?php echo isset($transaction->createdDate) ? date('d/m/Y H:i:s', strtotime($transaction->createdDate)) : ""; ?></td>
															<td>
                                                                <?php
                                                                switch ($transaction->status) {
                                                                    case "00":
                                                                        echo "Thành công";
                                                                        break;
                                                                    case "99":
                                                                        echo "Chờ xử lý";
                                                                        break;
                                                                    case "97":
                                                                        echo "Bị hủy";
                                                                        break;
                                                                    default:
                                                                        echo "Thất bại";
                                                                        break;
                                                                }
                                                                ?>
                                                            </td>
														</tr>
													<?php endforeach; ?>
												<?php endif; ?>
											<?php endif; ?>
										</tbody>
									</table>
									<div class="row">
										<div class="col-xs-12 pull-left">
											<div class="dataTables_paginate paging_bootstrap">
												<?php if(isset($tab) && $tab == "paymentbills"): ?>
													<?php if(!empty($paginationLinks)) echo $paginationLinks; ?>
												<?php endif; ?>
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

<script>
	$(document).ready(function(){
		$('body').on('change','#paymentbills #payment_type',function(){
			if ($(this).val()==1) {
				$(this).parents('#paymentbills').find('.id_payment_bills').attr('placeholder','Mã giao dịch đối tác');
			}else{
				$(this).parents('#paymentbills').find('.id_payment_bills').attr('placeholder','Mã hợp đồng');
			}
		});
		$('body').on('click','.detail_transfer',function(){
			var csrf = $('input[name="csrf_megav_name"]').val();

			var req_id = $(this).attr('data-request-id'); 
			var fdate = $(this).attr('data-fdate'); 
			var tdate = $(this).attr('data-tdate'); 
			var transfer_type = $(this).attr('data-transfer-type'); 
			$.ajax({
				url: '/trans_history/transfer_detail',
				type: 'POST',
				dataType: 'json',
				data: {req_id: req_id,fdate:fdate,tdate:tdate,transfer_type:transfer_type,csrf_megav_name:csrf},
			})
			.done(function(data) {
				$('#modal_transfer .modal-title').html('Chi tiết mã giao dịch: '+req_id);
				if (data.status==true) {
					
					$('#modal_transfer .modal-body').html('');
					$('#modal_transfer .modal-body').html(data.html);
				}
			});

		});
		
		$('body').on('click','#modal_topup .resendOtp',function(){
			var csrf = $('input[name="csrf_megav_name"]').val();
			$.ajax({
				url: '/trans_history/senOTPHistoryCheck',
				type: 'POST',
				dataType: 'json',
				data: {check: 1,csrf_megav_name:csrf},
			})
			.done(function(data) {
				if (data.status==true) {
					alert(data.mess);
					$('#transIdOtpHistory').val(data.transId);
				}
			});

		});
		$('body').on('click','.detail_topup',function(){
			var csrf = $('input[name="csrf_megav_name"]').val();
			var req_id = $(this).attr('data-request-id'); 
			var fdate = $(this).attr('data-fdate'); 
			var tdate = $(this).attr('data-tdate'); 
			$.ajax({
				url: '/trans_history/senOTPHistoryCheck',
				type: 'POST',
				dataType: 'json',
				data: {check: 1,req_id: req_id,fdate:fdate,tdate:tdate,csrf_megav_name:csrf},
			})
			.done(function(data) {
				if (data.status==1) {
					$('#transIdOtpHistory').val(data.transId);
					$('#requestIdHistory').val(req_id);
					$('#fDayHistory').val(fdate);
					$('#tDayHistory').val(tdate);
					var xhtml ='';
					if( data.otp_pass==1){
						xhtml += '<div class="form-group">'+
							      '<div class="col-md-12 mess_otp mess_top">'+
							        'Hệ thống đã gửi OTP tới số điện thoại '+data.phone+'.<br/>Không nhận được <a class="resendOtp" href="javascript:void(0)" data-request-id="'+req_id+'">Click gửi lại</a>'+
							      '</div>'+
							    '</div>'+
								'<div class="form-group">'+
							      '<label class="col-md-6" for="trand_name">Nhập mã OTP:</label>'+
							      '<div class="col-md-6">'+
							        '<input type="password" class="form-control bill_info_style" id="otp_lv" placeholder="Nhập mã xác thực" autocomplete="off">'+
							      	'<span class="error"></span>' + 
							      '</div>'+
							    '</div>';
					}else{
						xhtml += '<div class="form-group">'+
								      '<label class="col-md-6" for="trand_name">Nhập mật khẩu cấp 2:</label>'+
									  '<div class="col-md-6">'+
									    '<input type="password" class="form-control bill_info_style" id="pass_lv" placeholder="Mật khẩu cấp 2" autocomplete="off">'+
									  	'<span class="error"></span>' + 
									  '</div>'+
								    '</div>'+
								    '<div class="form-group">'+
								       '<label class="col-md-6" for="trand_name"></label>'+
								      '<div class="col-md-6 mess_otp">'+
								        '<a class="resendMk" href="/reset_pass_lv2">Quên mật khẩu cấp 2?</a>'+
								      '</div>'+
								    '</div>';
					}
					$('#modal_topup').modal('show'); 
					$('#modal_topup .modal-body').html('');
					$('#modal_topup .modal-body').fadeOut(100, function(){
			            $('#modal_topup .modal-body').html(xhtml).fadeIn();
			        });
				}else if(data.status==3){
					// hiện chi tiết giao dịch
					$('#modal_topup_detail').modal('show'); 
					$('#modal_topup_detail .modal-title').html('Chi tiết giao dịch '+ req_id);
					$('#modal_topup_detail .modal-body').html('');
					$('#modal_topup_detail .modal-body').fadeOut(100, function(){
			            $('#modal_topup_detail .modal-body').html(data.html).fadeIn();
			        });
				}
			});


		});
		
		$('body').on('click','.sendOtpViewDetail',function(){
			var csrf = $('input[name="csrf_megav_name"]').val();
			var check = $(this).parents('#modal_topup').find('#checkOptionHistory').val();
			var status = true;
			if (check == 1) {
	    		var otp_lv = $('#modal_topup #otp_lv').val();
			    if (otp_lv == '' || otp_lv == null || otp_lv == 'undefined') {
		    			$('#modal_topup #otp_lv').parents('#modal_topup').find('.error').text('Bạn phải nhập OTP.');
		    			status = false;
		    	}else{
		    		$('#modal_topup #otp_lv').parents('#modal_topup').find('.error').text('');
		    	}
			}
	    	if (check == 2) {
	    		var pass_lv = $('#modal_topup #pass_lv').val();
	    		if (pass_lv == '' || pass_lv == null || pass_lv == 'undefined') {
		    			$('#modal_topup #pass_lv').parents('#modal_topup').find('.error').text('Bạn phải nhập mật khẩu cấp 2.');
		    			status = false;		    		
		    	}else{
		    		$('#modal_topup #pass_lv').parents('#modal_topup').find('.error').text('');
		    	}
	    	}
	    	var transId = $('#transIdOtpHistory').val(); 
	    	var req_id  = $('#requestIdHistory').val();
			var fdate   = $('#fDayHistory').val();
			var tdate   = $('#tDayHistory').val();
	    	var data = {
	    		'transId': transId,
    			'req_id' : req_id,
		    	'fdate' : fdate,
		    	'tdate' : tdate
    		}
    		if (check==1) {
    			data.otp_lv = otp_lv;
    		}
    		if(check==2){
    			data.pass_lv = pass_lv;
    		}
    		if (status==true) {
				$.ajax({
					url: '/trans_history/sendConfirmOtpViewDetail',
					type: 'POST',
					dataType: 'json',
					data: {data:data,csrf_megav_name:csrf},
				})
				.done(function(data) {
					if (data.status==false) {
						if (check==1) {
							$('#modal_topup #otp_lv').parents('#modal_topup').find('.error').text(data.mess);
						}
						if(check==2){
			    			$('#modal_topup #pass_lv').parents('#modal_topup').find('.error').text(data.mess);
			    		}
					}else{
						$('#modal_topup').modal('hide'); 
						// hiện chi tiết giao dịch
						$('#modal_topup_detail').modal('show'); 
						/*$('#modal_topup_detail #requestIdHistoryDetail').val(req_id);
			            $('#modal_topup_detail #fDayHistoryDetail').val(fdate);
			            $('#modal_topup_detail #tDaypHistoryDetail').val(tdate);*/
						$('#modal_topup_detail .modal-title').html('Chi tiết giao dịch '+ req_id);
						$('#modal_topup_detail .modal-body').html('');
						$('#modal_topup_detail .modal-body').fadeOut(100, function(){
				            $('#modal_topup_detail .modal-body').html(data.html).fadeIn();
				        });
					}
				});
			}


		});
		$('body').on('click','.view_continute',function(){
			var csrf = $('input[name="csrf_megav_name"]').val();
			var _this = $(this);
			var transId = _this.attr('data-trans-id');
			var fdate = _this.attr('ta-fdate');
			var tdate = _this.attr('data-tdate');
			var numbPage = _this.attr('data-numb-page');
			var pageSize = _this.attr('data-page-size');
			
			$.ajax({
				url: '/trans_history/pagi_topup_detail',
				type: 'POST',
				dataType: 'json',
				data: {transId:transId,fdate:fdate,tdate:tdate,numbPage:numbPage,pageSize:pageSize,csrf_megav_name:csrf},
			})
			.done(function(data) {
				if (data.status==true) {
					if (!$('#modal_topup_detail table tr').hasClass('done_data')) {
						$('#modal_topup_detail .modal-body table').fadeOut(100, function(){
				            $('#modal_topup_detail .modal-body table').append(data.html).fadeIn();
				        });
					}
					if (data.none == true) {
						_this.css('display','none');
					}else{
						 _this.attr('data-numb-page',parseInt(numbPage)+1);
					}
			       
				}
			});
		});

		$('body').on('click','#modal_topup_detail .btn-export-excel',function(){
			var _this = $(this);
			//_this.prop('disabled', true);
			var transId = $('#modal_topup_detail .pagi-data').attr('data-trans-id');
			var fdate = $('#modal_topup_detail .pagi-data').attr('data-fdate');
			var tdate = $('#modal_topup_detail .pagi-data').attr('data-tdate');
			window.open('/trans_history/export_topup_detail/'+transId+'/'+fdate.split("/").join("-")+'/'+tdate.split("/").join("-"),'_blank');

			/*var transId = $('#modal_topup_detail .view_continute').attr('data-trans-id');
			var fdate = $('#modal_topup_detail .view_continute').attr('ta-fdate');
			var tdate = $('#modal_topup_detail .view_continute').attr('data-tdate');
			
			$.ajax({
				url: '/trans_history/export_topup_detail',
				type: 'POST',
				dataType: 'json',
				data: {transId:transId,fdate:fdate,tdate:tdate},
			})
			.done(function(data) {
				_this.prop('disabled', false);
			});*/
		});
			


		
	});
function toTimestamp(strDate){
 var datum = Date.parse(strDate);
 return datum/1000;
}
</script>


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