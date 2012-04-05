<?php
namespace Cockatoo;
require_once('/usr/local/cockatoo/def.php');
require_once(Config::COCKATOO_ROOT.'utils/beak.php');

class BeakTest extends \PHPUnit_Framework_TestCase
{
    public function __construct(){
    }
    public function setUp(){
    }
    //   public function tearDown(){
    //     print "tearDown\n";
    //   }
    public static function tearDownAfterClass() {
    }

    public function testParseBrlDomain(){
        list($P,$D,$C,$p,$m,$q,$c) = parse_brl('layout://name.domain/');
        $this->assertEquals('layout',$P);
        $this->assertEquals('name.domain',$D);
        $this->assertEquals('get',$m);
    }
    public function testParseBrlCollection(){
        list($P,$D,$C,$p,$m,$q,$c) = parse_brl('layout://name.domain/default');
        $this->assertEquals('layout',$P);
        $this->assertEquals('name.domain',$D);
        $this->assertEquals('default',$C);
        $this->assertEquals('get',$m);
    }
    public function testParseBrlCollection2(){
        list($P,$D,$C,$p,$m,$q,$c) = parse_brl('layout://name.domain/default?get');
        $this->assertEquals('layout',$P);
        $this->assertEquals('name.domain',$D);
        $this->assertEquals('default',$C);
        $this->assertEquals('get',$m);
    }
    public function testParseBrlPath(){
        list($P,$D,$C,$p,$m,$q,$c) = parse_brl('layout://name.domain/default/path/to');
        $this->assertEquals('layout',$P);
        $this->assertEquals('name.domain',$D);
        $this->assertEquals('default',$C);
        $this->assertEquals('/path/to',$p);
        $this->assertEquals('get',$m);
    }
    public function testParseBrlMethod(){
        list($P,$D,$C,$p,$m,$q,$c) = parse_brl('layout://name.domain/default/path/to?get');
        $this->assertEquals('layout',$P);
        $this->assertEquals('name.domain',$D);
        $this->assertEquals('default',$C);
        $this->assertEquals('/path/to',$p);
        $this->assertEquals('get',$m);
    }
    public function testParseBrlQuery1(){
        list($P,$D,$C,$p,$m,$q,$c) = parse_brl('layout://name.domain/default/path/to?get&foo=FOO&bar=BAR');
        $this->assertEquals('layout',$P);
        $this->assertEquals('name.domain',$D);
        $this->assertEquals('default',$C);
        $this->assertEquals('/path/to',$p);
        $this->assertEquals('get',$m);
        $qs = parse_brl_query($q);
        $this->assertEquals('FOO',$qs['foo']);
        $this->assertEquals('BAR',$qs['bar']);
    }
    public function testParseBrlQuery2(){
        list($P,$D,$C,$p,$m,$q,$c) = parse_brl('layout://name.domain/default/path/to?get&[]foo=FOO&[]foo=BAR&[]foo=BAZ');
        $qs = parse_brl_query($q);
        $this->assertEquals('FOO',$qs['foo'][0]);
        $this->assertEquals('BAR',$qs['foo'][1]);
        $this->assertEquals('BAZ',$qs['foo'][2]);
    }
    public function testParseBrlQuery3(){
        list($P,$D,$C,$p,$m,$q,$c) = parse_brl('layout://name.domain/default/path/to?get&foo=FOO&bar');
        $qs = parse_brl_query($q);
        $this->assertEquals('FOO',$qs['foo']);
    }
    public function testParseBrlQuery4(){
        list($P,$D,$C,$p,$m,$q,$c) = parse_brl('layout://name.domain/default/path/to?get&foo="FOO&FOO"&bar=\'BAR&BAR\'');
        $qs = parse_brl_query($q);
        $this->assertEquals('FOO&FOO',$qs['foo']);
        $this->assertEquals('BAR&BAR',$qs['bar']);

    }
    public function testParseBrlComment(){
        list($P,$D,$C,$p,$m,$q,$c) = parse_brl('layout://name.domain/default/path/to?get&foo=FOO&bar=BAR#critical#cacheable=3600');
        $this->assertEquals('layout',$P);
        $this->assertEquals('name.domain',$D);
        $this->assertEquals('default',$C);
        $this->assertEquals('/path/to',$p);
        $this->assertEquals('get',$m);
        $qs = parse_brl_query($q);
        $this->assertEquals('FOO',$qs['foo']);
        $this->assertEquals('BAR',$qs['bar']);
        $this->assertEquals('critical',$c[0]);
        $this->assertEquals('cacheable=3600',$c[1]);
    }
    public function testParseBrlNotEnough1(){
        try {
            list($P,$D,$C,$p,$m,$q,$c) = parse_brl('layout://?get');
        }catch( \Exception $e){
            return;
        }
        $this->assertTrue(FALSE, 'Should not to be successful.');
    }
    public function testParseBrlNotEnough2(){
        try {
            list($P,$D,$C,$p,$m,$q,$c) = parse_brl('layout://#critical');
        }catch( \Exception $e){
            return;
        }
        $this->assertTrue(FALSE, 'Should not to be successful.');
    }
    public function testParseBrlNotEnough3(){
        try {
            list($P,$D,$C,$p,$m,$q,$c) = parse_brl('layout:///foo');
        }catch( \Exception $e){
            return;
        }
        $this->assertTrue(FALSE, 'Should not to be successful.');
    }
    public function testParseBrlSign(){
        list($P,$D,$C,$p,$m,$q,$c) = parse_brl('layout://service-layout/default/path/to/?get&foo=FOO+FOO+FOO&bar=BAR%3DBAR%3ABAR#critical+#cacheable=3600:0');
        $this->assertEquals('layout',$P);
        $this->assertEquals('service-layout',$D);
        $this->assertEquals('default',$C);
        $this->assertEquals('/path/to/',$p);
        $this->assertEquals('get',$m);
        $qs = parse_brl_query($q);
        $this->assertEquals('FOO FOO FOO',$qs['foo']);
        $this->assertEquals('BAR=BAR:BAR',$qs['bar']);
        $this->assertEquals('critical ',$c[0]);
        $this->assertEquals('cacheable=3600:0',$c[1]);
    }
    public function testParseBrlMulti(){
        list($P,$D,$C,$p,$m,$q,$c) = parse_brl('layout://service-layout/default/path/to/?get&foo=FOO%E3%81%82&bar=BAR#%E3%81%82critical#cacheable');
        $this->assertEquals('layout',$P);
        $this->assertEquals('service-layout',$D);
        $this->assertEquals('default',$C);
        $this->assertEquals('/path/to/',$p);
        $this->assertEquals('get',$m);
        $qs = parse_brl_query($q);
        $this->assertEquals('FOOあ',$qs['foo']);
        $this->assertEquals('BAR',$qs['bar']);
        $this->assertEquals('あcritical',$c[0]);
        $this->assertEquals('cacheable',$c[1]);
    }

    public function testBrl2IPC(){
        $ipc = brl2ipc('segment','layout://name.domain/default/path/to?get&foo=FOO&bar=BAR#critical#cacheable=3600');
        $this->assertEquals('ipc:///tmp/segment.layout.name.domain',$ipc);
    }

    public function testBrlGen(){
        $brl = brlgen('layout','service','default','/path/to/',Beak::M_GET,array('foo'=>'FOO','bar'=>'BAR'),array('critical','cacheable'));
        $exp = "layout://service-layout/default/path/to/?get&foo=FOO&bar=BAR#critical#cacheable";
        $this->assertEquals($exp,$brl);
    }
    public function testBrlGenF(){
        try {
            $brl = brlgen('INVALID','service','default','/path/to/',Beak::M_GET,array('foo'=>'FOO','bar'=>'BAR'),array('critical','cacheable'));
        }catch( \Exception $e){
            return;
        }
        $this->assertTrue(FALSE, 'Should not to be successful.');
    }
    public function testBrlGenSign(){
        $brl = brlgen('layout','service','default','/path/to/',Beak::M_GET,array('foo'=>'FOO FOO FOO','bar'=>'BAR=BAR:BAR'),array('critical ','cacheable='));
        $exp = "layout://service-layout/default/path/to/?get&foo=FOO+FOO+FOO&bar=BAR%3DBAR%3ABAR#critical+#cacheable%3D";
        $this->assertEquals($exp,$brl);
    }
    public function testBrlGenMulti(){
        $brl = brlgen('layout','service','default','/path/to/',Beak::M_GET,array('foo'=>'あ','bar'=>'BAR'),array('あ','cacheable'));
        $exp = "layout://service-layout/default/path/to/?get&foo=%E3%81%82&bar=BAR#%E3%81%82#cacheable";
        $this->assertEquals($exp,$brl);
    }
}
