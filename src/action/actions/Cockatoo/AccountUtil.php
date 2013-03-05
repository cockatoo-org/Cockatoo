<?php
namespace Cockatoo;
class AccountUtil {
  const KEY_USER    ='user';
  const KEY_PASSWD  ='passwd';
  const KEY_CONFIRM ='confirm';
  const KEY_HASH    ='hash';
  const KEY_EMAIL   ='email';
  const KEY_ROOT    ='root';
  const KEY_WRITABLE='writable';
  const KEY_EXPIRES ='expires';

  const SESSION_LOGIN='login';

  public static function get_account($base_brl,$user){
    $brl = $base_brl.$user.'?'.Beak::M_GET;
    $accounts = BeakController::beakSimpleQuery($brl);
    if ( $accounts ) {
      return $accounts;
    }
    throw new \Exception('Invalid account !');
  }
  public static function save_account($base_brl,&$user_data){
    $brl = $base_brl.$user_data[self::KEY_USER].'?'.Beak::M_SET;
    $ret = BeakController::beakSimpleQuery($brl,$user_data);
    if ( $ret ) {
      return $ret;
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }
  public static function remove_account($base_brl,$user){
    $brl = $base_brl.$user.'?'.Beak::M_DEL;
    $ret = BeakController::beakSimpleQuery($brl);
    if ( $ret ) {
      return $ret;
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
  public static function mail($user_data,$mail_from,$reply_to){
    $subject = 'Your profile changed';
    $msg = ''.
      'Your new profile'."\n".
      '  User     : ' . $user_data[AccountUtil::KEY_USER] ."\n".
      '  Password : ' . (isset($user_data[AccountUtil::KEY_PASSWD])?$user_data[AccountUtil::KEY_PASSWD]:'(no change)')."\n".
      '  Email    : ' . $user_data[AccountUtil::KEY_EMAIL]."\n".
      '  Root     : ' . ($user_data[AccountUtil::KEY_ROOT]?'YES':'NO');
    $headers = ''.
      'From: '    .$mail_from ."\r\n" .
      'Reply-To: '.$reply_to ."\r\n";
      
    mail($user_data[AccountUtil::KEY_EMAIL],$subject,$msg);
    Log::info('Send mail to ' . $user_data[AccountUtil::KEY_EMAIL] , "\n".'Subject:'.$subject."\n".$headers . "\n" . $msg);
  }
}

