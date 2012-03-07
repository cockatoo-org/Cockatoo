<?php
namespace wiki;
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/Action.php');
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

class AdminAction extends \Cockatoo\Action {
  public function proc(){
    try{
      $this->setNamespace('wiki');
      $session = $this->getSession();
    
      $user  = $session['login']['user'];
      $root  = $session['login']['root'];
      if ( ! $root ) {
        throw new \Exception('You do not have a permission !!');
      }

      if ( isset($session[\Cockatoo\Def::SESSION_KEY_POST]) ) {
        if (  $session[\Cockatoo\Def::SESSION_KEY_POST]['submit']==='add user' ) {
          $up_user = $session[\Cockatoo\Def::SESSION_KEY_POST]['user'];
          $up_passwd = ($session[\Cockatoo\Def::SESSION_KEY_POST]['passwd'])?($session[\Cockatoo\Def::SESSION_KEY_POST]['passwd']):'';
          $up_hash   = ($up_passwd)?md5($up_passwd):$session[\Cockatoo\Def::SESSION_KEY_POST]['hash'];
          $up_email  = $session[\Cockatoo\Def::SESSION_KEY_POST]['email'];
          $up_root   = isset($session[\Cockatoo\Def::SESSION_KEY_POST]['root']);
          if ( ! $up_user or 
               ! $up_email ){
            throw new \Exception('Validate error !! ');
          }
          if ( ! $up_passwd and ! $up_hash ) {
            $up_passwd = Lib::mkpasswd();
            $up_hash = md5($up_passwd);
          }
          $data = array('user' => $up_user,
                        'hash' => $up_hash,
                        'email'=> $up_email,
                        'root' => $up_root);
          Lib::save_account($data);
          if ( WikiConfig::MAIL_NOTIFICATION ) {
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
        }elseif (  $session[\Cockatoo\Def::SESSION_KEY_POST]['submit']==='remove user') {
          if ( ! $session[\Cockatoo\Def::SESSION_KEY_POST]['user'] ){
            throw new \Exception('Validate error !! ');
          }
          Lib::remove_account($session[\Cockatoo\Def::SESSION_KEY_POST]['user']);
        }
      }
      // User list
      $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'wiki','users','/',\Cockatoo\Beak::M_KEY_LIST,array(),array());
      $ret = \Cockatoo\BeakController::beakQuery(array($brl));
      $keys = array(\Cockatoo\Beak::Q_UNIQUE_INDEX => $ret[$brl]);
      $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'wiki','users','/'.$user,\Cockatoo\Beak::M_GET_ARRAY,array(),array());
      $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$keys)));
      $users = $ret[$brl];
      return array('users' => $users);

    }catch ( \Exception $e ) {
      $s['emessage'] = $e->getMessage();
      $this->updateSession($s);
      $this->setRedirect('/wiki/error');
       \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
    }
  }

  public function postProc(){
  }
}