<?php
/**
 * static.php - bootstrap (css/js)
 *  
 * @access public
 * @package cockatoo-web
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
require_once(dirname(__FILE__).'/../def.php');
require_once(Config::COCKATOO_ROOT.'wwwutils/core/reqparser.php');
require_once(Config::COCKATOO_ROOT.'wwwutils/core/webutils.php');
require_once(Config::COCKATOO_ROOT.'utils/beak.php');
require_once(Config::COCKATOO_ROOT.'utils/stcontents.php');

$REMOTE_ADDR     = $_SERVER['REMOTE_ADDR'];
$NAME = 'from ' . $REMOTE_ADDR . ' : ';

try {
  $per = Log::pre_performance();
  Log::info($NAME);

  $HEADER = getallheaders();
  list($SERVICE,$DEVICE,$PATH,$ARGS) = parseStaticRequest($HEADER,$_SERVER,$_GET,$_COOKIE);
  $brl = brlgen(Def::BP_STATIC,$SERVICE,$DEVICE,$PATH,Beak::M_GET);
  $content = StaticContent::get($brl);
  StaticContent::http($content,$HEADER);
}catch ( \Exception $e ) {
  Log::error($NAME . '404 Not found ' . $e->getMessage(),$e);
  http_404();
}

Log::performance($per,$NAME);
