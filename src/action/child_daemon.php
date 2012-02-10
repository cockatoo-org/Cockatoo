#!/usr/bin/env php
<?php
/**
 * child_daemon.php - Balancing the requests to Action.
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
require_once(Config::$COCKATOO_ROOT.'utils/beak.php');
\ClassLoader::addClassPath(Config::$COCKATOO_ROOT.'action/actions');
declare(ticks = 1);

/**
 * Action daemon
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class ActionDaemon {
  const PollInterval   = 10000000; // 

  protected $id;
  protected $childDSN;
  protected $maxreq;
  protected $zmqCtx;
  protected $zmqSockData;
  protected $zmqPoll;
  protected $currentAction = null;
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
   * Constructor
   *
   *   Construct a object.
   *
   * @param String $childDSN    Request port
   * @param String $maxreq
   */
  public function __construct($childDSN,$maxreq){
    $this->childDSN    = $childDSN;
    $this->maxreq      = $maxreq;
    $this->zmqCtx      = new \ZMQContext();
    $this->zmqSockData = new \ZMQSocket($this->zmqCtx, \ZMQ::SOCKET_REP);
    $this->zmqPoll = new \ZMQPoll();
  }
  /**
   * Main loop
   *
   *   Send terminate-packet to exit this function.
   */
  public function main(){
    pcntl_sigprocmask(SIG_BLOCK, array(SIGCHLD));
    pcntl_signal ( SIGTERM , array(&$this,"term"));
    $this->zmqSockData->connect($this->childDSN);
    $this->zmqPoll->add($this->zmqSockData,\ZMQ::POLL_IN );
    $readables = array();
    $writables = array();
    try {
      for ( $i = 0 ; $i < $this->maxreq ; ) {
        try {
          $ev = $this->zmqPoll->poll($readables,$writables,self::PollInterval);
        }catch (\ZMQPollException $e){
          continue;
        }
        if ( $ev ) {
          // Do action
          $req = $this->zmqSockData->recv();
          Log::debug(__CLASS__ . '::' . __FUNCTION__ . ' : Recive data  I (' .$i. '/ '.$this->maxreq.' ) : '. $req);
          if ( $this->zmqSockData->getSockOpt(\ZMQ::SOCKOPT_RCVMORE) ) {
            if ( ! $this->currentAction ) {
              list($P,$D,$C,$p,$m,$q,$c) = parse_brl($req);
              $clazz = $C.'\\'.ltrim($p,'/');
              Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Instantiate ' . $clazz);
              $this->currentAction = new $clazz($req);
            }
            continue;
          }
          $context = beak_decode($req);
          $this->currentAction->set($context[0],$context[1]);
          $ret = $this->currentAction->run();
          $this->zmqSockData->send(beak_encode($ret));
          $this->currentAction->postRun();
          $this->currentAction = null;
          $i++;
        }
        if ( $this->termFlg ) {
          Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : Recirved a terminate-signal');
          break;
        }        
      }
      Log::info(__CLASS__ . '::' . __FUNCTION__ . ' Child daemon just came to end ( max request ). : ' . $this->maxreq);
    }catch ( \Exception $e ) {
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . $e->getMessage(),$e);
    }
  }
}

if ( count($argv) !== 3 ) {
  $msg = <<<_MSG_
Invalid arguments !

Usage:
  child_daemon.php <GATEWAY-DSN>  <NUM-REQUEST-TO-DIE>

Example:
  child_daemon.php  1 ipc://action.foobar.com 1000

_MSG_;
   print $msg;
   var_dump($argv);
   die ();
}

Log::warn('ActionDaemon start : ' . $argv[1] . ' ' . $argv[2]);
$daemon = new ActionDaemon($argv[1],$argv[2]);
$daemon->main();
Log::warn('ActionDaemon end : ' . $argv[1] . ' ' . $argv[2]);
