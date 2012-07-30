<?php
/**
 * cms_layout.php - CMS
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
require_once(Config::COCKATOO_ROOT.'wwwutils/core/reqparser.php');
require_once(Config::COCKATOO_ROOT.'wwwutils/core/webutils.php');
require_once(Config::COCKATOO_ROOT.'wwwutils/core/widget.php');
require_once(Config::COCKATOO_ROOT.'wwwutils/core/content.php');
require_once(Config::COCKATOO_ROOT.'utils/beak.php');
  
$HEADER  = getallheaders();
list($SERVICE,$TEMPLATE,$PATH,$ARGS,$REQUEST_PARSER,$TEMPLATE_SELECTOR) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);

$SCRIPT='';

try {
  $COMPONENTS_DRAWERS = array();
  {
    // core component
    $BASIC_CONTANT_DRAWER = new ContentDrawer(Def::RESERVED_SERVICE_CORE,'','/',null,null,null,Def::RenderingModeCMSTEMPLATE);  
    $brl = brlgen(Def::BP_COMPONENT,Def::RESERVED_SERVICE_CORE,Def::RESERVED_TEMPLATE_DEFAULT,'',Beak::M_KEY_LIST);
    $components = BeakController::beakSimpleQuery($brl);
    $children = array();
    foreach ( $components as $p){
      if ( preg_match('@[^/]$@',$p,$matches) !== 0 ) {
//        if ( strcmp($p,'pagelayout')){
          $page_id = $p;
          $children []= layoutChildren(Def::RESERVED_SERVICE_CORE,Def::RESERVED_TEMPLATE_DEFAULT,$page_id,'',array());
      }
    }
    $layoutData = array( 
      Def::K_LAYOUT_PRE_ACTION => null,
      Def::K_LAYOUT_POST_ACTION => null,
      Def::K_LAYOUT_SESSION_EXP => '0',
      Def::K_LAYOUT_LAYOUT => layoutChildren(Def::RESERVED_SERVICE_CORE,Def::RESERVED_TEMPLATE_DEFAULT,'.ghost','',$children),
      );
    // @@@ sort $layoutData by type ?
    $BASIC_CONTANT_DRAWER->layout($layoutData);  
    $BASIC_CONTANT_DRAWER->components();  
  }
  $COMPONENTS_DRAWERS []= $BASIC_CONTANT_DRAWER;
  $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,'',Beak::M_KEY_LIST);
  $services = BeakController::beakSimpleQuery($brl);
  foreach ( $services as $service_id){
    if ( ! $service_id ) {
      continue;
    }
    if ( is_readable($service_id) ){
      if (strcmp($service_id,Def::RESERVED_SERVICE_CORE)===0){
        continue;
      }
      // service component
      $COMPONENTS_DRAWER = new ContentDrawer($service_id,'','/',null,null,null,Def::RenderingModeCMSTEMPLATE);
      $brl = brlgen(Def::BP_COMPONENT,$service_id,Def::RESERVED_TEMPLATE_DEFAULT,'',Beak::M_KEY_LIST);
      $components = BeakController::beakSimpleQuery($brl);
      $children = array();
      if ( $components ) {
        foreach ( $components as $p){
          if ( preg_match('@[^/]$@',$p,$matches) !== 0 ) {
            $page_id = $p;
            $children []= layoutChildren($service_id,Def::RESERVED_TEMPLATE_DEFAULT,$page_id,'',array());
          }
        }
        // @@@ sort $layoutData by type ?
        usort($children,'Cockatoo\componentSorter');
      }
      $layoutData = array( 
        Def::K_LAYOUT_PRE_ACTION => null,
        Def::K_LAYOUT_POST_ACTION => null,
        Def::K_LAYOUT_SESSION_EXP => '0',
        Def::K_LAYOUT_LAYOUT => layoutChildren(Def::RESERVED_SERVICE_CORE,Def::RESERVED_TEMPLATE_DEFAULT,'.ghost','',$children),
        );
      $COMPONENTS_DRAWER->layout($layoutData);  
      $COMPONENTS_DRAWER->components();

      $COMPONENTS_DRAWERS []= $COMPONENTS_DRAWER;
    }
  }
  // page
  $CONTENT_DRAWER = new ContentDrawer($SERVICE,$TEMPLATE,$PATH,null,$REQUEST_PARSER,$TEMPLATE_SELECTOR,Def::RenderingModeCMS);  
  $brl = brlgen(Def::BP_LAYOUT,$SERVICE,$TEMPLATE,$PATH,Beak::M_GET);
  $page_layout = BeakController::beakSimpleQuery($brl);
  $CONTENT_DRAWER->layout($page_layout);
  $CONTENT_DRAWER->components();
  $OP = 'setL';
  Include Config::COCKATOO_ROOT.'wwwutils/core/cms_frame.php';
}catch ( \Exception $ex ) {
  print $ex->getMessage();
}

return;

function componentSorter($a,$b){
  return strcmp($a[Def::K_LAYOUT_COMPONENT] , $b[Def::K_LAYOUT_COMPONENT]);
}
function layoutChildren($service,$template,$path,$extra,$children){
  return array(Def::K_LAYOUT_COMPONENT => brlgen(Def::BP_COMPONENT,$service,$template,$path,''),
               Def::K_LAYOUT_EXTRA    => $extra ,
               Def::K_LAYOUT_CHILDREN => $children);
}
