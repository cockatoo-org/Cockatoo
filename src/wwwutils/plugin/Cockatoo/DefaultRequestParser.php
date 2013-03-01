<?php
/**
 * DefaultRequestParser.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/02/01
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
namespace Cockatoo;
class DefaultRequestParser extends RequestParser {
  public function parseImpl(){
    if ( preg_match('@^/([^/]+)/([^/]+)/(.*)?$@', $this->reqpath , $matches ) !== 0 ) {
      $this->service = $matches[1];
      $this->template  = $matches[2];
      $this->path    = $matches[3];
      $this->session_path = '/'.$matches[1];
      return; // other application
    }elseif ( $this->reqpath === '/favicon.ico' ) {
      moved_permanentry('/_s_/core/default/logo.png');
      return;
    }
    throw new \Exception('Unexpect PATH:' . $this->reqpath);
  }
}