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


// TemplateSelector
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
  public function __construct(&$header,&$server,&$get,&$cookie){
    $this->header = &$header;
    $this->server = &$server;
    $this->get    = &$get;
    $this->cookie = &$cookie;
    $this->reqpath= &$get[Def::REWRITE_TOKEN];
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
//    if ( Config::APP_OCCUPATION ) {
//      $this->reqpath = Config::APP_OCCUPATION.$this->reqpath;
//    }
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
  abstract public function parseImpl();
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
      $this->template       = $matches[2];
      $this->path         = $matches[3];
      return;
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
      $this->service      = $matches[1];
      $this->template       = $matches[2];
      $this->path         = $matches[3];
      $this->session_path = '/' . $matches[1];
      return;
    }
    throw new \Exception('Unexpect PATH:' . $this->reqpath);
  }
}

/**
 * Template selector base
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
abstract class TemplateSelector {
  public static $instance;
  protected     $templateTree = array();
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
   * Select template
   *
   * @param String $template  template
   * @return String template
   */
  abstract public function select($template);
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
 * Default template selector base
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class DefaultTemplateSelector extends TemplateSelector {
  /**
   * Selector
   */
  public function select($template) {
    return $template;
  }
}

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
  if ( ! RequestParser::$instance ) {
    $clazz = Config::RequestParser;
    RequestParser::$instance = new $clazz($header,$server,$get,$cookie);
  }
  if ( ! TemplateSelector::$instance ) {
    $clazz = Config::TemplateSelector;
    TemplateSelector::$instance = new $clazz($header,$server,$get,$cookie);
  }

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
    Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Request parsed (debug)  : ' . $service . ' , ' . $template . ' , ' . $path . ' , ' . $args);
    return array($service,$template,$path,$args);
  }

//  list($service,$template,$path,$args) = RequestParser::$instance->parse();
  RequestParser::$instance->parse();
  $service      = RequestParser::$instance->service;
  $template       = RequestParser::$instance->template;
  $path         = RequestParser::$instance->path;
  $args         = RequestParser::$instance->args;
  $session_path = RequestParser::$instance->session_path;
  Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Request preparsed : ' . $service . ' , ' . $template . ' , ' . $path . ' , ' . $args);
  
  if ( strcmp($service,Def::RESERVED_SERVICE_CORE) === 0 ) {
  }elseif ( strcmp($template,Def::RESERVED_TEMPLATE_STATIC) === 0 ) {
  }else{
    $template = TemplateSelector::$instance->select($template);
  }

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
    Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Request parsed (debug)  : ' . $service . ' , ' . $template . ' , ' . $path . ' , ' . $args);
    return array($service,$template,$path,$args,'/');
  }

  RequestParser::$instance = new DefaultRequestParser($header,$server,$get,$cookie);
  RequestParser::$instance->parseStatic();
  $service = RequestParser::$instance->service;
  $template  = RequestParser::$instance->template;
  $path    = RequestParser::$instance->path;
 
  Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Request parsed (debug)  : ' . $service . ' , ' . $template . ' , ' . $path . ' , ' . $args);
  return array($service,$template,$path);
}