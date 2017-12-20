<?php if(isset($this->session_memcached->userdata['info_user']['userID'])): ?>
		<div id="sidebar-wrapper" class="navbar-default sidebar" role="navigation">
			
			<div class="nav-left-info">
				<div class="lienhe"><i class="fa fa-phone" style="margin-right: 5px;"></i>HotLine: <span>19006470</span>
					<div class="visible-xs-block close_left_menu"></div>
				</div> 
				<div class="menu-left-info">
					<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
					<input type="hidden" class="account_name" value="<?php echo isset($userinfo['mobileNo']) ? $userinfo['mobileNo'] : '';?>">
					<img class="logo-user" data-toggle="modal" data-target="#myModalChooseAvatar" data-backdrop="static" data-keyboard="false" title="Cập nhật avatar" src="<?php echo (isset($this->session_memcached->userdata['info_user']['avatar_url']) && $this->session_memcached->userdata['info_user']['avatar_url'] !='') ? $this->session_memcached->userdata['info_user']['avatar_url'] : '/../../images/avatar-default.png'; ?>">
					
					<div class="nav-user-name"><?php echo isset($userinfo['mobileNo']) ? $userinfo['mobileNo'] : '';?></div>
					<div class="notify-box">
						<i class="fa fa-bell-o" aria-hidden="true"></i>
						<span><?php echo $this->session_memcached->userdata['info_user']['countUserInbox']; ?></span>
					</div>
					<div class="notify-list">
						<div class="panel panel-success">
					      <div class="panel-heading"><strong>Thông tin giao dịch</strong>:
							<input type="hidden" class="page_num_messager" value="1" />
					      </div>
					      <div class="panel-body">	
					      </div>
					      <div class="panel-footer-notify">
					      	<p class="text-center"><a href="javascript:void;" class="notify-all">Xem thêm</a></p>
					      </div>
					    </div>
						<br />
					</div>

				</div>

				
				
				<div class="sidebar-brand" style="margin-top: 15px;">
					<p><span class="user-balance">Số dư khả dụng</span><span class="balance"><?php echo isset($userinfo['balance']) ? number_format($userinfo['balance']) : 0;?> đ</span></p>
					<p><span class="user-balance">Số dư tạm giữ</span><span class="balance"><?php echo isset($userinfo['balance']) ? number_format($userinfo['balance_behold']) : 0;?> đ</span></p>
				</div>
				
			</div>
			
			<ul class="nav side-menu-new" id="side-menu">
				<li>
					<a class="news-promotions-sidebar" href="javascript:;" title="Khuyến mại" data-media="<?php echo base_url('images/EventSmartnet.png') ?>">Khuyến mại</a>
				</li>
				<li class="">
					<a class="trans-history">Lịch sử giao dịch</a>
				</li>
				<li>
					<a class="balance-change">Biến động số dư</a>
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
					<a class="support-center-sidebar">Trung tâm hỗ trợ</a>
				</li>
				<li>
					<a href="/logout">Đăng xuất</a>
				</li>
			</ul>
		</div>
<?php endif; ?>