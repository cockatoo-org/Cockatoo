<?php
namespace Cockatoo;
require_once(Config::COCKATOO_ROOT.'action/Action.php');
/**
 * AuthAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/07/07
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */

abstract class AuthAction extends Action {
  protected $EXPIRES  = 315360000; // 10 years
  protected $BASE_BRL = 'storage://core-storage/users/';
  protected $MAIL_FROM= 'root@cockatoo.jp';
  protected $REPLY_TO = 'root@cockatoo.jp';
  protected $EREDIRECT= '/';

  protected $NAMESPACE = 'login';

  protected function first_hook() {}
  abstract protected function login_hook(&$user_data);
  abstract protected function already_hook(&$user_data);

  protected function success_hook(&$user_data){
    return $user_data;
  }
  protected function error_hook(&$e){
    $s[Def::SESSION_KEY_ERROR] = $e->getMessage();
    $this->updateSession($s);
    $this->setMovedTemporary($this->EREDIRECT);
    Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
  }


  final public function proc(){
    try {
      $this->first_hook();
      $this->setNamespace($NAMESPACE);
      $now = time();

      $session = $this->getSession();
      $user_data = $session[AccountUtil::SESSION_LOGIN];
      if ( $user_data && $user_data[AccountUtil::KEY_USER] ) {
        $user_data = $session[AccountUtil::SESSION_LOGIN];
        if ( $user_data[AccountUtil::KEY_EXPIRES] < $now ) {
          throw new \Exception('Login expired !! : ' );
        }else{
          $user_data[AccountUtil::KEY_EXPIRES] = $now + $this->EXPIRES;
        }
        $user_data = $this->already_hook($user_data);
        $s[AccountUtil::SESSION_LOGIN] = $user_data;
        $this->updateSession($s);
      }else {
        $user_data = $this->login_hook($user_data);
        if ( $user_data && $user_data[AccountUtil::KEY_USER] ) {
          // login success
          $user_data[AccountUtil::KEY_EXPIRES] = $now + $this->EXPIRES;
        }
        $s[AccountUtil::SESSION_LOGIN] = $user_data;
        $this->updateSession($s);
      }
      $this->success_hook($user_data);
    }catch ( \Exception $e ) {
      $s[AccountUtil::SESSION_LOGIN] = null;
      $this->updateSession($s);
      $this->error_hook($e);
    }
    return array();
  }
  public function postProc(){
  }
}


