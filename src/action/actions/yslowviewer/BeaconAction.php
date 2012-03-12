<?php
namespace yslowviewer;
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/Action.php');
/**
 * BeaconAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/03/12
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
class BeaconAction extends \Cockatoo\Action {
  public function proc(){
    try{
      $this->setNamespace('yslowviewer');
      if ( $this->method === \Cockatoo\Beak::M_SET ) {
        $session = $this->getSession();
        $beacon = json_decode($session[\Cockatoo\Def::SESSION_KEY_POST],1);
        $beacon['_u'] = strftime('%Y-%m-%d %H:%m:%S');
        
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'yslowviewer',$beacon['u'],'',\Cockatoo\Beak::M_CREATE_COL,array(\Cockatoo\Beak::Q_UNIQUE_INDEX=>'_u'),array());
        $ret = \Cockatoo\BeakController::beakQuery(array($brl));
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'yslowviewer',$beacon['u'],$beacon['_u'],\Cockatoo\Beak::M_SET,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$beacon)));
        if ( ! $ret[$brl] ) {
          throw new \Exception('Cannot save it ! Probably storage error...');
        }
      }elseif ( $this->method === \Cockatoo\Beak::M_COL_LIST ) {
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'yslowviewer','','',\Cockatoo\Beak::M_COL_LIST,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$beacon)));
        $urls;
        foreach($ret[$brl] as $url ) {
          $urls [$url]= urldecode($url);
        }
        return array('urls' => $urls);
      }elseif ( $this->method === \Cockatoo\Beak::M_KEY_LIST ) {
        $session = $this->getSession();
        $url = $session[\Cockatoo\Def::SESSION_KEY_GET]['u'];
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'yslowviewer',urlencode($url),'',\Cockatoo\Beak::M_KEY_LIST,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$beacon)));
        $times;
        foreach($ret[$brl] as $t ) {
          $times []= $t;
        }
        return array('times' => $times);
      }
      return array();
    }catch ( \Exception $e ) {
      $s['emessage'] = $e->getMessage();
      $this->updateSession($s);
      $this->setRedirect('/error');
       \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
      return null;
    }
  }
}