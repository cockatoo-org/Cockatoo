<?php
namespace Cockatoo;

class UrlUtil {
  public static function urlencode($url){
    $url = urlencode($url);
    $url = str_replace('-','%2D',$url);
    return str_replace('.','%2E',$url);
  }
  public static function urldecode($url){
    return urldecode($url);
  }
}