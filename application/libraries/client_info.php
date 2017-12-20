<?php defined('BASEPATH') OR exit('No direct script access allowed');


class client_info
{
    public function __construct()
    {
        $this->ci = & get_instance();
        $this->ci->load->library('session_memcached');
        $this->ci->load->library('redis');
    }


    /**
     * Get url clientid to redirect after login
     * @author: hatt
     * @param $clientID
     * @return bool
     */
    public function get_client_info($clientID)
    {
        $this->ci->load->library('id_curl');
        $url = URL_AUTHENSERVER;
        $url .= urlencode('method=getClient' . '&clientid=' . $clientID);
        $info = $this->ci->id_curl->get_curl($url);
        log_message('info', 'LOGIN : get_client_info url nhan ve ' . $url . '. info nhan ve sau khi gui di ' . $info);
        if (!empty($info)) {
            $response = json_decode($info);
            if ($response->status == '00') {
                $urlRedirect = $response->data;
                return $urlRedirect;
            } else {
                return false;
            }
        }
    }

    /**
     *logout ra khoi he thong openID
     * @author: hatt
     * @create on : 22/08/2014
     */
    public function logout()
    {
        $redis = new CI_Redis();
        $curr_url = $_SERVER['QUERY_STRING'];
        if (empty($curr_url)) {
            $clientId = CLIENT_ID_OPENID;
        } else {
            $info = trim(str_replace('appId=', '', $curr_url));
            $info_user = json_decode(urldecode($info));
            $url = $info_user->url;
            $clientId = $info_user->clientID;
        }
        $checkurl = $this->get_client_info($clientId);
        $this->ci->session_memcached->unset_userdata('user_data');
        $this->ci->session_memcached->unset_userdata('info_user');
        //del danh sach redis
        $redis->del('clientId');
        foreach($_COOKIE as $key => $value){
            if(stripos($key, '_authen')){
                delete_cookie($key);
            }
        }
        delete_cookie($clientId.'_payment_card');
        delete_cookie($clientId.'_payment_bank');
            return true;


    }
    public function remove_data()
    {
        $this->ci->session_memcached->unset_userdata('user_data');
        $this->ci->session_memcached->unset_userdata('info_user');
        //del danh sach redis
        delete_cookie('clientId');
        delete_cookie('epay_session');
        delete_cookie('ci_session');
        foreach ($_COOKIE as $key => $value) {
            if (stripos($key, '_authen')) {
                delete_cookie($key);
            }
        }
        $facebook = new Facebook(array(
            'appId' => $this->ci->config->item('appId'),
            'appSecret' => $this->ci->config->item('appSecret'),
        ));
        $facebook->destroySession();
        return true;
    }

}