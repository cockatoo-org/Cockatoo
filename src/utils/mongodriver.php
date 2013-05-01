<?php
/**
 * mongodriver.php - 
 *  
 * @access public
 * @package cockatoo
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
require_once (Config::COCKATOO_ROOT.'utils/beak.php');

/**
 * MongoAccess
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class MongoAccess {
  const KEY = 'PER';
  const RETRY_WAIT = 1200000;
  /**
   * Mongo server list
   */
  private $server;
  /**
   * Mongo options
   */
  private $options;
  /**
   * Database name
   */
  private $dbname = null;
  /**
   * Database collection name
   */
  private $collection = null;
  /**
   * Allow to read from slave  
   */
  private $slaveOk;
  /**
   * Mongo driver objects
   */
  private $mongo;
  private $mongodb;
  private $mongocollection;
  /**
   * Constructor
   *
   *   Construct a object.
   *
   * @param int $arg Argument.....
   */
  public function __construct($location,$dbname,$collection,$slaveOk=true,$user=null,$password=null){
    $this->slaveOk     = $slaveOk;
    $this->dbname      = $dbname;
    $this->collection  = $collection;
    $this->options     = array('replicaSet' => false,'persist' => $uuid,'connect' => false);
    if ( $user ) {
      $this->options['username'] = $user;
      $this->options['password'] = $password;
    }
    // Connect string
    $this->server = 'mongodb://';
    $flg = false;
    foreach ( $location as $host => $info ) {
      $this->server .= ($flg?',':'') . $host;
      $flg = true;
      if ( isset($info['replicaSet']) ) {
        $this->options['replicaSet'] = $info['replicaSet'];
      }
    }
    try {
      $this->initMongo(false);
    }catch(\MongoException $e){
      $this->close();
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . $e->getMessage(),$e);
    }
  }

  public function mongoProc($obj,$callback){
    try {
      if ( ! $this->mongodb ) {
        throw new \MongoException('No connection');
      }
      return $obj->$callback($this->mongo,$this->mongodb,$this->mongocollection);
    }catch(\MongoException $e){
      if ( $e->getCode() === 11000 ) {
        // Query errors. ( Nothing to do )
        return false;
      }
      $this->close();
      Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : ' . $e->getMessage(),$e);
      // Retry
      try {
        $this->initMongo(true);
        if ( ! $this->mongodb ) {
          throw new \MongoException('No connection');
        }
        return $obj->$callback($this->mongo,$this->mongodb,$this->mongocollection);
      }catch(\MongoException $e){
        $this->close();
        Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . $e->getMessage(),$e);
      }
    }
    return null;
  }

  private function initMongo($retry){
    if ( $retry ) {
      // usleep(self::RETRY_WAIT); // Cannot wait on the Online system !!!
      Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Retry ! ' );
    }else{
      $uuid = self::KEY.$this->slaveOk;
      $this->mongo = new \Mongo($this->server,$this->options);
    }
    $this->mongo->connect();
//    Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : ' . $this->mongo->getSlave());
    if ( $this->slaveOk ) {
      $this->mongo->setSlaveOkay(true);
    }
    $this->mongodb = $this->mongo->selectDB($this->dbname);
    if ( $this->collection ) {
      $this->mongocollection = $this->mongodb->selectCollection($this->collection);
    }
  }

  private function close(){
    try {
//       if ( $this->mongo) {
//         $this->mongo->close();
//       }
//       $this->mongo = null;
//       $this->mongodb = null;
//       $this->mongocollection = null;
    }catch(\MongoException $e){
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . $e->getMessage(),$e);
    }
  }

  /**
   * Destructor
   *
   *   Destruct a object.
   */
  public function __destruct(){
  }
}

