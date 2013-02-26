<?php
namespace mongo;
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/Action.php');
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
    $page   = $qs['page']?$qs['page']:'';
    if ( isset($this->args['P'] ) ) {
      $page = $this->args['P'];
    }
    if ( ! $page ) {
      $page = MongoConfig::PAGE_NAME;
    }
    $name   = $qs['n']?$qs['n']:'null';
    if ( isset($this->args['N'] ) ) {
      $name = $this->args['N'];
    }
    $this->updateArgs(array(
                        'P' => $page,
                        'N' => $name));
    $this->setNamespace('mongo');
    return Array('page' => $page);
  }

  public function postProc(){
  }
}
