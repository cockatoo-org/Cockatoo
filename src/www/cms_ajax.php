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
$COCKATOO_CONF=getenv('COCKATOO_CONF');
require_once($COCKATOO_CONF);
require_once($COCKATOO_ROOT.'wwwutils/core/webutils.php');
require_once($COCKATOO_ROOT.'wwwutils/core/widget.php');
require_once($COCKATOO_ROOT.'wwwutils/core/content.php');
require_once($COCKATOO_ROOT.'utils/beak.php');
require_once($COCKATOO_ROOT.'utils/stcontents.php');

$_sP = $_POST;
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
$pheader = '';
foreach ( explode("\n",$_sP['pheader']) as $p ) {
  if ( preg_match('@^\s*$@',$p,$matches) === 0 ) {
    $pheader .= $p . "\n";
  }
}
$session      = $_sP['session'];
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
$r;
$emsg='';

try {
  if ( $op === 'getS' ) {
    $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,'',Beak::M_KEY_LIST);
    $ret = BeakController::beakQuery(array($brl));
    foreach ( $ret[$brl] as $sid){
      $r [] = array('sid' => $sid , 'name' => $sid);
    }
  } elseif( $op === 'addS' ) {
    // addD ('default')
    $sid = $_sP['name'];
    $did = 'default';
    $layout = array(Def::K_LAYOUT_TYPE => 'HorizontalWidget' , Def::K_LAYOUT_COMPONENT => "component://core-component/default/horizontal#critical" , Def::K_LAYOUT_EXTRA => null ,  Def::K_LAYOUT_CHILDREN => array(
                      array(Def::K_LAYOUT_TYPE => 'PageLayout' , Def::K_LAYOUT_COMPONENT => "component://core-component/default/pagelayout" , Def::K_LAYOUT_EXTRA => null ,  Def::K_LAYOUT_CHILDREN => array())
                      ));
    $header='<meta http-equiv="content-type" content="text/html; charset=utf-8">' . "\n" .
      '<meta http-equiv="content-style-type" content="text/css">' . "\n" .
      '<meta http-equiv="content-script-type" content="text/javascript">' . "\n";
    setD(false,$rev,$sid,$did,''        ,''  ,'' ,$header,'',$layout);
    $brl = brlgen(Def::BP_CMS,Def::CMS_SERVICES,Def::CMS_SERVICES,$sid,Beak::M_SET);
    $ret = BeakController::beakQuery(array($brl));
    if ( ! $ret[$brl] ) {
      throw new \Exception('Fail to set : ' . $brl);
    }
  } elseif( $op === 'getD' ) {
    $brl = brlgen(Def::BP_LAYOUT,$sid,'','',Beak::M_COL_LIST);
    $ret = BeakController::beakQuery(array($brl));
    $r = array();
    foreach ( $ret[$brl] as $did){
      // layout
      $brl = brlgen(Def::BP_LAYOUT,$sid,$did,'/',Beak::M_GET);
      $data = BeakController::beakQuery(array($brl));
      $data = &$data[$brl];
      $rev = $data[Beak::ATTR_REV];
      $eredirect = $data[Def::K_LAYOUT_EREDIRECT];
      $header = $data[Def::K_LAYOUT_HEADER];
      $pheader = $data[Def::K_LAYOUT_PHEADER];
      $expires = $data[Def::K_LAYOUT_EXPIRES];
      // css
      $cssBrl = brlgen(Def::BP_STATIC,$sid,$did,Config::CommonCSS,Beak::M_GET);
      $cssRet = BeakController::beakQuery(array($cssBrl));
      $css = $cssRet[$cssBrl];
      $css = $css?$css[Def::K_STATIC_DATA]:'';
      // js
      $jsBrl = brlgen(Def::BP_STATIC,$sid,$did,Config::CommonJs,Beak::M_GET);
      $jsRet = BeakController::beakQuery(array($jsBrl));
      $js = $jsRet[$jsBrl];
      $js = $js?$js[Def::K_STATIC_DATA]:'';
      $r []= array('rev' => $rev,
                   'sid' => $sid ,
                   'did' => $did ,
                   'name' => $did ,
                   'eredirect' => $eredirect,
                   'css' => $css ,
                   'js' => $js,
                   'expires'      => ($expires<=0)?'false':'true',
                   'expires_time' => $expires,
                   'header' => $header,
                   'pheader' => $pheader,
                   'layout' => '<a target="_blank" href="/cms_layout.php?'.Def::REQUEST_SERVICE.'=' . $sid . '&'.Def::REQUEST_DEVICE.'=' . $did . '&'.Def::REQUEST_PATH.'=/' . "" . '">'. "$did" .'</a>'
                    
        );
    }
  } elseif( $op === 'addD' ) {
    $did = $_sP['device'];
    $layout = array(Def::K_LAYOUT_TYPE => 'HorizontalWidget' , Def::K_LAYOUT_COMPONENT => "component://core-component/default/horizontal#critical" , Def::K_LAYOUT_EXTRA => null ,  Def::K_LAYOUT_CHILDREN => array(
                      array(Def::K_LAYOUT_TYPE => 'PageLayout' , Def::K_LAYOUT_COMPONENT => "component://core-component/default/pagelayout" , Def::K_LAYOUT_EXTRA => null ,  Def::K_LAYOUT_CHILDREN => array())
                      ));
    setD(false,$rev,$sid,$did,$eredirect,$css,$js,$expires,$expires_time,$header,$pheader,$layout);
  } elseif( $op === 'setD' ) {
    setD(true,$rev,$sid,$did,$eredirect,$css,$js,$expires,$expires_time,$header,$pheader,null);
  } elseif( $op === 'getP' ) {
    $brl = brlgen(Def::BP_LAYOUT,$sid,$did,'',Beak::M_KEY_LIST);
    $ret = BeakController::beakQuery(array($brl));
    $r = array();
    foreach ( $ret[$brl] as $p){
      $pid = $p;
//       if ( preg_match('@[^/]$@',$p,$matches) !== 0 ) {
        $r [] = array('sid' => $sid , 
                      'did' => $did , 
                      'pid' => $pid ,
                      'name' => $pid , 
                      'ctype' => '',
                      'page' => '<a target="_blank" href="/index.php?'.Def::REQUEST_SERVICE.'=' . $sid . '&'.Def::REQUEST_DEVICE.'=' . $did . '&'.Def::REQUEST_PATH.'=' . "/$pid"  . '">'. "/$pid" .'</a>',
                      'layout' => '<a target="_blank" href="/cms_layout.php?'.Def::REQUEST_SERVICE.'=' . $sid . '&'.Def::REQUEST_DEVICE.'=' . $did . '&'.Def::REQUEST_PATH.'=' . "/$pid" .'">'. "/$pid".'</a>',
                      'pre_action' => '',
                      'post_action' => '',
                      'session' => '',
                      'session_exp' => '',
                      'expires' => '',
                      'expires_time' => '',
                      'header' => '',
                      'pheader' => '',
                      'contents' => ''
          );
//       }
    }
  } elseif( $op === 'getPP' ) {
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
               'session'     => ($CONTENT_DRAWER->sessionExp<0)?'false':'true',
               'session_exp' => $CONTENT_DRAWER->sessionExp,
               'expires'      => ($CONTENT_DRAWER->expires<=0)?'false':'true',
               'expires_time' => $CONTENT_DRAWER->expires,
               'eredirect'   => $CONTENT_DRAWER->eredirect,
               'redirect'    => $CONTENT_DRAWER->redirect,
               'header'      => $CONTENT_DRAWER->layoutData[Def::K_LAYOUT_HEADER],
               'pheader'     => $CONTENT_DRAWER->layoutData[Def::K_LAYOUT_PHEADER],
               'contents'    => $contents
      );
  } elseif( $op === 'addP' ) {
    $pid = $_sP['name'];
    $layout = array(Def::K_LAYOUT_TYPE => 'HorizontalWidget' , Def::K_LAYOUT_COMPONENT => "component://core-component/default/horizontal#critical" , Def::K_LAYOUT_EXTRA => null ,  Def::K_LAYOUT_CHILDREN => array());
    setP(false,$rev,$sid,$did,$pid,$ctype,$eredirect,$redirect,$pre_action,$post_action,$session,$session_exp,$expires,$expires_time,$header,$pheader,$layout);
  } elseif( $op === 'setP' ) {
    setP(true,$rev,$sid,$did,$pid,$ctype,$eredirect,$redirect,$pre_action,$post_action,$session,$session_exp,$expires,$expires_time,$header,$pheader,null);
  } elseif( $op === 'getC' ) {
    $brl = brlgen(Def::BP_COMPONENT,$sid,'default','',Beak::M_KEY_LIST);
    $ret = BeakController::beakQuery(array($brl));
    $r = array();
    foreach ( $ret[$brl] as $c){
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
  } elseif( $op === 'getCC' ) {
    $brl = brlgen(Def::BP_COMPONENT,$sid,'default',$cid,Beak::M_GET);
    $ret = BeakController::beakQuery(array($brl));
    $cdata = &$ret[$brl];

    $r = array('rev'         => $cdata[Beak::ATTR_REV],
               'type'        => $cdata[Def::K_COMPONENT_TYPE],
               'subject'     => $cdata[Def::K_COMPONENT_SUBJECT],
               'description' => $cdata[Def::K_COMPONENT_DESCRIPTION],
               'id'          => $cdata[Def::K_COMPONENT_ID],
               'clazz'       => $cdata[Def::K_COMPONENT_CLASS],
               'body'        => $cdata[Def::K_COMPONENT_BODY],
               'actions'     => $cdata[Def::K_COMPONENT_ACTION]?join("\n",$cdata[Def::K_COMPONENT_ACTION]):'',
               'js'          => $cdata[Def::K_COMPONENT_JS],
               'css'         => $cdata[Def::K_COMPONENT_CSS]
      );
  } elseif( $op === 'addC' ) {
    $cid = $_sP['name'];
    setC(false,$rev,$sid,$cid,$type,$subject,$description,$css,$js,$id,$class,$body,$actions);
  } elseif( $op === 'setC' ) {
    setC(true,$rev,$sid,$cid,$type,$subject,$description,$css,$js,$id,$class,$body,$actions);
  } else{
  }
} catch (\Exception $e) {
  $emsg = $e->getMessage();
}

function setD($flg,$rev,$sid,$did,$eredirect,$css,$js,$expires,$expires_time,$header,$pheader,$layout){
  if ( preg_match('@\s@',$did,$matches) !== 0 ) { 
    throw new \Exception('Cannot use blank-charactor as DEVICE : ' . $pid);
  }
  if ( $expires === 'false' ) {
    $expires_time = -1;
  }
  if ( ! $flg ){
    $brl = brlgen(Def::BP_LAYOUT,$sid,$did,'/',Beak::M_GET);
    $data = BeakController::beakQuery(array($brl));
    if ( $data[$brl]) {
      throw new \Exception('Device already exist ! : ' . $did);
    } else {
      // layout
      $uindex = '_u';
      $brl = brlgen(Def::BP_LAYOUT,$sid,$did,'',Beak::M_CREATE_COL,array(Beak::Q_UNIQUE_INDEX=>$uindex),array(Beak::COMMENT_KIND_RENEW));
      $ret = BeakController::beakQuery(array($brl));
      // component
      $brl = brlgen(Def::BP_COMPONENT,$sid,$did,'',Beak::M_CREATE_COL,array(Beak::Q_UNIQUE_INDEX=>$uindex),array(Beak::COMMENT_KIND_RENEW));
      $ret = BeakController::beakQuery(array($brl));
      // STATIC
      $brl = brlgen(Def::BP_STATIC,$sid,$did,'',Beak::M_CREATE_COL,array(Beak::Q_UNIQUE_INDEX=>$uindex),array(Beak::COMMENT_KIND_RENEW));
      $ret = BeakController::beakQuery(array($brl));
      // session
      $brl = brlgen(Def::BP_SESSION,$sid,$did,'',Beak::M_CREATE_COL,array(Beak::Q_UNIQUE_INDEX=>$uindex),array(Beak::COMMENT_KIND_RENEW));
      $ret = BeakController::beakQuery(array($brl));
    }
  }else{
  }
  $brl = brlgen(Def::BP_LAYOUT,$sid,$did,'/',Beak::M_GET);
  $ret = BeakController::beakQuery(array($brl));
  $data = $ret[$brl]?$ret[$brl]:array();
  $data[Beak::ATTR_REV] = $rev;
  $data[Def::K_LAYOUT_EREDIRECT] = $eredirect;
  if ( $layout ) {
    $data[Def::K_LAYOUT_LAYOUT] = $layout;
  }
  $data[Def::K_LAYOUT_HEADER]      = $header;
  $data[Def::K_LAYOUT_PHEADER]      = $pheader;
  $data[Def::K_LAYOUT_EXPIRES]     = $expires_time;
  $brl = brlgen(Def::BP_LAYOUT,$sid,$did,'/',Beak::M_SET,array(),array(Beak::COMMENT_KIND_REV));
  $ret = BeakController::beakQuery(array(array($brl,$data)));
  if ( ! $ret[$brl] ) {
    throw new \Exception('Fail to set : ' . $brl);
  }
  // css
  $cssBrl = brlgen(Def::BP_STATIC,$sid,$did,Config::CommonCSS,'');
  StaticContent::save($cssBrl,'text/css','',$css,null,Beak::Q_UNIQUE_INDEX,$expires_time);
  // js
  $jsBrl = brlgen(Def::BP_STATIC,$sid,$did,Config::CommonJs,'');
  StaticContent::save($jsBrl,'text/javascript','',$js,null,Beak::Q_UNIQUE_INDEX,$expires_time);
}
function setP($flg,$rev,$sid,$did,$pid,$ctype,$eredirect,$redirect,$pre_action,$post_action,$session,$session_exp,$expires,$expires_time,$header,$pheader,$layout){
  if ( preg_match('@\s@',$pid,$matches) !== 0 ) { 
    throw new \Exception('Cannot use blank-charactor as PAGE : ' . $pid);
  }
  $data = array();
  if ( $session === 'false' ) {
    $session_exp = -1;
  }
  if ( $expires === 'false' ) {
    $expires_time = -1;
  }
  $brl = brlgen(Def::BP_LAYOUT,$sid,$did,$pid,Beak::M_GET);
  $data = BeakController::beakQuery(array($brl));
  if ( ! $flg and $data[$brl]) {
    throw new \Exception('Page already exist ! : ' . $pid);
  }else{
    $data = &$data[$brl];
  }
  $data[Beak::ATTR_REV] = $rev;
  //$data[Def::K_LAYOUT_CTYPE]       = $ctype;
  $data[Def::K_LAYOUT_EREDIRECT]   = $eredirect;
  $data[Def::K_LAYOUT_REDIRECT]    = $redirect;
  $data[Def::K_LAYOUT_PRE_ACTION]  = $pre_action;
  $data[Def::K_LAYOUT_POST_ACTION] = $post_action;
  $data[Def::K_LAYOUT_SESSION_EXP] = $session_exp;
  $data[Def::K_LAYOUT_EXPIRES]     = $expires_time;
  $data[Def::K_LAYOUT_HEADER]      = $header;
  $data[Def::K_LAYOUT_PHEADER]     = $pheader;
  if ( $layout ) {
    $data[Def::K_LAYOUT_LAYOUT] = $layout;
  }
  $brl = brlgen(Def::BP_LAYOUT,$sid,$did,$pid,Beak::M_SET,array(),array(Beak::COMMENT_KIND_REV));
  $ret = BeakController::beakQuery(array(array($brl,$data)));
  if ( ! $ret[$brl] ) {
    throw new \Exception('Fail to set : ' . $brl);
  }
}
function setC($flg,$rev,$sid,$cid,$type,$subject,$description,$css,$js,$id,$class,$body,$actions) {
  if ( preg_match('@\s@',$cid,$matches) !== 0 ) { 
    throw new \Exception('Cannot use blank-charactor as COMPONENT : ' . $cid);
  }
  $data = array();
  $brl = brlgen(Def::BP_COMPONENT,$sid,'default',$cid,Beak::M_GET);
  $data = BeakController::beakQuery(array($brl));
  if ( ! $flg and $data[$brl]) {
    throw new \Exception('Component already exist ! : ' . $cid);
  }else{
    $data = &$data[$brl];
  }
  $data[Beak::ATTR_REV] = $rev;
  $data[Def::K_COMPONENT_TYPE]        = $type;
  $data[Def::K_COMPONENT_SUBJECT]     = $subject;
  $data[Def::K_COMPONENT_DESCRIPTION] = $description;
  $data[Def::K_COMPONENT_CSS]         = $css;
  $data[Def::K_COMPONENT_JS]          = $js;
  $data[Def::K_COMPONENT_ID]          = $id;
  $data[Def::K_COMPONENT_CLASS]       = $class;
  $data[Def::K_COMPONENT_BODY]        = $body;
  $data[Def::K_COMPONENT_ACTION]      = $actions;
  $brl = brlgen(Def::BP_COMPONENT,$sid,'default',$cid,Beak::M_SET,array(),array(Beak::COMMENT_KIND_REV));
  $ret = BeakController::beakQuery(array(array($brl,$data)));
  if ( ! $ret[$brl] ) {
    throw new \Exception('Fail to set : ' . $brl);
  }
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
