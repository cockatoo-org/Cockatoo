<?php
namespace yslowviewer;
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/Action.php');
/**
 * NetexportAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/03/23
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */

class Netexportaction extends \Cockatoo\Action {
  const STORAGE='netexport';
  private static function urlencode($url){
    $url = urlencode($url);
    $url = str_replace('-','%2D',$url);
    return str_replace('.','%2E',$url);
  }
  public function proc(){
    try{
      $this->setNamespace('yslowviewer');
      if ( $this->method === \Cockatoo\Beak::M_SET ) {
        $now = time();
        $session = $this->getSession();
        $har = json_decode($session[\Cockatoo\Def::SESSION_KEY_POST],1);
//        $beacon['har'] = $session[\Cockatoo\Def::SESSION_KEY_POST];
        $beacon['har'] = $har;
        $beacon['t'] = strftime('%Y-%m-%d %H:%M:%S',$now);
        $beacon['_t'] = $now;
        $beacon['u']=self::urlencode($har['log']['entries'][0]['request']['url']);
        foreach($har['log']['entries'] as $k => $req ){
          if ( isset($har['log']['entries'][$k]['response']['content']['text'])) {
            $har['entries'][$k]['response']['content']['text'] = '...';
          }
        }
        // Create collection
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,self::STORAGE,$beacon['u'],'',\Cockatoo\Beak::M_CREATE_COL,array(\Cockatoo\Beak::Q_UNIQUE_INDEX=>'_u'),array());
        $ret = \Cockatoo\BeakController::beakQuery(array($brl));
        // Save latest
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,self::STORAGE,$beacon['u'],'',\Cockatoo\Beak::M_SET,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$beacon)));
        if ( ! $ret[$brl] ) {
          throw new \Exception('Cannot save it ! Probably storage error...');
        }
        // Save 
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,self::STORAGE,$beacon['u'],$now,\Cockatoo\Beak::M_SET,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$beacon)));
        if ( ! $ret[$brl] ) {
          throw new \Exception('Cannot save it ! Probably storage error...');
        }
      }elseif ( $this->method === \Cockatoo\Beak::M_COL_LIST ) {
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,self::STORAGE,'','',\Cockatoo\Beak::M_COL_LIST,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array($brl));
        $urls;
        foreach($ret[$brl] as $url ) {
          $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,self::STORAGE,$url,'',\Cockatoo\Beak::M_GET,array(),array());
          $ret = \Cockatoo\BeakController::beakQuery(array($brl));
          $urls [$url]= array('url' => urldecode($url),'t' => $ret[$brl]['t'],'o' => $ret[$brl]['o'],'lt' => $ret[$brl]['pages'][0]['pageTimings']['onLoad']);
        }
        return array('urls' => $urls);
      }elseif ( $this->method === \Cockatoo\Beak::M_KEY_LIST ) {
        $session = $this->getSession();
        $url = $session[\Cockatoo\Def::SESSION_KEY_GET]['u'];
        $url = self::urlencode($url);
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,self::STORAGE,$url,'',\Cockatoo\Beak::M_KEY_LIST,array(),array());
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
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,self::STORAGE,$url,$t,\Cockatoo\Beak::M_GET,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array($brl));
        if ( ! $ret[$brl] ) {
          throw new \Exception('Cannot get it ! Probably data not found...');
        }
        $beacon = $ret[$brl];
        $beacon['u'] = urldecode($beacon['u']);
        
        return array('url' => $url,
                     '@HAR' => json_encode($beacon['har']),
                     '_t' => $beacon['_t']);
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