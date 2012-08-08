<?php
namespace Cockatoo;
require_once('/usr/local/cockatoo/def.php');
require_once(Config::COCKATOO_ROOT.'utils/beak.php');
require_once(Config::COCKATOO_ROOT.'wwwutils/core/reqparser.php');
$CUR=dirname(__FILE__);
require_once($CUR.'/../../wwwbootstrap.php');

class WwwTest extends \PHPUnit_Framework_TestCase {
  public function setUp(){
  }
  public function tearDown(){
  }
  public function testTestHtml(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    $HTML = run_www('index.php','/fortest/default/main');
    $EXPECTS=<<<__HTML__
HTTP/1.1 200 OK
Content-type: text/html
X-FORTEST: 1
X-FORTEST-MAIN:1
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
FORTEST HEADER
MAIN header
<link rel="stylesheet" type="text/css" media="all" href="/_s_/core/default/css/cockatoo.css"></link><link rel="stylesheet" type="text/css" media="all" href="/_s_/fortest/default/css/cockatoo.css"></link>
<style type="text/css">
<!--
#test0 {
background-color: #FFCCCC;
}#test3 {
background-color: #FFFFCC;
}#test2 {
background-color: #CCCCFF;
}#test1 {
background-color: #CCFFCC;
}
-->
</style>
</head>
<body id="co-frame">
 <div id="co-main">
<div  class=" co-Widget   co-Horizontal" extra=""><div class="co-Wbody"><div id="test0" class=" co-Widget  test0 co-Horizontal" extra=""><div class="co-Wbody">TEST0 body
</div></div><div  class=" co-Widget co-PageLayout   co-Horizontal" extra=" "><div class="co-Wbody"><div  class=" co-Widget   co-Vertical" extra=""><div class="co-Wbody"><div id="test3" class=" co-Widget  test3 co-Horizontal" extra="" style="float:left;width:200px;"><div class="co-Wbody">TEST3 body
</div></div><div id="test2" class=" co-Widget  test2 co-Horizontal" extra="" style="float:none;width:auto;margin-left:200px;"><div class="co-Wbody">TEST2 body
</div></div></div></div></div></div><div id="test1" class=" co-Widget  test1 co-Horizontal" extra=""><div class="co-Wbody">TEST1 body
</div></div></div></div> </div>
FORTEST body bottom
MAIN body bottom
<script type="text/javascript" src="/_s_/core/default/js/cockatoo.js"></script><script type="text/javascript" src="/_s_/fortest/default/js/cockatoo.js"></script>
<script type="text/javascript">
<!--
// TEST0 JS
// TEST3 JS
// TEST2 JS
// TEST1 JS

-->
</script>
</body>
</html>

__HTML__;

    $this->assertEquals($EXPECTS,$HTML);
  }
  public function testWikiTop(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    $HTML = run_www('index.php','/wiki/view');
    $EXPECTS='cockatoo-wiki: Cockatoo PHP framework';
    
    foreach( explode("\n",$HTML) as $line ){
      if ( preg_match('@<title>(.*)</title>@',$line,$matches ) !== 0) {
        $this->assertEquals($EXPECTS,$matches[1]);
        return;
      }
    }
    $this->assertTrue(false,'No page title');
  }
  public function testWikiRedirect(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    $HTML = run_www('index.php','/wiki/XXX');
    $EXPECTS='/wiki/view'; // Error redirect
    
    foreach( explode("\n",$HTML) as $line ){
      if ( preg_match('@Location:\s*(.*)@',$line,$matches ) !== 0) {
        $this->assertEquals($EXPECTS,$matches[1]);
        return;
      }
    }
    $this->assertTrue(false,'Unexpected page');
  }

  public function testWikiFallback(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    $BIN = run_www('index.php','/wiki/android/img/',array('n'=>'logo.png','debug'=>'1'));
    
    foreach( explode("\n",$BIN) as $line ){
      if ( preg_match('@- A\.wiki\.img\.\*bin@',$line,$matches ) !== 0) {
        $this->assertTrue(true,''); 
        return;
      }
    }
    $this->assertTrue(false,'Unexpected page');
  }
}