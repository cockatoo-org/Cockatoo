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
    throw new \Exception('You do not have write permission.');
  }
  static function isRoot(&$session) {
    if ( $session[\Cockatoo\AccountUtil::SESSION_LOGIN][\Cockatoo\AccountUtil::KEY_ROOT] ) {
      return true;
    }
    throw new \Exception('You are not admin.');
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

  static function get_event($eventid){
    $eventid = \Cockatoo\UrlUtil::urlencode($eventid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','events','/'.$eventid,\Cockatoo\Beak::M_GET,array(),array());
    $edata = \Cockatoo\BeakController::beakSimpleQuery($brl);
    return $edata;
  }
  static function get_events(){
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','events','',\Cockatoo\Beak::M_GET_RANGE,array(),array());
    $events = \Cockatoo\BeakController::beakSimpleQuery($brl);
    return $events;
  }
  static function save_event($eventid,&$edata){
    $eventid = \Cockatoo\UrlUtil::urlencode($eventid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','events','/'.$eventid,\Cockatoo\Beak::M_SET,array(),array());
    $ret = \Cockatoo\BeakController::beakSimpleQuery($brl,$edata);
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


  static function get_exam($examid){
    $examid = \Cockatoo\UrlUtil::urlencode($examid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','exams','/'.$examid,\Cockatoo\Beak::M_GET,array(),array());
    $edata = \Cockatoo\BeakController::beakSimpleQuery($brl);
    return $edata;
  }
  static function get_exams(){
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','exams','',\Cockatoo\Beak::M_GET_RANGE,array(),array());
    $exams = \Cockatoo\BeakController::beakSimpleQuery($brl);
    return $exams;
  }
  static function save_exam($examid,&$edata){
    $examid = \Cockatoo\UrlUtil::urlencode($examid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'mongo','exams','/'.$examid,\Cockatoo\Beak::M_SET,array(),array());
    $ret = \Cockatoo\BeakController::beakSimpleQuery($brl,$edata);
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

}