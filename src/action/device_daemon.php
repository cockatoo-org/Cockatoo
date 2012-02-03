#!/usr/bin/env php
<?php
/**
 * device_daemon.php - Balancing the requests to Action.
 *  
 * @access public
 * @package cockatoo-action
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
$COCKATOO_CONF=getenv('COCKATOO_CONF');
require_once($COCKATOO_CONF);

/**
 * ZeroMQ-device daemon
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class DeviceDaemon {
  /**
   * Front side DSN
   */
  protected $serviceDSN;
  /**
   * Back side DSN
   */
  protected $childDSN;
  /**
   * zeroMQ context
   */
  protected $zmqCtx;
  /**
   * Front side zeroMQ socket
   */
  protected $zmqSockFront;
  /**
   * Back side zeroMQ socket
   */
  protected $zmqSockBack;
  /**
   * Constructor
   *
   *   Construct a object.
   *
   * @param String $serviceDSN Front side
   * @param String $childDSN   Back side
   */
  public function __construct($serviceDSN,$childDSN){
    $this->serviceDSN = $serviceDSN;
    $this->childDSN   = $childDSN;
    $this->zmqCtx       = new \ZMQContext();
    $this->zmqSockFront = new \ZMQSocket($this->zmqCtx, \ZMQ::SOCKET_XREP);
    $this->zmqSockBack  = new \ZMQSocket($this->zmqCtx, \ZMQ::SOCKET_XREQ);
  }
  /**
   * Main loop (zeroMQ device)
   */
  public function main(){
    $this->zmqSockFront->bind($this->serviceDSN);
    $this->zmqSockBack->bind($this->childDSN);
    new \ZMQDevice(\ZMQ::DEVICE_QUEUE,$this->zmqSockFront,$this->zmqSockBack);
  }
}

if ( count($argv) !== 3 ) {
  $msg = <<<_MSG_
Invalid arguments !

Usage:
  device_daemon.php <FRONT-DSN> <BACK-DSN>

Example:
  device_daemon.php tcp://127.0.0.1:9999  ipc://action.foobar.com

_MSG_;
   print $msg;
   var_dump($argv);
   die ();
}
Log::warn('DeviceDaemon start : ' . $argv[1] . ' ' . $argv[2]);
try {
  $daemon = new DeviceDaemon($argv[1],$argv[2]);
  $daemon->main();
}catch(\Exception $e){
  Log::fatal('DeviceDaemon exception : ' . $e->getMessage() ,$e);
}
Log::warn('DeviceDaemon stop : ' . $argv[1] . ' ' . $argv[2]);

