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
$COCKATOO_CONF=getenv('COCKATOO_CONF');
require_once($COCKATOO_CONF);
require_once(Config::$COCKATOO_ROOT.'wwwutils/core/cms_acl.php');
require_once(Config::$COCKATOO_ROOT.'wwwutils/core/webutils.php');
require_once(Config::$COCKATOO_ROOT.'wwwutils/core/widget.php');
require_once(Config::$COCKATOO_ROOT.'wwwutils/core/content.php');
require_once(Config::$COCKATOO_ROOT.'utils/beak.php');
require_once(Config::$COCKATOO_ROOT.'utils/stcontents.php');

$_sP = $_POST;
$_sG = $_GET;

$op   = $_sP['op'];
  
$sid  = $_sP['sid'];
$aid  = $_sP['aid'];
$role = $_sP['role'];
$r;
$emsg='';
try {
  if ( $op === 'getS' ) {
    $sids = getS();
    foreach ( $sids as $sid){
      if ( is_readable($sid) ){
        if ( ! $sid ) {
          $r [] = array('sid' => $sid , 'name' => 'ADMIN');
        }else{
          $r [] = array('sid' => $sid , 'name' => $sid);
        }
      }
    }
  } elseif( $op === 'addS' ) {
    check_admin();
    $sid = $_sP['name'];
    $account = get_account();
    set_auth($sid,$account,CmsAuth::ADMIN);
  } elseif( $op === 'delS' ) {
    check_admin();
    $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,$sid,Beak::M_DEL);
    $ret = BeakController::beakQuery(array($brl));
    if ( ! $ret[$brl] ) {
      throw new \Exception('Fail to del : ' . $brl);
    }
  } elseif( $op === 'getA' ) {
    if ( is_readable($sid) ){
      $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,$sid,Beak::M_GET);
      $ret = BeakController::beakQuery(array($brl));
      if ( $ret[$brl] ) {
        foreach ( $ret[$brl]['account'] as $k => $v ) {
          $r[] = array(
            'aid'  => $k,
            'name' => $k,
            'role' => $v
            );
        }
      }
    }
  } elseif( $op === 'addA' ) {
    check_admin($sid);
    $aid = $_sP['name'];
    set_auth($sid,$aid,$role);
  } elseif( $op === 'delA' ) {
    check_admin($sid);
    set_auth($sid,$aid);
  } elseif( $op === 'setA' ) {
    check_admin($sid);
    set_auth($sid,$aid,$role);
  }
} catch (\Exception $e) {
  $emsg .= $e->getMessage();
}
function getS(){
  $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,'',Beak::M_KEY_LIST);
  $ret = BeakController::beakQuery(array($brl));
  if ( ! $ret[$brl] ) {
    throw new \Exception('Fail to get : ' . $brl);
  }
  return $ret[$brl];
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

