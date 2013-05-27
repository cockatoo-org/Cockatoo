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
\ClassLoader::addClassPath(Config::COCKATOO_ROOT.'wwwutils/plugin');


/**
 * Request URL parser base
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class RequestParser {
  static public $instance;
  protected $header;
  protected $server;
  protected $get;
  protected $cookie;
  protected $reqpath;

  public    $base;
  public    $service;
  public    $template;
  public    $path;
  public    $args         = array();
  public    $session_path = '/';
  /**
   * Constructor
   *
   * @param Array $header $_HEADER
   * @param Array $server $_SERVER
   * @param Array $get    $_GET
   * @param Array $cookie $_COOKIE
   */
  public function __construct(&$base,&$header,&$server,&$get,&$cookie,&$reqpath){
    $this->base   = &$base;
    $this->header = &$header;
    $this->server = &$server;
    $this->get    = &$get;
    $this->cookie = &$cookie;
    $this->reqpath= &$reqpath;
  }
  /**
   * Parse
   *
   *  Result :
   *    Set members
   *     - $service
   *     - $template
   *     - $path        
   *     - $args        
   *     - $session_path 
   *
   * @return Array Parsed array
   */
  public function parse() {
    $this->parseImpl();
  }
  /**
   * ParseImpl
   *
   *  Result :
   *    Set members
   *     - $service
   *     - $template
   *     - $path        
   *     - $args        
   *     - $session_path 
   *
   * @return Array Parsed array
   */
  public function parseImpl() {
    throw new \Exception('Unexpect PATH:' . $this->reqpath);
  }
  /**
   * Parse
   *
   *  Result :
   *    Set members
   *     - $service
   *     - $template
   *     - $path        
   *    )
   *
   * @return Array Parsed array
   */
  public function parseStatic(){
    if ( preg_match('@^'.Def::PATH_STATIC_PREFIX.'/?([^/]+)/([^/]+)(?:/(.*))?$@', $this->reqpath , $matches ) !== 0 ) {
      $this->service      = $matches[1];
      $this->template     = $matches[2];
      $this->path         = $matches[3];
      return;
    }
    throw new \Exception('Unexpect PATH:' . $this->reqpath);
  }


  protected     $templateTree = array();
  /**
   * Select template
   *
   * @param String $template  template
   * @return String template
   */
  public function select($template) {
    return $template;
  }
  /**
   * Fallback template
   *
   * @param String $template current template
   * @return String Returns next template or Null
   */
  public function fallback(&$template) {
    $fallbackTemplate = $this->templateTree[$template];
    if ( $fallbackTemplate ) {
      return $fallbackTemplate;
    }
    return null;
  }
}

/**
 * Default Request URL parser
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
//class DefaultRequestParser extends RequestParser {
//  public function parseImpl(){
//    if ( preg_match('@^/?([^/]+)/([^/]+)(?:/(.*))?$@', $this->reqpath , $matches ) !== 0 ) {
//      $this->service      = $matches[1];
//      $this->template     = $matches[2];
//      $this->path         = $matches[3];
//      $this->session_path = '/' . $matches[1];
//      return;
//    }
//    throw new \Exception('Unexpect PATH:' . $this->reqpath);
//  }
//}



/**
 * Parse
 *
 *  Return:
 *   Array ( 
 *     [0] => String, // service
 *     [1] => String, // template
 *     [2] => String, // path
 *     [3] => String  // args
 *     [4] => String  // session_path
 *   )
 *
 * @param Array $header $_HEADER
 * @param Array $server $_SERVER
 * @param Array $get    $_GET
 * @param Array $cookie $_COOKIE
 * @return Array Parsed array
 */
function parseRequest(&$header,&$server,&$get,&$cookie){
  $reqpath = $get[Def::REWRITE_TOKEN];
  $clazz = Config::$DefaultRequestParser;
  $base  = '/';
  foreach ( Config::$RequestParser as $k => $v)
  if ( preg_match('@^('.$k.')(/.*)?$@', $reqpath , $matches ) !== 0 ){
    $clazz = $v;
    $base  = $k;
    $reqpath = $matches[2];
  }
  RequestParser::$instance = new $clazz($base,$header,$server,$get,$cookie,$reqpath);
 

  // The mode of specifying the path as a query string (debug)
  if ( Config::Mode === Def::MODE_DEBUG and 
       (
         isset($get[Def::REQUEST_SERVICE]) or 
         isset($get[Def::REQUEST_TEMPLATE]) or 
         isset($get[Def::REQUEST_PATH]) or 
         isset($get[Def::REQUEST_ARGS])
         )
    ) {
    // Override 
    $service= isset($get[Def::REQUEST_SERVICE])?$get[Def::REQUEST_SERVICE]:$service;
    $template = isset($get[Def::REQUEST_TEMPLATE])?$get[Def::REQUEST_TEMPLATE]:$template;
    $path   = isset($get[Def::REQUEST_PATH])?$get[Def::REQUEST_PATH]:$path;
    $args   = isset($get[Def::REQUEST_ARGS])?$get[Def::REQUEST_ARGS]:$args;

    // Url encode (except for '/')
    $path = path_urlencode($path);
    Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Request parsed (debug)  : ' . $service . ' , ' . $template . ' , ' . $path . ' , ' . $args);
    return array($service,$template,$path,$args);
  }

//  list($service,$template,$path,$args) = RequestParser::$instance->parse();
  RequestParser::$instance->parse();
  $service      = RequestParser::$instance->service;
  $template     = RequestParser::$instance->template;
  $path         = RequestParser::$instance->path;
  $args         = RequestParser::$instance->args;
  $session_path = RequestParser::$instance->session_path;
  
  if ( strcmp($service,Def::RESERVED_SERVICE_CORE) === 0 ) {
  }elseif ( strcmp($template,Def::RESERVED_TEMPLATE_STATIC) === 0 ) {
  }else{
    $template = RequestParser::$instance->select($template);
  }

  // Url encode (except for '/')
  $path = path_urlencode($path);
  Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Request parsed    : ' . $service . ' , ' . $template . ' , ' . $path . ' , ' . $args);
  return array($service,$template,$path,$args,$session_path);
}

function parseStaticRequest(&$header,&$server,&$get,&$cookie){
  // The mode of specifying the path as a query string (debug)
  if ( Config::Mode === Def::MODE_DEBUG and 
       (
         isset($get[Def::REQUEST_SERVICE]) or 
         isset($get[Def::REQUEST_TEMPLATE]) or 
         isset($get[Def::REQUEST_PATH]) or 
         isset($get[Def::REQUEST_ARGS])
         )
    ) {
    // Override 
    $service= isset($get[Def::REQUEST_SERVICE])?$get[Def::REQUEST_SERVICE]:$service;
    $template = isset($get[Def::REQUEST_TEMPLATE])?$get[Def::REQUEST_TEMPLATE]:$template;
    $path   = isset($get[Def::REQUEST_PATH])?$get[Def::REQUEST_PATH]:$path;
    $args   = isset($get[Def::REQUEST_ARGS])?$get[Def::REQUEST_ARGS]:$args;
    // Url encode (except for '/')
    $path = path_urlencode($path);
    Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Request parsed (debug)  : ' . $service . ' , ' . $template . ' , ' . $path . ' , ' . $args);
    return array($service,$template,$path,$args,'/');
  }

  $reqpath = $get[Def::REWRITE_TOKEN];
  $base = '/';
  RequestParser::$instance = new RequestParser($base,$header,$server,$get,$cookie,$reqpath);
  RequestParser::$instance->parseStatic();
  $service = RequestParser::$instance->service;
  $template= RequestParser::$instance->template;
  $path    = RequestParser::$instance->path;
 
  // Url encode (except for '/')
  $path = path_urlencode($path);
  Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Request parsed (debug)  : ' . $service . ' , ' . $template . ' , ' . $path . ' , ' . $args);
  return array($service,$template,$path);
}