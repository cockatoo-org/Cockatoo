<?php
/**
 * def.php - Definition config
 *  
 * @access public
 * @package cockatoo
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
if (!function_exists('getallheaders')){
  function getallheaders()
  {
    $headers = '';
    foreach ($_SERVER as $name => $value)
    {
      if (substr($name, 0, 5) == 'HTTP_')
      {
        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
      }
    }
    return $headers;
  }
} 

class Def {
  const  PATH_STATIC_PREFIX       = '/_s_'; // Associate with rewrite

  const  REWRITE_TOKEN            = '_R'; // httpd-cockatoo.conf

  const  REQUEST_SERVICE          = '_S';
  const  REQUEST_TEMPLATE           = '_D';
  const  REQUEST_LAYOUT           = '_L';
  const  REQUEST_PATH             = '_P';
  const  REQUEST_ARGS             = '_A';

  const  RESERVED_SERVICE_CORE    = 'core';
  const  RESERVED_TEMPLATE_DEFAULT  = 'default';
  const  RESERVED_TEMPLATE_LAYOUT  = 'layout';
  const  RESERVED_TEMPLATE_STATIC   = 'static';

  const  K_LAYOUT_TYPE            = 'type';
  const  K_LAYOUT_PRE_ACTION      = 'pre_action';
  const  K_LAYOUT_POST_ACTION     = 'post_action';
  // const  K_LAYOUT_CTYPE           = 'ctype';
  const  K_LAYOUT_SESSION_EXP     = 'session_exp';
  const  K_LAYOUT_EXPIRES         = 'expires';
  const  K_LAYOUT_CHILDREN        = 'children';
  const  K_LAYOUT_EXTRA           = 'extra';
  const  K_LAYOUT_LAYOUT          = 'layout';
  const  K_LAYOUT_COMPONENT       = 'component';
  const  K_LAYOUT_CLASS           = 'class';
  const  K_LAYOUT_HEIGHT          = 'height';
  const  K_LAYOUT_WIDTH           = 'width';
  const  K_LAYOUT_MIN_HEIGHT      = 'min_height';
  const  K_LAYOUT_MIN_WIDTH       = 'min_width';
  const  K_LAYOUT_VPOS            = 'vpos';
  const  K_LAYOUT_SWIDTH          = 'swidth';
  const  K_LAYOUT_EREDIRECT       = 'eredirect';
  const  K_LAYOUT_REDIRECT        = 'redirect';
  const  K_LAYOUT_PHEADER         = 'pheader';
  const  K_LAYOUT_HEADER          = 'header';
  const  K_LAYOUT_BOTTOM          = 'bottom';

  const  K_LAYOUT_CTYPE_HTML      = 'html';
  const  K_LAYOUT_CTYPE_PLAIN     = 'plain';
  const  K_LAYOUT_CTYPE_JSON      = 'json';
  const  K_LAYOUT_CTYPE_BIN       = 'binary';


  const  K_COMPONENT_TYPE         = 'type';
  const  K_COMPONENT_SUBJECT      = 'subject';
  const  K_COMPONENT_DESCRIPTION  = 'description';
  const  K_COMPONENT_ID           = 'id';
  const  K_COMPONENT_CLASS        = 'class';
  const  K_COMPONENT_BODY         = 'body';
  const  K_COMPONENT_JS           = 'js';
  const  K_COMPONENT_CSS          = 'css';
  const  K_COMPONENT_ACTION       = 'action';
  const  K_COMPONENT_HEADER       = 'header';
  const  K_COMPONENT_BOTTOM       = 'bottom';

  const  K_STATIC_TYPE            = 'type';
  const  K_STATIC_DATA            = 'data';
  const  K_STATIC_BIN             = '*bin';
  const  K_STATIC_DESCRIPTION     = 'desc';
  const  K_STATIC_EXPIRE          = 'exp';
  const  K_STATIC_ETAG            = 'etag';
   
  const  BP_SESSION               = 'session';
  const  BP_LAYOUT                = 'layout';
  const  BP_COMPONENT             = 'component';
  const  BP_STATIC                = 'static';
  const  BP_STORAGE               = 'storage';
  const  BP_ACTION                = 'action';
  const  BP_SEARCH                = 'search';
  const  BP_CMS                   = 'cms';

  const  CMS_SERVICES             = 'services';

  const  BD_SEPARATOR             = '-';

  const  RenderingModeNORMAL      = 0x00;
  const  RenderingModeDEBUG1      = 0x01;
  const  RenderingModeDEBUG2      = 0x02;
  const  RenderingModeDEBUG3      = 0x04;
  const  RenderingModeDEBUG4      = 0x08;
  const  RenderingModeCMS         = 0x10;
  const  RenderingModeCMSTEMPLATE = 0x20;

  const  PAGELAYOUT_BRL           = 'component://core-component/default/pagelayout';
  const  LAYOUTWIDGET             = 'LayoutWidget';
  const  PLAINWIDGET              = 'PlainWidget';
  const  JSONWIDGET               = 'JsonWidget';
  const  BINARYWIDGET             = 'BinaryWidget';
  const  IPC_GW_SEGMENT           = 'gateway';

  const  ActionSuccess            = 0;
  const  ActionError              = 1;

  const  AC_SESSION_ID            = '_SID';
  const  AC_SERVICE               = '_S';
#   const  AC_ARGS                  = '_A';
  const  AC_ENV                   = '_E';
#  const AC_BRL                   = '_brl';

  const  SESSION_KEY_REQ          = '_r';
  const  SESSION_KEY_POST         = '_p';
  const  SESSION_KEY_GET          = '_g';
  const  SESSION_KEY_SERVER       = '_s';
  const  SESSION_KEY_TEMPLATE       = '_d';
  const  SESSION_KEY_COOKIE       = '_c';
  const  SESSION_KEY_EXP          = '_e';
  const  SESSION_KEY_FILES        = '_f';
  const  SESSION_KEY_ERROR        = '_err';

  const  F_TYPE                   = 't';
  const  F_ERROR                  = 'e';
  const  F_NAME                   = 'n';
  const  F_CONTENT                = 'c';
  const  F_SIZE                   = 's';

  const  CS_CORE                  = 'C';
  const  CS_SESSION               = 'S';
  const  CS_ACTION                = 'A';

  const  CS_CORE_BASE             = '_base';
  const  CS_CORE_FULLURL          = '_url';
  const  CS_CORE_FULLEURL         = '_eurl';


  const  MODE_NORMAL              = 0;
  const  MODE_DEBUG               = 1;
  // Log mask for specifing at config.php 
  const  LOGLV_DEBUG              = 0x0FFFFFFF;
  const  LOGLV_TRACE              = 0x00FFFFFF;
  const  LOGLV_PERFORMANCE        = 0x000FFFFF;
  const  LOGLV_INFO               = 0x0000FFFF;
  const  LOGLV_WARN               = 0x00000FFF;
  const  LOGLV_ERROR              = 0x000000FF;
  const  LOGLV_FATAL              = 0x0000000F;
  // Log lv
  const  LOGLV_DEBUG0             = 0x01000000;
  const  LOGLV_TRACE0             = 0x00100000;
  const  LOGLV_PERFORMANCE3       = 0x00080000;
  const  LOGLV_PERFORMANCE2       = 0x00040000;
  const  LOGLV_PERFORMANCE1       = 0x00020000;
  const  LOGLV_PERFORMANCE0       = 0x00010000;
  const  LOGLV_INFO0              = 0x00001000;
  const  LOGLV_WARN0              = 0x00000100;
  const  LOGLV_ERROR0             = 0x00000010;
  const  LOGLV_FATAL0             = 0x00000001;

  const SESSION_NO_SESSION        = -1;
  const SESSION_TMP_SESSION       = 0;
  const SESSION_DEFAULT           = 0x7FFFFFFF;

}

abstract class DefaultConfig {
  /**
   * Cockatoo paths
   */
  const COCKATOO_ROOT='/usr/local/cockatoo/';
  /**
   * Specify the application path if you want to run only one application on this domain.
   *   ex> '/wiki/default'
   */
//  const APP_OCCUPATION = false;
  /**
   * Service MODE
   *   You should set Def::MODE_NORMAL in service environment.
   */
  const Mode           = Def::MODE_NORMAL;
  /**
   * The default redirect path that when unhandled error occured.
   */
  const ErrorRedirect  = '/default/error';
  /**
   * Path to files
   */
  const CommonCSS      = 'css/cockatoo.css';
  const CommonJs       = 'js/cockatoo.js';
  /**
   * Session cookie name
   */
  const SESSION_COOKIE = 'ALB_SESID';
  /**
   * Request analizer
   */
  const RequestParser     = 'Cockatoo\DefaultRequestParser';
  const TemplateSelector    = 'Cockatoo\DefaultTemplateSelector'; 
  /**
   * Beak serializer
   */
  const BeakPacker     = 'Cockatoo\DefaultBeakPacker';
  /**
   * Gateway socket path
   */
  const IPCDirectory   = '/tmp';
  /**
   * Timeout
   */
  const ActionTimeout  = 1000;  // This means 1 sec.
  /**
   * CMS acl
   */
  const CMSAuth       = 'Cockatoo\SkipCmsAuth';
  /**
   * PID
   */
  static public $PID;
  /**
   * BEAK drivers
   */
  static public $BEAKS;
  static public $EXT_BEAKS;
  /**
   * BEAK scheme-list
   */
  static public $SYS_BEAKS;
  /**
   * Memcached switch for beak cache
   */
  static public $UseMemcache          = false;
  static public $EXPIRE_BALANCE       = 2.718;
  static public $EXPIRE_BALANCE_BOOST = 60; // sec
  /**
   * Dynamic locator switch from zookeeper
   */
  static public $UseZookeeper       = false;
  static public $ZookeeperCacheFile = 'daemon/etc/zoo.json';
  /**
   * Static locator
   */
  static public $BeakLocation;
  /**
   * Log
   */
  static public $Loglv       = Def::LOGLV_INFO;
  static public $LogDataDump = false;
  static public $LogFile     = 'logs/cockatoo.log';
  /**
   * Measure of the zmq socket leak
   */
  static public $Error2Die;

  static private $init = true;

  public static function __init__ () {
    if ( self::$init ) {
      self::$init = false;
      $conf = get_called_class();
      //--------------------
      // Pre init
      //--------------------
      self::$PID = posix_getpid();
      // Domain suffix
      self::$SYS_BEAKS = array (
        Def::BP_SESSION  => 'session'  ,
        Def::BP_LAYOUT   => 'layout'   ,
        Def::BP_COMPONENT=> 'component',
        Def::BP_STATIC   => 'static'   ,
        Def::BP_STORAGE  => 'storage'   ,
        Def::BP_ACTION   => 'action'   ,
        Def::BP_SEARCH   => 'search'   ,
        Def::BP_CMS      => 'cms',
        null
        );
      // @@@ Todo: 
      //   It sounds like there are some bugs about connection pool in some httpd_modules.
      //   Then we must kill the httpd process FORCIBLY...
      self::$Error2Die = 10;

      /**
       * BEAK Driver switch
       */
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
        null
        );
      /**
       * Static locations.
       *
       */
      self::$BeakLocation = array (
        'cms://services-cms/'           => array(''),
        'layout://core-layout/'         => array(''),
        'component://core-component/'   => array(''),
        'static://core-static/'         => array('')
        );
      //--------------------
      // Call init
      //--------------------
      $conf::init();
      //--------------------
      // Post init
      //--------------------
      if ( Config::Mode == Def::MODE_DEBUG ) {
        ini_set('display_errors','On');
      }
    }
  }
  //abstract public static function init();
}
# PHP settings
ini_set('error_reporting',2039); # E_ALL & ^E_NOTICE
ini_set('log_errors','On');
ini_set('display_errors','Off');
$COCKATOO_CONF = getenv('COCKATOO_CONF');
if ( ! $COCKATOO_CONF ) {
  $COCKATOO_CONF = dirname(__FILE__) . '/config.php';
}
require_once($COCKATOO_CONF);
Config::__init__();
putenv('COCKATOO_ROOT='.Config::COCKATOO_ROOT);
ini_set('error_log',Config::COCKATOO_ROOT . '/logs/php_error.log');

chdir(Config::COCKATOO_ROOT);

require_once(Config::COCKATOO_ROOT.'utils/ClassLoader.php');
\ClassLoader::addClassPath(Config::COCKATOO_ROOT.'libs');

require_once(Config::COCKATOO_ROOT.'utils/log.php');

