<?php
namespace wiki;
require_once($COCKATOO_ROOT.'action/Action.php');
/**
 * HistAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/07/07
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */

class HistAction extends \Cockatoo\Action {
  public function proc(){
    $this->setNamespace('wiki');
    // Query strings
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'wiki','hist','/CUR',\Cockatoo\Beak::M_GET,array(),array());
    $bret = \Cockatoo\BeakController::beakQuery(array($brl));
    if ( $bret[$brl] and $bret[$brl]['hist']) {
      krsort($bret[$brl]['hist']);
      return $bret[$brl];
    }
    return null;
  }
  public function postProc(){
  }
}
