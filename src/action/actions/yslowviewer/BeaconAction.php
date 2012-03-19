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
        $beacon = json_decode($session[\Cockatoo\Def::SESSION_KEY_POST],1);
        $beacon['t'] = strftime('%Y-%m-%d %H:%M:%S',$now);
        $beacon['_t'] = $now;
        $beacon['u'] = self::urlencode(urldecode($beacon['u']));
        // Create collection
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'yslowviewer',$beacon['u'],'',\Cockatoo\Beak::M_CREATE_COL,array(\Cockatoo\Beak::Q_UNIQUE_INDEX=>'_u'),array());
        $ret = \Cockatoo\BeakController::beakQuery(array($brl));
        // Save latest
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'yslowviewer',$beacon['u'],'',\Cockatoo\Beak::M_SET,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$beacon)));
        if ( ! $ret[$brl] ) {
          throw new \Exception('Cannot save it ! Probably storage error...');
        }
        // Save 
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'yslowviewer',$beacon['u'],$now,\Cockatoo\Beak::M_SET,array(),array());
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
        $url = self::urlencode($url);
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'yslowviewer',$url,'',\Cockatoo\Beak::M_KEY_LIST,array(),array());
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
        if ( ! $this->queries ) {
          $session = $this->getSession();
          $url = $session[\Cockatoo\Def::SESSION_KEY_GET]['u'];
          $url = self::urlencode($url);
          $t = $session[\Cockatoo\Def::SESSION_KEY_GET]['t'];
          $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'yslowviewer',$url,$t,\Cockatoo\Beak::M_GET,array(),array());
          $ret = \Cockatoo\BeakController::beakQuery(array($brl));
          if ( ! $ret[$brl] ) {
            throw new \Exception('Cannot save it ! Probably storage error...');
          }
          $beacon = $ret[$brl];
          $beacon['u'] = urldecode($beacon['u']);
          foreach( $beacon['g'] as $k => $e) {
            foreach ( $beacon['g'][$k]['components'] as $n => $v ) {
              $beacon['g'][$k]['components'][$n] = urldecode($v);
            }
          }
          foreach( $beacon['comps'] as $k => $v ) {
            $beacon['comps'][$k]['url'] = urldecode($beacon['comps'][$k]['url']);
          }
        
          uasort($beacon['stats'],function($a,$b){
                   return $a['w'] < $b['w'];
                 });
          uasort($beacon['stats_c'],function($a,$b){
                   return $a['w'] < $b['w'];
                 });
          $beacon['url'] = $url;
          return $beacon;
        }else{ // &graph
          $session = $this->getSession();
          $url = $session[\Cockatoo\Def::SESSION_KEY_GET]['u'];
          $url = self::urlencode($url);
          $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'yslowviewer',$url,'',\Cockatoo\Beak::M_KEY_LIST,array(),array());
          $ret = \Cockatoo\BeakController::beakQuery(array($brl));
          $times = &$ret[$brl];
          rsort($times);
          $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'yslowviewer',$url,'',\Cockatoo\Beak::M_GET_ARRAY,array(),array());
          $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,array('_u' => $times))));
          $graph_summary;
          $graph_summary[0]['label'] = 'Resp time';
          $graph_summary[0]['dim']   = 'sec';
          $graph_summary[1]['label'] = 'Score';
          $graph_summary[1]['dim']   = '';

//          $graph_summary[1]['label'] = 'Resp bytes';
//          $graph_summary[1]['dim']   = 'KB';
//          $graph_summary[2]['label'] = 'Num req';
//          $graph_summary[2]['dim'] = '';
          foreach($ret[$brl] as $url => $data ){
            if ( $url ) {
              $graph_summary[0]['data'] []= array($data['_t']*1000, $data['lt']);
           $graph_summary[0]['label'] = 'Resp time';
          $graph_summary[0]['dim']   = 'sec';
             $graph_summary[1]['data'] []= array($data['_t']*1000, $data['o']);
//              $graph_summary[1]['data'] []= array($data['_t'], $data['w']/1000);
//              $graph_summary[2]['data'] []= array($data['_t'], $data['r']);
            }
          }
          return array('@json' => json_encode($graph_summary));
        }
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