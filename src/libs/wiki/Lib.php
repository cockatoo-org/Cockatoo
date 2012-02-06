<?php
namespace wiki;
class Lib {
  static function mkpasswd(){
    $ret = '';
    for ( $i=0;$i < rand(10,16);$i++){
      $ret .= chr(rand(0x20,0x7E));
    }
    return $ret;
  }
  static function get_account($user){
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STRAGE,'wiki','users','/'.$user,\Cockatoo\Beak::M_GET,array(),array());
    $ret = \Cockatoo\BeakController::beakQuery(array($brl));
    if ( $ret[$brl] ){
      return $ret[$brl];
    }
    throw new \Exception('Invalid account !');
  }
  static function save_account($data){
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STRAGE,'wiki','users','/'.$data['user'],\Cockatoo\Beak::M_SET,array(),array());
    $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$data)));
    if ( $ret[$brl] ) {
      return $ret[$brl];
    }
    throw new \Exception('Cannot save it ! Probably strage error...');
  }
  static function remove_account($user){
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STRAGE,'wiki','users','/'.$user,\Cockatoo\Beak::M_DEL,array(),array());
    $ret = \Cockatoo\BeakController::beakQuery(array($brl));
    if ( $ret[$brl] ) {
      return $ret[$brl];
    }
    throw new \Exception('Cannot save it ! Probably strage error...');
  }
}