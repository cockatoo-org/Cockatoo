<?php
/**
 * BeakFile.php - Beak driver : File base storage
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
 * File base storage
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class BeakFile extends Beak {
  /**
   * Result object
   */
  protected $ret = null;
  /**
   * File path
   */
  protected $fullPath;
  /**
   * Directory data file
   *   Data filename : <path>/.meta
   */
  const DIR_FILE = '.meta';
  /**
   * Data file suffix
   *   Data filename : <path>.j
   */
  const DATA_FILE = '.j';
  /**
   * Database directory
   */
  const BeakFileDirectory = 'datasource';
  /**
   * Index name
   */
  protected $uniqueIndex = Beak::Q_UNIQUE_INDEX;

  /**
   * Constructor
   * 
   * @see Action.php
   */
  public function __construct(&$brl,&$scheme,&$domain,&$collection,&$path,&$method,&$queries,&$comments,&$arg,&$hide) {
    parent::__construct($brl,$scheme,$domain,&$collection,$path,$method,$queries,$comments,$arg,$hide);
    $this->uniqueIndex = ($this->queries[Beak::Q_UNIQUE_INDEX])?$this->queries[Beak::Q_UNIQUE_INDEX]:$this->uniqueIndex;
    $base = Config::COCKATOO_ROOT . self::BeakFileDirectory . '/' . $this->domain . '/' . $this->collection . '/' . $this->path;
    $this->fullPath = $this->path_gen($base);
  }
  private function path_gen($base){
    if ( preg_match('@/$@',$base,$matches) === 0 ) {
      return $base . self::DATA_FILE;
    }else{
      return $base . self::DIR_FILE;
    }
  }
  /**
   * Filesystem crawler
   * 
   * @param String $path  ..
   * @param String $rel   ..
   * @param String $isKey ..
   * @return Array  Array(Path)
   */
  private function listDir($path,$rel,$isKey) {
    $ret = array();
    if ( is_dir(self::BeakFileDirectory."/$this->domain/$path")){
      if ($dh = opendir(self::BeakFileDirectory."/$this->domain/$path")) {
        while (($file = readdir($dh)) !== false) {
          if ( preg_match('@^\.\.?(svn)?$@',$file,$matches) != 0 ) { // @@@ svn
            continue;
          }
          if ( $isKey ) {
            if ( is_dir(self::BeakFileDirectory."/$this->domain/$path/$file") ) {
              $ret = array_merge($ret,$this->listDir("$path$file/","$rel$file/",$isKey));
            }else {
              if ( preg_match('@\.j$@',$file,$matches) != 0 ) {
                $ret [] = substr("$rel$file",0,-2);
              }elseif ( preg_match('@\.meta$@',$file,$matches) != 0 ) {
                $ret [] = substr("$rel$file",0,-5);
              }
            }
          } else {
            if ( is_dir(self::BeakFileDirectory."/$this->domain/$path/$file") ) {
              $ret [] = "$rel$file";
            }
          }
        }
        sort($ret);
        closedir($dh);
      }
    }
    return $ret;
  }

  /**
   * Not necessary to implement
   * 
   * @see Action.php
   */
  public function createColQuery(){
    $this->mkdir($this->fullPath);
  }
  /**
   * Get all keys containing the collection.
   *
   * @see Action.php
   */
  public function listKeyQuery() {
    $this->ret =  $this->listDir("/$this->collection/$this->path",'',true);
  }

  /**
   * Get all collections name
   *
   * @see Action.php
   */
  public function listColQuery() {
    $this->ret =  $this->listDir("/$this->collection/$this->path",'',false);
  }

  /**
   * Get impl
   */
  private function getDoc($file) {
    if ( is_file($file)) {
      $json=file_get_contents($file);
      return self::decode($json);
//     if ( $this->qlcKey and $this->ret[$this->qlcKey] ){
//       $list = &$this->ret[$this->qlcKey];
//       $list = array_slice ( $list , $this->qlcIndex , $this->qlcNumber);
//     }
    }
    return null;
  }
  /**
   * Get multi document datas
   * 
   * @see Action.php
   */
  public function getaQuery() {
    $this->ret  = array();
    $base = Config::COCKATOO_ROOT . self::BeakFileDirectory . '/' . $this->domain . '/' . $this->collection . '/';
    foreach ( $this->arg[$this->uniqueIndex] as $cond ) {
      $path = $cond;
      $this->ret[$path] = $this->getDoc($this->path_gen($base . $path));
    }
  }
  /**
   * Get document data
   *
   * @see Action.php
   */
  public function getQuery() {
    $this->ret = $this->getDoc($this->fullPath);
  }

  /**
   * MKDIR
   *
   * @param String $path path
   */
  private function mkDir (&$path) {
    $dir = dirname($path);
    if ( !is_dir($dir) ){
      $this->mkDir($dir);
      mkdir($dir);
    }
  }

  /**
   * Judge commitavle when revision mode.
   * 
   * @return boolean Returns Commitable
   * @todo more effeciently !!! @@@
   */
  private function judgeRev($file,&$arg){
    $prev = null;
    if ( $this->rev ) {
      if ( is_file($file)) {
        $json=file_get_contents($file);
        $prev = self::decode($json);
        if ( $prev[Beak::ATTR_REV] and $arg[Beak::ATTR_REV] and
               $arg[Beak::ATTR_REV] !== $prev[Beak::ATTR_REV] ){
          Log::debug(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'revision judge failure : ' . $arg[Beak::ATTR_REV] .' !== ' . $prev[Beak::ATTR_REV]);
          return false; // Skip
        }
      }
      $arg[Beak::ATTR_REV] = (String)time();
    }
    return true;
  }

  /**
   * Set impl
   */
  private function setDoc($file,$path,&$arg) {
    if ( ! $this->judgeRev($file,&$arg) ){
      return false;
    }
    $this->mkDir($file);
    $arg[$this->uniqueIndex] = $path;
    if ( $this->partial) {
      if ( is_file($file)) {
        $json=file_get_contents($file);
        $prev = self::decode($json);
        if ( $prev ) {
          $arg = array_merge($prev,$arg);
        }
      }
    }
    $data = self::encode($arg);
    return file_put_contents($file,$data)?true:false;
  }
  /**
   * Set document data
   *
   * @see Action.php
   */
  public function setQuery() {
    $this->ret = $this->setDoc($this->fullPath,$this->path,$this->arg);
  }
  /**
   * Set multi document datas
   *
   * @see Action.php
   */
  public function setaQuery() {
    $this->ret  = array();
    $base = self::BeakFileDirectory . '/' . $this->domain . '/' . $this->collection . '/';
    foreach ( $this->arg as $arg ) {
      $path = $arg[$this->uniqueIndex];
      $this->ret[$path] = $this->setDoc($this->path_gen($base . $path),$path,$arg);
    }
  }

  /**
   * Remove document
   */
  private function delDoc($file,$arg) {
    if ( ! $this->judgeRev($file,$arg) ){
      return false;
    }
    if ( is_file($file) ) {
      unlink($file);
    }
    return true;
  }
  /**
   * Remove document
   *
   * @see Action.php
   */
  public function delQuery() {
    $this->ret = $this->delDoc($this->fullPath,$this->arg);
  }
  /**
   * Remove multi documents
   *
   * @see Action.php
   */
  public function delaQuery() {
    $this->ret  = array();
    $base = self::BeakFileDirectory . '/' . $this->domain . '/' . $this->collection . '/';
    foreach ( $this->arg[$this->uniqueIndex] as $cond ) {
      $path = &$cond;
      $this->ret[$path] = $this->delDoc($this->path_gen($base . $path),null);
    }
  }
  /**
   * Move collection
   */
  public function mvColQuery() {
    $dir = dirname($this->fullPath);
    if ( is_dir($dir) ){
      $new = dirname($dir) . '/' . $this->queries[Beak::Q_NEWNAME];
      system ( "rm -rf $new" );
      rename($dir,$new);
    }
  }
  /**
   * Get operation results
   * 
   * @see Action.php
   */
  public function result() {
    return $this->ret;
  }

  static private function decode(&$str){
//     return json_decode($str,true);
    $data = json_decode($str,true);
    self::decode_unpack($data);
    return $data;
  }
  static private function decode_unpack(&$data){
    if ( is_array($data) ) {
      foreach($data as $k => $v){
        self::decode_unpack($data[$k]);
      }
    } elseif( is_string($data) and strncmp('@BIN@',$data,5)=== 0) {
      $data = pack('H*',substr($data,5));
    }else {
    }
  }

  static private function is_hash(&$array){
    foreach( $array as $k => $v  ){
      if ( is_string($k) ) {
        return true;
      }else{
        return false;
      }
    }
  }
  static private function encode(&$data){
    $ret;
    if ( is_array($data) ) {
      if ( self::is_hash($data) ) {
        $ret = '{';
        $flg = false;
        foreach( $data as $k => $v ) {
          $ret .= ($flg?',':'').'"'.$k.'":'.self::encode($v);
          $flg = true;
        }
        $ret .= '}';
      }else {
        $ret = '[';
        $flg = false;
        foreach( $data as $k => $v ) {
          $ret .= ($flg?',':'').self::encode($v);
          $flg = true;
        }
        $ret .= ']';
      }
    } elseif( is_string($data)) {
      $s = json_encode($data);
      if ( $data and $s === 'null' ) {
        $s = '"@BIN@'.join(unpack('H*',$data)).'"';
      }
      $ret = $s;
    } else{
      $ret = json_encode($data);
    }
    return $ret;
  }
}
