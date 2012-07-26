<?php
/**
 * content.php - HTML content drawer
 *  
 * @access public
 * @package cockatoo-web
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;

/**
 * HTML content drawer
 *  
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class ContentDrawer {
  protected $mode;
  protected $service;
  protected $device;
  protected $path;
  protected $args;
  protected $session_path;
  protected $widget;
  public    $baseLayoutBrl;
  public    $layoutBrl;
  public    $layoutData;
  protected $layout;
  public    $ctype;
  public    $baseEredirect;
  public    $eredirect;
  public    $redirect;
  protected $componentBrls;
  public    $componentData;
  public    $actionBrls;
  protected $actionResults;
  public    $preAction;
  protected $preActionResults;
  public    $postAction;
  protected $postActionResults;
  protected $now;
  public    $sessionExp;
  protected $sessionID;
  protected $session;
  public    $expires;
  protected $hdf;
  protected $cs;

  public function __construct ($service,$device,$path,$args,$session_path,$mode) {
    $this->service      = $service;
    $this->device       = $device;
    $this->path         = $path;
    $this->args         = $args;
    $this->session_path = $session_path;
    $this->mode         = $mode;
  }

  private function debug($msg,$data,$mode=Def::RenderingModeDEBUG1){
    Log::debug($msg,$data);
    if ( Config::Mode === Def::MODE_DEBUG and $this->mode & $mode ) {
      print '<pre>';
      print "$msg\n";
      if ( is_array($data) ) {
        var_dump($data);
      }else {
        print htmlspecialchars($data,\ENT_QUOTES);
      }
      print '</pre>';
    }
  }

  private function collectComponentBrls ( &$data ) {
    $this->componentBrls[] = $data[Def::K_LAYOUT_COMPONENT];
    foreach ( $data[Def::K_LAYOUT_CHILDREN] as $child ) {
      $this->collectComponentBrls($child);
    }
  }

  private function mergeLayout(&$baseLayout,&$pageLayout){
    if ( strcmp($baseLayout[Def::K_LAYOUT_TYPE],Def::PAGELAYOUT)===0 ){
      $pageLayout[Def::K_LAYOUT_CLASS]     .= 'co-PageLayout ' . $baseLayout[Def::K_LAYOUT_CLASS];
      $pageLayout[Def::K_LAYOUT_HEIGHT]    .= $pageLayout[Def::K_LAYOUT_HEIGHT]?$pageLayout[Def::K_LAYOUT_HEIGHT]:$baseLayout[Def::K_LAYOUT_HEIGHT];
      $pageLayout[Def::K_LAYOUT_WIDTH]     .= $pageLayout[Def::K_LAYOUT_WIDTH]?$pageLayout[Def::K_LAYOUT_WIDTH]:$baseLayout[Def::K_LAYOUT_WIDTH];
      $pageLayout[Def::K_LAYOUT_MIN_HEIGHT].= $pageLayout[Def::K_LAYOUT_MIN_HEIGHT]?$pageLayout[Def::K_LAYOUT_MIN_HEIGHT]:$baseLayout[Def::K_LAYOUT_MIN_HEIGHT];
      $pageLayout[Def::K_LAYOUT_MIN_WIDTH] .= $pageLayout[Def::K_LAYOUT_MIN_WIDTH]?$pageLayout[Def::K_LAYOUT_MIN_WIDTH]:$baseLayout[Def::K_LAYOUT_MIN_WIDTH];
      $pageLayout[Def::K_LAYOUT_EXTRA]     .= ' ' . $pageLayout[Def::K_LAYOUT_EXTRA];
      $baseLayout = $pageLayout;
      return;
    }
    foreach ( array_keys($baseLayout[Def::K_LAYOUT_CHILDREN]) as $k ) {
      $this->mergeLayout($baseLayout[Def::K_LAYOUT_CHILDREN][$k],$pageLayout);
    }
  }


  public function layout($data = null, $baseEredirect = null) {
    if ( $data ) {
      $this->layoutData = $data;
    }else {
      $this->baseLayoutBrl= brlgen(Def::BP_LAYOUT,$this->service,$this->device,'',Beak::M_GET);
      $this->layoutBrl    = brlgen(Def::BP_LAYOUT,$this->service,$this->device,$this->path,Beak::M_GET);

      if ( strcmp($this->path,'') === 0 or strcmp($this->path,'/') === 0){
        throw new \Exception('Unexpect Layout is specified !' . $this->path );
      }
      
      $this->debug('== LAYOUT(BRL) ==',array($this->layoutBrl,$this->baseLayoutBrl),Def::RenderingModeDEBUG2);
      $datas = BeakController::beakGetsQuery(array($this->baseLayoutBrl,$this->layoutBrl));
      // Base layout
      if ( ! $datas[$this->baseLayoutBrl] ) {
        // Device not defined.
        Log::info(__CLASS__ . '::' . __FUNCTION__ . ' : (D) Try to fallback from : ' . $this->device);
        $this->device = DeviceSelector::$instance->fallback($this->device);
        if ( $this->device ) {
          Log::info(__CLASS__ . '::' . __FUNCTION__ . ' : (D) Try to fallback to   : ' . $this->device);
          $this->debug('== BASE LAYOUT NOT FOUND ! (fallback to '.$this->device.') ==','',Def::RenderingModeDEBUG2);
          // @@@ fallback or redirect ?
          return $this->layout();
        }
        throw new \Exception('Device not found !');
      }
      $this->baseEredirect = $datas[$this->baseLayoutBrl][Def::K_LAYOUT_EREDIRECT];
      $this->baseHeader    = $datas[$this->baseLayoutBrl][Def::K_LAYOUT_HEADER];
      $this->basePHeader   = $datas[$this->baseLayoutBrl][Def::K_LAYOUT_PHEADER];
      $this->baseBottom    = $datas[$this->baseLayoutBrl][Def::K_LAYOUT_BOTTOM];
      $this->baseSessionExp= $datas[$this->baseLayoutBrl][Def::K_LAYOUT_SESSION_EXP];

      // Page layout
      if ( ! $datas[$this->layoutBrl] ) {
        Log::info(__CLASS__ . '::' . __FUNCTION__ . ' : (P) Try to fallback from : ' . $this->device);
        $this->device = DeviceSelector::$instance->fallback($this->device);
        if ( $this->device ) {
          Log::info(__CLASS__ . '::' . __FUNCTION__ . ' : (P) Try to fallback to   : ' . $this->device);
          $this->debug('== LAYOUT NOT FOUND ! (fallback to '.$this->device.') ==','',Def::RenderingModeDEBUG2);
          // @@@ fallback or redirect ?
          return $this->layout(null,($baseEredirect?$baseEredirect:$this->baseEredirect) );
        }
        throw new \Exception('Page not found !');
      }
      $this->redirect      = $datas[$this->layoutBrl][Def::K_LAYOUT_REDIRECT];
      if ( $this->redirect ) {
        return $this->redirect;
      }
      $this->eredirect     = $datas[$this->layoutBrl][Def::K_LAYOUT_EREDIRECT];
      $this->debug('== LAYOUT(BRL) ==',$datas,Def::RenderingModeDEBUG2);
      // @@@ きちゃない・・・
      if      ( $datas[$this->layoutBrl][Def::K_LAYOUT_LAYOUT][Def::K_LAYOUT_TYPE] === Def::PLAINWIDGET ) {
        $this->ctype = Def::K_LAYOUT_CTYPE_PLAIN;
        $this->layoutData = $datas[$this->layoutBrl];
      }elseif ( $datas[$this->layoutBrl][Def::K_LAYOUT_LAYOUT][Def::K_LAYOUT_TYPE] === Def::JSONWIDGET ) {
        $this->ctype = Def::K_LAYOUT_CTYPE_JSON;
        $this->layoutData = $datas[$this->layoutBrl];
      }elseif ( $datas[$this->layoutBrl][Def::K_LAYOUT_LAYOUT][Def::K_LAYOUT_TYPE] === Def::BINARYWIDGET ) {
        $this->ctype = Def::K_LAYOUT_CTYPE_BIN;
        $this->layoutData = $datas[$this->layoutBrl];
      }else {
        $this->ctype = Def::K_LAYOUT_CTYPE_HTML;
        $this->mergeLayout($datas[$this->baseLayoutBrl][Def::K_LAYOUT_LAYOUT],$datas[$this->layoutBrl][Def::K_LAYOUT_LAYOUT]);
        $this->layoutData = $datas[$this->layoutBrl];
        $this->layoutData[Def::K_LAYOUT_LAYOUT] = $datas[$this->baseLayoutBrl][Def::K_LAYOUT_LAYOUT];
      }
    }
    //$this->ctype        = $this->layoutData[Def::K_LAYOUT_CTYPE];
    $this->pheader      = $this->basePHeader . $this->layoutData[Def::K_LAYOUT_PHEADER];
    $this->layout       = $this->layoutData[Def::K_LAYOUT_LAYOUT];
    $this->header       = $this->baseHeader  . $this->layoutData[Def::K_LAYOUT_HEADER];
    $this->bottom       = $this->baseBottom  . $this->layoutData[Def::K_LAYOUT_BOTTOM];
    $this->preAction    = $this->layoutData[Def::K_LAYOUT_PRE_ACTION];
    $this->postAction   = $this->layoutData[Def::K_LAYOUT_POST_ACTION];
    $this->sessionExp   = (int)(((int)$this->layoutData[Def::K_LAYOUT_SESSION_EXP]===Def::SESSION_DEFAULT)?($this->baseSessionExp):($this->layoutData[Def::K_LAYOUT_SESSION_EXP]));
    $this->expires      = $this->layoutData[Def::K_LAYOUT_EXPIRES];
    $this->collectComponentBrls($this->layout);
  }


  public function session(&$HEADER,&$SERVER,&$POST,&$GET,&$COOKIE,&$FILES) {
    $this->now = time();
    if ( $this->sessionExp === Def::SESSION_NO_SESSION ) {
      // Nothing to do
    }elseif ( $this->sessionExp === Def::SESSION_TMP_SESSION ) {
      $this->sessionID = uniqid();
      $this->session   = array();
      $exp = 0;
      Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Create tmp session ' . $this->sessionID);
    }elseif ( $this->sessionExp !== Def::SESSION_NO_SESSION ) {
      $this->sessionID = isset($_COOKIE[Config::SESSION_COOKIE])?$_COOKIE[Config::SESSION_COOKIE]:null;
      $this->session = null;
      // Try session
      Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Get session cookie ' . $this->sessionID );
      $this->session = getSession($this->sessionID,$this->service);
      if ( ! $this->sessionID or ! $this->session ) {
        $this->sessionID = uniqid();
        $this->session = array();
        Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Create new session ' . $this->sessionID);
      }
      $exp = $this->now+$this->sessionExp;
      // cookie
      // name , value , exp(sec) , path , domain , secure , httponly 
      Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Issue session cookie ' . $this->sessionID . ' , ' . $this->sessionExp);
      setcookie(Config::SESSION_COOKIE,$this->sessionID,$exp,$this->session_path);
    }

    foreach($FILES as $FILE){
      $files []= array(Def::F_ERROR=>$FILE['error'],Def::F_NAME=>$FILE['name'],Def::F_TYPE=>FileContentType::get($FILE['name']),Def::F_SIZE=>$FILE['size'],Def::F_CONTENT=>file_get_contents($FILE['tmp_name']));
      $data = array(file_get_contents($FILE['tmp_name']));
    }
    // Set current session
    $this->session[Def::SESSION_KEY_REQ]    = $HEADER;
    $this->session[Def::SESSION_KEY_SERVER] = $SERVER;
    $this->session[Def::SESSION_KEY_POST]   = $POST;
    $this->session[Def::SESSION_KEY_GET]    = $GET;
    $this->session[Def::SESSION_KEY_COOKIE] = $COOKIE;
    $this->session[Def::SESSION_KEY_FILES]  = $files;
    $this->session[Def::SESSION_KEY_DEVICE] = $this->device;
    $this->session[Def::SESSION_KEY_EXP]    = $exp;
    // Save
    setSession($this->sessionID,$this->service,$this->session);
  }
  
  public function components() {
    $this->debug('== COMPONENTS(BRL) ==',$this->componentBrls,Def::RenderingModeDEBUG3);

    //$this->componentData = BeakController::beakQuery($this->componentBrls);
    $this->componentData = BeakController::beakGetsQuery($this->componentBrls);

    $this->debug('== COMPONENTS DATA ==',$this->componentData,Def::RenderingModeDEBUG3);

    $this->widget = WidgetFactory::getWidget($this->layout,$this->componentData,$this->mode);
    $this->widget->layoutWalk($this->componentData);
  }

  // @@@ Need to give consideration $context What should be containig.
  protected function doActions(&$brls){
    $hide = array(
      Def::AC_SERVICE    => $this->service ,
      Def::AC_SESSION_ID => $this->sessionID);
    $queries = array();
    foreach($brls as $brl){
      if ( $brl ) {
        $queries []= array($brl, $this->args,$hide);
      }
    }
    $this->debug('== ACTIONS(BRL) ==',$queries,Def::RenderingModeDEBUG4);
    $results = BeakController::beakQuery($queries);
    $this->debug('== ACTIONS DATA ==',$results,Def::RenderingModeDEBUG4);

    $redirect = null;
    foreach( $results as $brl => $rets ) {
      if ( $rets and $rets[0] === Def::ActionSuccess) {
        // merge session
        if ( isset($rets[3]) and $this->session ) {
          foreach($rets[3] as $sk => $sv ) {
            $this->session[$sk] = $sv;
          }
        }
        // reserve redirect
        if ( ! $redirect ) {
          $redirect = $rets[4];
        }
        // set cookie
        if ( isset($rets[5]) ) {
          foreach($rets[5] as $cn => $cs ) {
            setcookie($cn,$cs[0],$cs[1],$cs[2],$cs[3],$cs[4],$cs[5]);
          }
        }
        // set header
        if ( isset($rets[6]) ) {
          $this->pheader.="\n".$rets[6];
        }
        // merge args
        if ( isset($rets[7]) ) {
          foreach($rets[7] as $ak => $av ) {
            $this->args[$ak] = $av;
          }
        }
      }
    }
    // update session
    if ( $this->session ) {
      setSession($this->sessionID,$this->service,&$this->session);
    }
    // redirect
    if ( $redirect ) {
      redirect($redirect);
    }
    return $results;
  }

  public function preAction() {
    if ( $this->preAction ) {
      $brls = array($this->preAction);
      $this->preActionResults = $this->doActions($brls);
    }
  }
  public function postAction() {
    if ( $this->postAction ) {
      $brls = array($this->postAction);
      $this->postActionResults = $this->doActions($brls);
    }
  }
  public function actions() {
    $this->actionBrls = $this->widget->actionWalk();
    $this->actionBrls = array_unique($this->actionBrls);
    $this->actionResults = $this->doActions($this->actionBrls);
  }

  // @@@ static or util
  protected function array2hdf(&$array, &$hdf, $node_name = null) {
    if ( $array ) {
      foreach ($array as $k => $v) {
        $encode_flg = true;
        if ($node_name !== null) {
          if ( strncmp('@',$k,1) === 0 ) {
            $encode_flg = false;
          }
          $k = $node_name . '.' . str_replace('-','_',$k);
        }
        if ( $v === null ) {
          // Nothing to do
        } else if ( !is_array($v)) {
          if ( $encode_flg ){
            // @@@ HTML mode
            $v = htmlspecialchars($v,\ENT_QUOTES);
            // @@@ JS mode
          }
          $this->debug('- ' . $k ,$v,Def::RenderingModeDEBUG1);
          \hdf_set_value($hdf, $k, $v);
        } else {
          $this->array2hdf($v, $hdf, $k);
        }
      }
    }
  }
  public function prepareDraw() {
    // template variable (hdf)
    $this->hdf = \hdf_init();
    if ( is_array($this->preActionResults) && count($this->preActionResults) > 0 ) {
      foreach( $this->preActionResults as $brl => $rets ) {
        if( $rets) {
          $this->array2hdf($rets[2],$this->hdf,Def::CS_ACTION . ($rets[1]?'.'.$rets[1]:''));
        }
      }
    }
    if ( is_array($this->actionResults) && count($this->actionResults) > 0 ) {
      foreach( $this->actionResults as $brl => $rets ) {
        if ( $rets ) {
          $this->array2hdf($rets[2],$this->hdf,Def::CS_ACTION . ($rets[1]?'.'.$rets[1]:''));
        }
      }
    }
    if ( is_array($this->postActionResults) && count($this->postActionResults) > 0 ) {
      foreach( $this->postActionResults as $brl => $rets ) {
        if( $rets) {
          $this->array2hdf($rets[2],$this->hdf,Def::CS_ACTION . ($rets[1]?'.'.$rets[1]:''));
        }
      }
    }
    if ( $this->session ) {
      $this->array2hdf($this->session,$this->hdf,Def::CS_SESSION);
    }
  }
  public function drawPHeader($type) {
    // HTTP protocol header.
    $etag = null;
    http_200($type,$etag,$this->expires);
    if ( $this->pheader ) {
      if ( strstr($this->pheader,'<?cs ') ) {
        $this->cs = \cs_init($this->hdf);
        cs_parse_string($this->cs, $this->pheader);
        $this->pheader = cs_render($this->cs);
        cs_destroy($this->cs);
      }    
      foreach ( explode("\n",$this->pheader) as $line ) {
        if ( $line ) {
          header($line);
        }
      }
    }
  }

  public function drawHeader() {
    if ( $this->header ) {
      if ( strstr($this->header,'<?cs ') ) {
        // template engine (cs)
        $this->cs = \cs_init($this->hdf);
        cs_parse_string($this->cs, $this->header);
        print cs_render($this->cs);
        cs_destroy($this->cs);
      }else{
        print $this->header;
      }
    }
  }

  public function drawCommonCss() {
    print '<link rel="stylesheet" type="text/css" media="all" href="'.Def::PATH_STATIC_PREFIX.'/'.Def::RESERVED_SERVICE_CORE.'/'.Def::RESERVED_DEVICE_DEFAULT.'/'.Config::CommonCSS.'"></link>';
    print '<link rel="stylesheet" type="text/css" media="all" href="'.Def::PATH_STATIC_PREFIX.'/'.$this->service.'/'.$this->device.'/'.Config::CommonCSS.'"></link>';
  }
  public function drawCommonJs() {
    print '<script type="text/javascript" src="'.Def::PATH_STATIC_PREFIX.'/'.Def::RESERVED_SERVICE_CORE.'/'.Def::RESERVED_DEVICE_DEFAULT.'/'.Config::CommonJs.'"></script>';
    print '<script type="text/javascript" src="'.Def::PATH_STATIC_PREFIX.'/'.$this->service.'/'.$this->device.'/'.Config::CommonJs.'"></script>';
  }
  public function drawCss() {
    print $this->widget->cssWalk();
  }
  public function drawJs() {
    print $this->widget->jsWalk();
  }

  public function drawMain() {
    $template = $this->widget->drawWalk();
    // template engine (cs)
    $this->cs = \cs_init($this->hdf);
    cs_parse_string($this->cs, $template);
    print cs_render($this->cs);
    cs_destroy($this->cs);
  }
  public function drawBottom() {
    if ( $this->bottom ) {
      if ( strstr($this->header,'<?cs ') ) {
        // @@@ Todo: The number of runnign cs too match !!!
        // template engine (cs)
        $this->cs = \cs_init($this->hdf);
        cs_parse_string($this->cs, $this->bottom);
        print cs_render($this->cs);
        cs_destroy($this->cs);
      }else{
        print $this->bottom;
      }
    }
  }

  protected function findKey($key,$len,$data,$cur){
    $n = strlen($cur);
    if ( strncmp($key,$cur,$n) === 0 ) {
      if ( $n === $len ) {
        return $data; // Found
      }
      foreach ( $data as $k => $v ) {
        $ret = $this->findKey($key,$len,$v,$cur.'.'.$k);
        if ( $ret ) {
          return $ret;
        }
      }
    }
    return null;
  }
  protected function findResults($key,$len){
    if ( is_array($this->preActionResults) && count($this->preActionResults) > 0 ) {
      foreach( $this->preActionResults as $brl => $rets ) {
        if( $rets) {
          $ret = $this->findKey($key,$len,$rets[2],Def::CS_ACTION . ($rets[1]?'.'.$rets[1]:''));
          if ( $ret ) {
            return $ret;
          }
        }
      }
    }
    if ( is_array($this->actionResults) && count($this->actionResults) > 0 ) {
      foreach( $this->actionResults as $brl => $rets ) {
        if ( $rets ) {
          $ret = $this->findKey($key,$len,$rets[2],Def::CS_ACTION . ($rets[1]?'.'.$rets[1]:''));
          if ( $ret ) {
            return $ret;
          }
        }
      }
    }
    if ( is_array($this->postActionResults) && count($this->postActionResults) > 0 ) {
      foreach( $this->postActionResults as $brl => $rets ) {
        if( $rets) {
          $ret = $this->findKey($key,$len,$rets[2],Def::CS_ACTION . ($rets[1]?'.'.$rets[1]:''));
          if ( $ret ) {
            return $ret;
          }
        }
      }
    }
    return $this->findKey($key,$len,$this->session,Def::CS_SESSION);
  }
  public function drawJson() {
    $data = array();
    $template = $this->widget->drawWalk();
    foreach( explode("\n",$template) as $key ) {
      if ( preg_match('@^\s*$@',$key,$matches) === 0 ) {
        $data[$key] = $this->findResults($key,strlen($key));
      }
    }
    print json_encode($data);
  }

  public function drawBinary() {
    $content = null;
    $template = $this->widget->drawWalk();
    foreach( explode("\n",$template) as $key ) {
      if ( preg_match('@^\s*$@',$key,$matches) === 0 ) {
        $content = $this->findResults($key,strlen($key));
        break;
      }
    }
    StaticContent::http($content,$this->session[Def::SESSION_KEY_REQ]);
  }

  public function drawTemplate() {
    print $this->widget->templateWalk();
  }
  public function drawCMS() {
    print $this->widget->cmsWalk();
  }
  public function __destruct(){
    global $COCKATOO_GLFLG;
    if ( $this->hdf ) {
      hdf_destroy($this->hdf);
    }
    if ( $this->session ) {
      if (  $this->session[Def::SESSION_KEY_EXP] <= $this->now  ) {
        delSession($this->sessionID,$this->service);
      }else{
        $this->session[Def::SESSION_KEY_REQ]    = null;
        $this->session[Def::SESSION_KEY_SERVER] = null;
        $this->session[Def::SESSION_KEY_POST]   = null;
        $this->session[Def::SESSION_KEY_GET]    = null;
        $this->session[Def::SESSION_KEY_COOKIE] = null;
        $this->session[Def::SESSION_KEY_FILES]  = null;
        setSession($this->sessionID,$this->service,&$this->session);
     }
    }
    if ( $COCKATOO_GLFLG ) {
      // @@@ It's not exact work because flushed stdbuffer is possibly buffered by the other modules, for instance mod_zip and so on...
      if ( Config::$Error2Die and rand(1,Config::$Error2Die) === 1 ) {
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : Something occurd and kill myself : ' . $COCKATOO_GLFLG);
        flush();
        posix_kill(posix_getpid(),9);
      }
    }
  }
}