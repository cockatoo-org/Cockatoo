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
  protected $REDIRECT = '/mongo/noryo1/timetable';
  protected $COLLECTION = 'timetable';
  protected $DOCNAME    = 'timebox';
  protected $ORDER      = '1';
  function new_doc(){
    $origin = '*New';
    $contents = array(array('tag' => 'h2','attr'=>array(),'children' => array(array('tag'=>'text' , 'text' => 'New'))));
    return array(
      'docid' => 'new',
      'origin' => $origin,
      'contents' => $contents
      );
  }
  function post_to_doc (&$post,&$doc) {
    if ( ! $doc ) {
      $doc = $post;
    }else{
      $doc = array_merge($doc,$post);
    }
    unset($doc['submit']);
    $origin   = $doc['origin'];
    $lines = preg_split("@\r?\n@",$origin);
    $parser = new PageParser($doc['title'],$lines);
    $doc['contents'] =  $parser->parse();
  }
  const SEPARATOR = '.';
  function update_docid(&$docid,&$doc) {
    if ( ! $docid || strcmp($docid,'new')===0 ) {
      $docid = $doc['start'] . self::SEPARATOR . uniqid();
    }
    $parsed_docid = explode(self::SEPARATOR,$docid);
    if ( ! ($doc['start'] === $parsed_docid[0]) ) {
      $docid = $doc['start'] . self::SEPARATOR . uniqid();
    }
    return $docid;
  }
}
