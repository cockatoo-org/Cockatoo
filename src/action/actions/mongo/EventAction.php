<?php
namespace mongo;
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/Action.php');
/**
 * GetEventAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/07/07
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */

class EventAction extends \Cockatoo\Action {
  public function proc(){

    try{
      list($P,$D,$C,$p,$m,$q,$c) = \Cockatoo\parse_brl($this->BRL);
      // 
      $this->setNamespace('mongo');
      $session = $this->getSession();
      $user  = $session[\Cockatoo\AccountUtil::SESSION_LOGIN][\Cockatoo\AccountUtil::KEY_USER];
      $eventid   = $this->args['E'];

      if ( $m === \Cockatoo\Beak::M_GET ) {
        $edata = Lib::get_event($eventid);
        if ( $edata ) {
          return array( 'event' => $edata);
        }
        $origin = '*New';
        $contents = array(array('tag' => 'h2','attr'=>array(),'children' => array(array('tag'=>'text' , 'text' => 'New'))));
        return array( 'event' => array(
                        'eventid' => 'new',
                        'origin' => $origin,
                        'contents' => $contents,
                        'owner' => $user
                        ));
      }elseif( $m === \Cockatoo\Beak::M_GET_ARRAY ) {
        $events = Lib::get_events();
        return array('events' => $events);
      }elseif( $m === \Cockatoo\Beak::M_SET ) {
        if ( ! $user ) {
          throw new \Exception('You have to login before update mongo !!');
        }
        $op = $session[\Cockatoo\Def::SESSION_KEY_POST]['op'];
        if( $op === 'preview' ) {
          $edata = $session[\Cockatoo\Def::SESSION_KEY_POST];
          $origin   = $edata['origin'];
          $lines = explode("\n",$origin);
          $parser = new PageParser($edata['title'],$lines);
          $edata['contents'] =  $parser->parse();
          return array( 'event' => $edata );
        }elseif( $op === 'save' ) {
          $edata = $session[\Cockatoo\Def::SESSION_KEY_POST];
          unset($edata['submit']);
          $lines = explode("\n",$edata['origin']);
          $parser = new PageParser($edata['title'],$lines);
          $edata['contents'] =  $parser->parse();
          $prev_eventid = $eventid;
          $eventid = '' . $edata['date'] . '-' . $edata['time'] . '-' . uniqid();
          $edata['eventid'] = $eventid;
          Lib::save_event($eventid,$edata);
          Lib::remove_event($prev_eventid);
          $this->setMovedTemporary('/mongo/events/'.$eventid);
          return array();
        }
      }
    }catch ( \Exception $e ) {
      $s[\Cockatoo\Def::SESSION_KEY_ERROR] = $e->getMessage();
      $this->updateSession($s);
      $this->setMovedTemporary('/mongo/events');
       \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
      return null;
    }
  }
  public function postProc(){
  }
}

