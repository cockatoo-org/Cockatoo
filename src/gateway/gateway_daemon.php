#!/usr/bin/env php
<?php
/**
 * gateway_daemon.php - Gateway daemon
 *  
 * @access public
 * @package cockatoo-gateway
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
$COCKATOO_CONF=getenv('COCKATOO_CONF');
require_once($COCKATOO_CONF);

/**
 * Gateway daemon
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class GatewayDaemon {
  protected $frontDSN;
  protected $backDSNs;
  protected $zmqCtx;
  protected $zmqSockFront;
  protected $zmqSockBack;
  /**
   * Constructor
   *
   *   Construct a object.
   *
   * @param String $frontDSN    BRL's DSN
   * @param String $backDSNs    ActionDaemon's DSN
   */
  public function __construct($frontDSN,$backDSNs){
    $this->frontDSN     = $frontDSN;
    $this->backDSNs     = $backDSNs;
    $this->zmqCtx       = new \ZMQContext();
    $this->zmqSockFront = new \ZMQSocket($this->zmqCtx, \ZMQ::SOCKET_XREP);
    $this->zmqSockBack  = new \ZMQSocket($this->zmqCtx, \ZMQ::SOCKET_XREQ);
  }
  /**
   * Main loop (zeroMQ device)
   */
  public function main(){
    $this->zmqSockFront->bind($this->frontDSN);
    foreach($this->backDSNs as $backDSN ) {
      $this->zmqSockBack->connect($backDSN);
    }
    new \ZMQDevice(\ZMQ::DEVICE_QUEUE,$this->zmqSockFront,$this->zmqSockBack);
  }
}

if ( count($argv) < 3 ) {
  $msg = <<<_MSG_
Invalid arguments !

Usage:
  gateway_daemon.php  <FRONT-DSN> <BACK-DSN> [<BACK-DSN> ...]

Example:
  gateway_daemon.php action://foobar.com  tcp://127.0.0.1:9997 tcp://127.0.0.1:9998 tcp://127.0.0.1:9999

_MSG_;
   print $msg;
   var_dump($argv);
   die ();
}
array_shift($argv);
$front = array_shift($argv);
Log::warn('GatewayDaemon start :  ' . $brl,$argv);
try {
  $daemon = new GatewayDaemon($front,$argv);
  $daemon->main();
}catch(\Exception $e){
  Log::fatal('GatewayDaemon exception : ' . $e->getMessage() ,$e);
}
Log::warn('GatewayDaemon stop :  ' . $brl,$argv);

