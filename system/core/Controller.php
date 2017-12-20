<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright		Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @copyright		Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	private static $instance;

	/**
	 * Constructor
	 */
	public function __construct($requiredLogin = false)
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');

		$this->load->initialize();
        $this->load->helper('url');
        $this->load->helper('string');
        $this->load->helper('text');
        $this->load->library('session');
		$this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
		$this->load->helper('language');
		
		log_message('debug', "Controller Class Initialized");
		//var_dump($requiredLogin); die;
		if($requiredLogin == true)
		{
			$this->load->library('session_memcached');
			$this->session_memcached->get_userdata();
			if(!isset($this->session_memcached->userdata['info_user']['userID']))
			{
				//redirect();
				echo "<script>window.top.location='" . base_url() . "'</script>";
				die;
			}
			elseif($this->session_memcached->userdata['info_user']['security_method'] == '0' 
					|| $this->session_memcached->userdata['info_user']['phone_status'] == '0'
					|| ($this->session_memcached->userdata['info_user']['countUserbankAcc'] == '0' && REQUIRE_HAVE_BANK_ACCOUNT == '1'))
			{
				//redirect('/transaction_manage');
				//die;
				echo "<script>window.top.location='" . base_url('transaction_manage') . "'</script>";
				die;
			}
		}
		
		//ini_set('display_errors', 'Off');
		//error_reporting(0);
		//define('MP_DB_DEBUG', false);
		
	}

	public static function &get_instance()
	{
		return self::$instance;
	}
}
// END Controller class

/* End of file Controller.php */
/* Location: ./system/core/Controller.php */