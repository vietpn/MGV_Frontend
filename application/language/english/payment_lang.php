<?php
/**
 * Ngon ngu tieng viet cho module payment
 *
 * @author: tienhm
 * @date: 7/11/2014
 * @time: 11:46 AM
 */

// Ngon ngu cho phan thanh toan qua theo cao 
$lang['payment_card_title'] = 'Thanh toán qua thẻ cào';
$lang['payment_card_cardtype'] = 'Loại Thẻ';
$lang['payment_card_cardcode'] = 'Mã Thẻ';
$lang['payment_card_serial'] = 'Seri Thẻ';
$lang['payment_card_submit'] = 'Thanh toán';
$lang['payment_card_success'] = 'Nạp thẻ thành công';
$lang['payment_card_failure'] = 'Nạp thẻ thất bại';
$lang['payment_card_account_not_match'] = 'Tài khoản không phù hợp';

// Ngon ngu cho phan thanh toan qua ngan hang 
$lang['payment_bank_title'] = 'Thanh toán qua ngân hàng';
$lang['payment_bank_bankname'] = 'Ngân hàng';
$lang['payment_bank_amount'] = 'Số tiền';
$lang['payment_bank_fee'] = 'Phí giao dịch';
$lang['payment_bank_billing'] = 'THÀNH TIỀN';
$lang['payment_bank_submit'] = 'Thanh toán';
$lang['payment_bank_success'] = 'Thanh toán thành công';
$lang['payment_bank_failure'] = 'Thanh toán thất bại';

// Ngon ngu cho phan thanh toan qua sms
$lang['payment_sms_title'] = 'Thanh toán qua SMS';
$lang['payment_sms_value'] = 'Mệnh giá';

// Ngon ngu cho thanh toan in app purchase
$lang['payment_iap_title'] = 'Thanh toán qua In App Purchase';

// Ngon ngu chung
$lang['error_message'] = 'Bạn nhập thông tin sai định dạng';
$lang['logout_successful'] = 'Bạn đã đăng xuất thành công';
$lang['payment_system_down'] = 'Hệ thống đang bận. Hãy thử lại sau.';
$lang['payment_is_that_you'] = 'Bạn có phải là %s ?';
$lang['yes'] = 'Phải';
$lang['no'] = 'Không phải';
$lang['retry'] = 'Xin vui lòng thử lại';
$lang['invalid_info'] = 'Thông tin thanh toán không phù hợp';

// IBanking Err
$lang['bank_err_908'] = 'Lỗi timeout xảy ra do không nhận thông điệp trả về';
$lang['bank_err_911'] = 'Số tiền không hợp lệ';
$lang['bank_err_912'] = 'Phí không hợp lệ';
$lang['bank_err_913'] = 'Tax không hợp lệ';
$lang['bank_err_810'] = 'Thẻ hết hạn/thẻ bị khóa ';
$lang['bank_err_811'] = 'Thẻ chưa đăng ký dịch vụ Internet banking ';
$lang['bank_err_812'] = 'Ngày phát hành/hết hạn không đúng ';
$lang['bank_err_813'] = 'Vượt quá hạn mức thanh toán';
$lang['bank_err_821'] = 'Số tiền không đủ để thanh toán';
$lang['bank_err_899'] = 'Người sử dụng cancel';
$lang['bank_err_901'] = 'Merchant_code không hợp lệ';
$lang['bank_err_902'] = 'Chuỗi mã hóa không hợp lệ';
$lang['bank_err_903'] = 'Merchant_tran_id không hợp lệ';
$lang['bank_err_904'] = 'Không tìm thấy giao dịch trong hệ thống';
$lang['bank_err_906'] = 'Đã xác nhận trước đó';
$lang['bank_err_11'] = 'Sai thông tin';
$lang['bank_err_12'] = 'Ngân hàng tạm khóa hoặc không tồn tại';
$lang['bank_err_13'] = 'Có lỗi';
$lang['bank_err_14'] = 'Code không hợp lệ';
$lang['bank_err_801'] = 'Ngân hàng từ chối giao dịch';
$lang['bank_err_803'] = 'Mã đơn vị không tồn tại';
$lang['bank_err_804'] = 'Không đúng acces code';
$lang['bank_err_805'] = 'Số tiền không hợp lệ';
$lang['bank_err_806'] = 'Mã tiền tệ không tồn tại';
$lang['bank_err_807'] = 'Lỗi không xác định';
$lang['bank_err_808'] = 'Số thẻ không đúng';
$lang['bank_err_809'] = 'Tên chủ thẻ không đúng';
$lang['bank_err_01'] = 'Thất bại';
$lang['bank_err_02'] = 'Chưa confirm được';
$lang['bank_err_03'] = 'Đã confirm trước đó';
$lang['bank_err_04'] = 'Giao dịch Pending';
$lang['bank_err_05'] = 'Sai MAC';
$lang['bank_err_06'] = 'Không xác định mã lỗi';
$lang['bank_err_07'] = 'Giao dịch không tồn tại';
$lang['bank_err_08'] = 'Thông tin không đầy đủ';
$lang['bank_err_09'] = 'Đại lý không tồn tại';
$lang['bank_err_10'] = 'Sai định dạng';