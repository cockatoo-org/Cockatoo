<?php
/**
 * HealthAction.php - 
 *  
 * @access public
 * @package cockatoo-action
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
require_once(Config::$COCKATOO_ROOT.'action/Action.php');

class HealthAction extends Action {
  protected function proc(){
    $this->setNamespace('Cockatoo');
    return array('return' => 'OK' );
  }

  public function postRun(){
  }
}
