<?php
namespace mongo;
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/Action.php');
/**
 * UserPostAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/07/07
 * @version $Id$doc$
 * @copyright Copyright (C) 2011, rakuten 
 */
abstract class UserPostAction extends \Cockatoo\Action {
  protected $REDIRECT  = '/mongo/tips';
  protected $SERVICE    = 'mongo';
  protected $COLLECTION = 'docs';
  protected $NAMESPACE  = 'mongo';
  protected $DOCNAME    = 'doc';

  abstract function new_doc();
  abstract function post_to_doc (&$post); 
  abstract function update_docid(&$docid,&$doc);
  function get_hook(&$doc){
  }
  function set_hook(&$doc){
  }
  function begin_hook(&$op,&$docid,&$post){
    return null; // continue
  }

  protected $user = '';
  protected $isRoot = false;
  protected $isWritable = false;

  function get_doc($docid){
    $session = $this->getSession();
    $docid = \Cockatoo\UrlUtil::urlencode($docid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->SERVICE,$this->COLLECTION,'/'.$docid,\Cockatoo\Beak::M_GET,array(),array());
    $doc = \Cockatoo\BeakController::beakSimpleQuery($brl);
    if ( $doc &&
         ( $this->isRoot || (boolean)$doc['public'] || $doc['_owner'] === $this->user ) ) {
      return $doc;
    }
    return $doc;
  }
  function get_docs(){
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->SERVICE,$this->COLLECTION,'',\Cockatoo\Beak::M_GET_RANGE,array(),array());
    $docs = \Cockatoo\BeakController::beakSimpleQuery($brl);
    if ( ! $this->isRoot ) {
      $user = $this->user;
      $docs = array_filter($docs,function ($doc) use(&$user) {
          return (boolean)$doc['public'] || $doc['_owner'] === $user;
        });
    }
    return $docs;
  }
  function save_doc($docid,&$data){
    $docid = \Cockatoo\UrlUtil::urlencode($docid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->SERVICE,$this->COLLECTION,'/'.$docid,\Cockatoo\Beak::M_SET,array(),array());
    $ret = \Cockatoo\BeakController::beakSimpleQuery($brl,$data);
    if ( $ret ) {
      return $ret;
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }
  function remove_doc($docid){
    $docid = \Cockatoo\UrlUtil::urlencode($docid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->SERVICE,$this->COLLECTION,'/'.$docid,\Cockatoo\Beak::M_DEL,array(),array());
    $ret = \Cockatoo\BeakController::beakSimpleQuery($brl);
    if ( $ret ) {
      return $ret;
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }

  public function proc(){
    try{
      $this->setNamespace($this->NAMESPACE);
      $session     = $this->getSession();
      $this->user  = Lib::user($session);
      $this->isRoot = Lib::isRoot($session);
      $this->isWritable = Lib::isWritable($session);
      $docid          = $this->args['E'];
      $post = $session[\Cockatoo\Def::SESSION_KEY_POST];
      $op = $post['op'];

      $doc = $this->begin_hook($op,$docid,$post);
      if ( $doc ) {
        return array( $this->DOCNAME => $doc);
      }
      if ( $this->method === \Cockatoo\Beak::M_GET ) {
        $doc = $this->get_doc($docid);
        if ( $doc ) {
          $this->get_hook($doc);
          return array( $this->DOCNAME => $doc);
        }
        $this->setMovedTemporary($this->REDIRECT);
        return null;
      }elseif( $this->method === \Cockatoo\Beak::M_GET_ARRAY ) {
        $docs = $this->get_docs();
        return array($this->DOCNAME.'s' => $docs);
      }elseif( $this->method === \Cockatoo\Beak::M_SET ) {
        if ( ! $this->isWritable ) {
          throw new \Exception('You do not have write permission.');
        }
        if ( ! $op ) {
          $doc = $this->get_doc($docid);
          if ( $doc ) {
            $this->set_hook($doc);
            return array( $this->DOCNAME => $doc);
          }
          $doc = $this->new_doc();
          return array( $this->DOCNAME => $doc);
        }
        $doc = $this->post_to_doc($post);
        if( $op === 'preview' ) {
          $this->set_hook($doc);
          return array( $this->DOCNAME => $doc );
        }elseif( $op === 'save' ) {
          $old_docid = $docid;
          $new_docid = $this->update_docid($docid,$doc);
          $doc['docid'] = $new_docid;
          $doc['_owner'] = $this->user;
          $doc['_ownername'] = Lib::name($session);
          $doc['_time'] = time();
          $doc['_timestr'] = date('Y-m-d',$doc['_time']);
          $this->save_doc($new_docid,$doc);
          if ( $new_docid !== $old_docid ) {
            $this->remove_doc($old_docid);
          }
          $this->setMovedTemporary($this->REDIRECT.'/'.$new_docid);
          return array();
        }
      }
    }catch ( \Exception $e ) {
      $s[\Cockatoo\Def::SESSION_KEY_ERROR] = $e->getMessage();
      $this->updateSession($s);
      $this->setMovedTemporary($this->REDIRECT);
       \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
      return null;
    }
  }
  public function postProc(){
  }
}

