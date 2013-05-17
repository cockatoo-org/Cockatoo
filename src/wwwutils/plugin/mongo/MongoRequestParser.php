<?php
/**
 * MongoRequestParser.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/02/01
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
namespace mongo;
class MongoRequestParser extends \Cockatoo\DefaultRequestParser {
  public $service = 'mongo';
  public $session_path = '/mongo';
  public $template = 'default';
  public function parseImpl(){
    if ( $this->reqpath && 
         preg_match('@^/noryo2013(?:/(.*))?$@', $this->reqpath , $matches ) !== 0 ) {
      $this->template = 'noryo2013';
      $reqpath = $matches[1];
      if ( preg_match('@^timetable/edit?$@', $reqpath , $matches ) !== 0 ) {
        $this->path = 'timetable/edit';
        $this->args['E'] = $matches[1];
      }elseif ( preg_match('@^timetable/(.*)?$@', $reqpath , $matches ) !== 0 ) {
        $this->path = 'timetable/';
        $this->args['E'] = $matches[1];
      }else{
        $this->path = $reqpath;
        $this->args['P'] = $reqpath;
      }
      return;
    }

    if ( ! $this->reqpath ||
         preg_match('@^/(.*)?$@', $this->reqpath , $matches ) !== 0 ) {
      $reqpath = $matches[1];
      if ( preg_match('@^edit/(.*)?$@', $reqpath , $matches ) !== 0 ) {
        $this->path = 'edit';
        $this->args['P'] = $matches[1];
      }elseif ( preg_match('@^img/(.*)?$@', $reqpath , $matches ) !== 0 ) {
        $this->path = 'img';
        $this->args['P'] = $matches[1];
      }elseif ( preg_match('@^upload/(.*)?$@', $reqpath , $matches ) !== 0 ) {
        $this->path = 'upload';
        $this->args['P'] = $matches[1];
      }elseif ( preg_match('@^uploaded/(.*)?$@', $reqpath , $matches ) !== 0 ) {
        $this->path = 'uploaded';
        $this->args['P'] = $matches[1];
      }elseif ( preg_match('@^events/edit/(.*)?$@', $reqpath , $matches ) !== 0 ) {
        $this->path = 'events/edit';
        $this->args['E'] = $matches[1];
      }elseif ( preg_match('@^events/(.*)?$@', $reqpath , $matches ) !== 0 ) {
        $this->path = 'events/';
        $this->args['E'] = $matches[1];
      }elseif ( preg_match('@^exams/edit/(.*)?$@', $reqpath , $matches ) !== 0 ) {
        $this->path = 'exams/edit';
        $this->args['E'] = $matches[1];
      }elseif ( preg_match('@^exams/(.*)?$@', $reqpath , $matches ) !== 0 ) {
        $this->path = 'exams/';
        $this->args['E'] = $matches[1];
      }elseif ( preg_match('@^tips/edit/(.*)?$@', $reqpath , $matches ) !== 0 ) {
        $this->path = 'tips/edit';
        $this->args['E'] = $matches[1];
      }elseif ( preg_match('@^tips/(.*)?$@', $reqpath , $matches ) !== 0 ) {
        $this->path = 'tips/';
        $this->args['E'] = $matches[1];
      }elseif ( preg_match('@^news/edit/(.*)?$@', $reqpath , $matches ) !== 0 ) {
        $this->path = 'news/edit';
        $this->args['E'] = $matches[1];
      }elseif ( preg_match('@^news/(.*)?$@', $reqpath , $matches ) !== 0 ) {
        $this->path = 'news/';
        $this->args['E'] = $matches[1];
      }else{ 
        $this->path = $reqpath;
        $this->args['P'] = $reqpath;
      }
      return;
    }
    throw new \Exception('Unexpect PATH:' . $this->reqpath);
  }
}