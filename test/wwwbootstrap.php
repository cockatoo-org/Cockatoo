<?php
namespace Cockatoo;
$COCKATOO_CONF=getenv('COCKATOO_CONF');
require_once($COCKATOO_CONF);

$_SERVER;
$HEADER;
function default_args(){
  global $_SERVER,$HEADER;
  $_SERVER=array(
    'REDIRECT_STATUS' => '200' ,
    'HTTP_HOST' => '127.0.0.1' ,
    'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ja; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14' ,
    'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' ,
    'HTTP_ACCEPT_LANGUAGE' => 'ja,en-us;q=0.7,en;q=0.3' ,
    'HTTP_ACCEPT_ENCODING' => 'gzip,deflate' ,
    'HTTP_ACCEPT_CHARSET' => 'Shift_JIS,utf-8;q=0.7,*;q=0.7' ,
    'HTTP_KEEP_ALIVE' => '115' ,
    'HTTP_CONNECTION' => 'keep-alive' ,
    'PATH' => '/usr/bin:/bin' ,
    'SERVER_SIGNATURE' => '' ,
    'SERVER_SOFTWARE' => 'Apache/2.2.14 (Unix) DAV/2 mod_ssl/2.2.14 OpenSSL/0.9.8k PHP/5.3.5' ,
    'SERVER_NAME' => '127.0.0.1' ,
    'SERVER_ADDR' => '127.0.0.1' ,
    'SERVER_PORT' => '80' ,
    'REMOTE_ADDR' => '127.0.0.1' ,
    'DOCUMENT_ROOT' => '/usr/local/apache2/htdocs' ,
    'SERVER_ADMIN' => 'you@example.com' ,
    'SCRIPT_FILENAME' => null ,
    'REMOTE_PORT' => '1369' ,
    'REDIRECT_QUERY_STRING' => null,
    'REDIRECT_URL' => null,
    'GATEWAY_INTERFACE' => 'CGI/1.1' ,
    'SERVER_PROTOCOL' => 'HTTP/1.1' ,
    'REQUEST_METHOD' => 'GET' ,
    'QUERY_STRING' => null,
    'REQUEST_URI' => null,
    'SCRIPT_NAME' => null ,
    'PHP_SELF' => null ,
    'REQUEST_TIME' => 1299469847,
    'argv' => null,
    'argc' => 1
    );
  $HEADER = array(
    'Host' => '127.0.0.1' ,
    'User-Agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ja; rv:1.9.2.14) Gecko/20110218 Firefox/3.6.14' ,
    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' ,
    'Accept-Language' => 'ja,en-us;q=0.7,en;q=0.3' ,
    'Accept-Encoding' => 'gzip,deflate' ,
    'Accept-Charset' => 'Shift_JIS,utf-8;q=0.7,*;q=0.7' ,
    'Keep-Alive' => '115' ,
    'Connection' => 'keep-alive' 
    );
}

function getallheaders () {
  global $HEADER;
  return $HEADER;
}
function header($output){
  print $output."\n";
}

$OUTPUT;
function run_www ($file,$uri,$get=array(),$post=array(),$server=array(),$header=array()){
  global $_GET,$_POST,$_SERVER,$HEADER,$OUTPUT,$COCKATOO_ROOT;
  default_args();

  $PATH=$COCKATOO_ROOT.'www/';
  $_SERVER['SCRIPT_FILENAME'] = $PATH . $file;
  $_SERVER['SCRIPT_NAME'] = $file;
  $_SERVER['PHP_SELF'] = $file;
  $_SERVER['REDIRECT_URL'] = $uri;
  $ruri = $uri;
  foreach($get as $k => $v ) {
    $ruri.= '&' . $k . '=' . $v;
  }
  $_SERVER['REQUEST_URI']=$ruri;
  $_SERVER['QUERY_STRING'] = 'r='.$ruri;
  $_SERVER['REDIRECT_QUERY_STRING'] = $_SERVER['QUERY_STRING'];
  $_SERVER['argv'] = array($_SERVER['QUERY_STRING']);

  $_SERVER = array_merge($_SERVER,$server);
  $HEADER  = array_merge($HEADER,$header);
  $_POST   = $post;
  $_GET = $get;
  $_GET['r'] = $uri;

  $OUTPUT='';
  ob_start("Cockatoo\www_output");
  include($PATH . $file);
  ob_end_flush();
  return $OUTPUT;
}

function www_output($buffer)
{
  global $OUTPUT;
  $OUTPUT .= $buffer;
//   return ($buffer);
  return '';
}
