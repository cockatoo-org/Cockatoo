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
    if ( preg_match('@^'.\Cockatoo\Config::APP_OCCUPATION.'/view/(.*)?$@', $this->reqpath , $matches ) !== 0 ) {
      return array('wiki','default','/view',array('P'=>$matches[1]));
    }elseif ( preg_match('@^'.\Cockatoo\Config::APP_OCCUPATION.'/edit/(.*)?$@', $this->reqpath , $matches ) !== 0 ) {
      return array('wiki','default','/edit',array('P'=>$matches[1]));
    }elseif ( preg_match('@^'.\Cockatoo\Config::APP_OCCUPATION.'/img/(.*)?$@', $this->reqpath , $matches ) !== 0 ) {
      return array('wiki','default','/img',array('P'=>$matches[1]));
    }elseif ( preg_match('@^'.\Cockatoo\Config::APP_OCCUPATION.'/upload/(.*)?$@', $this->reqpath , $matches ) !== 0 ) {
      return array('wiki','default','/upload',array('P'=>$matches[1]));
    }elseif ( preg_match('@^'.\Cockatoo\Config::APP_OCCUPATION.'/uploaded/(.*)?$@', $this->reqpath , $matches ) !== 0 ) {
      return array('wiki','default','/uploaded',array('P'=>$matches[1]));
    }elseif ( preg_match('@^'.\Cockatoo\Config::APP_OCCUPATION.'(?:/(.*))?$@', $this->reqpath , $matches ) !== 0 ) {
      return array('wiki','default',$matches[1]);
    }
    throw new \Exception('Unexpect PATH:' . $this->reqpath);
  }
}
