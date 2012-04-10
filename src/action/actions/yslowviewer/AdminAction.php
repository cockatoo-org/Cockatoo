<?php
namespace yslowviewer;
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
class AdminAction extends \Cockatoo\AdminAction {
  protected function preAction(){
    $this->setNamespace('yslowviewer');
    $this->BASE_BRL=YslowviewerConfig::USER_COLLECTION;

    $session = $this->getSession();
    $root  = $session[\Cockatoo\AccountUtil::SESSION_LOGIN]['root'];
    if ( ! $root ) {
      throw new \Exception('You do not have a permission !!');
    }
  }
  protected function genUserData(&$post_data,&$session_login,&$user_data){
    $this->passwd = $user_data[\Cockatoo\AccountUtil::KEY_PASSWD];
    return $user_data;
  }
  protected function success(&$submit,&$user_data){
    if (  $submit === 'add user' ) {
      mail($user_data[\Cockatoo\AccountUtil::KEY_EMAIL],
           'Your profile changed',
           'Your new profile'."\n".
           '  User     : ' . $user_data[\Cockatoo\AccountUtil::KEY_USER] ."\n".
           '  Password : ' . (($this->passwd)?$this->passwd:'(no change)')."\n".
           '  Email    : ' . $user_data[\Cockatoo\AccountUtil::KEY_EMAIL]."\n".
           '  Root     : ' . ($user_data[\Cockatoo\AccountUtil::KEY_ROOT]?'YES':'NO'),
           'From: '.YslowviewerConfig::MAIL_FROM ."\r\n" .
           'Reply-To: '.YslowviewerConfig::MAIL_FROM ."\r\n"
        );
    }
  }
  protected function error(&$e){
    $s['emessage'] = $e->getMessage();
    $this->updateSession($s);
    $this->setRedirect('/yslowviewer/default/error');
    \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
  }

  public function postProc(){
  }
}
