<?php
namespace yslowviewer;
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

class Netexportaction extends BeaconAction {
  protected $STORAGE='netexport';
  function get_json(){
    $session = $this->getSession();
    return array('har' => json_decode($session[\Cockatoo\Def::SESSION_KEY_POST],1));
  }
  function form_beacon($beacon){
    $beacon['u']=\Cockatoo\UrlUtil::urlencode($beacon['har']['log']['entries'][0]['request']['url']);
    foreach($beacon['har']['log']['entries'] as $k => $req ){
      if ( isset($beacon['har']['log']['entries'][$k]['response']['content']['text'])) {
        $beacon['har']['entries'][$k]['response']['content']['text'] = '...';
      }
    }
    return $beacon;
  }
  function list_form($beacon){
    return array('t' => $beacon['t'],'o' => $beacon['o'],'lt' => $beacon['har']['log']['pages'][0]['pageTimings']['onLoad']);
  }
  function form_detail($beacon){
    $beacon['@HAR'] = json_encode($beacon['har']);
    unset($beacon['har']);
    return $beacon;
  }
}