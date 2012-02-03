<?php
/**
 * def.php - Definition config
 *  
 * @access public
 * @package cockatoo
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
class Def {
  const  PATH_STATIC_PREFIX       = '/_s_'; // Associate with rewrite

  const  REQUEST_SERVICE          = '_S';
  const  REQUEST_DEVICE           = '_D';
  const  REQUEST_PATH             = '_P';
  const  REQUEST_ARGS             = '_A';

  const  RESERVED_SERVICE_CORE    = 'core';
  const  RESERVED_DEVICE_DEFAULT  = 'default';
  const  RESERVED_DEVICE_STATIC   = 'static';

  const  K_LAYOUT_TYPE            = 'type';
  const  K_LAYOUT_PRE_ACTION      = 'pre_action';
  const  K_LAYOUT_POST_ACTION     = 'post_action';
  // const  K_LAYOUT_CTYPE           = 'ctype';
  const  K_LAYOUT_SESSION_EXP     = 'session_exp';
  const  K_LAYOUT_EXPIRES         = 'expires';
  const  K_LAYOUT_CHILDREN        = 'children';
  const  K_LAYOUT_EXTRA           = 'extra';
  const  K_LAYOUT_LAYOUT          = 'layout';
  const  K_LAYOUT_COMPONENT       = 'component';
  const  K_LAYOUT_CLASS           = 'class';
  const  K_LAYOUT_HEIGHT          = 'height';
  const  K_LAYOUT_WIDTH           = 'width';
  const  K_LAYOUT_MIN_HEIGHT      = 'min_height';
  const  K_LAYOUT_MIN_WIDTH       = 'min_width';
  const  K_LAYOUT_VPOS            = 'vpos';
  const  K_LAYOUT_SWIDTH          = 'swidth';
  const  K_LAYOUT_EREDIRECT       = 'eredirect';
  const  K_LAYOUT_REDIRECT        = 'redirect';
  const  K_LAYOUT_PHEADER         = 'pheader';
  const  K_LAYOUT_HEADER          = 'header';

  const  K_LAYOUT_CTYPE_HTML      = 'html';
  const  K_LAYOUT_CTYPE_PLAIN     = 'plain';
  const  K_LAYOUT_CTYPE_JSON      = 'json';
  const  K_LAYOUT_CTYPE_BIN       = 'binary';


  const  K_COMPONENT_TYPE         = 'type';
  const  K_COMPONENT_SUBJECT      = 'subject';
  const  K_COMPONENT_DESCRIPTION  = 'description';
  const  K_COMPONENT_ID           = 'id';
  const  K_COMPONENT_CLASS        = 'class';
  const  K_COMPONENT_BODY         = 'body';
  const  K_COMPONENT_JS           = 'js';
  const  K_COMPONENT_CSS          = 'css';
  const  K_COMPONENT_ACTION       = 'action';

  const  K_STATIC_TYPE            = 'type';
  const  K_STATIC_DATA            = 'data';
  const  K_STATIC_BIN             = '*bin';
  const  K_STATIC_DESCRIPTION     = 'desc';
  const  K_STATIC_EXPIRE          = 'exp';
  const  K_STATIC_ETAG            = 'etag';
   
  const  BP_SESSION               = 'session';
  const  BP_LAYOUT                = 'layout';
  const  BP_COMPONENT             = 'component';
  const  BP_STATIC                = 'static';
  const  BP_STRAGE                = 'strage';
  const  BP_ACTION                = 'action';
  const  BP_SEARCH                = 'search';
  const  BP_CMS                   = 'cms';

  const  CMS_SERVICES             = 'services';

  const  BD_SEPARATOR             = '-';

  const  RenderingModeNORMAL      = 0x00;
  const  RenderingModeDEBUG1      = 0x01;
  const  RenderingModeDEBUG2      = 0x02;
  const  RenderingModeDEBUG3      = 0x04;
  const  RenderingModeDEBUG4      = 0x08;
  const  RenderingModeCMS         = 0x10;
  const  RenderingModeCMSTEMPLATE = 0x20;

  const  PAGELAYOUT               = 'PageLayout';
  const  PLAINWIDGET              = 'PlainWidget';
  const  JSONWIDGET               = 'JsonWidget';
  const  BINARYWIDGET             = 'BinaryWidget';
  const  IPC_GW_SEGMENT           = 'gateway';

  const  ActionSuccess            = 0;
  const  ActionError              = 1;

  const  AC_SESSION_ID            = '_SID';
  const  AC_SERVICE               = '_S';
#   const  AC_ARGS                  = '_A';
  const  AC_ENV                   = '_E';
#  const AC_BRL                   = '_brl';

  const  SESSION_KEY_REQ          = '_r';
  const  SESSION_KEY_POST         = '_p';
  const  SESSION_KEY_GET          = '_g';
  const  SESSION_KEY_SERVER       = '_s';
  const  SESSION_KEY_DEVICE       = '_d';
  const  SESSION_KEY_COOKIE       = '_c';
  const  SESSION_KEY_EXP          = '_e';
  const  SESSION_KEY_FILES        = '_f';

  const  F_TYPE                   = 't';
  const  F_ERROR                  = 'e';
  const  F_NAME                   = 'n';
  const  F_CONTENT                = 'c';
  const  F_SIZE                   = 's';

  const  CS_SESSION               = 'S';
  const  CS_ACTION                = 'A';

  const  MODE_NORMAL              = 0;
  const  MODE_DEBUG               = 1;
  // Log mask for specifing at config.php 
  const  LOGLV_DEBUG              = 0x0FFFFFFF;
  const  LOGLV_TRACE              = 0x00FFFFFF;
  const  LOGLV_PERFORMANCE        = 0x000FFFFF;
  const  LOGLV_INFO               = 0x0000FFFF;
  const  LOGLV_WARN               = 0x00000FFF;
  const  LOGLV_ERROR              = 0x000000FF;
  const  LOGLV_FATAL              = 0x0000000F;
  // Log lv
  const  LOGLV_DEBUG0             = 0x01000000;
  const  LOGLV_TRACE0             = 0x00100000;
  const  LOGLV_PERFORMANCE3       = 0x00080000;
  const  LOGLV_PERFORMANCE2       = 0x00040000;
  const  LOGLV_PERFORMANCE1       = 0x00020000;
  const  LOGLV_PERFORMANCE0       = 0x00010000;
  const  LOGLV_INFO0              = 0x00001000;
  const  LOGLV_WARN0              = 0x00000100;
  const  LOGLV_ERROR0             = 0x00000010;
  const  LOGLV_FATAL0             = 0x00000001;

}
