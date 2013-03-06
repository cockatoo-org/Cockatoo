<?php
namespace mongo;
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/Action.php');
/**
 * EventAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/07/07
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */

class EventAction extends \Cockatoo\Action {
  private static $EREDIRECT = '/mongo/events';

  public function proc(){
    try{
      // 
      $this->setNamespace('mongo');
      $session = $this->getSession();
      $user  = Lib::user($session);
      $eventid   = $this->args['E'];

      if ( $this->method === \Cockatoo\Beak::M_GET ) {
        $data = Lib::get_event($session,$eventid);
        if ( $data ) {
          return array( 'event' => $data);
        }
        $this->setMovedTemporary(self::$EREDIRECT);
        return null;
      }elseif( $this->method === \Cockatoo\Beak::M_GET_ARRAY ) {
        $datas = Lib::get_events($session);
        return array('events' => $datas);
      }elseif( $this->method === \Cockatoo\Beak::M_SET ) {
        if ( ! Lib::isWritable($session) ) {
          throw new \Exception('You do not have write permission.');
        }
        $op = $session[\Cockatoo\Def::SESSION_KEY_POST]['op'];
        if ( ! $op ) {
          $data = Lib::get_event($session,$eventid);
          if ( $data ) {
            return array( 'event' => $data);
          }
          $origin = '*New';
          $contents = array(array('tag' => 'h2','attr'=>array(),'children' => array(array('tag'=>'text' , 'text' => 'New'))));
          return array( 'event' => array(
                          'eventid' => 'new',
                          'origin' => $origin,
                          'contents' => $contents,
                          'owner' => $user
                          ));
        }
        if( $op === 'preview' ) {
          $data = $session[\Cockatoo\Def::SESSION_KEY_POST];
          $origin   = $data['origin'];
          $lines = preg_split("@\r?\n@",$origin);
          $parser = new PageParser($data['title'],$lines);
          $data['contents'] =  $parser->parse();
          return array( 'event' => $data );
        }elseif( $op === 'save' ) {
          $data = $session[\Cockatoo\Def::SESSION_KEY_POST];
          unset($data['submit']);
          $lines = preg_split("@\r?\n@",$data['origin']);
          $parser = new PageParser($data['title'],$lines);
          $data['contents'] =  $parser->parse();
          $prev_eventid = $eventid;
          $eventid = '' . $data['date'] . '-' . $data['time'] . '-' . uniqid();
          $data['eventid'] = $eventid;
          Lib::save_event($eventid,$data);
          Lib::remove_event($prev_eventid);
          $this->setMovedTemporary('/mongo/events/'.$eventid);
          return array();
        }
      }
    }catch ( \Exception $e ) {
      $s[\Cockatoo\Def::SESSION_KEY_ERROR] = $e->getMessage();
      $this->updateSession($s);
      $this->setMovedTemporary(self::$EREDIRECT);
       \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
      return null;
    }
  }
  public function postProc(){
  }
}

