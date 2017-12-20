<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

// config pagination
define('NUMBER_RECORDS_PER_PAGE', '10');

// footer images
define('FOOTER_IMG','tt247.png|https://thanhtoan247.vn,mgc.png|https://megacard.vn,tt247-2.png|https://thanhtoan247.vn,mgc-1.png|https://megacard.vn');


// session name of user 
define('SESSION_NAME', 'MEGAV_V1');

// URL login MegaID
define('URL_LOGIN_MEGAID', '');

// config memecache
define('MEMCACHE_SERVER', '172.16.10.61');
define('MEMCACHE_PORT', '11211');
define('MEMCACHE_TTL', '2592000');

// config redis
define('USER_INFO_REDIS_TTL', '12000'); // 20 phút


define('CLIENT_ID_OPENID','100005');
define('URL_CLIENT','http://localhost:7680');
//thoi gian song cua redis va cookie thoi gian nay de dong bo vs thoi gian song tren authenserver
define('TIMELIFE_REDIS','36000');
//define('KEY_DECODE','PrYc426SeRWzKrwgwoPs1m3NyLBzyzgO');
define('KEY_DECODE','RVdMf7UBhTu1wgTsLDSkDRBhYQt6duYs');
define('KEY_DECODE_SDK','34Wotq4VTKGRg2TqAlfpO6gvN9+5Xq3+');


//authenserver
define('URL_AUTHENSERVER','http://172.16.10.72:8080/MegaVCore-1.0.1/rest/megav/authenServer1?');
//define('URL_AUTHENSERVER','http://172.16.12.59:8080/MegaVCore/rest/megav/authenServer1?');
define('URL_ALTER_AUTHEN','http://172.16.10.61:8080/MegaVCore-1.0.1/rest/megav/authenServer1?');
define('ERR_00','Xác thực thành công. ClientID, username, password hợp lệ');
define('ERR_01','Client request từ IP không hợp lệ');
define('ERR_02','ClientId không hợp lệ');
define('ERR_03','Client đang bị tạm khóa');
define('ERR_04','User hoặc mật khẩu không đúng');
define('ERR_05','User không tồn tại');
define('ERR_06','AuthenCode không hợp lệ');
define('ERR_07','Hết thời hạn truy cập. Xin vui lòng đăng nhập lại');
define('ERR_08','Dữ liệu không hợp lệ(Dữ liệu null hoặc không giải mã được)');
define('ERR_09','Comfirm thành công');
define('ERR_14','Tên người dùng đã tồn tại');
define('ERR_15','Email đã tồn tại');
define('ERR_16','Email hoặc mật khẩu không đúng');
define('ERR_17','Tài khoản chưa được active');
define('ERR_CQ_WQ','Mật khẩu không đúng');

// redis
define('REDIS_SELECT_INDEX', 2);
define('SOURCE_URL_KEY_PREFIX', 600);
define('SOURCE_URL_TTL', 7200); // 10 phút, thời gian lưu url trc khi login
define('TRANS_DATA_TTL', 6000); // 10 phút, thời gian lưu data giao dich
define('SESSION_KEY_TTL', 86400); // 24h, thời gian sống của session key


// medav 
define('URL_MEGAV_CORE','http://172.16.10.72:8080/MegaVCore-1.0.1/rest/megav/authenServer?mgvrequest=');
//define('URL_MEGAV_CORE','http://172.16.12.104:8080/MegaVCore/rest/megav/authenServer?mgvrequest=');

define('PARTNER_ID', "1000");
define('PARTNER_UNAME', "frontend_1");
define('PARTNER_PASS', "fcea920f7412b5da7be0cf42b8c93759");
define('PARTNER_KEY_ENDCODE', "Iw3Tx5Kts8iSyzGPJaJryLCnsLxz6QvT");
define('PARTNER_RSA_KEY_PATH', "/key");

// check so lan nhap sai otp
define('WRONG_OTP', 3);

// capcha google
define('NUM_OF_WRONG_PASS', 1);
define('API_GOOGLE_RECAPTCHA_URL', 'https://www.google.com/recaptcha/api/siteverify');
define('API_GOOGLE_RECAPTCHA_PUBLIC', '6LepBA4UAAAAALhhQOPAEDE8IMbWq6FPxOamQQ_u');
define('API_GOOGLE_RECAPTCHA_SECRET', '6LepBA4UAAAAAJydjX60Vae_ocMipHXyDONdKiJi');

// cau hinh so lan retry ket noi get session key voi core 
define('RETRY_GET_SESSION_KEY', 3);

// cau hinh so lan retry get userinfo in redis
define('RETRY_GET_UINFO_REDIS', 3);

// cau hinh mediaserver
define('URL_MEDIA_SERVER', 'http://172.16.10.160:8081/uploadimages/upload');
define('MEDIA_SERVER_KEY', 'MegaV');


// cau hinh so lan quen mat khau cap 2
define('RESET_PASS_LV2_KEY_CONFIG', 'systemKeyResetPassLv2');
define('RESET_PASS_LV2_NUMB', '3');

// megav server error code
define('STATUS_SUCCESS', '00');
define('STATUS_CANT_SEND_OTP', '32');
define('STATUS_WRONG_OTP', '08');
define('STATUS_WRONG_PASSLV2', '24');
define('STATUS_MANY_USER', '18');
define('STATUS_INVALID_PHONE_EMAIL', '14');
define('STATUS_INVALID_MERCHANTID', '36');
define('STATUS_BONUS_NOT_ENOUGH_MONEY', '37');
define('STATUS_DUPLICATE_TRANSACTION', '38');
define('STATUS_VERIFY_SIGNATURE_FAIL', '39');
define('STATUS_USERNAME_EXITED', '10');
define('STATUS_WRONG_AMOUNT', '41');
define('STATUS_WRONG_SESSIONKEY', '06');
define('STATUS_REDIRECT_BANK', 'G1');// ra ngoài ngân hàng điền mẫu đăng ký.
define('STATUS_BANK_EXCEED', 'G5');// số tiền vượt ngưỡng cho phép của ngân hàng


// EC merchant
define('EC_TIME_REDIRECT_NOT_ENOUGH_MONEY', '30000');

// payment
define('TEMPLATE_FEE_KEY_REDIS', 'MEGAV_TEMPLATE_FEE');
define('PROVIDER_KEY_REDIS', 'MEGAV_LIST_PROVIDER');


// processingCode
define('TRANSFER_TO_USER', 4);// chuyển tiền ví nội bộ
define('TRANSFER_TO_SERVICES', 5);// chuyển tiền ví dịch vụ

// transfer
define('TRANSFER_MINIMUM_AMOUNT', '1000');
define('TRANSFER_MAXIMUM_AMOUNT', '10000000');

// withdraw
define('WITHDRAW_MINIMUM_AMOUNT', '1000');
define('WITHDRAW_MAXIMUM_AMOUNT', '10000000');

// version WEB
define('VERSION_WEB', '1.8');

// buy card
define('TIME_REDIRECT_LIST_CARD', '50000'); // 50 giay

// config page warning
define('WARNING_PAGE', '0');


// subtupe
define('SUB_DEPOSIT_BANK_REDIRECT', '1');
define('SUB_DEPOSIT_BANK_FAST', '2');
define('SUB_DEPOSIT_BANK_MAPPING', '3');
define('SUB_TRANSFER_DEBIT', '4');
define('SUB_TRANSFER_RECEIVER', '5');
define('SUB_WITHDRAW_TYPE_OFFLINE', '6');
define('SUB_WITHDRAW_TYPE_FAST', '7');
define('SUB_WITHDRAW_TYPE_ONLINE', '8');

// template type
define('EC_MERCHANT_TEMP', '1');
define('TRANSFER_LOCAL_TEMP', '2');
define('DEPOSIT_IBK_TEMP', '3');
define('WIDTHDRAW_TEMP', '4');
define('WIDTHDRAW_MAPPING_TEMP', '5');
define('DEPOSIT_BANK_MAPPING_TEMP', '6');
define('TRANSFER_TO_SERVICE_TEMP', '7');
define('TOPUP_GAME_TEMP', '8');
define('DOWNLOAD_SOFTPIN_TEMP', '9');
define('PAY_BILL_TEMP', '10');
define('WIDTHDRAW_FAST_TEMP', '11');
define('DEPOSIT_IBK_VISA_TEMP', '12');
define('TRANSFER_BY_FILE_TEMP', '13');

define('REQUIRE_HAVE_BANK_ACCOUNT', '1');

/* End of file constants.php */
/* Location: ./application/config/constants.php */