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
require_once ('/usr/local/cockatoo/def.php');
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
   * parser obj
   */
  protected $jparser = null;
  /**
   * Directory data file
   *   Data filename : <path>/.meta
   */
  const DIR_FILE = '.meta';
  /**
   * Index directory
   *   Data filename : <path>/.idx/
   */
  const DIR_INDEX = '.idx/';
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
   * Constructor
   * 
   * @see Action.php
   */
  public function __construct(&$brl,&$scheme,&$domain,&$collection,&$path,&$method,&$queries,&$comments,&$arg,&$hide) {
    parent::__construct($brl,$scheme,$domain,$collection,$path,$method,$queries,$comments,$arg,$hide);

    if ( Config::Mode === Def::MODE_DEBUG ) {
      $this->jparser = new JParser("\n",true);
    }else{
      $this->jparser = new JParser();
    }

    if ( isset($this->queries[Beak::Q_FILTERS]) ) {
      $this->filters = explode(',',$this->queries[Beak::Q_FILTERS]);
    }else if ( isset($this->queries[Beak::Q_EXCEPTS]) ) {
      $this->excepts = explode(',',$this->queries[Beak::Q_EXCEPTS]);
    }
    $this->sort = isset($this->queries[Beak::Q_SORT])?$this->queries[Beak::Q_SORT]:'';
    $this->skip = isset($this->queries[Beak::Q_SKIP])?$this->queries[Beak::Q_SKIP]:0;
    $this->limit = isset($this->queries[Beak::Q_LIMIT])?$this->queries[Beak::Q_LIMIT]:Beak::DEFAULT_LIMIT;

    $this->collection_path = Config::COCKATOO_ROOT . self::BeakFileDirectory . '/' . $this->domain . '/' . $this->collection . '/';
  }
  private function path_gen($path){
    if ( !$path or preg_match('@/$@',$path,$matches) !== 0 ) {
      return $this->collection_path.$path.self::DIR_FILE;
    }else{
      return $this->collection_path.$path.self::DATA_FILE;
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
    if ( is_dir($path)){
      if ($dh = opendir($path)) {
        while (($file = readdir($dh)) !== false) {
          if ( preg_match('@^\.meta$@',$file,$matches) != 0 ) {
          }elseif ( preg_match('@^\.\.?@',$file,$matches) != 0 ) {
            continue;
          }
          if ( $isKey ) {
            if ( is_dir($path.'/'.$file) ) {
              $ret = array_merge($ret,$this->listDir($path.'/'.$file,$rel.$file.'/',$isKey));
            }else {
              if ( preg_match('@\.j$@',$file,$matches) != 0 ) {
                $ret [] = substr($rel.$file,0,-2);
              }elseif ( preg_match('@\.meta$@',$file,$matches) != 0 ) {
                $ret [] = substr($rel.$file,0,-5);
              }
            }
          } else {
            if ( is_dir($path.'/'.$file) ) {
              $ret [] = $rel.$file;
            }
          }
        }
        sort($ret);
        closedir($dh);
      }
    }
    return $ret;
  }
  private function getIndex($key = ''){
    $dir = $this->collection_path . self::DIR_INDEX;
    if ( ! $key ) {
      if( is_dir($dir) and $dh = opendir($dir) ) {
        $ret = array();
        while (($index = readdir($dh)) !== false) {
          if ( preg_match('@^\.+$@',$index,$matches) != 0 ) {
            continue;
          }
          $ret []= $index;
        }
        return $ret;
      }
      return array();
    }elseif ( $key === Beak::Q_UNIQUE_INDEX ) {
      $l = $this->listDir($this->collection_path,'',true);
      if ( count($l) ){
        return array_combine($l,array_map(function($n){return array($n);},$l));
      }
      return array();
    }else{
      $ifile = $dir . $key;
      if ( is_file($ifile)) {
        $json = file_get_contents($ifile);
        return $this->jparser->decode($json);
      }
      return array();
    }
  }
  private function setIndex($index_key,$data){
    $index = $this->getIndex($index_key);
    if ( isset($data[$index_key])){
      $index[$data[$index_key]] []= $data[Beak::Q_UNIQUE_INDEX];
      $index[$data[$index_key]] = array_unique($index[$data[$index_key]]);
      $json = $this->jparser->encode($index);
      $ifile = $this->collection_path . self::DIR_INDEX.$index_key;
      file_put_contents($ifile,$json);
    }
  }

  /**
   * Not necessary to implement
   * 
   * @see Action.php
   */
  public function createColQuery(){
    if ( $this->renew ) {
      self::rmdir($this->collection_path);
    }
    self::mkdir($this->collection_path);
    self::mkdir($this->collection_path . self::DIR_INDEX);
    if ( isset($this->queries[Beak::Q_INDEXES]) ){
      foreach(explode(',',$this->queries[Beak::Q_INDEXES]) as $index_key){
        $ifile = $this->collection_path . self::DIR_INDEX.$index_key;
        if ( $this->renew ) {
          file_put_contents($ifile,'{"":[]}');
        }
        foreach($this->listDir($this->collection_path,'',true) as $path){
          $data = $this->getDoc($this->path_gen($path));
          $this->setIndex($index_key,$data);
        }
      }
    }
  }
  /**
   * Get all keys containing the collection.
   *
   * @see Action.php
   */
  public function listKeyQuery() {
    $this->ret =  $this->listDir($this->collection_path,'',true);
  }

  /**
   * Get all collections name
   *
   * @see Action.php
   */
  public function listColQuery() {
    $this->ret =  $this->listDir($this->collection_path,'',false);
  }

  /**
   * Get impl
   */
  private function getDoc($file) {
    if ( is_file($file)) {
      $json=file_get_contents($file);
      $data=$this->jparser->decode($json);
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
    return null;

  }
  /**
   * Range-get document datas
   * 
   * @see Action.php
   */
  public function getrQuery() {
    $this->ret  = array();
    // Parse sort conditions
    $sort_key  = null;
    $sort_type = 0;
    if ( $this->sort and preg_match('@(^.+):([\-1]+)$@',$this->sort,$matches) !== 0) {
      $sort_key = $matches[1];
      $sort_type = (int)$matches[2];
    }
    // Determine the terms of the index-key.
    list($key,$conds) = $this->arg?each($this->arg):array(null,null);
    if ( ! $key ) { 
      // Default index-key is the '_u'.
      // When sorting, Adopt SORT-KEY as the index-key for performance.
      $key = $this->sort?$sort_key:Beak::Q_UNIQUE_INDEX;
      $conds = array('$' => null);
    }
    $index = $this->getIndex($key);
    // Sort condition
    if ( $this->sort ) {
      if ( $sort_key !== $key ){
        throw new \Exception('Sort key enfoced all scan : key => ' . $key . ' sort => ' . $sort_key);
      }
      if ($sort_type === -1 ) {
        $index = array_reverse($index,true);
      }
    }
    
    if ( ! $index ) {
      return; // return empty. (means no match )
    }
    $unique_keys = array(); // Unique key '_u' list
    $count = 0;
    foreach ( $index as $v => $us ){
      if ( ! count($us) ){
        continue;
      }
      $hit = true;
      foreach($conds as $op => $opr) {
        if      ( $op === '$' ) {
          $hit = true;
          continue;   // LOOP : $conds
        }elseif ( $op === '$in' ) {
          $hit = false;
          foreach( $opr as $oprv ) {
            if ( $oprv === $v ) {
              $hit = true;
              break; // LOOP : $opr 
            }
          }
        }elseif ( $op === '$gt' ) {
          if ( $opr < $v ) {
            continue;// LOOP : $conds
          }
          $hit = false;
        }elseif ( $op === '$lt' ) {
          if ( $opr > $v ) {
            continue;// LOOP : $conds
          }
          $hit = false;
        }elseif ( $op === '$gte' ) {
          if ( $opr <= $v ) {
            continue;// LOOP : $conds
          }
          $hit = false;
        }elseif ( $op === '$lte' ) {
          if ( $opr >= $v ) {
            continue;// LOOP : $conds
          }
          $hit = false;
        }else{
          throw new \Exception('Unsupported operation ! op=[ ' . $op . '] ' . $this->brl);
        }
      }
      if ( ! $hit ) {
        continue;// LOOP : $index
      }
      if ($sort_type === -1 ) {
        $us = array_reverse($us);
      }
      $nskip = $this->skip - $count;
      $count += count($us);
      if ( $nskip <= 0 ) {              // add simply
      }elseif ( $nskip < count($us) ){  // boundary issue
        $us = array_slice($us,($nskip-count($us)));
      }else{                            // skip
        continue;// LOOP : $index
      }
      $unique_keys = array_merge($unique_keys,$us);
      $ncount = $count - ($this->skip + $this->limit);
      if ( $ncount == 0 ) {
        break;
      } elseif ( $ncount > 0 ) {
        $unique_keys = array_slice($unique_keys,0,$this->limit);
        break;
      }
    } //$index
    if ( $unique_keys ) {
      foreach($unique_keys as $path){
        $data = $this->getDoc($this->path_gen($path));
        if ( $data !== null ) {
          $this->ret[] = $data;
        }
      }
    }
  }
  /**
   * Get multi document datas
   * 
   * @see Action.php
   */
  public function getaQuery() {
    $this->ret  = null;
    $unique_keys = array();
    foreach ( $this->arg as $key => $cond ) {
      if ( $key === Beak::Q_UNIQUE_INDEX ) {
        $unique_keys = $cond;
        break;
      }
      $index = $this->getIndex($key);
      if ( $index ) {
        foreach($cond as $v){
          if ( isset($index[$v]) ){
            $unique_keys = array_merge($unique_keys,$index[$v]);
          }
        }
        break;
      }
    }
    if ( $unique_keys ) {
      $this->ret = array();
      foreach($unique_keys as $path){
        $data = $this->getDoc($this->path_gen($path));
        if ( $data !== null ) {
          $this->ret[$path] = $data;
        }
      }
    }
  }
  /**
   * Get document data
   *
   * @see Action.php
   */
  public function getQuery() {
    $this->ret = $this->getDoc($this->path_gen($this->path));
  }

  /**
   * MKDIR
   *
   * @param String $path path
   */
  private static function mkDir($path) {
    if ( !is_dir($path) ){
      self::mkDir(dirname($path));
      mkdir($path);
    }
  }

  /**
   * RMDIR
   *
   * @param String $path path
   */
  private static function rmDir ($path) {
    if ( is_dir($path) ){
      if ( is_dir($path) and $dh = opendir($path) ) {
        while (($child = readdir($dh)) !== false) {
          if ( preg_match('@^\.+$@',$child,$matches) != 0 ) {
            continue;
          }
          $child = $path.'/'.$child;
          self::rmDir($child);
        }
        rmdir($path);
      }
    }else{
      if ( is_file($path) ){
        unlink($path);
      }
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
        $prev = $this->jparser->decode($json);
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
    if ( ! $this->op || strcmp($this->op,Beak::COMMENT_KIND_OP_SET)===0 ) {
      if ( ! $this->judgeRev($file,$arg) ){
        return false;
      }
      if ( is_file($file)) {
        $json=file_get_contents($file);
        $prev = $this->jparser->decode($json);
      }
      if ( $prev && strcmp($this->op,Beak::COMMENT_KIND_OP_SET)===0 ) {
        $arg = array_merge($prev,$arg);
      }else{
        $prev = $arg;
      }
      self::mkDir(dirname($file));
    }else{
      if ( is_file($file)) {
        $json=file_get_contents($file);
        $prev = $this->jparser->decode($json);
      }
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
      $arg[Beak::Q_UNIQUE_INDEX] = $path;
    }

    foreach($this->getIndex() as $index_key ){
      $this->setIndex($index_key,$arg);
    }

    $data = $this->jparser->encode($arg);
    return file_put_contents($file,$data)?true:false;
  }
  /**
   * Set document data
   *
   * @see Action.php
   */
  public function setQuery() {
    $this->ret = $this->setDoc($this->path_gen($this->path),$this->path,$this->arg);
  }
  /**
   * Set multi document datas
   *
   * @see Action.php
   */
  public function setaQuery() {
    $this->ret  = array();
    foreach ( $this->arg as $arg ) {
      $path = $arg[Beak::Q_UNIQUE_INDEX];
      $this->ret[$path] = $this->setDoc($this->path_gen($path),$path,$arg);
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
    $this->ret = $this->delDoc($this->path_gen($this->path),$this->arg);
  }
  /**
   * Remove multi documents
   *
   * @see Action.php
   */
  public function delaQuery() {
    $this->ret  = array();
    foreach ( $this->arg[Beak::Q_UNIQUE_INDEX] as $cond ) {
      $path = &$cond;
      $this->ret[$path] = $this->delDoc($this->path_gen($path),null);
    }
  }
  /**
   * Move collection
   */
  public function mvColQuery() {
    $dir = dirname($this->path_gen($this->path));
    if ( is_dir($dir) ){
      $new = dirname($dir) . '/' . $this->queries[Beak::Q_NEWNAME];
      self::rmdir($new);
      rename($dir,$new);
    }
  }
  /**
   * System use only
   *
   */
  public function sysQuery() {
    if ( $this->queries[Beak::Q_SYS] = 'idxs' ) {
      $this->ret = $this->getIndex();
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
/*
  static private function decode(&$str){
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
    if ( Config::Mode === Def::MODE_DEBUG ) {
      return self::encodeD($data);
    }
    
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
  static private function encodeD(&$data){
    $ret;
    if ( is_array($data) ) {
      if ( self::is_hash($data) ) {
        $ret = "{\n";
        $flg = false;
        foreach( $data as $k => $v ) {
          $ret .= ($flg?",\n":'').'"'.$k.'":'.self::encodeD($v);
          $flg = true;
        }
        $ret .= "\n}";
      }else {
        $ret = "[\n";
        $flg = false;
        foreach( $data as $k => $v ) {
          $ret .= ($flg?",\n":'').self::encodeD($v);
          $flg = true;
        }
        $ret .= "\n]";
      }
    } elseif( is_string($data)) {
      // @@@ $s = str_replace('\n',"\n",json_encode($data));
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
*/
}


class JParser {
  public function __construct($esep="",$strn=false) {
    $this->ELEMENT_SEPARATOR = $esep;
    $this->IS_STRING_NLINE   = $strn;
  }
  public function encode(&$d){
    return $this->jencode($d);
  }
  public function decode(&$j){
    $cpj = $j;
    return $this->_jdecode($cpj);
  }

  static function is_hash($d){
    if ( is_array($d)) {
      foreach ( $d as $k => $v ) {
        if ( is_string($k) ) {
          return true;
        }
      }
    }
    return false;
  }

  private function _escape(&$str) {
    $ret = '"';
    for ( $i = 0 ; $i < strlen($str);$i++){
      if ( $str[$i] === '\\' ) {
        $ret .= '\\\\';
        continue;
      }else if ( $str[$i] === "\f" ) {
        $ret .= '\\f';
        continue;
      }else if ( $str[$i] === "\b" ) {
        $ret .= '\\b';
        continue;
      }else if ( $str[$i] === "\t" ) {
        $ret .= '\\t';
        continue;
      }else if ( $str[$i] === "\r" ) {
        $ret .= '\\r';
        continue;
      }else if ( $str[$i] === "\n" ) {
        if ( ! $this->IS_STRING_NLINE ) {
          $ret .= '\\n';
        }else{
          $ret .= "\n";
        }
        continue;
      }else if ( $str[$i] === '"' ) {
        $ret .= '\\"';
        continue;
      }else {
        $bytes = $str[$i] . $str[$i+1] . $str[$i+2];
        if ( preg_match('@^([^\x09\x0A\x0D\x20-\x7E]{3})$@',$bytes,$matches) ){
          $enc = mb_convert_encoding($bytes,'UTF-16','UTF-8');
          $ret .= sprintf('\\u%02x%02x',ord($enc[0]),ord($enc[1]));
          $i += 2;
          continue;
        }else if ( ord($str[$i]) <= 0x1F || ord($str[$i]) >= 0x7F  ) {
          return '"@BIN@'.join(unpack('H*',$str)).'"';
        }
      }
      $ret .= $str[$i];
    }
    return $ret.'"';
    $str = preg_replace_callback('@((?:[^\x09\x0A\x0D\x20-\x7E]{3})+)@',
                                 function ($matches){
                                   $enc = mb_convert_encoding($matches[1],'UTF-16','UTF-8');
                                   $ret = '';
                                   for ( $i = 0; $i < strlen($enc); ){
                                     $ret .= sprintf('\\u%02x%02x',ord($enc[$i++]),ord($enc[$i++]));
                                   }
                                   return $ret;
                                 },
                                 $str);
    return '"' . $str . '"';
  }
  private function _unescape(&$j) {
    $j = substr($j,1);
    if ( strncmp('@BIN@',$j,5) === 0 ) {
      $pos = strpos($j,'"');
      $elem = substr($j,0,$pos);
      $ret = pack('H*',substr($elem,5));
      $j = substr($j,$pos+1);
      return $ret;
    }
    $escape = false;
    $ret = '';
    for ( $i = 0 ; $i < strlen($j);$i++){
      if( $escape ) {
        if ( $j[$i] === '\\' ) {
          $ret .= '\\';
        }else if ( $j[$i] === 'f' ) {
          $ret .= "\f";
        }else if ( $j[$i] === 'b' ) {
          $ret .= "\b";
        }else if ( $j[$i] === 't' ) {
          $ret .= "\t";
        }else if ( $j[$i] === 'r' ) {
          $ret .= "\r";
        }else if ( $j[$i] === 'n' ) {
          $ret .= "\n";
        }else if ( $j[$i] === '"' ) {
          $ret .= '"';
        }else if ( $j[$i] === 'u' ) {
          $ret .= mb_convert_encoding(pack('H*',$j[++$i].$j[++$i].$j[++$i].$j[++$i] ),'UTF-8','UTF-16');
        }else {
        }
        $escape = false;
        continue;
      }
      if ( $j[$i] === '\\' ){
        $escape = true;
        continue;
      }
      if ( $j[$i] === '"' ){
        break;
      }
      $ret .= $j[$i];
    }
    $str = substr($j,0,$i);
    $j = substr($j,$i+1);
    return $ret;
  }

  private function jencode(&$d){
    if  ( is_null($d) ) {
      $ret = 'null';
    }elseif( self::is_hash($d) ) {
      $ret = '{'.$this->ELEMENT_SEPARATOR;
      $flg = false;
      foreach( $d as $k => $v ) {
        $ret .= ($flg?','.$this->ELEMENT_SEPARATOR:'').'"'.$k.'":'.$this->jencode($v);
        $flg = true;
      }
      $ret .= $this->ELEMENT_SEPARATOR.'}';
    }elseif( is_array($d) ) {
      $ret = "[".$this->ELEMENT_SEPARATOR;
      $flg = false;
      foreach ( $d as $v ) {
        $ret .= ($flg?','.$this->ELEMENT_SEPARATOR:'').$this->jencode($v);
        $flg = true;
      }
      $ret .= $this->ELEMENT_SEPARATOR.']';
    }elseif( is_string($d) ) {
      $ret = $this->_escape($d);
    }elseif( is_bool($d) ) {
      $ret = $d?'true':'false';
    }elseif( is_numeric($d) ) {
      $ret = $d;
    }else{
      throw new \Exception('Could not jencode');
    }
    return $ret;
  }
  private function _jdecode(&$str){
    $str = ltrim($str);
    if ( $str[0] === '{' ) {
      $str = substr($str,1);
      $str = ltrim($str," \t\r\n");
      while ( $str[0] !== '}' ) {
        if ( $str[0] !== '"' ) {
          throw new \Exception('Could not jdecode. expects ["] : actual [' .$str[0] .']' );
        }
        // key
        $k = $this->_unescape($str);
        // value
        $str = ltrim($str,": \t\r\n");
        $v = $this->_jdecode($str);
        // add
        $ret[$k] = $v;
      }
      $str = substr($str,1);
    }elseif ( $str[0] === '[' ) {
      $str = substr($str,1);
      $str = ltrim($str," \t\r\n");
      while ( $str[0] !== ']' ) {
        $v = $this->_jdecode($str);
        $ret[] = $v;
      }
      $str = substr($str,1);
    }elseif ( $str[0] === '"' ) {
      $ret = $this->_unescape($str);
    }else{
      // $pos = strpos($str,',]}');
      $elem = strtok($str,',]}');
      $pos = strlen($elem);
      $elem = substr($str,0,$pos);
      $str = substr($str,$pos);
      $elem = trim($elem," \t\r\n");
      if ( $elem === 'null' ) {
        $ret = NULL;
      }elseif ( $elem === 'true' ) {
        $ret = true;
      }elseif ( $elem === 'false' ) {
        $ret = false;
      }else{
        $ret = floatval($elem);
     }
    }
    $str = ltrim($str,", \t\r\n");
    return $ret;
  }
}
