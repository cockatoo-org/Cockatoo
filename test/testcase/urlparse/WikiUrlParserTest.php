<?php
namespace Cockatoo;
require_once('/usr/local/cockatoo/def.php');
require_once(Config::COCKATOO_ROOT.'utils/beak.php');
require_once(Config::COCKATOO_ROOT.'wwwutils/core/reqparser.php');
$CUR=dirname(__FILE__);
require_once($CUR.'/../../wwwbootstrap.php');

class WikiUrlParserTest extends \PHPUnit_Framework_TestCase
{
  public function setUp(){
  }
  public function tearDown(){
  }
  public function testFavicon(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    pre_www('index.php','/favicon.ico');
    try {
      list($SERVICE,$DEVICE,$PATH,$ARGS,$SESSION_PATH) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);
    }catch(RedirectException $e){
      $this->assertEquals('301 redirect : /_s_/core/default/logo.png',$e->getMessage());
    }
    return;
    $this->assertEquals('wiki',$SERVICE);
    $this->assertEquals('default',$DEVICE);
    $this->assertEquals('img',$PATH);
    $this->assertEquals('/',$SESSION_PATH);
    $this->assertEquals(array(
                          'P' => 'Cockatoo PHP framework',
                          'N' => 'logo.png',
                          ),$ARGS);
  }
  public function testTop(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    pre_www('index.php','/wiki/view/');
    list($SERVICE,$DEVICE,$PATH,$ARGS,$SESSION_PATH) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);
    $this->assertEquals('wiki',$SERVICE);
    $this->assertEquals('default',$DEVICE);
    $this->assertEquals('view',$PATH);
    $this->assertEquals('/wiki',$SESSION_PATH);
    $this->assertEquals(array(
                          'P' => ''
                          ),$ARGS);
  }
  public function testPage(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    pre_www('index.php','/wiki/view/pagename');
    list($SERVICE,$DEVICE,$PATH,$ARGS,$SESSION_PATH) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);
    $this->assertEquals('wiki',$SERVICE);
    $this->assertEquals('default',$DEVICE);
    $this->assertEquals('view',$PATH);
    $this->assertEquals('/wiki',$SESSION_PATH);
    $this->assertEquals(array(
                          'P' => 'pagename'
                          ),$ARGS);
  }
  public function testEdit(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    pre_www('index.php','/wiki/edit/pagename');
    list($SERVICE,$DEVICE,$PATH,$ARGS,$SESSION_PATH) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);
    $this->assertEquals('wiki',$SERVICE);
    $this->assertEquals('default',$DEVICE);
    $this->assertEquals('edit',$PATH);
    $this->assertEquals('/wiki',$SESSION_PATH);
    $this->assertEquals(array(
                          'P' => 'pagename'
                          ),$ARGS);
  }
  public function testImg(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    pre_www('index.php','/wiki/img/pagename',array('n' => 'logo.png'));
    list($SERVICE,$DEVICE,$PATH,$ARGS,$SESSION_PATH) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);
    $this->assertEquals('wiki',$SERVICE);
    $this->assertEquals('default',$DEVICE);
    $this->assertEquals('img',$PATH);
    $this->assertEquals('/wiki',$SESSION_PATH);
    $this->assertEquals(array(
                          'P' => 'pagename'
                          ),$ARGS);
  }
  public function testUpload(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    pre_www('index.php','/wiki/upload/pagename');
    list($SERVICE,$DEVICE,$PATH,$ARGS,$SESSION_PATH) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);
    $this->assertEquals('wiki',$SERVICE);
    $this->assertEquals('default',$DEVICE);
    $this->assertEquals('upload',$PATH);
    $this->assertEquals('/wiki',$SESSION_PATH);
    $this->assertEquals(array(
                          'P' => 'pagename'
                          ),$ARGS);
  }
  public function testUploaded(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    pre_www('index.php','/wiki/uploaded/pagename');
    list($SERVICE,$DEVICE,$PATH,$ARGS,$SESSION_PATH) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);
    $this->assertEquals('wiki',$SERVICE);
    $this->assertEquals('default',$DEVICE);
    $this->assertEquals('uploaded',$PATH);
    $this->assertEquals('/wiki',$SESSION_PATH);
    $this->assertEquals(array(
                          'P' => 'pagename'
                          ),$ARGS);
  }
  public function testOther(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    pre_www('index.php','/wiki/error');
    list($SERVICE,$DEVICE,$PATH,$ARGS,$SESSION_PATH) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);
    $this->assertEquals('wiki',$SERVICE);
    $this->assertEquals('default',$DEVICE);
    $this->assertEquals('error',$PATH);
    $this->assertEquals('/wiki',$SESSION_PATH);
    $this->assertEquals(array(),$ARGS);
  }
  public function testAndroidView1(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    pre_www('index.php','/wiki/android/view/pagename');
    list($SERVICE,$DEVICE,$PATH,$ARGS,$SESSION_PATH) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);
    $this->assertEquals('wiki',$SERVICE);
    $this->assertEquals('android',$DEVICE);
    $this->assertEquals('view',$PATH);
    $this->assertEquals('/wiki',$SESSION_PATH);
    $this->assertEquals(array(
                          'P' => 'pagename'
                          ),$ARGS);
  }
  public function testAndroidView2(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    pre_www('index.php','/wiki/view/pagename',array(),array(),array(),array('User-Agent' => 'Mozilla/5.0 (Linux; U; Android 2.3.4; ja-jp; ISW11HT Build/GRJ22) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1'));
    list($SERVICE,$DEVICE,$PATH,$ARGS,$SESSION_PATH) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);
    $this->assertEquals('wiki',$SERVICE);
    $this->assertEquals('android',$DEVICE);
    $this->assertEquals('view',$PATH);
    $this->assertEquals('/wiki',$SESSION_PATH);
    $this->assertEquals(array(
                          'P' => 'pagename'
                          ),$ARGS);
  }
  public function testAndroidImg1(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    pre_www('index.php','/wiki/android/img/pagename',array('n' => 'logo.png'),array(),array(),array());
    list($SERVICE,$DEVICE,$PATH,$ARGS,$SESSION_PATH) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);
    $this->assertEquals('wiki',$SERVICE);
    $this->assertEquals('android',$DEVICE);
    $this->assertEquals('img',$PATH);
    $this->assertEquals('/wiki',$SESSION_PATH);
    $this->assertEquals(array(
                          'P' => 'pagename'
                          ),$ARGS);
  }
  public function testAndroidImg2(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    pre_www('index.php','/wiki/img/pagename',array('n' => 'logo.png'),array(),array(),array('User-Agent' => 'Mozilla/5.0 (Linux; U; Android 2.3.4; ja-jp; ISW11HT Build/GRJ22) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1'));
    list($SERVICE,$DEVICE,$PATH,$ARGS,$SESSION_PATH) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);
    $this->assertEquals('wiki',$SERVICE);
    $this->assertEquals('android',$DEVICE);
    $this->assertEquals('img',$PATH);
    $this->assertEquals('/wiki',$SESSION_PATH);
    $this->assertEquals(array(
                          'P' => 'pagename'
                          ),$ARGS);
  }
  public function testAndroidOther(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    pre_www('index.php','/wiki/android/error');
    list($SERVICE,$DEVICE,$PATH,$ARGS,$SESSION_PATH) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);
    $this->assertEquals('wiki',$SERVICE);
    $this->assertEquals('android',$DEVICE);
    $this->assertEquals('error',$PATH);
    $this->assertEquals('/wiki',$SESSION_PATH);
    $this->assertEquals(array(),$ARGS);
  }
  public function testOtherService(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    pre_www('index.php','/yslowviewer/default/main');
    list($SERVICE,$DEVICE,$PATH,$ARGS,$SESSION_PATH) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);
    $this->assertEquals('yslowviewer',$SERVICE);
    $this->assertEquals('default',$DEVICE);
    $this->assertEquals('main',$PATH);
    $this->assertEquals('/yslowviewer',$SESSION_PATH);
    $this->assertEquals(array(),$ARGS);
  }
  public function testAnyOther(){
    global $HEADER,$_SERVER,$_GET,$_COOKIE;
    pre_www('index.php','/');
    try{
      list($SERVICE,$DEVICE,$PATH,$ARGS,$SESSION_PATH) = parseRequest($HEADER,$_SERVER,$_GET,$_COOKIE);
    }catch(\Exception $e){
      return; // Should be ERROR and Redirect to Config::ErrorRedirect
    }
    $this->assertTrue(false,'Unexpected parse');
  }
}
