#!/usr/bin/env php
<?php
/**
 * mongomon.php - Gateway daemon
 *  
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;

/**
 * Watch daemon
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class MongoMon {
  protected $sleep;
  protected $hour;
  protected $cmd_status;
  protected $cmd_alert;
  protected $cmd_report;
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
  public function __construct($sleep,$hour,$status,$alert,$report){
    $this->sleep  = $sleep;
    $this->hour   = $hour;
    $this->cmd_status = $status;
    $this->cmd_alert  = $alert;
    $this->cmd_report = $report;
  }
  /**
   * Main loop
   */
  public function main(){
    pcntl_sigprocmask(SIG_BLOCK, array(SIGCHLD));
    for(;;){
      $this->cur = array('Unable to get status');
      $this->proc();
      $this->prior = $this->cur;
      sleep($this->sleep);
    }
  }

  private function proc ( ) {
    $this->get_status();
    $ret = $this->check_status();
    if ( $ret ) {
      $this->alert("[Alert] Mongo status changed !");
    }
    $t = time();
    if ( $this->prior === null or strcmp(strftime('%H',$t),$this->hour) === 0 and strcmp(strftime('%F',$t),$this->date) !== 0) {
      $this->date = strftime('%F',$t);
      $this->debug( "# $this->prior # $this->date" );
      $this->report("[Report] Mongo status");
    }
  }

  private function get_status(){
    try {
      // Execute
      $hp = popen($this->cmd_status,'r');
      $str = '';
      while(true) {
        $read = fread($hp,8192);
        if ( ! $read ) {
          break;
        }
        $str .= $read;
      }
      pclose($hp);
      // Parse
      $flg = 0;
      $stat = '';
      foreach( preg_split("@\r?\n@",$str) as $line ) {
        if ( preg_match('@{@',$line,$matches) !== 0 and ! $flg) {
          $flg = 1;
          $stat = '{';
        }elseif ( preg_match('@>\s*bye\s*$@',$line,$matches) !== 0 ) {
          break;
        }elseif ($flg){
          if ( preg_match('@(.*)ISODate\(([^\)]+)\)(.*)@',$line,$matches) !== 0 ) {
            $stat .= $matches[1] . $matches[2] . $matches[3];
          }else{
            $stat .= $line;
          }
        }
      }
      $data = json_decode($stat,1);
      if ( $data !== null and isset($data['members']) ) {
        $this->cur = array();
        foreach($data['members'] as $member ) {
//          $this->cur[$member['name']] = array( 
//            'status' => $member['stateStr'],
//            'optime' => $member['optimeDate'] 
//            );
          $this->cur[$member['name']] = $member['stateStr'] . ' : ' . $member['optimeDate'];
        }
      }
    }catch (\Exception $e ) {
    }
  }

  private function check_status(){
    if ( $this->prior === null ) {
      return false; // First
    }
    return !(strcmp(json_encode($this->prior),json_encode($this->cur))===0);
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
  private function alert($subject){
    $this->cmd($this->cmd_alert,$subject);
  }
  private function report($subject){
    $this->cmd($this->cmd_report,$subject);
  }

  private function debug ( $msg ) {
    if ( $this->debug){
      print $msg . "\n";
    }
  }
}

if ( count($argv) < 4 ) {
  $msg = <<<_MSG_
Invalid arguments !

Usage:
  mongomon.php   sleep-sec  daliy-report-hour status-command  alert-command report-command

  status-command:   

  alert-command:    "title" is given on COMMAND-LINE and "body" is put as STDIN.
    <ALERT-COMMAND>'title' <<<"body"
    "-" means skip
  report-command:
    use alert-command if you do not specify

Example:
  mongomon.php \
   60 \
   7 \
   '/usr/local/mongo/mongoctrl status' \
   '/usr/local/cockatoo/operation/mongo/mongomon_mail.bash' \
   'mail-to@foobar.com,mail-to@barbaz.com' \
   'mail-from@foobar.com' \
   '[STG]'


_MSG_;
   print $msg;
   var_dump($argv);
   die ();
}
array_shift($argv);
$sleep  = array_shift($argv);
$hour   = array_shift($argv);
$status = array_shift($argv);
$alert  = array_shift($argv);
$report = array_shift($argv);
$report = ($report)?$report:$alert;
$daemon = new MongoMon($sleep,$hour,$status,$alert,$report);
$daemon->main();
// mongomon.php 60 7  '/usr/local/mongo/mongoctrl status' '/usr/local/cockatoo/operation/mongo/mongomon_mail.bash'