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
  public $BRL;
  public $method;
  public $queries;
  public $sessionID;
  public $service;
  public $args;
  public $session = null;
  public $updateSession = array();
  public $redirect = null;
  public $namespace = null;
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
    $this->namespace = str_replace('\\','.',get_class($this));
    list($P,$D,$C,$p,$m,$q,$c) = parse_brl($this->BRL);
    $this->method = $m;
    $this->queries = $q;
  }
  /**
   * Prepare process
   *
   * @param String $context  Action context
   */
  public function set($args,$hide) {
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
    $this->updateSession = $data;
  }
  /**
   * Get session
   *
   * @return array session objcet
   */
  protected function getSession(){
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
   * @param String $data session-data
   */
  protected function setRedirect($redirect){
    $this->redirect = $redirect;
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
                   $this->namespace,
                   $ret,
                   $this->updateSession,
                   $this->redirect,
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
  abstract protected function proc();
  /**
   * Hookpoint after proc()
   *
   */
  public function postRun(){}
}
