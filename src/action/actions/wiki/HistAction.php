<?php
namespace wiki;
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/Action.php');
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
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'wiki','hist','',     \Cockatoo\Beak::M_GET_RANGE,array(\Cockatoo\Beak::Q_SORT => '_u:-1',\Cockatoo\Beak::Q_LIMIT =>10),array());
    $histories = \Cockatoo\BeakController::beakSimpleQuery($brl);
    return array('hist' => $histories);
  }
  public function postProc(){
  }
}
