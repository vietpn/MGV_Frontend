<?php if(isset($this->session_memcached->userdata['info_user']['userID'])): ?>
		<div id="sidebar-wrapper" class="navbar-default sidebar" role="navigation">
			
			<div class="nav-left-info">
				<div class="lienhe"><i class="fa fa-phone" style="margin-right: 5px;"></i>HotLine: <span>19006470</span></div> 
				<div class="menu-left-info">
					<img class="logo-user" src="/../../images/avatar-default.png">
					<div class="nav-user-name"><?php echo isset($userinfo['userName']) ? $userinfo['userName'] : '';?></div>
				</div>
				
				<div class="sidebar-brand" style="margin-top: 15px;">
					<p><span class="user-balance">Số dư khả dụng</span><span class="balance"><?php echo isset($userinfo['balance']) ? number_format($userinfo['balance']) : 0;?> đ</span></p>
					<p><span class="user-balance">Số dư tạm giữ</span><span class="balance">0 đ</span></p>
				</div>
				
			</div>
			
			<ul class="nav side-menu-new" id="side-menu">
				<li>
					<a >Khuyến mại</a>
				</li>
				<li class="">
					<a class="trans-history">Lịch sử giao dịch</a>
				</li>
				<li>
					<a href="#">Biến động số dư</a>
				</li>
				<li class="transaction">
					<a href="/transaction_manage" style="color: rgb(61, 61, 61);">Giao dịch</a>
					
					<span><a class="trans-item-payment-sidebar">Nạp tiền</a></span>
					<span><a class="trans-item-withdraw-sidebar">Rút tiền</a></span>
					<span><a class="trans-item-transfer-sidebar">Chuyển tiền</a></span>
					<span><a class="trans-item-bank-map-sidebar">Liên kết BANK</a></span>
					<span><a class="trans-item-payment-mobile-sidebar">Nạp điện thoại</a></span>
					<span><a class="trans-item-payment-game-sidebar">Nạp tiền game</a></span>
					<span><a class="trans-item-topup-sidebar">Mua mã thẻ</a></span>
					<span><a class="trans-item-thanh-toan-sidebar">Thanh toán</a></span>
					
				</li>
				<li>
					<a class="accountmanage" href="/acc_manage">Quản trị tài khoản</a>
				</li>
				<li>
					<a href="#">Trung tâm hỗ trợ</a>
				</li>
				<li>
					<a href="/logout">Đăng xuất</a>
				</li>
			</ul>
		</div>
<?php endif; ?>