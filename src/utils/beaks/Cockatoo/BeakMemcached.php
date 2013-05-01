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
  protected $memcached;
  /**
   * Constructor
   * 
   * @see Action.php
   */
  public function __construct(&$brl,&$scheme,&$domain,&$collection,&$path,&$method,&$queries,&$comments,&$arg,&$hide) {
    parent::__construct($brl,$scheme,$domain,$collection,$path,$method,$queries,$comments,$arg,$hide);

    if ( isset($this->queries[Beak::Q_FILTERS]) ) {
      $this->filters = explode(',',$this->queries[Beak::Q_FILTERS]);
    }else if ( isset($this->queries[Beak::Q_EXCEPTS]) ) {
      $this->excepts = explode(',',$this->queries[Beak::Q_EXCEPTS]);
    }

    $base_brl = $scheme . '://' . $domain . '/';

    $this->beakLocation = BeakLocationGetter::singleton();
    $locations = $this->beakLocation->getLocation(array($base_brl),'');
    if ( ! $locations[$base_brl] ) {
      Log::debug(__CLASS__ . '::' . __FUNCTION__ . ' : No location-info ' . $base_brl);
      return ;
    }

    $this->prefix  = $domain . '/' . (($collection)?$collection.'/':'');
    $token = $base_brl;
    foreach ( $locations[$base_brl] as $host => $info ) {
      $token .= $host . ',';
    }
    $persistant_id = md5($token);
    $this->memcached = new \Memcached($persistant_id);
    
    if ( ! $this->memcached->getServerList() ) {
      $servers = array();
      foreach ( $locations[$base_brl] as $host => $info ) {
        $servers []= explode(':',$host);
      }
      $this->memcached->addServers($servers);
    }

  }
  private function genKey($key) {
    return $this->prefix  . $key;
  }

  public function createColQuery(){
    if ( Config::Mode === Def::MODE_DEBUG ) {
    }
  }
  public function listColQuery() {
    $key = $this->genKey($this->path);
    Log::fatal(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Not supported : ' . $key);
    $cols=array();
    if ( Config::Mode === Def::MODE_DEBUG ) {
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
    if ( Config::Mode === Def::MODE_DEBUG ) {
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

  private function filterData(&$data){
    if ( $this->filters ) {
      $ret;
      foreach($this->filters as $c){
        if ( isset($data[$c]) ) {
          $ret[$c] = $data[$c];
        }
      }
      return $ret;
    }elseif($this->excepts ) {
      foreach($this->excepts as $c){
        if ( isset($data[$c]) ) {
          unset($data[$c]);
        }
      }
    }
    return $data;
  }

  public function getaQuery() {
    $keyMap = array();
    $keys = array();
    foreach( $this->arg['_u'] as $path ) {
      $key = $this->genKey($path);
      $keys []= $key;
      $keyMap[$key] = $path;
    }
    $this->ret = array();
    $datas = $this->memcached->getMulti($keys);
    foreach($datas as $k => $d){
      $this->ret[$keyMap[$k]] = $this->filterData($d);
    }
//    if ( $ret ) {
//      $this->ret = array_combine($this->arg['_u'],$ret);
//    }
  }
  public function getQuery() {
    $key = $this->genKey($this->path);
    $ret = $this->memcached->get($key);
    if ( $ret ) {
      $this->ret = $this->filterData($ret);
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
    if ( ! $this->op || strcmp($this->op,Beak::COMMENT_KIND_OP_SET)===0 ) {
      $prev = $this->judgeRev($key,$arg);
      if ( $prev === false ){
        return false;
      }
      if( $prev === true ) {
        $prev = $this->memcached->get($key);
      }
      if( $prev && strcmp($this->op,Beak::COMMENT_KIND_OP_SET)===0 ) {
        $arg = array_merge($prev,$arg);
      }else{
        $prev = $arg;
      }
    }else{
      $prev = $this->memcached->get($key);
    }
    if ( $prev === null ) {
      return false;
    }
    if       ( strcmp($this->op,Beak::COMMENT_KIND_OP_INC)===0 ) {
      foreach ( $arg as $k => $v ) {
        $prev[$k] += $v;
      }
      $arg = $prev;
    }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_SET)===0 ) {
      // Nothing to do.
    }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_UNSET)===0 ) {
      foreach ( $arg as $k => $v ) {
        unset($prev[$k]);
      }
      $arg = $prev;
    }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_PUSH)===0 ) {
      foreach ( $arg as $k => $v ) {
        $prev[$k] []= $v;
      }
      $arg = $prev;
    }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_PUSHALL)===0 ) {
      foreach ( $arg as $k => $v ) {
        $prev[$k] = array_merge($prev[$k],$v);
      }
      $arg = $prev;
    }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_POP)===0 ) {
      foreach ( $arg as $k => $v ) {
        if ( $v === 1 ) {
          array_pop($prev[$k]);
        }else if ( $v === -1 ) {
          array_shift($prev[$k]);
        }
      }
      $arg = $prev;
    }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_RENAME)===0 ) {
      foreach ( $arg as $k => $v ) {
        $prev[$v] = $prev[$k];
        unset($prev[$k]);
      }
      $arg = $prev;
    }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_ADDTOSET)===0 ) {
    }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_PULL)===0 ) {
    }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_PULLALL)===0 ) {
    }else{
      $arg['_u'] = $path;
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
      $path = $arg['_u'];
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
    foreach ( $this->arg['_u'] as $path ) {
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
   * System use only
   *
   */
  public function sysQuery() {
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
