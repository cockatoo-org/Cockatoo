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

abstract class AdminAction extends Action {
  protected $BASE_BRL  = 'storage://core-storage/users/';

  public function proc(){
    try {
      $this->preAction();
      $session = $this->getSession();
    
      if ( isset($session[Def::SESSION_KEY_POST]) ) {
        $submit = $session[Def::SESSION_KEY_POST]['submit'];
        if (  $submit === 'add user' ) {
          try{
            $user_data = AccountUtil::get_account($this->BASE_BRL,$session[Def::SESSION_KEY_POST][AccountUtil::KEY_USER]);
          }catch(\Exception $e){
          }
          $user_data[AccountUtil::KEY_USER] = $session[Def::SESSION_KEY_POST][AccountUtil::KEY_USER];
          if ( $session[Def::SESSION_KEY_POST][AccountUtil::KEY_PASSWD] ) {
            $user_data[AccountUtil::KEY_PASSWD] = $session[Def::SESSION_KEY_POST][AccountUtil::KEY_PASSWD];
            $user_data[AccountUtil::KEY_HASH]   = md5($session[Def::SESSION_KEY_POST][AccountUtil::KEY_USER] . $session[Def::SESSION_KEY_POST][AccountUtil::KEY_PASSWD]);
          }elseif( $session[Def::SESSION_KEY_POST][AccountUtil::KEY_HASH] ){
            $user_data[AccountUtil::KEY_HASH]   = $session[Def::SESSION_KEY_POST][AccountUtil::KEY_HASH];
          }
          $user_data[AccountUtil::KEY_EMAIL] = ($session[Def::SESSION_KEY_POST][AccountUtil::KEY_EMAIL])?$session[Def::SESSION_KEY_POST][AccountUtil::KEY_EMAIL]:'';
          $user_data[AccountUtil::KEY_ROOT]  = isset($session[Def::SESSION_KEY_POST][AccountUtil::KEY_ROOT])?'1':'';

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
      $brl = $this->BASE_BRL.'?keys';
      $ret = BeakController::beakQuery(array($brl));
      $keys = array(Beak::Q_UNIQUE_INDEX => $ret[$brl]);
      
      $brl = $this->BASE_BRL.'?getA';
      $ret = BeakController::beakQuery(array(array($brl,$keys)));
      $users = $ret[$brl];
      return array('users' => $users);
    }catch ( \Exception $e ) {
      $this->error($e);
    }
    return array();
  }
  abstract protected function preAction();
  abstract protected function genUserData(&$post_data,&$session_login,&$user_data);
  abstract protected function success(&$submit,&$user_data);
  abstract protected function error(&$exception);

}
