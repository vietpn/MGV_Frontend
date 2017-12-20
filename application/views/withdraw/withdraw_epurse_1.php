
	<ul class="breadcrumb">
		<li class="first">|</li>
		<li><a>Rút tiền</a></li>
	</ul>

	<div class="form-center">
		
		<div class="step-by-step">
			<ol class="progtrckr" data-progtrckr-steps="4">

				<li class="progtrckr-done step_one"><span>Chọn phương thức rút tiền</span></li>
				<li class="<?php echo isset($step) && $step == 2 ? 'progtrckr-done' : 'progtrckr-todo' ?> step_two"><span class="hidden-xs hidden-sm">Khai báo thông tin rút</span></li>
				<li class="<?php echo isset($step) && $step == 3 ? 'progtrckr-done' : 'progtrckr-todo' ?> step_three"><span class="hidden-xs hidden-sm">Xác nhận giao dịch - hoàn thành</span></li>

				
			</ol>
		</div>
		<div class="cash_out" style="text-align: center;">
			<ul class="nav nav-tabs" style="width: auto;display: inline-block;">
				
				<li>
					<span>Rút tiền nhanh</span>
					<p>Các giao dịch rút tiền nhanh sẽ được chuyển ngay vào tài khoản Ngân hàng của Quý khách </p>
					<a class="active" href="/withdraw/withdraw_epurse/2">Rút tiền</a>
				</li>
				<li>
					<span>Rút tiền qua tài khoản liên kết</span>
					<p>Rút tiền ra tài khoản ngân hàng đã liên kết với Ví, tiền sẽ nổi ngay khi giao dịch thành công</p>
					<a class="active" href="/withdraw/withdraw_epurse/3">Rút tiền</a>
				</li>
			</ul>
		</div>
		
	</div>

