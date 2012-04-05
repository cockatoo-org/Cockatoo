<?php
namespace Cockatoo;
require_once('/usr/local/cockatoo/def.php');
require_once(Config::COCKATOO_ROOT.'utils/beak.php');

class BeakMongoTest extends \PHPUnit_Framework_TestCase
{
  function set(){
      Config::$BeakLocation = array (
        'layout://unittest-layout/' => array('127.0.0.1:27017'),
        );
    Config::$BEAKS = array (
      Def::BP_LAYOUT   => 'Cockatoo\BeakMongo'   ,
      null
      );
  }
  public function setUp(){
  $this->set();
  $brl = brlgen(Def::BP_LAYOUT,'unittest','device','',Beak::M_CREATE_COL,array(),array(Beak::COMMENT_KIND_RENEW));
  $ret = BeakController::beakQuery(array($brl));
  $this->defaultData = array( 'key' => 'value' ,
                              'int' => 1,
                              'list' => array('foo','bar','baz'),
                              'hash' => array(
                                'foo' => 'FOO',
                                'bar' => 'BAR',
                                'baz' => 'BAZ') );
  
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
}

public static function tearDownAfterClass() {
}

// @@@ revision ( seta del dela)
public function testBeakUpdateRev(){
# Update with REV
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_SET,array(),array(Beak::COMMENT_KIND_REV,Beak::COMMENT_KIND_PARTIAL));
  $data = array('add' => 'ADD');
  $datas = BeakController::beakQuery(array(array($brl,$data)));
  $this->wait();
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_GET);
  $datas = BeakController::beakQuery(array($brl));
  $expects = 'ADD';
  $actual  = $datas[$brl]['add'];
  $this->assertEquals($expects,$actual);
  $this->assertTrue(isset($datas[$brl][Beak::ATTR_REV]),'Rev column not found !');
# Update success by ignoreing REV
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_SET,array(),array(Beak::COMMENT_KIND_REV,Beak::COMMENT_KIND_PARTIAL));
  $data = array('add2' => 'ADD2');
  $datas = BeakController::beakQuery(array(array($brl,$data)));
  $this->assertTrue($datas[$brl],'Upload failure !');
  $this->wait();
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_GET);
  $datas = BeakController::beakQuery(array($brl));
  $expects = 'ADD2';
  $actual  = $datas[$brl]['add2'];
  $this->assertEquals($expects,$actual);
# Update failure by REV
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_SET,array(),array(Beak::COMMENT_KIND_REV,Beak::COMMENT_KIND_PARTIAL));
  $data = array('add3' => 'ADD3',Beak::ATTR_REV => 1);
  $datas = BeakController::beakQuery(array(array($brl,$data)));
  $actual  = $datas[$brl];
  $this->assertFalse($datas[$brl],'Unexpected success to update');
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_GET);
  $datas = BeakController::beakQuery(array($brl));
  $this->assertFalse(isset($datas[$brl]['add3']),'Unexpected column');
}

public function testBeakUpdate(){
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_SET,array(),array(Beak::COMMENT_KIND_PARTIAL));
  $data = array('add' => 'ADD');
  $datas = BeakController::beakQuery(array(array($brl,$data)));
  
  $this->wait();
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_GET);
  $datas = BeakController::beakQuery(array($brl));
  
  $exp = array_merge($this->defaultData,array(Beak::Q_UNIQUE_INDEX => 'path/to','add' => 'ADD'));
  ksort($exp);
  $expects = json_encode($exp);
  ksort($datas[$brl]);
  $actual  = json_encode($datas[$brl]);
  $this->assertEquals($expects,$actual);
  
}
public function testBeakReplace(){
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_SET);
  $data = array(Beak::Q_UNIQUE_INDEX => 'path/to','replace' => 'REP');
  $datas = BeakController::beakQuery(array(array($brl,$data)));
  
  $this->wait();
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_GET);
  $datas = BeakController::beakQuery(array($brl));
  
  ksort($data);
  $expects = json_encode($data);
  ksort($datas[$brl]);
  $actual  = json_encode($datas[$brl]);
  $this->assertEquals($expects,$actual);
}
public function testBeakSets(){
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','',Beak::M_SET_ARRAY,array(),array(Beak::COMMENT_KIND_PARTIAL));
  $data = array(
    array(Beak::Q_UNIQUE_INDEX => 'path/to','add' => 'ADD'),
    array(Beak::Q_UNIQUE_INDEX => 'path/new','add' => 'NEW'));
  $datas = BeakController::beakQuery(array(array($brl,$data)));
  
  $this->wait();
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_GET);
  $datas = BeakController::beakQuery(array($brl));
  
  $exp = array_merge($this->defaultData,array(Beak::Q_UNIQUE_INDEX => 'path/to','add' => 'ADD'));
  ksort($exp);
  $expects = json_encode($exp);
  ksort($datas[$brl]);
  $actual  = json_encode($datas[$brl]);
  
  $this->assertEquals($expects,$actual);
  
}
public function testBeakDel(){
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_DEL);
  $datas = BeakController::beakQuery(array($brl));
  
  $this->wait();
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','path/to',Beak::M_GET);
  $datas = BeakController::beakQuery(array($brl));
  $actual   = json_encode($datas);
  $expects  = '{"layout:\/\/unittest-layout\/device\/path\/to?get":null}';
  $this->assertEquals($expects,$actual);
}
public function testBeakDels(){
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','',Beak::M_DEL_ARRAY);
  $data [Beak::Q_UNIQUE_INDEX]= array('path/to','file1');
  $datas = BeakController::beakQuery(array(array($brl,$data)));
  
  $this->wait();

  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','',Beak::M_GET_ARRAY);
  $data [Beak::Q_UNIQUE_INDEX]= array('path/to','file1');
  $datas = BeakController::beakQuery(array(array($brl,$data)));

  $actual   = json_encode($datas);
  $expects  = '{"layout:\/\/unittest-layout\/device\/?getA":[]}';
  $this->assertEquals($expects,$actual);
}

public function testBeakSimpleGet(){
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','',Beak::M_GET);
  $datas = BeakController::beakQuery(array($brl));
  
  $exp = array_merge($this->defaultData,array(Beak::Q_UNIQUE_INDEX=>''));
  ksort($exp);
  $expects = json_encode($exp);
  ksort($datas[$brl]);
  $actual  = json_encode($datas[$brl]);
  $this->assertEquals($expects,$actual);
}
public function testBeakFilteredGet(){
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','',Beak::M_GET,array(Beak::Q_FILTERS=>'key,int,ignore'));
  $datas = BeakController::beakQuery(array($brl));
  
  $exp['int'] = $this->defaultData['int'];
  $exp['key'] = $this->defaultData['key'];
  ksort($exp);
  $expects = json_encode($exp);
  ksort($datas[$brl]);
  $actual  = json_encode($datas[$brl]);
  $this->assertEquals($expects,$actual);
}
public function testBeakExceptedGet(){
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','',Beak::M_GET,array(Beak::Q_EXCEPTS=>'key,int,ignore'));
  $datas = BeakController::beakQuery(array($brl));
  
  $exp = array_merge($this->defaultData,array(Beak::Q_UNIQUE_INDEX=>''));
  unset($exp['key']);
  unset($exp['int']);
  ksort($exp);
  $expects = json_encode($exp);
  ksort($datas[$brl]);
  $actual  = json_encode($datas[$brl]);
  $this->assertEquals($expects,$actual);
}

public function testBeakSimpleGets() {
  $brls = array();
  $brl1  = brlgen(Def::BP_LAYOUT,'unittest','device','',Beak::M_GET);
  $brl2  = brlgen(Def::BP_LAYOUT,'unittest','device','/path/to',Beak::M_GET);
  $brls [] = $brl1;
  $brls [] = $brl2;
  $datas = BeakController::beakQuery($brls);
  
  $exp = array_merge($this->defaultData,array(Beak::Q_UNIQUE_INDEX=>''));
  ksort($exp);
  $expects = json_encode($exp);
  ksort($datas[$brl1]);
  $actual  = json_encode($datas[$brl1]);
  $this->assertEquals($expects,$actual);
  
  $exp = array_merge($this->defaultData,array(Beak::Q_UNIQUE_INDEX=>'path/to'));
  ksort($exp);
  $expects = json_encode($exp);
  ksort($datas[$brl2]);
  $actual  = json_encode($datas[$brl2]);
  $this->assertEquals($expects,$actual);
}

public function testBeakSimpleGetF1(){
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','/nothing',Beak::M_GET);
  $datas = BeakController::beakQuery(array($brl));
  $expects = null;
  $actual  = $datas[$brl];
  $this->assertEquals($expects,$datas[$brl]);
}
public function testBeakSimpleGetF2(){
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','/nothing',Beak::M_GET,array(),array('critical'));
  try {
    $datas = BeakController::beakQuery(array($brl));
  }catch( \Exception $e){
    return;
  }
  $this->assertTrue(FALSE, 'Should not to be successful.');
}
public function testBeakSimpleGetF3(){
  try {
    $brl  = 'invalid://test.device./nothing?get';
    $datas = BeakController::beakQuery(array($brl));
  }catch( \Exception $e){
    return;
  }
  $this->assertTrue(FALSE, 'Should not to be successful.');
}
public function testBeakSimpleGetF4(){
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','/nothing','foo');
  try {
    $datas = BeakController::beakQuery(array($brl));
  }catch( \Exception $e){
    return;
  }
  $this->assertTrue(FALSE, 'Should not to be successful.');
}

public function testBeakGets(){
  $keys = array('','invalid','path/to','file1');
  $arg = array(Beak::Q_UNIQUE_INDEX => $keys);
  // create query
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','',Beak::M_GET_ARRAY);
  $datas = BeakController::beakQuery(array(array($brl,$arg)));
  foreach ( $keys as $key ){
    ksort($datas[$brl]);
    if ( $key === 'invalid' ) {
      $this->assertFalse(is_array($datas[$brl][$key]), 'Unexpected success !!' . $key);
    }else {
      $exp = array_merge($this->defaultData,array(Beak::Q_UNIQUE_INDEX=>$key));
      ksort($exp);
      $expects = json_encode($exp);
      ksort($datas[$brl][$key]);
      $actual  = json_encode($datas[$brl][$key]);
      $this->assertTrue(is_array($datas[$brl][$key]), 'Fail to get.' . $key);
      $this->assertEquals($expects,$actual);
    }
  }
}

public function wait() {
  usleep(100000);
}
  public function testBeakKeyListAll(){
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','',Beak::M_KEY_LIST);
  $datas = BeakController::beakQuery(array($brl));
  $exp = array('','file1','file2','path/','path/to');
  sort($exp);
  $expects  = json_encode($exp);
  sort($datas[$brl]);
  $actual   = json_encode($datas[$brl]);
  $this->assertEquals($expects,$actual);
}

public function testBeakColList(){
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','','',Beak::M_COL_LIST);
  $datas = BeakController::beakQuery(array($brl));
  $expects  = json_encode(array('device'));
  ksort($datas[$brl]);
  $actual   = json_encode($datas[$brl]);
  $this->assertEquals($expects,$actual);
}

public function testBeakIndex(){
  // Create index and Input data
  $brl = brlgen(Def::BP_LAYOUT,'unittest','device','',Beak::M_CREATE_COL,array(Beak::Q_INDEXES=>'key,int'),array());
  $ret = BeakController::beakQuery(array($brl));
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','A',Beak::M_SET,array());
  $data = array('key'=>'value1','int' => 2);
  $datas[] = array($brl , $data);
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','B',Beak::M_SET,array());
  $data = array('key'=>'value2','int' => 2);
  $datas[] = array($brl , $data);
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','C/D',Beak::M_SET,array());
  $data = array('key'=>'value3','int' => 3);
  $datas[] = array($brl , $data);
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','C/D/F',Beak::M_SET,array());
  $data = array('key'=>'value2','int' => 4);
  $datas[] = array($brl , $data);
  $datas = BeakController::beakQuery($datas);

  // Fetch by int
  $arg = array('int' => array(2,3));
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','',Beak::M_GET_ARRAY);
  $datas = BeakController::beakQuery(array(array($brl,$arg)));
  ksort($datas[$brl]);
  foreach ( $datas[$brl] as $key => $data ){
    ksort($datas[$brl][$key]);
  }
  $actual = json_encode(array_keys($datas[$brl]));
  $expects= '["A","B","C\/D"]';
  $this->assertEquals($expects,$actual);
  // Fetch by key
  $arg = array('key' => array('value1','value2'));
  $brl  = brlgen(Def::BP_LAYOUT,'unittest','device','',Beak::M_GET_ARRAY);
  $datas = BeakController::beakQuery(array(array($brl,$arg)));
  ksort($datas[$brl]);
  $actual = json_encode(array_keys($datas[$brl]));
  $expects= '["A","B","C\/D\/F"]';
  $this->assertEquals($expects,$actual);
}
}
