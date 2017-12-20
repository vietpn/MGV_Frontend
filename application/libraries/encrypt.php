<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ma hoa va giai ma du lieu
 */
class encrypt
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
        $str = $this->ci->hex2bin($text);
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

}