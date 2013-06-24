<?php
/**
 * cms_acl.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/02/22
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
namespace Cockatoo;
/**
 * ??????????
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */

abstract class CmsAuth {
  const READABLE = 0x1;
  const WRITABLE = 0x2;
  const ADMIN    = 0x8;
  public static $instance;
  protected $header;
  protected $server;
  protected $get;
  protected $cookie;
  /**
   * Constructor
   *
   * @param Array $header $_HEADER
   * @param Array $server $_SERVER
   * @param Array $get    $_GET
   * @param Array $cookie $_COOKIE
   */
  public function __construct(&$header,&$server,&$get,&$cookie){
    $this->header = &$header;
    $this->server = &$server;
    $this->get    = &$get;
    $this->cookie = &$cookie;
  }
  
  public function is_admin($service){
    return check_auth($service,$this->get_account(),self::ADMIN);
  }
  public function is_readable($service){
    return check_auth($service,$this->get_account(),self::READABLE);
  }
  public function is_writable($service){
    return check_auth($service,$this->get_account(),self::WRITABLE);
  }
  abstract public function get_account();
  abstract public function get_loginpage();
}

class SkipCmsAuth extends CmsAuth {
  public function is_admin($service){
    return true;
  }
  public function is_readable($service){
    return true;
  }
  public function is_writable($service){
    return true;
  }
  public function get_account(){
    return 'admin';
  }
  public function get_loginpage(){
    return null;
  }
}

require_once(Config::COCKATOO_ROOT.'utils/session.php');
class DefaultCmsAuth extends CmsAuth {
  public function __construct(&$header,&$server,&$get,&$cookie){
    parent::__construct($header,$server,$get,$cookie);
    $sessionID = isset($_COOKIE[Config::SESSION_COOKIE])?$_COOKIE[Config::SESSION_COOKIE]:null;
    if ( $sessionID ) {
      $session = getSession($sessionID,'core');
      $this->account= $session['login']['user'];
    }
  }  
  public function get_account(){
    return $this->account;
  }
  public function get_loginpage(){
    return '/core/login?r=/_cms_/cms_page.php';
  }
}

function check_auth( $service , $account , $role ) {
  // check system admin
  $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,'',Beak::M_GET);
  $auth = BeakController::beakSimpleQuery($brl);
  if ( $auth and $auth['account'] and $auth['account'][$account] and $auth['account'][$account] > 0 ) {
    return true;
  }
  // check service role
  $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,$service,Beak::M_GET);
  $auth = BeakController::beakSimpleQuery($brl);
  if ( $auth and $auth['account'] and $auth['account'][$account] and $auth['account'][$account] >= $role ) {
    return true;
  }
  return false;
}
function set_auth( $service , $account , $role = -1 ) {
  $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,$service,Beak::M_GET);
  $auth = BeakController::beakSimpleQuery($brl);
  if ( ! $auth ) {
    $auth = array('account' => array());
  }
  $auth['account'][$account]= $role;
  if ( $role < 0 ) {
    unset($auth['account'][$account]);
  }

  $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,$service,Beak::M_SET);
  $ret = BeakController::beakSimpleQuery($brl,$auth);
  if ( ! $ret ) {
    throw new \Exception('Fail to set : ' . $brl);
  }
}

function init_auth(){
  $clazz = Config::CMSAuth;
  CmsAuth::$instance = new $clazz($header,$server,$get,$cookie);
}
init_auth();

function get_account( ) {
  return CmsAuth::$instance->get_account();
}

function check_admin( $service ) {
  if ( ! CmsAuth::$instance->is_admin($service ) ) {
    throw new \Exception('You do not have ADMIN permission');
  }
}
function check_writable( $service ) {
  if ( ! CmsAuth::$instance->is_writable($service ) ) {
    throw new \Exception('You do not have WRITABLE permission');
  }
}
function is_readable( $service ) {
  return CmsAuth::$instance->is_readable($service );
}
function get_loginpage( ) {
  return CmsAuth::$instance->get_loginpage( );
}
