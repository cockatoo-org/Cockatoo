<?php
/**
 * BeakSolr.php - Beak driver : Solr request
 *  
 * @access public
 * @package cockatoo-beaks
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
require_once ($COCKATOO_ROOT.'utils/beak.php');

/**
 * Solr request
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class BeakSolr extends Beak {
  const COMMENT_TIMEOUT_MS   = 'COTO';
  const CURL_CONN_TIMEOUT_MS = 5000;
  const CURL_TIMEOUT_MS      = 5000;
  const CURL_TIMEOUT_MS_MIN  = 1000;
  /**
   * Result object
   */
  protected $ret = null;
  /**
   * Solr domain
   */
  protected $server = null;
  /**
   * Curl CH
   */
  protected $ch = null;

  protected $timeout_ms     = self::CURL_TIMEOUT_MS;
  

  /**
   * Constructor
   * 
   * @see Action.php
   */
  public function __construct(&$brl,&$scheme,&$domain,&$collection,&$path,&$method,&$queries,&$comments,&$arg,&$hide) {
    parent::__construct($brl,$scheme,$domain,&$collection,$path,$method,$queries,$comments,$arg,$hide);
    $this->beakLocation = BeakLocationGetter::singleton();
    $brl = Def::BP_SEARCH . '://' . $domain . '/';
    $locations = $this->beakLocation->getLocation(array($brl),'');
    $this->server = 'http://';
    if ( ! $locations[$brl] ) {
      Log::debug(__CLASS__ . '::' . __FUNCTION__ . ' : No location-info : ' . $this->brl);
      return ;
    }
    $this->server .= $locations[$brl][array_rand($locations[$brl])];
    $this->ch = curl_init();
    $http_proxy = getenv('http_proxy');    
    if ($http_proxy){
      curl_setopt($this->ch, CURLOPT_PROXY, $http_proxy);
    }

    foreach ( $this->comments as $comment ) {
      if ( preg_match('@^'.self::COMMENT_TIMEOUT_MS.'=(\d+)$@',$comment,$matches) !== 0 ){
        $this->timeout_ms = $matches[1];
        if ( $this->timeout_ms < self::CURL_TIMEOUT_MS_MIN ) {
          $this->timeout_ms = self::CURL_TIMEOUT_MS_MIN;
        }
        break;
      }
    }
  }

  /**
   * Craete collection and Create index
   *   
   * @see Action.php
   */
  public function createColQuery(){
  }

  /**
   * Get all collections name
   *
   * @see Action.php
   */
  public function listColQuery() {
  }

  /**
   * Get all keys containing the collection.
   *
   * @see Action.php
   */
  public function listKeyQuery() {
  }

  /**
   * T.B.D @@@
   * 
   * @see Action.php
   */
  public function getaQuery() {
  }

  /**
   * Get document data
   *
   * @see Action.php
   */
  public function getQuery() {
    $this->server .= '/solr/select?wt=json';
    //
    if ( is_array($this->queries) ) {
      foreach ( $this->queries as $k => $v ) {
        $this->server .= '&' . urlencode($k) . '=' . urlencode($v);
      }
    }
    //
    if ( is_array($this->arg) ) {
      foreach ( $this->arg as $k => $v ) {
        $this->server .= '&' . urlencode($k) . '=' . urlencode($v);
      }
    }
    Log::debug(__CLASS__ . '::' . __FUNCTION__ . ' : ' . $this->brl . ' , ' . $this->server);

    curl_setopt($this->ch, \CURLOPT_URL, $this->server);
//     curl_setopt($this->ch, \CURLOPT_HEADER, 0);
    curl_setopt($this->ch, \CURLOPT_RETURNTRANSFER,1); 
    curl_setopt($this->ch, \CURLOPT_CONNECTTIMEOUT_MS,self::CURL_CONN_TIMEOUT_MS); 
    curl_setopt($this->ch, \CURLOPT_TIMEOUT_MS,$this->timeout_ms); 
  }
  /**
   * T.B.D @@@
   *
   * @see Action.php
   */
  public function delQuery() {
  }
  /**
   * T.B.D @@@
   *
   * @see Action.php
   */
  public function delaQuery() {
  }
  /**
   * Set multi document datas
   * 
   * @see Action.php
   */
  public function setaQuery(){
  }
  /**
   * Set document data
   *
   * @see Action.php
   */
  public function setQuery() {
  }
  /**
   * Move collection
   */
  public function mvColQuery() {
  }


  /**
   * Get operation results
   * 
   * @see Action.php
   */
  public function result() {
    $response = curl_exec($this->ch);
//     $hsize = curl_getinfo($this->ch,\CURLINFO_HEADER_SIZE);
    curl_close($this->ch);
    # $header = substr($response, 0, $hsize);
//     $body = substr( $response, $hsize );
    $this->ret = json_decode($response,true);
    return $this->ret;
  }
}

// search://cockatoo-search/solr/?get&fq=server_key:isnews&q=article_title:keyword
// -> http://127.0.0.1:8090/solr/select?wt=json&fq=service_key:isnews&q=article_title:keyword

// putenv('http_proxy=');
// $queries = '&queries=foobar';//array ( 'queries' => 'foobar');
// $comments = array();
// $args = null;
// $brl = 'search://cockatoo-search/solr/?get&queries=foobar';
// $scheme = 'search';
// $domain = 'cockatoo-search';
// $collect = 'solr';
// $path = '/';
// $method = 'get';
// $beak = new BeakSolr($brl,$scheme,$domain,$collect,$path,$method,$queries,$comments,$args);
// $beak->getQuery();
// $ret = $beak->result();
// var_dump($ret);

/*
  function curl_request_async($url, $params, $type='POST')
  {
      foreach ($params as $key => &$val) {
        if (is_array($val)) $val = implode(',', $val);
        $post_params[] = $key.'='.urlencode($val);
      }
      $post_string = implode('&', $post_params);

      $parts=parse_url($url);

      $fp = fsockopen($parts['host'],
          isset($parts['port'])?$parts['port']:80,
          $errno, $errstr, 30);

      // Data goes in the path for a GET request
      if('GET' == $type) $parts['path'] .= '?'.$post_string;

      $out = "$type ".$parts['path']." HTTP/1.1\r\n";
      $out.= "Host: ".$parts['host']."\r\n";
      $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
      $out.= "Content-Length: ".strlen($post_string)."\r\n";
      $out.= "Connection: Close\r\n\r\n";
      // Data goes in the request body for a POST request
      if ('POST' == $type && isset($post_string)) $out.= $post_string;

      fwrite($fp, $out);
      fclose($fp);
  }
*/