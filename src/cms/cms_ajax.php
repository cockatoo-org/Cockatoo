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
  
$sid  = $_sP['sid'];
$did  = $_sP['did'];
$pid  = $_sP['pid'];
$cid  = $_sP['cid'];
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
foreach ( explode("\n",$_sP['pheader']) as $p ) {
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
$actions      = explode("\n",$_sP['actions']);
$check        = $_sP['brl'];
$r;
$emsg='';
try {
  if ( $op === 'getS' ) {
    $sids = getS();
    foreach ( $sids as $sid){
      if ( is_readable($sid) ){
        $r [] = array('sid' => $sid , 'name' => $sid);
      }
    }
  } elseif( $op === 'getD' ) {
    if ( is_readable($sid) ){
      $dids = getD($sid);
      foreach ( $dids as $did){
        // layout
        $brl = brlgen(Def::BP_LAYOUT,$sid,$did,'/',Beak::M_GET);
        $default_layout = BeakController::beakSimpleQuery($brl);
        $rev        = $default_layout[Beak::ATTR_REV];
        $eredirect  = $default_layout[Def::K_LAYOUT_EREDIRECT];
        $header     = $default_layout[Def::K_LAYOUT_HEADER];
        $pheader    = $default_layout[Def::K_LAYOUT_PHEADER];
        $bottom     = $default_layout[Def::K_LAYOUT_BOTTOM];
        $session_exp= $default_layout[Def::K_LAYOUT_SESSION_EXP];
        $expires    = $default_layout[Def::K_LAYOUT_EXPIRES];
        // css
        $cssBrl = brlgen(Def::BP_STATIC,$sid,$did,Config::CommonCSS,Beak::M_GET);
        $css = BeakController::beakSimpleQuery($cssBrl);
        $css = $css?$css[Def::K_STATIC_DATA]:'';
        // js
        $jsBrl = brlgen(Def::BP_STATIC,$sid,$did,Config::CommonJs,Beak::M_GET);
        $js = BeakController::beakSimpleQuery($jsBrl);
        $js = $js?$js[Def::K_STATIC_DATA]:'';
        $r []= array('rev' => $rev,
                     'sid' => $sid ,
                     'did' => $did ,
                     'name' => $did ,
                     'eredirect' => $eredirect,
                     'css' => $css ,
                     'js' => $js,
                     'session' => $session_exp,
                     'session_exp' => $session_exp,
                     'expires'      => $expires,
                     'expires_time' => $expires,
                     'header' => $header,
                     'pheader' => $pheader,
                     'bottom' => $bottom,
                     'layout' => '<a target="_blank" href="cms_layout.php?'.Def::REQUEST_SERVICE.'=' . $sid . '&'.Def::REQUEST_DEVICE.'=' . $did . '&'.Def::REQUEST_PATH.'=/' . "" . '">'. "$did" .'</a>'
                    
          );
      }
    }
  } elseif( $op === 'addD' ) {
    check_writable($sid);
    $did = $_sP['device'];
    $layout = array(Def::K_LAYOUT_TYPE => 'HorizontalWidget' , Def::K_LAYOUT_COMPONENT => "component://core-component/default/horizontal#critical" , Def::K_LAYOUT_EXTRA => null ,  Def::K_LAYOUT_CHILDREN => array(
                      array(Def::K_LAYOUT_TYPE => 'PageLayout' , Def::K_LAYOUT_COMPONENT => "component://core-component/default/pagelayout" , Def::K_LAYOUT_EXTRA => null ,  Def::K_LAYOUT_CHILDREN => array())
                      ));
    setD(false,$rev,$sid,$did,$eredirect,$css,$js,$session_exp,$expires_time,$header,$pheader,$bottom,$layout);
  } elseif( $op === 'setD' ) {
    check_writable($sid);
    setD(true,$rev,$sid,$did,$eredirect,$css,$js,$session_exp,$expires_time,$header,$pheader,$bottom,null);
  } elseif( $op === 'getP' ) {
    if ( is_readable($sid) ){
      $pids = getP($sid,$did);
      foreach ( $pids as $pid){
        $r [] = array('sid' => $sid , 
                      'did' => $did , 
                      'pid' => $pid ,
                      'name' => $pid , 
                      'ctype' => '',
                      'page' => '<a target="_blank" href="/index.php?'.Def::REQUEST_SERVICE.'=' . $sid . '&'.Def::REQUEST_DEVICE.'=' . $did . '&'.Def::REQUEST_PATH.'=' . "/$pid"  . '">'. "/$pid" .'</a>',
                      'layout' => '<a target="_blank" href="cms_layout.php?'.Def::REQUEST_SERVICE.'=' . $sid . '&'.Def::REQUEST_DEVICE.'=' . $did . '&'.Def::REQUEST_PATH.'=' . "/$pid" .'">'. "/$pid".'</a>',
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
    if ( is_readable($sid) ){
      $CONTENT_DRAWER = new ContentDrawer($sid,$did,$pid,null,null,null,Def::RenderingModeNORMAL);  
      $CONTENT_DRAWER->layout();
      $CONTENT_DRAWER->components();
      $contents = '';
      $contents .= "$CONTENT_DRAWER->layoutBrl\n";
      $contents .= " * $CONTENT_DRAWER->preAction\n";
      foreach ( $CONTENT_DRAWER->componentData as $b => $c ) {
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
    check_writable($sid);
    $pid = $_sP['name'];
    $layout = array(Def::K_LAYOUT_TYPE => 'HorizontalWidget' , Def::K_LAYOUT_COMPONENT => "component://core-component/default/horizontal#critical" , Def::K_LAYOUT_EXTRA => null ,  Def::K_LAYOUT_CHILDREN => array());
    setP(false,$rev,$sid,$did,$pid,$ctype,$eredirect,$redirect,$pre_action,$post_action,$session_exp,$expires_time,$header,$pheader,$bottom,$layout);
  } elseif( $op === 'setP' ) {
    check_writable($sid);
    setP(true,$rev,$sid,$did,$pid,$ctype,$eredirect,$redirect,$pre_action,$post_action,$session_exp,$expires_time,$header,$pheader,$bottom,null);
  } elseif( $op === 'cpP' ) {
    check_writable($sid);
    $brl = brlgen(Def::BP_LAYOUT,$sid,$did,$pid,Beak::M_GET);
    $page_layout = BeakController::beakSimpleQuery($brl);
    $layout = $page_layout[Def::K_LAYOUT_LAYOUT];
    $pid = $_sP['name'];
    setP(false,$rev,$sid,$did,$pid,$ctype,$eredirect,$redirect,$pre_action,$post_action,$session_exp,$expires_time,$header,$pheader,$bottom,$layout);
  } elseif( $op === 'delP' ) {
    $brl = brlgen(Def::BP_LAYOUT,$sid,$did,$pid,Beak::M_DEL);
    BeakController::beakSimpleQuery($brl);
  } elseif( $op === 'getC' ) {
    if ( is_readable($sid) ){
      $brl = brlgen(Def::BP_COMPONENT,$sid,'default','',Beak::M_KEY_LIST);
      $components = BeakController::beakSimpleQuery($brl);
      $r = array();
      foreach ( $components as $c){
        $cid = $c;
        if ( preg_match('@[^/]$@',$c,$matches) !== 0 ) {
          $brl = brlgen(Def::BP_COMPONENT,$sid,'default',$cid,Beak::M_GET);
          $r [] = array('sid' => $sid ,
                        'cid' => $cid ,
                        'name' => $cid , 
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
    if ( is_readable($sid) ){
      $brl = brlgen(Def::BP_COMPONENT,$sid,'default',$cid,Beak::M_GET);
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
                 'css'         => $component[Def::K_COMPONENT_CSS]
        );
    }
  } elseif( $op === 'addC' ) {
    check_writable($sid);
    $cid = $_sP['name'];
    setC(false,$rev,$sid,$cid,$type,$subject,$description,$css,$js,$id,$class,$body,$actions);
  } elseif( $op === 'setC' ) {
    check_writable($sid);
    setC(true,$rev,$sid,$cid,$type,$subject,$description,$css,$js,$id,$class,$body,$actions);
  } elseif( $op === 'cpC' ) {
    check_writable($sid);
    $cid = $_sP['name'];
    setC(true,$rev,$sid,$cid,$type,$subject,$description,$css,$js,$id,$class,$body,$actions);
  } elseif( $op === 'checkC' ) {
    if ( is_readable($sid) ){
      $r['required'] = implode(getRequired($check),"\n");
    }
  } elseif( $op === 'delC' ) {
    check_writable($sid);
    $brl = brlgen(Def::BP_COMPONENT,$sid,'default',$cid,Beak::M_GET);
    $required = getRequired($brl);
    if ( $required ) {
      throw new \Exception('Still required by ' . implode($required,'<br>'));
    }
    $brl = brlgen(Def::BP_COMPONENT,$sid,'default',$cid,Beak::M_DEL);
    BeakController::beakSimpleQuery($brl);
  } elseif( $op === 'setL' ) {
    check_writable($sid);
    $layout = json_decode($_sP['layout'],true);
    if ( ! $layout ) {
      throw new \Exception('Fail to json_decode : ' . $_sP['layout']);
    }
    $brl = brlgen(Def::BP_LAYOUT,$sid,$did,$pid,Beak::M_GET);
    $page_layout = BeakController::beakSimpleQuery($brl);
    if ( ! $page_layout ) {
      throw new \Exception('Fail to get : ' . $brl);
    }
    $brl = brlgen(Def::BP_LAYOUT,$sid,$did,$pid,Beak::M_SET);
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
function getD($sid){
  $brl = brlgen(Def::BP_LAYOUT,$sid,'','',Beak::M_COL_LIST);
  $default_layout = BeakController::beakSimpleQuery($brl);
  if ( $default_layout === null ) {
    throw new \Exception('Fail to get : ' . $brl);
  }
  return $default_layout;
}
function getP($sid,$did){
  $brl = brlgen(Def::BP_LAYOUT,$sid,$did,'',Beak::M_KEY_LIST);
  $page_layout = BeakController::beakSimpleQuery($brl);
  if ( $page_layout === null ) {
    throw new \Exception('Fail to get : ' . $brl);
  }
  return $page_layout;
}

function setD($flg,$rev,$sid,$did,$eredirect,$css,$js,$session_exp,$expires_time,$header,$pheader,$bottom,$layout){
  if ( preg_match('@\s@',$did,$matches) !== 0 ) { 
    throw new \Exception('Cannot use blank-charactor as DEVICE : ' . $pid);
  }
  if ( ! $flg ){
    $brl = brlgen(Def::BP_LAYOUT,$sid,$did,'/',Beak::M_GET);
    $default_layout = BeakController::beakSimpleQuery($brl);
    if ( $default_layout) {
      throw new \Exception('Device already exist ! : ' . $did);
    } else {
      // layout
      $brl = brlgen(Def::BP_LAYOUT,$sid,$did,'',Beak::M_CREATE_COL,array(),array(Beak::COMMENT_KIND_RENEW));
      $ret = BeakController::beakSimpleQuery($brl);
      // component
      $brl = brlgen(Def::BP_COMPONENT,$sid,$did,'',Beak::M_CREATE_COL,array(),array(Beak::COMMENT_KIND_RENEW));
      $ret = BeakController::beakSimpleQuery($brl);
      // STATIC
      $brl = brlgen(Def::BP_STATIC,$sid,$did,'',Beak::M_CREATE_COL,array(),array(Beak::COMMENT_KIND_RENEW));
      $ret = BeakController::beakSimpleQuery($brl);
      // session
      $brl = brlgen(Def::BP_SESSION,$sid,$did,'',Beak::M_CREATE_COL,array(),array(Beak::COMMENT_KIND_RENEW));
      $ret = BeakController::beakSimpleQuery($brl);
    }
  }else{
  }
  $brl = brlgen(Def::BP_LAYOUT,$sid,$did,'/',Beak::M_GET);
  $default_layout = BeakController::beakSimpleQuery($brl);
  $default_layout = $default_layout?$default_layout:array();
  $default_layout[Beak::ATTR_REV]            = $rev;
  $default_layout[Def::K_LAYOUT_EREDIRECT]   = $eredirect;
  if ( $layout ) {
    $default_layout[Def::K_LAYOUT_LAYOUT]    = $layout;
  }
  $default_layout[Def::K_LAYOUT_HEADER]      = $header;
  $default_layout[Def::K_LAYOUT_PHEADER]     = $pheader;
  $default_layout[Def::K_LAYOUT_BOTTOM]      = $bottom;
  $default_layout[Def::K_LAYOUT_SESSION_EXP] = $session_exp;
  $default_layout[Def::K_LAYOUT_EXPIRES]     = $expires_time;
  $brl = brlgen(Def::BP_LAYOUT,$sid,$did,'/',Beak::M_SET,array(),array(Beak::COMMENT_KIND_REV));
  $ret = BeakController::beakSimpleQuery($brl,$default_layout);
  if ( ! $ret ) {
    throw new \Exception('Fail to set : ' . $brl);
  }
  // css
  $cssBrl = brlgen(Def::BP_STATIC,$sid,$did,Config::CommonCSS,'');
  StaticContent::save($cssBrl,'text/css','',$css,null,$expires_time);
  // js
  $jsBrl = brlgen(Def::BP_STATIC,$sid,$did,Config::CommonJs,'');
  StaticContent::save($jsBrl,'text/javascript','',$js,null,$expires_time);
}
function setP($flg,$rev,$sid,$did,$pid,$ctype,$eredirect,$redirect,$pre_action,$post_action,$session_exp,$expires_time,$header,$pheader,$bottom,$layout){
  if ( preg_match('@\s@',$pid,$matches) !== 0 ) { 
    throw new \Exception('Cannot use blank-charactor as PAGE : ' . $pid);
  }
  $data = array();
  $brl = brlgen(Def::BP_LAYOUT,$sid,$did,$pid,Beak::M_GET);
  $page_layout = BeakController::beakSimpleQuery($brl);
  if ( ! $flg and $page_layout) {
    throw new \Exception('Page already exist ! : ' . $pid);
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
  $brl = brlgen(Def::BP_LAYOUT,$sid,$did,$pid,Beak::M_SET,array(),array(Beak::COMMENT_KIND_REV));
  $ret = BeakController::beakSimpleQuery($brl,$page_layout);
  if ( ! $ret ) {
    throw new \Exception('Fail to set : ' . $brl);
  }
}
function setC($flg,$rev,$sid,$cid,$type,$subject,$description,$css,$js,$id,$class,$body,$actions) {
  if ( preg_match('@\s@',$cid,$matches) !== 0 ) { 
    throw new \Exception('Cannot use blank-charactor as COMPONENT : ' . $cid);
  }
  $brl = brlgen(Def::BP_COMPONENT,$sid,'default',$cid,Beak::M_GET);
  $component = BeakController::beakSimpleQuery($brl);
  if ( ! $flg and $component ) {
    throw new \Exception('Component already exist ! : ' . $cid);
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
  $brl = brlgen(Def::BP_COMPONENT,$sid,'default',$cid,Beak::M_SET,array(),array(Beak::COMMENT_KIND_REV));
  $ret = BeakController::beakSimpleQuery($brl,$component);
  if ( ! $ret ) {
    throw new \Exception('Fail to set : ' . $brl);
  }
}
function getRequired($check){
  $requires=array();
  if ( preg_match('@^(.+)\?|#@',$check,$matches) ){
    $check = $matches[1];
  }
  $sids = getS();
  foreach( $sids as $sid ) {
    $dids = getD($sid);
    foreach( $dids as $did ) {
      $pids = getP($sid,$did);
      foreach( $pids as $pid ) {
        if ( ! $pid ){
          continue;
        }
        try { 
          $CONTENT_DRAWER = new ContentDrawer($sid,$did,$pid,null,null,null,Def::RenderingModeNORMAL);  
          $CONTENT_DRAWER->layout();
          $CONTENT_DRAWER->components();
          foreach ( $CONTENT_DRAWER->componentData as $b => $c ) {
            if ( preg_match('@^(.+)\?|#@',$b,$matches) ){
              $b = $matches[1];
            }
            if ( $b === $check ) {
              $requires []= " $CONTENT_DRAWER->layoutBrl";
            }
          }
        } catch (\Exception $e) {
          $emsg .= $sid.'/'.$did.'/'.$pid . ' : ' . $e->getMessage() . "\n";
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
