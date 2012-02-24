#!/usr/bin/env php
<?php
// svn propset svn:keywords "Date Rev Id" gateway_healthcheck.php
// TZ=Asia/Tokyo phpdoc -t html -d source
/**
 * gateway_healthcheck.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/10/12
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
require_once(dirname(__FILE__) . '/../def.php');
require_once(Config::COCKATOO_ROOT.'utils/beak.php');

class GatewayHealthcheck {
  const CHECK_RETRY   = 5;
  /**
   * Watching interval (usec)
   */
  const LOOP_SLEEP = 1000000;

  protected $count = 0;
  protected $brl;
  public function __construct($brl){
    $this->brl = $brl;
  }

  protected function healthCheckA(){
    // Watch and restart gateway 
    $checkbrl = $this->brl . 'Cockatoo/HealthAction';
    $ret = BeakController::beakQuery(array($checkbrl));
    if ( ! isset($ret[$checkbrl][2]) ) {
      if ( ++$this->count < self::CHECK_RETRY ){
        Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : Action check failure (' . $this->count. ') ' . $this->brl);
      }else{
        Log::fatal(__CLASS__ . '::' . __FUNCTION__ . ' : Action check failure (' . $this->count. ') ' . $this->brl);
        return 1;
      }
    }else{
      $this->count = 0;
    }
    return 0;
  }
  
  public function main() {
    while(true) {
      usleep(self::LOOP_SLEEP);
      if ( $this->healthCheckA() ) {
        break;
      }
    }
  }
}

if ( count($argv) < 2 ) {
  $msg = <<<_MSG_
Invalid arguments !

Usage:
  gateway_healthcheck.php  <BRL>

Example:
  gateway_healthcheck.php action://foobar.com

_MSG_;
   print $msg;
   var_dump($argv);
   die ();
}
array_shift($argv);
$brl = array_shift($argv);
Log::warn('GatewayHealthcheck start :  ' . $brl,$argv);
try {
  $daemon = new GatewayHealthcheck($brl,$argv);
  $daemon->main();
}catch(\Exception $e){
  Log::fatal('GatewayHealthcheck exception : ' . $e->getMessage() ,$e);
}
Log::warn('GatewayHealthcheck stop :  ' . $brl,$argv);

