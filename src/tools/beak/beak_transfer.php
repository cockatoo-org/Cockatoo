<?php
/**
 * beak_transfer.php - Beak transfer library
 *  
 * @access public
 * @package cockatoo-tools
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
require_once(Config::COCKATOO_ROOT.'utils/beak.php');
require_once(Config::COCKATOO_ROOT.'utils/stcontents.php');
class BeakImporter {
  public $EXECLUDE = array(
    '@^\.svn$@',
    '@^\.\.?$@'
    );
  static $BEAK_DRIVERS = array (
    'file'  => 'Cockatoo\BeakFile'   ,
    'mongo' => 'Cockatoo\BeakMongo'  ,
    'null'  => 'Cockatoo\BeakNull'  ,
    null
    );
  private $TO_BEAKS;
  private $charset;
  private $type;
  private $expire;
  private $to_location;
  public function __construct($to,$brl,$charset='AUTO',$type='AUTO',$to_location = null,$expire=null){
    $this->charset = $charset;
    $this->type    = $type;
    $this->expire  = $expire;
    $this->fi = finfo_open(\FILEINFO_MIME_ENCODING);
    $to_driver = self::$BEAK_DRIVERS[$to];
    if ( ! $to_driver ) {
      throw new \Exception( 'Unexpect driver ! : ' . $to . ' => ' . $to_driver );
    }
    foreach ( Config::$SYS_BEAKS as $k => $v ) {
      $this->TO_BEAKS[$k]   = $to_driver;
    }
    list($S,$D,$C,$p,$m,$q,$c) = parse_brl($brl);
    $base_brl = $S . '://' . $D . '/';
    $this->to_location  =$to_location?array($to_location):Config::$BeakLocation[$base_brl];

    Log::info('import to [' . $to_driver . '(' . $this->to_location[0] . ')] => ' . $brl);
    print('import to [' . $to_driver . '(' . $this->to_location[0] . ')] => ' . $brl . "\n");
  }
  function __destruct(){
    finfo_close($this->fi);
  }
  function import_all($path,$brl){
    // print 'importing : ' . $path . ' => ' . $brl . "\n";

    if ( is_dir($path) ) {
      if ($dh = opendir($path)) {
        while (($file = readdir($dh)) !== false) {
          $skip = false;
          foreach ( $this->EXECLUDE as $pattern ) {
            if ( preg_match($pattern,$file,$matches) != 0 ) {
              $skip = true;
              break;
            }
          }
          if ( $skip ) {
            continue;
          }
          $this->import_all($path . '/' . $file ,$brl . '/' . $file);
        }
      }
    }else {
      $type = (strcmp($this->type,'AUTO')===0)?FileContentType::get($path):$this->type;
      $this->import($path,$brl,$type);
    }
  }
  static function check_utf8_convert(&$content){
    $cmp = mb_convert_encoding($content, 'UTF-8','UTF-8');
    if ( $cmp !== $content) {
      $charset = 'EUC-JP';
      if ( mb_check_encoding($content, $charset) ) {
        return $charset;
      }
      $charset = 'SHIFT-JIS';
      if ( mb_check_encoding($content, $charset) ) {
        return $charset;
      }
      return 'UNKNOWN';
    }
    return 'UTF-8';
  }

  static function utf8_convert(&$content,$charset){
    return mb_convert_encoding($content, 'UTF-8',$charset);
  }

  function import($file,$brl,$type){
    if ( is_file($file) ) {
      $content = file_get_contents($file);
      $mime = finfo_file ($this->fi, $file);
      if ( strcmp($mime,'binary')!==0){
        $charset = $this->charset;
        if ( strcmp($charset,'AUTO')===0 ) {
          $charset = self::check_utf8_convert($content);
        }
        if ( strcmp($charset,'UTF-8')!==0 ) {
          Log::info('  (convert) : ' . $file . '  ' . $charset .' => '.'UTF-8');
          print('  (convert) : ' . $file . '  ' . $charset .' => '.'UTF-8' . "\n");
          $content = self::utf8_convert($content,$charset);
        }
      }

      print($brl . '   (' . $type . ')' . "\n");
      if ( StaticContent::save($brl,$type,$description,$content,$this->TO_BEAKS,$this->expire) ) {
        print('success'.' => '.$brl.' ('.$type.')' . ' ( exp : '.$this->expire.')' ."\n");
      }else {
        print('failure'.' => '.$brl.' ('.$type.')' . '(exp:'.$this->expire.')' ."\n");
      }
    }
  }
}

class BeakTransfer {
  static $BEAK_DRIVERS = array (
    'file'  => 'Cockatoo\BeakFile'   ,
    'mongo' => 'Cockatoo\BeakMongo'  ,
    'null'  => 'Cockatoo\BeakNull'   ,
    null
    );
  private $TO_BEAKS;
  private $FROM_BEAKS;
  private $scheme;
  private $prefix;
  private $base_brl;
  private $from_location;
  private $to_location;
  public function __construct($from,$to,$prefix,$scheme,$from_location=null,$to_location=null){
    $this->prefix = $prefix;
    $this->scheme = $scheme;
    $from_driver = self::$BEAK_DRIVERS[$from];
    $to_driver = self::$BEAK_DRIVERS[$to];
    if ( ! $from_driver or ! $to_driver ) {
      throw new \Exception( 'Unexpect driver ! : ' . $from . ' => ' . $from_driver . ' , ' . $to . ' => ' . $to_driver );
    }
    foreach ( Config::$SYS_BEAKS as $k => $v ) {
      $this->TO_BEAKS[$k]   = $to_driver;
      $this->FROM_BEAKS[$k] = $from_driver;
    }
    $this->base_brl = brlgen($this->scheme,$this->prefix,'','','');
    $this->from_location=$from_location?array($from_location):Config::$BeakLocation[$this->base_brl];
    $this->to_location  =$to_location?array($to_location):Config::$BeakLocation[$this->base_brl];
    Log::info('transfer from [' . $from_driver . '('.$this->from_location[0].')] to [' . $to_driver . '(' . $this->to_location[0] . ')');
    print('transfer from [' . $from_driver . '('.$this->from_location[0].')] to [' . $to_driver . '(' . $this->to_location[0] . ')' . "\n");
  }
  /**
   * Transfer all collections
   *  
   */
  function transfer_all($renew = true,$callback=null){
    Log::info('transfer_all : ' . $this->scheme . '://' . $this->prefix . '-' . $this->scheme);
    print('transfer_all : ' . $this->scheme . '://' . $this->prefix . '-' . $this->scheme . "\n");
    $brl = brlgen($this->scheme,$this->prefix,'','',Beak::M_COL_LIST);
    Config::$BeakLocation[$this->base_brl] = $this->from_location;
    $collections = BeakController::beakQuery(array($brl),$this->FROM_BEAKS);
    foreach ( $collections[$brl] as $collection ) {
      $collection = chop($collection,'/');
      $this->transfer_collection($collection,$renew,$callback);
    }
  }
  /**
   * Transfer collection
   *  
   * @param String $collection BRL collection
   * @param String $renew Drop and create collection if specified true
   * @param String $callback Data editor
   */
  function transfer_collection($collection,$renew = true,$callback=null){
    Log::info('transfer_collection : ' . $this->scheme . ' : ' . $this->prefix . ' : ' . $collection);
    print('transfer_collection : ' . $this->scheme . ' : ' . $this->prefix . ' : ' . $collection . "\n");
    $original = $collection;
    // Get collection info
    $brl   = brlgen($this->scheme,$this->prefix,$original,'',Beak::M_SYSTEM,array(Beak::Q_SYS=>'idxs'));
    Config::$BeakLocation[$this->base_brl] = $this->from_location;
    $ret = BeakController::beakQuery(array($brl),$this->FROM_BEAKS);
    $idxs = implode(',',$ret[$brl]);

    if ( $renew ) {
      $collection=$collection.'.tmp';
      $brl = brlgen($this->scheme,$this->prefix,$collection,'',Beak::M_CREATE_COL,array(Beak::Q_INDEXES=>$idxs),array(Beak::COMMENT_KIND_RENEW));
      Config::$BeakLocation[$this->base_brl] = $this->to_location;
      $ret = BeakController::beakQuery(array($brl),$this->TO_BEAKS);
    } else {
      $brl = brlgen($this->scheme,$this->prefix,$collection,'',Beak::M_CREATE_COL,array(Beak::Q_INDEXES=>$idxs),array());
      Config::$BeakLocation[$this->base_brl] = $this->to_location;
      $ret = BeakController::beakQuery(array($brl),$this->TO_BEAKS);
    }
    $brl   = brlgen($this->scheme,$this->prefix,$original,'',Beak::M_KEY_LIST);
    Config::$BeakLocation[$this->base_brl] = $this->from_location;
    $paths = BeakController::beakQuery(array($brl),$this->FROM_BEAKS);

    $queries = array();
    $count = 0;
    if ( isset($paths[$brl]) ) {
      foreach ( $paths[$brl] as $path ) {
        $brl = brlgen($this->scheme,$this->prefix,$original,$path,Beak::M_GET);
        Config::$BeakLocation[$this->base_brl] = $this->from_location;
        $ret = BeakController::beakQuery(array($brl),$this->FROM_BEAKS);
        $data = &$ret[$brl];
        $brl = brlgen($this->scheme,$this->prefix,$collection,$path,Beak::M_SET,array());
        if ( $callback ) {
          $data = $callback($data);
        }
        $queries []= array($brl,$data);
        $count++;
        if ( $count >= 100 ) {
          Config::$BeakLocation[$this->base_brl] = $this->to_location;
          $ret = BeakController::beakQuery($queries,$this->TO_BEAKS);
          foreach( $ret as  $b => $r) {
            if ( $r ) {
              Log::info($r.' => '.$b);
              print($r.' => '.$b . "\n");
            }else {
              Log::error('failure'.' => '.$b);
              print('failure'.' => '.$b . "\n");
            }
          }
          $queries = array();
          $count = 0;
        }
      }
      Config::$BeakLocation[$this->base_brl] = $this->to_location;
      $ret = BeakController::beakQuery($queries,$this->TO_BEAKS);
      foreach( $ret as  $b => $r) {
        if ( $r ) {
          Log::info($r.' => '.$b);
          print($r.' => '.$b . "\n");
        }else {
          Log::error('failure'.' => '.$b);
          print('failure'.' => '.$b . "\n");
        }
      }
    }
    if ( $renew ) {
      $brl = brlgen($this->scheme,$this->prefix,$collection,'',Beak::M_MV_COL,array(Beak::Q_NEWNAME=>$original));
      $ret = BeakController::beakQuery(array($brl),$this->TO_BEAKS);
    }
  }
}

function parse_in($in){
  if ( $in and  preg_match('@^([^,]+)(?:,(\S+))?@',$in,$matches) != 0 ) {
    return array($matches[1],$matches[2]);
  }
  return null;
}
