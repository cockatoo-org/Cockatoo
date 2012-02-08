<?php
/**
 * memcache.php - cache controller
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/02/08
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
namespace Cockatoo;
/**
 * ??????????
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class Memcache {
  public $memcached;
  /**
   * Constructor
   *
   *   Construct a object.
   *
   * @param int $arg Argument.....
   */
  public function __construct($servers){
    $token = '';
    foreach ( $servers as $server ) {
      $token .= $server . ',';
    }
    $persistant_id = md5($token);
    $this->memcached = new \Memcached($persistant_id);
    if ( ! $this->memcached->getServerList() ) {
      $servers = array();
      foreach ( $servers as $server ) {
        $servers []= explode(':',$server);
      }
      $this->memcached->addServers($servers);
    }
  }
  public function get(&$key){
    return $this->memcached->get($key);
  }
  public function set(&$key,&$data){
    return $this->memcached->set($key,$data);
  }
}
