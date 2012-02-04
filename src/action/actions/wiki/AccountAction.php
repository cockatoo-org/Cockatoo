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
    $this->setNamespace('wiki');

    $session = $this->getSession();
    $submit = $session[\Cockatoo\Def::SESSION_KEY_POST]['submit'];
    if ( !$submit){
      $submit = 'login';
    }
    if ( $submit === 'login' ) {
      $user = $session[\Cockatoo\Def::SESSION_KEY_POST]['user'];
      $s['login'] = null;
      if ( ! $user ) {
        // logout
      } else {
        // login
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STRAGE,'wiki','users','/'.$user,\Cockatoo\Beak::M_GET,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array($brl));
        if ( $ret[$brl] and $ret[$brl]['hash'] === md5($session[\Cockatoo\Def::SESSION_KEY_POST]['passwd']) ) {
          $s['login'] = $ret[$brl];
        }else{
          $s['emessage'] = 'Login failed ! ';
          $this->updateSession($s);
          $this->setRedirect('/error');
          return;
        }
      }
      $this->updateSession($s);
      $this->setRedirect('/view');
    }

    return array();
  }

  public function postProc(){
  }
}