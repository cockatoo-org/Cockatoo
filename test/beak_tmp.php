#!/usr/bin/env php
<?php
namespace Cockatoo;
require_once(dirname(__FILE__) . '/../src/def.php');
require_once(Config::COCKATOO_ROOT.'utils/beak.php');
function assert($res,$expt){
  if ( $res === $expt ) {
  }else{
    print "Assert \n RES:".$res ."\n EXP:".$expt ."\n";
  }
}
Config::$BEAKS['storage'] = 'Cockatoo\BeakMongo';
Config::$BeakLocation['storage://test-storage/'] = array('127.0.0.1:27017');
//Config::$BEAKS['storage'] = 'Cockatoo\BeakFile';

$brl = 'storage://test-storage/test?ccol&_is=i,d#renew';
\Cockatoo\BeakController::beakQuery(array($brl));
// Save latest
$brl = 'storage://test-storage/test/foo?set';
$data = array('_u' => 'foo' , 'i' => 1 , 'd' => 'A' , 'a' => 'A');
\Cockatoo\BeakController::beakQuery(array(array($brl,$data)));
$brl = 'storage://test-storage/test?keys';
$ret = \Cockatoo\BeakController::beakQuery(array($brl));
ksort($ret[$brl]);
$ret = json_encode($ret[$brl]);
$exp = '["foo"]';
assert($ret,$exp);

$brl = 'storage://test-storage/test/bar?set';
$data = array('_u' => 'bar' , 'i' => 2 , 'd' => 'A' , 'a' => 'A');
\Cockatoo\BeakController::beakQuery(array(array($brl,$data)));
$brl = 'storage://test-storage/test/baz?set';
$data = array('_u' => 'baz' , 'i' => 2 , 'd' => 'B' , 'a' => 'A');
\Cockatoo\BeakController::beakQuery(array(array($brl,$data)));
$brl = 'storage://test-storage/test/foo/bar?set';
$data = array('_u' => 'foo/bar' , 'i' => 3 , 'd' => 'C' , 'a' => 'B');
\Cockatoo\BeakController::beakQuery(array(array($brl,$data)));
$brl = 'storage://test-storage/test/bar?get&_flts=i';
$ret = \Cockatoo\BeakController::beakQuery(array($brl));
ksort($ret[$brl]);
$ret = json_encode($ret[$brl]);
$exp = '{"i":2}';
assert($ret,$exp);

$brl = 'storage://test-storage/test/bar?get&_exts=i';
$ret = \Cockatoo\BeakController::beakQuery(array($brl));
ksort($ret[$brl]);
$ret = json_encode($ret[$brl]);
$exp = '{"_u":"bar","a":"A","d":"A"}';
assert($ret,$exp);

$brl = 'storage://test-storage/test/?getA';
$data = array('_u' => array('baz','bar'));
$ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$data)));
ksort($ret[$brl]);
$ret = json_encode($ret[$brl]);
$exp = '{"bar":{"_u":"bar","i":2,"d":"A","a":"A"},"baz":{"_u":"baz","i":2,"d":"B","a":"A"}}';
assert($ret,$exp);

$brl = 'storage://test-storage/test/?getA&_flts=_u';
$data = array('d' => array('A','C'));
$ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$data)));
ksort($ret[$brl]);
$ret = json_encode($ret[$brl]);
$exp = '{"bar":{"_u":"bar"},"foo":{"_u":"foo"},"foo\/bar":{"_u":"foo\/bar"}}';
assert($ret,$exp);

$brl = 'storage://test-storage/test?ccol&_is=i,a';
\Cockatoo\BeakController::beakQuery(array($brl));

$brl = 'storage://test-storage/test/?getA&_flts=_u';
$data = array('a' => array('A'));
$ret = \Cockatoo\BeakController::beakQuery(array(array($brl,$data)));
ksort($ret[$brl]);
$ret = json_encode($ret[$brl]);
$exp = '{"bar":{"_u":"bar"},"baz":{"_u":"baz"},"foo":{"_u":"foo"}}';
assert($ret,$exp);

$brl = 'storage://test-storage/test/?sys&_s=idxs';
$ret = \Cockatoo\BeakController::beakQuery(array($brl));
sort($ret[$brl]);
$ret = json_encode($ret[$brl]);
$exp = '["a","d","i"]';
assert($ret,$exp);
