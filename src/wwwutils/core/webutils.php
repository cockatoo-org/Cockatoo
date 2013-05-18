<?php
/**
 * webutils.php - Component implement
 *  
 * @access public
 * @package cockatoo-web
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;

/*
 *
 */
function getPost(&$method){
  if ( $method === 'POST' ) {
    $POST = $_POST;
    foreach($_FILES as $name => $file){
      if ( ! $file['error'] > 0.0 ) {
        $POST[$name] = array(Def::F_ERROR=>$file['error'],Def::F_NAME=>$file['name'],Def::F_TYPE=>FileContentType::get($file['name']),Def::F_SIZE=>$file['size'],Def::F_CONTENT=>file_get_contents($file['tmp_name']));
      }
    }
    return $POST;
    /*
    $STR = file_get_contents('php://input');
    if ( preg_match('@^(?:[^=&]*=[^=&]*(?:&[^=&]*=[^=&]*)*)?$@',$STR,$matches) !== 0 ) {
      Log::trace($NAME,'Query format');
      parse_str($STR,$POST);
      return $POST;
    }else{
      Log::trace($NAME,'Not Query format');
      return $STR;
    }
    */
  }
}
/*
 *
 */
function expires_header(&$expire){
  if ( $expire and $expire > 0 ){
    header("Cache-Control: public, maxage=".$expire);
    header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expire) . ' GMT');        
    //header('Vary: Accept-Encoding');        
  }
}
function etag_header(&$etag){
  if ( $etag ) {
    header('ETag: ' . $etag);
  }
}

/*
 * 
 */
function http_200(&$type=null,&$etag=null,&$expire=null){
  if ( $type ) {
    header('HTTP/1.1 200 OK');
    header('Content-type: ' . $type);
  }
  if ( $etag ) {
    etag_header($etag);
  }
  if ( $expire ) {
    expires_header($expire);
  }
}
function http_301(&$location){
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: ' . $location);
}
function http_302(&$location){
  header('HTTP/1.1 302 Moved Temporary');
  header('Location: ' . $location);
}
function http_304(&$etag,&$expire){
  header("HTTP/1.1 304 Not Modified");
  if ( $expire ) {
    expires_header($expire);
  }
  etag_header($etag);
}
function http_404(){
  header('HTTP/1.1 404 Not found');
}


/**
 * Redirect marker exception
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class RedirectException extends \Exception{
}

/**
 * Redirect marker exception
 *
 * @param String $location URL
 * @throw RedirectException
 */
function moved_permanently($location){
  http_301($location);
  throw new RedirectException('301 redirect : ' . $location);
}

/**
 * Redirect marker exception
 *
 * @param String $location URL
 * @throw RedirectException
 */
function moved_temporary($location){
  http_302($location);
  throw new RedirectException('302 redirect : ' . $location);
}


