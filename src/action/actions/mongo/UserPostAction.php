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
  abstract function post_to_doc (&$post,&$doc); 
  abstract function update_docid(&$docid,&$doc);
  function get_hook(&$doc){
  }
  function set_hook(&$doc){
  }
  function preview_hook(&$doc){
  }
  function save_hook(&$doc){
  }
  function post_save_hook(&$doc){
    return false;
  }
  function begin_hook(&$op,&$docid,&$doc,&$post){
    return null; // continue
  }

  protected $user = '';
  protected $isRoot = false;
  protected $isWritable = false;

  function get_doc($docid,$force = false){
    $session = $this->getSession();
    $docid = \Cockatoo\UrlUtil::urlencode($docid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->SERVICE,$this->COLLECTION,'/'.$docid,\Cockatoo\Beak::M_GET,array(),array());
    $doc = \Cockatoo\BeakController::beakSimpleQuery($brl);
    if ( $doc ) {
      if ( $force ) {
        return $doc;
      }
      if ( $this->isRoot || $doc['_owner'] === $this->user ) {
        $doc['writable'] = true;
        return $doc;
      }
      if ( (boolean)$doc['public'] ) {
        return $doc;
      }
    }
    return null;
  }
  function get_docs(){
    $limit = 1000;
    $qs = \Cockatoo\parse_brl_query($this->queries);
    if ( isset($qs[\Cockatoo\Beak::Q_LIMIT]) ) {
      $limit = $qs[\Cockatoo\Beak::Q_LIMIT];
    }
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->SERVICE,$this->COLLECTION,'',\Cockatoo\Beak::M_GET_RANGE,array(\Cockatoo\Beak::Q_EXCEPTS => 'contents,attenders,waiters,cancelers',\Cockatoo\Beak::Q_SORT => '_u:-1',\Cockatoo\Beak::Q_LIMIT => $limit),array());
    $docs = \Cockatoo\BeakController::beakSimpleQuery($brl);
    $isRoot = $this->isRoot;
    $user = $this->user;
    $docs = array_filter($docs,function ($doc) use(&$user,&$isRoot) {
        return (boolean)$doc['public'] || $isRoot || $doc['_owner'] === $user;
      });
    array_walk($docs,function (&$doc) use(&$user,&$isRoot) {
        if ( $isRoot || $doc['_owner'] === $user ) {
          $doc['writable'] = true;
        }
      });
    return $docs;
  }
  function save_doc($docid,&$doc,$force = false){
    if ( $doc && 
         ( $force || $this->isRoot || $doc['_owner'] === $this->user )) {
      unset($doc['writable']);
      $docid = \Cockatoo\UrlUtil::urlencode($docid);
      $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->SERVICE,$this->COLLECTION,'/'.$docid,\Cockatoo\Beak::M_SET,array(),array());
      $ret = \Cockatoo\BeakController::beakSimpleQuery($brl,$doc);
      if ( $ret ) {
        return $ret;
      }
      throw new \Exception('Cannot save it ! Probably storage error...');
    }
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
      $this->username = Lib::name($session);
      $this->isRoot = Lib::isRoot($session);
      $this->isWritable = Lib::isWritable($session);
      $docid          = $this->args['E'];
      $doc = null;
      if ( $docid ) {
        $doc = $this->get_doc($docid);
      }
      $post = $session[\Cockatoo\Def::SESSION_KEY_POST];
      $op = $post['op'];

      $retdoc = $this->begin_hook($op,$docid,$doc,$post);
      if ( $retdoc ) {
        return array( $this->DOCNAME => $retdoc);
      }
      if ( $this->method === \Cockatoo\Beak::M_GET ) {
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
          if ( $doc ) {
            $this->set_hook($doc);
            return array( $this->DOCNAME => $doc);
          }
          $doc = $this->new_doc();
          $doc['writable'] = true;
          return array( $this->DOCNAME => $doc);
        }
        if ($doc){
          $doc['public'] = false;
        }
        $this->post_to_doc($post,$doc);
        if ( !$doc['_owner'] ) {
          $doc['_owner'] = $this->user;
          $doc['_ownername'] = $this->username;
          $doc['docid'] = $this->update_docid($docid,$doc);
          $prev = $this->get_doc($doc['docid'],true);
          if ( $prev && $prev['_owner'] !== $doc['_owner'] ) {
            throw new \Exception('You do not have permission or the event is already registed.');
          }
        }
        if( $op === 'preview' ) {
          $this->preview_hook($doc);
          $doc['writable'] = true;
          return array( $this->DOCNAME => $doc );
        }elseif( $op === 'save' ) {
          $old_docid = $docid;
          $new_docid = $this->update_docid($docid,$doc);
          $doc['docid'] = $new_docid;
          $doc['_time'] = time();
          $doc['_timestr'] = date('Y-m-d',$doc['_time']);
          $this->save_hook($doc);
          $this->save_doc($new_docid,$doc);
          if ( $new_docid !== $old_docid ) {
            $this->remove_doc($old_docid);
          }
          $redirect = $this->post_save_hook($doc);
          if ( ! $redirect ) {
            $redirect = $this->REDIRECT.'/'.$new_docid;
          }
          $this->setMovedTemporary($redirect);
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

