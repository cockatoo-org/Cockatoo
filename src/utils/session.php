<?php
/**
 * session.php - Session controller
 *  
 * @access public
 * @package cockatoo-utils
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
require_once(Config::COCKATOO_ROOT.'utils/beak.php');

/**
 * Get session object
 *
 * @param String $sessionID session-id
 * @param String $prefix    beak domain prefix
 * @return Array Returns a session object
 */
function getSession($sessionID,$prefix){
  if ( ! $sessionID ) {
    return null;
  }
  $brl = brlgen(Def::BP_SESSION,$prefix,'default',$sessionID,Beak::M_GET,array(),array(Beak::COMMENT_KIND_FRESH));
  $ret = BeakController::beakQuery(array($brl));
  return $ret[$brl];
}
/**
 * Set session object
 *
 * @param String $sessionID session-id
 * @param String $prefix    beak domain prefix
 * @param Array  $session   session object
 */
function setSession($sessionID,$prefix,$session){
  if ( ! $sessionID ) {
    return null;
  }
  $brl = brlgen(Def::BP_SESSION,$prefix,'default',$sessionID,Beak::M_SET);
  $ret = BeakController::beakQuery(array(array($brl,$session)));
}
/**
 * Del session object
 *
 * @param String $sessionID session-id
 * @param String $prefix    beak domain prefix
 */
function delSession($sessionID,$prefix){
  if ( ! $sessionID ) {
    return null;
  }
  $brl = brlgen(Def::BP_SESSION,$prefix,'default',$sessionID,Beak::M_DEL);
  $ret = BeakController::beakQuery(array($brl));
}
