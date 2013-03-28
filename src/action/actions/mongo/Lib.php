<?php
namespace mongo;
class Lib {
  #
  static function user(&$session) {
    return $session[\Cockatoo\AccountUtil::SESSION_LOGIN][\Cockatoo\AccountUtil::KEY_USER];
  }
  static function name(&$session) {
    return $session[\Cockatoo\AccountUtil::SESSION_LOGIN][\Cockatoo\AccountUtil::KEY_NAME];
  }
  static function isWritable(&$session) {
    if ( $session[\Cockatoo\AccountUtil::SESSION_LOGIN][\Cockatoo\AccountUtil::KEY_ROOT] ||
         $session[\Cockatoo\AccountUtil::SESSION_LOGIN][\Cockatoo\AccountUtil::KEY_WRITABLE] ) {
      return true;
    }
    return false;
  }
  static function isRoot(&$session) {
    if ( $session[\Cockatoo\AccountUtil::SESSION_LOGIN][\Cockatoo\AccountUtil::KEY_ROOT] ) {
      return true;
    }
    return false;
  }

  # Page
  static function page(&$page,&$origin,&$contents,&$user){
    return array('title' => $page,'origin' => $origin , 'contents' => $contents);
  }
  static function get_page($page){
    $page = \Cockatoo\UrlUtil::urlencode($page);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','page','/'.$page,\Cockatoo\Beak::M_GET,array(),array());
    $page_data = \Cockatoo\BeakController::beakSimpleQuery($brl);
    return $page_data;
  }
  static function save_page($page,&$pdata){
    $page = \Cockatoo\UrlUtil::urlencode($page);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','page','/'.$page,\Cockatoo\Beak::M_SET,array(),array());
    $ret = \Cockatoo\BeakController::beakSimpleQuery($brl,$pdata);
    if ( $ret ) {
      return $ret;
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }
  static function remove_page($page){
    $page = \Cockatoo\UrlUtil::urlencode($page);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','page','/'.$page,\Cockatoo\Beak::M_DEL,array(),array());
    $ret = \Cockatoo\BeakController::beakSimpleQuery($brl);
    if ( $ret ) {
      return $ret;
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }
}