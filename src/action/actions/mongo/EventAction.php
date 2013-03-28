<?php
namespace mongo;
/**
 * EventAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/07/07
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
class EventAction extends UserPostAction {
  protected $REDIRECT = '/mongo/events';
  protected $COLLECTION = 'events';
  protected $DOCNAME    = 'event';

  function new_doc(){
    $origin = '*New';
    $contents = array(array('tag' => 'h2','attr'=>array(),'children' => array(array('tag'=>'text' , 'text' => 'New'))));
    return array(
      'docid' => 'new',
      'origin' => $origin,
      'contents' => $contents
      );
  }
  function post_to_doc (&$post) {
    $doc = $post;
    unset($doc['submit']);
    $origin   = $doc['origin'];
    $lines = preg_split("@\r?\n@",$origin);
    $parser = new PageParser($doc['title'],$lines);
    $doc['contents'] =  $parser->parse();
    return $doc;
  }
  const SEPARATOR = '.';
  function update_docid(&$docid,&$doc) {
    if ( ! $docid || strcmp($docid,'new')===0 ) {
        $docid = $doc['date'] . self::SEPARATOR . $doc['time'] . self::SEPARATOR . uniqid();
    }
    $parsed_docid = explode(self::SEPARATOR,$docid);
    if ( ! ($doc['date'] === $parsed_docid[0] && $doc['time'] === $parsed_docid[1]) ) {
      $docid = $doc['date'] . self::SEPARATOR . $doc['time'] . self::SEPARATOR . uniqid();
    }
    return $docid;
  }
}
