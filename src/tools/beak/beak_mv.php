#!/usr/bin/env php
<?php
/**
 * beak_mv.php - Beak transfer (BeakFile to BeakMongo)
 *  
 * @access public
 * @package cockatoo-utils
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
$COCKATOO_CONF=getenv('COCKATOO_CONF');
require_once($COCKATOO_CONF);
require_once($COCKATOO_ROOT.'tools/beak/beak_transfer.php');
ini_set('display_errors','On');


function parse_in($in){
  if ( $in and  preg_match('@^([^,]+)(?:,(\S+))?@',$in,$matches) != 0 ) {
    return array($matches[1],$matches[2]);
  }
  return null;
}
$options = getopt('',array('from:','to:','db:','col:','add','callback:'));

list($from,$froml)     =  parse_in($options['from']);
list($to,$tol)         =  parse_in($options['to']);
list($service,$scheme) =  parse_in($options['db']);
$new = isset($options['add'])?false:true;
$callback = isset($options['callback'])?$options['callback']:null;
$uindex=Beak::Q_UNIQUE_INDEX;
$collection            =  $options['col']?$options['col']:'ALL';

if ( ! ( $from and $to and $scheme and $service and $collection) ) {
  $msg = <<<_MSG_
Invalid arguments !

Usage:
  beak_mv.php --from <LOCATE> --to <LOCATE> --db <SERVICE,SCHEME> [--col <COLLECTION>]

LOCATE:
  <DRIVER>,<IP>:<PORT>

DRIVER:
  file       Cockatoo\BeakFile
  mongo      Cockatoo\BeakMongo
  null       Cockatoo\BeakNull

COLLECTION:
  ALL        All collections in the DB.

Example:
  export COCKATOO_ROOT=/usr/local/cockatoo
  beak_mv.php --from file --to mongo,172.25.36.63:27017 --db core,component
  beak_mv.php --from file --to mongo,172.25.36.63:27017 --db core,component --col default --add --callback

Setting:
  Edit [Config::BeakLocation] valiable in config.php to set the dest locations. 
    self::$BeakLocation = array (
      'strage://servicename-strage/'  => array('127.0.0.1:27017')
      );


_MSG_;
   print $msg;
   var_dump($argv);
   die ();
}

function kick_minimize(&$d,$type){
  global $COCKATOO_ROOT;
  $MINIMIZE=$COCKATOO_ROOT.'tools/minimize/minimize.sh';
  $descriptorspec = array(
    0 => array("pipe", 'r'),
    1 => array("pipe", 'w'),
    2 => array("file", '/dev/null', 'a')
    );
  $hp = proc_open("$MINIMIZE $type",$descriptorspec,$pipes);
  if ( is_resource($hp) ){
    fwrite($pipes[0],$d);
    fclose($pipes[0]);
    $d = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    proc_close($hp);
  }
}
function html_compaction(&$d) {
  if ( preg_match('@\S@',$d,$matches) === 0 ){
    return '';
  }
  kick_minimize($d,'raw_html');
}

function js_compaction(&$d) {
  if ( preg_match('@\S@',$d,$matches) === 0 ){
    return '';
  }
  kick_minimize($d,'js');
}

function css_compaction(&$d) {
  if ( preg_match('@\S@',$d,$matches) === 0 ){
    return '';
  }
  kick_minimize($d,'css');
}

function component_compaction($d){
  html_compaction($d['body']);
  js_compaction($d['js']);
  css_compaction($d['css']);
  return $d;
}

try {
  $transfer = new BeakTransfer($from,$to,$service,$scheme,$froml,$tol);
  if ( strcmp($collection,'ALL')!==0 ) {
    $transfer->transfer_collection($collection,$new,$uindex,$callback);
  }else {
    $transfer->transfer_all($new,$uindex,$callback);
  }
}catch(\Exception $e){
  Log::error($e);
  print($e);
}


# COCKATOO_ROOT=/usr/local/cockatoo/ /usr/local/php/bin/php beak_mv.php 
# 
