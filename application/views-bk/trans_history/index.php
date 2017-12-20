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

					<!-- Nav tabs -->
					  <ul class="nav nav-tabs tab_trans_history" role="tablist">
					    <li role="presentation" class="<?php echo (isset($tab) && $tab == "payment" ) ? 'active' : ""; ?>"><a href="javascript:void(0)" data-target="#payment" aria-controls="payment" role="tab" data-toggle="tab">Nạp tiền</a></li>
					    <li role="presentation" class="<?php echo (isset($tab) && $tab == "transfer" ) ? 'active' : ""; ?>"><a href="javascript:void(0)" data-target="#transfer" aria-controls="transfer" role="tab" data-toggle="tab">Chuyển tiền</a></li>
					    <li role="presentation" class="<?php echo (isset($tab) && $tab == "withdraw" ) ? 'active' : ""; ?>"><a href="javascript:void(0)" data-target="#withdraw" aria-controls="withdraw" role="tab" data-toggle="tab">Rút tiền</a></li>
					    <li role="presentation" class="disabled <?php echo (isset($tab) && $tab == "paymentphone" ) ? 'active' : ""; ?>"><a href="javacsript:void(0)" data-target="#paymentphone">Nạp điện thoại</a></li>
					    <li role="presentation" class="disabled <?php echo (isset($tab) && $tab == "topup" ) ? 'active' : ""; ?>"><a href="javacsript:void(0)" data-target="#topup">Mua mã thẻ</a></li>
					    <li role="presentation" class="disabled <?php echo (isset($tab) && $tab == "paymentgame" ) ? 'active' : ""; ?>"><a href="javacsript:void(0)" data-target="#paymentgame">Nạp Game</a></li>
					    <li role="presentation" class="disabled <?php echo (isset($tab) && $tab == "paymentbills" ) ? 'active' : ""; ?>"><a href="javacsript:void(0)" data-target="#paymentbills">Thanh toán hóa đơn</a></li>
					  </ul>

	
					<div class="panel panel-default">

					<div class="panel-body">
					  

					  <!-- Tab panes -->
					  <div class="tab-content">
					    <div role="tabpanel" class="tab-pane <?php echo (isset($tab) && $tab == "payment" ) ? 'active' : ""; ?>" id="payment">
							<span class="error"><?php echo validation_errors() ?></span>
                            <?php echo form_open('/trans_history/payment', array('method' => 'post', 'role' => 'form', 'id' => 'frm_search_list')); ?>
								<input type="hidden" id="hidden_page" name="page" />
								<div class="row">
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											
										
											<div class="form-group fix_width_control">
												<select name="provider_code" class="form-control">
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
										
											<div class="form-group fix_width_control">
											<?php $arrStatus = array(array('val' => '-1', 'name' => 'Trạng thái'),
																	array('val' => '99', 'name' => 'Chờ xử lý'),
																	array('val' => '00', 'name' => 'Thành công'),
																	array('val' => '01', 'name' => 'Thất bại')); ?>
												<select name="status" class="form-control">
													<?php foreach($arrStatus as $status): ?>
														<option value="<?php echo $status['val']; ?>" <?php echo $tab == "payment" ? set_select('status', $status['val'], False) : ''; ?>><?php echo $status['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>


											<div class="form-group fix_width_control">
											<?php $arrHT = array(array('val' => '-1', 'name' => 'Hình thức'),
																	array('val' => '1', 'name' => 'Nạp tiền online ngân hàng'),
																	array('val' => '2', 'name' => 'Nạp tiền online liên kết'),
																	array('val' => '3', 'name' => 'Nạp tiền offline')); ?>
												<select name="hinh_thuc" class="form-control">
													<?php foreach($arrHT as $name): ?>
														<option value="<?php echo $name['val']; ?>"<?php echo ($tab == 'payment' && $after_post['hinh_thuc']==$name['val']) ? 'selected="selected"' : ''; ?>><?php echo $name['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
									
								
										<div class="form-group fix_width_control input-group">
											<input name="fdate" class="form-control has-datepicker" type="text" value="<?php echo ($tab == 'payment' && set_value('fdate')) ? set_value('fdate') : date('d/m/Y'); ?>">
											<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
										</div>
										<div class="form-group fix_width_control input-group w1">
											<hr>
										</div>
										<div class="form-group fix_width_control input-group pleft">
											<input name="tdate" class="form-control has-datepicker" type="text" value="<?php echo ($tab == 'payment' && set_value('tdate')) ? set_value('tdate') : date('d/m/Y'); ?>">
											<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
										</div>

										<div class="form-group fix_width_control pleft w20">
												<input name="transId" class="form-control" type="text" value="<?php echo $tab == 'payment' ? set_value('transId') : '' ?>" placeholder="Mã giao dịch">
											</div>

										<div class="form-group fix_width_control pleft pull-right">
											<input type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-default btn-search pull-right" value="Tìm kiếm"/>
										</div>

									</div>
									<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
										<div class="form-group fix_width_control no-margin" style="width: 80%;">
											<p class="heading-result-history">
												Tổng số giao dịch: <span class="total-his"><?php echo $tab == 'payment' ? $totalTrans : 0; ?></span><span style="display:inline-block;width:45px;"></span>Tổng tiền thực nhận: <span class="amount-his"><?php echo $tab == 'payment' ? $totalAmount : 0; ?> vnđ</span>
												</p>
										</div>
                                        <div class="form-group fix_width_control pleft pull-right no-margin">
                                            <button type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-success btn-export-excel pull-right" name="excel">Xuất Excel</button>
                                        </div>
									</div>
								</div>
							
								<div class="table-responsive table_load_history">
									<table class="table table-bordered table-hover table-striped">
										<thead>
											<tr>
												<th>Mã giao dịch</th>
												<th>Ngân hàng</th>
												<th>Số tiền (đ)</th>
												<th style="width: 130px;">Phí cố định theo giao dịch (đ)</th>
												<th>Phí theo tỉ lệ (đ)</th>
												<th>Tổng phí giao dịch (đ)</th>
												<th>Thực nhận (đ)</th>
												<th>Thời gian yêu cầu</th>
												<th>Trạng thái</th>
											</tr>
										</thead>
										<tbody>
											<?php if(isset($tab) && $tab == "payment"): ?>
												<?php if(isset($listTrans)): ?>
													<?php foreach($listTrans as $transaction): ?>
														<tr>
															<td><?php echo (isset($transaction->requestId)) ? $transaction->requestId : ""; ?></td>
															<td><?php echo (isset($transaction->bankName)) ? $transaction->bankName : ""; ?></td>
															<td><?php echo (isset($transaction->amount)) ? number_format($transaction->amount) : ""; ?></td>
															<td><?php echo (isset($transaction->fixFee)) ? number_format($transaction->fixFee) : ""; ?></td>
															<td><?php echo (isset($transaction->rateFee)) ? $transaction->rateFee : ""; ?></td>
															<td><?php echo (isset($transaction->feeAmount)) ? number_format($transaction->feeAmount) : ""; ?></td>
															<td><?php echo (isset($transaction->realReceive)) ? number_format($transaction->realReceive) : ""; ?></td>
															<td><?php echo (isset($transaction->timeCreate)) ? date('d/m/Y H:i:s', strtotime($transaction->timeCreate)) : ""; ?></td>
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
										<div class="col-xs-6 pull-right">
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
								<input type="hidden" id="hidden_page" name="page" />
								<div class="row">
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											
											
											<div class="form-group fix_width_control w16">
											<?php $arrTransferType = array(
																		array('val' => '-1', 'name' => 'Hình thức'),
																		array('val' => TRANSFER_TO_USER , 'name' => 'Chuyển tiền ví nội bộ'),
																		array('val' => TRANSFER_TO_SERVICES, 'name' => 'Chuyển tiền ví dịch vụ')
																); 
											?>
												

												<select name="transferType" class="form-control">
													<?php foreach($arrTransferType as $star): ?>
														<option value="<?php echo $star['val']; ?>" <?php echo ($tab == 'transfer' && $after_post['transferType']==$star['val']) ? 'selected="selected"' : ''; ?>><?php echo $star['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>

											<div class="form-group fix_width_control w16">
											<?php $arrStatus = array(array('val' => '-1', 'name' => 'Trạng thái'),
																	array('val' => '99', 'name' => 'Chờ xử lý'),
																	array('val' => '00', 'name' => 'Thành công'),
																	array('val' => '01', 'name' => 'Thất bại')); ?>
												<select name="status" class="form-control">
													<?php foreach($arrStatus as $status): ?>
														<option value="<?php echo $status['val']; ?>" <?php echo $tab == 'transfer' ? set_select('status', $status['val'], False) : ''; ?>><?php echo $status['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="form-group fix_width_control input-group">
												<input name="fdate" class="form-control has-datepicker" type="text" value="<?php echo ($tab == 'transfer' && set_value('fdate')) ? set_value('fdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control input-group w1">
												<hr>
											</div>
											<div class="form-group fix_width_control input-group pleft">
												<input name="tdate" class="form-control has-datepicker" type="text" value="<?php echo ($tab == 'transfer' && set_value('tdate')) ? set_value('tdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control w20 pleft">
												<input name="transId" class="form-control" type="text" value="<?php echo $tab == 'transfer' ? set_value('transId'): '' ?>" placeholder="Mã giao dịch">
											</div>
											<div class="form-group fix_width_control input-group w16 pleft pull-right">
												<input type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-default btn-search pull-right" value="Tìm kiếm"/>
											</div>
										</div>
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											<div class="form-group fix_width_control input-group no-margin" style="width: 80%;">
											<p class="heading-result-history">
												Tổng số giao dịch: <span class="total-his"><?php echo $tab == 'transfer' ? $totalTrans : 0; ?></span><span style="display:inline-block;width:45px;"></span>Tổng tiền thực nhận: <span class="amount-his"><?php echo $tab == 'transfer' ? $totalAmount : 0; ?> vnđ</span>
												</p>
											</div>
                                            <div class="form-group fix_width_control input-group w16 pleft pull-right no-margin">
                                                <button type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-success btn-export-excel pull-right" name="excel">Xuất Excel</button>
                                            </div>
										</div>
									
								</div>
							
							
								<div class="table-responsive table_load_history">
									<table class="table table-bordered table-hover table-striped">
										<thead>
											<tr>
												<th>Mã giao dịch</th>
												<th>Hình thức</th>
												<th>Số tiền chuyển (đ)</th>
												<th>Tài khoản chuyển</th>
												<th>Tài khoản nhận</th>
												<th>Thời gian chuyển</th>
												<th>Trạng thái</th>
												<th>Thao tác</th>
											</tr>
										</thead>
										<tbody>
											<?php if(isset($tab) && $tab == "transfer"): ?>
												<?php if(isset($listTrans)): ?>
													<?php foreach($listTrans as $transaction): ?>
														<tr>
															<td><?php echo (isset($transaction->transId)) ? $transaction->transId : ""; ?></td>
															<td>
																<?php 
                                                                switch($transaction->transferType) {
                                                                    case TRANSFER_TO_USER:
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
															<td><?php echo (isset($transaction->amount)) ? number_format($transaction->amount) : ""; ?></td>
															<td><?php echo (isset($transaction->sendUserName)) ? $transaction->sendUserName : ""; ?></td>
															<td><?php echo (isset($transaction->receiveUserName)) ? $transaction->receiveUserName : ""; ?></td>
															<td><?php echo (isset($transaction->createdDate)) ? date('d/m/Y H:i:s', strtotime($transaction->createdDate)) : ""; ?></td>
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
															<td><?php echo "<a  data-toggle='modal' data-target='#modal_transfer' href='javascript:void(0);' class='detail_transfer' data-request-id='".$transaction->transId."' data-fdate='".$after_post['fdate']."' data-tdate='".$after_post['tdate']."' data-transfer-type='".$after_post['transferType']."'>Xem chi tiết</a>"; ?></td>
														</tr>
													<?php endforeach; ?>
												<?php endif; ?>
											<?php endif; ?>
										</tbody>
									</table>
									<div class="row">
										<div class="col-xs-6 pull-right">
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
						          <button type="button" class="btn btn-default" style="border-radius: 0px;" data-dismiss="modal">Đóng lại</button>
						        </div>
						      </div>
						      
						    </div>
						  </div>

					    <div role="tabpanel" class="tab-pane <?php echo (isset($tab) && $tab == "withdraw" ) ? 'active' : ""; ?>" id="withdraw">
                            <span class="error"><?php echo validation_errors() ?></span>
							<?php echo form_open('/trans_history/withdraw', array('method' => 'post', 'role' => 'form', 'id' => 'frm_search_list')); ?>
								<input type="hidden" id="hidden_page" name="page" />
								<div class="row">
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											
											<div class="form-group fix_width_control">
												<select name="provider_code" class="form-control">
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
											<div class="form-group fix_width_control">
											<?php $arrStatus = array(array('val' => '-1', 'name' => 'Trạng thái'),
																	array('val' => '99', 'name' => 'Chờ xử lý'),
																	array('val' => '00', 'name' => 'Thành công'),
																	array('val' => '01', 'name' => 'Thất bại')); ?>
												<select name="status" class="form-control">
													<?php foreach($arrStatus as $status): ?>
														<option value="<?php echo $status['val']; ?>" <?php echo $tab == 'withdraw' ? set_select('status', $status['val'], False) : ''; ?>><?php echo $status['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
                                            <div class="form-group fix_width_control">
                                                <?php
                                                $withdrawTypes = array(
                                                    '' => 'Hình thức',
                                                    '4' => 'Rút tiền theo phiên',
                                                    '11' => 'Rút tiền nhanh',
                                                    '5' => 'Rút tiền qua tài khoản liên kết',
                                                );
                                                ?>
                                                <select name="withdraw_type" class="form-control">
                                                    <?php foreach($withdrawTypes as $key => $value): ?>
                                                        <option value="<?php echo $key ?>" <?php echo $tab == 'withdraw' ? set_select('withdraw_type', $key, False) : ''; ?>><?php echo $value ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
											<div class="form-group fix_width_control input-group">
												<input name="fdate" class="form-control has-datepicker" type="text" value="<?php echo ($tab == 'withdraw' && set_value('fdate')) ? set_value('fdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control input-group w1">
												<hr>
											</div>
											<div class="form-group fix_width_control input-group pleft">
												<input name="tdate" class="form-control has-datepicker" type="text" value="<?php echo ($tab == 'withdraw' && set_value('tdate')) ? set_value('tdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control w20 pleft">
												<input name="transId" class="form-control" type="text" value="<?php echo $tab == 'withdraw' ? set_value('transId') : '' ?>" placeholder="Mã giao dịch">
											</div>
											<div class="form-group fix_width_control input-group pleft pull-right">
												<input type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-default btn-search pull-right" value="Tìm kiếm"/>
											</div>
									</div>
									<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
										<div class="form-group fix_width_control input-group no-margin" style="width: 80%;">
											<p class="heading-result-history">
												Tổng số giao dịch: <span class="total-his"><?php echo $tab == 'withdraw' ? $totalTrans : 0; ?></span><span style="display:inline-block;width:45px;"></span>Tổng tiền thực nhận: <span class="amount-his"><?php echo $tab == 'withdraw' ? $totalAmount : 0; ?> vnđ</span>
												</p>
										</div>
                                        <div class="form-group fix_width_control input-group pleft pull-right no-margin">
                                            <button type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-success btn-export-excel pull-right" name="excel">Xuất Excel</button>
                                        </div>
									</div>
								</div>
							
							
								<div class="table-responsive table_load_history">
									<table class="table table-bordered table-hover table-striped">
										<thead>
											<tr>
												<th>Mã giao dịch</th>
												<th>Ngân hàng</th>
												<th>Số tiền (đ)</th>
												<th style="width:130px;">Phí cố định theo giao dịch (đ)</th>
												<th>Phí theo tỉ lệ (đ)</th>
												<th>Tổng phí giao dịch (đ)</th>
												<th>Thực nhận (đ)</th>
												<th>Thời gian yêu cầu</th>
												<th>Trạng thái</th>
											</tr>
										</thead>
										<tbody>
											<?php if(isset($tab) && $tab == "withdraw"): ?>
												<?php if(isset($listTrans)): ?>
													<?php foreach($listTrans as $transaction): ?>
														<tr>
															<td><?php echo (isset($transaction->orgTransId)) ? $transaction->orgTransId : ""; ?></td>
															<td><?php echo (isset($transaction->bankName)) ? $transaction->bankName : ""; ?></td>
															<td><?php echo (isset($transaction->amount)) ? number_format($transaction->amount) : ""; ?></td>
															<td><?php echo (isset($transaction->fixFee)) ? number_format($transaction->fixFee) : ""; ?></td>
															<td><?php echo (isset($transaction->rateFee)) ? number_format($transaction->rateFee) : ""; ?></td>
															<td><?php echo (isset($transaction->feeAmount)) ? $transaction->feeAmount : ""; ?></td>
															<td><?php echo (isset($transaction->realMinus)) ? number_format($transaction->realMinus) : ""; ?></td>
															<td><?php echo (isset($transaction->timeCreate)) ? date('d/m/Y H:i:s', strtotime($transaction->timeCreate)) : ""; ?></td>
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
										<div class="col-xs-6 pull-right">
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
								<input type="hidden" id="hidden_page" name="page" />
								<div class="row">
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											
											
											<div class="form-group fix_width_control">
												<select name="provider_code" class="form-control">
													<option value="-1">Ngân hàng</option>
													<?php if(isset($listBank)): ?>
														<?php foreach($listBank as $bank): ?>
															<?php if($bank->type == '2'): ?>
																<option value="<?php echo $bank->providerCode; ?>" <?php echo set_select('provider_code', $bank->providerCode, False); ?>><?php echo $bank->providerName; ?></option>
															<?php endif; ?>
														<?php endforeach; ?>
													<?php endif; ?>
												</select>
											</div>
											<span class="error"><?php echo form_error('bank_code'); ?></span>
											<div class="form-group fix_width_control">
												<select name="provider_code" class="form-control">
													<option value="-1">Ngân hàng</option>
													<?php if(isset($listBank)): ?>
														<?php foreach($listBank as $bank): ?>
															<?php if($bank->type == '2'): ?>
																<option value="<?php echo $bank->recId; ?>" <?php echo set_select('provider_code', $bank->recId, False); ?>><?php echo $bank->providerName; ?></option>
															<?php endif; ?>
														<?php endforeach; ?>
													<?php endif; ?>
												</select>
											</div>
											<div class="form-group fix_width_control">
											<?php $arrStatus = array(array('val' => '-1', 'name' => 'Trạng thái'),
																	array('val' => '99', 'name' => 'Chờ xử lý'),
																	array('val' => '00', 'name' => 'Thành công'),
																	array('val' => '01', 'name' => 'Thất bại')); ?>
												<select name="status" class="form-control">
													<?php foreach($arrStatus as $status): ?>
														<option value="<?php echo $status['val']; ?>" <?php echo set_select('status', $status['val'], False); ?>><?php echo $status['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="form-group fix_width_control input-group">
												<input name="fdate" class="form-control has-datepicker" type="text" value="<?php echo (set_value('fdate')) ? set_value('fdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control input-group w1">
												<hr>
											</div>
											<div class="form-group fix_width_control input-group pleft">
												<input name="tdate" class="form-control has-datepicker" type="text" value="<?php echo (set_value('tdate')) ? set_value('tdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control pleft w20">
												<input name="transId" class="form-control" type="text" value="<?php echo set_value('transId') ?>" placeholder="Mã giao dịch">
											</div>
                                            <div class="form-group fix_width_control input-group pleft pull-right">
                                                <input type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-default btn-search pull-right" value="Tìm kiếm"/>
                                            </div>
											<div class="form-group fix_width_control input-group pleft">
												<button type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-success btn-export-excel pull-right" name="excel">Xuất Excel</button>

											</div>

										</div>

										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											<div class="form-group fix_width_control input-group" style="width: 80%;">
												<p class="heading-result-history">
												Tổng số giao dịch: <span class="total-his"><?php echo $totalTrans; ?></span><span style="display:inline-block;width:45px;"></span>Tổng tiền thực nhận: <span class="amount-his"><?php echo $totalAmount; ?> vnđ</span>
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
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
														</tr>
													<?php endforeach; ?>
												<?php endif; ?>
											<?php endif; ?>
										</tbody>
									</table>
									<div class="row">
										<div class="col-xs-6 pull-right">
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
								<input type="hidden" id="hidden_page" name="page" />
								<div class="row">
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											
											<div class="form-group fix_width_control w16">
												<select name="provider_code" class="form-control">
													<option value="-1">Ngân hàng</option>
													<?php if(isset($listBank)): ?>
														<?php foreach($listBank as $bank): ?>
															<?php if($bank->type == '2'): ?>
																<option value="<?php echo $bank->recId; ?>" <?php echo set_select('provider_code', $bank->recId, False); ?>><?php echo $bank->providerName; ?></option>
															<?php endif; ?>
														<?php endforeach; ?>
													<?php endif; ?>
												</select>
											</div>
											<div class="form-group fix_width_control w16">
											<?php $arrStatus = array(array('val' => '-1', 'name' => 'Trạng thái'),
																	array('val' => '99', 'name' => 'Chờ xử lý'),
																	array('val' => '00', 'name' => 'Thành công'),
																	array('val' => '01', 'name' => 'Thất bại')); ?>
												<select name="status" class="form-control">
													<?php foreach($arrStatus as $status): ?>
														<option value="<?php echo $status['val']; ?>" <?php echo set_select('status', $status['val'], False); ?>><?php echo $status['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="form-group fix_width_control input-group">
												<input name="fdate" class="form-control has-datepicker" type="text" value="<?php echo (set_value('fdate')) ? set_value('fdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control input-group w1">
												<hr>
											</div>
											<div class="form-group fix_width_control input-group pleft">
												<input name="tdate" class="form-control has-datepicker" type="text" value="<?php echo (set_value('tdate')) ? set_value('tdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control w20 pleft">
												<input name="transId" class="form-control" type="text" value="<?php echo set_value('transId') ?>" placeholder="Mã giao dịch">
											</div>
											<div class="form-group fix_width_control input-group w16 pleft pull-right">
												<input type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-default btn-search pull-right" value="Tìm kiếm"/>
											</div>
                                            <div class="form-group fix_width_control input-group w16 pleft">
                                                <button type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-success btn-export-excel pull-right" name="excel">Xuất Excel</button>
                                            </div>
										</div>
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
											<div class="form-group fix_width_control input-group" style="width: 80%;">
											<p class="heading-result-history">
												Tổng số giao dịch: <span class="total-his"><?php echo $totalTrans; ?></span><span style="display:inline-block;width:45px;"></span>Tổng tiền thực nhận: <span class="amount-his"><?php echo $totalAmount; ?> vnđ</span>
												</p>
                                            </div>

										</div>
									
								</div>
							
								<div class="table-responsive table_load_history">
									<table class="table table-bordered table-hover table-striped">
										<thead>
											<tr>
												<th>Mã giao dịch</th>
												<th>Số điện thoại nạp</th>
												<th>Mệnh giá (đ)</th>
												<th>Số lượng</th>
												<th>Tiền chiết khấu (đ)</th>
												<th>Tiền thanh toán (đ)</th>
												<th>Thời gian yêu cầu</th>
												<th>Trạng thái</th>
												<th>Thao tác</th>
											</tr>
										</thead>
										<tbody>
											<?php if(isset($tab) && $tab == "topup"): ?>
												<?php if(isset($listTrans)): ?>
													<?php foreach($listTrans as $transaction): ?>
														<tr>
															<td><?php echo $transaction->epurseTransId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
														</tr>
													<?php endforeach; ?>
												<?php endif; ?>
											<?php endif; ?>
										</tbody>
									</table>
									<div class="row">
										<div class="col-xs-6 pull-right">
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
					    
						<div role="tabpanel" class="tab-pane <?php echo (isset($tab) && $tab == "paymentgame" ) ? 'active' : ""; ?>" id="paymentgame">
                            <span class="error"><?php echo validation_errors() ?></span>
							<?php echo form_open('/trans_history/paymentgame', array('method' => 'post', 'role' => 'form', 'id' => 'frm_search_list')); ?>
								<input type="hidden" id="hidden_page" name="page" />
								<div class="row">
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											
											<div class="form-group fix_width_control w16">
												<select name="provider_code" class="form-control">
													<option value="-1">Ngân hàng</option>
													<?php if(isset($listBank)): ?>
														<?php foreach($listBank as $bank): ?>
															<?php if($bank->type == '2'): ?>
																<option value="<?php echo $bank->recId; ?>" <?php echo set_select('provider_code', $bank->recId, False); ?>><?php echo $bank->providerName; ?></option>
															<?php endif; ?>
														<?php endforeach; ?>
													<?php endif; ?>
												</select>
											</div>
											<div class="form-group fix_width_control w16">
											<?php $arrStatus = array(array('val' => '-1', 'name' => 'Trạng thái'),
																	array('val' => '99', 'name' => 'Chờ xử lý'),
																	array('val' => '00', 'name' => 'Thành công'),
																	array('val' => '01', 'name' => 'Thất bại')); ?>
												<select name="status" class="form-control">
													<?php foreach($arrStatus as $status): ?>
														<option value="<?php echo $status['val']; ?>" <?php echo set_select('status', $status['val'], False); ?>><?php echo $status['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="form-group fix_width_control input-group">
												<input name="fdate" class="form-control has-datepicker" type="text" value="<?php echo (set_value('fdate')) ? set_value('fdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control input-group w1">
												<hr>
											</div>
											<div class="form-group fix_width_control input-group pleft">
												<input name="tdate" class="form-control has-datepicker" type="text" value="<?php echo (set_value('tdate')) ? set_value('tdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control w20 pleft">
												<input name="transId" class="form-control" type="text" value="<?php echo set_value('transId') ?>" placeholder="Mã giao dịch">
											</div>
											<div class="form-group fix_width_control input-group w16 pleft pull-right">
												<input type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-default btn-search pull-right" value="Tìm kiếm"/>
											</div>
                                            <div class="form-group fix_width_control input-group w16 pleft">
                                                <button type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-success btn-export-excel pull-right" name="excel">Xuất Excel</button>
                                            </div>
										</div>
									
										
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
											<div class="form-group fix_width_control input-group" style="width: 80%;">
												<p class="heading-result-history">
												Tổng số giao dịch: <span class="total-his"><?php echo $totalTrans; ?></span><span style="display:inline-block;width:45px;"></span>Tổng tiền thực nhận: <span class="amount-his"><?php echo $totalAmount; ?> vnđ</span>
												</p>
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
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
														</tr>
													<?php endforeach; ?>
												<?php endif; ?>
											<?php endif; ?>
										</tbody>
									</table>
									<div class="row">
										<div class="col-xs-6 pull-right">
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
								<input type="hidden" id="hidden_page" name="page" />
								<div class="row">
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 txt-info">
											
											<div class="form-group fix_width_control w16">
												<select name="provider_code" class="form-control">
													<option value="-1">Ngân hàng</option>
													<?php if(isset($listBank)): ?>
														<?php foreach($listBank as $bank): ?>
															<?php if($bank->type == '2'): ?>
																<option value="<?php echo $bank->recId; ?>" <?php echo set_select('provider_code', $bank->recId, False); ?>><?php echo $bank->providerName; ?></option>
															<?php endif; ?>
														<?php endforeach; ?>
													<?php endif; ?>
												</select>
											</div>
											<div class="form-group fix_width_control w16">
											<?php $arrStatus = array(array('val' => '-1', 'name' => 'Trạng thái'),
																	array('val' => '99', 'name' => 'Chờ xử lý'),
																	array('val' => '00', 'name' => 'Thành công'),
																	array('val' => '01', 'name' => 'Thất bại')); ?>
												<select name="status" class="form-control">
													<?php foreach($arrStatus as $status): ?>
														<option value="<?php echo $status['val']; ?>" <?php echo set_select('status', $status['val'], False); ?>><?php echo $status['name']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="form-group fix_width_control input-group">
												<input name="fdate" class="form-control has-datepicker" type="text" value="<?php echo (set_value('fdate')) ? set_value('fdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control input-group w1">
												<hr>
											</div>
											<div class="form-group fix_width_control input-group pleft">
												<input name="tdate" class="form-control has-datepicker" type="text" value="<?php echo (set_value('tdate')) ? set_value('tdate') : date('d/m/Y'); ?>">
												<span class="input-group-addon "><i class="fa fa-calendar"></i></span>
											</div>
											<div class="form-group fix_width_control w20 pleft">
												<input name="transId" class="form-control" type="text" value="<?php echo set_value('transId') ?>" placeholder="Mã giao dịch">
											</div>
											<div class="form-group fix_width_control input-group w16 pleft pull-right">
												<input type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-default btn-search pull-right" value="Tìm kiếm"/>
											</div>
                                            <div class="form-group fix_width_control input-group w16 pleft">
                                                <button type="submit" class="col-md-6 col-lg-6 col-xs-6 col-sm-6 btn btn-success btn-export-excel pull-right" name="excel">Xuất Excel</button>
                                            </div>
										</div>
										<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
											<div class="form-group fix_width_control input-group" style="width: 80%;">
												<p class="heading-result-history">
												Tổng số giao dịch: <span class="total-his"><?php echo $totalTrans; ?></span><span style="display:inline-block;width:45px;"></span>Tổng tiền thực nhận: <span class="amount-his"><?php echo $totalAmount; ?> vnđ</span>
												</p>
											</div>

										</div>
									
								</div>

								<div class="table-responsive table_load_history">
									<table class="table table-bordered table-hover table-striped">
										<thead>
											<tr>
												<th>Mã giao dịch EP</th>
												<th>Mã hợp đồng/Mã giao dịch đối tác</th>
												<th>Nhà cung cấp</th>
												<th>Tiền thanh toán (đ)</th>
												<th>Loại thanh toán</th>
												<th>Ngày giao dịch</th>
												<th>Trạng thái</th>
											</tr>
										</thead>
										<tbody>
											<?php if(isset($tab) && $tab == "paymentbills"): ?>
												<?php if(isset($listTrans)): ?>
													<?php foreach($listTrans as $transaction): ?>
														<tr>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
															<td><?php echo $transaction->requestId; ?></td>
														</tr>
													<?php endforeach; ?>
												<?php endif; ?>
											<?php endif; ?>
										</tbody>
									</table>
									<div class="row">
										<div class="col-xs-6 pull-right">
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
		
		$('body').on('click','.detail_transfer',function(){
			var req_id = $(this).attr('data-request-id'); 
			var fdate = $(this).attr('data-fdate'); 
			var tdate = $(this).attr('data-tdate'); 
			var transfer_type = $(this).attr('data-transfer-type'); 
			$.ajax({
				url: '/trans_history/transfer_detail',
				type: 'POST',
				dataType: 'json',
				data: {req_id: req_id,fdate:fdate,tdate:tdate,transfer_type:transfer_type},
			})
			.done(function(data) {
				if (data.status==true) {
					$('#modal_transfer .modal-body').html('');
					$('#modal_transfer .modal-body').html(data.html);
				}
			});

		});
	});
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
</body>
</html>