<?php
/**
 * BeakMongo.php - Beak driver : MongoDB base storage
 *  
 * @access public
 * @package cockatoo-beaks
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
require_once (Config::COCKATOO_ROOT.'utils/mongodriver.php');

/**
 * MongoDB base storage
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class BeakMongo extends Beak {
  /**
   * Index name
   */
  protected $mongoAcc;
  protected $timeout;
  protected $fsync;
  protected $mongocursor;
  /**
   * Constructor
   * 
   * @see Action.php
   */
  public function __construct(&$brl,&$scheme,&$domain,&$collection,&$path,&$method,&$queries,&$comments,&$arg,&$hide) {
    parent::__construct($brl,$scheme,$domain,$collection,$path,$method,$queries,$comments,$arg,$hide);
    
    $this->columns = array('_id' => 0);
    if ( isset($this->queries[Beak::Q_FILTERS]) ) {
      $this->columns = array_merge($this->columns,array_fill_keys(explode(',',$this->queries[Beak::Q_FILTERS]),1));
    }else if ( isset($this->queries[Beak::Q_EXCEPTS]) ) {
      $this->columns = array_merge($this->columns,array_fill_keys(explode(',',$this->queries[Beak::Q_EXCEPTS]),0));
    }

    $this->sort = isset($this->queries[Beak::Q_SORT])?$this->queries[Beak::Q_SORT]:'';
    $this->skip = isset($this->queries[Beak::Q_SKIP])?$this->queries[Beak::Q_SKIP]:0;
    $this->limit = isset($this->queries[Beak::Q_LIMIT])?$this->queries[Beak::Q_LIMIT]:Beak::DEFAULT_LIMIT;
    
    
    $this->beakLocation = BeakLocationGetter::singleton();
    $base_brl = $scheme . '://' . $domain . '/';
    $locations = $this->beakLocation->getLocation(array($base_brl),'');
    if ( ! $locations[$base_brl] ) {
      Log::debug(__CLASS__ . '::' . __FUNCTION__ . ' : No location-info ' . $base_brl);
      return ;
    }
    $this->fsync = $this->nsync?false:true;
    $this->mongoAcc = new MongoAccess($locations[$base_brl],$this->domain,$this->collection,(!$this->fresh));
    // @@@ Todo: user , password
    // $this->mongoAcc = new MongoAccess($locations[$base_brl],$this->domain,$this->collection,(!$this->fresh),$user,$passwd);
  }


  public function createColQueryImpl($mongo,$mongodb,$mongocollection){
    if ( $this->renew ) {
      if ( $mongocollection ) {
        $mongocollection->drop();
        $mongocollection = null;
      }
    }
    if ( !isset($mongocollection) ) {
      $mongocollection = $mongodb->createCollection($this->collection);
    }
    if ( isset($mongocollection) ) {
      $ret = $mongocollection->ensureIndex(array(Beak::Q_UNIQUE_INDEX => 1),array('unique' => true,'safe' => true));
      if ( isset($this->queries[Beak::Q_INDEXES]) ){
        foreach(explode(',',$this->queries[Beak::Q_INDEXES]) as $index){
          $ret = $mongocollection->ensureIndex(array($index=>1),array('unique' => false,'safe' => true));
        }
      }
      return true;
    }
  }

  public function listColQueryImpl($mongo,$mongodb,$mongocollection) {
    return array_map(function($obj){ return $obj->getName();},$mongodb->listCollections());
  }

  public function listKeyQueryImpl($mongo,$mongodb,$mongocollection) {
    if ( $mongocollection ) {
      $ret = array();
      $this->mongocursor = $mongocollection->find(array(Beak::Q_UNIQUE_INDEX => new \MongoRegex('/^'.$this->path.'.*/') ),array('_id' => 0, Beak::Q_UNIQUE_INDEX => 1));
      if ( $this->mongocursor ) {
        $this->ret = array();
        while ( $this->mongocursor->hasNext() ) {
          $doc = $this->mongocursor->getNext();
          $ret []= $doc[Beak::Q_UNIQUE_INDEX];
        }
      }
      return $ret;
    }
    return null;
  }

  public function getrQueryImpl($mongo,$mongodb,$mongocollection) {
    if ( $mongocollection ) {
      $ret = array();
      $query = array();
      foreach ( $this->arg as $key => $cond ) {
        $query[$key] = $cond;
      }

      $this->mongocursor = $mongocollection->find($query,$this->columns);
      if ( $this->mongocursor ) {
        if ( $this->sort and preg_match('@(^.+):([\-1]+)$@',$this->sort,$matches) !== 0) {
          $this->mongocursor->sort(array($matches[1]=>(int)$matches[2]));
        }
        $this->mongocursor->limit($this->limit);
        $this->mongocursor->skip($this->skip);
        $this->ret = array();
        while ( $this->mongocursor->hasNext() ) {
          $data = $this->mongocursor->getNext();
          if ( $data[Beak::ATTR_BIN] ) {
            self::decode($data);
          }
//          $ret [$data[Beak::Q_UNIQUE_INDEX]]= $data;
          $ret []= $data;
        }
      }
      return $ret;
    }
    return null;
  }
  public function getaQueryImpl($mongo,$mongodb,$mongocollection) {
    if ( $mongocollection ) {
      $ret = array();
      foreach ( $this->arg as $key => $cond ) {
        $query[$key]['$in'] = $cond;
      }

      $this->mongocursor = $mongocollection->find($query,$this->columns);
      if ( $this->mongocursor ) {
        while ( $this->mongocursor->hasNext() ) {
          $data = $this->mongocursor->getNext();
          if ( $data[Beak::ATTR_BIN] ) {
            self::decode($data);
          }
          $ret [$data[Beak::Q_UNIQUE_INDEX]]= $data;
        }
      }
      return $ret;
    }
    return null;
  }

  public function getQueryImpl($mongo,$mongodb,$mongocollection) {
    if ( $mongocollection ) {
      $data = $mongocollection->findOne(array(Beak::Q_UNIQUE_INDEX => $this->path ),$this->columns);
      if ( isset($data[Beak::ATTR_BIN]) ) {
        self::decode($data);
      }
      return $data;
    }
    return null;
  }

  static function encode(&$data){
    foreach (array_keys($data) as $k ) {
      if ( strncmp($k,Beak::ATTR_BIN,1) === 0 and strlen($k)!==1 ) {
//        $data[$k] = new MongoBinData($data[$k]);
        $data[$k] = join(unpack('H*',$data[$k]));
      }else if ( is_array($data[$k]) ) {
        self::encode($data[$k]);
      }      
    }
  }
  static function decode(&$data){
    foreach (array_keys($data) as $k ) {
      if ( strncmp($k,Beak::ATTR_BIN,1) === 0 and strlen($k)!==1 ) {
//        $data[$k] = $data[$k]->bin;
        $data[$k] = pack('H*',$data[$k]);
      }else if ( is_array($data[$k]) ) {
        self::decode($data[$k]);
      }      
    }
  }

  private function setDoc(&$mongocollection,&$path,&$arg){
    $cond = array(
      Beak::Q_UNIQUE_INDEX => $path
      );
    if ( ! $this->op || strcmp($this->op,Beak::COMMENT_KIND_OP_SET)===0 ) {
      if ( $this->rev ) {
        if ( $arg[Beak::ATTR_REV] ) {
          $cond[Beak::ATTR_REV] = $arg[Beak::ATTR_REV];
        }
        $arg[Beak::ATTR_REV] = (String)time();
      }
      if ( isset($arg[Beak::ATTR_BIN]) ) {
        self::encode($arg);
      }
    }
    if ( $this->op ) {
      if       ( strcmp($this->op,Beak::COMMENT_KIND_OP_INC)===0 ) {
      }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_SET)===0 ) {
      }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_UNSET)===0 ) {
      }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_PUSH)===0 ) {
      }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_PUSHALL)===0 ) {
      }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_ADDTOSET)===0 ) {
      }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_POP)===0 ) {
      }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_PULL)===0 ) {
      }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_PULLALL)===0 ) {
      }else if ( strcmp($this->op,Beak::COMMENT_KIND_OP_RENAME)===0 ) {
      }else{
      }
      return $mongocollection->update($cond,array($this->op => $arg),array('upsert' => true , 'safe' => true , 'fsync' => $this->fsync , 'timeout' => $this->timeout,'multiple' => false));
    }else {
      $arg[Beak::Q_UNIQUE_INDEX] = &$path;
      return $mongocollection->update($cond,$arg,array('upsert' => true , 'safe' => true , 'fsync' => $this->fsync , 'timeout' => $this->timeout,'multiple' => false));
    }
  }

  public function setQueryImpl($mongo,$mongodb,$mongocollection) {
    if ( $mongocollection ) {
      $ret = $this->setDoc($mongocollection,$this->path,$this->arg);
      return  $ret['ok']==1.0?true:false;
    }
  }
  public function setaQueryImpl($mongo,$mongodb,$mongocollection) {
    if ( $mongocollection ) {
      $ret = array();
      foreach ( $this->arg as $arg ) {
        $path = &$arg[Beak::Q_UNIQUE_INDEX];
        $ret = $this->setDoc($mongocollection,$path,$arg);
        $ret[$path] = $ret['ok']==1.0?true:false;
      }
      return $ret;
    }
    return null;
  }
  public function delQueryImpl($mongo,$mongodb,$mongocollection) {
    if ( $mongocollection ) {
      $cond = array(
        Beak::Q_UNIQUE_INDEX => $this->path
        );
      if ( $this->rev ) {
        if ( $arg[Beak::ATTR_REV] ) {
          $cond[Beak::ATTR_REV] = $this->arg[Beak::ATTR_REV];
        }
      }
      $ret = $mongocollection->remove($cond,array('safe' => true , 'fsync' => $this->fsync , 'timeout' => $this->timeout,'justOne' => true));
      return $ret['ok']==1.0?true:false;
    }
    return null;
  }
  public function delaQueryImpl($mongo,$mongodb,$mongocollection) {
    if ( $mongocollection ) {
      foreach ( $this->arg[Beak::Q_UNIQUE_INDEX] as $cond ) {
        $query[Beak::Q_UNIQUE_INDEX]['$in'] []= $cond;
      }
      $ret = $mongocollection->remove($query,array('safe' => true , 'fsync' => $this->fsync , 'timeout' => $this->timeout,'justOne' => false));
      return $ret['ok']==1.0?true:false;
    }
    return null;
  }

  public function mvColQueryImpl($mongo,$mongodb,$mongocollection) {
    if ( $mongocollection and $this->queries[Beak::Q_NEWNAME] ) {
      $ret = $mongodb->execute('db.'.$this->collection.'.renameCollection("'.
                                     $this->queries[Beak::Q_NEWNAME] .'",1)');
      return $ret['ok']==1.0?true:false;
    }
    return null;
  }

  public function sysQueryImpl($mongo,$mongodb,$mongocollection) {
    if ( $mongocollection ) {
      if ( $this->queries[Beak::Q_SYS] = 'idxs' ) {
        $idxs = $mongocollection->getIndexInfo();
        foreach($idxs as $idx){
          $iname = implode('_',array_keys($idx['key']));
          if ( $iname !== '_id' and $iname !== Beak::Q_UNIQUE_INDEX){
            $ret[]=$iname;
          }
        }
      }
      return $ret;
    }
    return null;
  }

  public function createColQuery(){
    if ( ! $this->mongoAcc ) {
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Unable to get $this->mongoAcc ( no connection )');
      return;
    }
    $this->ret = $this->mongoAcc->mongoProc($this,'createColQueryImpl');
  }
  public function listColQuery() {
    if ( ! $this->mongoAcc ) {
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Unable to get $this->mongoAcc ( no connection )');
      return;
    }
    $this->ret = $this->mongoAcc->mongoProc($this,'listColQueryImpl');
  }
  public function listKeyQuery() {
    if ( ! $this->mongoAcc ) {
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Unable to get $this->mongoAcc ( no connection )');
      return;
    }
    $this->ret = $this->mongoAcc->mongoProc($this,'listKeyQueryImpl');
  }
  public function getaQuery() {
    if ( ! $this->mongoAcc ) {
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Unable to get $this->mongoAcc ( no connection )');
      return;
    }
    $this->ret = $this->mongoAcc->mongoProc($this,'getaQueryImpl');
  }
  public function getrQuery() {
    if ( ! $this->mongoAcc ) {
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Unable to get $this->mongoAcc ( no connection )');
      return;
    }
    $this->ret = $this->mongoAcc->mongoProc($this,'getrQueryImpl');
  }
  public function getQuery() {
    if ( ! $this->mongoAcc ) {
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Unable to get $this->mongoAcc ( no connection )');
      return;
    }
    $this->ret = $this->mongoAcc->mongoProc($this,'getQueryImpl');
  }
  public function setQuery() {
    if ( ! $this->mongoAcc ) {
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Unable to get $this->mongoAcc ( no connection )');
      return;
    }
    $this->ret = $this->mongoAcc->mongoProc($this,'setQueryImpl');
  }
  public function setaQuery() {
    if ( ! $this->mongoAcc ) {
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Unable to get $this->mongoAcc ( no connection )');
      return;
    }
    $this->ret = $this->mongoAcc->mongoProc($this,'setaQueryImpl');
  }
  public function delQuery() {
    if ( ! $this->mongoAcc ) {
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Unable to get $this->mongoAcc ( no connection )');
      return;
    }
    $this->ret = $this->mongoAcc->mongoProc($this,'delQueryImpl');
  }
  public function delaQuery() {
    if ( ! $this->mongoAcc ) {
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Unable to get $this->mongoAcc ( no connection )');
      return;
    }
    $this->ret = $this->mongoAcc->mongoProc($this,'delaQueryImpl');
  }
  public function mvColQuery() {
    if ( ! $this->mongoAcc ) {
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Unable to get $this->mongoAcc ( no connection )');
      return;
    }
    $this->ret = $this->mongoAcc->mongoProc($this,'mvColQueryImpl');
  }
  /**
   * System use only
   *
   */
  public function sysQuery() {
    if ( ! $this->mongoAcc ) {
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . 'Unable to get $this->mongoAcc ( no connection )');
      return;
    }
    $this->ret = $this->mongoAcc->mongoProc($this,'sysQueryImpl');
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
