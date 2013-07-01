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
require_once(Config::COCKATOO_ROOT.'cmsutils/cms_core.php');
require_once(Config::COCKATOO_ROOT.'cmsutils/cms_acl.php');
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
        $pre_action = $default_layout[Def::K_LAYOUT_PRE_ACTION];
        $post_action= $default_layout[Def::K_LAYOUT_POST_ACTION];
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
                     'pre_action' => $pre_action,
                     'post_action' => $post_action,
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
    setD(false,$rev,$service_id,$template_id,$eredirect,$redirect,$css,$js,$pre_action,$post_action,$session_exp,$expires_time,$header,$pheader,$bottom,$layout);
  } elseif( $op === 'setD' ) {
    check_writable($service_id);
    setD(true,$rev,$service_id,$template_id,$eredirect,$redirect,$css,$js,$pre_action,$post_action,$session_exp,$expires_time,$header,$pheader,$bottom,null);
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
      $contents .= " * $CONTENT_DRAWER->globalPreAction\n";
      $contents .= " * $CONTENT_DRAWER->preAction\n";
      foreach ( $CONTENT_DRAWER->componentDatas as $b => $c ) {
        $contents .= " - $b\n";
        if ( $c[Def::K_COMPONENT_ACTION] ){
          foreach ( $c[Def::K_COMPONENT_ACTION] as $a ) {
            if ( $a ) {
              $contents .= "      $a\n";
            }
          }
        }
      }
      $contents .= " * $CONTENT_DRAWER->postAction\n";
      $contents .= " * $CONTENT_DRAWER->globalPostAction\n";
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
