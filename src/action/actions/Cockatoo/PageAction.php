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
      $page  = $session[Def::SESSION_KEY_POST]['page'];
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
        return array('s' => $ret);
      }elseif( $op === 'remove' ) {
        if ( ! $this->isWritable($session) ) {
          throw new \Exception('You are not admin.');
        }
        $pdata = $this->get_page($page);
        if ( ! $pdata ) {
          return array('e' => 'Invalid origin page');
        }
        $this->remove_page($page);
        $brl =  brlgen(Def::BP_STATIC, $this->STORAGE, 'page', $page, Beak::M_KEY_LIST);
        $images = BeakController::beakSimpleQuery($brl);
        foreach ( $images as $name ) {
          $brl =  brlgen(Def::BP_STATIC, $this->STORAGE, 'page', $name, Beak::M_DEL);
          BeakController::beakSimpleQuery($brl);
        }
        return array('s' => true);
      }elseif( $op === 'move' ) {
        if ( ! $this->isWritable($session) ) {
          throw new \Exception('You are not admin.');
        }
        $new = $session[Def::SESSION_KEY_POST]['new'];
        if ( ! $new ) {
          return array('e' => 'Not specified');
        }
        if ( $new === $page ) {
          return array('e' => 'Specified orgin name');
        }
        $newpage = $this->get_page($new);
        if ( $newpage ) {
          return array('e' => 'Specified page was exist');
        }
        $pdata = $this->get_page($page);
        if ( ! $pdata ) {
          return array('e' => 'Invalid origin page');
        }
        $pdata['title'] = $new;
        $lines = preg_split("@\r?\n@",$pdata['origin']);
        $parser = new PageParser($this->BASEPATH,$this->IMGPATH,$new,$lines);
        $pdata['contents'] = $parser->parse();
        $this->save_page($new,$pdata);
        
        $epage = path_urlencode($page);
        $enew  = path_urlencode($new);
        
        $brl =  brlgen(Def::BP_STATIC, $this->STORAGE, 'page', $epage . '/', Beak::M_KEY_LIST);
        $images = BeakController::beakSimpleQuery($brl);
        if ( $images ) {
          foreach ( $images as &$fname ) {
            // GET image from old doc
            $brl =  brlgen(Def::BP_STATIC, $this->STORAGE, 'page', $fname, Beak::M_GET);
            $img = BeakController::beakSimpleQuery($brl);
            // SET image to new doc
            $newname = $enew .'/'.path_urlencode(substr($fname,strlen($epage)+1));
            $brl =  brlgen(Def::BP_STATIC, $this->STORAGE, 'page', $newname, Beak::M_SET);
            $ret = BeakController::beakSimpleQuery($brl,$img);
            // DEL image from old doc
            $brl =  brlgen(Def::BP_STATIC, $this->STORAGE, 'page', $fname, Beak::M_DEL);
            $img = BeakController::beakSimpleQuery($brl);
          }
        }
        $this->remove_page($page);
        return array('r' => $new);
      }elseif( $op === 'fupload' ) {
        if ( ! $this->isWritable($session) ) {
          throw new \Exception('You are not admin.');
        }
        $image = $session[Def::SESSION_KEY_POST]['filename'];
        if ( ! $image ) {
          return array('r' => 'Invalid image');
        }
        $epage  = path_urlencode($page);
        $fname = $epage .'/'.path_urlencode($image['n']);
        $brl =  brlgen(Def::BP_STATIC, $this->STORAGE, 'page', $fname, null);
        $type = $image[Def::F_TYPE];
        $content = &$image[Def::F_CONTENT];
        $ret = StaticContent::save($brl,$type,$this->user,$content);
        return array('s' => $ret);
      }elseif( $op === 'flist' ) {
        if ( ! $this->isWritable($session) ) {
          throw new \Exception('You are not admin.');
        }
        $epage  = path_urlencode($page);
        $brl =  brlgen(Def::BP_STATIC, $this->STORAGE, 'page', $epage .'/', Beak::M_KEY_LIST);
        $images = BeakController::beakSimpleQuery($brl);
        $ret = [];
        foreach ( $images as &$fname ) {
          $ret[substr($fname,strlen($epage)+1)] = $this->IMGPATH.'/' . $fname;
        }
        return $ret;
      }elseif( $op === 'fdelete' ) {
        if ( ! $this->isWritable($session) ) {
          throw new \Exception('You are not admin.');
        }
        $epage  = path_urlencode($page);
        $fname = $epage .'/'.$session[Def::SESSION_KEY_POST]['filename'];
        $brl =  brlgen(Def::BP_STATIC, $this->STORAGE, 'page', $fname, Beak::M_DEL);
        $ret = BeakController::beakSimpleQuery($brl);
        return array('s' => $ret);
      }
    }catch ( \Exception $e ) {
      $s[Def::SESSION_KEY_ERROR] = $e->getMessage();
      $this->updateSession($s);
       Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
      return null;
    }
  }

  public function postProc(){
  }
}

