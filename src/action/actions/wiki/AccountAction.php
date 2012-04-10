<?php
namespace wiki;
//require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/Action.php');
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

class AccountAction extends \Cockatoo\AccountAction {
  const REDIRECT_PATH='/wiki/view';

  protected function preAction(){
    $this->setNamespace('wiki');
    $this->BASE_BRL=WikiConfig::USER_COLLECTION;
  }
  protected function genUserData(&$post_data,&$session_login,&$user_data){
    return $user_data;
  }
  protected function success(&$submit,&$user_data){
    if ( $submit === 'password reset' ) {
      mail($user_data[\Cockatoo\AccountUtil::KEY_EMAIL],
           'Your profile changed',
           'Your new profile'."\n".
           '  User     : ' . $user_data[\Cockatoo\AccountUtil::KEY_USER] ."\n".
           '  Password : ' . (isset($user_data[\Cockatoo\AccountUtil::KEY_PASSWD])?'change !':'(no change)')."\n".
           '  Email    : ' . $user_data[\Cockatoo\AccountUtil::KEY_EMAIL]."\n".
           '  Root     : ' . ($user_data[\Cockatoo\AccountUtil::KEY_ROOT]?'YES':'NO'),
           'From: '.WikiConfig::MAIL_FROM ."\r\n" .
           'Reply-To: '.WikiConfig::MAIL_FROM ."\r\n"
        );
    }elseif($submit === 'profile' ){
      return;
    }
    $this->setRedirect(self::REDIRECT_PATH);
  }
  protected function error(&$e){
    $s['emessage'] = $e->getMessage();
    $this->updateSession($s);
    $this->setRedirect('/wiki/error');
    \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
  }
}