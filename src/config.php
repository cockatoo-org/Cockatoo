<?php
/**
 * config.php - Sample config
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
   * The default redirect path that when unhandled error occured.
   */
  const ErrorRedirect  = '/wiki/view';

  /**
   * Request analizer
   */
  static $DefaultRequestParser;
  static $RequestParser;

  /**
   * CMS acl
   */
  const CMSAuth       = 'Cockatoo\DefaultCmsAuth';
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
//    self::$BEAKS = array (
//      Def::BP_CMS      => 'Cockatoo\BeakFile'   ,
//      Def::BP_SESSION  => 'Cockatoo\BeakMemcached'   ,
//      Def::BP_LAYOUT   => 'Cockatoo\BeakMongo'   ,
//      Def::BP_COMPONENT=> 'Cockatoo\BeakMongo'   ,
//      Def::BP_STATIC   => 'Cockatoo\BeakMongo'   ,
//      Def::BP_STORAGE  => 'Cockatoo\BeakMongo'   ,
//      Def::BP_ACTION   => 'Cockatoo\BeakProxy' ,
////      Def::BP_ACTION   => 'Cockatoo\BeakAction' , // action://...
//      null
//      );
    self::$EXT_BEAKS = array (
      'action://core-action/'   => 'Cockatoo\BeakAction' ,
      null
      );

    /**
     * Static locations.
     */
    self::$BeakLocation = array (
      'cms://services-cms/'           => array(''),
      'layout://core-layout/'         => array(''),
      'component://core-component/'   => array(''),
      'static://core-static/'         => array(''),
      );
//    self::$BeakLocation = array (
//      'cms://services-cms/'           => array(''),
//      'layout://core-layout/'         => array('127.0.0.1:27017' => array('replicaSet'=>false)),
//      'component://core-component/'   => array('127.0.0.1:27017' => array('replicaSet'=>false)),
//      'static://core-static/'         => array('127.0.0.1:27017' => array('replicaSet'=>false)),
//      'storage://core-storage/'        => array('127.0.0.1:27017' => array('replicaSet'=>false)),
//      'session://core-session/'       => array('127.0.0.1:11211'),
//      'layout://wiki-layout/'         => array('127.0.0.1:27017' => array('replicaSet'=>false)),
//      'component://wiki-component/'   => array('127.0.0.1:27017' => array('replicaSet'=>false)),
//      'static://wiki-static/'         => array('127.0.0.1:27017' => array('replicaSet'=>false)),
//      'storage://wiki-storage/'       => array('127.0.0.1:27017' => array('replicaSet'=>false)),
//      'session://wiki-session/'       => array('127.0.0.1:11211'),
//      'layout://mongo-layout/'         => array('127.0.0.1:27017' => array('replicaSet'=>false)),
//      'component://mongo-component/'   => array('127.0.0.1:27017' => array('replicaSet'=>false)),
//      'static://mongo-static/'         => array('127.0.0.1:27017' => array('replicaSet'=>false)),
//      'storage://mongo-storage/'       => array('127.0.0.1:27017' => array('replicaSet'=>false)),
//      'session://mongo-session/'       => array('127.0.0.1:11211')
//      );
    //--------------------
    // Zookeeper ( dynamic locations )
    //--------------------
    self::$UseZookeeper        = array('127.0.0.1:2181');
    self::$ZookeeperCacheFile  = self::COCKATOO_ROOT.'daemon/etc/zoo.json';

    self::$RequestParser = array (
      '/core' => 'Cockatoo\CoreRequestParser',
      '/wiki' => 'wiki\WikiRequestParser',
      '/mongo' => 'mongo\MongoRequestParser'
      );
    self::$DefaultRequestParser = 'Cockatoo\DefaultRequestParser';
  }
}
