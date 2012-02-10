<?php
/**
 * index.php - bootstrap (html)
 *  
 * @access public
 * @package cockatoo-web
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
$CONTENT_DRAWER = null;
try {
  $COCKATOO_CONF=getenv('COCKATOO_CONF');
  require_once($COCKATOO_CONF);
  require_once(Config::$COCKATOO_ROOT.'wwwutils/core/reqparser.php');
  require_once(Config::$COCKATOO_ROOT.'wwwutils/core/webutils.php');
  require_once(Config::$COCKATOO_ROOT.'wwwutils/core/widget.php');
  require_once(Config::$COCKATOO_ROOT.'wwwutils/core/content.php');
  require_once(Config::$COCKATOO_ROOT.'utils/session.php');
  require_once(Config::$COCKATOO_ROOT.'utils/beak.php');
  require_once(Config::$COCKATOO_ROOT.'utils/stcontents.php');

  $HTTP_URI        = $_SERVER['REQUEST_URI'];
  $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
  $HTTP_REMOTE_ADDR= $_SERVER['REMOTE_ADDR'];
  $HTTP_PROTOCOL   = $_SERVER['SERVER_PROTOCOL'];
  $REMOTE_ADDR     = $_SERVER['REMOTE_ADDR'];
  $NAME = 'from ' . $REMOTE_ADDR . ' : ';

  try {
    $per = Log::pre_performance();
    Log::info($NAME);

    $HEADER  = getallheaders();
    list($SERVICE,$DEVICE,$PATH,$ARGS,$REQUEST_PARSER,$DEVICE_SELECTOR) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);

    $mode = isset($_GET['debug'])?$_GET['debug']:Def::RenderingModeNORMAL;
    $CONTENT_DRAWER = new ContentDrawer($SERVICE,$DEVICE,$PATH,$ARGS,$REQUEST_PARSER,$DEVICE_SELECTOR,$mode);  
    $force_redirect = $CONTENT_DRAWER->layout();
    if ( $force_redirect ) {
      redirect($force_redirect);
    }

    if ( strcmp($CONTENT_DRAWER->ctype,Def::K_LAYOUT_CTYPE_HTML) === 0 ) {
      $CONTENT_DRAWER->session($HEADER,$_SERVER,$_POST,$_GET,$_COOKIE,$_FILES);
      $CONTENT_DRAWER->preAction();
      $CONTENT_DRAWER->components();  
      $CONTENT_DRAWER->preAction();  
      $CONTENT_DRAWER->actions();  
      $CONTENT_DRAWER->postAction();
      $CONTENT_DRAWER->prepareDraw();
      $CONTENT_DRAWER->drawPHeader('text/html');
      Include 'wwwutils/core/frame.php';
    } elseif ( strcmp($CONTENT_DRAWER->ctype,Def::K_LAYOUT_CTYPE_PLAIN) === 0 ) {
      $CONTENT_DRAWER->session($HEADER,$_SERVER,$_POST,$_GET,$_COOKIE,$_FILES);
      $CONTENT_DRAWER->preAction();
      $CONTENT_DRAWER->components();  
      $CONTENT_DRAWER->preAction();  
      $CONTENT_DRAWER->actions();  
      $CONTENT_DRAWER->postAction();
      $CONTENT_DRAWER->prepareDraw();
      $CONTENT_DRAWER->drawPHeader('text/plain');
      $CONTENT_DRAWER->drawMain();
    } elseif ( strcmp($CONTENT_DRAWER->ctype,Def::K_LAYOUT_CTYPE_JSON) === 0 ) {
      $CONTENT_DRAWER->session($HEADER,$_SERVER,$_POST,$_GET,$_COOKIE,$_FILES);
      $CONTENT_DRAWER->preAction();
      $CONTENT_DRAWER->components();  
      $CONTENT_DRAWER->preAction();  
      $CONTENT_DRAWER->actions();  
      $CONTENT_DRAWER->postAction();
      if ( Config::Mode === Def::MODE_DEBUG ) {
        $CONTENT_DRAWER->prepareDraw();
      }
      $CONTENT_DRAWER->drawPHeader('text/javascript');
      $CONTENT_DRAWER->drawJson();
    } elseif ( strcmp($CONTENT_DRAWER->ctype,Def::K_LAYOUT_CTYPE_BIN) === 0 ) {

      $CONTENT_DRAWER->tmpSession($HEADER,$_SERVER,$_POST,$_GET,$_COOKIE,$_FILES);
      $CONTENT_DRAWER->preAction();
      $CONTENT_DRAWER->components();  
      $CONTENT_DRAWER->preAction();  
      $CONTENT_DRAWER->actions();  
      $CONTENT_DRAWER->postAction();
      if ( Config::Mode === Def::MODE_DEBUG ) {
        $CONTENT_DRAWER->prepareDraw();
      }
      $CONTENT_DRAWER->drawPHeader(null);
      $CONTENT_DRAWER->drawBinary();
    }
  }catch ( RedirectException $e ) {
    Log::info($NAME . $e->getMessage());
    if ( $mode & (Def::RenderingModeDEBUG1 | Def::RenderingModeDEBUG2) ) {
      print $e->getMessage();
    }
  }
}catch ( \Exception $e ) {
  try {
    Log::warn($NAME . $e->getMessage(),$e);
    if ( $mode & (Def::RenderingModeDEBUG1 | Def::RenderingModeDEBUG2) ) {
      print $e->getMessage();
    }
    if ( $CONTENT_DRAWER ) {
      if ( $CONTENT_DRAWER->eredirect ) {
        redirect($CONTENT_DRAWER->eredirect);
      }elseif( $CONTENT_DRAWER->baseEredirect ) {
        redirect($CONTENT_DRAWER->baseEredirect);
      }
    }
    redirect(Config::ErrorRedirect);
  }catch ( RedirectException $e ) {
    Log::info($NAME . $e->getMessage());
    if ( $mode & (Def::RenderingModeDEBUG1 | Def::RenderingModeDEBUG2) ) {
      print $e->getMessage();
    }
  }catch ( \Exception $e ) {
    Log::error($NAME . $e->getMessage(),$e);
    if ( $mode & (Def::RenderingModeDEBUG1 | Def::RenderingModeDEBUG2) ) {
      print $e->getMessage();
    }
  }
}
Log::performance($per,$NAME);
