<?php
namespace wiki;
require_once($COCKATOO_ROOT.'action/Action.php');
/**
 * PreAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/07/07
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */

class PreAction extends \Cockatoo\Action {
  public function proc(){
    $session = $this->getSession();
    $url=\parse_url($session[\Cockatoo\Def::SESSION_KEY_SERVER]['REQUEST_URI']);
    \parse_str($url['query'],$qs);
    $author = $session['login']['name']?$session['login']['name']:'guest';
    $page   = $qs['page']?$qs['page']:'top';
    if ( isset($this->args['P'] ) ) {
      $page = $this->args['P'];
    }
    $name   = $qs['n']?$qs['n']:'null';
    $this->updateSession(array('wiki' => array('current' => $page ) ) );
    $this->updateArgs(array(
                        'A' => $author,
                        'P' => $page,
                        'N' => $name));
    return Array();
  }

  public function postProc(){
  }
}
