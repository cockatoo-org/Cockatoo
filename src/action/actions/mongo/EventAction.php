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
      '_u' => 'new',
      'origin' => $origin,
      'contents' => $contents
      );
  }
  function attend(&$docid,&$doc,&$data){
    // 
    if ( $doc['capacity'] < count($doc['attenders']) ) {
      // Add to attenders
      if ( $this->searchMy($doc['attenders']) ) {
        return;
      }
      $doc['attenders'] []= $data;
    }else{
      // Add to waiters
      if ( $this->searchMy($doc['waiters']) ) {
        return;
      }
      $doc['waiters'] []= $data;
    }
    // Remove from cancelers
    $this->removeMy($doc['cancelers']);
    $this->save_doc($docid,$doc,true);
  }
  function pullback_waiter(&$doc){
    $c = array_pop($doc['waiters']);
    if ( $c ) {
      $doc['attenders'] []= $c;
    }
  }
  function cancel(&$docid,&$doc,&$data){
    // Remove from attenders
    if ( $my = $this->removeMy($doc['attenders']) ) {
      // Cancel to Attenders
      $this->pullback_waiter($doc);
      // Add to cancelers
      $doc['cancelers'] []= $data;
      $this->save_doc($docid,$doc,true);
      return;
    }
    if ( $my = $this->removeMy($doc['waiters']) ) {
      // Add to cancelers
      $doc['cancelers'] []= $data;
      $this->save_doc($docid,$doc,true);
      return;
    }
  }

  function save_hook(&$doc){
    if ( ! isset($doc['attenders']) ) {
      $doc['attenders'] = array();
    }
    if ( ! isset($doc['cancelers']) ) {
      $doc['cancelers'] = array();
    }
    if ( ! isset($doc['waiters']) ) {
      $doc['waiters'] = array();
    }
  }
  function post_save_hook(&$doc){
    return $this->REDIRECT;
  }
  function begin_hook(&$op,&$docid,&$doc,&$post){
    $method  = $this->get_method();
    if ( $method === \Cockatoo\Beak::M_GET ) {
      if ( ! $this->user ) { // Is login
        return null;
      }
      if ( ! $doc ) {
        return null;
      }
      if ( $op === 'attend' ) {
        $data = array('user' => $this->user, 'name' => $this->username ,'msg' => $post['msg']);
        $this->attend($docid,$doc,$data);
      } else if ( $op === 'cancel' ) {
        $data = array('user' => $this->user, 'name' => $this->username ,'msg' => $post['msg']);
        $this->cancel($docid,$doc,$data);
      }
    }
    return null;
  }

  function removeMy (&$list ) {
    foreach ($list as $i => &$e ) {
      if ( strcmp($e['user'],$this->user)===0 ) {
        unset($list[$i]);
        return true;
      }
    }
    return false;
  }

  function & searchMy (&$list ) {
    foreach ($list as $i => &$e ) {
      if ( strcmp($e['user'],$this->user)===0 ) {
        return $e;
      }
    }
    return null;
  }

  function get_hook(&$doc){
    if ( ! $this->user ) { // Is login
      return null;
    }
    $doc['attendstatus'] = 1;
    $my = &$this->searchMy($doc['attenders'] );
    if ( $my ) {
      $my['my'] = 'my';
      $doc['attendstatus'] = 2;
    }
    if ( ! $my && 
         $my = &$this->searchMy($doc['waiters'] ) ) {
      $my['my'] = 'my';
      $doc['attendstatus'] = 2;
    }
    if ( ! $my &&
         $my = &$this->searchMy($doc['cancelers'] ) ) {
      $my['my'] = 'my';
    }
  }

  function post_to_doc (&$post,&$doc) {
    if ( ! $doc ) {
      $doc = $post;
    }else{
      $doc = array_merge($doc,$post);
    }
    $npullback = $doc['capacity'] - count($doc['attenders']);
    if ( $npullback < 0 ) {
      throw new \Exception('Specified capacity smaller than attenders !');
    }
    while ( $npullback-- > 0  ) {
      $this->pullback_waiter($doc);
    }
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
      return $doc['date'] . self::SEPARATOR . $doc['event_id'];
    }
    $parsed_docid = explode(self::SEPARATOR,$docid);
    if ( ! ($doc['date'] === $parsed_docid[0]) ) {
      return $doc['date'] . self::SEPARATOR . $doc['event_id'];
    }
    return $docid;
  }
}
