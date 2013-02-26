<?php
namespace mongo;
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/Action.php');

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
    $this->setNamespace('mongo');
// Query strings
    $session = $this->getSession();
    
    $user  = $session[\Cockatoo\AccountUtil::SESSION_LOGIN][\Cockatoo\AccountUtil::KEY_USER];
    $page   = $this->args['P'];
    $name   = $this->args['N'];
    if ( $this->method === \Cockatoo\Beak::M_SET and isset($session[\Cockatoo\Def::SESSION_KEY_FILES])) {
      if ( ! $user ) {
        $s[\Cockatoo\Def::SESSION_KEY_ERROR] = 'You have to login before update mongo !!';
        $this->updateSession($s);
        return;
      }
      foreach($session[\Cockatoo\Def::SESSION_KEY_FILES] as $file){
        if ( ! $file[\Cockatoo\Def::F_ERROR] ) {
          $brl =  \Cockatoo\brlgen(\Cockatoo\Def::BP_STATIC, 'mongo', $page, $file[\Cockatoo\Def::F_NAME], null);
          $type = $file[\Cockatoo\Def::F_TYPE];
          $content = &$file[\Cockatoo\Def::F_CONTENT];
          \Cockatoo\StaticContent::save($brl,$type,$user,$content);
          $names []= $file[\Cockatoo\Def::F_NAME];
        }
      }
      $this->setMovedTemporary('/mongo/uploaded/'.$page);
      return;
    }else if ( $this->method === \Cockatoo\Beak::M_KEY_LIST ) {
      $brl =  \Cockatoo\brlgen(\Cockatoo\Def::BP_STATIC, 'mongo', $page, '', \Cockatoo\Beak::M_KEY_LIST);
      $images = \Cockatoo\BeakController::beakSimpleQuery($brl);
      foreach ( $images as $name ) {
        $names []= $name;
      }
      return array('names'=>$names);
    }else if ( $this->method === \Cockatoo\Beak::M_GET ) {
      $brl =  \Cockatoo\brlgen(\Cockatoo\Def::BP_STATIC, 'mongo', $page, $name, \Cockatoo\Beak::M_GET);
      $image = \Cockatoo\BeakController::beakSimpleQuery($brl);
      if ( $image ) {
        return array('img' => $image);
      }
      return;
    }
  }

  public function postProc(){
  }
}
