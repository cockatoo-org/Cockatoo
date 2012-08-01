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
  public function proc(){
    try{
      $this->setNamespace('pwatch');
      $session = $this->getSession();
      $user  = $session[\Cockatoo\AccountUtil::SESSION_LOGIN][\Cockatoo\AccountUtil::KEY_USER];
      if ( $this->method === \Cockatoo\Beak::M_GET ) {
        $url = $session[\Cockatoo\Def::SESSION_KEY_GET]['u'];
        $LIMIT = $session[\Cockatoo\Def::SESSION_KEY_GET]['limit'];
        $LIMIT = $LIMIT?(int)$LIMIT:100;
        list($date,$str_date) = \Cockatoo\UtilDselector::select($session,86400);
        $eurl= \Cockatoo\UrlUtil::urlencode($url);
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'pwatch',$eurl,'',\Cockatoo\Beak::M_GET_RANGE,array(\Cockatoo\Beak::Q_FILTERS=>'_u,t,SUMMARY',\Cockatoo\Beak::Q_SORT=>'_u:-1',\Cockatoo\Beak::Q_LIMIT=>$LIMIT),array());
        $origins = \Cockatoo\BeakController::beakSimpleQuery($brl,array('_u' => array('$lte' => $date)));
        $datas = array();
        $i = 0;
        foreach( $origins as $data ) {
          $datas[$i] = $data['SUMMARY'];
          $datas[$i]['t'] = $data['t'];
          $i++;
        }
        return array(
          'url' => $url,
          'datas' => $datas,
          '@json' => json_encode(array_reverse($datas)),
          );
      }elseif ( $this->method === \Cockatoo\Beak::M_SET ) {
        $submit = $session[\Cockatoo\Def::SESSION_KEY_POST]['submit'];
        $url = $session[\Cockatoo\Def::SESSION_KEY_POST]['u'];
        $eurl = \Cockatoo\UrlUtil::urlencode($url);
        $interval = $session[\Cockatoo\Def::SESSION_KEY_POST]['interval'];
        $style    = $session[\Cockatoo\Def::SESSION_KEY_POST]['style'];

        if (  $submit === 'add URL' ) {
          if ( ! $user and PwatchConfig::ACL ) {
            throw new \Exception('Guest users are not allowed to ADD');
          }
          if ( preg_match('@^https?://@', $url , $matches ) === 0 ) {
            throw new \Exception('Invalid URL : ' . $url);
          }
          if ( $interval < 1 ){
            throw new \Exception('Invalid Interval : ' . $interval);
          }
          $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'pwatch',$eurl,'',\Cockatoo\Beak::M_CREATE_COL,array(),array());
          $ret = \Cockatoo\BeakController::beakSimpleQuery($brl);
          // Save 
          $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'pwatch','URLS',$eurl,\Cockatoo\Beak::M_SET,array(),array(\Cockatoo\Beak::COMMENT_KIND_PARTIAL));
          $ret = \Cockatoo\BeakController::beakSimpleQuery($brl,array('_u' => $eurl,'url' => $url,'interval' => $interval,'style' => $style));
          if ( ! $ret ) {
            throw new \Exception('Cannot save it ! Probably storage error...');
          }
        }elseif ( $submit === 'remove URL') {
          if ( preg_match('@^https?://@', $url , $matches ) === 0 ) {
            throw new \Exception('Invalid URL : ' . $url);
          }
          // Delete
          $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'pwatch','URLS',$eurl,\Cockatoo\Beak::M_DEL,array(),array());
          $ret = \Cockatoo\BeakController::beakSimpleQuery($brl);
        }
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'pwatch','URLS','',\Cockatoo\Beak::M_GET_RANGE,array(),array());
        $urls = \Cockatoo\BeakController::beakSimpleQuery($brl,array());
        return array('urls' => $urls);
      }elseif ( $this->method === \Cockatoo\Beak::M_KEY_LIST ) {
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'pwatch','URLS','',\Cockatoo\Beak::M_GET_RANGE,array(),array());
        $urls = \Cockatoo\BeakController::beakSimpleQuery($brl,array());
        return array('urls' => $urls);
      }
    }catch ( \Exception $e ) {
      $s[\Cockatoo\Def::SESSION_KEY_ERROR] = $e->getMessage();
      $this->updateSession($s);
      $this->setRedirect('main');
       \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
      return null;
    }
  }
}