<?php
/**
 * zoo.php - Zookeeper accessor
 *  
 * @access public
 * @package cockatoo
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;

class Zoo {
  const PREFIX = '/cockatoo';
  const DEFAULT_TIMEOUT = 5000;
  const DEFAULT_RETRY   = 2;

  const ESCAPE_SRC = '/';
  const ESCAPE_DST = '\\';

  public  static $zooMap;
  private static $timeout;
  private static $hosts;
  private static $init;
  private static $zoo;

  private static $DEF_ACL = array(array('perms' => \Zookeeper::PERM_ALL, 'scheme' => 'world', 'id' => 'anyone'));

  public static function init($conf) {
    if ( ! self::$init ) {
      if (!isset($conf['hosts']) or !is_array($conf['hosts'])) {
        throw new \Exception('invalid argument. $conf must have a "hosts" key and $conf["hosts"] must be type array');
      }
      $hosts = $conf['hosts'];

      $timeout = self::DEFAULT_TIMEOUT;
      if (isset($conf['timeout']) and is_numeric($conf['timeout']) ){
        $timeout = $conf['timeout'];
      }

      self::$hosts   = $hosts;
      self::$timeout = $timeout;
      self::$init    = true;
      foreach (self::$hosts as $host) {
        self::connect($host);
      }
      usleep($timeout * 1000);
      self::current();
    }
  }
  public static function state($zoo=null) {
    if ( $zoo ) {
      self::$zoo = $zoo;
      return ($zoo->getState() === \Zookeeper::CONNECTED_STATE);
    }
    if (! self::$zoo or self::$zoo->getState() !== \Zookeeper::CONNECTED_STATE) {
      return (self::current() !== null);
    }
    return true;
  }
  public static function getGroups($zoo=null){
    if ( ! self::state($zoo) ) {
      throw new \Exception('Cannot access to  zookeeper');
    }
    $ret = &self::$zoo->getChildren(self::PREFIX);
    if ( $ret ) {
      foreach($ret as $i => $r){
        $ret[$i]= self::unescape($r);
      }
    }
    return $ret;
  }
  public static function getProcesses($group,$zoo=null) {
    if ( ! self::state($zoo) ) {
      throw new \Exception('Cannot access to  zookeeper');
    }
    $groupdir = self::groupdir($group);
    $list = &self::$zoo->getChildren($groupdir);
    return $list;
  }

  public static function regist($group,$hostport) {
    if ( ! self::state() ) {
      throw new \Exception('Cannot access to  zookeeper');
    }
    if (!self::$zoo->exists(self::PREFIX)) {
      self::$zoo->create(self::PREFIX, '', self::$DEF_ACL);
    }
    $groupdir = self::groupdir($group);
    if (!self::$zoo->exists($groupdir)) {
      self::$zoo->create($groupdir, '', self::$DEF_ACL);
    }
    $procnode = $groupdir . '/' . $hostport;
    if (!self::$zoo->exists($procnode)) {
      $res = self::$zoo->create($procnode, '', self::$DEF_ACL, \Zookeeper::EPHEMERAL);
    }
  }
  public static function delete($group, $hostport) {
    if ( ! self::state() ) {
      throw new \Exception('Cannot access to  zookeeper');
    }
    $groupdir = self::groupdir($group);
    $procnode = $groupdir . '/' . $hostport;
    if (self::$zoo->exists($procnode)) {
      self::$zoo->delete($procnode);
    }
  }

  private static function current() {
    foreach( self::$zooMap as $host => $zoo ) {
      $state = $zoo->getState();
      if ( $state === \Zookeeper::OK ) {
        // Nothing to do
        Log::debug(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Zookeeper status is ZOK  ! ' , $host);
      }elseif ( $state === \Zookeeper::EXPIRED_SESSION_STATE ) {
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Some error occured and try to recover (ZSESSIONEXPIRED) ! ' , $host);
        self::connect($host);
      }elseif ( $state === \Zookeeper::CONNECTED_STATE ){
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Found an available zookeeper  ! ' , $host);
        self::$zoo = $zoo;
        return $zoo;
      }else {
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Unbound status ! ' . $state , $host);
      }
    }
    Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Cannot found any available zookeepers  ! ' , self::$hosts);
    self::$zoo = null;
    return null;
  }
  public static function connect($host) {
    if ( isset(self::$zooMap[$host]) ) {
      Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Close connection ' . $host);
      unset(self::$zooMap[$host]);
    }
    $zoo = new \Zookeeper($host, null, self::$timeout);
    $zoo->setDebugLevel(\Zookeeper::LOG_LEVEL_ERROR);
    self::$zooMap[$host] = $zoo;
  }
  private static function groupdir(&$group){
    return self::PREFIX . '/' . self::escape($group);
  }
  private static function escape(&$group) {
    $ret = str_replace(self::ESCAPE_SRC, self::ESCAPE_DST, $group);
    return $ret;
  }
  private static function unescape(&$group) {
    $ret = str_replace(self::ESCAPE_DST, self::ESCAPE_SRC, $group);
    return $ret;
  }
}

// ini_set('display_errors','On');
// $conf = array('hosts' => array('127.0.0.1:12181','127.0.0.1:22181','127.0.0.1:32181'));
// //$conf = array('hosts' => array('172.25.36.54:2181','172.25.36.56:2181','172.25.36.57:2181'));
// Zoo::init($conf);
// for ( $i = 0 ; $i<1000; $i++){
//   if ( $i % 2 ){
//     Zoo::regist('test://foo.bar/','host:999');
//   }else{
//     Zoo::delete('test://foo.bar/','host:999');
//   }
//   $groups = Zoo::getGroups();
//   var_dump($groups);
//   foreach ( $groups as $g ) {
//     $proccess = Zoo::getProcesses($g);
//     var_dump(array($g => $proccess));
//   }
//   sleep(1);
// }
