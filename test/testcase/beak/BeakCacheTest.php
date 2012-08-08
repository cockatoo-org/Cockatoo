<?php
namespace Cockatoo;
require_once('/usr/local/cockatoo/def.php');
Config::$UseMemcache = array('127.0.0.1:11211');
Config::$BEAKS = array (
  Def::BP_LAYOUT   => 'Cockatoo\BeakFile'   ,
  null
  );
require_once(Config::COCKATOO_ROOT.'utils/beak.php');

class BeakCacheTest extends \PHPUnit_Framework_TestCase
{
  function set(){
  }

  public function setUp(){
    $mem = new \Memcached();
    $mem->addServer('127.0.0.1',11211);
    $mem->delete('layout://unittest-layout/device/path/to?get#cache#cexp%3D10');
    $mem->delete('layout://unittest-layout/device/path/to?get#cache');
    
    $this->set();
    $brl = brlgen(Def::BP_LAYOUT,'unittest','device','',Beak::M_CREATE_COL,array(),array(Beak::COMMENT_KIND_RENEW));
    $ret = BeakController::beakSimpleQuery($brl);
    $this->defaultData = array( 'key' => 'value' ,
                                'list' => array('foo','bar','baz'),
                                'hash' => array(
                                  'foo' => 'FOO',
                                  'bar' => 'BAR',
                                  'baz' => 'BAZ') );
    $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','',Beak::M_DEL_ARRAY);
    $data [Beak::Q_UNIQUE_INDEX]= array('','path/','path/to','path/new','file1','file2');
    $datas = BeakController::beakSimpleQuery($brl,$data);
    
    $datas = array();
    $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','',Beak::M_SET,array());
    $data = $this->defaultData;
    $datas[] = array($brl , $data);
    $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/',Beak::M_SET,array());
    $data = $this->defaultData;
    $datas[] = array($brl , $data);
    $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_SET,array());
    $data = $this->defaultData;
    $datas[] = array($brl , $data);
    $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','file1',Beak::M_SET,array());
    $data = $this->defaultData;
    $datas[] = array($brl , $data);
    $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','file2',Beak::M_SET,array());
    $data = $this->defaultData;
    $datas[] = array($brl , $data);
    $datas = BeakController::beakQuery($datas);
    
    $this->wait();
  }
  public function tearDown(){
    global $COCKATOO_ROOT;
    system('rm -rf ' . $COCKATOO_ROOT.'/datasource/test-layout');
  }
  
  public static function tearDownAfterClass() {
  }
  
  // @@@ revision (set seta del dela)
  public function testCache1(){
    // Make cache ( DEFAULT 300 )
    $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_GET,array(),array(Beak::COMMENT_KIND_CACHE));
    $datas = BeakController::beakSimpleQuery($brl);
    $exp = array_merge($this->defaultData,array(Beak::Q_UNIQUE_INDEX=>'path/to'));
    ksort($exp);
    $expects = json_encode($exp);
    ksort($datas);
    $actual  = json_encode($datas);
    $this->assertEquals($expects,$actual);
    // Update document
    $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_SET);
    $data = array('add' => 'ADD');
    $datas = BeakController::beakSimpleQuery($brl,$data);
    // Get from cache
    $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_GET,array(),array(Beak::COMMENT_KIND_CACHE));
    $datas = BeakController::beakSimpleQuery($brl);
    $exp = array_merge($this->defaultData,array(Beak::Q_UNIQUE_INDEX=>'path/to'));
    ksort($exp);
    $expects = json_encode($exp);
    ksort($datas);
    $actual  = json_encode($datas);
    $this->assertEquals($expects,$actual);
  }
  public function testCacheExp(){
    // Make cache ( 10 )
    $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_GET,array(),array(Beak::COMMENT_KIND_CACHE,Beak::COMMENT_KIND_CACHE_EXP.'=10'));
    $datas = BeakController::beakSimpleQuery($brl);
    $exp = array_merge($this->defaultData,array(Beak::Q_UNIQUE_INDEX=>'path/to'));
    ksort($exp);
    $expects = json_encode($exp);
    ksort($datas);
    $actual  = json_encode($datas);
    $this->assertEquals($expects,$actual);
    // Update document
    $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_SET,array(),array(Beak::COMMENT_KIND_PARTIAL));
    $data = array('add' => 'ADD');
    $datas = BeakController::beakQuery(array(array($brl,$data)));
    // Get from cache
    $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_GET,array(),array(Beak::COMMENT_KIND_CACHE,Beak::COMMENT_KIND_CACHE_EXP.'=10'));
    $datas = BeakController::beakSimpleQuery($brl);
    $exp = array_merge($this->defaultData,array(Beak::Q_UNIQUE_INDEX=>'path/to'));
    ksort($exp);
    $expects = json_encode($exp);
    ksort($datas);
    $actual  = json_encode($datas);
    $this->assertEquals($expects,$actual);
    sleep(11);
    // Cache expired and gat from origin.
    $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_GET,array(),array(Beak::COMMENT_KIND_CACHE,Beak::COMMENT_KIND_CACHE_EXP.'=10'));
    $datas = BeakController::beakSimpleQuery($brl);
    $exp = array_merge($this->defaultData,array(Beak::Q_UNIQUE_INDEX=>'path/to','add' => 'ADD'));
    ksort($exp);
    $expects = json_encode($exp);
    ksort($datas);
    $actual  = json_encode($datas);
    $this->assertEquals($expects,$actual);
  }
  public function wait() {
    usleep(100000);
  }
}
