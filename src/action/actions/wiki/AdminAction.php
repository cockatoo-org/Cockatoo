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
  private $BASE_BRL=WikiConfig::USER_COLLECTION;
  public function proc(){
    try{
      $this->setNamespace('wiki');
      $session = $this->getSession();
    
      $user  = $session[\Cockatoo\AccountUtil::SESSION_LOGIN][\Cockatoo\AccountUtil::KEY_USER];
      $root  = $session[\Cockatoo\AccountUtil::SESSION_LOGIN]['root'];
      if ( ! $root ) {
        throw new \Exception('You do not have a permission !!');
      }

      if ( isset($session[\Cockatoo\Def::SESSION_KEY_POST]) ) {
        $submit = $session[\Cockatoo\Def::SESSION_KEY_POST]['submit'];
        if (  $submit === 'add user' ) {
          $up_user = $session[\Cockatoo\Def::SESSION_KEY_POST][\Cockatoo\AccountUtil::KEY_USER];
          $up_passwd = ($session[\Cockatoo\Def::SESSION_KEY_POST][\Cockatoo\AccountUtil::KEY_PASSWD])?($session[\Cockatoo\Def::SESSION_KEY_POST][\Cockatoo\AccountUtil::KEY_PASSWD]):'';
          $up_hash   = ($up_passwd)?md5($up_passwd):$session[\Cockatoo\Def::SESSION_KEY_POST][\Cockatoo\AccountUtil::KEY_HASH];
          $up_email  = $session[\Cockatoo\Def::SESSION_KEY_POST][\Cockatoo\AccountUtil::KEY_EMAIL];
          $up_root   = isset($session[\Cockatoo\Def::SESSION_KEY_POST]['root']);
          if ( ! $up_user or 
               ! $up_email ){
            throw new \Exception('Validate error !! ');
          }
          if ( ! $up_passwd and ! $up_hash ) {
            $up_passwd = \Cockatoo\AccountUtil::mkpasswd();
            $up_hash = md5($up_passwd);
          }
          $data = array(\Cockatoo\AccountUtil::KEY_USER => $up_user,
                        \Cockatoo\AccountUtil::KEY_HASH => $up_hash,
                        \Cockatoo\AccountUtil::KEY_EMAIL=> $up_email,
                        'root' => $up_root);
          \Cockatoo\AccountUtil::save_account($this->BASE_BRL, $data);
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
        }elseif (  $submit === 'remove user') {
          if ( ! $session[\Cockatoo\Def::SESSION_KEY_POST][\Cockatoo\AccountUtil::KEY_USER] ){
            throw new \Exception('Validate error !! ');
          }
          \Cockatoo\AccountUtil::remove_account($this->BASE_BRL,$session[\Cockatoo\Def::SESSION_KEY_POST][\Cockatoo\AccountUtil::KEY_USER]);
        }
      }
      // User list
      $brl = WikiConfig::USER_COLLECTION.'?keys';
      // $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'wiki','users','/',\Cockatoo\Beak::M_KEY_LIST,array(),array());
      $ret = \Cockatoo\BeakController::beakQuery(array($brl));
      $keys = array(\Cockatoo\Beak::Q_UNIQUE_INDEX => $ret[$brl]);

      $brl = WikiConfig::USER_COLLECTION.'?getA';
      //$brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'wiki','users','/'.$user,\Cockatoo\Beak::M_GET_ARRAY,array(),array());
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