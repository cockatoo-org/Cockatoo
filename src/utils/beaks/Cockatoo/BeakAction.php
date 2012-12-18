<?php
/**
 * BeakAction.php - Beak driver : Do action.
 *  
 * @access public
 * @package cockatoo-beaks
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
\ClassLoader::addClassPath(Config::COCKATOO_ROOT.'action/actions');
require_once (Config::COCKATOO_ROOT.'utils/beak.php');
require_once (Config::COCKATOO_ROOT.'action/Action.php');

/**
 * Do Action
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class BeakAction extends Beak {
  /**
   * Result object
   */
  protected $ret = null;
  /**
   * Action object
   */
  protected $currentAction = null;
  /**
   * Constructor
   * 
   * @see Action.php
   */
  public function __construct(&$brl,&$scheme,&$domain,&$collection,&$path,&$method,&$queries,&$comments,&$arg,&$hide) {
    parent::__construct($brl,$scheme,$domain,$collection,$path,$method,$queries,$comments,$arg,$hide);

    $base = $scheme . '://' . $domain . '/';
    $beaklocation = BeakLocationGetter::singleton();
    $locations = $beaklocation->getLocation(array($base));
    if ( Config::Mode === Def::MODE_DEBUG or count($locations) > 0 ) {
      try {
        $clazz = $this->collection.'\\'.$this->path;
        $this->currentAction = new $clazz($this->brl);
      }catch(\Exception $e){
        Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . $this->brl . ' , ' . $e->getMessage(),$e);
      }
    }else{
      Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . ' Invalid location' . $base );
    }
  }
  /**
   * Not necessary to implement
   * 
   * @see Action.php
   */
  public function createColQuery(){
    $this->runAction();
  }
  /**
   * Not necessary to implement
   * 
   * @see Action.php
   */
  public function listKeyQuery(){
    $this->runAction();
  }
  /**
   * Not necessary to implement
   * 
   * @see Action.php
   */
  public function listColQuery(){
    $this->runAction();
  }
  /**
   * Not necessary to implement
   * 
   * @see Action.php
   */
  public function setQuery(){
    $this->runAction();
  }
  /**
   * Not necessary to implement
   * 
   * @see Action.php
   */
  public function setaQuery(){
    $this->runAction();
  }
  /**
   * Not necessary to implement
   * 
   * @see Action.php
   */
  public function getaQuery(){
    $this->runAction();
  }
  /**
   * Not necessary to implement
   * 
   * @see Action.php
   */
  public function getrQuery(){
    $this->runAction();
  }
  /**
   * Do action
   * 
   * @see Action.php
   */
  public function getQuery(){
    $this->runAction();
  }
  /**
   * T.B.D @@@
   *
   * @see Action.php
   */
  public function delQuery() {
    $this->runAction();
  }
  /**
   * T.B.D @@@
   *
   * @see Action.php
   */
  public function delaQuery() {
    $this->runAction();
  }
  /**
   * Move collection
   */
  public function mvColQuery() {
    $this->runAction();
  }
  /**
   * System use only
   *
   */
  public function sysQuery() {
    $this->runAction();
  }
  /**
   * runAction
   * 
   * @see Action.php
   */
  private function runAction(){
    if ( $this->currentAction ) {
      $this->currentAction->set($this->arg,$this->hide);
      $this->ret = $this->currentAction->run();
      $this->currentAction->postRun();
      $this->currentAction = null;
    }
  }
  /**
   * Get result
   * 
   * @see Action.php
   */
  public  function result() {
    return $this->ret;
  }
}
