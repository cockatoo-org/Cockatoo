<?php
namespace mongo;
/**
 * TipAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/07/07
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
class TipAction extends UserPostAction {
  protected $REDIRECT = '/mongo/tips';
  protected $COLLECTION = 'tips';
  protected $DOCNAME    = 'tip';
  function new_doc(){
    $origin = '*New';
    $contents = array(array('tag' => 'h2','attr'=>array(),'children' => array(array('tag'=>'text' , 'text' => 'New'))));
    return array(
      '_u' => 'new',
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
}
