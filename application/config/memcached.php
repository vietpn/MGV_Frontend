<?php
 
  if (!defined('BASEPATH')) exit('No direct script access allowed');
 
  $config['memcached'] = array(
          'hostname' => MEMCACHE_SERVER,
          'port'     => MEMCACHE_PORT,
          'weight'   => 1
  );
?>