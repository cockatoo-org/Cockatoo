<?php
/**
 * CoreRequestParser.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/02/01
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
namespace Cockatoo;
class CoreRequestParser extends DefaultRequestParser {
  public $service = 'core';
  public $session_path = '/';
  public function parseImpl(){
    if ( preg_match('@^/admin$@', $this->reqpath , $matches ) !== 0 ) {
      $this->template= 'default';
      $this->path    = 'admin';
      return;
    }elseif ( preg_match('@^/login$@', $this->reqpath , $matches ) !== 0 ){
      $this->template= 'default';
      $this->path    = 'login';
      return;
    }elseif ( preg_match('@^/logintwitter$@', $this->reqpath , $matches ) !== 0 ){
      $this->template= 'default';
      $this->path    = 'logintwitter';
      return;
    }elseif ( preg_match('@^/logingoogle$@', $this->reqpath , $matches ) !== 0 ){
      $this->template= 'default';
      $this->path    = 'logingoogle';
      return;
    }elseif ( preg_match('@^/profile@', $this->reqpath , $matches ) !== 0 ){
      $this->template= 'default';
      $this->path    = 'profile';
      return;
    }elseif ( preg_match('@^/([^/]+)/(.*)?$@', $this->reqpath , $matches ) !== 0 ) {
      $this->template= $matches[1];
      $this->path    = $matches[2];
      return; // core application
    }
    throw new \Exception('Unexpect PATH:' . $this->reqpath);
  }
}