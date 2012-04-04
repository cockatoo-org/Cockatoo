<?php
namespace yslowviewer;
/**
 * PagespeedAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/04/03
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */

class PagespeedAction extends BeaconAction {
  protected $STORAGE='pagespeed';
  function get_json(){
    $session = $this->getSession();
    return json_decode($session[\Cockatoo\Def::SESSION_KEY_POST]['content'],1);
  }
  function form_beacon($beacon){
    $beacon['u']=self::urlencode($beacon['pageStats']['initialUrl']);
    foreach($beacon['rules'] as $i => $k){
      $beacon['rules'][$i]['@warnings'] = $beacon['rules'][$i]['warnings'];
      unset($beacon['rules'][$i]['warnings']);
    }
    return $beacon;
  }
  function list_form($beacon){
    return array('t' => $beacon['t'],'o' => $beacon['pageStats']['overallScore'],'lt' => $beacon['pageStats']['pageLoadTime']);
  }
  function form_detail($beacon){
    uasort($beacon['rules'],function($a,$b){
             if ( $a['score'] === 'disabled' ) {
               return true;
             }
             if ( $b['score'] === 'disabled' ) {
               return false;
             }
             return $a['score'] > $b['score'];
           });
    return $beacon;
  }
  function other_methods(){
    if ( $this->method === \Cockatoo\Beak::M_GET_ARRAY ) {
      $session = $this->getSession();
      $url = $session[\Cockatoo\Def::SESSION_KEY_GET]['u'];
      $url = self::urlencode($url);
      $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,$url,'',\Cockatoo\Beak::M_KEY_LIST,array(),array());
      $ret = \Cockatoo\BeakController::beakQuery(array($brl));
      $times = &$ret[$brl];
      rsort($times);
      $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,$url,'',\Cockatoo\Beak::M_GET_ARRAY,array(),array());
      $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,array('_u' => $times))));

      $graph_summary;
      $graph_summary[0]['label']  = 'Total score';
      $graph_summary[0]['label']  = 'Total score';
      $graph_summary[0]['min']    = 0;
      $graph_summary[0]['max']    = 100;
      $graph_summary[1]['label']  = 'Resp time';
      $graph_summary[1]['dim']    = 'msec';
      
      $graph_scores;
  

      $times = array();
      $count = 0;
      ksort($ret[$brl]);
      foreach($ret[$brl] as $u => $data ){
        if ( $u ) {
          $times []= strftime($data['t']);
          $graph_summary[0]['data'] []= array($count, $data['pageStats']['overallScore']);
          $graph_summary[1]['data'] []= array($count, $data['pageStats']['pageLoadTime']);
          foreach($data['rules'] as $i => $rule){
            $graph_scores[$i]['label']   = $rule['shortName'];
            $graph_scores[$i]['label2']   = $rule['name'];
            $graph_scores[$i]['dim']     ="";
            $graph_scores[$i]['min']     = 0;
            $graph_scores[$i]['max']     = 100;
            $graph_scores[$i]['hide']    = 1;
            $graph_scores[$i]['data']    []= array($count, $rule['score']);
          }

          $count++;
        }
      }

      $graph_scores[3]['hide']   = 0;
      $graph_scores[4]['hide']   = 0;
      $graph_scores[5]['hide']   = 0;
      $graph_scores[6]['hide']   = 0;
      $graph_scores[8]['hide']   = 0;
      $graph_scores[9]['hide']   = 0;
      $graph_scores[10]['hide']  = 0;
      $graph_scores[11]['hide']  = 0;
      $graph_scores[15]['hide']  = 0;
      $graph_scores[16]['hide']  = 0;
      $graph_scores[18]['hide']  = 0;
      $graph_scores[19]['hide']  = 0;
      $graph_scores[20]['hide']  = 0;
      $graph_scores[21]['hide']  = 0;
      $graph_scores[22]['hide']  = 0;
      $graph_scores[27]['hide']  = 0;
      $graph_scores[28]['hide']  = 0;

      return array('url' => $url,
                   '@json' => json_encode(array('times' => $times,
                                                'summary'=>$graph_summary,
                                                'scores'=>$graph_scores
                                            )));
    }
    throw new \Exception('Unexpected method ! : ' . $this->method);
  }
}