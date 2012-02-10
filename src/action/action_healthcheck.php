#!/usr/bin/env php
<?php
// svn propset svn:keywords "Date Rev Id" action_healthcheck.php
// TZ=Asia/Tokyo phpdoc -t html -d source
/**
 * action_healthcheck.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/10/12
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
$COCKATOO_CONF=getenv('COCKATOO_CONF');
require_once($COCKATOO_CONF);
require_once(Config::$COCKATOO_ROOT.'utils/beak.php');
declare(ticks = 1);

/**
 * ??????????
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class ActionHealthcheck {
  const CHECK_RETRY   = 5;
  const RCV_RETRY     = 50;
  const RCV_TIMEOUT   = 100000;
  const CONN_WAIT     = 100000;
  /**
   * Watching interval (usec)
   */
  const LOOP_SLEEP = 1000000;
  /**
   * Watch count
   */
  protected $count = 0;
  /**
   * zeroMQ
   */
  protected $brl;
  /**
   * zeroMQ
   */
  protected $serviceDSN;
  /**
   * zeroMQ
   */
  protected $zmqCtx;
  /**
   * Watch socket connection
   */
  protected $zmqSockWatch;

  /**
   * Signal flg
   */
  protected $hupFlg = false;
  /**
   * Signal flg
   */
  protected $termFlg = false;
  /**
   * Signal handler (SIGTERM)
   *
   * @param int $no signal number
   */
  function term($no){
    $this->termFlg = true;
  }
  /**
   * Signal handler (SIGHUP)
   *
   * @param int $no signal number
   */
  function hup($no){
    $this->hupFlg = true;
  }

  /**
   * Constructor
   *
   *   Construct a object.
   *
   */
  public function __construct($brl,$serviceDSN,$hupWait){
    $this->brl         = $brl;
    $this->serviceDSN  = $serviceDSN;
    $this->hupWait     = $hupWait;
    // zmq
    $this->zmqCtx      = new \ZMQContext();
    $this->zmqSockWatch  = new \ZMQSocket($this->zmqCtx, \ZMQ::SOCKET_XREQ,uniqid("HC"));
    $this->zmqSockWatch->setSockOpt(\ZMQ::SOCKOPT_RCVBUF,1048576);
  }

  public function main() {
    pcntl_signal ( SIGTERM , array(&$this,"term"));
    pcntl_signal ( SIGHUP , array(&$this,"hup"));
    while(true) {
      if ( $this->hupFlg ) {
        $this->hupFlg = false;
        $this->count = 0;
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : enter hup');
        usleep($this->hupWait);
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : leave hup');
      }
      if ( $this->termFlg ) {
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : enter terminator');
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : leave terminator');
        break;
      }
      if ( $this->healthCheckD() ) {
        break;
      }
      usleep(self::LOOP_SLEEP);
    }
  }
  protected function healthCheckDRcv () {
    for($i = 0 ; $i < self::RCV_RETRY ; $i++ ) {
      usleep(self::RCV_TIMEOUT);
      try {
        return $this->zmqSockWatch->recv(\ZMQ::MODE_NOBLOCK);
      }catch( \ZMQSocketException $e){
      }
    }
    throw new \Exception("Device daemon health-check timeout");
  }
  protected function healthCheckD () {
    $checkbrl = $this->brl . 'Cockatoo/HealthAction';
    Log::debug(__CLASS__ . '::' . __FUNCTION__ . ' : device daemon check : ' . $checkbrl);
    $this->zmqSockWatch->connect($this->serviceDSN);
    usleep(self::CONN_WAIT);
    $this->zmqSockWatch->send("",\ZMQ::MODE_SNDMORE);
    $this->zmqSockWatch->send($checkbrl,\ZMQ::MODE_SNDMORE);
    $this->zmqSockWatch->send(beak_encode(array(array(),array())));
    try { 
      $this->healthCheckDRcv();
      $ret = beak_decode($this->healthCheckDRcv());
      if ( ! isset($ret[2]) ) {
        throw new \Exception("Device daemon health-check timeout");
      }
      $this->count = 0;
    }catch(\Exception $e ) {
      if ( ++$this->count < self::CHECK_RETRY ){
        Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . $e->getMessage() . ' (' . $this->count . ')',$e);
      }else{
        Log::fatal(__CLASS__ . '::' . __FUNCTION__ . ' : ' . $e->getMessage() . ' (' . $this->count . ')',$e);
        return 1;
      }
    }
    return 0;
  }
}

if ( count($argv) !== 4 ) {
  $msg = <<<_MSG_
Invalid arguments !

Usage:
  action_healthcheck.php <BRL> <FRONT-DSN> <BACK-DSN> <HUP-WAIT>

Example:
  action_healthcheck.php  action://news-action/ tcp://127.0.0.1:9999  100000000

_MSG_;
   print $msg;
   var_dump($argv);
   die ();
}

Log::warn('ActionHealthCheck start : ' . $argv[1] . ' ' . $argv[2] . ' ' . $argv[3]);
try {
  $daemon = new ActionHealthCheck($argv[1],$argv[2],$argv[3]);
  $daemon->main();
}catch(\Exception $e){
  Log::fatal('ActionHealthCheck exception : ' . $e->getMessage() ,$e);
}
Log::warn('ActionHealthCheck stop : ' . $argv[1] . ' ' . $argv[2] . ' ' . $argv[3]);

