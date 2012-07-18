<?php
namespace pwatch;
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/Action.php');
/**
 * PwatchAction.php - Beacon base action
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/07/11
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
class PwatchAction extends \Cockatoo\Action {
  protected static function urlencode($url){
    $url = urlencode($url);
    $url = str_replace('-','%2D',$url);
    return str_replace('.','%2E',$url);
  }
  public function proc(){
    try{
      $this->setNamespace('pwatch');
      $session = $this->getSession();
      if ( $this->method === \Cockatoo\Beak::M_GET ) {
        $url = $session[\Cockatoo\Def::SESSION_KEY_GET]['url'];
        $eurl= self::urlencode($url);
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'pwatch',$eurl,'',\Cockatoo\Beak::M_GET_RANGE,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,array('_u' => array('$gt' => 0)))));
        $datas = array();
        $i = 0;
        foreach( $ret[$brl] as $t => $data ) {
          $datas[$i] = $data['SUMMARY'];
          $datas[$i]['t'] = $data['t'];
          $i++;
        }
        return array(
          'url' => $url,
          'datas' => $datas,
          '@json' => json_encode($datas),
          );
      }elseif ( $this->method === \Cockatoo\Beak::M_SET ) {
        $submit = $session[\Cockatoo\Def::SESSION_KEY_POST]['submit'];
        $url = $session[\Cockatoo\Def::SESSION_KEY_POST]['url'];
        $eurl = self::urlencode($url);
        $interval = $session[\Cockatoo\Def::SESSION_KEY_POST]['interval'];
        $style    = $session[\Cockatoo\Def::SESSION_KEY_POST]['style'];
        $last  = $session[\Cockatoo\Def::SESSION_KEY_POST]['last'];
        $ptime = $session[\Cockatoo\Def::SESSION_KEY_POST]['ptime'];
        $total = $session[\Cockatoo\Def::SESSION_KEY_POST]['total'];
        $size = $session[\Cockatoo\Def::SESSION_KEY_POST]['size'];

        if (  $submit === 'add URL' ) {
          if ( ! $user and PwatchConfig::ACL ) {
            \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ ,'Guest users are not allowed to ADD');
            return null;
          }
          if ( preg_match('@^https?://@', $url , $matches ) === 0 ) {
            throw new \Exception('Invalid URL');
          }
          if ( $interval < 1 ){
            throw new \Exception('Invalid Interval');
          }
          $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'pwatch',$eurl,'',\Cockatoo\Beak::M_CREATE_COL,array(),array());
          $ret = \Cockatoo\BeakController::beakQuery(array($brl));
          // Save 
          $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'pwatch','URLS',$eurl,\Cockatoo\Beak::M_SET,array(),array());
          $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,array('_u' => $eurl,'url' => $url,'interval' => $interval,'last' => $last , 'ptime' => $ptime, 'style' => $style,'total' => $total , 'size' => $size))));
          if ( ! $ret[$brl] ) {
            throw new \Exception('Cannot save it ! Probably storage error...');
          }
        }elseif ( $submit === 'remove URL') {
          if ( preg_match('@^https?://@', $url , $matches ) === 0 ) {
            throw new \Exception('Invalid URL');
          }
          // Delete
          $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'pwatch','URLS',$eurl,\Cockatoo\Beak::M_DEL,array(),array());
          $ret = \Cockatoo\BeakController::beakQuery(array($brl));
        }
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'pwatch','URLS','',\Cockatoo\Beak::M_GET_RANGE,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,array('_u' => array('$gt' => '')))));
        return array('urls' => $ret[$brl]);
      }elseif ( $this->method === \Cockatoo\Beak::M_KEY_LIST ) {
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'pwatch','URLS','',\Cockatoo\Beak::M_GET_RANGE,array(),array());
        $ret = \Cockatoo\BeakController::beakQuery(array(array($brl,array('_u' => array('$gt' => '')))));
        return array('urls' => $ret[$brl]);
      }
    }catch ( \Exception $e ) {
      $s['emessage'] = $e->getMessage();
      $this->updateSession($s);
      $this->setRedirect('/pwatch/default/main');
       \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
      return null;
    }
  }
}