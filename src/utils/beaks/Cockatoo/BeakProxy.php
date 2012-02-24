<?php
/**
 * BeakProxy.php - Beak driver : BeakQuery through cockatoo-gateway
 *  
 * @access public
 * @package cockatoo-beaks
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
require_once (Config::COCKATOO_ROOT.'utils/beak.php');

/**
 * BeakQuery through cockatoo-gateway
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class BeakProxy extends Beak {
  const PollInterval   = 10000; // 
  /**
   * Result object
   */
  protected $ret = null;
  /**
   * zeroMQ context
   */
  static protected $zmqCtx;
  /**
   * Timeout (usec)
   */
  static protected $timeout;
  /**
   * zeroMQ poll 
   */
  protected $zmqPoll;
  /**
   * zeroMQ socket
   */
  protected $zmqSock;
  /**
   * gateway DSN
   */
  protected $dsn;
  /**
   * Constructor
   * 
   * @see Action.php
   */
  public function __construct(&$brl,&$scheme,&$domain,&$collection,&$path,&$method,&$queries,&$comments,&$arg,&$hide) {
    parent::__construct($brl,$scheme,$domain,&$collection,$path,$method,$queries,$comments,$arg,$hide);
    // Static objects
    if ( ! self::$zmqCtx ) {
      self::$zmqCtx = new \ZMQContext();
    }
    self::$timeout = Config::ActionTimeout;
    // 
    $this->zmqSock = new \ZMQSocket(self::$zmqCtx, \ZMQ::SOCKET_REQ);
    $this->dsn = brl2ipc(Def::IPC_GW_SEGMENT,$this->brl);
    $this->zmqSock->connect($this->dsn);
    $this->zmqPoll = new \ZMQPoll();
  }
  /**
   * T.B.D @@@
   * 
   * @see Action.php
   */
  public function createColQuery(){
    $this->send();
  }
  /**
   * T.B.D @@@
   * 
   * @see Action.php
   */
  public function listKeyQuery(){
    $this->send();
  }
  /**
   * T.B.D @@@
   * 
   * @see Action.php
   */
  public function listColQuery(){
    $this->send();
  }
  /**
   * T.B.D @@@
   * 
   * @see Action.php
   */
  public function setQuery(){
    $this->send();
  }
  /**
   * T.B.D @@@
   * 
   * @see Action.php
   */
  public function setaQuery(){
    $this->send();
  }
  /**
   * T.B.D @@@
   * 
   * @see Action.php
   */
  public function getaQuery(){
    $this->send();
  }
  /**
   * Get all collections name
   *  Async query
   *
   * @see Action.php
   */
  public function getQuery(){
    $this->send();
  }
  /**
   * T.B.D @@@
   *
   * @see Action.php
   */
  public function delQuery() {
    $this->send();
  }
  /**
   * T.B.D @@@
   *
   * @see Action.php
   */
  public function delaQuery() {
    $this->send();
  }
  /**
   * Move collection
   */
  public function mvColQuery() {
    $this->send();
  }
  /**
   * Send
   *
   */
  private function send() {
    $this->zmqSock->send($this->brl,\ZMQ::MODE_SNDMORE);
    $this->zmqSock->send(beak_encode(array($this->arg,$this->hide)));
    $this->zmqPoll->add($this->zmqSock,\ZMQ::POLL_IN);
  }
  /**
   * Get operation results
   *  Async result
   * 
   * @see Action.php
   */
  public  function result() {
    global $COCKATOO_GLFLG;
    while ( true ) {
      // @@@ poll bug ? There is any possibility to return 0 when readable.
      $readables = array();
      $writables = array();

      $start = utime();
      try {
        $ev = $this->zmqPoll->poll($readables,$writables,((self::$timeout<1000)?1000:self::$timeout) );
      }catch (\ZMQPollException $e){
        Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : API query failure (poll): ' . $e->getMessage(),$e );
        $end = utime(true);
        $diff = diffutime($end,$start);
        self::$timeout -= $diff;
        break;
      }
      $end = utime(true);
      $diff = diffutime($end,$start);
      self::$timeout -= $diff;

      if ( in_array($this->zmqSock,$readables) ) {
        // Propabely readable
        $this->ret = beak_decode($this->zmqSock->recv());
        break;
      }
      if ( self::$timeout < 0 ) {
        $COCKATOO_GLFLG = 'Beak query time out (' . $this->brl . ')';
        break;
      }
//      usleep(self::PollInterval);
    }
    if ( ! $this->ret ) {
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : API query failure : ' . $this->brl );
    }
    return $this->ret;
  }
}
