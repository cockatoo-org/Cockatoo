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
$CUR=getcwd();
require_once(dirname(__FILE__) . '/../../def.php');
require_once(Config::COCKATOO_ROOT.'tools/beak/beak_transfer.php');
ini_set('display_errors','On');

$options = getopt('',array('src:','to:','brl:','type:','charset:','exp:'));
$src                   = $options['src'];
list($to,$tol)         = parse_in($options['to']);
$brl                   = $options['brl'];
$type                  = isset($options['type'])?$options['type']:'AUTO';
$charset               = isset($options['charset'])?$options['charset']:'AUTO';
$expire                = $options['exp'];

if ( preg_match('@^/@',$src,$matches) === 0 ) { 
  $src = $CUR .'/'.$src;
}

if ( ! ( $src and $to and $brl ) ) {
  $msg = <<<_MSG_
Invalid arguments !

Usage:
  beak_import.php --src <FILE/DIR> --to <LOCATE> --brl <BASE BRL> [--type <TYPE>] [--charset <CHARSET>] [--exp <EXPIRE>] 

LOCATE:
  <DRIVER>,<IP>:<PORT>

DRIVER:
  file       Cockatoo\BeakFile
  mongo      Cockatoo\BeakMongo
  null       Cockatoo\BeakNull

TYPE:
  AUTO       Use default definition by the extention.
  mime-type
  
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


try {
  $importer = new BeakImporter($to,$brl,$charset,$type,$tol,$expire);
  $importer->import_all($src,$brl);

}catch(\Exception $e){
  Log::error($e);
  print($e);
}
