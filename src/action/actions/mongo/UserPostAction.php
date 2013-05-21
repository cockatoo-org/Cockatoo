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
  protected $ORDER      = '-1';
  protected $DOCS_EXCEPTS = 'contents';
  protected $IMAGE_PATH = 'default';

  public function docid(){
    return $this->args['E'];
  }
  abstract function new_doc();
  abstract function post_to_doc (&$post,&$doc); 
  function update_docid(&$docid,&$doc) {
    if ( ! $docid || strcmp($docid,'new')===0 ) {
      return $doc['_time'] . uniqid();
    }
    return $docid;
  }
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
  function post_remove_hook(){
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
      $doc['_u'] = \Cockatoo\UrlUtil::urldecode($doc['_u']);
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
    $qs = $this->get_queries();
    if ( isset($qs[\Cockatoo\Beak::Q_LIMIT]) ) {
      $limit = $qs[\Cockatoo\Beak::Q_LIMIT];
    }
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->SERVICE,$this->COLLECTION,'',\Cockatoo\Beak::M_GET_RANGE,array(\Cockatoo\Beak::Q_EXCEPTS => $this->DOCS_EXCEPTS,\Cockatoo\Beak::Q_SORT => '_u:'.$this->ORDER ,\Cockatoo\Beak::Q_LIMIT => $limit),array());
    $docs = \Cockatoo\BeakController::beakSimpleQuery($brl);
    $isRoot = $this->isRoot;
    $user = $this->user;
    $docs = array_filter($docs,function ($doc) use(&$user,&$isRoot) {
        return (boolean)$doc['public'] || $isRoot || $doc['_share'] || $doc['_owner'] === $user;
      });
    array_walk($docs,function (&$doc) use(&$user,&$isRoot) {
        $doc['_u'] = \Cockatoo\UrlUtil::urldecode($doc['_u']);
        if ( $isRoot || $doc['_owner'] === $user ) {
          $doc['writable'] = true;
        }
      });
    return $docs;
  }
  function save_doc($docid,&$doc,$force = false){
    if ( $doc && 
         ( $force || $this->isRoot || $doc['_share'] || $doc['_owner'] === $this->user )) {
      unset($doc['writable']);
      if ( $doc['images'] ) {
        foreach ( $doc['images']  as $name => $image ) {
          if ( is_array($image) ){
            $fname = $docid .'/'.$name;
            $brl =  \Cockatoo\brlgen(\Cockatoo\Def::BP_STATIC, 'mongo', $this->IMAGE_PATH, $fname, null);
            $type = $image[\Cockatoo\Def::F_TYPE];
            $content = &$image[\Cockatoo\Def::F_CONTENT];
            \Cockatoo\StaticContent::save($brl,$type,$this->user,$content);
            $doc['images'][$name]= $fname;
          }
        }
      }
      $docid = \Cockatoo\UrlUtil::urlencode($docid);
      $doc['public'] =  ($doc['public'])?true:false;
      $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->SERVICE,$this->COLLECTION,'/'.$docid,\Cockatoo\Beak::M_SET,array(),array());
      $ret = \Cockatoo\BeakController::beakSimpleQuery($brl,$doc);
      if ( $ret ) {
        return $ret;
      }
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }
  function remove_doc($docid){

    $brl =  \Cockatoo\brlgen(\Cockatoo\Def::BP_STATIC, 'mongo', $this->IMAGE_PATH, $docid, \Cockatoo\Beak::M_KEY_LIST);
    $images = \Cockatoo\BeakController::beakSimpleQuery($brl);
    foreach ( $images as $name ) {
      $brl =  \Cockatoo\brlgen(\Cockatoo\Def::BP_STATIC, 'mongo', $this->IMAGE_PATH, $name, \Cockatoo\Beak::M_DEL);
      \Cockatoo\BeakController::beakSimpleQuery($brl);
    }

    $docid = \Cockatoo\UrlUtil::urlencode($docid);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->SERVICE,$this->COLLECTION,'/'.$docid,\Cockatoo\Beak::M_DEL,array(),array());
    $ret = \Cockatoo\BeakController::beakSimpleQuery($brl);
    if ( $ret ) {
      return $ret;
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }
  function move_doc($docid,&$doc,$old_docid){
    if ( $doc && 
         ( $this->isRoot || $doc['_share'] || $doc['_owner'] === $this->user )) {
      $brl =  \Cockatoo\brlgen(\Cockatoo\Def::BP_STATIC, 'mongo', $this->IMAGE_PATH, $old_docid, \Cockatoo\Beak::M_KEY_LIST);
      $images = \Cockatoo\BeakController::beakSimpleQuery($brl);

      if ( $doc['images'] ) {
        foreach ( $doc['images']  as $name => $image ) {
          if ( ! is_array($image) ){
            unset($doc['images'][$image]);
          }
        }
      }
      foreach ( $images as $old_fname ) {
        if ( preg_match('@/([^/]+)$@',$old_fname,$matches) !== 0 ) {
          $name  = $matches[1];
          $fname = $docid . '/' . $name;
          $brl =  \Cockatoo\brlgen(\Cockatoo\Def::BP_STATIC, 'mongo', $this->IMAGE_PATH, $old_fname, \Cockatoo\Beak::M_GET);
          $img = \Cockatoo\BeakController::beakSimpleQuery($brl);
          $brl =  \Cockatoo\brlgen(\Cockatoo\Def::BP_STATIC, 'mongo', $this->IMAGE_PATH, $fname, \Cockatoo\Beak::M_SET);
          $ret = \Cockatoo\BeakController::beakSimpleQuery($brl,$img);
          $doc['images'][$name]= $fname;
        }
      }
      $this->save_doc($docid,$doc);
      $this->remove_doc($old_docid);
    }
  }

  public function proc(){
    try{
      $this->setNamespace($this->NAMESPACE);
      $session     = $this->getSession();
      $this->user  = Lib::user($session);
      $this->username = Lib::name($session);
      $this->isRoot = Lib::isRoot($session);
      $this->isWritable = Lib::isWritable($session);
      $docid          = $this->docid();
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
      $method  = $this->get_method();
      if ( $method === \Cockatoo\Beak::M_GET ) {
        if ( $doc ) {
          $this->get_hook($doc);
          return array( $this->DOCNAME => $doc);
        }
        $this->setMovedTemporary($this->REDIRECT);
        return null;
      }elseif( $method === \Cockatoo\Beak::M_GET_ARRAY ) {
        $docs = $this->get_docs();
        return array($this->DOCNAME.'s' => $docs);
      }elseif( $method === \Cockatoo\Beak::M_SET ) {
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
        // Set owner to new document
        if ( !$doc['_owner'] ) {
          $doc['_owner'] = $this->user;
          $doc['_ownername'] = $this->username;
        }
        $old_docid = $docid;
        $doc['_u'] = $this->update_docid($docid,$doc);
        // Check permission
        $prev = $this->get_doc($doc['_u'],true);
        if ( ! $doc['_share'] && $prev && $prev['_owner'] !== $this->user ) {
          throw new \Exception('You do not have permission or the event is already registed.');
        }
        // 
        if( $op === 'preview' ) {
          $this->preview_hook($doc);
          $doc['writable'] = true;
          return array( $this->DOCNAME => $doc );
        }elseif( $op === 'save' ) {
          $doc['_time'] = time();
          $doc['_timestr'] = date('Y-m-d',$doc['_time']);
          $this->save_hook($doc);
          if ( $old_docid && $doc['_u'] !== $old_docid ) {
            $this->move_doc($doc['_u'],$doc,$old_docid);
          }else{
            $this->save_doc($doc['_u'],$doc);
          }
          $redirect = $this->post_save_hook($doc);
          if ( ! $redirect ) {
            $redirect = $this->REDIRECT.'/'.$doc['_u'];
          }
          $this->setMovedTemporary($redirect);
          return array();
        }elseif( $op === 'remove' ) {
          $this->remove_doc($doc['_u']);
          $redirect = $this->post_remove_hook();
          if ( ! $redirect ) {
            $redirect = $this->REDIRECT;
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