<?php
namespace yslowviewer;
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/Action.php');
/**
 * BeaconAction.php - Beacon base action
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/03/12
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
abstract class BeaconAction extends \Cockatoo\Action {
  protected $STORAGE=null;
  protected static function urlencode($url){
    $url = urlencode($url);
    $url = str_replace('-','%2D',$url);
    return str_replace('.','%2E',$url);
  }
  abstract function get_json();
  abstract function form_beacon($beacon);
  abstract function list_form($beacon);
  abstract function form_detail($beacon);

  function other_methods(){
    throw new \Exception('Unexpected method ! : ' . $this->method);
  }

  public function proc(){
    try{
      $this->setNamespace('yslowviewer');
      if ( $this->method === \Cockatoo\Beak::M_SET ) {
        $now = time();
        $beacon = json_decode($session[\Cockatoo\Def::SESSION_KEY_POST],1);
        $beacon = $this->get_json();

        $beacon['t'] = strftime('%Y-%m-%d %H:%M:%S',$now);
        $beacon['_t'] = $now;
        $beacon = $this->form_beacon($beacon);
        // Create collection
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,$beacon['u'],'',\Cockatoo\Beak::M_CREATE_COL,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array($brl));
        // Save latest
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,$beacon['u'],'',\Cockatoo\Beak::M_SET,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$beacon)));
        if ( ! $ret[$brl] ) {
          throw new \Exception('Cannot save it ! Probably storage error...');
        }
        // Save 
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,$beacon['u'],$now,\Cockatoo\Beak::M_SET,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$beacon)));
        if ( ! $ret[$brl] ) {
          throw new \Exception('Cannot save it ! Probably storage error...');
        }
      }elseif ( $this->method === \Cockatoo\Beak::M_COL_LIST ) {
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,'','',\Cockatoo\Beak::M_COL_LIST,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array($brl));
        $urls;
        foreach($ret[$brl] as $url ) {
          $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,$url,'',\Cockatoo\Beak::M_GET,array(),array());
          $ret = \Cockatoo\BeakController::beakQuery(array($brl));
          $urls[$url]= $this->list_form($ret[$brl]);
          $urls[$url]['url'] = urldecode($url);
        }
        return array('urls' => $urls);
      }elseif ( $this->method === \Cockatoo\Beak::M_KEY_LIST ) {
        $session = $this->getSession();
        $url = $session[\Cockatoo\Def::SESSION_KEY_GET]['u'];
        $url = self::urlencode($url);
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,$url,'',\Cockatoo\Beak::M_KEY_LIST,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array($brl));
        rsort($ret[$brl]);
        // times
        $times;
        foreach($ret[$brl] as $t ) {
          if ( $t ) {
            $times [$t]= strftime('%Y-%m-%d %H:%M:%S',$t);
          }
        }
        return array('times' => $times,'u' => $url);
      }elseif ( $this->method === \Cockatoo\Beak::M_GET ) {
        $session = $this->getSession();
        $url = $session[\Cockatoo\Def::SESSION_KEY_GET]['u'];
        $url = self::urlencode($url);
        $t = $session[\Cockatoo\Def::SESSION_KEY_GET]['t'];
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,$url,$t,\Cockatoo\Beak::M_GET,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array($brl));
        if ( ! $ret[$brl] ) {
          throw new \Exception('Cannot get it ! Probably data not found...');
        }
        $beacon = $ret[$brl];
        $beacon['u'] = urldecode($beacon['u']);
        $beacon['url'] = $url;
        return $this->form_detail($beacon);
      }else{
        return $this->other_methods();
      }
    }catch ( \Exception $e ) {
      $s['emessage'] = $e->getMessage();
      $this->updateSession($s);
      $this->setRedirect('/yslowviewer/default/main');
       \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
      return null;
    }
  }
}



