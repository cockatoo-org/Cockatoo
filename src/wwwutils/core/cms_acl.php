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

}

class DefaultCmsAuth extends CmsAuth {
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
}

function check_auth( $service , $account , $role ) {
  $auth=array();
  // check system admin
  $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,'',Beak::M_GET);
  $ret = BeakController::beakQuery(array($brl));
  $auth = $ret[$brl];
  if ( $auth['account'][$account] and $auth['account'][$account] > 0 ) {
    return true;
  }
  // check service role
  $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,$service,Beak::M_GET);
  $ret = BeakController::beakQuery(array($brl));
  $auth = $ret[$brl];
  if ( $auth['account'][$account] and $auth['account'][$account] >= $role ) {
    return true;
  }
  return false;
}
function set_auth( $service , $account , $role = -1 ) {
  $auth=array();
  $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,$service,Beak::M_GET);
  $ret = BeakController::beakQuery(array($brl));
  if ( $ret[$brl] ) {
    $auth = $ret[$brl];
  }
  $auth['account'][$account]= $role;
  if ( $role < 0 ) {
    unset($auth['account'][$account]);
  }

  $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,$service,Beak::M_SET);
  $ret = BeakController::beakQuery(array(array($brl,$auth)));
  if ( ! $ret[$brl] ) {
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
