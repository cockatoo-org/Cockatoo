<?php
namespace Cockatoo;
require_once(dirname(__FILE__) . '/../def.php');

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

function setD($flg,$rev,$service_id,$template_id,$eredirect,$redirect,$css,$js,$pre_action,$post_action,$session_exp,$expires_time,$header,$pheader,$bottom,$layout){
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
  $default_layout[Def::K_LAYOUT_PRE_ACTION]  = $pre_action;
  $default_layout[Def::K_LAYOUT_POST_ACTION] = $post_action;
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
  $subject = ($subject?$subject:$component_id);

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
