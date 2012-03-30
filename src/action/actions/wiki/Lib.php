<?php
namespace wiki;
class Lib {
  # Page
  static function page(&$page,&$origin,&$contents,&$user){
    return array('title' => $page,'origin' => $origin , 'contents' => $contents , 'author' => $user);
  }
  static function get_page($page){
    $page = urlencode($page);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'wiki','page','/'.$page,\Cockatoo\Beak::M_GET,array(),array());
    $ret = \Cockatoo\BeakController::beakQuery(array($brl));
    return $ret[$brl];
  }
  static function save_page($page,&$pdata){
    $page = urlencode($page);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'wiki','page','/'.$page,\Cockatoo\Beak::M_SET,array(),array());
    $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$pdata)));
    if ( $ret[$brl] ) {
      return $ret[$brl];
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }
  static function remove_page($page){
    $page = urlencode($page);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'wiki','page','/'.$page,\Cockatoo\Beak::M_DEL,array(),array());
    $ret = \Cockatoo\BeakController::beakQuery(array($brl));
    if ( $ret[$brl] ) {
      return $ret[$brl];
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }
}