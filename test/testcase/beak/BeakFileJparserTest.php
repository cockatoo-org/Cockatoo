<?php
namespace Cockatoo;
require_once('/usr/local/cockatoo/def.php');
require_once (Config::COCKATOO_ROOT.'utils/beak.php');
require_once (Config::COCKATOO_ROOT.'utils/beaks/Cockatoo/BeakFile.php');

class BeakFileJparserTest extends \PHPUnit_Framework_TestCase
{
  function set(){
  }
  public function setUp(){
    $this->set();
    $this->defaultData = array( 
      'StrAscii' => "abcdefgHIJKLMNopqrstuVWXYZ0123456789",
      'StrEx' => "START<TAB=>\t<CRLF=>\r\n<DQ=>\"aa\"<SLASH=>\\n<SQ=>'<SP=> END",
      'StrUni' => "東京<tokyo>あaいうiuえおんeonパン屑",
      'Int' => 120,
      'BoolT' => true,
      'BoolF' => false,
      'Null' => NULL,
      'Array' => array ( 'AA','BB','CC' ),
      'ArrayHash' => array ( array( 'aa'=>'AA','bb'=>'BB'),array('cc'=>'ああ') ),
      'ArrayArray' => array ( array('AA','BB'),array('ああ') ),
      'Hash' => array ( 'xx' => 'XX' , 'yy' => 'いい' , 'ああ' ),
      'Float' => 34.56789,
      'BIN' => "\0aaa\1\255\254aaa"
      );
  }
  public function tearDown(){
  }

  public static function tearDownAfterClass() {
  }

  public function testEncode(){
    $jparser = new JParser();
    $actual = $jparser->encode($this->defaultData);
    $expects = '{"StrAscii":"abcdefgHIJKLMNopqrstuVWXYZ0123456789","StrEx":"START<TAB=>\t<CRLF=>\r\n<DQ=>\"aa\"<SLASH=>\\\\n<SQ=>\'<SP=> END","StrUni":"\u6771\u4eac<tokyo>\u3042a\u3044\u3046iu\u3048\u304a\u3093eon\u30d1\u30f3\u5c51","Int":120,"BoolT":true,"BoolF":false,"Null":null,"Array":["AA","BB","CC"],"ArrayHash":[{"aa":"AA","bb":"BB"},{"cc":"\u3042\u3042"}],"ArrayArray":[["AA","BB"],["\u3042\u3042"]],"Hash":{"xx":"XX","yy":"\u3044\u3044","0":"\u3042\u3042"},"Float":34.56789,"BIN":"@BIN@0061616101adac616161"}';
    $this->assertEquals($expects,$actual);
  }
  public function testEncodeStringNL(){
    $jparser = new JParser('',true);
    $actual = $jparser->encode($this->defaultData);
    $expects = '{"StrAscii":"abcdefgHIJKLMNopqrstuVWXYZ0123456789","StrEx":"START<TAB=>\t<CRLF=>\r'."\n".'<DQ=>\"aa\"<SLASH=>\\\\n<SQ=>\'<SP=> END","StrUni":"\u6771\u4eac<tokyo>\u3042a\u3044\u3046iu\u3048\u304a\u3093eon\u30d1\u30f3\u5c51","Int":120,"BoolT":true,"BoolF":false,"Null":null,"Array":["AA","BB","CC"],"ArrayHash":[{"aa":"AA","bb":"BB"},{"cc":"\u3042\u3042"}],"ArrayArray":[["AA","BB"],["\u3042\u3042"]],"Hash":{"xx":"XX","yy":"\u3044\u3044","0":"\u3042\u3042"},"Float":34.56789,"BIN":"@BIN@0061616101adac616161"}';
    $this->assertEquals($expects,$actual);
  }
  public function testEncodeElementNL(){
    $jparser = new JParser("\n",false);
    $actual = $jparser->encode($this->defaultData);
    $expects = '{'."\n"
      .'"StrAscii":"abcdefgHIJKLMNopqrstuVWXYZ0123456789",'."\n"
      .'"StrEx":"START<TAB=>\t<CRLF=>\r\n<DQ=>\"aa\"<SLASH=>\\\\n<SQ=>\'<SP=> END",'."\n"
      .'"StrUni":"\u6771\u4eac<tokyo>\u3042a\u3044\u3046iu\u3048\u304a\u3093eon\u30d1\u30f3\u5c51",'."\n"
      .'"Int":120,'."\n"
      .'"BoolT":true,'."\n"
      .'"BoolF":false,'."\n"
      .'"Null":null,'."\n"
      .'"Array":['."\n"
      .'"AA",'."\n"
      .'"BB",'."\n"
      .'"CC"'."\n"
      .'],'."\n"
      .'"ArrayHash":['."\n"
      .'{'."\n"
      .'"aa":"AA",'."\n"
      .'"bb":"BB"'."\n"
      .'},'."\n"
      .'{'."\n"
      .'"cc":"\u3042\u3042"'."\n"
      .'}'."\n"
      .'],'."\n"
      .'"ArrayArray":['."\n"
      .'['."\n"
      .'"AA",'."\n"
      .'"BB"'."\n"
      .'],'."\n"
      .'['."\n"
      .'"\u3042\u3042"'."\n"
      .']'."\n"
      .'],'."\n"
      .'"Hash":{'."\n"
      .'"xx":"XX",'."\n"
      .'"yy":"\u3044\u3044",'."\n"
      .'"0":"\u3042\u3042"'."\n"
      .'},'."\n"
      .'"Float":34.56789,'."\n"
      .'"BIN":"@BIN@0061616101adac616161"'."\n"
      .'}';
    $this->assertEquals($expects,$actual);
  }
  public function testEncodeElementNLStringNL(){
    $jparser = new JParser("\n",true);
    $actual = $jparser->encode($this->defaultData);
    $expects = '{'."\n"
      .'"StrAscii":"abcdefgHIJKLMNopqrstuVWXYZ0123456789",'."\n"
      .'"StrEx":"START<TAB=>\t<CRLF=>\r'."\n".'<DQ=>\"aa\"<SLASH=>\\\\n<SQ=>\'<SP=> END",'."\n"
      .'"StrUni":"\u6771\u4eac<tokyo>\u3042a\u3044\u3046iu\u3048\u304a\u3093eon\u30d1\u30f3\u5c51",'."\n"
      .'"Int":120,'."\n"
      .'"BoolT":true,'."\n"
      .'"BoolF":false,'."\n"
      .'"Null":null,'."\n"
      .'"Array":['."\n"
      .'"AA",'."\n"
      .'"BB",'."\n"
      .'"CC"'."\n"
      .'],'."\n"
      .'"ArrayHash":['."\n"
      .'{'."\n"
      .'"aa":"AA",'."\n"
      .'"bb":"BB"'."\n"
      .'},'."\n"
      .'{'."\n"
      .'"cc":"\u3042\u3042"'."\n"
      .'}'."\n"
      .'],'."\n"
      .'"ArrayArray":['."\n"
      .'['."\n"
      .'"AA",'."\n"
      .'"BB"'."\n"
      .'],'."\n"
      .'['."\n"
      .'"\u3042\u3042"'."\n"
      .']'."\n"
      .'],'."\n"
      .'"Hash":{'."\n"
      .'"xx":"XX",'."\n"
      .'"yy":"\u3044\u3044",'."\n"
      .'"0":"\u3042\u3042"'."\n"
      .'},'."\n"
      .'"Float":34.56789,'."\n"
      .'"BIN":"@BIN@0061616101adac616161"'."\n"
      .'}';
    $this->assertEquals($expects,$actual);
  }
}  
