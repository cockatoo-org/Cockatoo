<?php
/**
 * ErrorAction.php - 
 *  
 * @access public
 * @package cockatoo-action
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
require_once(Config::COCKATOO_ROOT.'action/Action.php');

class ErrorAction extends Action {
  protected function proc(){
    $this->setNamespace('error');
    $session = $this->getSession();
    if ( isset($session[Def::SESSION_KEY_ERROR]) ) {
      // Reset error message
      $s[Def::SESSION_KEY_ERROR] = null;
      $this->updateSession($s);
      return array(Def::SESSION_KEY_ERROR => $session[Def::SESSION_KEY_ERROR]);
    }
    return array();
  }

  public function postRun(){
  }
}
