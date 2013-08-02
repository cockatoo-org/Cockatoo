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
  require_once(dirname(__FILE__).'/../def.php');
  require_once(Config::COCKATOO_ROOT.'wwwutils/core/reqparser.php');
  require_once(Config::COCKATOO_ROOT.'wwwutils/core/webutils.php');
  require_once(Config::COCKATOO_ROOT.'wwwutils/core/widget.php');
  require_once(Config::COCKATOO_ROOT.'wwwutils/core/content.php');
  require_once(Config::COCKATOO_ROOT.'utils/session.php');
  require_once(Config::COCKATOO_ROOT.'utils/beak.php');
  require_once(Config::COCKATOO_ROOT.'utils/stcontents.php');

  $HTTPS           = $_SERVER['SSL_PROTOCOL'];

  $HTTP_URI        = $_SERVER['REQUEST_URI'];
  $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
  $HTTP_REMOTE_ADDR= $_SERVER['REMOTE_ADDR'];
  $HTTP_PROTOCOL   = $_SERVER['SERVER_PROTOCOL'];
  $REMOTE_ADDR     = $_SERVER['REMOTE_ADDR'];
  $NAME = 'from ' . $REMOTE_ADDR . ' : ';
  $POST = getPost($_SERVER[REQUEST_METHOD]);

  try {
    $per = Log::pre_performance();
    Log::info($NAME);

    $HEADER  = getallheaders();
    list($SERVICE,$TEMPLATE,$PATH,$ARGS,$SESSION_PATH) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);

    $mode = isset($_GET['debug'])?$_GET['debug']:Def::RenderingModeNORMAL;
    if ( $mode !== Def::RenderingModeNORMAL ) {
      header('Content-Type: text/html; charset=UTF-8');
    }

    $CONTENT_DRAWER = new ContentDrawer($SERVICE,$TEMPLATE,$PATH,$ARGS,$SESSION_PATH,$mode);  
    $force_redirect = $CONTENT_DRAWER->layout();
    if ( $force_redirect ) {
      moved_permanently($force_redirect);
    }

    if ( strcmp($CONTENT_DRAWER->ctype,Def::K_LAYOUT_CTYPE_HTML) === 0 ) {
      $CONTENT_DRAWER->session($HEADER,$_SERVER,$POST,$_GET,$_COOKIE);
      $CONTENT_DRAWER->components();  
      $CONTENT_DRAWER->preAction();  
      $CONTENT_DRAWER->actions();  
      $CONTENT_DRAWER->postAction();
      $CONTENT_DRAWER->startEncode();
      $CONTENT_DRAWER->prepareDraw();
      $CONTENT_DRAWER->drawPHeader('text/html');
      Include Config::COCKATOO_ROOT.'wwwutils/core/frame.php';
      $CONTENT_DRAWER->endEncode();
    } elseif ( strcmp($CONTENT_DRAWER->ctype,Def::K_LAYOUT_CTYPE_PLAIN) === 0 ) {
      $CONTENT_DRAWER->session($HEADER,$_SERVER,$POST,$_GET,$_COOKIE);
      $CONTENT_DRAWER->components();  
      $CONTENT_DRAWER->preAction();  
      $CONTENT_DRAWER->actions();  
      $CONTENT_DRAWER->postAction();
      $CONTENT_DRAWER->startEncode();
      $CONTENT_DRAWER->prepareDraw();
      $CONTENT_DRAWER->drawPHeader('text/plain');
      $CONTENT_DRAWER->drawMain();
      $CONTENT_DRAWER->endEncode();
    } elseif ( strcmp($CONTENT_DRAWER->ctype,Def::K_LAYOUT_CTYPE_JSON) === 0 ) {
      $CONTENT_DRAWER->session($HEADER,$_SERVER,$POST,$_GET,$_COOKIE);
      $CONTENT_DRAWER->components();  
      $CONTENT_DRAWER->preAction();  
      $CONTENT_DRAWER->actions();  
      $CONTENT_DRAWER->postAction();
      if ( Config::Mode === Def::MODE_DEBUG ) {
        $CONTENT_DRAWER->prepareDraw();
      }
      $CONTENT_DRAWER->startEncode();
      $CONTENT_DRAWER->drawPHeader('text/javascript');
      $CONTENT_DRAWER->drawJson();
      $CONTENT_DRAWER->endEncode();
    } elseif ( strcmp($CONTENT_DRAWER->ctype,Def::K_LAYOUT_CTYPE_BIN) === 0 ) {
      $CONTENT_DRAWER->session($HEADER,$_SERVER,$POST,$_GET,$_COOKIE);
//      $CONTENT_DRAWER->tmpSession($HEADER,$_SERVER,$POST,$_GET,$_COOKIE);
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
        moved_temporary($CONTENT_DRAWER->eredirect);
      }elseif( $CONTENT_DRAWER->baseEredirect ) {
        moved_temporary($CONTENT_DRAWER->baseEredirect);
      }
    }
    moved_temporary(Config::ErrorRedirect);
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
