<?php
/**
 * cms_ajax_admin.php - CMS
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
$aid  = $_sP['aid'];
$role = $_sP['role'];
$r;
$emsg='';
try {
  if ( $op === 'getS' ) {
    $service_ids = getS();
    foreach ( $service_ids as $service_id){
      if ( is_readable($service_id) ){
        if ( $service_id === '' ) {
          $r [] = array('service_id' => $service_id , 'name' => 'ADMIN');
        }else{
          $r [] = array('service_id' => $service_id , 'name' => $service_id);
        }
      }
    }
  } elseif( $op === 'addS' ) {
    check_admin('');
    $service_id = $_sP['name'];
    $account = get_account();
    set_auth($service_id,$account,CmsAuth::ADMIN);
  } elseif( $op === 'delS' ) {
    check_admin('');
    $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,$service_id,Beak::M_DEL);
    $ret = BeakController::beakSimpleQuery($brl);
    if ( ! $ret ) {
      throw new \Exception('Fail to del : ' . $brl);
    }
  } elseif( $op === 'getA' ) {
    if ( is_readable($service_id) ){
      $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,$service_id,Beak::M_GET);
      $service = BeakController::beakSimpleQuery($brl);
      if ( $service ) {
        foreach ( $service['account'] as $k => $v ) {
          $r[] = array(
            'aid'  => $k,
            'name' => $k,
            'role' => $v
            );
        }
      }
    }
  } elseif( $op === 'addA' ) {
    check_admin($service_id);
    $aid = $_sP['name'];
    set_auth($service_id,$aid,$role);
  } elseif( $op === 'delA' ) {
    check_admin($service_id);
    set_auth($service_id,$aid);
  } elseif( $op === 'setA' ) {
    check_admin($service_id);
    set_auth($service_id,$aid,$role);
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

