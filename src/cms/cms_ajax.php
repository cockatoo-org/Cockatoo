<?php
/**
 * cms_ajax.php - CMS
 *  
 * @access public
 * @package cockatoo-cms
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
require_once(dirname(__FILE__) . '/../def.php');
require_once(Config::COCKATOO_ROOT.'wwwutils/core/cms_acl.php');
require_once(Config::COCKATOO_ROOT.'wwwutils/core/webutils.php');
require_once(Config::COCKATOO_ROOT.'wwwutils/core/widget.php');
require_once(Config::COCKATOO_ROOT.'wwwutils/core/content.php');
require_once(Config::COCKATOO_ROOT.'utils/beak.php');
require_once(Config::COCKATOO_ROOT.'utils/stcontents.php');

$_sP = getPost($_SERVER['REQUEST_METHOD']);
$_sG = $_GET;

$op   = $_sP['op'];
  
$service_id  = $_sP['service_id'];
$template_id  = $_sP['template_id'];
$page_id  = $_sP['page_id'];
$component_id  = $_sP['component_id'];
$layout_id  = $_sP['layout_id'];
$static_id  = $_sP['static_id'];
$rev  = $_sP['rev'];
$ctype= $_sP['ctype'];

$eredirect = $_sP['eredirect'];
$redirect = $_sP['redirect'];
$css = $_sP['css'];
$js  = $_sP['js'];
$pre_action = $_sP['pre_action'];
$post_action = $_sP['post_action'];
$header = $_sP['header'];
$bottom = $_sP['bottom'];
$pheader = '';
foreach ( preg_split("@\r?\n@",$_sP['pheader']) as $p ) {
  if ( preg_match('@^\s*$@',$p,$matches) === 0 ) {
    $pheader .= $p . "\n";
  }
}
$session_exp  = $_sP['session_exp'];
$expires      = $_sP['expires'];
$expires_time = $_sP['expires_time'];

$type         = $_sP['type'];
$subject      = $_sP['subject'];
$description  = $_sP['description'];
$id           = $_sP['id'];
$class        = $_sP['clazz'];
$body         = $_sP['body'];
$bin          = $_sP['bin'];
$actions      = preg_split("@\r?\n@",$_sP['actions']);
$check        = $_sP['brl'];
$r;
$emsg='';
try {
  if ( $op === 'getS' ) {
    $service_ids = getS();
    foreach ( $service_ids as $service_id){
      if ( is_readable($service_id) ){
        $r [] = array('service_id' => $service_id , 'name' => $service_id);
      }
    }
  } elseif( $op === 'getD' ) {
    if ( is_readable($service_id) ){
      $template_ids = getD($service_id);
      foreach ( $template_ids as $template_id){
        // layout
        $brl = brlgen(Def::BP_LAYOUT,$service_id,$template_id,'/',Beak::M_GET);
        $default_layout = BeakController::beakSimpleQuery($brl);
        $rev        = $default_layout[Beak::ATTR_REV];
        $eredirect  = $default_layout[Def::K_LAYOUT_EREDIRECT];
        $redirect   = $default_layout[Def::K_LAYOUT_REDIRECT];
        $header     = $default_layout[Def::K_LAYOUT_HEADER];
        $pheader    = $default_layout[Def::K_LAYOUT_PHEADER];
        $bottom     = $default_layout[Def::K_LAYOUT_BOTTOM];
        $session_exp= $default_layout[Def::K_LAYOUT_SESSION_EXP];
        $expires    = $default_layout[Def::K_LAYOUT_EXPIRES];
        // css
        $cssBrl = brlgen(Def::BP_STATIC,$service_id,$template_id,Config::CommonCSS,Beak::M_GET);
        $css = BeakController::beakSimpleQuery($cssBrl);
        $css = $css?$css[Def::K_STATIC_DATA]:'';
        // js
        $jsBrl = brlgen(Def::BP_STATIC,$service_id,$template_id,Config::CommonJs,Beak::M_GET);
        $js = BeakController::beakSimpleQuery($jsBrl);
        $js = $js?$js[Def::K_STATIC_DATA]:'';
        $r []= array('rev' => $rev,
                     'service_id' => $service_id ,
                     'template_id' => $template_id ,
                     'name' => $template_id ,
                     'eredirect' => $eredirect,
                     'redirect' => $redirect,
                     'css' => $css ,
                     'js' => $js,
                     'session' => $session_exp,
                     'session_exp' => $session_exp,
                     'expires'      => $expires,
                     'expires_time' => $expires,
                     'header' => $header,
                     'pheader' => $pheader,
                     'bottom' => $bottom,
                     'layout' => '<a target="_blank" href="cms_layout.php?'.Def::REQUEST_SERVICE.'=' . $service_id . '&'.Def::REQUEST_TEMPLATE.'=' . $template_id . '&'.Def::REQUEST_PATH.'=/' . "" . '">'. "$template_id" .'</a>'
                    
          );
      }
    }
  } elseif( $op === 'addD' ) {
    check_writable($service_id);
    $template_id = $_sP['template'];
    $layout = array(Def::K_LAYOUT_TYPE => 'HorizontalWidget' , Def::K_LAYOUT_COMPONENT => "component://core-component/default/horizontal#critical" , Def::K_LAYOUT_EXTRA => null ,  Def::K_LAYOUT_CHILDREN => array(
                      array(Def::K_LAYOUT_TYPE => 'PageLayout' , Def::K_LAYOUT_COMPONENT => "component://core-component/default/pagelayout" , Def::K_LAYOUT_EXTRA => null ,  Def::K_LAYOUT_CHILDREN => array())
                      ));
    setD(false,$rev,$service_id,$template_id,$eredirect,$redirect,$css,$js,$session_exp,$expires_time,$header,$pheader,$bottom,$layout);
  } elseif( $op === 'setD' ) {
    check_writable($service_id);
    setD(true,$rev,$service_id,$template_id,$eredirect,$redirect,$css,$js,$session_exp,$expires_time,$header,$pheader,$bottom,null);
  } elseif( $op === 'getP' ) {
    if ( is_readable($service_id) ){
      $page_ids = getP($service_id,$template_id);
      foreach ( $page_ids as $page_id){
        $r [] = array('service_id' => $service_id , 
                      'template_id' => $template_id , 
                      'page_id' => $page_id ,
                      'name' => $page_id , 
                      'ctype' => '',
                      'page' => '<a target="_blank" href="/index.php?'.Def::REQUEST_SERVICE.'=' . $service_id . '&'.Def::REQUEST_TEMPLATE.'=' . $template_id . '&'.Def::REQUEST_PATH.'=' . "/$page_id"  . '">'. "/$page_id" .'</a>',
                      'layout' => '<a target="_blank" href="cms_layout.php?'.Def::REQUEST_SERVICE.'=' . $service_id . '&'.Def::REQUEST_TEMPLATE.'=' . $template_id . '&'.Def::REQUEST_PATH.'=' . "/$page_id" .'">'. "/$page_id".'</a>',
                      'pre_action' => '',
                      'post_action' => '',
                      'session' => '',
                      'session_exp' => '',
                      'expires' => '',
                      'expires_time' => '',
                      'header' => '',
                      'pheader' => '',
                      'bottom' => '',
                      'contents' => ''
          );
      }
    }
  } elseif( $op === 'getPP' ) {
    if ( is_readable($service_id) ){
      $CONTENT_DRAWER = new ContentDrawer($service_id,$template_id,$page_id,null,null,null,Def::RenderingModeNORMAL);  
      $CONTENT_DRAWER->layout();
      $CONTENT_DRAWER->components();
      $contents = '';
      $contents .= "$CONTENT_DRAWER->layoutBrl\n";
      $contents .= " * $CONTENT_DRAWER->preAction\n";
      foreach ( $CONTENT_DRAWER->componentDatas as $b => $c ) {
        $contents .= " - $b\n";
        foreach ( $c[Def::K_COMPONENT_ACTION] as $a ) {
          if ( $a ) {
            $contents .= "      $a\n";
          }
        }
      }
      $contents .= " * $CONTENT_DRAWER->postAction\n";
      $r = array('rev'         => $CONTENT_DRAWER->layoutData[Beak::ATTR_REV],
                 'ctype'       => $CONTENT_DRAWER->ctype,
                 'pre_action'  => $CONTENT_DRAWER->preAction,
                 'post_action' => $CONTENT_DRAWER->postAction,
                 'session'     => $CONTENT_DRAWER->layoutData[Def::K_LAYOUT_SESSION_EXP],
                 'session_exp' => $CONTENT_DRAWER->layoutData[Def::K_LAYOUT_SESSION_EXP],
                 'expires'     => $CONTENT_DRAWER->expires,
                 'expires_time'=> $CONTENT_DRAWER->expires,
                 'eredirect'   => $CONTENT_DRAWER->eredirect,
                 'redirect'    => $CONTENT_DRAWER->redirect,
                 'header'      => $CONTENT_DRAWER->layoutData[Def::K_LAYOUT_HEADER],
                 'pheader'     => $CONTENT_DRAWER->layoutData[Def::K_LAYOUT_PHEADER],
                 'bottom'      => $CONTENT_DRAWER->layoutData[Def::K_LAYOUT_BOTTOM],
                 'contents'    => $contents
        );
    }
  } elseif( $op === 'addP' ) {
    check_writable($service_id);
    $page_id = $_sP['name'];
    $layout = array(Def::K_LAYOUT_TYPE => 'HorizontalWidget' , Def::K_LAYOUT_COMPONENT => "component://core-component/default/horizontal#critical" , Def::K_LAYOUT_EXTRA => null ,  Def::K_LAYOUT_CHILDREN => array());
    setP(false,$rev,$service_id,$template_id,$page_id,$ctype,$eredirect,$redirect,$pre_action,$post_action,$session_exp,$expires_time,$header,$pheader,$bottom,$layout);
  } elseif( $op === 'setP' ) {
    check_writable($service_id);
    setP(true,$rev,$service_id,$template_id,$page_id,$ctype,$eredirect,$redirect,$pre_action,$post_action,$session_exp,$expires_time,$header,$pheader,$bottom,null);
  } elseif( $op === 'cpP' ) {
    check_writable($service_id);
    $brl = brlgen(Def::BP_LAYOUT,$service_id,$template_id,$page_id,Beak::M_GET);
    $page_layout = BeakController::beakSimpleQuery($brl);
    $layout = $page_layout[Def::K_LAYOUT_LAYOUT];
    $page_id = $_sP['name'];
    setP(false,$rev,$service_id,$template_id,$page_id,$ctype,$eredirect,$redirect,$pre_action,$post_action,$session_exp,$expires_time,$header,$pheader,$bottom,$layout);
  } elseif( $op === 'delP' ) {
    $brl = brlgen(Def::BP_LAYOUT,$service_id,$template_id,$page_id,Beak::M_DEL);
    BeakController::beakSimpleQuery($brl);
  } elseif( $op === 'getC' ) {
    if ( is_readable($service_id) ){
      $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_DEFAULT,'',Beak::M_KEY_LIST);
      $components = BeakController::beakSimpleQuery($brl);
      $r = array();
      foreach ( $components as $c){
        $component_id = $c;
        if ( preg_match('@[^/]$@',$c,$matches) !== 0 ) {
          $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_DEFAULT,$component_id,Beak::M_GET);
          $r [] = array('service_id' => $service_id ,
                        'component_id' => $component_id ,
                        'name' => $component_id , 
                        'brl'  => $brl , 
                        'description' => '' , 
                        'type' => '',
                        'clazz' => '',
                        'body' => '',
                        'actions' => '',
                        'css' => '',
                        'js' => ''
            );
        }
      }
    }
  } elseif( $op === 'getCC' ) {
    if ( is_readable($service_id) ){
      $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_DEFAULT,$component_id,'');
      $component = BeakController::beakSimpleQuery($brl);
      $r = array('rev'         => $component[Beak::ATTR_REV],
                 'type'        => $component[Def::K_COMPONENT_TYPE],
                 'subject'     => $component[Def::K_COMPONENT_SUBJECT],
                 'description' => $component[Def::K_COMPONENT_DESCRIPTION],
                 'id'          => $component[Def::K_COMPONENT_ID],
                 'clazz'       => $component[Def::K_COMPONENT_CLASS],
                 'body'        => $component[Def::K_COMPONENT_BODY],
                 'actions'     => $component[Def::K_COMPONENT_ACTION]?join("\n",$component[Def::K_COMPONENT_ACTION]):'',
                 'js'          => $component[Def::K_COMPONENT_JS],
                 'css'         => $component[Def::K_COMPONENT_CSS],
                 'header'      => $component[Def::K_COMPONENT_HEADER],
                 'bottom'      => $component[Def::K_COMPONENT_BOTTOM]
        );
    }
  } elseif( $op === 'addC' ) {
    check_writable($service_id);
    $component_id = $_sP['name'];
    setC(false,$rev,$service_id,$component_id,$type,$subject,$description,$css,$js,$id,$class,$body,$actions,$header,$bottom);
  } elseif( $op === 'setC' ) {
    check_writable($service_id);
    setC(true,$rev,$service_id,$component_id,$type,$subject,$description,$css,$js,$id,$class,$body,$actions,$header,$bottom);
  } elseif( $op === 'cpC' ) {
    check_writable($service_id);
    $component_id = $_sP['name'];
    setC(true,$rev,$service_id,$component_id,$type,$subject,$description,$css,$js,$id,$class,$body,$actions,$header,$bottom);
  } elseif( $op === 'checkC' ) {
    if ( is_readable($service_id) ){
      $r['required'] = implode(getRequired($check),"\n");
    }
  } elseif( $op === 'delC' ) {
    check_writable($service_id);
    $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_DEFAULT,$component_id,Beak::M_GET);
    $required = getRequired($brl);
    if ( $required ) {
      throw new \Exception('Still required by ' . implode($required,'<br>'));
    }
    $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_DEFAULT,$component_id,Beak::M_DEL);
    BeakController::beakSimpleQuery($brl);



/* @@@ */

  } elseif( $op === 'getL' ) {
    if ( is_readable($service_id) ){
      $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_LAYOUT,'',Beak::M_KEY_LIST);
      $layoutwidgets = BeakController::beakSimpleQuery($brl);
      $r = array();
      foreach ( $layoutwidgets as $layout_id){
        
        if ( preg_match('@[^/]$@',$layout_id,$matches) !== 0 ) {
          $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_LAYOUT,$layout_id,Beak::M_GET);
          $r [] = array('service_id' => $service_id ,
                        'layout_id' => $layout_id ,
                        'name' => $layout_id , 
                        'brl'  => $brl , 
                        'description' => '' , 
                        'type' => '',
                        'layout' => '<a target="_blank" href="cms_layout.php?'.Def::REQUEST_SERVICE.'=' . $service_id . '&'.Def::REQUEST_TEMPLATE.'=' . $template_id . '&'.Def::REQUEST_LAYOUT.'='.$layout_id.'">'.$layout_id.'</a>'
            );
        }
      }
    }
  } elseif( $op === 'getLL' ) {
    if ( is_readable($service_id) ){
      $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_LAYOUT,$layout_id,'');
      $layoutwidget = BeakController::beakSimpleQuery($brl);
      $r = array('rev'         => $layoutwidget[Beak::ATTR_REV],
                 'type'        => $layoutwidget[Def::K_COMPONENT_TYPE],
                 'subject'     => $layoutwidget[Def::K_COMPONENT_SUBJECT],
                 'description' => $layoutwidget[Def::K_COMPONENT_DESCRIPTION]
        );
    }
  } elseif( $op === 'addL' ) {
    check_writable($service_id);
    $layout_id = $_sP['name'];
    $layout = array(Def::K_LAYOUT_TYPE => 'HorizontalWidget' , Def::K_LAYOUT_COMPONENT => "component://core-component/default/horizontal#critical" , Def::K_LAYOUT_EXTRA => null ,  Def::K_LAYOUT_CHILDREN => array());
    setL(false,$rev,$service_id,$layout_id,$type,$subject,$description,$layout);
  } elseif( $op === 'setL' ) {
    setL(true,$rev,$service_id,$layout_id,$type,$subject,$description,null);
  } elseif( $op === 'setCL' ) {
    check_writable($service_id);

    $layout = json_decode($_sP['layout'],true);
    if ( ! $layout ) {
      throw new \Exception('Fail to json_decode : ' . $_sP['layout']);
    }
    $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_LAYOUT,$layout_id,Beak::M_GET);
    $layoutwidget = BeakController::beakSimpleQuery($brl);
    if ( ! $layoutwidget ) {
      throw new \Exception('Fail to get : ' . $brl);
    }
    $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_LAYOUT,$layout_id,Beak::M_SET);
    $layoutwidget[Def::K_LAYOUT_LAYOUT] = $layout;
    $ret = BeakController::beakSimpleQuery($brl,$layoutwidget);
    if ( ! $ret ) {
      throw new \Exception('Fail to set : ' . $brl);
    }
  } elseif( $op === 'cpL' ) {
    check_writable($service_id);
    $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_LAYOUT,$layout_id,Beak::M_GET);
    $layoutwidget = BeakController::beakSimpleQuery($brl);
    $layout = $layoutwidget[Def::K_LAYOUT_LAYOUT];
    $layout_id = $_sP['name'];
    setL(true,$rev,$service_id,$layout_id,$type,$subject,$description,$layout);
  } elseif( $op === 'checkL' ) {
    if ( is_readable($service_id) ){
      $r['required'] = implode(getRequired($check),"\n");
    }
  } elseif( $op === 'delL' ) {
    check_writable($service_id);
    $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_LAYOUT,$layout_id,Beak::M_GET);
    $required = getRequired($brl);
    if ( $required ) {
      throw new \Exception('Still required by ' . implode($required,'<br>'));
    }
    $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_LAYOUT,$layout_id,Beak::M_DEL);
    BeakController::beakSimpleQuery($brl);




  } elseif( $op === 'getSC' ) {
    $r = array();
    $template_ids = getD($service_id);
    foreach ( $template_ids as $template_id){
      $brl = brlgen(Def::BP_STATIC,$service_id,$template_id,'',Beak::M_KEY_LIST);
      $static_contents = BeakController::beakSimpleQuery($brl);
      foreach ( $static_contents as $static_id){
        $brl = brlgen(Def::BP_STATIC,$service_id,$template_id,$static_id,'');
        $r [] = array('service_id' => $service_id ,
                      'template_id' => $template_id ,
                      'static_id' => $static_id ,
                      'name' => $static_id , 
                      'brl'  => $brl
          );
      }
    }
  } elseif( $op === 'getSCC' ) {
    if ( is_readable($service_id) ){
      $brl = brlgen(Def::BP_STATIC,$service_id,$template_id,$static_id,Beak::M_GET);
      $static_content = BeakController::beakSimpleQuery($brl);
      $r = array('type'        => $static_content[Def::K_STATIC_TYPE],
                 'etag'        => $static_content[Def::K_STATIC_ETAG],
                 'expires'     => $static_content[Def::K_STATIC_EXPIRE],
                 'bin'         => (bool)$static_content[Def::K_STATIC_BIN],
                 'body'        => $static_content[Def::K_STATIC_DATA],
                 'description' => $static_content[Def::K_STATIC_DESCRIPTION]
        );
    }
  } elseif( $op === 'addSC' ) {
    check_writable($service_id);
    $static_id = $_sP['name'];
    setSC(false,$rev,$service_id,$template_id,$static_id,$type,$expires,$bin,$body,$description);
  } elseif( $op === 'setSC' ) {
    check_writable($service_id);
    setSC(true,$rev,$service_id,$template_id,$static_id,$type,$expires,$bin,$body,$description);
  } elseif( $op === 'delSC' ) {
    check_writable($service_id);
    $brl = brlgen(Def::BP_STATIC,$service_id,$template_id,$static_id,Beak::M_DEL);
    BeakController::beakSimpleQuery($brl);
  } elseif( $op === 'setPL' ) {
    check_writable($service_id);
    $layout = json_decode($_sP['layout'],true);
    if ( ! $layout ) {
      throw new \Exception('Fail to json_decode : ' . $_sP['layout']);
    }
    $brl = brlgen(Def::BP_LAYOUT,$service_id,$template_id,$page_id,Beak::M_GET);
    $page_layout = BeakController::beakSimpleQuery($brl);
    if ( ! $page_layout ) {
      throw new \Exception('Fail to get : ' . $brl);
    }
    $brl = brlgen(Def::BP_LAYOUT,$service_id,$template_id,$page_id,Beak::M_SET);
    $page_layout[Def::K_LAYOUT_LAYOUT] = $layout;
    $ret = BeakController::beakSimpleQuery($brl,$page_layout);
    if ( ! $ret ) {
      throw new \Exception('Fail to set : ' . $brl);
    }
  } else{
  }
} catch (\Exception $e) {
  $emsg .= $e->getMessage();
}

function getS(){
  $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,'',Beak::M_KEY_LIST);
  $service = BeakController::beakSimpleQuery($brl);
  if ( ! $service ) {
    throw new \Exception('Fail to get : ' . $brl);
  }
  return $service;
}
function getD($service_id){
  $brl = brlgen(Def::BP_LAYOUT,$service_id,'','',Beak::M_COL_LIST);
  $default_layout = BeakController::beakSimpleQuery($brl);
  if ( $default_layout === null ) {
    throw new \Exception('Fail to get : ' . $brl);
  }
  return $default_layout;
}
function getP($service_id,$template_id){
  $brl = brlgen(Def::BP_LAYOUT,$service_id,$template_id,'',Beak::M_KEY_LIST);
  $page_layout = BeakController::beakSimpleQuery($brl);
  if ( $page_layout === null ) {
    throw new \Exception('Fail to get : ' . $brl);
  }
  return $page_layout;
}

function setD($flg,$rev,$service_id,$template_id,$eredirect,$redirect,$css,$js,$session_exp,$expires_time,$header,$pheader,$bottom,$layout){
  if ( preg_match('@\s@',$template_id,$matches) !== 0 ) { 
    throw new \Exception('Cannot use blank-charactor as TEMPLATE : ' . $page_id);
  }
  if ( ! $flg ){
    $brl = brlgen(Def::BP_LAYOUT,$service_id,$template_id,'/',Beak::M_GET);
    $default_layout = BeakController::beakSimpleQuery($brl);
    if ( $default_layout) {
      throw new \Exception('Template already exist ! : ' . $template_id);
    } else {
      // layout
      $brl = brlgen(Def::BP_LAYOUT,$service_id,$template_id,'',Beak::M_CREATE_COL,array(),array(Beak::COMMENT_KIND_RENEW));
      $ret = BeakController::beakSimpleQuery($brl);
      // component
      $brl = brlgen(Def::BP_COMPONENT,$service_id,$template_id,'',Beak::M_CREATE_COL,array(),array(Beak::COMMENT_KIND_RENEW));
      $ret = BeakController::beakSimpleQuery($brl);
      // STATIC
      $brl = brlgen(Def::BP_STATIC,$service_id,$template_id,'',Beak::M_CREATE_COL,array(),array(Beak::COMMENT_KIND_RENEW));
      $ret = BeakController::beakSimpleQuery($brl);
      // session
      $brl = brlgen(Def::BP_SESSION,$service_id,$template_id,'',Beak::M_CREATE_COL,array(),array(Beak::COMMENT_KIND_RENEW));
      $ret = BeakController::beakSimpleQuery($brl);
    }
  }else{
  }
  $brl = brlgen(Def::BP_LAYOUT,$service_id,$template_id,'/',Beak::M_GET);
  $default_layout = BeakController::beakSimpleQuery($brl);
  $default_layout = $default_layout?$default_layout:array();
  $default_layout[Beak::ATTR_REV]            = $rev;
  $default_layout[Def::K_LAYOUT_EREDIRECT]   = $eredirect;
  $default_layout[Def::K_LAYOUT_REDIRECT]    = $redirect;
  if ( $layout ) {
    $default_layout[Def::K_LAYOUT_LAYOUT]    = $layout;
  }
  $default_layout[Def::K_LAYOUT_HEADER]      = $header;
  $default_layout[Def::K_LAYOUT_PHEADER]     = $pheader;
  $default_layout[Def::K_LAYOUT_BOTTOM]      = $bottom;
  $default_layout[Def::K_LAYOUT_SESSION_EXP] = $session_exp;
  $default_layout[Def::K_LAYOUT_EXPIRES]     = $expires_time;
  $brl = brlgen(Def::BP_LAYOUT,$service_id,$template_id,'/',Beak::M_SET,array(),array(Beak::COMMENT_KIND_REV));
  $ret = BeakController::beakSimpleQuery($brl,$default_layout);
  if ( ! $ret ) {
    throw new \Exception('Fail to set : ' . $brl);
  }
  // css
  $cssBrl = brlgen(Def::BP_STATIC,$service_id,$template_id,Config::CommonCSS,'');
  StaticContent::save($cssBrl,'text/css','',$css,null,$expires_time);
  // js
  $jsBrl = brlgen(Def::BP_STATIC,$service_id,$template_id,Config::CommonJs,'');
  StaticContent::save($jsBrl,'text/javascript','',$js,null,$expires_time);
}
function setP($flg,$rev,$service_id,$template_id,$page_id,$ctype,$eredirect,$redirect,$pre_action,$post_action,$session_exp,$expires_time,$header,$pheader,$bottom,$layout){
  if ( preg_match('@\s@',$page_id,$matches) !== 0 ) { 
    throw new \Exception('Cannot use blank-charactor as PAGE : ' . $page_id);
  }
  $data = array();
  $brl = brlgen(Def::BP_LAYOUT,$service_id,$template_id,$page_id,Beak::M_GET);
  $page_layout = BeakController::beakSimpleQuery($brl);
  if ( ! $flg and $page_layout) {
    throw new \Exception('Page already exist ! : ' . $page_id);
  }
  $page_layout[Beak::ATTR_REV]            = $rev;
  //$page_layout[Def::K_LAYOUT_CTYPE]       = $ctype;
  $page_layout[Def::K_LAYOUT_EREDIRECT]   = $eredirect;
  $page_layout[Def::K_LAYOUT_REDIRECT]    = $redirect;
  $page_layout[Def::K_LAYOUT_PRE_ACTION]  = $pre_action;
  $page_layout[Def::K_LAYOUT_POST_ACTION] = $post_action;
  $page_layout[Def::K_LAYOUT_SESSION_EXP] = $session_exp;
  $page_layout[Def::K_LAYOUT_EXPIRES]     = $expires_time;
  $page_layout[Def::K_LAYOUT_HEADER]      = $header;
  $page_layout[Def::K_LAYOUT_PHEADER]     = $pheader;
  $page_layout[Def::K_LAYOUT_BOTTOM]      = $bottom;
  if ( $layout ) {
    $page_layout[Def::K_LAYOUT_LAYOUT]    = $layout;
  }
  $brl = brlgen(Def::BP_LAYOUT,$service_id,$template_id,$page_id,Beak::M_SET,array(),array(Beak::COMMENT_KIND_REV));
  $ret = BeakController::beakSimpleQuery($brl,$page_layout);
  if ( ! $ret ) {
    throw new \Exception('Fail to set : ' . $brl);
  }
}
function setC($flg,$rev,$service_id,$component_id,$type,$subject,$description,$css,$js,$id,$class,$body,$actions,$header,$bottom) {
  if ( preg_match('@\s@',$component_id,$matches) !== 0 ) { 
    throw new \Exception('Cannot use blank-charactor as COMPONENT : ' . $component_id);
  }
  $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_DEFAULT,$component_id,Beak::M_GET);
  $component = BeakController::beakSimpleQuery($brl);
  if ( ! $flg and $component ) {
    throw new \Exception('Component already exist ! : ' . $component_id);
  }
  $component[Beak::ATTR_REV]               = $rev;
  $component[Def::K_COMPONENT_TYPE]        = $type;
  $component[Def::K_COMPONENT_SUBJECT]     = $subject;
  $component[Def::K_COMPONENT_DESCRIPTION] = $description;
  $component[Def::K_COMPONENT_CSS]         = $css;
  $component[Def::K_COMPONENT_JS]          = $js;
  $component[Def::K_COMPONENT_ID]          = $id;
  $component[Def::K_COMPONENT_CLASS]       = $class;
  $component[Def::K_COMPONENT_BODY]        = $body;
  $component[Def::K_COMPONENT_ACTION]      = $actions;
  $component[Def::K_COMPONENT_HEADER]      = $header;
  $component[Def::K_COMPONENT_BOTTOM]      = $bottom;
  $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_DEFAULT,$component_id,Beak::M_SET,array(),array(Beak::COMMENT_KIND_REV));
  $ret = BeakController::beakSimpleQuery($brl,$component);
  if ( ! $ret ) {
    throw new \Exception('Fail to set : ' . $brl);
  }
}

function setL($flg,$rev,$service_id,$layout_id,$type,$subject,$description,$layout) {
  if ( preg_match('@\s@',$layout_id,$matches) !== 0 ) { 
    throw new \Exception('Cannot use blank-charactor as LAYOUT : ' . $layout_id);
  }
  $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_LAYOUT,$layout_id,Beak::M_GET);
  $layoutwidget = BeakController::beakSimpleQuery($brl);
  if ( ! $flg and $layoutwidget ) {
    throw new \Exception('Component already exist ! : ' . $layout_id);
  }
  $layoutwidget[Beak::ATTR_REV]               = $rev;
  $layoutwidget[Def::K_COMPONENT_TYPE]        = $type;
  $layoutwidget[Def::K_COMPONENT_SUBJECT]     = $subject;
  $layoutwidget[Def::K_COMPONENT_DESCRIPTION] = $description;
  if ( $layout ) {
    $layoutwidget[Def::K_LAYOUT_LAYOUT]    = $layout;
  }
  $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_LAYOUT,$layout_id,Beak::M_SET,array(),array(Beak::COMMENT_KIND_REV));
  $ret = BeakController::beakSimpleQuery($brl,$layoutwidget);
  if ( ! $ret ) {
    throw new \Exception('Fail to set : ' . $brl);
  }
}

function setSC($flg,$rev,$service_id,$template_id,$static_id,$type,$expires,$bin,$data,$description) {
  if ( preg_match('@\s@',$static_id,$matches) !== 0 ) { 
    throw new \Exception('Cannot use blank-charactor as STATIC : ' . $static_id);
  }
  $brl = brlgen(Def::BP_STATIC,$service_id,$template_id,$static_id,Beak::M_GET);
  $static_content = BeakController::beakSimpleQuery($brl);
  if ( ! $flg and $static_content ) {
    throw new \Exception('Static already exist ! : ' . $static_id);
  }
  if ( $bin === 'true' ) {
    $data = $static_content[Def::K_STATIC_BIN];
  }
  $brl = brlgen(Def::BP_STATIC,$service_id,$template_id,$static_id,'');
  $ret = StaticContent::save($brl,$type,$description,$data,null,$expires);
  if ( ! $ret ) {
    throw new \Exception('Fail to set : ' . $brl);
  }
}

function getRequired($check){
  $requires=array();
  if ( preg_match('@^(.+)\?|#@',$check,$matches) ){
    $check = $matches[1];
  }
  $service_ids = getS();
  foreach( $service_ids as $service_id ) {
    $template_ids = getD($service_id);
    foreach( $template_ids as $template_id ) {
      $page_ids = getP($service_id,$template_id);
      foreach( $page_ids as $page_id ) {
        if ( ! $page_id ){
          continue;
        }
        try { 
          $CONTENT_DRAWER = new ContentDrawer($service_id,$template_id,$page_id,null,null,null,Def::RenderingModeNORMAL);  
          $CONTENT_DRAWER->layout();
          $CONTENT_DRAWER->components();
          if ( $CONTENT_DRAWER->layoutDatas ) {
            foreach ( $CONTENT_DRAWER->layoutDatas as $b => $c ) {
              if ( preg_match('@^(.+)\?|#@',$b,$matches) ){
                $b = $matches[1];
              }
              if ( $b === $check ) {
                $requires []= " $CONTENT_DRAWER->layoutBrl";
              }
            }
          }
          foreach ( $CONTENT_DRAWER->componentDatas as $b => $c ) {
            if ( preg_match('@^(.+)\?|#@',$b,$matches) ){
              $b = $matches[1];
            }
            if ( $b === $check ) {
              $requires []= " $CONTENT_DRAWER->layoutBrl";
            }
          }
        } catch (\Exception $e) {
          $emsg .= $service_id.'/'.$template_id.'/'.$page_id . ' : ' . $e->getMessage() . "\n";
        }
      }
    }
  }
  return $requires;
}

if ( $emsg !== '' ) {
  $r['emsg'] = $emsg;
  $ret = json_encode($r);
  print $ret;
}elseif ( count($r) > 0 ) {
  $ret = json_encode($r);
  print $ret;
}else {
  print "[]";
}
