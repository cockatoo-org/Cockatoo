<?php
/**
 * reqparser.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/01/23
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
namespace Cockatoo;
\ClassLoader::addClassPath($COCKATOO_ROOT.'wwwutils/plugin');


// DeviceSelector
/**
 * Request URL parser base
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
abstract class RequestParser {
  public static $instance;
  protected $header;
  protected $server;
  protected $get;
  protected $cookie;
  protected $reqpath;
  /**
   * Constructor
   *
   * @param Array $header $_HEADER
   * @param Array $server $_SERVER
   * @param Array $get    $_GET
   * @param Array $cookie $_COOKIE
   */
  public function __construct(&$header,&$server,&$get,&$cookie){
    $this->header = &$header;
    $this->server = &$server;
    $this->get    = &$get;
    $this->cookie = &$cookie;
    $this->reqpath= &$get['r'];
  }
  /**
   * Parse
   *
   *  Result :
   *    Array ( 
   *     [0] => String, // service
   *     [1] => String, // device
   *     [2] => String, // path
   *     [3] => String  // args
   *    )
   *
   * @return Array Parsed array
   */
  public function parse() {
    if ( Config::APP_OCCUPATION ) {
      $this->reqpath = Config::APP_OCCUPATION.$this->reqpath;
    }
    return $this->parseImpl();
  }
  /**
   * ParseImpl
   *
   *  Result :
   *    Array ( 
   *     [0] => String, // service
   *     [1] => String, // device
   *     [2] => String, // path
   *     [3] => String  // args
   *    )
   *
   * @return Array Parsed array
   */
  abstract public function parseImpl();
  /**
   * Parse
   *
   *  Result :
   *    Array ( 
   *     [0] => String, // service
   *     [1] => String, // device
   *     [2] => String, // path
   *     [3] => String  // args
   *    )
   *
   * @return Array Parsed array
   */
  public function parseStatic(){
    if ( preg_match('@^'.Def::PATH_STATIC_PREFIX.'/?([^/]+)/([^/]+)(?:/(.*))?$@', $this->reqpath , $matches ) !== 0 ) {
      array_shift($matches);
      return $matches;
    }
    throw new \Exception('Unexpect PATH:' . $this->reqpath);
  }
}

/**
 * Default Request URL parser
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class DefaultRequestParser extends RequestParser {
  public function parseImpl(){
    if ( preg_match('@^/?([^/]+)/([^/]+)(?:/(.*))?$@', $this->reqpath , $matches ) !== 0 ) {
      array_shift($matches);
      return $matches;
    }
    throw new \Exception('Unexpect PATH:' . $this->reqpath);
  }
}

/**
 * Device selector base
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
abstract class DeviceSelector {
  public static $instance;
  protected     $deviceTree = array();
  protected     $header;
  protected     $server;
  protected     $get;
  protected     $cookie;
  /**
   * Constructor
   *
   * @param Array $header $_HEADER
   * @param Array $server $_SERVER
   * @param Array $get    $_GET
   * @param Array $cookie $_COOKIE
   */
  public function __construct(&$header,&$server,&$get,&$cookie){
    $this->header = &$header;
    $this->server = &$server;
    $this->get    = &$get;
    $this->cookie = &$cookie;
  }
  /**
   * Select device
   *
   * @param String $device  device
   * @return String device
   */
  abstract public function select($device);
  /**
   * Fallback device
   *
   * @param String $device current device
   * @return String Returns next device or Null
   */
  public function fallback(&$device) {
    $fallbackDevice = $this->deviceTree[$device];
    if ( $fallbackDevice ) {
      return $fallbackDevice;
    }
    return null;
  }
}

/**
 * Default device selector base
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class DefaultDeviceSelector extends DeviceSelector {
  /**
   * Selector
   */
  public function select($device) {
    return $device;
  }
}

/**
 * Parse
 *
 *  Return:
 *   Array ( 
 *     [0] => String, // service
 *     [1] => String, // device
 *     [2] => String, // path
 *     [3] => String  // args
 *     [4] => mixed,  // RequestParser object
 *     [5] => mixed   // DeviceSelector object
 *   )
 *
 * @param Array $header $_HEADER
 * @param Array $server $_SERVER
 * @param Array $get    $_GET
 * @param Array $cookie $_COOKIE
 * @return Array Parsed array
 */
function parseRequest(&$header,&$server,&$get,&$cookie){
  if ( ! RequestParser::$instance ) {
    $clazz = Config::RequestParser;
    RequestParser::$instance = new $clazz($header,$server,$get,$cookie);
  }
  if ( ! DeviceSelector::$instance ) {
    $clazz = Config::DeviceSelector;
    DeviceSelector::$instance = new $clazz($header,$server,$get,$cookie);
  }

  // The mode of specifying the path as a query string (debug)
  if ( Config::Mode === Def::MODE_DEBUG and 
       (
         isset($get[Def::REQUEST_SERVICE]) or 
         isset($get[Def::REQUEST_DEVICE]) or 
         isset($get[Def::REQUEST_PATH]) or 
         isset($get[Def::REQUEST_ARGS])
         )
    ) {
    // Override 
    $service= isset($get[Def::REQUEST_SERVICE])?$get[Def::REQUEST_SERVICE]:$service;
    $device = isset($get[Def::REQUEST_DEVICE])?$get[Def::REQUEST_DEVICE]:$device;
    $path   = isset($get[Def::REQUEST_PATH])?$get[Def::REQUEST_PATH]:$path;
    $args   = isset($get[Def::REQUEST_ARGS])?$get[Def::REQUEST_ARGS]:$args;
    Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Request parsed (debug)  : ' . $service . ' , ' . $device . ' , ' . $path . ' , ' . $args);
    return array($service,$device,$path,$args,RequestParser::$instance,DeviceSelector::$instance);
  }

  list($service,$device,$path,$args) = RequestParser::$instance->parse();
  Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Request preparsed : ' . $service . ' , ' . $device . ' , ' . $path . ' , ' . $args);
  
  if ( strcmp($service,Def::RESERVED_SERVICE_CORE) === 0 ) {
  }elseif ( strcmp($device,Def::RESERVED_DEVICE_STATIC) === 0 ) {
  }else{
    $device = DeviceSelector::$instance->select($device);
  }

  Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Request parsed    : ' . $service . ' , ' . $device . ' , ' . $path . ' , ' . $args);
  return array($service,$device,$path,$args,RequestParser::$instance,DeviceSelector::$instance);
}

function parseStaticRequest(&$header,&$server,&$get,&$cookie){
  // The mode of specifying the path as a query string (debug)
  if ( Config::Mode === Def::MODE_DEBUG and 
       (
         isset($get[Def::REQUEST_SERVICE]) or 
         isset($get[Def::REQUEST_DEVICE]) or 
         isset($get[Def::REQUEST_PATH]) or 
         isset($get[Def::REQUEST_ARGS])
         )
    ) {
    // Override 
    $service= isset($get[Def::REQUEST_SERVICE])?$get[Def::REQUEST_SERVICE]:$service;
    $device = isset($get[Def::REQUEST_DEVICE])?$get[Def::REQUEST_DEVICE]:$device;
    $path   = isset($get[Def::REQUEST_PATH])?$get[Def::REQUEST_PATH]:$path;
    $args   = isset($get[Def::REQUEST_ARGS])?$get[Def::REQUEST_ARGS]:$args;
    Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Request parsed (debug)  : ' . $service . ' , ' . $device . ' , ' . $path . ' , ' . $args);
    return array($service,$device,$path,$args,RequestParser::$instance,DeviceSelector::$instance);
  }

  RequestParser::$instance = new DefaultRequestParser($header,$server,$get,$cookie);
  list($service,$device,$path,$args) = RequestParser::$instance->parseStatic();

  Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Request parsed (debug)  : ' . $service . ' , ' . $device . ' , ' . $path . ' , ' . $args);
  return array($service,$device,$path,$args);
}