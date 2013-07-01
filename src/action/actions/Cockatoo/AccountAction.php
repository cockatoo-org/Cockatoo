<?php
namespace Cockatoo;
class AccountAction extends AuthAction {
  protected function login_hook(&$user_data) {
    $session = $this->getSession();
    $submit = $session[Def::SESSION_KEY_POST]['submit'];

    if      ( $submit === 'logout' ) {
      $user_data = null;
    }elseif ( $submit === 'login' ) {
      $user = $session[Def::SESSION_KEY_POST][AccountUtil::KEY_USER];
      if ( ! $user ) {
        throw new \Exception('Invalid account !');
      } 
      $user_data = AccountUtil::get_account($this->BASE_BRL,$user);
      if ( ! $user_data || $user_data[AccountUtil::KEY_HASH] !== md5($user . $session[Def::SESSION_KEY_POST][AccountUtil::KEY_PASSWD]) ) {
        throw new \Exception('Invalid account !');
      }
    }elseif ( $submit === 'password reset' ) {
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
      $user_data = null;
    }elseif ( $submit === 'update profile' ) {
      if ( $session[Def::SESSION_KEY_POST][AccountUtil::KEY_NAME] ) {
        $user_data[AccountUtil::KEY_NAME] = $session[Def::SESSION_KEY_POST][AccountUtil::KEY_NAME];
      }
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
    }else {
      if ( ! $user_data ) {
        throw new \Exception('Unexpected request');
      }
    }
    $redirect = $session[Def::SESSION_KEY_POST]['r'];
    if ( $redirect ) {
      $this->setMovedTemporary($redirect);
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
