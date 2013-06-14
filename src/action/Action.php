<?php
/**
 * Action.php - Action base class
 *  
 * @access public
 * @package cockatoo-action
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
require_once(Config::COCKATOO_ROOT.'utils/session.php');

/**
 * Action base
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
abstract class Action {
  private $namespace = null;
  private $method = null;
  private $queries = null;
  private $comments = null;

  public $BRL;
  public $sessionID;
  public $service;
  public $args;
  public $session = null;
  public $updateSession = array();
  public $moved_temporary  = null;
  public $moved_permanently= null;
  public $updateCookie = array();
  public $header = '';
  public $updateArgs = null;

//   public $templateName;
//   public $requestURL;
  /**
   * Constructor
   *
   *   Construct a object.
   *
   * @param String $brl  Action identifier
   */
  public function __construct($brl){
    $this->BRL = $brl;
  }

  private function parse_brl(){
    list($P,$D,$C,$p,$m,$q,$c) = parse_brl($this->BRL);
    $this->method = $m;
    $this->queries = $q;
    $this->comments = $c;
  }
  public function get_method(){
    if ( $this->method === null ) {
      $this->parse_brl();
    }
    return $this->method;
  }
  public function get_queries(){
    if ( $this->queries === null ) {
      $this->parse_brl();
    }
    if ( is_string($this->queries) ) {
      $this->queries = parse_brl_query($this->queries);
    }
    return $this->queries;
  }
  /**
   * Prepare process
   *
   * @param String $context  Action context
   */
  public function prepare($args,$hide) {
    $this->sessionID = $hide[Def::AC_SESSION_ID];
    $this->service   = $hide[Def::AC_SERVICE];
    $this->args      = $args;
  }
  /**
   * Set action environment.
   *  This value is sent to following actions as action args.
   *  It's mainly useful to BRL cache.
   *  For instance.
   *   pre-action : action://foo/FooAction.php       => call updateArgs(Array('a'=>'b'));
   *   actions    : action://foo/BarAction.php#cache => key of the cache is action://foo/BarAction.php#caache#[md5str of args]
   *   
   */
  protected function updateArgs($data){
    $this->updateArgs  = $data;
  }

  /**
   * Update session data
   *
   * @param String $data session-data
   */
  protected function updateSession($data){
    if ( $this->updateSession == null ) {
      $this->updateSession = $data;
    }else{
      $this->updateSession = array_merge($this->updateSession,$data);
    }
  }
  /**
   * Get session
   *
   * @return array session objcet
   */
  protected function &getSession(){
    if ( ! $this->session ) {
      $this->session = getSession($this->sessionID,$this->service);
    }
    return $this->session;
  }
  /**
   * Force redirect
   *
   *  It means that it doesn't do render this page.
   *
   * @deplicated Use setMovedTemporary
   * @param String redirect location
   */
  protected function setRedirect($redirect){
    $this->moved_temporary = $redirect;
  }
  /**
   * Moved permanently redirect (301)
   *
   *  It means that it doesn't do render this page.
   *
   * @param String redirect location
   */
  protected function setMovedPermanently($location){
    $this->moved_permanently = $location;
  }
  /**
   * Moved temporary redirect (302)
   *
   *  It means that it doesn't do render this page.
   *
   * @param String redirect location
   */
  protected function setMovedTemporary($location){
    $this->moved_temporary = $location;
  }
  /**
   * Set namespace of the rendering object
   *
   *  '<Namespace>.<Class>' is default.
   *
   * @param String $namespace new namespace
   */
  protected function setNamespace($namespace = null){
    $this->namespace = $namespace;
  }
  /**
   * Get cookie
   *
   * @param String $name cookie-key
   * @return String cookie
   */
  protected function getCookie($name){
    $this->getSession();
    return (isset($this->session[Def::SESSION_KEY_COOKIE][$name])?$this->session[Def::SESSION_KEY_COOKIE][$name]:null);
  }
  /**
   * Set cookie
   *
   * @param String $name cookie-key
   * @param String $value cookie-value
   * @param String $expire cookie-expire-time > time() + $valide_sec
   * @param String $path cookie-path
   * @param String $comain cookie-domain
   * @param boolean $secure secure cookie
   * @param boolean $httponly httponly
   */
  protected function setCookie($name,$value,$expire,$path,$domain,$secure=false,$httponly=false) {
    $this->updateCookie[$name] = array($value,$expire,$path,$domain,$secure,$httponly);
  }
  /**
   * Set header
   *
   *  It means that it doesn't do render this page.
   *
   * @param String $data session-data
   */
  protected function setHeader($key,$value){
    $this->header = $key.':'.$value;
  }

  /**
   * Do action-process
   *
   */
  public function run(){
    try {
      Log::info(__CLASS__ . '::' . __FUNCTION__ . ' : Action : ' . $this->BRL);
      $per = Log::pre_performance();
      $ret = $this->proc();
      Log::performance1($per,'Action::run : ' . $this->BRL);
      return array(Def::ActionSuccess,
                   ($this->namespace!==null)?$this->namespace:str_replace('\\','.',get_class($this)),
                   $ret,
                   $this->updateSession,
                   $this->moved_permanently,
                   $this->moved_temporary,
                   $this->updateCookie,
                   $this->header,
                   $this->updateArgs
        );
    }catch( \Exception $ex ) {
    }
  }
  /**
   * Implement action-process
   *
   */
  protected function proc() {
    $method  = $this->get_method();
    if     ( strcmp($method,Beak::M_GET) === 0 ) {
      return $this->get();
    }elseif( strcmp($method,Beak::M_GET_ARRAY) === 0 ) {
      return $this->getA();
    }elseif( strcmp($method,Beak::M_GET_RANGE) === 0 ) {
      return $this->getR();
    }elseif( strcmp($method,Beak::M_SET) === 0 ) {
      return $this->set();
    }elseif( strcmp($method,Beak::M_SET_ARRAY) === 0 ) {
      return $this->setA();
    }elseif( strcmp($method,Beak::M_COL_LIST) === 0 ) {
      return $this->cols();
    }elseif( strcmp($method,Beak::M_KEY_LIST) === 0 ) {
      return $this->keys();
    }elseif( strcmp($method,Beak::M_CREATE_COL) === 0 ) {
      return $this->ccol();
    }elseif( strcmp($method,Beak::M_DEL) === 0 ) {
      return $this->del();
    }elseif( strcmp($method,Beak::M_DEL_ARRAY) === 0 ) {
      return $this->delA();
    }elseif( strcmp($method,Beak::M_MV_COL) === 0 ) {
      return $this->mcol();
    }elseif( strcmp($method,Beak::M_SYSTEM) === 0 ) {
      return $this->sys();
    }
    throw new \Exception('Unsupported BRL Method ! :' . $this->BRL);
  }
  /**
   * Hookpoint after proc()
   *
   */
  public function postRun(){}


  /**
   * 
   */
  public function ccol(){
    throw new \Exception('Not implemented ! :' . $this->BRL);
  }
  /**
   * 
   */
  public function keys(){
    throw new \Exception('Not implemented ! :' . $this->BRL);
  }
  /**
   * 
   */
  public function cols(){
    throw new \Exception('Not implemented ! :' . $this->BRL);
  }
  /**
   * 
   */
  public function set(){
    throw new \Exception('Not implemented ! :' . $this->BRL);
  }
  /**
   * 
   */
  public function setA(){
    throw new \Exception('Not implemented ! :' . $this->BRL);
  }
  /**
   * 
   */
  public function getA(){
    throw new \Exception('Not implemented ! :' . $this->BRL);
  }
  /**
   * 
   */
  public function getR(){
    throw new \Exception('Not implemented ! :' . $this->BRL);
  }
  /**
   * 
   */
  public function get(){
    throw new \Exception('Not implemented ! :' . $this->BRL);
  }
  /**
   *
   */
  public function del() {
    throw new \Exception('Not implemented ! :' . $this->BRL);
  }
  /**
   *
   */
  public function delA() {
    throw new \Exception('Not implemented ! :' . $this->BRL);
  }
  /**
   *
   */
  public function mcol() {
    throw new \Exception('Not implemented ! :' . $this->BRL);
  }
  /**
   *
   */
  public function sys() {
    throw new \Exception('Not implemented ! :' . $this->BRL);
  }
}
