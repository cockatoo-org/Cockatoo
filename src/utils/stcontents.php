<?php
// svn propset svn:keywords "Date Rev Id" stcontents.php
// TZ=Asia/Tokyo phpdoc -t html -d source
/**
 * stcontents.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/01/23
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
namespace Cockatoo;
require_once(Config::$COCKATOO_ROOT.'wwwutils/core/webutils.php');

/**
 * FileContentType
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class FileContentType {
  static public $FILE_CONTENT_TYPE = array(
    'xml'  => 'text/xml',
    'js'   => 'text/javascript',
    'css'  => 'text/css',
    'html' => 'text/html',
    'txt'  => 'text/plain',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'tiff' => 'image/tiff',
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    null
    );

  static public function get($path){
    $ext  = pathinfo($path,\PATHINFO_EXTENSION);
    $type = self::$FILE_CONTENT_TYPE[strtolower($ext)];
    return ($type===null)?'text/plain':$type;
  }
  static public function is_bin($type){
    if ( strncmp($type,'text',4)===0 ){
      return false;
    }
    return true;
  }
}


/**
 * StaticContent
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class StaticContent {
  static public function save($brl,$type,$description,&$content,$TO_BEAKS=null,$uindex=Beak::Q_UNIQUE_INDEX,$exp=null){
    $token = preg_replace('/^(.{7})(.{3})(.{8}).*/','${1}-${2}-${3}', md5($content));
    if ( FileContentType::is_bin($type) ){
      $data = array(
        Def::K_STATIC_ETAG => $token,
        Def::K_STATIC_TYPE => $type,
        Def::K_STATIC_EXPIRE => $exp,
        Def::K_STATIC_DESCRIPTION => &$description,
        Def::K_STATIC_BIN  => &$content,
        Beak::ATTR_BIN => true
        );
    }else{
      $data = array(
        Def::K_STATIC_ETAG => $token,
        Def::K_STATIC_TYPE => $type,
        Def::K_STATIC_EXPIRE => $exp,
        Def::K_STATIC_DESCRIPTION => &$description,
        Def::K_STATIC_DATA  => &$content
        );
    }

    Log::info($brl . '   (' . $type . ')');
    $brl .= '?'.Beak::M_SET.'&' . Beak::Q_UNIQUE_INDEX . '=' . $uindex;
    $ret = BeakController::beakQuery(array(array($brl,$data)),$TO_BEAKS);
    $r = $ret[$brl];
    if ( $r ) {
      Log::info($r.' => '.$brl.' ('.$type.')');
      return True;
    }else {
      Log::error('failure'.' => '.$brl.' ('.$type.')');
      return False;
    }
  }

  static public function get($brl){
    $ret = BeakController::beakQuery(array($brl));
    $content = $ret[$brl];
    if ( ! $content ) {
      throw new \Exception('Not found ' . $brl);
    }
    return $content;
  }

  static public function http($content,$HEADER){
    if ( ! $content ) {
      http_404();
      return;
    }
    if ( $HEADER['If-None-Match'] and $content[Def::K_STATIC_ETAG] and $content[Def::K_STATIC_ETAG] === $HEADER['If-None-Match'] ) {
      // 304
      http_304($content[Def::K_STATIC_ETAG],$content[Def::K_STATIC_EXPIRE]);
      return;
    }
    // 200
    http_200($content[Def::K_STATIC_TYPE],$content[Def::K_STATIC_ETAG],$content[Def::K_STATIC_EXPIRE]);
    if ( isset($content[Beak::ATTR_BIN]) ) {
      print $content[Def::K_STATIC_BIN];
    }else{
      print $content[Def::K_STATIC_DATA];
    }
  }
}
