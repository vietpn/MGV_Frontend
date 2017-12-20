<?php
/**
 * User: Phongwm
 * Date: 2016-07-02
 * Time: 3:43 PM
 * To change this template use File | Settings | File Templates.
 */
?>

<script type="text/javascript" src="<?php echo base_url() . '../js/payment/request_trans.js' ?>"></script>
<div class="txt_account">Tra cứu lịch sử giao dịch</div>
<div class="group-username row">
	<?php echo form_open("trans_history?clientID=$client_id", array('method' => 'post', 'role' => 'form')); ?>
		<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
		<div class="box-body">
			<div class="row">
				<div class="form-group col-md-3">
					<input type="text" name="serial" value="<?php echo set_value('serial'); ?>" class="form-control" placeholder="Số Serial">
					<?php echo form_error('serial'); ?>
				</div>
				<div class="form-group col-md-3">
					<input type="text" name="MPIN" value="<?php echo set_value('MPIN'); ?>" class="form-control" placeholder="Mã thẻ">
					<?php echo form_error('MPIN'); ?>
				</div>
				<div class="form-group col-md-3">
					<input type="text" name="phone" value="<?php echo set_value('phone'); ?>" class="form-control" placeholder="Số điện thoại">
					<?php echo form_error('phone'); ?>
				</div>
				<div class="form-group col-md-3">
					<input type="text" name="num_records" value="<?php echo set_value('num_records'); ?>" class="form-control" placeholder="Số lượng giao dịch">
					<?php echo form_error('num_records'); ?>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-4">
					<?php
						$dd_list = array('0' => 'Chọn loại hình thanh toán',
										 '1' => 'Thanh toán qua SMS',
										 '2' => 'Thanh toán qua thẻ cào',
										 '3' => 'Thanh toán qua ngân hàng');
						$sl_val = $this->input->post('TYPE');
					?>
					<?php echo form_dropdown('TYPE', $dd_list, set_value('TYPE', ( ( !empty($sl_val) ) ? "$sl_val" : 3 ) ), 'class="form-control"' ); ?>
					<?php echo form_error('TYPE'); ?>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-3">
					<input type="text" id="fromdate" name="fromdate" value="<?php echo set_value('fromdate', (isset($filter['fromdate']) && !empty($filter['fromdate']))?$filter['fromdate']:'') ; ?>" class="form-control" placeholder="Từ ngày">
					<?php echo form_error('fromdate'); ?>
				</div>
				<div class="form-group col-md-3">
					<input type="text" id="todate" name="todate" value="<?php echo set_value('todate', (isset($filter['todate']) && !empty($filter['todate']))?$filter['todate']:''); ?>" class="form-control" placeholder="Đến ngày">
					<?php echo form_error('todate'); ?>
				</div>
			</div>
        </div>
		<div class="box-footer">
			<div class="form-group">
				<button type="submit" class="btn btn-primary ">Tìm kiếm</button>
			</div>
		</div>
    </form>
</div>

<div class="group-username row">
	<div class="box">
		<style>
			th{
				vertical-align: middle !important;
				text-align: center;
			}
			.box{
				overflow: auto;
			}
			.table-bordered>thead>tr>th,.table>tbody>tr>td{
				
			}
		</style>
		<table class="table table-striped" id="maintable">
			<thead>
			<tr>
				<th>STT</th>
				<th>Mã giao dịch</th>
				<th>Dịch vụ</th>
				<!-- Cot thanh toan Card -->
				<th style="min-width: 135px;">Số Serial</th>
				<th style="min-width: 135px;">Mã thẻ</th>
				<th style="min-width: 135px;">Đối tác</th>
				
				<!-- Cot thanh toan SMS -->
				<th style="min-width: 135px;">Số điện thoại</th>

				<!-- Cot thanh toan Bank -->
				<th style="min-width: 135px;">Số tài khoản ngân hàng</th>
				<th style="min-width: 135px;">Tên tài khoản ngân hàng</th>
				<th style="min-width: 135px;">Tên ngân hàng</th>

				<!-- Cot cho thanh toan chung -->
				<th style="min-width: 135px;">Loại thanh toán</th>
				<th>Giá trị giao dịch</th>
				<th style="min-width: 135px;">Trạng thái giao dịch</th>
				
				<th>Ngày tạo</th>
			</tr>
			</thead>

			<tbody>
			<?php if(isset($transactions) && !empty($transactions)): ?>
				<?php foreach ($transactions as $key => $record) : ?>
					<tr>
						<td><?php echo $key+1 ?></td>
						<td><?php echo $record->trans_id ?></td>
						<td><?php echo $record->sevice ?></td>
						<!-- Cot thanh toan Card -->
						<td><?php echo $record->serial ?></td>
						<td><?php echo $record->mpin ?></td>
						<td><?php echo $record->provider_name ?></td>
						
						<!-- Cot thanh toan SMS -->
						<td><?php if($record->payment_type == '1') echo $record->phone_numb ?></td>

						<!-- Cot thanh toan Bank -->
						<td><?php if($record->payment_type == '3') echo $record->bank_account ?></td>
						<td><?php if($record->payment_type == '3') echo $record->bank_acccount_name ?></td>
						<td><?php if($record->payment_type == '3') echo $record->bank_name ?></td>

						<!-- Cot thanh toan chung -->
						<td><?php 
							if($record->payment_type == '1')
								echo "Thanh toán qua SMS";
							elseif($record->payment_type == '2')
								echo "Thanh toán qua thẻ cào";
							elseif($record->payment_type == '3')
								echo "Thanh toán qua ngân hàng";
							
						?></td>
						<td><?php echo $record->amount ?></td>
						<td><?php 
								if($record->status == '00')
									echo "Thành công";
								elseif($record->status == '99')
									echo "Đang xử lý";
								else
									echo "Thất bại";
						?></td>
						<td><?php echo $record->created_date ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="15">Không có giao dịch</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

