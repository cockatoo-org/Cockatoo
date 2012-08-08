<?php
/**
 * config.php - Wiki config
 *  
 * @access public
 * @package cockatoo
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
class Config extends DefaultConfig {
  /**
   * Service MODE
   *   You should set Def::MODE_NORMAL in service environment.
   */
  const Mode           = Def::MODE_DEBUG;

  /**
   * The redirect path that when unhandled error occured.
   */
  const ErrorRedirect  = '/wiki/view';

  /**
   * Request analizer
   */
  const RequestParser     = 'Cockatoo\SampleRequestParser';
  const TemplateSelector    = 'Cockatoo\SampleTemplateSelector'; 

  /**
   * Timeout
   */
  const ActionTimeout  = 5000;  // This means 5 sec.

  public static function init () {
    //--------------------
    // LOG settings
    //--------------------
    // self::$Loglv   = Def::LOGLV_TRACE;
    self::$Loglv   = Def::LOGLV_INFO;
    self::$LogDataDump = true;
    /**
     * Filename , STDOUT or STDIN
     */
    self::$LogFile = self::COCKATOO_ROOT . '/logs/cockatoo.log';

    //--------------------
    // Beak cache settings
    //--------------------
    // self::$UseMemcache         = array('127.0.0.1:11211');;

    /**
     * BEAK Driver switch
     */
    // Local mode
    self::$BEAKS = array (
      Def::BP_CMS      => 'Cockatoo\BeakFile'   , // cms://...
      Def::BP_SESSION  => 'Cockatoo\BeakFile'   , // session://...
      Def::BP_LAYOUT   => 'Cockatoo\BeakFile'   , // layout://...
      Def::BP_COMPONENT=> 'Cockatoo\BeakFile'   , // component://...
      Def::BP_STATIC   => 'Cockatoo\BeakFile'   , // static://...
      Def::BP_STORAGE  => 'Cockatoo\BeakFile'   , // storage://...
      Def::BP_ACTION   => 'Cockatoo\BeakAction' , // action://...
      null
      );
    self::$EXT_BEAKS = array (
      'action://core-action/'   => 'Cockatoo\BeakAction' ,
      null
      );

    /**
     * Static locations.
     *
     *  @@@ Todo:
     *    $BeakLocation should be merged with $BEAK but have to consider zookeeper ...
     */
    self::$BeakLocation = array (
      'cms://services-cms/'           => array(''),
      'layout://core-layout/'         => array(''),
      'component://core-component/'   => array(''),
      'static://core-static/'         => array(''),
      );
      //--------------------
      // Zookeeper ( dynamic locations )
      //--------------------
//    self::$UseZookeeper        = array('127.0.0.1:2181');
//    self::$ZookeeperCacheFile  = self::COCKATOO_ROOT.'daemon/etc/zoo.json';
      //
  }
}
