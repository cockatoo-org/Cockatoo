#!/usr/bin/env php
<?php
/**
 * beak_import.php - Beak transfer (BeakFile to BeakMongo)
 *  
 * @access public
 * @package cockatoo-tools
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
$COCKATOO_CONF=getenv('COCKATOO_CONF');
require_once($COCKATOO_CONF);
$CUR=getcwd();
require_once($COCKATOO_ROOT.'tools/beak/beak_transfer.php');
ini_set('display_errors','On');

if ( !$argv or count($argv) > 8 or count($argv) < 3 ) {
  $msg = <<<_MSG_
Invalid arguments !

Usage:
  beak_import.php  <FILE/DIR> <BRL> [<TYPE>] [CHARSET] [TO] [<IP:PORT>] [EXPIRE]

TYPE:
  AUTO       Use default definition by the extention.
  
CHARSET:
  AUTO       Convert from auto to utf8
  UTF-8      Don't convert
  EUC-JP     Convert from eucjp to utf8
  SHIFT-JIS  Convert from sjis to utf8

TO:
  file       Cockatoo\BeakFile
  mongo      Cockatoo\BeakMongo
  null       Cockatoo\BeakNull

EXPIRE:
  Specify the http-expire seconds.

Example:
  beak_import.php 'jquery-1.4.4.min.js' 'static://core-static/default/js/jquery-1.4.4.min.js'  'text/javascript'
  beak_import.php './js' 'static://core-static/default/js'
Setting:


_MSG_;
   print $msg;
   var_dump($argv);
   die ();
}

$path    = $argv[1];
$brl     = $argv[2];
$type    = $argv[3]?$argv[3]:'AUTO';
$charset = $argv[4]?$argv[4]:'AUTO';
$to      = $argv[5]?$argv[5]:'file';
$location= $argv[6];
$expire  = $argv[7];

if ( preg_match('@^/@',$path,$matches) === 0 ) { 
  $path = $CUR .'/'.$path;
}
try {
  $importer = new BeakImporter($to,$charset,$type,$location,$expire);
  $importer->import_all($path,$brl);

}catch(\Exception $e){
  Log::error($e);
  print($e);
}
