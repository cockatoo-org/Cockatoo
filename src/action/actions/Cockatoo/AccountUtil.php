<?php
namespace Cockatoo;
class AccountUtil {
  const KEY_USER    ='user';
  const KEY_PASSWD  ='passwd';
  const KEY_CONFIRM ='confirm';
  const KEY_HASH    ='hash';
  const KEY_EMAIL   ='email';
  const KEY_ROOT    ='root';

  const SESSION_LOGIN='login';

  public static function get_account($base_brl,$user){
    $brl = $base_brl.$user.'?'.\Cockatoo\Beak::M_GET;
    $ret = \Cockatoo\BeakController::beakQuery(array($brl));
    if ( $ret[$brl] ){
      return $ret[$brl];
    }
    throw new \Exception('Invalid account !');
  }
  public static function save_account($base_brl,&$user_data){
    $brl = $base_brl.$user_data[self::KEY_USER].'?'.\Cockatoo\Beak::M_SET;
    $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$user_data)));
    if ( $ret[$brl] ) {
      return $ret[$brl];
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }
  public static function remove_account($base_brl,$user){
    $brl = $base_brl.$user.'?'.\Cockatoo\Beak::M_DEL;
    $ret = \Cockatoo\BeakController::beakQuery(array($brl));
    if ( $ret[$brl] ) {
      return $ret[$brl];
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }
  public static function mkpasswd(){
    $ret = '';
    for ( $i=0;$i < rand(10,16);$i++){
      $ret .= chr(rand(0x20,0x7E));
    }
    return $ret;
  }

}

