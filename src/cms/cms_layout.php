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
list($SERVICE,$DEVICE,$PATH,$ARGS,$REQUEST_PARSER,$DEVICE_SELECTOR) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);

$SCRIPT='';
  
try {
  $COMPONENTS_DRAWERS = array();

  {
    // core component
    $BASIC_CONTANT_DRAWER = new ContentDrawer(Def::RESERVED_SERVICE_CORE,'','/',null,null,null,Def::RenderingModeCMSTEMPLATE);  
    $brl = brlgen(Def::BP_COMPONENT,Def::RESERVED_SERVICE_CORE,Def::RESERVED_DEVICE_DEFAULT,'',Beak::M_KEY_LIST);
    $ret = BeakController::beakQuery(array($brl));
    $children = array();
    foreach ( $ret[$brl] as $p){
      if ( preg_match('@[^/]$@',$p,$matches) !== 0 ) {
        if ( strcmp($p,'pagelayout')){
          $pid = $p;
          $children []= array(Def::K_LAYOUT_COMPONENT => brlgen(Def::BP_COMPONENT,Def::RESERVED_SERVICE_CORE,Def::RESERVED_DEVICE_DEFAULT,$pid,Beak::M_GET),
                              Def::K_LAYOUT_EXTRA    => '' ,
                              Def::K_LAYOUT_CHILDREN => array());
        }
      }
    }
    $layoutData = array( 
      Def::K_LAYOUT_PRE_ACTION => null,
      Def::K_LAYOUT_POST_ACTION => null,
      Def::K_LAYOUT_SESSION_EXP => '0',
      Def::K_LAYOUT_LAYOUT => array(Def::K_LAYOUT_COMPONENT => brlgen(Def::BP_COMPONENT,Def::RESERVED_SERVICE_CORE,Def::RESERVED_DEVICE_DEFAULT,'.ghost',''),
                        Def::K_LAYOUT_EXTRA => '' ,
                        Def::K_LAYOUT_CHILDREN => $children)
      );
    // @@@ sort $layoutData by type ?
    $BASIC_CONTANT_DRAWER->layout($layoutData);  
    $BASIC_CONTANT_DRAWER->components();  
  }
  $COMPONENTS_DRAWERS []= $BASIC_CONTANT_DRAWER;
  $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,'',Beak::M_KEY_LIST);
  $ret = BeakController::beakQuery(array($brl));
  foreach ( $ret[$brl] as $sid){
    if ( is_readable($sid) ){
      if (strcmp($sid,Def::RESERVED_SERVICE_CORE)===0){
        continue;
      }
      // service component
      $COMPONENTS_DRAWER = new ContentDrawer($sid,'','/',null,null,null,Def::RenderingModeCMSTEMPLATE);  $brl = brlgen(Def::BP_COMPONENT,$sid,Def::RESERVED_DEVICE_DEFAULT,'',Beak::M_KEY_LIST);
      $ret = BeakController::beakQuery(array($brl));
      $children = array();
      if ( $ret[$brl] ) {
        foreach ( $ret[$brl] as $p){
          if ( preg_match('@[^/]$@',$p,$matches) !== 0 ) {
            $pid = $p;
            $children []= array(Def::K_LAYOUT_COMPONENT => brlgen(Def::BP_COMPONENT,$sid,Def::RESERVED_DEVICE_DEFAULT,$pid,Beak::M_GET),
                                Def::K_LAYOUT_EXTRA => '' ,
                                Def::K_LAYOUT_CHILDREN => array());
          }
        }
        // @@@ sort $layoutData by type ?
        usort($children,'Cockatoo\componentSorter');
      }
      $layoutData = array( 
        Def::K_LAYOUT_PRE_ACTION => null,
        Def::K_LAYOUT_POST_ACTION => null,
        Def::K_LAYOUT_SESSION_EXP => '0',
        Def::K_LAYOUT_LAYOUT => array(Def::K_LAYOUT_COMPONENT => brlgen(Def::BP_COMPONENT,Def::RESERVED_SERVICE_CORE,Def::RESERVED_DEVICE_DEFAULT,'.ghost',''),
                                      Def::K_LAYOUT_EXTRA => '' ,
                                      Def::K_LAYOUT_CHILDREN => $children)
        );
      $COMPONENTS_DRAWER->layout($layoutData);  
      $COMPONENTS_DRAWER->components();

      $COMPONENTS_DRAWERS []= $COMPONENTS_DRAWER;
    }
  }
/*
  {
    // service component
    $COMPONENTS_DRAWER = new ContentDrawer($SERVICE,'','/',null,null,null,Def::RenderingModeCMSTEMPLATE);  
    $brl = brlgen(Def::BP_COMPONENT,$SERVICE,Def::RESERVED_DEVICE_DEFAULT,'',Beak::M_KEY_LIST);
    $ret = BeakController::beakQuery(array($brl));
    $children = array();
    if ( $ret[$brl] ) {
      foreach ( $ret[$brl] as $p){
        if ( preg_match('@[^/]$@',$p,$matches) !== 0 ) {
          $pid = $p;
          $children []= array(Def::K_LAYOUT_COMPONENT => brlgen(Def::BP_COMPONENT,$SERVICE,Def::RESERVED_DEVICE_DEFAULT,$pid,Beak::M_GET),
                              Def::K_LAYOUT_EXTRA => '' ,
                              Def::K_LAYOUT_CHILDREN => array());
        }
      }
      // @@@ sort $layoutData by type ?
      usort($children,'Cockatoo\componentSorter');
    }
    $layoutData = array( 
      Def::K_LAYOUT_PRE_ACTION => null,
      Def::K_LAYOUT_POST_ACTION => null,
      Def::K_LAYOUT_SESSION_EXP => '0',
      Def::K_LAYOUT_LAYOUT => array(Def::K_LAYOUT_COMPONENT => brlgen(Def::BP_COMPONENT,'core',Def::RESERVED_DEVICE_DEFAULT,'.ghost',''),
                        Def::K_LAYOUT_EXTRA => '' ,
                        Def::K_LAYOUT_CHILDREN => $children)
      );
    $COMPONENTS_DRAWER->layout($layoutData);  
    $COMPONENTS_DRAWER->components();
  }
*/
  {
    // page
    $CONTENT_DRAWER = new ContentDrawer($SERVICE,$DEVICE,$PATH,null,$REQUEST_PARSER,$DEVICE_SELECTOR,Def::RenderingModeCMS);  
    $brl = brlgen(Def::BP_LAYOUT,$SERVICE,$DEVICE,$PATH,Beak::M_GET);
    $ret = BeakController::beakQuery(array($brl));
    $CONTENT_DRAWER->layout($ret[$brl]);
    $CONTENT_DRAWER->components();
  }
  Include 'wwwutils/core/cms_frame.php';
}catch ( \Exception $ex ) {
  print $ex->getMessage();
}

return;

function componentSorter($a,$b){
  return strcmp($a[Def::K_LAYOUT_COMPONENT] , $b[Def::K_LAYOUT_COMPONENT]);
}
