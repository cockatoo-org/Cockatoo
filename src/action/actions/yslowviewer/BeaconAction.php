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
        $session = $this->getSession();
        $user  = $session[\Cockatoo\AccountUtil::SESSION_LOGIN][\Cockatoo\AccountUtil::KEY_USER];
        if ( ! $user and YslowviewerConfig::ACL ) {
          \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ ,'Guest users are not allowed to POST');
          return null; // Guest users are not allowed to POST
        }
        $now = time();
        $beacon = json_decode($session[\Cockatoo\Def::SESSION_KEY_POST],1);
        $beacon = $this->get_json();


        $beacon['t'] = strftime('%Y-%m-%d %H:%M:%S',$now);
        $beacon['_t'] = $now;
        $beacon = $this->form_beacon($beacon);
        // Create collection
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,$beacon['u'],'',\Cockatoo\Beak::M_CREATE_COL,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array($brl));
        // Save 
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,$beacon['u'],$now,\Cockatoo\Beak::M_SET,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$beacon)));
        if ( ! $ret[$brl] ) {
          throw new \Exception('Cannot save it ! Probably storage error...');
        }
        // Save list
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,'URLS',$beacon['u'],\Cockatoo\Beak::M_SET,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$beacon)));

      }elseif ( $this->method === \Cockatoo\Beak::M_COL_LIST ) {
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,'URLS','',\Cockatoo\Beak::M_GET_RANGE,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,array())));
        $eurls =$ret[$brl];

        $urls = array();
        $THIS = &$this;
        array_map(function($r) use(&$urls,$THIS){ 
            $url = \Cockatoo\UrlUtil::urldecode($r['_u']);
            $domain = parse_url($url,\PHP_URL_HOST );
            $edomain = \Cockatoo\UrlUtil::urlencode($domain);
            $urls[$edomain]['domain'] = $domain;
            $urls[$edomain]['urls'][$r['_u']] = $THIS->list_form($r);
            $urls[$edomain]['urls'][$r['_u']]['url'] = $url;
            return;
          },$eurls);
        return array('domains' => $urls);
      }elseif ( $this->method === \Cockatoo\Beak::M_KEY_LIST ) {
        $session = $this->getSession();
        $url = $session[\Cockatoo\Def::SESSION_KEY_GET]['u'];
        list($date,$str_date) = \Cockatoo\UtilDselector::select($session,86400);
        $url = \Cockatoo\UrlUtil::urlencode($url);
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,$url,'',\Cockatoo\Beak::M_GET_RANGE,array(\Cockatoo\Beak::Q_FILTERS=>'_u,t',\Cockatoo\Beak::Q_SORT=>'_u:-1',\Cockatoo\Beak::Q_LIMIT=>100),array());
        $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,array('_u' => array('$lte' => $date)))));
        // times
        $times;
        foreach($ret[$brl] as $b) {
          if ( $b['_u'] ) {
            $times [$b['_u']]= $b['t'];
          }
        }
        return array('times' => $times,'u' => $url,'date' => $str_date);
      }elseif ( $this->method === \Cockatoo\Beak::M_GET ) {
        $session = $this->getSession();
        $url = $session[\Cockatoo\Def::SESSION_KEY_GET]['u'];
        list($date,$str_date) = \Cockatoo\UtilDselector::select($session,86400);
        $eurl = \Cockatoo\UrlUtil::urlencode($url);
        $t = $session[\Cockatoo\Def::SESSION_KEY_GET]['t'];
        $beacon = null;
        if ( $t ) {
          $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,$eurl,$t,\Cockatoo\Beak::M_GET,array(),array());
          $ret = \Cockatoo\BeakController::beakQuery(array($brl));
          if ( ! $ret[$brl] ) {
            // throw new \Exception('Cannot get it ! Probably data not found...');
          }else{
            $beacon = $ret[$brl];
          }
        }
        if ( ! $beacon ){
          $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,$eurl,'',\Cockatoo\Beak::M_GET_RANGE,array(\Cockatoo\Beak::Q_SORT=>'_u:-1',\Cockatoo\Beak::Q_LIMIT=>1),array());
          $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,array('_u' => array('$lte' => $date)))));
          // $ret = \Cockatoo\BeakController::beakQuery(array($brl));
          if ( ! $ret[$brl] ) {
            throw new \Exception('Cannot get it ! Probably data not found...');
          }
          $beacon = $ret[$brl][0];
        }
        //$beacon['u'] = \Cockatoo\UrlUtil::urldecode($beacon['u']);
        $beacon['url'] = $url;
        return $this->form_detail($beacon);
      }else{
        return $this->other_methods();
      }
    }catch ( \Exception $e ) {
      $s['emessage'] = $e->getMessage();
      $this->updateSession($s);
      // $this->setRedirect('/yslowviewer/default/main');
       \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
      return null;
    }
  }
}



