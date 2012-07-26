<?php
namespace yslowviewer;
/**
 * YslowAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/03/12
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
class YslowAction extends BeaconAction {
  protected $STORAGE='yslow';
  function get_json(){
    $session = $this->getSession();
    return json_decode($session[\Cockatoo\Def::SESSION_KEY_POST],1);
  }
  function form_beacon($beacon){
    $beacon['u'] = \Cockatoo\UrlUtil::urlencode(\Cockatoo\UrlUtil::urldecode($beacon['u']));
    return $beacon;
  }
  function list_form($beacon) {
    return array('t' => $beacon['t'],'o' => $beacon['o'],'lt' => $beacon['lt']);
  }
  function form_detail($beacon) {
    foreach( $beacon['g'] as $k => $e) {
      foreach ( $beacon['g'][$k]['components'] as $n => $v ) {
        $beacon['g'][$k]['components'][$n] = \Cockatoo\UrlUtil::urldecode($v);
      }
    }
    foreach( $beacon['comps'] as $k => $v ) {
      $beacon['comps'][$k]['url'] = \Cockatoo\UrlUtil::urldecode($beacon['comps'][$k]['url']);
    }
    
    uasort($beacon['stats'],function($a,$b){
             return $a['w'] < $b['w'];
           });
    uasort($beacon['stats_c'],function($a,$b){
             return $a['w'] < $b['w'];
           });
    return $beacon;
  }

  function other_methods(){
    if ( $this->method === \Cockatoo\Beak::M_GET_ARRAY ) {
      $session = $this->getSession();
      $url = $session[\Cockatoo\Def::SESSION_KEY_GET]['u'];
      list($date,$str_date) = \Cockatoo\UtilDselector::select($session,86400);
      $eurl = \Cockatoo\UrlUtil::urlencode($url);
      $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,$this->STORAGE,$eurl,'',\Cockatoo\Beak::M_GET_RANGE,array(\Cockatoo\Beak::Q_EXCEPTS => 'stats,stats_c,comps',\Cockatoo\Beak::Q_SORT=>'_u:-1',\Cockatoo\Beak::Q_LIMIT=>100),array());
      $beacons = \Cockatoo\BeakController::beakSimpleQuery($brl,array('_u' => array('$lte' => $date)));

      $graph_summary;
      $graph_summary[0]['label']  = 'Total score';
      $graph_summary[0]['min']    = 0;
      $graph_summary[0]['max']    = 100;
      $graph_summary[1]['label']  = 'Resp time';
      $graph_summary[1]['dim']    = 'msec';
      
      $graph_scores[0]['label']   ="Make fewer HTTP requests";
      $graph_scores[0]['dim']     ="";
      $graph_scores[0]['min']     = 0;
      $graph_scores[0]['max']     = 100;
      $graph_scores[1]['label']   ="Use a Content Delivery Network (CDN)";
      $graph_scores[1]['hide']    = 1;
      $graph_scores[2]['label']   ="Avoid empty src or href";
      $graph_scores[2]['hide']    = 1;
      $graph_scores[3]['label']   ="Add Expires headers";
      $graph_scores[4]['label']   ="Compress components with gzip";
      $graph_scores[5]['label']   ="Put CSS at top";
      $graph_scores[5]['hide']    = 1;
      $graph_scores[6]['label']   ="Put JavaScript at bottom";
      $graph_scores[6]['hide']    = 1;
      $graph_scores[7]['label']   ="Avoid CSS expressions";
      $graph_scores[7]['hide']    = 1;
      $graph_scores[8]['label']   ="Make JavaScript and CSS external";
      $graph_scores[9]['label']   ="Reduce DNS lookups";
      $graph_scores[10]['label']  ="Minify JavaScript and CSS";
      $graph_scores[11]['label']  ="Avoid URL redirects";
      $graph_scores[12]['label']  ="Remove duplicate JavaScript and CSS";
      $graph_scores[12]['hide']   = 1;
      $graph_scores[13]['label']  ="Configure entity tags (ETags)";
      $graph_scores[14]['label']  ="Make AJAX cacheable";
      $graph_scores[14]['hide']   = 1;
      $graph_scores[15]['label']  ="Use GET for AJAX requests";
      $graph_scores[15]['hide']   = 1;
      $graph_scores[16]['label']  ="Reduce the number of DOM elements";
      $graph_scores[17]['label']  ="Avoid HTTP 404 (Not Found) error";
      $graph_scores[18]['label']  ="Reduce cookie size";
      $graph_scores[19]['label']  ="Use cookie-free domains";
      $graph_scores[20]['label']  ="Avoid AlphaImageLoader filter";
      $graph_scores[20]['hide']   = 1;
      $graph_scores[21]['label']  ="Do not scale images in HTML";
      $graph_scores[22]['label']  ="Make favicon small and cacheable";
      $graph_scores[22]['hide']   = 1;
      
      
//          $graph_summary[1]['label'] = 'Resp bytes';
//          $graph_summary[1]['dim']   = 'KB';
//          $graph_summary[2]['label'] = 'Num req';
//          $graph_summary[2]['dim'] = '';
      $times = array();
      $count = 0;
      foreach(array_reverse($beacons) as $beacon ){
        //foreach(array_reverse($beacons) as $beacon ){
        $u = $beacon['_u'];
        if ( $u ) {
          $times []= strftime($beacon['t']);
          $graph_summary[0]['data'] []= array($count, $beacon['o']);
          $graph_summary[1]['data'] []= array($count, $beacon['lt']);
//              $graph_summary[1]['data'] []= array($beacon['_t'], $beacon['w']/1000);
//              $graph_summary[2]['data'] []= array($beacon['_t'], $beacon['r']);
          
          $graph_scores[0]['data']  []= array($count, $beacon['g']['ynumreq']['score']);
          $graph_scores[1]['data']  []= array($count, $beacon['g']['ycdn']['score']);
          $graph_scores[2]['data']  []= array($count, $beacon['g']['yemptysrc']['score']);
          $graph_scores[3]['data']  []= array($count, $beacon['g']['yexpires']['score']);
          $graph_scores[4]['data']  []= array($count, $beacon['g']['ycompress']['score']);
          $graph_scores[5]['data']  []= array($count, $beacon['g']['ycsstop']['score']);
          $graph_scores[6]['data']  []= array($count, $beacon['g']['yjsbottom']['score']);
          $graph_scores[7]['data']  []= array($count, $beacon['g']['yexpressions']['score']);
          $graph_scores[8]['data']  []= array($count, $beacon['g']['yexternal']['score']);
          $graph_scores[9]['data']  []= array($count, $beacon['g']['ydns']['score']);
          $graph_scores[10]['data'] []= array($count, $beacon['g']['yminify']['score']);
          $graph_scores[11]['data'] []= array($count, $beacon['g']['yredirects']['score']);
          $graph_scores[12]['data'] []= array($count, $beacon['g']['ydupes']['score']);
          $graph_scores[13]['data'] []= array($count, $beacon['g']['yetags']['score']);
          $graph_scores[14]['data'] []= array($count, $beacon['g']['yxhr']['score']);
          $graph_scores[15]['data'] []= array($count, $beacon['g']['yxhrmethod']['score']);
          $graph_scores[16]['data'] []= array($count, $beacon['g']['ymindom']['score']);
          $graph_scores[17]['data'] []= array($count, $beacon['g']['yno404']['score']);
          $graph_scores[18]['data'] []= array($count, $beacon['g']['ymincookie']['score']);
          $graph_scores[19]['data'] []= array($count, $beacon['g']['ycookiefree']['score']);
          $graph_scores[20]['data'] []= array($count, $beacon['g']['ynofilter']['score']);
          $graph_scores[21]['data'] []= array($count, $beacon['g']['yimgnoscale']['score']);
          $graph_scores[22]['data'] []= array($count, $beacon['g']['yfavicon']['score']);
          $count++;
        }
      }
      return array('url' => $url,
                   '@json' => json_encode(array('times' => $times,
                                                'summary'=>$graph_summary,
                                                'scores'=>$graph_scores
                                            )));
    }
    throw new \Exception('Unexpected method ! : ' . $this->method);
  }
}

