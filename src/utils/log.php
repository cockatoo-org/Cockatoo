<?php
/**
 * log.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/01/23
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
namespace Cockatoo;
require_once(Config::COCKATOO_ROOT.'utils/utils.php');
/**
 * Log format. 
 *   <LV> <DATE> <TIME> <PID> <NAME> <MSG>
 */
class Log {
  static private $PROCESS;
  static private $HOST;
  const DELIMITOR = ' ';

  const KEY = 'LOG';
  const DBNAME='cockatoo-log';
  const COLLECTION='current';
  static $mongocollection;
  static $uuid;

  static function log($lv,$msg,$data,$m=false){
    global $argv,$_SERVER;
    if ( ! self::$PROCESS ) {
      self::$PROCESS = $_SERVER['REQUEST_URI'];
      if ( ! self::$PROCESS ) {
        self::$PROCESS = basename($argv[0]);
      }
      self::$HOST=getenv('HOSTNAME');
    }
    $line = $lv . self::DELIMITOR;
    list($ut,$t) = utime();
    $ts = strftime('%F %T,',$t).sprintf('%06.0f',floor($ut*1000000)) . self::DELIMITOR;
    $line .= $ts;
    $line .= Config::$PID . self::DELIMITOR;
    $line .= self::$PROCESS . self::DELIMITOR;
    $line .= $msg;
    if ( Config::$LogDataDump){
      if ( is_string($data) ) {
        $line .= self::DELIMITOR . $data;
      } elseif ( is_object($data) and is_callable(array($data,'__toString')) ) {
        $line .= "\n" . $data;
      }else {
        $line .= "\n" . var_export($data,true);
      }
    }
    $line .= "\n";
    error_log($line, 3, Config::$LogFile);
    // mongolog
    /* Cannot work with efficient.
    if ( Config::$LogOnMongo and $m ) {
      $obj = array(
        'LV' => $lv,
        'TS' => $ts,
        'HOST' => self::$HOST,
        'PID' => Config::$PID,
        'NAME' => self::$PROCESS,
        'MSG' => $msg
        );
      try {
        if ( ! self::$uuid ) {
          $server = 'mongodb://';
          $flg = false;
          foreach ( Config::$LogOnMongo as $location ) {
            $server .= ($flg?',':'') . $location;
            $flg = true;
          }
          self::$uuid = mls_get(self::KEY);
          if ( self::$uuid === 0 ) {
            self::$uuid = uniqid();
            mls_set(self::KEY,self::$uuid);
          }
          $mongo = new \Mongo($server,array('replicaSet' => true,'persist' => self::$uuid));
          if ( $mongo ) {
            $mongodb = $mongo->selectDB(self::DBNAME);
            if ( $mongodb ) {
              self::$mongocollection = $mongodb->selectCollection(self::COLLECTION);
            }
          }
        }
        if ( self::$mongocollection ) {
          self::$mongocollection->save($obj,array('safe' => false , 'fsync' => false ));
        }
      }catch(\MongoException $e){
        self::$uuid = 0;
        mls_set(self::KEY,0); // invalidate pool
      }
    }
    */
  }

  /**
   * Returns a performance object
   *
   * @return array  Returns a performance object
   */
  static function pre_performance(){
    if ( Config::$Loglv & Def::LOGLV_PERFORMANCE ){
      return utime();
    }
    return null;
  }
  /**
   * Output performance log.
   *
   * @param Array  $per  Performance object that returned by pre_performance()
   * @param String $msg  Log message
   * @param Object $data Relevant data
   */
  static function performance($per,$msg,$data=''){
    if ( Config::$Loglv & Def::LOGLV_PERFORMANCE0 ){
      $now = utime();
      $diff = diffutime($now,$per);
      Log::log('P ',sprintf('%4.3f (msec) %4.3f (KB) ',$diff/1000,(memory_get_usage(true)/1000)) . $msg,$data);
    }
  }
  static function performance1($per,$msg,$data=''){
    if ( Config::$Loglv & Def::LOGLV_PERFORMANCE1 ){
      $now = utime();
      $diff = diffutime($now,$per);
      Log::log('P1',sprintf('%4.3f (msec) %4.3f (KB) ',$diff/1000,(memory_get_usage(true)/1000)) . $msg,$data);
    }
  }
  static function performance2($per,$msg,$data=''){
    if ( Config::$Loglv & Def::LOGLV_PERFORMANCE2 ){
      $now = utime();
      $diff = diffutime($now,$per);
      Log::log('P2',sprintf('%4.3f (msec) %4.3f (KB) ',$diff/1000,(memory_get_usage(true)/1000)) . $msg,$data);
    }
  }
  static function performance3($per,$msg,$data=''){
    if ( Config::$Loglv & Def::LOGLV_PERFORMANCE3 ){
      $now = utime();
      $diff = diffutime($now,$per);
      Log::log('P3',sprintf('%4.3f (msec) %4.3f (KB) ',$diff/1000,(memory_get_usage(true)/1000)) . $msg,$data);
    }
  }

  static function debug($msg,$data=''){
    if ( Config::$Loglv & Def::LOGLV_DEBUG0 and Config::Mode === Def::MODE_DEBUG ) 
      Log::log('D ',$msg,$data);
  }
  static function trace($msg,$data=''){
    if ( Config::$Loglv & Def::LOGLV_TRACE0 )
      Log::log('T ',$msg,$data);
  }
  
  static function info($msg,$data=''){
    if ( Config::$Loglv & Def::LOGLV_INFO0 )
      Log::log('I ',$msg,$data,true);
  }
  static function warn($msg,$data=''){
    if ( Config::$Loglv & Def::LOGLV_WARN0 )
      Log::log('W ',$msg,$data,true);
  }
  static function error($msg,$data=''){
    if ( Config::$Loglv & Def::LOGLV_ERROR0 )
      Log::log('E ',$msg,$data,true);
  }
  static function fatal($msg,$data=''){
    if ( Config::$Loglv & Def::LOGLV_FATAL0 )
      Log::log('F ',$msg,$data,true);
  }
}

