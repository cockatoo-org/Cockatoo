<?php
/**
 * BeakMemcached.php - Beak driver : MemcachedDB base storage
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
 * MemcachedDB base storage
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class BeakMemcached extends Beak {
  /**
   * Index name
   */
  protected $uniqueIndex;
  protected $memcached;
  /**
   * Constructor
   * 
   * @see Action.php
   */
  public function __construct(&$brl,&$scheme,&$domain,&$collection,&$path,&$method,&$queries,&$comments,&$arg,&$hide) {
    parent::__construct($brl,$scheme,$domain,&$collection,$path,$method,$queries,$comments,$arg,$hide);

    $this->uniqueIndex = isset($this->queries[Beak::Q_UNIQUE_INDEX])?$this->queries[Beak::Q_UNIQUE_INDEX]:Beak::Q_UNIQUE_INDEX;
    $base_brl = $scheme . '://' . $domain . '/';

    $this->beakLocation = BeakLocationGetter::singleton();
    $locations = $this->beakLocation->getLocation(array($base_brl),'');
    if ( ! $locations[$base_brl] ) {
      Log::debug(__CLASS__ . '::' . __FUNCTION__ . ' : No location-info ' . $base_brl);
      return ;
    }

    $this->prefix  = $domain . '/' . (($collection)?$collection.'/':'');
    $token = $base_brl;
    foreach ( $locations[$base_brl] as $location ) {
      $token .= $location . ',';
    }
    $persistant_id = md5($token);
    $this->memcached = new \Memcached($persistant_id);
    
    if ( ! $this->memcached->getServerList() ) {
      $servers = array();
      foreach ( $locations[$base_brl] as $location ) {
        $servers []= explode(':',$location);
      }
      $this->memcached->addServers($servers);
    }

  }
  private function genKey($key) {
    return $this->prefix  . $key;
  }

  public function createColQuery(){
    if ( Config::Mode == Def::MODE_DEBUG ) {
    }
  }
  public function listColQuery() {
    $key = $this->genKey($this->path);
    Log::fatal(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Not supported : ' . $key);
    $cols=array();
    if ( Config::Mode == Def::MODE_DEBUG ) {
      foreach($this->memcached->getServerList() as $server){
        $dp=array(
          0 => array('file','/dev/null','r'),
          1 => array('pipe','w'),
          2 => array('file','/dev/null','a')
          );
        $h = proc_open('memdump --servers=' . $server['host'] .':'.$server['port'],$dp,$pipes);
        if ( $h ) {
          $out=$pipes[1];
          while ($line=fgets($out,1024)){
            $line=chop($line);
             if ( preg_match('@^'.$this->prefix.'([^/]+)/$@',$line,$matches) !== 0 ){
                $cols []= $matches[1];
             }
          }
          proc_close($h);
        }
      }
      $this->ret = $cols;
    }
  }
  public function listKeyQuery() {
    $key = $this->genKey($this->path);
    Log::fatal(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Not supported : ' . $key);
    if ( Config::Mode == Def::MODE_DEBUG ) {
      foreach($this->memcached->getServerList() as $server){
        $dp=array(
          0 => array('file','/dev/null','r'),
          1 => array('pipe','w'),
          2 => array('file','/dev/null','a')
          );
        $h = proc_open('memdump --servers=' . $server['host'] .':'.$server['port'],$dp,$pipes);
        if ( $h ) {
          $out=$pipes[1];
          while ($line=fgets($out,1024)){
            $line=chop($line);
             if ( preg_match('@^'.$this->prefix.'(.*)$@',$line,$matches) !== 0 ){
                $cols []= $matches[1];
             }
          }
          proc_close($h);
        }
      }
      $this->ret = $cols;
    }
  }
  public function getaQuery() {
    $keys = array();
    foreach( $this->arg[$this->uniqueIndex] as $path ) {
      $keys []= $this->genKey($path);
    }
    $ret = $this->memcached->getMulti($keys);
    if ( $ret ) {
      $this->ret = array_combine($this->arg[$this->uniqueIndex],$ret);
    }
  }
  public function getQuery() {
    $key = $this->genKey($this->path);
    $ret = $this->memcached->get($key);
    if ( $ret ) {
      $this->ret = &$ret;
    }
  }


  private function judgeRev(&$key,&$arg){
    if ( $this->rev ) {
      $prev = $this->memcached->get($key);
      if ( $prev === false ) {
        $prev = null;
      }else {
        if ( $prev[Beak::ATTR_REV] and $arg[Beak::ATTR_REV] and
             $arg[Beak::ATTR_REV] !== $prev[Beak::ATTR_REV] ){
          Log::debug(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'revision judge failure : ' . $arg[Beak::ATTR_REV] .' !== ' . $prev[Beak::ATTR_REV]);
          return false; // Skip
        }
      }
      $arg[Beak::ATTR_REV] = (String)time();
      return $prev;
    }
    return true;
  }

  private function setDoc(&$path,&$arg){
    $key = $this->genKey($path);
    $prev = $this->judgeRev($key,$arg);
    if ( $prev === false ){
      return false;
    }
    $arg[$this->uniqueIndex] = $path;
    
    if ( $this->partial) {
      if( $prev === null ) {
        // Nothing to do
      }elseif( $prev === true ) {
        $prev = $this->memcached->get($key);
        if ( $prev ){
          $arg = array_merge($prev,$arg);
        }
      }else {
        $arg = array_merge($prev,$arg);
      }
    }else {
      // Nothing to do
    }
    return $arg;
  }

  public function setQuery() {
    $key = $this->genKey($this->path);
    $arg = $this->setDoc($this->path,$this->arg);
    if ( $arg ) {
      $this->ret = $this->memcached->set($key,$this->arg);
      return;
    }
    $this->ret = false;
  }
  public function setaQuery() {
    $args=array();
    $paths=array();
    foreach ( $this->arg as $arg ) {
      $path = $arg[$this->uniqueIndex];
      $paths []= $path;
      $arg = $this->setDoc($path,$arg);
      if ( $arg ) {
        $key = $this->genKey($path);
        $args[$key] = $arg;
      }
    }
    $this->ret = array_fill_keys($paths,  $this->memcached->setMulti($args));
  }
  public function delQuery() {
    $key = $this->genKey($this->path);
    $prev = $this->judgeRev($key,$this->arg);
    if ( $prev === false ){
      $this->ret = false;
      return;
    }
    $this->ret =  $this->memcached->delete($key);
  }
  public function delaQuery() {
    $paths=array();
    foreach ( $this->arg[$this->uniqueIndex] as $path ) {
      $paths[$path]= false;
      $key = $this->genKey($path);
      $prev = $this->judgeRev($key,$this->arg);
      if ( $prev !== false ){
        $paths[$path]= $this->memcached->delete($key);
      }
    }
    $this->ret = $paths;
  }
  public function mvColQuery() {
    $key = $this->genKey($this->path);
    $dst = $this->genKey($this->queries[Beak::Q_NEWNAME]);
    $prev = $this->memcached->get($key);
    if ( $this->memcached->set($dst,$prev) ) {
      if ( $this->memcached->delete($key) ) {
        $this->ret = true;
        return;
      }
    }
    $this->ret = false;
  }

  /**
   * Get operation results
   * 
   * @see Action.php
   */
  public function result() {
    return $this->ret;
  }
}
