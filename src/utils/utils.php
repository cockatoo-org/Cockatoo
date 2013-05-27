<?php
/**
 * utils.php - Utilities
 *  
 * @access public
 * @package cockatoo-utils
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;

/**
 * Get time as micro second
 *
 * @return Array Returns micro second
 */
function utime() {
  return explode(' ',microtime());
}
/**
 * Get difference between the micro second objects
 *
 * @param Array $t1  Micro second object
 * @param Array $t2  Micro second object
 * @return int Returns difference as micro second
 */
function diffutime($t1,$t2) {
  return (($t1[0]-$t2[0]) + ($t1[1]-$t2[1]))*1000000;
}

function path_urlencode ( $str ) {
  return implode("/", array_map("rawurlencode", explode("/", $str)));
}

function http($url,$method = 'GET', $postfields = NULL) {
    $http_info = array();
    $ci = curl_init();
    /* Curl settings */
//    curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ci, CURLOPT_TIMEOUT, 30);
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
    curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);
//    curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
    curl_setopt($ci, CURLOPT_HEADER, FALSE);

    if ( $method === 'POST' ) {
      $params = '';
      foreach ( $postfields as $k => $v ) {
        $params .= urlencode($k).'='.urlencode($v).'&';
      }
      $params = rtrim($params,'&');

      curl_setopt($ci, CURLOPT_POST, TRUE);
      if (!empty($postfields)) {
        curl_setopt($ci, CURLOPT_POSTFIELDS, $params);
      }
    }

    curl_setopt($ci, CURLOPT_URL, $url);
    $response = curl_exec($ci);
    $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    $http_info = array_merge($http_info, curl_getinfo($ci));
    curl_close ($ci);
    return $response;
}
