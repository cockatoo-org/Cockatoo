<?php
namespace wiki;
require_once($COCKATOO_ROOT.'action/Action.php');
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
    $this->setNamespace('wiki');
    $session = $this->getSession();
    
    $user  = $session['login']['user'];
    $root  = $session['login']['root'];
    if ( ! $root ) {
      $s['emessage'] = 'You do not have a permission !!';
      $this->updateSession($s);
      $this->setRedirect('/error');
      return;
    }

    if ( isset($session[\Cockatoo\Def::SESSION_KEY_POST]) ) {
      if (  $session[\Cockatoo\Def::SESSION_KEY_POST]['submit']==='add user' ) {
        $user = array('user'   => $session[\Cockatoo\Def::SESSION_KEY_POST]['user'],
                      'hash' => ($session[\Cockatoo\Def::SESSION_KEY_POST]['passwd'])?(md5($session[\Cockatoo\Def::SESSION_KEY_POST]['passwd'])):($session[\Cockatoo\Def::SESSION_KEY_POST]['hash']),
                      'email'  => $session[\Cockatoo\Def::SESSION_KEY_POST]['email'],
                      'root' => isset($session[\Cockatoo\Def::SESSION_KEY_POST]['root']));
        if ( ! $user['user'] or
             ! $user['hash'] or
             ! $user['email'] ){
          $s['emessage'] = 'Validate error !! ';
          $this->updateSession($s);
          $this->setRedirect('/error');
          return;
        }
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STRAGE,'wiki','users','/'.$user['user'],\Cockatoo\Beak::M_SET,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$user)));
      }elseif (  $session[\Cockatoo\Def::SESSION_KEY_POST]['submit']==='remove user') {
        if ( ! $session[\Cockatoo\Def::SESSION_KEY_POST]['user'] ){
          $s['emessage'] = 'Validate error !! ';
          $this->updateSession($s);
          $this->setRedirect('/error');
          return;
        }
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STRAGE,'wiki','users','/'.$session[\Cockatoo\Def::SESSION_KEY_POST]['user'],\Cockatoo\Beak::M_DEL,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array($brl));
      }
    }
    // User list
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STRAGE,'wiki','users','/',\Cockatoo\Beak::M_KEY_LIST,array(),array());
    $ret = \Cockatoo\BeakController::beakQuery(array($brl));
    $keys = array(\Cockatoo\Beak::Q_UNIQUE_INDEX => $ret[$brl]);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STRAGE,'wiki','users','/'.$user,\Cockatoo\Beak::M_GET_ARRAY,array(),array());
    $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$keys)));
    $users = $ret[$brl];

    return array('users' => $users);
  }

  public function postProc(){
  }
}