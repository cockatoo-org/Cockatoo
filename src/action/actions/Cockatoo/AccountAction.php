<?php
namespace Cockatoo;
class AccountAction extends AuthAction {
  protected $submit;
  protected function first_hook() {
    $session = $this->getSession();
    $this->submit = $session[Def::SESSION_KEY_POST]['submit'];
  }
  protected function login_hook(&$user_data) {
    $session = $this->getSession();
    if ( $this->submit === 'login' ) {
      $user = $session[Def::SESSION_KEY_POST][AccountUtil::KEY_USER];
      if ( ! $user ) {
        throw new \Exception('Invalid account !');
      } 
      $user_data = AccountUtil::get_account($this->BASE_BRL,$user);
      if ( ! $user_data || $user_data[AccountUtil::KEY_HASH] !== md5($user . $session[Def::SESSION_KEY_POST][AccountUtil::KEY_PASSWD]) ) {
        throw new \Exception('Invalid account !');
      }
      return $user_data;
    }elseif ( $this->submit === 'password reset' ) {
      $user = $session[Def::SESSION_KEY_POST][AccountUtil::KEY_USER];
      $passwd = AccountUtil::mkpasswd();
      $hash = md5($user.$passwd);
      $user_data = AccountUtil::get_account($this->BASE_BRL,$user);
      if ( ! $user_data ) {
        throw new \Exception('Invalid account !');
      }
      $user_data[AccountUtil::KEY_HASH] = $hash;
      $user_data[AccountUtil::KEY_USER] = $user;
      AccountUtil::save_account($this->BASE_BRL,$user_data);
      // mail notice
      $user_data[AccountUtil::KEY_PASSWD]=$passwd;
      AccountUtil::mail($user_data,$this->MAIL_FROM,$this->REPLY_TO);
      return null;
    }
    return null;
  }
  protected function already_hook(&$user_data) {
    $session = $this->getSession();
    if ( $this->submit === 'logout' ) {
      return null;
    }elseif ( $this->submit === 'update profile' ) {
      if ( $session[Def::SESSION_KEY_POST][AccountUtil::KEY_PASSWD] ){
        if ( $session[Def::SESSION_KEY_POST][AccountUtil::KEY_PASSWD] !== $session[Def::SESSION_KEY_POST][AccountUtil::KEY_CONFIRM] ){
          throw new \Exception('Unmatch password !');
        }
        $user_data[AccountUtil::KEY_HASH] = md5($user_data[AccountUtil::KEY_USER] . $session[Def::SESSION_KEY_POST][AccountUtil::KEY_PASSWD]);
      }
      if ( $session[Def::SESSION_KEY_POST][AccountUtil::KEY_EMAIL] ) {
        $user_data[AccountUtil::KEY_EMAIL] = $session[Def::SESSION_KEY_POST][AccountUtil::KEY_EMAIL];
      }
      AccountUtil::save_account($this->BASE_BRL,$user_data);
      return $user_data;
    }
    return $user_data;
  }
  protected function success_hook(&$user_data){
    $session = $this->getSession();
    $redirect = $session[Def::SESSION_KEY_POST]['r'];
    if ( $redirect ) {
      $this->setMovedTemporary($redirect);
//    }else{
//      $this->setMovedTemporary($this->REDIRECT);
    }
    return $user_data;
  }
  protected function error_hook(&$e){
    parent::error_hook($e);
    $session = $this->getSession();
    $redirect = $session[Def::SESSION_KEY_POST]['r'];
    if ( $redirect ) {
      $this->setMovedTemporary($redirect);
    }
  }
}
