<?php
namespace Cockatoo;

class UtilDselector {
  public static function select($session,$offset){
    $str_date = $session[\Cockatoo\Def::SESSION_KEY_GET]['dselector'];
    if ( $str_date ) {
      $tm = strptime($str_date,'%Y-%m-%d');
      $date = mktime(0,0,0,$tm['tm_mon']+1,$tm['tm_mday'],$tm['tm_year']+1900)+$offset;
    }else{
      $date = time();
      $str_date = strftime('%Y-%m-%d',$date);
      $date += $offset;
    }
    return array($date,$str_date);
  }
}