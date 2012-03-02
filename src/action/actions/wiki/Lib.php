<?php
namespace wiki;
class Lib {
  # Account
  static function mkpasswd(){
    $ret = '';
    for ( $i=0;$i < rand(10,16);$i++){
      $ret .= chr(rand(0x20,0x7E));
    }
    return $ret;
  }
  static function get_account($user){
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'wiki','users','/'.$user,\Cockatoo\Beak::M_GET,array(),array());
    $ret = \Cockatoo\BeakController::beakQuery(array($brl));
    if ( $ret[$brl] ){
      return $ret[$brl];
    }
    throw new \Exception('Invalid account !');
  }
  static function save_account(&$data){
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'wiki','users','/'.$data['user'],\Cockatoo\Beak::M_SET,array(),array());
    $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$data)));
    if ( $ret[$brl] ) {
      return $ret[$brl];
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }
  static function remove_account($user){
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'wiki','users','/'.$user,\Cockatoo\Beak::M_DEL,array(),array());
    $ret = \Cockatoo\BeakController::beakQuery(array($brl));
    if ( $ret[$brl] ) {
      return $ret[$brl];
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }
  # Page
  static function page(&$page,&$origin,&$contents,&$user){
    return array('title' => $page,'origin' => $origin , 'contents' => array($contents) , 'author' => $user);
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