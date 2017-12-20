<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author        tienhm
 *
 * // ------------------------------------------------------------------------
 *
 * /**
 * Session Class
 *
 * Modify CI_Session to write session to redis
 */
class session_memcached
{

    var $sess_encrypt_cookie = FALSE;
    var $sess_use_database = FALSE;
    var $sess_use_memcached = FALSE;
    var $sess_table_name = '';
    var $sess_expiration = 20;
    var $sess_expire_on_close = FALSE;
    var $sess_match_ip = FALSE;
    var $sess_match_useragent = TRUE;
    var $sess_cookie_name = 'epay_session';
    var $cookie_prefix = '';
    var $cookie_path = '';
    var $cookie_domain = '';
    var $cookie_secure = FALSE;
    var $sess_time_to_update = 10;
    var $encryption_key = '';
    var $flashdata_key = 'flash';
    var $time_reference = 'time';
    var $gc_probability = 5;
    var $userdata = array();
    var $CI;
    var $now;
    var $redis;

    /**
     * Session Constructor
     *
     * The constructor runs the session routines automatically
     * whenever the class is instantiated.
     */
    public function __construct($params = array())
    {
        // Set the super object to a local variable for use throughout the class
        $this->CI =& get_instance();

        // Set all the session preferences, which can either be set
        // manually via the $params array above or via the config file
        foreach (array('sess_encrypt_cookie', 'sess_use_database', 'sess_table_name', 'sess_expiration', 'sess_expire_on_close', 'sess_match_ip', 'sess_match_useragent', 'sess_cookie_name', 'cookie_path', 'cookie_domain', 'cookie_secure', 'sess_time_to_update', 'time_reference', 'cookie_prefix', 'encryption_key') as $key) {
            $this->$key = (isset($params[$key])) ? $params[$key] : $this->CI->config->item($key);
        }
	
        if ($this->encryption_key == '') {
            show_error('In order to use the Session class you are required to set an encryption key in your config file.');
        }

        // Load the string helper so we can use the strip_slashes() function
        $this->CI->load->helper('string');

        // Do we need encryption? If so, load the encryption class
        if ($this->sess_encrypt_cookie == TRUE) {
            $this->CI->load->library('encrypt');
        }
		
        // Are we using a database?  If so, load it
        if ($this->sess_use_database === TRUE AND $this->sess_table_name != '') {
            $this->CI->load->database();
        }

        // Added by tienhm
        // Load library redis
        if ($this->sess_use_memcached === TRUE) {
            $this->CI->load->driver('cache');
        } else {
			$this->CI->load->library('redis');
			$this->redis = new CI_Redis();
		}
		
        // Set the "now" time.  Can either be GMT or server time, based on the
        // config prefs.  We use this to set the "last activity" time
        $this->now = $this->_get_time();
		
        // Set the session length. If the session expiration is
        // set to zero we'll set the expiration two years from now.
        if ($this->sess_expiration == 0) {
			
            $this->sess_expiration = (60 * 60 * 24 * 365 * 2);
			log_message('error','Init3 ==== sess_expiration: '.$this->sess_expiration, false, true);
        }
		
        // Set the cookie name
        $this->sess_cookie_name = $this->cookie_prefix . $this->sess_cookie_name;

        // Run the Session routine. If a session doesn't exist we'll
        // create a new one.  If it does, we'll update it.
        if (!$this->sess_read()) {
			//log_message('error', "sess_read tra ve false");
            $this->sess_create();
        } else {
			//log_message('error', "sess_read tra ve true");
            $this->sess_update();
        }

        // Delete 'old' flashdata (from last request)
        $this->_flashdata_sweep();

        // Mark all new flashdata as old (data will be deleted before next request)
        $this->_flashdata_mark();

        // Delete expired sessions if necessary
        $this->_sess_gc();

        log_message('debug', "Session routines successfully run", false, true);
    }

    // --------------------------------------------------------------------

    /**
     * Fetch the current session data if it exists
     *
     * @access    public
     * @return    bool
     */
    function sess_read()
    {
		//log_message('error', 'SESSION READ');
        // Fetch the cookie
        $session = $this->CI->input->cookie($this->sess_cookie_name);
		//log_message('error', 'sess_read get cookie name = ' . $this->sess_cookie_name . ' | ' . print_r($session, true), false, true);
        // No cookie?  Goodbye cruel world!...
        if ($session === FALSE) {
            log_message('error', 'A session cookie was not found.', false, true);
            return FALSE;
        }

        // HMAC authentication
        $len = strlen($session) - 40;

        if ($len <= 0) {
            log_message('error', 'Session: The session cookie was not signed.', false, true);
            return FALSE;
        }

        // Check cookie authentication
        $hmac = substr($session, $len);
        $session = substr($session, 0, $len);

        // Time-attack-safe comparison
        $hmac_check = hash_hmac('sha1', $session, $this->encryption_key);
        $diff = 0;

        for ($i = 0; $i < 40; $i++) {
            $xor = ord($hmac[$i]) ^ ord($hmac_check[$i]);
            $diff |= $xor;
        }

        if ($diff !== 0) {
            log_message('error', 'Session: HMAC mismatch. The session cookie data did not match what was expected.', false, true);
            $this->sess_destroy();
            return FALSE;
        }

        // Decrypt the cookie data
        if ($this->sess_encrypt_cookie == TRUE) {
            $session = $this->CI->encrypt->decode($session);
        }
		//log_message('error', 'session get in browser: ' . $session);
        // Unserialize the session array
		//log_message('error', 'function sess_read call _unserialize SESSION || ' . print_r($session, true), false, true);
        $session = $this->_unserialize($session);

        // Is the session data we unserialized an array with the correct format?
        if (!is_array($session) OR !isset($session['session_id']) OR !isset($session['ip_address']) OR !isset($session['user_agent']) OR !isset($session['last_activity'])) {
            log_message('error', 'khong co session_id, ip_address, user_agent, last_activity', false, true);
			$this->sess_destroy();
            return FALSE;
        }

        // Is the session current?
        if (($session['last_activity'] + $this->sess_expiration) < $this->now) {
			log_message('error', ' session last_activity + sess_expiration < now', false, true);
            $this->sess_destroy();
            return FALSE;
        }

        // Does the IP Match?
        if ($this->sess_match_ip == TRUE AND $session['ip_address'] != $this->CI->input->ip_address()) {
			log_message('error', ' session ip_address = CI->input->ip_address()', false, true);
            $this->sess_destroy();
            return FALSE;
        }

        // Does the User Agent Match?
	   if ($this->sess_match_useragent == TRUE AND trim($session['user_agent']) != trim(substr($this->CI->input->user_agent(), 0, 120))) {
		
			log_message('error', ' session user_agent = CI->input->user_agent()', false, true);
			log_message('error','trim($session[user_agent])====='.trim($session['user_agent']) .'------------:'.trim(substr($this->CI->input->user_agent(), 0, 120)), false, true);
            $this->sess_destroy();
            return FALSE;
        }

        // Is there a corresponding session in the DB?
        if ($this->sess_use_database === TRUE) {
            $this->CI->db->where('session_id', $session['session_id']);

            if ($this->sess_match_ip == TRUE) {
                $this->CI->db->where('ip_address', $session['ip_address']);
            }

            if ($this->sess_match_useragent == TRUE) {
                $this->CI->db->where('user_agent', $session['user_agent']);
            }

            $query = $this->CI->db->get($this->sess_table_name);

            // No result?  Kill it!
            if ($query->num_rows() == 0) {
				log_message('error', ' query->num_rows() == 0', false, true);
                $this->sess_destroy();
                return FALSE;
            }

            // Is there custom data?  If so, add it to the main session array
            $row = $query->row();
            if (isset($row->user_data) AND $row->user_data != '') {
				//log_message('error', 'function sess_read call _unserialize row || ' . print_r($row, true), false, true);
                $custom_data = $this->_unserialize($row->user_data);

                if (is_array($custom_data)) {
                    foreach ($custom_data as $key => $val) {
                        $session[$key] = $val;
                    }
                }
            }
        }

        // Is there a corresponding session in the Redis?
        if ($this->sess_use_memcached === TRUE) {
			log_message('error', 'function sess_read get data in memcache ' . print_r($session, true), false, true);
            $query = $this->CI->cache->memcached->get($session['session_id']);
		} else {
			//log_message('error', 'function sess_read get data in redis ' . print_r($session, true), false, true);
			$query = $this->redis->get($session['session_id']);
		}
            // No result?  Kill it!
        if ($query === null) {
			log_message('error', ' khong get duoc session_id CI->cache->memcached->get($session[session_id])', false, true);
            $this->sess_destroy();
            return FALSE;
        }

        // Is there custom data?  If so, add it to the main session array
        if (isset($query->user_data) AND $query->user_data != '') {
			//log_message('error', 'session red dump data	: ' . print_r($query->user_data, true));
            $custom_data = $this->_unserialize($query->user_data);

            if (is_array($custom_data)) {
                foreach ($custom_data as $key => $val) {
                    $session[$key] = $val;
                }
            }
        }
        

        // Session is valid!
		//log_message('error', 'session read | old data: ' . print_r($session, true));
        $this->userdata = $session;
        unset($session);

        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Write the session data
     *
     * @access    public
     * @return    void
     */
    function sess_write()
    {
        // Are we saving custom data to the DB?  If not, all we do is update the cookie
        if ($this->sess_use_database === TRUE) {
            // set the custom userdata, the session data we will set in a second
            $custom_userdata = $this->userdata;
            $cookie_userdata = array();

            // Before continuing, we need to determine if there is any custom data to deal with.
            // Let's determine this by removing the default indexes to see if there's anything left in the array
            // and set the session data while we're at it
            foreach (array('session_id', 'ip_address', 'user_agent', 'last_activity') as $val) {
                unset($custom_userdata[$val]);
                $cookie_userdata[$val] = $this->userdata[$val];
            }

            // Did we find any custom data?  If not, we turn the empty array into a string
            // since there's no reason to serialize and store an empty array in the DB
            if (count($custom_userdata) === 0) {
                $custom_userdata = '';
            } else {
                // Serialize the custom data array so we can store it
                $custom_userdata = $this->_serialize($custom_userdata);
            }

            // Run the update query
            $this->CI->db->where('session_id', $this->userdata['session_id']);
            $this->CI->db->update($this->sess_table_name, array('last_activity' => $this->userdata['last_activity'], 'user_data' => $custom_userdata));

            // Write the cookie.  Notice that we manually pass the cookie data array to the
            // _set_cookie() function. Normally that function will store $this->userdata, but
            // in this case that array contains custom data, which we do not want in the cookie.
			log_message('error','this->sess_use_database === TRUE', false, true);
            $this->_set_cookie($cookie_userdata);

        } elseif ($this->sess_use_memcached === TRUE) {
            // set the custom userdata, the session data we will set in a second
            // Added by tienhm
            log_message('error', 'CREATE NEW', false, true);
            $cookie_userdata = array();

            // Before continuing, we need to determine if there is any custom data to deal with.
            // Let's determine this by removing the default indexes to see if there's anything left in the array
            // and set the session data while we're at it
            foreach (array('session_id', 'ip_address', 'user_agent', 'last_activity') as $val) {
                $cookie_userdata[$val] = $this->userdata[$val];
            }
            log_message('error', 'NEW DATA TO CACHE: '.print_r($this->userdata, true), false, true);
            // Create memcache key
            // Added by tienhm
            $data_to_cache = $this->_serialize($this->userdata);
            //log_message('error', 'NEW DATA SERIALIZED: '.$data_to_cache, false, true);
			
            $this->CI->cache->memcached->save($this->userdata['session_id'], $data_to_cache, MEMCACHE_TTL);
            // Write the cookie.  Notice that we manually pass the cookie data array to the
            // _set_cookie() function. Normally that function will store $this->userdata, but
            // in this case that array contains custom data, which we do not want in the cookie.
			log_message('error','this->sess_use_memcached === TRUE', false, true);
            $this->_set_cookie($cookie_userdata);
        } elseif ($this->sess_use_memcached === FALSE) {
			//log_message('error','---------ELSE---------- SAVE REDIS');	
			log_message('error', 'CREATE NEW', false, true);
			$cookie_userdata = array();
            foreach (array('session_id', 'ip_address', 'user_agent', 'last_activity') as $val) {
                $cookie_userdata[$val] = $this->userdata[$val];
            }
            //log_message('error', 'NEW DATA TO REDIS: '.print_r($this->userdata, true), false, true);
            $data_to_cache = $this->_serialize($this->userdata);
            //log_message('error', 'NEW DATA SERIALIZED: '.$data_to_cache, false, true);
			
            //$this->CI->cache->memcached->save($this->userdata['session_id'], $data_to_cache, MEMCACHE_TTL);
			
			$this->redis->set($this->userdata['session_id'], $data_to_cache);
			$this->redis->expire($this->userdata['session_id'], USER_INFO_REDIS_TTL);
			//$this->redis->exec();
			
            $this->_set_cookie($cookie_userdata);
            return;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Create a new session
     *
     * @access    public
     * @return    void
     */
    function sess_create()
    {
		//log_message('error','SESSION create', false, true);
        $sessid = '';
        while (strlen($sessid) < 32) {
            $sessid .= mt_rand(0, mt_getrandmax());
        }

        // To make the session ID even more secure we'll combine it with the user's IP
        $sessid .= $this->CI->input->ip_address();

        $this->userdata = array(
            'session_id' => md5(uniqid($sessid, TRUE)),
            'ip_address' => $this->CI->input->ip_address(),
            'user_agent' => substr($this->CI->input->user_agent(), 0, 120),
            'last_activity' => $this->now,
            'user_data' => ''
        );


        // Save the data to the DB if needed
        if ($this->sess_use_database === TRUE) {
            $this->CI->db->query($this->CI->db->insert_string($this->sess_table_name, $this->userdata));
        }

        // Save the data to the memcached server if needed
        if ($this->sess_use_memcached === TRUE) {
            $this->CI->cache->memcached->save($this->userdata['session_id'], $this->userdata, MEMCACHE_TTL);
        } 
		
		if ($this->sess_use_memcached === FALSE) {
			//log_message('error', 'luu session dang nhap: key: ' . $this->userdata['session_id'] . " | value: " . print_r($this->userdata, TRUE), false, true);
			
			$cache_data = $this->_serialize($this->userdata);
			
			$this->redis->set($this->userdata['session_id'], $cache_data);
			$this->redis->expire($this->userdata['session_id'], USER_INFO_REDIS_TTL);
			//$this->redis->exec();
		}
		
        //log_message('error', 'SESSION ID: '.$this->userdata['session_id'], false, true);
        // Write the cookie
        $this->_set_cookie();
    }

    // --------------------------------------------------------------------

    /**
     * Update an existing session
     *
     * @access    public
     * @return    void
     */
    function sess_update()
    {
		//log_message('error','SESSION UPDATE');
		
		//log_message('error', 'USERDATE IN SESS_UPDATE:'.print_r($this->userdata, true));
		
        // We only update the session every five minutes by default
        if (($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now) {
			//log_message('error', 'UPDATE SESSION 5 phut 1 lan, last_activity+5phut = '.($this->userdata['last_activity'] + $this->sess_time_to_update).' thoi gian hien tai = '.$this->now );
            return;
        }
		
		//log_message('error','het 5 phut, tao moi session id');
        // Save the old session id so we know which record to
        // update in the database if we need it
        $old_sessid = $this->userdata['session_id'];
        $new_sessid = '';
        while (strlen($new_sessid) < 32) {
            $new_sessid .= mt_rand(0, mt_getrandmax());
        }

        // To make the session ID even more secure we'll combine it with the user's IP
        $new_sessid .= $this->CI->input->ip_address();

        // Turn it into a hash
        $new_sessid = md5(uniqid($new_sessid, TRUE));

        // Update the session data in the session data array
        //$this->userdata['session_id'] = $new_sessid;
        $this->userdata['last_activity'] = $this->now;

        // _set_cookie() will handle this for us if we aren't using database sessions
        // by pushing all userdata to the cookie.
        $cookie_data = NULL;

        // Update the session ID and last_activity field in the DB if needed
        if ($this->sess_use_database === TRUE) {
            // set cookie explicitly to only have our session data
            $cookie_data = array();
            foreach (array('session_id', 'ip_address', 'user_agent', 'last_activity') as $val) {
                $cookie_data[$val] = $this->userdata[$val];
            }

            $this->CI->db->query($this->CI->db->update_string($this->sess_table_name, array('last_activity' => $this->now, 'session_id' => $new_sessid), array('session_id' => $old_sessid)));
        }

        // Added by tienhm
        // Update the session in memcached if needed
        if ($this->sess_use_memcached === TRUE) {
            // set cookie explicitly to only have our session data
            $cookie_data = array();
            $memcache_data = $this->CI->cache->memcached->get($old_sessid);
            if(is_null($memcache_data)){
				log_message('error', 'khong get duoc memcache data', false, true);
                $memcache_data_decrypted = array();
            } else {
				log_message('error', 'get duoc memcache data = '.print_r($memcache_data, true), false, true);
                $memcache_data_decrypted = $this->_unserialize($memcache_data);
            }
            foreach (array('session_id', 'ip_address', 'user_agent', 'last_activity') as $val) {
                $cookie_data[$val] = $this->userdata[$val];
                $memcache_data_decrypted[$val] = $this->userdata[$val];
            }
            $cache_data = $this->_serialize($memcache_data_decrypted);
			//log_message("error", 'DELETE OLD SESSION: '.$old_sessid.' AND CREATE NEW SESSION: '.$new_sessid);
            $this->CI->cache->memcached->delete($old_sessid);
			
			
			// [2016-08-14] phongwm add: them cache luu session ID
			$memcache_data_decrypted = $this->_unserialize($cache_data);
			if(isset($memcache_data_decrypted['info_user']['userID']))
			{
				$sess_id_cache = $this->CI->cache->memcached->get('USER_SESS_ID'.$memcache_data_decrypted['info_user']['userID']);
				if($sess_id_cache == false || empty($sess_id_cache))
				{
					$this->CI->cache->memcached->save('USER_SESS_ID'.$memcache_data_decrypted['info_user']['userID'], $this->userdata['session_id'], MEMCACHE_TTL);
				}
				else
				{
					$arr_sess_id_cache = explode(",", $sess_id_cache);
					if(!in_array($this->userdata['session_id'], $arr_sess_id_cache))
						$this->CI->cache->memcached->save('USER_SESS_ID'.$memcache_data_decrypted['info_user']['userID'], $sess_id_cache.','.$this->userdata['session_id'], MEMCACHE_TTL);
				}
			}
			// [2016-08-14] phongwm add: them cache luu session ID
			
			
            $this->CI->cache->memcached->save($new_sessid, $cache_data, MEMCACHE_TTL);
        } else {
			$cookie_data = array();
            $redis_data = $this->redis->get($old_sessid);
			$this->redis->expire($old_sessid, USER_INFO_REDIS_TTL);
			//log_message('error', 'old sessid: ' . $old_sessid);
			//log_message('error', 'old sessid data: ' . print_r($redis_data, true), false, true);
            if(is_null($redis_data)){
				//log_message('error', 'khong get duoc redis data');
                $redis_data_decrypted = array();
            } else {
				//log_message('error', 'get duoc redis data = '.print_r($redis_data, true));
                $redis_data_decrypted = $this->_unserialize($redis_data);
            }
            foreach (array('session_id', 'ip_address', 'user_agent', 'last_activity') as $val) {
                $cookie_data[$val] = $this->userdata[$val];
                $redis_data_decrypted[$val] = $this->userdata[$val];
            }
			
            $cache_data = $this->_serialize($redis_data_decrypted);
			//log_message("error", 'DELETE OLD SESSION: '.$old_sessid.' AND CREATE NEW SESSION: '.$new_sessid . " data: " .$cache_data, false, true);
            //$this->redis->del($old_sessid);
			
			// [2016-08-14] phongwm add: data user luu redis
			//log_message('error', 'sess_update | update new session' . print_r($cache_data, true), false, true);
			//$this->redis->set($new_sessid, $cache_data);
			//$this->redis->expire($new_sessid, USER_INFO_REDIS_TTL);
			
			// [2016-08-14] phongwm add: them redis luu session ID
			//log_message('error', 'DATA AFTER DELETE SESSION | ' . print_r($cache_data, true), false, true);
			$redis_data_decrypted = $this->_unserialize($cache_data);
			if(isset($redis_data_decrypted['info_user']['userID']))
			{
				$sess_id_cache = $this->redis->get('USER_SESS_ID'.$redis_data_decrypted['info_user']['userID']);
				if($sess_id_cache == false || empty($sess_id_cache))
				{
					
					$this->redis->set('USER_SESS_ID'.$redis_data_decrypted['info_user']['userID'], $this->userdata['session_id']);
					$this->redis->expire('USER_SESS_ID'.$redis_data_decrypted['info_user']['userID'], MEMCACHE_TTL);
					//$this->redis->exec();
				}
				else
				{
					$arr_sess_id_cache = explode(",", $sess_id_cache);
					if(!in_array($this->userdata['session_id'], $arr_sess_id_cache))
					{
						$this->redis->set('USER_SESS_ID'.$redis_data_decrypted['info_user']['userID'], $sess_id_cache.','.$this->userdata['session_id']);
						$this->redis->expire('USER_SESS_ID'.$redis_data_decrypted['info_user']['userID'], MEMCACHE_TTL);
						//$this->redis->exec();
					}
				}
			}
			
			
            
		}
		
		//log_message('error', 'new session_id = '.$new_sessid.' new data = '.print_r($cache_data, true));
        // Write the cookie
        $this->_set_cookie($cookie_data);
    }

    // --------------------------------------------------------------------

    // Added by tienhm
    // Chuc nang de lay session tren memcache ve load vao userdata
    function get_userdata(){
        if($this->sess_use_memcached === TRUE){
            $session_id = $this->userdata['session_id'];
            //log_message('error','SESSION ID HIEN TAI: '.$session_id);
            $memcache_data = $this->CI->cache->memcached->get($session_id);
            if(is_null($memcache_data)){
               return;
            }
			//log_message('error','dump data memcache - USER GETDATA : '.print_r($memcache_data, true));
            $memcache_data_decrypted = $this->_unserialize($memcache_data);
			//log_message('error','user_name day : '.$memcache_data_decrypted['user_data']['username']);
			
            if($this->userdata['user_agent'] == $memcache_data_decrypted['user_agent'] &&
            $this->userdata['ip_address'] == $memcache_data_decrypted['ip_address']){
                $this->userdata = $memcache_data_decrypted;
				
            }
            return;
        } else {
			$session_id = $this->userdata['session_id'];
            //log_message('error','SESSION ID HIEN TAI: '.$session_id);
			/*
            $redis_data = $this->redis->get($session_id);
			log_message('error', 'get_userdata | data in redis : ' . print_r($redis_data, true));
            if(is_null($redis_data)){
               return;
            }
			*/
			
			for($retry = 1; $retry <= RETRY_GET_UINFO_REDIS; $retry++)
			{
				$redis_data = $this->redis->get($session_id);
				//log_message('error', 'get_userdata lan : ' . $retry . ' | user data: ' . print_r($redis_data, true));
				if(!is_null($redis_data) && !empty($redis_data))
					break;
			}
			
			if(is_null($redis_data)){
				   return;
			}
				
			//log_message('error','dump data redis - USER GETDATA : '.print_r($redis_data, true));
            $redis_data_decrypted = $this->_unserialize($redis_data);
			//log_message('error','user_name day : '.$memcache_data_decrypted['user_data']['username']);
			
            if($this->userdata['user_agent'] == $redis_data_decrypted['user_agent'] &&
            $this->userdata['ip_address'] == $redis_data_decrypted['ip_address']){
                $this->userdata = $redis_data_decrypted;
				
            }
            return;
		}
    }
    /**
     * Destroy the current session
     *
     * @access    public
     * @return    void
     */
    function sess_destroy()
    {
        // Kill the session DB row
        if ($this->sess_use_database === TRUE && isset($this->userdata['session_id'])) {
            $this->CI->db->where('session_id', $this->userdata['session_id']);
            $this->CI->db->delete($this->sess_table_name);
        }

        // Added by tienhm
        // Kill the session memcached
        if ($this->sess_use_memcached === TRUE && isset($this->userdata['session_id'])) {
            $this->CI->cache->memcached->delete($this->userdata['session_id']);
        } elseif($this->sess_use_memcached === FALSE && isset($this->userdata['session_id'])) {
			$this->redis->del($this->userdata['session_id']);
		}

        // Kill the cookie
        setcookie(
            $this->sess_cookie_name,
            addslashes(serialize(array())),
            ($this->now - 31500000),
            $this->cookie_path,
            $this->cookie_domain,
            0
        );

        // Kill session data
        $this->userdata = array();
    }

    // --------------------------------------------------------------------

    /**
     * Fetch a specific item from the session array
     *
     * @access    public
     * @param    string
     * @return    string
     */
    function userdata($item)
    {
        return (!isset($this->userdata[$item])) ? FALSE : $this->userdata[$item];
    }

    // --------------------------------------------------------------------

    /**
     * Fetch all session data
     *
     * @access    public
     * @return    array
     */
    function all_userdata()
    {
        return $this->userdata;
    }

    // --------------------------------------------------------------------

    /**
     * Add or change data in the "userdata" array
     *
     * @access    public
     * @param    mixed
     * @param    string
     * @return    void
     */
    function set_userdata($newdata = array(), $newval = '')
    {
        if (is_string($newdata)) {
            $newdata = array($newdata => $newval);
        }

        if (count($newdata) > 0) {
            foreach ($newdata as $key => $val) {
                $this->userdata[$key] = $val;
            }
        }

        $this->sess_write(NULL, 20);
    }

    // --------------------------------------------------------------------

    /**
     * Delete a session variable from the "userdata" array
     *
     * @access    array
     * @return    void
     */
    function unset_userdata($newdata = array())
    {
        if (is_string($newdata)) {
            $newdata = array($newdata => '');
        }

        if (count($newdata) > 0) {
            foreach ($newdata as $key => $val) {
                unset($this->userdata[$key]);
            }
        }

        $this->sess_write();
    }

    // ------------------------------------------------------------------------

    /**
     * Add or change flashdata, only available
     * until the next request
     *
     * @access    public
     * @param    mixed
     * @param    string
     * @return    void
     */
    function set_flashdata($newdata = array(), $newval = '')
    {
        if (is_string($newdata)) {
            $newdata = array($newdata => $newval);
        }

        if (count($newdata) > 0) {
            foreach ($newdata as $key => $val) {
                $flashdata_key = $this->flashdata_key . ':new:' . $key;
                $this->set_userdata($flashdata_key, $val);
            }
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Keeps existing flashdata available to next request.
     *
     * @access    public
     * @param    string
     * @return    void
     */
    function keep_flashdata($key)
    {
        // 'old' flashdata gets removed.  Here we mark all
        // flashdata as 'new' to preserve it from _flashdata_sweep()
        // Note the function will return FALSE if the $key
        // provided cannot be found
        $old_flashdata_key = $this->flashdata_key . ':old:' . $key;
        $value = $this->userdata($old_flashdata_key);

        $new_flashdata_key = $this->flashdata_key . ':new:' . $key;
        $this->set_userdata($new_flashdata_key, $value);
    }

    // ------------------------------------------------------------------------

    /**
     * Fetch a specific flashdata item from the session array
     *
     * @access    public
     * @param    string
     * @return    string
     */
    function flashdata($key)
    {
        $flashdata_key = $this->flashdata_key . ':old:' . $key;
        return $this->userdata($flashdata_key);
    }

    // ------------------------------------------------------------------------

    /**
     * Identifies flashdata as 'old' for removal
     * when _flashdata_sweep() runs.
     *
     * @access    private
     * @return    void
     */
    function _flashdata_mark()
    {
        $userdata = $this->all_userdata();
        foreach ($userdata as $name => $value) {
            $parts = explode(':new:', $name);
            if (is_array($parts) && count($parts) === 2) {
                $new_name = $this->flashdata_key . ':old:' . $parts[1];
                $this->set_userdata($new_name, $value);
                $this->unset_userdata($name);
            }
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Removes all flashdata marked as 'old'
     *
     * @access    private
     * @return    void
     */

    function _flashdata_sweep()
    {
        $userdata = $this->all_userdata();
        foreach ($userdata as $key => $value) {
            if (strpos($key, ':old:')) {
                $this->unset_userdata($key);
            }
        }

    }

    // --------------------------------------------------------------------

    /**
     * Get the "now" time
     *
     * @access    private
     * @return    string
     */
    function _get_time()
    {
        if (strtolower($this->time_reference) == 'gmt') {
            $now = time();
            $time = mktime(gmdate("H", $now), gmdate("i", $now), gmdate("s", $now), gmdate("m", $now), gmdate("d", $now), gmdate("Y", $now));
        } else {
            $time = time();
        }

        return $time;
    }

    // --------------------------------------------------------------------

    /**
     * Write the session cookie
     *
     * @access    public
     * @return    void
     */
    function _set_cookie($cookie_data = NULL)
    {
        if (is_null($cookie_data)) {
            $cookie_data = $this->userdata;
        }
        // Serialize the userdata for the cookie
        $cookie_data = $this->_serialize($cookie_data);

        if ($this->sess_encrypt_cookie == TRUE) {
            $cookie_data = $this->CI->encrypt->encode($cookie_data);
        } else {
            // if encryption is not used, we provide an md5 hash to prevent userside tampering
            $cookie_data .= hash_hmac('sha1', $cookie_data, $this->encryption_key);
        }
		
			$expire = ($this->sess_expire_on_close === TRUE) ? 0 : $this->sess_expiration + time();
		
        // Set the cookie
        //log_message('error', 'SESS COOKIE NAME: '.$this->sess_cookie_name);
        //log_message('error', 'EXPIRED: '.$this->sess_expiration);
       
		//$expire = 20;
        setcookie(
            $this->sess_cookie_name,
            $cookie_data,
            $expire,
            $this->cookie_path,
            $this->cookie_domain,
            $this->cookie_secure
        );
    }

    // --------------------------------------------------------------------

    /**
     * Serialize an array
     *
     * This function first converts any slashes found in the array to a temporary
     * marker, so when it gets unserialized the slashes will be preserved
     *
     * @access    private
     * @param    array
     * @return    string
     */
    function _serialize($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if (is_string($val)) {
                    $data[$key] = str_replace('\\', '{{slash}}', $val);
                }
            }
        } else {
            if (is_string($data)) {
                $data = str_replace('\\', '{{slash}}', $data);
            }
        }

        return serialize($data);
    }
    // --------------------------------------------------------------------

    /**
     * Unserialize
     *
     * This function unserializes a data string, then converts any
     * temporary slash markers back to actual slashes
     *
     * @access    private
     * @param    array
     * @return    string
     */
    function _unserialize($data)
    {
        //log_message('error', 'dump data: '.print_r($data, true), false, true);
		$data = @unserialize(strip_slashes($data));
		
        if (is_array($data))
        {
            foreach ($data as $key => $val)
            {
                if (is_string($val))
                {
                    $data[$key] = str_replace('{{slash}}', '\\', $val);
                }
            }

            return $data;
        }

        return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
    }

    // --------------------------------------------------------------------

    /**
     * Garbage collection
     *
     * This deletes expired session rows from database
     * if the probability percentage is met
     *
     * @access    public
     * @return    void
     */
    function _sess_gc()
    {
        if ($this->sess_use_database != TRUE) {
            return;
        }

        srand(time());
        if ((rand() % 100) < $this->gc_probability) {
            $expire = $this->now - $this->sess_expiration;

            $this->CI->db->where("last_activity < {$expire}");
            $this->CI->db->delete($this->sess_table_name);

            log_message('debug', 'Session garbage collection performed.', false, true);
        }
    }
	
	function getMemcachedData(){
		//$session = $this->CI->input->cookie($this->sess_cookie_name);
		print_r($this->userdata);
	}


}
// END Session Class

/* End of file Session.php */
/* Location: ./system/libraries/Session.php */
