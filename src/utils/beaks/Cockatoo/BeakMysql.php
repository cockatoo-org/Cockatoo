<?php
/**
 * BeakMysql.php - Beak driver : Mysql base strage
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/01/24
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
namespace Cockatoo;
require_once($COCKATOO_ROOT.'utils/beak.php');


/**
 * ??????????
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class BeakMysql extends Beak {
//  const KEY_TYPE = 'VARCHAR(4096)';
  const KEY_TYPE = 'VARCHAR(255)';
  const VAL_TYPE = 'MEDIUMBLOB';
  /**
   * Index name
   */
  protected $uniqueIndex;
  /**
   * Mysql
   */
  protected $pdo;
  /**
   * Constructor
   * 
   * @see Action.php
   */
  public function __construct(&$brl,&$scheme,&$domain,&$collection,&$path,&$method,&$queries,&$comments,&$arg,&$hide) {
    parent::__construct($brl,$scheme,$domain,&$collection,$path,$method,$queries,$comments,$arg,$hide);
    $this->uniqueIndex = isset($this->queries[Beak::Q_UNIQUE_INDEX])?$this->queries[Beak::Q_UNIQUE_INDEX]:Beak::Q_UNIQUE_INDEX;
//    $indexes           = isset($this->queries[Beak::Q_INDEXES])?$this->queries[Beak::Q_INDEXES]:'';
//    if ( $indexes ) {
//      $this->indexes     = explode(',',$indexes);
//    }
    
    $this->beakLocation = BeakLocationGetter::singleton();
    $base_brl = $scheme . '://' . $domain . '/';
    $locations = $this->beakLocation->getLocation(array($base_brl),'');
    if ( ! $locations[$base_brl] ) {
      Log::debug(__CLASS__ . '::' . __FUNCTION__ . ' : No location-info ' . $base_brl);
      return ;
    }
    // @@@ user / password
    $location = explode(':',$locations[$base_brl][array_rand($locations[$base_brl])]);
    $connect = 'mysql:host='.$location[0].';port='.$location[1].';dbname='.$this->domain;
    try { 
      $this->pdo = new \PDO($connect,'root','root',array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    }catch(\PDOException $e ){
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Mysql error : ' . $e->getMessage(),$e);
      return;
    }
  }
  private function ddlQuery(&$query){
    Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : ddl query : ' . $query);
    return (bool)$this->pdo->query($query);
  }
  private function fetchQuery(&$query,&$bind=array()){
    Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : fetch query : ' . $query);
    $stmt = $this->pdo->prepare($query);
    foreach($bind as $k => $v ) {
      $stmt->bindParam($k,$v[1],$v[0]);
    }
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_COLUMN);
  }
  private function execQuery(&$query,&$bind=array()){
    Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : exec query : ' . $query);
    $stmt = $this->pdo->prepare($query);
    foreach($bind as $k => $v ) {
      $stmt->bindParam($k,$v[1],$v[0]);
    }
    $stmt->execute();
    return $stmt->rowCount();
  }
  private static function encode($data){
    return serialize($data);
  }
  private static function decode($data){
    return unserialize($data);
  }
  /**
   * Craete collection and Create index
   *   
   * @see Action.php
   */
  public function createColQuery(){
    if ( $this->pdo ) {
      if ( $this->renew ) {
        $ddl = 'DROP TABLE '.$this->collection;
        $this->ddlQuery($ddl);
      }
      $ddl = 'CREATE TABLE '.$this->collection.' ('.$this->uniqueIndex.' '.self::KEY_TYPE.' CHARACTER SET utf8 ,D '.self::VAL_TYPE.',_R INT,PRIMARY KEY('.$this->uniqueIndex.'))';
      $this->ret = $this->ddlQuery($ddl);
    }
  }

  /**
   * Get all collections name
   *
   * @see Action.php
   */
  public function listColQuery() {
    if ( $this->pdo ) {
      $ddl = 'SHOW TABLES';
      $this->ret = $this->fetchQuery($ddl);
    }
  }

  /**
   * Get all keys containing the collection.
   *
   * @see Action.php
   */
  public function listKeyQuery() {
    if ( $this->pdo ) {
      $sql = 'SELECT '.$this->uniqueIndex.' FROM '.$this->collection.' WHERE '.$this->uniqueIndex.' LIKE :u';
      $bind = array(
        ':u'=>array(\PDO::PARAM_STR,$this->path.'%'));
      $this->ret = $this->fetchQuery($sql,$bind);
    }
  }

  /**
   * T.B.D @@@
   * 
   * @see Action.php
   */
  public function getaQuery() {
    if ( $this->pdo ) {
      $bind = array();
      $sql = 'SELECT D FROM '.$this->collection.' WHERE '.$this->uniqueIndex.' IN(';
      $i = 1;
      foreach ( $this->arg[$this->uniqueIndex] as $cond ) {
        if ( $i !== 1 ) {
          $sql .= ',';
        }
        $sql .= ':'.$i;
        $bind[':'.$i]= array(\PDO::PARAM_STR,$cond);
        $i++;
      }
      $sql .= ')';
      $ret = $this->fetchQuery($sql,$bind);
      foreach( $ret as $d ) {
        $r = self::decode($d);
        if ( $r[Beak::ATTR_REV] === 0 ) {
          unset($r[Beak::ATTR_REV]); // @@@
        }
        $this->ret[$r[$this->uniqueIndex]] = $r;
      }
    }
  }

  private function getDoc($path){
    $sql = 'SELECT D FROM '.$this->collection.' WHERE '.$this->uniqueIndex.'=:u';
    $bind = array(
      ':u'=>array(\PDO::PARAM_STR,$path));
    $ret = $this->fetchQuery($sql,$bind);
    if ( isset($ret[0]) ) {
      return self::decode($ret[0]);
    }
    return null;
  }
  /**
   * Get document data
   *
   * @see Action.php
   */
  public function getQuery() {
    if ( $this->pdo ) {
      $this->ret = $this->getDoc($this->path);
      if ( $this->ret[Beak::ATTR_REV] === 0 ) {
        unset($this->ret[Beak::ATTR_REV]); // @@@
      }
    }    
  }
  /**
   * T.B.D @@@
   *
   * @see Action.php
   */
  public function delQuery() {
    if ( $this->pdo ) {
      $sql = 'DELETE FROM ' . $this->collection . ' WHERE '.$this->uniqueIndex.'=:u AND (_R=:c OR :c=0)';
      $rev = 0;
      if ( $this->rev ) {
        if ( $this->arg[Beak::ATTR_REV] ) {
          $rev = $this->arg[Beak::ATTR_REV];
        }
        $this->arg[Beak::ATTR_REV] = (String)time();
      }else{
        $this->arg[Beak::ATTR_REV] = 0;
      }
      $bind = array(
        ':u'=>array(\PDO::PARAM_STR,$this->path),
        ':c'=>array(\PDO::PARAM_INT,$rev)
        );
      $this->ret = $this->execQuery($sql,$bind);
    }
  }
  /**
   * T.B.D @@@
   *
   * @see Action.php
   */
  public function delaQuery() {
    if ( $this->pdo ) {
      $sql = 'DELETE FROM ' . $this->collection . ' WHERE '.$this->uniqueIndex.' IN(';
      $i = 1;
      foreach ( $this->arg[$this->uniqueIndex] as $cond ) {
        if ( $i !== 1 ) {
          $sql .= ',';
        }
        $sql .= ':'.$i;
        $bind[':'.$i]= array(\PDO::PARAM_STR,$cond);
        $i++;
      }
      $sql .= ')';
      $this->ret = $this->execQuery($sql,$bind);
    }
  }
  /**
   * Set multi document datas
   * 
   * @see Action.php
   */
  public function setaQuery(){
    if ( $this->pdo ) {
      $this->ret = array();
      foreach ( $this->arg as $arg ) {
        $path = &$arg[$this->uniqueIndex];
        $this->ret[$path] = $this->setDoc($path,$arg);
      }
    }
  }
  private function setDoc(&$path,&$arg){
    $ret = null;
    if ( $this->partial) {
      $cur = $this->getDoc($path);
      if ( $cur ) {
        $arg = array_merge($cur,$arg);
      }
    }
    $arg[$this->uniqueIndex] = &$path;
    $sql = 'UPDATE ' . $this->collection . ' SET _R=:r,D=:d WHERE '.$this->uniqueIndex.'=:u AND (_R=:c OR :c=0)';
    $rev = 0;

    if ( $this->rev ) {
      if ( $arg[Beak::ATTR_REV] ) {
        $rev = $arg[Beak::ATTR_REV];
      }
      $arg[Beak::ATTR_REV] = (String)time();
    }else{
      $arg[Beak::ATTR_REV] = 0;
    }
    $bind = array(
      ':u'=>array(\PDO::PARAM_STR,$path),
      ':r'=>array(\PDO::PARAM_INT,$arg[Beak::ATTR_REV]),
      ':d'=>array(\PDO::PARAM_LOB,self::encode($arg)),
      ':c'=>array(\PDO::PARAM_INT,$rev)
      );
    $ret = $this->execQuery($sql,$bind);
    if ( ! $ret ){
      $sql = 'INSERT INTO ' . $this->collection . '('.$this->uniqueIndex.',_R,D) VALUES(:u,:r,:d)';
      $bind = array(
        ':u'=>array(\PDO::PARAM_STR,$path),
        ':r'=>array(\PDO::PARAM_INT,$arg[Beak::ATTR_REV]),
        ':d'=>array(\PDO::PARAM_LOB,self::encode($arg))
        );
      $ret = $this->execQuery($sql,$bind);
    }
    return (bool)$ret; // @@@
  }
  /**
   * Set document data
   *
   * @see Action.php
   */
  public function setQuery() {
    if ( $this->pdo ) {
      $this->ret = $this->setDoc($this->path,$this->arg);
    }
  }
  /**
   * Move collection
   */
  public function mvColQuery() {
    if ( $this->pdo and $this->queries[Beak::Q_NEWNAME] ) {
      $ddl = 'DROP TABLE '.$this->queries[Beak::Q_NEWNAME];
      $this->ddlQuery($ddl);
      $ddl = 'RENAME TABLE ' . $this->collection . ' TO ' . $this->queries[Beak::Q_NEWNAME];
      $this->ret = $this->ddlQuery($ddl);
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
}

function dump_result($results) {
  foreach ($results as $k => $v){
    if ( is_array($v) ) {
      print "[$k] => " . var_export($v,1) . "\n";
    }else {
      print "[$k] => $v\n";
    }
  }
}

/*
try {
$COCKATOO_CONF=getenv('COCKATOO_CONF');
require_once($COCKATOO_CONF);

$results = BeakController::beakQuery(array('strage://wiki-strage/test/?ccol#renew'));
dump_result($results);
$results = BeakController::beakQuery(array('strage://wiki-strage/test/?cols'));
dump_result($results);
$results = BeakController::beakQuery(array(array('strage://wiki-strage/test/foo?set#rev',array('_u'=>'foo','val1'=>'FOO'))));
dump_result($results);

$results = BeakController::beakQuery(array(array('strage://wiki-strage/test/foo?set#rev',array('_u'=>'foo','val2'=>'BAR','@R'=>1))));
dump_result($results);
$results = BeakController::beakQuery(array('strage://wiki-strage/test/foo?get'));
dump_result($results);
sleep(1);
$d = $results['strage://wiki-strage/test/foo?get'];
$d['val3']='BAZ';
$results = BeakController::beakQuery(array(array('strage://wiki-strage/test/foo?set#rev',$d)));
dump_result($results);
$results = BeakController::beakQuery(array('strage://wiki-strage/test/foo?get'));
dump_result($results);
$results = BeakController::beakQuery(array(array('strage://wiki-strage/test/bar?set',array('_u'=>'bar','val'=>'FOO'))));
dump_result($results);
$results = BeakController::beakQuery(array(array('strage://wiki-strage/test/bar?set',array('_u'=>'bar','val2'=>'BAR'))));
dump_result($results);
$results = BeakController::beakQuery(array(array('strage://wiki-strage/test/baz?set',array('_u'=>'baz','val1'=>'BAZ'))));
dump_result($results);
$results = BeakController::beakQuery(array(array('strage://wiki-strage/test/?setA',
                                                 array('foobar' => array('_u'=>'foobar','val1'=>'FOOBAR'),
                                                       'xyzzy' => array('_u'=>'xyzzy','val1'=>'XYZZY'))
                                             )));
dump_result($results);
$results = BeakController::beakQuery(array('strage://wiki-strage/test/b?keys'));
dump_result($results);
$results = BeakController::beakGetsQuery(array('strage://wiki-strage/test/foo?get','strage://wiki-strage/test/bar?get','strage://wiki-strage/test/baz?get'));
foreach ( $results as $result ) {
  dump_result($result);
}
$results = BeakController::beakQuery(array('strage://wiki-strage/test/?keys'));
dump_result($results);
$results = BeakController::beakQuery(array('strage://wiki-strage/test/baz?del'));
dump_result($results);
$results = BeakController::beakQuery(array('strage://wiki-strage/test/?keys'));
dump_result($results);
$results = BeakController::beakQuery(array(array('strage://wiki-strage/test/?delA',
                                                 array('_u' => array('foobar','xyzzy')))));
dump_result($results);
$results = BeakController::beakQuery(array('strage://wiki-strage/test/?keys'));
dump_result($results);


$results = BeakController::beakQuery(array('strage://wiki-strage/test/?mcol&_new=test2'));
dump_result($results);
$results = BeakController::beakQuery(array('strage://wiki-strage/test2/?keys'));
dump_result($results);

}catch( \Exception $e ) {
  var_dump('@@@@@@@@');
  var_dump($e->getMessage());
  var_dump($e);
}
*/