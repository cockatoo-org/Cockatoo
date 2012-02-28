<?php
/**
 * beak.php - beak implements
 *  
 * @access public
 * @package cockatoo-utils
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
\ClassLoader::addClassPath(Config::COCKATOO_ROOT.'utils/beaks');

/**
 * BRL parser
 *
 *   Return object type:
 *   array (
 *    [0] => String,         // scheme
 *    [1] => String,         // domain
 *    [2] => String,         // collection
 *    [3] => String,         // path
 *    [4] => String,         // method
 *    [5] => String,         // unparsed queries
 *    [6] => Array(String)   // comments
 *   )
 *
 *   ex > 
 *    list($S,$D,$C,$p,$m,$q,$c) = parse_brl($brl);
 *
 * @param String $brl BRL string
 * @return Array parsed object
 */
function parse_brl($brl) {
  # <scheme>://<domein>/<collection></path><?queries><#comment>
  if ( preg_match('@^([a-z]+)://([^/\?&#]+)/((?:[^/\?&#]+)?)((?:/[^\?&#]*)?)((?:\?[^&#]+)?)((?:&[^#]+)?)(#.*)?$@', $brl , $matches ) === 0 ) {
    throw new \Exception('Unexpect BRL:' . $brl);
  }
  array_shift($matches);
  $matches[4] = $matches[4]?substr($matches[4],1):Beak::M_GET;
  $matches[5] = isset($matches[5])?urldecode($matches[5]):'';
  $matches[6] = isset($matches[6])?explode('#',substr(urldecode($matches[6]),1)):array();
  return $matches;
}

/**
 * BRL query parser
 *
 *   ex > 
 *    list($S,$D,$C,$p,$m,$q,$c) = parse_brl($brl);
 *    $queries = parse_brl_query($q);
 *
 * @param String $brl BRL string
 * @return Array queries hash ([key] => value)
 */
function parse_brl_query($query) {
  $ret = array();
  if ( preg_match_all('@&([^=]+)=((?:\'[^\']*\')|(?:"[^\']*")|(?:[^&]*))@', $query , $matches ) !== 0 ) {
    foreach(array_keys($matches[1]) as $i ) {
      $k = $matches[1][$i];
      $v = trim($matches[2][$i],'\'"');
      if ( strncmp($k,'[]',2) === 0 ){
        $k = substr($k,2);
        if ( !isset($ret[$k]) ) {
          $ret[$k] = array();
        }
        $ret[$k] []= $v;
      }else {
        $ret[$k] = $v;
      }
    }
  }
  return $ret;
}

/**
 * IPC generator
 *
 * @param String $segment prefix
 * @param String $brl     BRL string
 * @return String IPC path
 */
function brl2ipc($segment,$brl) {
  return 'ipc://' . brl2file($segment,$brl);
}

/**
 * IPC generator
 *
 * @param String $segment prefix
 * @param String $brl     BRL string
 * @return String IPC path
 */
function brl2file($segment,$brl) {
  list($S,$D,$C,$p,$m,$q,$c) = parse_brl($brl);
  return Config::IPCDirectory . "/$segment.$S.$D";
}

/**
 * BRL generator
 *
 * @param String $scheme     BRL scheme
 * @param String $prefox     BRL domain prefix
 * @param String $collection BRL collection
 * @param String $path       BRL path
 * @param String $method     BRL method
 * @param Array  $queries    BRL queries
 * @param Array  $comments   BRL comments
 * @return String BRL
 */
function brlgen($scheme, $prefix, $collection, $path, $method, $queries=array(), $comments=array()) {
  if ( ! array_key_exists($scheme,Config::$BEAKS) ) {
    throw new \Exception('Unknown BEAK scheme:' . $scheme);
  }
  $domain = $prefix . Def::BD_SEPARATOR . Config::$SYS_BEAKS[$scheme];

  $brl = $scheme . '://' . $domain . '/';
  if ( $collection ) {
    if ( strncmp($path,'/',1) === 0) {
      $path = substr($path,1);
    }
    $brl .= $collection . '/' . $path;
  }
  if ( $method ) {
    $q = '';
    foreach ( $queries as $k => $v ) {
      $q .= '&' . urlencode($k) . '=' . urlencode($v);
    }
    $c = '';
    foreach ( $comments as $comment ) {
      $c .= '#' . urlencode($comment);
    }
    
    $brl .= '?' . $method . $q . $c;
  }
  return $brl;
}


if ( Config::$UseMemcache ) {
  require_once(Config::COCKATOO_ROOT.'utils/memcache.php');
}
if ( Config::$UseZookeeper ) {
  require_once(Config::COCKATOO_ROOT.'utils/zoo.php');
}
/**
 * Beak Location getter (singleton)
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class BeakLocationSetter {
  /**
   * Singleton object
   */
  protected static $instance = null;
  /**
   * Singleton
   *
   */
  private function __construct(){
    if ( Config::$UseZookeeper ) {
      Zoo::init(array('hosts' => Config::$UseZookeeper ));
    }
  }
  /**
   * Regist to Zookeeper
   *
   * @param String $brl BRL => $scheme://$domain/
   * @param String $ipport Location => $ip:$port
   */
  public function regist($brl,$ipport){
    if ( Config::$UseZookeeper ) {
      Zoo::regist($brl,$ipport);
    }    
  }
  /**
   * Remeve from Zookeeper
   *
   * @param String $brl BRL => $scheme://$domain/
   * @param String $ipport Location => $ip:$port
   */
  public function delete($brl,$ipport){
    if ( Config::$UseZookeeper ) {
      Zoo::delete($brl,$ipport);
    }    
  }    
  /**
   * Singleton
   *
   */
  public static function singleton() {
    if ( ! self::$instance ) {
      self::$instance = new BeakLocationSetter();
    }
    return self::$instance;
  }
}

/**
 * Beak Location getter (singleton)
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class BeakLocationGetter {
  /**
   * Singleton object
   */
  protected static $instance = null;
  /**
   * Zookeeper cache mtime
   */
  protected $zooCacheMtime = 0;
  /**
   * Zookeeper cache
   */
  protected $zooCache = array();

  /**
   * Get location info
   *  
   *  Return:
   *   Array ( [group-name] => array ( $prefix . $ip . ':' . $port ))
   *
   *
   * @param Array $targetGroups Group name prefixes
   * @param String Prefix scheme
   * @return Array Returns location info
   */
  public function getLocation($targetGroups,$prefix = 'tcp://'){
    $ret = array();
    // Static locations
    foreach (Config::$BeakLocation as $group => $nodes ) {
      foreach ($targetGroups as $g  ) {
        if ( strncmp($group,$g,strlen($g))===0 ) {
          foreach ( $nodes as $node ) {
            $ret[$group] []= $prefix . $node;
          }
        }
      }
    }
    // Dynamic locations ( from Zookeepr )
    if ( Config::$UseZookeeper ) {
      clearstatcache(Config::$ZookeeperCacheFile);
      if ( is_file(Config::$ZookeeperCacheFile)) { 
        list($dev, $ino, $mode, $nlink, $uid, $gid, $rdev, $size, $atime, $mtime, $ctime, $blksize, $blocks) = lstat(Config::$ZookeeperCacheFile);
        if ( $mtime != $this->zooCacheMtime ) {
          Log::info(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Update zoo cache !');
          $json = file_get_contents(Config::$ZookeeperCacheFile);
          $this->zooCache = json_decode($json,true);
          $this->zooCacheMtime = $mtime;
        }
      }
      if ( $this->zooCache ) {
        foreach($this->zooCache as $group => $nodes ) {
          foreach ($targetGroups as $g  ) {
            if ( strncmp($group,$g,strlen($g))===0 ) {
              if ( count($nodes) > 0 ){
                foreach ( $nodes as $node ) {
                  $ret[$group] []= $prefix . $node;
                }
              }
              break;
            }
          }
        } 
      }
    }
    return $ret;
  }
  /**
   * Singleton
   *
   */
  public static function singleton() {
    if ( ! self::$instance ) {
      self::$instance = new BeakLocationGetter();
    }
    return self::$instance;
  }
}

/**
 * Beak packer
 */
abstract class BeakPacker {
  public static $instance = null;
  abstract public function pack($arg);
  abstract public function unpack($arg);
}
class DefaultBeakPacker extends BeakPacker {
  public function pack($arg){
    return serialize($arg);
  }
  public function unpack($arg){
    return unserialize($arg);
  }
}

/**
 * Beak object packer
 *
 * @param Array $arg target
 * @return mixed packed object
 */
function beak_encode($arg){
  if ( ! BeakPacker::$instance ){
    $clazz = Config::BeakPacker;
    BeakPacker::$instance = new $clazz();
  }
  return BeakPacker::$instance->pack($arg);
}

/**
 * Beak object unpacker
 *
 * @param mixed packed object
 * @return Array object
 */
function beak_decode($arg) {
  if ( ! BeakPacker::$instance ){
    $clazz = Config::BeakPacker;
    BeakPacker::$instance = new $clazz();
  }
  return BeakPacker::$instance->unpack($arg);
}

/**
 * Beak base class
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
abstract class Beak {
  /**
   * Beak method definition
   */
  const M_GET        = 'get';
  /**
   * Beak method definition
   */
  const M_GET_ARRAY  = 'getA';
  /**
   * Beak method definition
   */
  const M_SET        = 'set';
  /**
   * Beak method definition
   */
  const M_SET_ARRAY  = 'setA';
  /**
   * Beak method definition
   */
  const M_COL_LIST   = 'cols';
  /**
   * Beak method definition
   */
  const M_KEY_LIST   = 'keys';
  /**
   * Beak method definition
   */
  const M_CREATE_COL = 'ccol';
  /**
   * Beak method definition
   */
  const M_DEL        = 'del';
  /**
   * Beak method definition
   */
  const M_DEL_ARRAY  = 'delA';
  /**
   * Beak method definition
   */
  const M_MV_COL     = 'mcol';

  /**
   * T.B.D
   */
  const ATTR_REV     = '@R';

  const ATTR_BIN     = '*';
//   /**
//    * T.B.D
//    */
//   const Q_LC_KEY     = '_k';
//   /**
//    * T.B.D
//    */
//   const Q_LC_INDEX   = '_i';
//   /**
//    * T.B.D
//    */
//   const Q_LC_NUMBER  = '_n';
  /**
   * Beak object's unique-key column
   */
  const Q_UNIQUE_INDEX  = '_u';
  /**
   * Beak object's index column
   */
  const Q_INDEXES       = '_is';
  /**
   * Beak MV_COL target
   */
  const Q_NEWNAME       = '_new';
  /**
   * Beak comment definition
   *
   *  Drop collection when create a collection
   */
  const COMMENT_KIND_RENEW      = 'renew';
  /**
   * Beak comment definition
   *
   *  Commit with revision 
   */
  const COMMENT_KIND_REV        = 'rev';
  /**
   * Beak comment definition
   *
   *  Commit with revision 
   */
  const COMMENT_KIND_CACHE      = 'cache';
  /**
   * Beak comment definition
   *
   *  Commit with revision 
   */
  const COMMENT_KIND_CACHE_EXP  = 'cexp';
  const DEFAULT_CACHE_EXPIRE    = 300; // 5 minute
  /**
   * Beak comment definition
   *
   *  Throw exception when beak result is NULL
   */
  const COMMENT_KIND_CRITICAL   = 'critical';
  /**
   * Beak comment definition
   *
   *   Replace document when 'set' method.
   */
  const COMMENT_KIND_PARTIAL    = 'partial';
  /**
   * Beak comment definition
   */
  const COMMENT_KIND_REPEATABLE = 'repeatable'; // @@@ yet
  /**
   * Beak comment definition
   *
   *   No sync write.
   */
  const COMMENT_KIND_NSYNC      = 'nsync';
  /**
   * Beak comment definition
   *
   *   Get fresh data.
   */
  const COMMENT_KIND_FRESH      = 'fresh';
  public    $brl;
  protected $scheme;
  protected $domain;
  protected $collection;
  protected $path;
  public    $method;
  public    $queries;
  public    $comments;
  public    $arg;
  public    $hide;
  public    $critical;
  public    $renew;
  public    $rev;
  public    $cacheable;
//  protected $qlcKey;
//  protected $qlcIndex;
//  protected $qlcNumber;

  public    $cache_hit;

  /**
   * Constructor
   *
   *   Construct a object.
   *
   * @param String $brl  BRL
   * @param String $scheme     BRL scheme
   * @param String $domain     BRL domain 
   * @param String $collection BRL collection
   * @param String $path       BRL path
   * @param String $method     BRL method
   * @param String $queries    BRL unparsed queries
   * @param Array  $comments   BRL comments
   * @param String $arg        
   * @param String $hide        
   */
  public function __construct(&$brl,&$scheme,&$domain,&$collection,&$path,&$method,&$queries,&$comments,&$arg,&$hide) {
    $this->brl        = $brl;
    $this->scheme     = $scheme;
    $this->domain     = $domain;
    $this->collection = $collection;
    $this->path       = ltrim($path,'/ ');
    $this->method     = $method;
    if ( $queries ) {
      $this->queries  = parse_brl_query($queries);
//       $this->qlcKey   = $this->queries[Beak::Q_LC_KEY];
//       $this->qlcIndex = $this->queries[Beak::Q_LC_INDEX];
//       $this->qlcNumber= $this->queries[Beak::Q_LC_NUMBER];
    }
    $this->comments  = $comments;
    $this->arg       = $arg;
    $this->hide      = $hide;
    $this->critical  = in_array(self::COMMENT_KIND_CRITICAL,$comments);
    $this->renew     = in_array(self::COMMENT_KIND_RENEW,$comments);
    $this->rev       = in_array(self::COMMENT_KIND_REV,$comments);
    $this->cacheable = in_array(self::COMMENT_KIND_CACHE,$comments);

    $this->cache_hit = null;
    $this->partial   = in_array(self::COMMENT_KIND_PARTIAL,$comments);
    $this->nsync     = in_array(self::COMMENT_KIND_NSYNC,$comments);
    $this->fresh     = in_array(self::COMMENT_KIND_FRESH,$comments);
  }
  /**
   * craete collection
   *
   */
  abstract public function createColQuery();
  /**
   * Get all collections name
   *
   *    Return Array(String) as collection-name ( call result() to get this)
   */
  abstract public function listColQuery();
  /**
   * Get all keys containing the collection.
   *
   *    Return Array(String) as key-name ( call result() to get this)
   */
  abstract public function listKeyQuery();
  /**
   * Set document data
   *
   *    Updating only difference of column is default.
   *    
   *    'partial'
   *       You can use option. when you want to replace partial of document.
   *  
   *    return Array keys ( call result() to get this)
   */
  abstract public function setQuery();
  /**
   * Set multi document datas
   *
   */
  abstract public function setaQuery();
  /**
   * Get document data
   *
   */
  abstract public function getQuery();
  /**
   * Get multi document datas
   *
   */
  abstract public function getaQuery();
  /**
   * Remove document
   *
   */
  abstract public function delQuery();
  /**
   * Remove multi documents
   *
   *  Revision is not supported !!!
   */
  abstract public function delaQuery();
  /**
   * Move collection
   *
   */
  abstract public function mvColQuery();
  /**
   * Get operation results
   * 
   *  @return mixed Returns result
   */
  abstract public  function result();
}


/**
 * Beak operation (singleton)
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class BeakController {
  /**
   * Singleton object
   */
  protected static $instance = null;
  /**
   *
   */
  protected $cachectrl=null;

  /**
   * Beak operation entry-point
   *
   *   Convert from 'get' to 'gets' to improve a query's performance.
   *
   *   Query-string and Comments are mostly unsupported without 'critical' comment.
   *
   * @param Array $brls Array($brl)
   * @param Array $classes Be able to specifiy the Beak-drivers @see config.php
   */
  public static function beakGetsQuery($brls,$classes=array()) {
    self::singleton();

    $beaks = array();
    $getsQuery = array();
    $orgQuery = array();
    foreach ( $brls as $brl ) {
      list($S,$D,$C,$p,$m,$q,$c) = parse_brl($brl);
      if ( strcmp($m,Beak::M_GET) !== 0 ) {
        throw new \Exception('Unsupported BRL method !  beakGetsQuery allowd only get method :' . $brl);
      }
      $D = explode(Def::BD_SEPARATOR,$D);
      $baseBrl = brlgen($S,$D[0],$C,'',Beak::M_GET_ARRAY);
      $p = ltrim($p,'/');
      $getsQuery[$baseBrl][Beak::Q_UNIQUE_INDEX] [] = $p;

      $orgQuery[$baseBrl][$brl] = array( 'P' => $p , 'C' => $c);
    }
    $queries = array();
    foreach( $getsQuery as $baseBrl => $arg ) {
      $hide = array();
      $beaks[$baseBrl] = self::$instance->prepareQuery($baseBrl,$arg,$hide,$classes);
    }

    $per = Log::pre_performance();

    try {
      foreach ( $beaks as $brl => $beak ) {
        self::$instance->query($beak);
      }

      foreach ( $beaks as $brl => $beak ) {
        $datas[$beak->brl] = self::$instance->result($beak);
      }
    }catch(\Exception $e){
      Log::performance3($per,'BeakController::beakGetsQuery (EX) : ' . implode('  ',$brls) );
      throw $e;
    }
    Log::performance3($per,'BeakController::beakGetsQuery : ' . implode('  ',$brls) );

    $ret=array();
    foreach($orgQuery as $baseBrl => $orgs ){
      foreach($orgs as $brl => $org ){
        $r = &$datas[$baseBrl][$org['P']];
        if ( $r === null and in_array(Beak::COMMENT_KIND_CRITICAL,$org['C'])){
          // @@@ Should I define the specific type exception to build the recover-code more easily ? 
          throw new \Exception('The critical BEAK failed ! :' . $org['BRL']); 
        }
        $ret[$brl] = $r;
      }
    }
    return $ret;
  }

  /**
   * Beak operation entry-point
   *
   * @param Array $brlTuples Array($brl) when 'get' operation, Array(Array($brl,$data)) when 'set' operation
   * @param Array $classes Be able to specifiy the Beak-drivers @see config.php
   */
  public static function beakQuery($brlTuples,$classes=array()) {
    self::singleton();

    $beaks = array();
    foreach ( $brlTuples as $brlTuple ) {
      $brl    = null;
      $arg    = null;
      $hide   = null;
      if ( ! is_array($brlTuple) ) {
        $brl = $brlTuple;
      }else {
        $brl  = &$brlTuple[0];
        $arg  = &$brlTuple[1];
        if( isset($brlTuple[2])) { 
          $hide = &$brlTuple[2];
        } else { 
          $hide = array(); 
        }
      }
      $beaks[$brl] = self::$instance->prepareQuery($brl,$arg,$hide,$classes);
    }
    $per = Log::pre_performance();
    try {
      foreach ( $beaks as $brl => $beak ) {
        self::$instance->query($beak);
      }
      $ret = array();
      foreach ( $beaks as $brl => $beak ) {
        $ret[$brl] = self::$instance->result($beak);
      }
    }catch(\Exception $e){
      Log::performance2($per,'BeakController::beakQuery (EX) : ' . implode('  ',array_keys($beaks)) );
      throw $e;
    }
    Log::performance2($per,'BeakController::beakQuery : ' . implode('  ',array_keys($beaks)) );
    return $ret;
  }

  /**
   * Singleton
   *
   */
  private function __construct(){
    if ( Config::$UseMemcache ) {
      $this->cachectrl = new Memcache(Config::$UseMemcache);
    }
  }

  /**
   * Singleton
   *
   */
  protected static function singleton() {
    if ( ! self::$instance ) {
      self::$instance = new BeakController();
    }
  }

  /**
   * Resolve Beak-driver
   *
   * @param String $brl  BRL
   * @param String $arg  
   * @param String $hide
   * @param Array $classes Be able to specifiy the Beak-drivers @see config.php
   * @return mixed Beak-driver object
   */
  protected function prepareQuery(&$brl,&$arg,&$hide,$classes) {
    list($S,$D,$C,$p,$m,$q,$c) = parse_brl($brl);
    $beak = isset($classes[$S])?$classes[$S]:Config::$BEAKS[$S];
    if ( ! $beak ) {
      throw new \Exception('Unsupported BRL Scheme ! :' . $brl);
    }
    return new $beak($brl,$S,$D,$C,$p,$m,$q,$c,$arg,$hide);
  }

  /**
   * Query cache
   *
   * 
   */

  protected function pre_query_cache(&$beak) {
    if ( Config::$UseMemcache ) {
      $beak->key = $beak->brl .($beak->arg?'#'.md5(beak_encode($beak->arg)):'');
      $cache = $this->cachectrl->get($beak->key);
      if ( $cache && $cache[0] && $cache[1] && $cache[2] ) {
        $hit    = true;
        $data   = $cache[0];
        $remain = $cache[1] - time();
        if ( $remain < 0 ) {
          $hit  = false;
        }
        $expire = $cache[2];
        // Flying threashold.
        if ( $hit and $expire*0.5 > $remain and $remain < 3600 ) {
          // Balance weight
          $chance = pow($remain,Config::$EXPIRE_BALANCE);
          // Short term boost
          if ( Config::$EXPIRE_BALANCE_BOOST >= $expire ) {
            $chance *= pow((Config::$EXPIRE_BALANCE_BOOST/$expire),Config::$EXPIRE_BALANCE);
          }
          $hit = rand(0,$chance) !== 0;
        }
        if ( $hit ) {// Hit
          $beak->cache_hit = $data;
          Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Cache HIT    => ' . $beak->key . ' ( ' . $remain . ' / ' . $expire . ' )' );
          return true;
        }else{
          Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Cache MISS   => ' . $beak->key . ' ( ' . $remain . ' / ' . $expire . ' )' );
          return false; // cache miss
        }
      }
    }
    return false; // cache miss
  }
  /**
   * Query cache 
   *
   * 
   */
  protected function post_query_cache(&$beak,&$ret) {
    if ( Config::$UseMemcache ) {
      if ( $ret ) {
        $expire = Beak::DEFAULT_CACHE_EXPIRE;
        foreach ( $beak->comments as $comment ) {
          if ( preg_match('@^'.Beak::COMMENT_KIND_CACHE_EXP.'=(\d+)$@',$comment,$matches) !== 0 ){
            $expire = $matches[1];
            break;
          }
        }          
        $cache=array();
        $cache[0] = &$ret;
        $cache[1] = $expire + time();
        $cache[2] = $expire;
        $this->cachectrl->set($beak->key,$cache);
      }
    }
  }
  /**
   * Query dispatcher
   *
   * @param mixed Beak-driver object
   */
  protected function query(&$beak) {
    if     ( strcmp($beak->method,Beak::M_GET) === 0 ) {
      if ( ! $beak->cacheable or ! $this->pre_query_cache($beak) ) {
        $beak->getQuery();
      }
    }elseif( strcmp($beak->method,Beak::M_GET_ARRAY) === 0 ) {
      if ( ! $beak->cacheable or ! $this->pre_query_cache($beak) ) {
        $beak->getaQuery();
      }
    }elseif( strcmp($beak->method,Beak::M_SET) === 0 ) {
      $beak->setQuery();
    }elseif( strcmp($beak->method,Beak::M_SET_ARRAY) === 0 ) {
      $beak->setaQuery();
    }elseif( strcmp($beak->method,Beak::M_COL_LIST) === 0 ) {
      $beak->listColQuery();
    }elseif( strcmp($beak->method,Beak::M_KEY_LIST) === 0 ) {
      $beak->listKeyQuery();
    }elseif( strcmp($beak->method,Beak::M_CREATE_COL) === 0 ) {
      $beak->createColQuery();
    }elseif( strcmp($beak->method,Beak::M_DEL) === 0 ) {
      $beak->delQuery();
    }elseif( strcmp($beak->method,Beak::M_DEL_ARRAY) === 0 ) {
      $beak->delaQuery();
    }elseif( strcmp($beak->method,Beak::M_MV_COL) === 0 ) {
      $beak->mvColQuery();
    }else {
      throw new \Exception('Unsupported BRL Method ! :' . $beak->method);
    }
  }

  /**
   * Get query result
   * 
   *  @return Array Array($brl => $result)
   */
  protected function result(&$beak) {
    if ( $beak->cache_hit  ) {
      $ret = $beak->cache_hit;
    }else {
      $ret = $beak->result();
      if ( $ret === null ) {
        if ( $beak->critical ) {
          // @@@ Should I define the specific type exception to build the recover-code more easily ? 
          throw new \Exception('The critical BEAK failed ! :' . $beak->brl); 
        }
      }
      if ( $beak->cacheable ) {
        $this->post_query_cache($beak,$ret);
      }
    }
    return $ret;
  }
}
