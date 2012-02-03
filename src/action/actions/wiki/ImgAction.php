<?php
namespace wiki;
require_once($COCKATOO_ROOT.'action/Action.php');

/**
 * ImgAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/07/07
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */

class ImgAction extends \Cockatoo\Action {
  public function proc(){
    $this->setNamespace('wiki');
// Query strings
    $session = $this->getSession();
    
    $author = $this->args['A'];
    $page   = $this->args['P'];
    $name   = $this->args['N'];

    $names = array();
    if ( $this->method === \Cockatoo\Beak::M_SET and isset($session[\Cockatoo\Def::SESSION_KEY_FILES])) {
      foreach($session[\Cockatoo\Def::SESSION_KEY_FILES] as $file){
        if ( ! $file[\Cockatoo\Def::F_ERROR] ) {
          $brl =  \Cockatoo\brlgen(\Cockatoo\Def::BP_STATIC, 'wiki', $page, $file[\Cockatoo\Def::F_NAME], null);
          $type = $file[\Cockatoo\Def::F_TYPE];
          $content = &$file[\Cockatoo\Def::F_CONTENT];
          \Cockatoo\StaticContent::save($brl,$type,$author,$content);
          $names []= $file[\Cockatoo\Def::F_NAME];
        }
      }
      $this->setRedirect('/i/uploaded?page='.$page);
    }else if ( $this->method === \Cockatoo\Beak::M_KEY_LIST ) {
      $brl =  \Cockatoo\brlgen(\Cockatoo\Def::BP_STATIC, 'wiki', $page, '', \Cockatoo\Beak::M_KEY_LIST);
      $bret = \Cockatoo\BeakController::beakQuery(array($brl));
      foreach ( $bret[$brl] as $name ) {
        $names []= $name;
      }
    }else if ( $this->method === \Cockatoo\Beak::M_GET ) {
      $brl =  \Cockatoo\brlgen(\Cockatoo\Def::BP_STATIC, 'wiki', $page, $name, \Cockatoo\Beak::M_GET);
      $bret = \Cockatoo\BeakController::beakQuery(array($brl));
      if ( $bret[$brl] ) {
        $this->setHeader('Content-Type',$bret[$brl][\Cockatoo\Def::K_STATIC_TYPE]);
        return array('img' => $bret[$brl]);
      }
    }
    return array('names'=>$names);
  }

  public function postProc(){
  }
}
