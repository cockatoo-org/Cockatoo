<?php
namespace Cockatoo;
require_once(Config::COCKATOO_ROOT.'action/Action.php');
/**
 * AccountAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/07/07
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */

abstract class AccountAction extends Action {
  protected $BASE_BRL  = 'storage://core-storage/users/';

  public function proc(){
    try {
      $this->preAction();

      $session = $this->getSession();
      $submit = $session[Def::SESSION_KEY_POST]['submit'];
      if ( $submit === 'login' ) {
        $s[AccountUtil::SESSION_LOGIN] = null;
        $user = $session[Def::SESSION_KEY_POST][AccountUtil::KEY_USER];
        if ( ! $user ) {
          throw new \Exception('Invalid account !');
        } 
        $user_data = AccountUtil::get_account($this->BASE_BRL,$user);
        if ( $user_data[AccountUtil::KEY_HASH] === md5($session[Def::SESSION_KEY_POST][AccountUtil::KEY_PASSWD]) ) {
          $s[AccountUtil::SESSION_LOGIN] = $user_data;
        }else{
          throw new \Exception('Invalid account !');
        }
        $this->updateSession($s);
        $this->success($submit,$user_data);
      }elseif ( $submit === 'logout' ) {
        $s[AccountUtil::SESSION_LOGIN] = null;
        $this->updateSession($s);
        $this->success($submit,$s);
      }elseif ( $submit === 'profile' ) {
        $user  = $session[AccountUtil::SESSION_LOGIN][AccountUtil::KEY_USER];
        if ( ! $user ) {
          throw new \Exception('Who are you !');
        }
        $this->success($submit,$session[AccountUtil::SESSION_LOGIN]);
      }elseif ( $submit === 'update profile' ) {
        $up_hash = $session[AccountUtil::SESSION_LOGIN][AccountUtil::KEY_HASH];
        if ( $session[Def::SESSION_KEY_POST][AccountUtil::KEY_PASSWD] ){
          if ( $session[Def::SESSION_KEY_POST][AccountUtil::KEY_PASSWD] !== $session[Def::SESSION_KEY_POST][AccountUtil::KEY_CONFIRM] ){
            throw new \Exception('Unmatch password !');
          }
          $up_hash = md5($session[Def::SESSION_KEY_POST][AccountUtil::KEY_PASSWD]);
        }
        $user_data = array(AccountUtil::KEY_USER  => $session[AccountUtil::SESSION_LOGIN][AccountUtil::KEY_USER],
                      AccountUtil::KEY_HASH  => $up_hash,
                      AccountUtil::KEY_EMAIL => ($session[Def::SESSION_KEY_POST][AccountUtil::KEY_EMAIL])?$session[Def::SESSION_KEY_POST][AccountUtil::KEY_EMAIL]:$session[AccountUtil::SESSION_LOGIN][AccountUtil::KEY_EMAIL]);
        $user_data = $this->genUserData($session[Def::SESSION_KEY_POST],$session[AccountUtil::SESSION_LOGIN],$user_data);
        AccountUtil::save_account($this->BASE_BRL,$user_data);
        $s[AccountUtil::SESSION_LOGIN] = $user_data;
        $this->updateSession($s);
        $this->success($submit,$user_data);
      }elseif ( $submit === 'password reset' ) {
        $up_user = $session[Def::SESSION_KEY_POST][AccountUtil::KEY_USER];
        $up_passwd = AccountUtil::mkpasswd();
        $up_hash = md5($up_passwd);
        $user_data = AccountUtil::get_account($this->BASE_BRL,$up_user);
        $user_data[AccountUtil::KEY_HASH] = $up_hash;
        $user_data[AccountUtil::KEY_USER] = $up_user;
        AccountUtil::save_account($this->BASE_BRL,$user_data);
        $user_data[AccountUtil::KEY_PASSWD]=$up_passwd;
        $this->success($submit,$user_data);
      }else{
        throw new \Exception('Invalid Request !');
      }
    }catch ( \Exception $e ) {
      $this->error($e);
    }
    return array();
  }
  public function postProc(){
  }


  abstract protected function preAction();
  abstract protected function genUserData(&$post_data,&$session_login,&$user_data);
  abstract protected function success(&$submit,&$user_data);
  abstract protected function error(&$exception);

}

