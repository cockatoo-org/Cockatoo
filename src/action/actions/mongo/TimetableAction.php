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
  protected $REDIRECT = '/mongo/noryo2013';
  protected $COLLECTION = 'timetable';
  protected $DOCNAME    = 'timebox';
  protected $ORDER      = '1';
  protected $DOCS_EXCEPTS = '';
  protected $IMAGE_PATH = 'timetable';
  public function docid(){
    $session = $this->getSession();
    return $session[\Cockatoo\Def::SESSION_KEY_POST]['_u'];
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
    $qs = $this->get_queries();
    $ret = parent::get_docs();
    $view = array_filter($ret,function ($doc) use($qs) {
        if ( isset($qs['exhibition']) ){
          return (boolean)$doc['public'] && $doc['place'] === 'exhibition';
        }
        return (boolean)$doc['public'];
      });
    array_unshift($ret,array('title' => '*new*'));
    return array('raw' => $ret , '@json' => json_encode($view));
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
    $doc['_share'] = true;
    if ( is_array($post['logo']) ) {
      $doc['images']['logo'] = $post['logo'];
      $doc['logo'] = $image[\Cockatoo\Def::F_NAME];
    }
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
