<?php
namespace mongo;
/**
 * TimetableAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/07/07
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
class TimetableAction extends UserPostAction {
  protected $REDIRECT = '/mongo/noryo2013/timetable';
  protected $COLLECTION = 'timetable';
  protected $DOCNAME    = 'timebox';
  protected $ORDER      = '1';
  public function docid(){
    $session     = $this->getSession();
    return isset($session[\Cockatoo\Def::SESSION_KEY_POST])?\Cockatoo\UrlUtil::urldecode($session[\Cockatoo\Def::SESSION_KEY_POST]['_u']):null;
  }
  function new_doc(){
    $origin = '*New';
    $contents = array(array('tag' => 'h2','attr'=>array(),'children' => array(array('tag'=>'text' , 'text' => 'New'))));
    return array(
      '_u' => 'new',
      'origin' => $origin,
      'contents' => $contents
      );
  }
  function get_docs(){
    $ret = parent::get_docs();
    return array('raw' => $ret , '@json' => json_encode($ret));
  }
  function post_save_hook(&$doc){
    return $this->REDIRECT . '/edit';
  }
  function post_remove_hook(){
    return $this->REDIRECT . '/edit';
  }
  function post_to_doc (&$post,&$doc) {
    if ( ! $doc ) {
      $doc = $post;
    }else{
      $doc = array_merge($doc,$post);
    }
    unset($doc['submit']);
    $doc['types']  = explode(',',$doc['types']  );
    $doc['targets']= explode(',',$doc['targets']);
    $origin   = $doc['origin'];
    $lines = preg_split("@\r?\n@",$origin);
    $parser = new PageParser($doc['title'],$lines);
    $doc['contents'] =  $parser->parse();
  }
  const SEPARATOR = '.';
  function update_docid(&$docid,&$doc) {
    if ( ! $docid || strcmp($docid,'new')===0 ) {
      return $doc['place'] . self::SEPARATOR .$doc['start'] . self::SEPARATOR . uniqid();
    }
    $parsed_docid = explode(self::SEPARATOR,$docid);
    if ( ! ($doc['place'] === $parsed_docid[0]) || ! ($doc['start'] === $parsed_docid[1]) ) {
      return $doc['place'] . self::SEPARATOR . $doc['start'] . self::SEPARATOR . uniqid();
    }
    return $docid;
  }
}
