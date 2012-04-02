<?php
/**
 * WikiRequestParser.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/02/01
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
namespace wiki;
class WikiRequestParser extends \Cockatoo\DefaultRequestParser {
  public function parseImpl(){
    if ( preg_match('@^/wiki/(.*)?$@', $this->reqpath , $matches ) !== 0 ) {
      // application = wiki
      $this->service = 'wiki';
      $this->session_path = '/wiki';
      
      $reqpath = $matches[1];
      if ( preg_match('@Android@', $this->header['User-Agent'] , $matches ) !== 0 ) {
        $reqpath = 'android/'.$reqpath;
      }
      if ( preg_match('@^android/(.*)?$@', $reqpath , $matches ) !== 0 ) {
        $this->device = 'android';
        $reqpath = $matches[1];
        if ( preg_match('@^view/(.*)?$@', $reqpath , $matches ) !== 0 ) {
          $this->path = '/view';
          $this->args['P'] = $matches[1];
        }elseif ( preg_match('@^img/(.*)?$@', $reqpath , $matches ) !== 0 ) {
          $this->path = '/img';
          $this->args['P'] = $matches[1];
        }else{ 
          $this->path = $reqpath;
        }
        return; // wiki android
      }else{
        $this->device = 'default';
        if ( preg_match('@^view/(.*)?$@', $reqpath , $matches ) !== 0 ) {
          $this->path = '/view';
          $this->args['P'] = $matches[1];
        }elseif ( preg_match('@^edit/(.*)?$@', $reqpath , $matches ) !== 0 ) {
          $this->path = '/edit';
          $this->args['P'] = $matches[1];
        }elseif ( preg_match('@^img/(.*)?$@', $reqpath , $matches ) !== 0 ) {
          $this->path = '/img';
          $this->args['P'] = $matches[1];
        }elseif ( preg_match('@^upload/(.*)?$@', $reqpath , $matches ) !== 0 ) {
          $this->path = '/upload';
          $this->args['P'] = $matches[1];
        }elseif ( preg_match('@^uploaded/(.*)?$@', $reqpath , $matches ) !== 0 ) {
          $this->path = '/uploaded';
          $this->args['P'] = $matches[1];
        }else{ 
          $this->path = $reqpath;
        }
        return; // wiki default
      }
    }elseif ( preg_match('@^/([^/]+)/([^/]+)/(.*)?$@', $this->reqpath , $matches ) !== 0 ) {
      $this->service = $matches[1];
      $this->device  = $matches[2];
      $this->path    = $matches[3];
      $this->session_path = '/'.$matches[1];
      return; // other application
    }elseif ( $this->reqpath === '/favicon.ico' ) {
      $this->service = 'wiki';
      $this->device = 'default';
      $this->path = '/img';
      $this->args['P'] = 'Cockatoo PHP framework';
      $this->args['N'] = 'logo.png';
      return; // favicon
    }
    throw new \Exception('Unexpect PATH:' . $this->reqpath);
  }
}
