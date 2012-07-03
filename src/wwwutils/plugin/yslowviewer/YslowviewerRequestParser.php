<?php
/**
 * YslowviewerRequestParser.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/02/01
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
namespace yslowviewer;
class YslowviewerRequestParser extends \Cockatoo\DefaultRequestParser {
  public function parseImpl(){
    if ( preg_match('@^/yslowviewer/default/(.*)?$@', $this->reqpath , $matches ) !== 0 ) {
      $this->service = 'yslowviewer';
      $this->device  = 'default';
      $this->session_path = '/yslowviewer';
      $this->path    = $matches[1];
      return;
    }elseif ( preg_match('@^/yslowviewer/(.*)?$@', $this->reqpath , $matches ) !== 0 ) {
      // application = yslowviewer
      $this->service = 'yslowviewer';
      $this->device  = 'default';
      $this->session_path = '/yslowviewer';
      $this->path    = $matches[1];
      return;
    }elseif ( preg_match('@^/([^/]+)/([^/]+)/(.*)?$@', $this->reqpath , $matches ) !== 0 ) {
      $this->service = $matches[1];
      $this->device  = $matches[2];
      $this->path    = $matches[3];
      $this->session_path = '/'.$matches[1];
      return; // other application
    }
    throw new \Exception('Unexpect PATH:' . $this->reqpath);
  }
}
