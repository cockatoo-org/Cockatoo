<?php
namespace wiki;
require_once($COCKATOO_ROOT.'action/Action.php');
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

class AccountAction extends \Cockatoo\Action {
  public function proc(){
    try{
      $this->setNamespace('wiki');

      $session = $this->getSession();
      $submit = $session[\Cockatoo\Def::SESSION_KEY_POST]['submit'];
      if ( $submit === 'login' ) {
        $s['login'] = null;
        $user = $session[\Cockatoo\Def::SESSION_KEY_POST]['user'];
        if ( ! $user ) {
          throw new \Exception('Invalid account !');
        } 
        $data = Lib::get_account($user);
        if ( $data['hash'] === md5($session[\Cockatoo\Def::SESSION_KEY_POST]['passwd']) ) {
          $s['login'] = $data;
        }else{
          throw new \Exception('Invalid account !');
        }
        $this->updateSession($s);
        $this->setRedirect('/view');
      }elseif ( $submit === 'logout' ) {
        $s['login'] = null;
        $this->updateSession($s);
        $this->setRedirect('/view');
      }elseif ( $submit === 'profile' ) {
        $user  = $session['login']['user'];
        if ( ! $user ) {
          throw new \Exception('Who are you !');
        }
      }elseif ( $submit === 'update profile' ) {
        $up_hash = $session['login']['hash'];
        if ( $session[\Cockatoo\Def::SESSION_KEY_POST]['passwd'] ){
          if ( $session[\Cockatoo\Def::SESSION_KEY_POST]['passwd'] !== $session[\Cockatoo\Def::SESSION_KEY_POST]['confirm'] ){
            throw new \Exception('Unmatch password !');
          }
          $up_hash = md5($session[\Cockatoo\Def::SESSION_KEY_POST]['passwd']);
        }
        $data = array('user'  => $session['login']['user'],
                      'hash'  => $up_hash,
                      'email' => ($session[\Cockatoo\Def::SESSION_KEY_POST]['email'])?$session[\Cockatoo\Def::SESSION_KEY_POST]['email']:$session['login']['email'],
                      'root'  => $session['login']['root']);
        Lib::save_account($data);
        $this->setRedirect('/view');
      }elseif ( $submit === 'password reset' ) {
        $up_user = $session[\Cockatoo\Def::SESSION_KEY_POST]['user'];
        $up_passwd = Lib::mkpasswd();
        $up_hash = md5($up_passwd);
        $data = Lib::get_account($up_user);
        $data['hash'] = $up_hash;
        $up_email = $data['email'];
        $up_root = $data['root'];
        Lib::save_account($data);
        $this->setRedirect('/view');
        mail($up_email,
             'Your profile changed',
             'Your new profile'."\n".
             '  User     : ' . $up_user ."\n".
             '  Password : ' . (($up_passwd)?$up_passwd:'(no change)')."\n".
             '  Email    : ' . $up_email."\n".
             '  Root     : ' . ($up_root?'YES':'NO'),
             'From: '.WikiConfig::MAIL_FROM ."\r\n" .
             'Reply-To: '.WikiConfig::MAIL_FROM ."\r\n"
          );
      }
    }catch ( \Exception $e ) {
      $s['emessage'] = $e->getMessage();
      $this->updateSession($s);
      $this->setRedirect('/error');
      \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
    }
    return array();
  }

  public function postProc(){
  }
}