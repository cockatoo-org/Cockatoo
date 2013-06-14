<?php
namespace Cockatoo;
require_once(Config::COCKATOO_ROOT.'action/Action.php');
/**
 * GetPageAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/07/07
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */

abstract class PageAction extends Action {
  protected $PAGEDATA = 'page';
  protected $NAMESPACE;
  protected $STORAGE;
  protected $BASEPATH;

  abstract protected function user(&$session);
  abstract protected function name(&$session);
  abstract protected function isWritable(&$session);
  

  # Page
  protected function page(&$page,&$origin,&$contents,&$user){
    return array('title' => $page,'origin' => $origin , 'contents' => $contents);
  }
  protected function get_page($page){
    $page = \Cockatoo\path_urlencode($page);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,$this->PAGEDATA,'/'.$page,\Cockatoo\Beak::M_GET,array(),array());
    $page_data = \Cockatoo\BeakController::beakSimpleQuery($brl);
    return $page_data;
  }
  protected function save_page($page,&$pdata){
    $page = \Cockatoo\path_urlencode($page);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,$this->PAGEDATA,'/'.$page,\Cockatoo\Beak::M_SET,array(),array());
    $ret = \Cockatoo\BeakController::beakSimpleQuery($brl,$pdata);
    if ( $ret ) {
      return $ret;
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }
  protected function remove_page($page){
    $page = \Cockatoo\path_urlencode($page);
    $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,$this->PAGEDATA,'/'.$page,\Cockatoo\Beak::M_DEL,array(),array());
    $ret = \Cockatoo\BeakController::beakSimpleQuery($brl);
    if ( $ret ) {
      return $ret;
    }
    throw new \Exception('Cannot save it ! Probably storage error...');
  }

  public function proc(){
    $this->IMGPATH='/_s_/'.$this->STORAGE.'/page';
    try{
      $this->setNamespace($this->NAMESPACE);
      $session = $this->getSession();
      $user  = $this->user($session);
      $page  = $this->args['P'];
      $name  = $this->args['N'];
      // Query strings
      $op = $session[Def::SESSION_KEY_POST]['op'];
      if ( ! $op ) {
        $op = 'get';
      }
      if ( $op === 'get' ) {
        $this->updateSession(array($this->NAMESPACE => $session[$this->NAMESPACE] ) );
        $pdata = $this->get_page($page);
        if ( $pdata ) {
          return array( 'page' => $pdata);
        }
        $origin = '*New';
        $contents = array(array('tag' => 'h2','attr'=>array(),'children' => array(array('tag'=>'text' , 'text' => 'New'))));
        return array( 'page' =>
                      $this->page($page,
                                $origin,
                                $contents,
                                $user));
      }
      $page  = path_urlencode($session[Def::SESSION_KEY_POST]['page']);
      if( $op === 'preview' ) {
        $origin   = $session[Def::SESSION_KEY_POST]['origin'];
        $lines = preg_split("@\r?\n@",$origin);
        $parser = new PageParser($this->BASEPATH,$this->IMGPATH,$page,$lines);
        return array( 'page' => 
                      $this->page($page,
                                $origin,
                                $parser->parse(),
                                $user));
      }elseif( $op === 'save' ) {
        if ( ! $this->isWritable($session) ) {
          throw new \Exception('You are not admin.');
        }
        $origin   = $session[Def::SESSION_KEY_POST]['origin'];
        $lines = preg_split("@\r?\n@",$origin);
        $parser = new PageParser($this->BASEPATH,$this->IMGPATH,$page,$lines);
        $pdata = $this->page($page,
                           $origin,
                           $parser->parse(),
                           $user);
        $pdata['_owner'] = $user;
        $pdata['_ownername'] = $this->name($session);
        $pdata['_time'] = time();
        $pdata['_timestr'] = date('Y-m-d',$pdata['_time']);
        $ret = $this->save_page($page,$pdata);
        // $this->setMovedTemporary('/mongo/'.$page);
        return array('r' => $ret);
      }elseif( $op === 'move' ) {
        if ( ! $this->isWritable($session) ) {
          throw new \Exception('You are not admin.');
        }
        $new = $session[Def::SESSION_KEY_POST]['new'];
        if ( $new ) {
          $pdata = $this->get_page($page);
          if ( $pdata ) {
            $pdata['title'] = $new;
            $lines = preg_split("@\r?\n@",$pdata['origin']);
            $parser = new PageParser($this->BASEPATH,$this->IMGPATH,$page,$lines);
            $pdata['contents'] = $parser->parse();
            $this->save_page($new,$pdata);
            $this->move_image($new,$page);
            $this->remove_page($page);
//            $this->setMovedTemporary('/mongo/'.$new);
            return array();
          }
//          $this->setMovedTemporary('/mongo/main');
        }else{
//          $this->setMovedTemporary('/mongo/'.$page);
        }
        return array();
      }elseif( $op === 'fupload' ) {
        $image = $session[Def::SESSION_KEY_POST]['filename'];
        if ( ! $image ) {
          return array('r' => False);
        }
        $fname = $page .'/'.path_urlencode($image['n']);
        $brl =  brlgen(Def::BP_STATIC, $this->STORAGE, 'page', $fname, null);
        $type = $image[Def::F_TYPE];
        $content = &$image[Def::F_CONTENT];
        $ret = StaticContent::save($brl,$type,$this->user,$content);
        return array('r' => $ret);
      }elseif( $op === 'flist' ) {
        $brl =  brlgen(Def::BP_STATIC, $this->STORAGE, 'page', $page, Beak::M_KEY_LIST);
        $images = BeakController::beakSimpleQuery($brl);
        $ret = [];
        foreach ( $images as &$fname ) {
          $ret[substr($fname,strlen($page)+1)] = $this->IMGPATH.'/' . $fname;
        }
        return $ret;
      }elseif( $op === 'fdelete' ) {
        $fname = $page .'/'.$session[Def::SESSION_KEY_POST]['filename'];
        $brl =  brlgen(Def::BP_STATIC, $this->STORAGE, 'page', $fname, Beak::M_DEL);
        $ret = BeakController::beakSimpleQuery($brl);
        return array('r' => $ret);
      }
    }catch ( \Exception $e ) {
      $s[Def::SESSION_KEY_ERROR] = $e->getMessage();
      $this->updateSession($s);
//      $this->setMovedTemporary('/mongo/main');
       Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
      return null;
    }
  }
  // @@@
  private function move_image($new,$page){
    $olds = array();
    $news = array();
    $brl =  brlgen(Def::BP_STATIC, $this->STORAGE, $page, '', Beak::M_KEY_LIST);
    $images = BeakController::beakSimpleQuery($brl);
    foreach ( $images as $name ) {
      $old = brlgen(Def::BP_STATIC, $this->STORAGE, $page, $name, Beak::M_DEL);
      $olds []= $old;
      $obrl  = brlgen(Def::BP_STATIC, $this->STORAGE, $page, $name, Beak::M_GET);
      $oret = BeakController::beakQuery(array($obrl));
      if ( $oret[$obrl] ) {
        $nset = brlgen(Def::BP_STATIC, $this->STORAGE, $new, $name, Beak::M_SET);
        $news []= array($nset,$oret[$obrl]);
      }
    }
    $ret = BeakController::beakQuery($news);
    $ret = BeakController::beakQuery($olds);
  }

  public function postProc(){
  }
}

