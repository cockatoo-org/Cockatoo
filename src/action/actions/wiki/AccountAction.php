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
      $name = $session[\Cockatoo\Def::SESSION_KEY_POST]['name'];
      $s['login'] = null;
      if ( ! $name ) {
        // logout
      } else {
        // login
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STRAGE,'wiki','users','/'.$name,\Cockatoo\Beak::M_GET,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array($brl));
        if ( $ret[$brl] ) {
          $s['login'] = $ret[$brl];
        }
      }
      $this->updateSession($s);
      $this->setRedirect('/view');
    }elseif ( $submit === 'confirm' ) {
      // Update session
      $s = array();
      $s['data']   = $session[\Cockatoo\Def::SESSION_KEY_POST];
      $s[\Cockatoo\Def::SESSION_KEY_POST]=null;
      $this->updateSession($s);
    }elseif ( $submit === 'signup' ) {
      $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STRAGE,'wiki','users','/'.$session['data']['name'],\Cockatoo\Beak::M_SET,array(),array(\Cockatoo\Beak::COMMENT_KIND_PARTIAL));
      $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$session['data'])));
      $s = null;
      $this->updateSession($s);
      $this->setRedirect('/view');
    }

    return array();
  }

  public function postProc(){
  }
}