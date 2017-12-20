<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ipaddress {

	protected $_ci;                 // CodeIgniter instance
	protected $response = '';       // Contains the cURL response for debug
	protected $session;             // Contains the cURL handler for a session
	protected $url;                 // URL of the session
	protected $options = array();   // Populates curl_setopt_array
	protected $headers = array();   // Populates extra HTTP headers
	public $error_code;             // Error code returned as an int
	public $error_string;           // Error message returned as a string
	public $info;                   // Returned after request (elapsed time, etc)

	function __construct($url = '')
	{
		$this->_ci = & get_instance();
		
	}

	public function __call($method, $arguments)
	{
		if (in_array($method, array('simple_get', 'simple_post', 'simple_put', 'simple_delete', 'simple_patch')))
		{
			// Take off the "simple_" and past get/post/put/delete/patch to _simple_call
			$verb = str_replace('simple_', '', $method);
			array_unshift($arguments, $verb);
			return call_user_func_array(array($this, '_simple_call'), $arguments);
		}
	}


	
		
}

/* End of file curl.php */
/* Location: ./application/libraries/curl.php */
