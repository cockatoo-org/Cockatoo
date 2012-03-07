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
/**
 * ??????????
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class Wikirequestparser extends \Cockatoo\DefaultRequestParser {
  public function parseImpl(){
    if ( preg_match('@^/wiki/(.*)?$@', $this->reqpath , $matches ) !== 0 ) {
      // application = wiki
      $reqpath = $matches[1];
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
    throw new \Exception('Unexpect PATH:' . $this->reqpath);
  }
}
