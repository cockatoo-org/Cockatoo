#!/usr/bin/env php
<?php
/**
 * zoomon.php - Zookeeper monitor ( for operation )
 *  
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
namespace Cockatoo;
require_once(dirname(__FILE__) . '/../../def.php');
require_once(Config::COCKATOO_ROOT.'utils/zoo.php');

/**
 * Monitor daemon
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class ZooMon {
  const STATUS_OK      = 0;
  const STATUS_NOTICE  = 1;
  const STATUS_ALERT   = 2;
  const ALERT_THRESHOLD= 1;
  protected $sleep;
  protected $hour;
  protected $hosts;
  protected $report;
  protected $prior=null;
  protected $cur  =array();
  protected $date;
  protected $debug=1;
  /**
   * Constructor
   *
   *   Construct a object.
   *
   */
  public function __construct($sleep,$hour,$hosts,$alert,$report,$notice){
    $this->sleep  = $sleep;
    $this->hour   = $hour;
    if ( strcmp($hosts,'-')===0){
      $this->hosts = Config::$Zookeeper;
    }else{
      $this->hosts  = explode(',',$hosts);
    }
    $conf = array('hosts' => $this->hosts );
    Zoo::init($conf);
    $this->cmd_alert  = $alert;
    $this->cmd_notice = $notice;
    $this->cmd_report = $report;
    $this->date   = "";
  }
  /**
   * Main loop
   */
  public function main(){
    for(;;){
      $this->cur = array();
      $this->proc();
      $this->prior = $this->cur;
      sleep($this->sleep);
    }
  }

  private function proc ( ) {
    $this->get_status();
    $this->check_status();
    if ( $this->status === self::STATUS_OK ) {
      // Nothing to do
    }else{
      if ( $this->status & self::STATUS_NOTICE ) {
        $this->notice("[Notice] Zoo status changed");
      }
      if ( $this->status & self::STATUS_ALERT ) {
        $this->alert("[Alert] Zoo status alert !!!");
      }
    }
    $t = time();
    if ( $this->prior === null or strcmp(strftime('%H',$t),$this->hour) === 0 and strcmp(strftime('%F',$t),$this->date) !== 0) {
      $this->date = strftime('%F',$t);
      $this->debug( "# $this->prior # $this->date" );
      $this->report("[Report] Zoo status");
    }
  }

  private function get_status(){
    // Execute
    foreach ( $this->hosts as $host ) {
      $zoo = Zoo::$zooMap[$host];
      $this->cur[$host] = 'Unable to get status';
      try {
        $data=null;
        $groups = Zoo::getGroups($zoo);
        if ( ! $groups ) {
          Zoo::connect($host);
          continue;
        }
        foreach ($groups as $group) {
          $nodes = Zoo::getProcesses($group,$zoo);
          $data[$group] = $nodes;
        } 
        $this->cur[$host] = $data;
      }catch(\Exception $e){
      }
    }
    //$this->debug( "# STATUS #" . var_export($this->cur,1) );
  }

  private function check_status(){
    $this->status = self::STATUS_OK;
    if ( $this->prior === null ) {
      return; // OK
    }
    if ( !(strcmp(json_encode($this->prior),json_encode($this->cur))===0) ){
      $this->status = self::STATUS_NOTICE;
      foreach ( $this->cur as $zoo_node => $beaks ) {
        if ( ! is_array($beaks) ) {
          $this->status = self::STATUS_ALERT;
          return;
        }
        foreach( $beaks as $beak => $hosts ) {
          if ( ! is_array($beaks) ) {
            $this->status = self::STATUS_ALERT;
            return;
          }
          if ( count($hosts) < self::ALERT_THRESHOLD ) {
            $this->status = self::STATUS_ALERT;
            return;
          }
        }
      }
    }      
  }

  private function cmd($cmd,$subject){
    if ( $cmd === '-' ) {
      return;
    }
    $body  = strftime('%F %T') . "\n\n";
    $body .= var_export($this->cur,true);
    $this->debug( "$cmd\n** $subject **\n$body" );
    $hp = popen($cmd,'w');
    fwrite($hp,$subject."\n",strlen($subject."\n"));
    fwrite($hp,$body,strlen($body));
    $read = fread($hp,8192);
    pclose($hp);
  }
  private function report($subject){
    $this->cmd($this->cmd_report,$subject);
  }
  private function notice($subject){
    $this->cmd($this->cmd_notice,$subject);
  }

  private function alert($subject){
    $this->cmd($this->cmd_alert,$subject);
  }

  private function debug ( $msg ) {
    if ( $this->debug){
      print $msg . "\n";
    }
  }
}

if ( count($argv) < 5 ) {
  $msg = <<<_MSG_
Invalid arguments !

Usage:
  zoomon.php sleep-sec daliy-report-hour zoo-hosts alert-command [report-command] [notice-command]

  zoo-hosts:        Camma delimited
    <HOST/IP>:<PORT>,...
  alert-command:    "title" is given on COMMAND-LINE and "body" is put as STDIN.
    <ALERT-COMMAND>'title' <<<"body"
    "-" means skip
  report-command:
    use alert-command if you do not specify
  notice-command:
    use alert-command if you do not specify

Example:
  zoomon.php \
   60 \
   7 \
   '127.0.0.1:2181,127.0.0.2:2182,127.0.0.3:2182' \
   '/usr/local/cockatoo/operation/zookeeper/zoomon_mail.bash'
    
   - Check zookeeper every minutes.
   - Send report-mail every morning at 7 
   - Check destinations are these three node '127.0.0.1:2181' '127.0.0.2:2182' '127.0.0.3:2182'
   - Send Alert, Report and Notice  as a email.

_MSG_;
   print $msg;
   var_dump($argv);
   die ();
}
array_shift($argv);
$sleep  = array_shift($argv);
$hour   = array_shift($argv);
$hosts  = array_shift($argv);
$alert  = array_shift($argv);
$report = array_shift($argv);
$notice = array_shift($argv);
$report = ($report)?$report:$alert;
$notice = ($notice)?$notice:$alert;
$daemon = new ZooMon($sleep,$hour,$hosts,$alert,$report,$notice);
$daemon->main();
// zoomon.php 10 7  '127.0.0.1:2181' '/usr/local/cockatoo/operation/zookeeper/zoomon_mail.bash'