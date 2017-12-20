<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ma hoa va giai ma du lieu
 */
class Id_encrypt
{
    public function __construct() {
        $this->ci = & get_instance();
    }


    /*
     * Ma hoa
     */
    public function encrypt($text)
    {
        $key = base64_decode(KEY_DECODE);
        $size = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
        $text = $this->pkcs5_pad($text, $size);
        $bin = pack('H*', bin2hex($text));
        $size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($size, MCRYPT_RAND);
        $encrypted = bin2hex(mcrypt_encrypt(MCRYPT_3DES, $key, $bin, MCRYPT_MODE_ECB, $iv));
        return $encrypted;
    }

    public function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /*
     * Giai ma
     */
    public function decode_access_token($text)
    {
        $key = base64_decode(KEY_DECODE);
        $str = $this->hex2bin($text);
        $size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($size, MCRYPT_RAND);
        $decrypted = mcrypt_decrypt(MCRYPT_3DES, $key, $str, MCRYPT_MODE_ECB, $iv);
        $info_user = json_decode(rtrim($this->pkcs5_unpad($decrypted)));
        return $info_user;
    }

    /**
     * Chuyen ve ASCII
     * @param $text
     * @return bool|string
     */
    public function pkcs5_unpad($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        $a = substr($text, 0, -1 * $pad);
        return substr($text, 0, -1 * $pad);
    }

    public function hex2bin($str) {
        $bin = "";
        $i = 0;
        do {
            $bin .= chr ( hexdec ( $str {$i} . $str {($i + 1)} ) );
            $i += 2;
        } while ( $i < strlen ( $str ) );
        return $bin;
    }

    public function mDESMAC_3des($input, $key) {
        $input = sha1 ( $input );
        $len = strlen ( $input );

        $td = mcrypt_module_open ( MCRYPT_3DES, '', MCRYPT_MODE_ECB, '' );
        $blocksize = mcrypt_enc_get_block_size ( $td );
        $keysize = mcrypt_enc_get_key_size ( $td );
        $iv_size = mcrypt_enc_get_iv_size ( $td );
        $iv = "hywebpg5";
        $input_len = strlen ( $input );
        $padsize = $blocksize - ($input_len % $blocksize);
        @mcrypt_generic_init ( $td, $key, $iv );

        // echo strlen($input) . "<BR>";
        // echo strlen(mcrypt_generic($td, $input)) . "<BR>";
        $MacDes = bin2hex ( mcrypt_generic ( $td, $this->hex2bin ( $input ) ) );
        return strtoupper ( $MacDes );
    }

}
