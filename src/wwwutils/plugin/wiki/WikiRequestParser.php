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
      $reqpath = $matches[1];
      if ( preg_match('@Android@', $this->header['User-Agent'] , $matches ) !== 0 ) {
        $reqpath = 'android/'.$reqpath;
      }
      if ( preg_match('@^android/(.*)?$@', $reqpath , $matches ) !== 0 ) {
        $reqpath = $matches[1];
        if ( preg_match('@^view/(.*)?$@', $reqpath , $matches ) !== 0 ) {
          return array('wiki','android','/view',array('P'=>$matches[1]));
        }elseif ( preg_match('@^img/(.*)?$@', $reqpath , $matches ) !== 0 ) {
          return array('wiki','android','/img',array('P'=>$matches[1]));
        }else{ 
          return array('wiki','android',$reqpath);
        }
      }else{
        if ( preg_match('@^view/(.*)?$@', $reqpath , $matches ) !== 0 ) {
          return array('wiki','default','/view',array('P'=>$matches[1]));
        }elseif ( preg_match('@^edit/(.*)?$@', $reqpath , $matches ) !== 0 ) {
          return array('wiki','default','/edit',array('P'=>$matches[1]));
        }elseif ( preg_match('@^img/(.*)?$@', $reqpath , $matches ) !== 0 ) {
          return array('wiki','default','/img',array('P'=>$matches[1]));
        }elseif ( preg_match('@^upload/(.*)?$@', $reqpath , $matches ) !== 0 ) {
          return array('wiki','default','/upload',array('P'=>$matches[1]));
        }elseif ( preg_match('@^uploaded/(.*)?$@', $reqpath , $matches ) !== 0 ) {
          return array('wiki','default','/uploaded',array('P'=>$matches[1]));
        }else{ 
          return array('wiki','default',$reqpath);
        }
      }
    }elseif ( preg_match('@^/([^/]+)/([^/]+)/(.*)?$@', $this->reqpath , $matches ) !== 0 ) {
      return array($matches[1],$matches[2],$matches[3]);
    }elseif ( $this->reqpath === '/favicon.ico' ) {
        return array('wiki','default','/img',array('P'=>'/Cockatoo PHP framework','N'=>'logo.png'));
    }
    throw new \Exception('Unexpect PATH:' . $this->reqpath);
  }
}
