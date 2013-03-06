<?php
namespace mongo;
class Lib {
  #
  static function user(&$session) {
    return $session[\Cockatoo\AccountUtil::SESSION_LOGIN][\Cockatoo\AccountUtil::KEY_USER];
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
    return array('title' => $page,'origin' => $origin , 'contents' => $contents , 'author' => $user);
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

  static function get_event(&$session,$eventid){
    $eventid = \Cockatoo\UrlUtil::urlencode($eventid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','events','/'.$eventid,\Cockatoo\Beak::M_GET,array(),array());
    $data = \Cockatoo\BeakController::beakSimpleQuery($brl);
    if ( $data &&
         ( Lib::isRoot($session) || (boolean)$data['public'] || $data['owner'] === Lib::user($session) ) ) {
      return $data;
    }
    return $data;
  }
  static function get_events(&$session){
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','events','',\Cockatoo\Beak::M_GET_RANGE,array(),array());
    $datas = \Cockatoo\BeakController::beakSimpleQuery($brl);
    if ( ! Lib::isRoot($session) ) {
      $datas = array_filter($datas,function ($e) use (&$session) {
          return (boolean)$e['public'] || $e['owner'] === Lib::user($session);
        });
    }
    return $datas;
  }
  static function save_event($eventid,&$data){
    $eventid = \Cockatoo\UrlUtil::urlencode($eventid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','events','/'.$eventid,\Cockatoo\Beak::M_SET,array(),array());
    $ret = \Cockatoo\BeakController::beakSimpleQuery($brl,$data);
    if ( $ret ) {
      return $ret;
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }
  static function remove_event($eventid){
    $eventid = \Cockatoo\UrlUtil::urlencode($eventid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','events','/'.$eventid,\Cockatoo\Beak::M_DEL,array(),array());
    $ret = \Cockatoo\BeakController::beakSimpleQuery($brl);
    if ( $ret ) {
      return $ret;
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }


  static function get_exam(&$session,$examid){
    $examid = \Cockatoo\UrlUtil::urlencode($examid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','exams','/'.$examid,\Cockatoo\Beak::M_GET,array(),array());
    $data = \Cockatoo\BeakController::beakSimpleQuery($brl);
    if ( $data &&
         ( Lib::isRoot($session) || (boolean)$data['public'] || $data['owner'] === Lib::user($session) ) ) {
      return $data;
    }
    return null;
  }
  static function get_exams(&$session){
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','exams','',\Cockatoo\Beak::M_GET_RANGE,array(),array());
    $datas = \Cockatoo\BeakController::beakSimpleQuery($brl);
    if ( ! Lib::isRoot($session) ) {
      $datas = array_filter($datas,function ($e) use (&$session) {
          return (boolean)$e['public'] || $e['owner'] === Lib::user($session);
        });
    }
    return $datas;
  }
  static function save_exam($examid,&$data){
    $examid = \Cockatoo\UrlUtil::urlencode($examid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','exams','/'.$examid,\Cockatoo\Beak::M_SET,array(),array());
    $ret = \Cockatoo\BeakController::beakSimpleQuery($brl,$data);
    if ( $ret ) {
      return $ret;
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }
  static function remove_exam($examid){
    $examid = \Cockatoo\UrlUtil::urlencode($examid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','exams','/'.$examid,\Cockatoo\Beak::M_DEL,array(),array());
    $ret = \Cockatoo\BeakController::beakSimpleQuery($brl);
    if ( $ret ) {
      return $ret;
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }

  static function get_tip(&$session,$tipid){
    $tipid = \Cockatoo\UrlUtil::urlencode($tipid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','tips','/'.$tipid,\Cockatoo\Beak::M_GET,array(),array());
    $data = \Cockatoo\BeakController::beakSimpleQuery($brl);
    if ( $data &&
         ( Lib::isRoot($session) || (boolean)$data['public'] || $data['owner'] === Lib::user($session) ) ) {
      return $data;
    }
    return $data;
  }
  static function get_tips(&$session){
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','tips','',\Cockatoo\Beak::M_GET_RANGE,array(),array());
    $datas = \Cockatoo\BeakController::beakSimpleQuery($brl);
    if ( ! Lib::isRoot($session) ) {
      $datas = array_filter($datas,function ($e) use (&$session) {
          return (boolean)$e['public'] || $e['owner'] === Lib::user($session);
        });
    }
    return $datas;
  }
  static function save_tip($tipid,&$data){
    $tipid = \Cockatoo\UrlUtil::urlencode($tipid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','tips','/'.$tipid,\Cockatoo\Beak::M_SET,array(),array());
    $ret = \Cockatoo\BeakController::beakSimpleQuery($brl,$data);
    if ( $ret ) {
      return $ret;
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }
  static function remove_tip($tipid){
    $tipid = \Cockatoo\UrlUtil::urlencode($tipid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','tips','/'.$tipid,\Cockatoo\Beak::M_DEL,array(),array());
    $ret = \Cockatoo\BeakController::beakSimpleQuery($brl);
    if ( $ret ) {
      return $ret;
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }
}