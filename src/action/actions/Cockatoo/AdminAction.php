<?php
namespace Cockatoo;
require_once(Config::COCKATOO_ROOT.'action/Action.php');
/**
 * AdminAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/07/07
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
class AdminAction extends Action {
  protected $BASE_BRL = 'storage://core-storage/users/';
  protected $MAIL_FROM= 'root@cockatoo.jp';
  protected $REPLY_TO = 'root@cockatoo.jp';
  protected $EREDIRECT = 'login';
 
  protected function preAction(){
    $this->setNamespace('admin');

    $session = $this->getSession();
    $root  = $session[AccountUtil::SESSION_LOGIN]['root'];
    if ( ! $root ) {
      throw new \Exception('You do not have a permission !!');
    }
  }
  protected function genUserData(&$post_data,&$session_login,&$user_data){
    $this->passwd = $user_data[AccountUtil::KEY_PASSWD];
    return $user_data;
  }
  protected function success(&$submit,&$user_data){
    if (  $submit === 'add user' ) {
      $user_data[AccountUtil::KEY_PASSWD] = $this->passwd;
      AccountUtil::mail($user_data,$this->MAIL_FROM,$this->REPLY_TO);
      $user_data[AccountUtil::KEY_PASSWD] = '';
    }
  }
  protected function error(&$e){
    $s[Def::SESSION_KEY_ERROR] = $e->getMessage();
    $this->updateSession($s);
    $this->setMovedTemporary($this->EREDIRECT);
    Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
  }

  public function proc(){
    try {
      $this->preAction();
      $session = $this->getSession();
    
      if ( isset($session[Def::SESSION_KEY_POST]) ) {
        $submit = $session[Def::SESSION_KEY_POST]['submit'];
        if (  $submit === 'add user' ) {
          $user_data = AccountUtil::get_account($this->BASE_BRL,$session[Def::SESSION_KEY_POST][AccountUtil::KEY_USER]);
          $user_data[AccountUtil::KEY_USER] = $session[Def::SESSION_KEY_POST][AccountUtil::KEY_USER];
          if ( $session[Def::SESSION_KEY_POST][AccountUtil::KEY_PASSWD] ) {
            $user_data[AccountUtil::KEY_PASSWD] = $session[Def::SESSION_KEY_POST][AccountUtil::KEY_PASSWD];
            $user_data[AccountUtil::KEY_HASH]   = md5($session[Def::SESSION_KEY_POST][AccountUtil::KEY_USER] . $session[Def::SESSION_KEY_POST][AccountUtil::KEY_PASSWD]);
          }elseif( $session[Def::SESSION_KEY_POST][AccountUtil::KEY_HASH] ){
            $user_data[AccountUtil::KEY_HASH]   = $session[Def::SESSION_KEY_POST][AccountUtil::KEY_HASH];
          }
          $user_data[AccountUtil::KEY_EMAIL] = ($session[Def::SESSION_KEY_POST][AccountUtil::KEY_EMAIL])?$session[Def::SESSION_KEY_POST][AccountUtil::KEY_EMAIL]:'';
          $user_data[AccountUtil::KEY_WRITABLE]  = isset($session[Def::SESSION_KEY_POST][AccountUtil::KEY_WRITABLE])?'1':'';
          $user_data[AccountUtil::KEY_ROOT]  = isset($session[Def::SESSION_KEY_POST][AccountUtil::KEY_ROOT])?'1':'';
          if ( $user_data[AccountUtil::KEY_ROOT] === '1' ) {
            $user_data[AccountUtil::KEY_WRITABLE] = '1';
          }

          if ( ! $user_data[AccountUtil::KEY_HASH] ) {
            $user_data[AccountUtil::KEY_PASSWD] = AccountUtil::mkpasswd();
            $user_data[AccountUtil::KEY_HASH]   = md5($user_data[AccountUtil::KEY_USER] . $user_data[AccountUtil::KEY_PASSWD]);
          }
          $user_data = $this->genUserData($session[Def::SESSION_KEY_POST],$session[AccountUtil::SESSION_LOGIN],$user_data);
          unset($user_data[AccountUtil::KEY_PASSWD]);
          AccountUtil::save_account($this->BASE_BRL,$user_data);
          $this->success($submit,$user_data);
        }elseif (  $submit === 'remove user') {
          if ( ! $session[Def::SESSION_KEY_POST][AccountUtil::KEY_USER] ){
            throw new \Exception('Validate error !! ');
          }
          AccountUtil::remove_account($this->BASE_BRL,$session[Def::SESSION_KEY_POST][AccountUtil::KEY_USER]);
          $this->success($submit,$session[Def::SESSION_KEY_POST][AccountUtil::KEY_USER]);
        }
      }
      // User list
      $brl = $this->BASE_BRL.'?getR';
      $users = BeakController::beakSimpleQuery($brl);
      return array('users' => $users);
    }catch ( \Exception $e ) {
      $this->error($e);
    }
    return array();
  }

  public function postProc(){
  }
}
