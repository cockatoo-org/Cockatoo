#!/usr/bin/env php
<?php
/**
 * gateway_controller.php - Gateway controller
 *  
 * @access public
 * @package cockatoo-gateway
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
require_once(dirname(__FILE__) . '/../def.php');
require_once(Config::COCKATOO_ROOT.'utils/beak.php');

declare(ticks = 1);

/**
 * Controller
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class GatewayController {
  /**
   * for GatewayController
   */
  const GatewayDaemon       = 'gateway/gateway_daemon.php';
  const GatewayHealthCheck  = 'gateway/gateway_healthcheck.php';
  /**
   * Watching interval (usec)
   */
  const LOOP_SLEEP = 1000000;
  /**
   * Forking wait (usec)
   */
  const FORK_WAIT  = 100000;
  /**
   * Waiting time (usec), It will raise kill-signal if this time has pasted since a terminate-packet was sent to child-process.
   */
  const KILL_WAIT  = 100000;
  /**
   * Available BRL list 
   *    Array ( $brl => $location )
   */
  protected $beakinfo   = array();
  /**
   * Current BRL list 
   *    Array ( $brl => array($ipc,$pid,$hpid) )
   */
  protected $gatewaies   = array();
  /**
   * Gateway-healthcheck PID
   */
  protected $hpid        = 0;
  /**
   * Gateway-daemon PID
   */
  protected $killing      = array();
  /**
   * Beak location getter
   */
  protected $beakLocation;
  /**
   * Watch count
   */
  protected $count = array();
  /**
   * Terminator connection
   */
  protected $termFlg   = false;
  /**
   * Signal handler (SIGTERM)
   *
   * @param int $no signal number
   */
  function term($no){
    $this->termFlg = true;
  }
  /**
   * Constructor
   *
   *   Construct a object.
   *
   */
  public function __construct(){
  }
  /**
   * Main loop
   *
   *   Send signal(SIGTERM) to exit this function.
   */
  public function main() {
    pcntl_sigprocmask(SIG_BLOCK, array(SIGCHLD));
    pcntl_signal ( SIGTERM , array(&$this,"term"));
    while(true) {
      $this->healthCheck();

      $beakinfo = $this->getBeakInfo();

      // Merge
      // Deleted location
      $diff = array_diff_key($beakinfo,$this->beakinfo);
      foreach($diff as $brl => $locations ){
        // Start gateway
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : Start ' . $brl);
        $this->gatewaies[$brl] = $this->forkChild($brl,$beakinfo[$brl]);
      }
      $diff = array_diff_key($this->beakinfo,$beakinfo);
      foreach($diff as $brl => $locations ){
        // Stop gateway
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : Stop ' . $brl);
        unset($this->beakinfo[$brl]);
        $pset = $this->gatewaies[$brl];
        $this->killing[]= $pset;
        unset($this->gatewaies[$brl]);
      }
      foreach($this->beakinfo as $brl => $locations ){
        $diff1 = array_diff($locations,$beakinfo[$brl]);
        $diff2 = array_diff($beakinfo[$brl],$locations);
        if ( $diff1 or $diff2 ) {
          // Restart gateway
          Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : Restert ' . $brl);
          $pset = $this->gatewaies[$brl];
          $this->killing[]= $pset;
          $this->gatewaies[$brl] = $this->forkChild($brl,$beakinfo[$brl]);
          break;
        }
      }
      $this->beakinfo = $beakinfo;
      usleep(self::LOOP_SLEEP);
      if ( $this->termFlg ) {
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : Enter terminator');
        foreach ( $this->gatewaies as $brl => $pset ) {
          Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Send signal to a child : ' . $pid);
          $this->killPids($pset);
        }
        usleep(self::KILL_WAIT);
        foreach ( $this->gatewaies as $brl => $pset ) {
          $this->waitPid($pset);
          unlink($pset[0]);
        }
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : Leave terminator');
        return 0;
      }
    }
  }
  /**
   * Initializer
   */
  public function init() {
    $this->beakLocation = BeakLocationGetter::singleton();
  }
  /**
   * Update available BRL list
   *
   * @return Array Returns available BRL list
   */
  protected function getBeakInfo() {
    return $this->beakLocation->getLocation(array(Def::BP_ACTION . '://'));
  }

  /**
   * Create new process
   *
   * @param $brl         target BRL
   * @param $locations   target location (IP:PORT)
   * @return int   forked process-id
   */
  protected function forkChild($brl,$locations){
    $pid = $this->forkChildDaemon($brl,$locations);
    $frontIPC = brl2file(Def::IPC_GW_SEGMENT,$brl);
    $realIPC = $frontIPC . '.' . $pid;
    usleep(self::FORK_WAIT);
    Log::info(__CLASS__ . '::' . __FUNCTION__ . ' : ' . $realIPC . ' => ' . $frontIPC);
    // @@@ symlink don't support force option, and also it couldn't do in atmic.
    // symlink ( $realIPC , $frontIPC );
    system ( "ln -sf $realIPC $frontIPC" );
    $hpid = $this->forkHealthCheck($brl);
    return array($realIPC,$pid,$hpid);
  }

  protected function forkChildDaemon($brl,$locations){
    $pid = pcntl_fork();
    if ($pid === -1) {
    } else if ($pid) {
      // Parent
      Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : Forking a child : ' . $pid);
      return $pid;
    } else {
      // Child
      try {
        $pid = posix_getpid();
        $frontDSN = brl2ipc(Def::IPC_GW_SEGMENT,$brl)  . '.' . $pid;
        $args = array_keys($locations);
        array_unshift($args,$frontDSN);
        pcntl_exec(self::GatewayDaemon,$args);
        Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : Cannot execute : ' . self::GatewayDaemon , $args);
        die('Cannot execute : ' . $child);
      } catch ( \Exception $e ) {
        die($e->getMessage());
      }
      exit();
    }
  }
  protected function forkHealthCheck($brl){
    $pid = pcntl_fork();
    if ($pid === -1) {
    } else if ($pid) {
      // Parent
      Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : Forking a child : ' . $pid);
      return $pid;
    } else {
      // Child
      try {
        $args = array($brl);
        pcntl_exec(self::GatewayHealthCheck,$args);
        Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : Cannot execute : ' . self::GatewayHealthCheck , $args);
        die('Cannot execute : ' . $child);
      } catch ( \Exception $e ) {
        die($e->getMessage());
      }
      exit();
    }
  }


  /**
   * Healthcheck the children
   *
   */
  protected function healthCheck () {
    foreach( $this->killing as $pset ){
      Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Send signal to a child : ' . $pid);
      $this->killPids($pset);
    }
    usleep(self::KILL_WAIT);
    foreach( $this->killing as $i => $pset ){
      $this->waitPid($this->killing[$i]);
      if ( $this->killing[$i][1] === 0 and $this->killing[$i][2] === 0 ) {
        // Clean up
        unlink($this->killing[$i][0]);
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : A child is dead : rm => ' . $realIPC);
        unset($this->killing[$i]);
      }
    }
    foreach($this->gatewaies as $brl => $pset ){
      if ( $this->waitPid($this->gatewaies[$brl] ) ) {
        $this->killing []= $this->gatewaies[$brl];
        $this->gatewaies[$brl] = $this->forkChild($brl,$this->beakinfo[$brl]);
      }
    }
  }
  /**
   * Wait for dead child.
   *
   * @param $pid specified process-id
   * @return boolean success/failure
   */
  protected function waitPid (&$pset) {
    $ret = false;
    if ( $pset[1] ) {
      if (  pcntl_waitpid($pset[1],$status,WNOHANG) != 0 ) {
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : A child is dead : ' . $pset[1]);
        $pset[1] = 0;
        $ret = true;
      }
    }
    if ( $pset[2] ) {
      if (  pcntl_waitpid($pset[2],$status,WNOHANG) != 0 ) {
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : A child is dead : ' . $pset[2]);
        $pset[2] = 0;
        $ret = true;
      }
    }
    return $ret;
  }

  protected function killPids($pset){
    if ( $pset[1] ) {
      posix_kill($pset[1], SIGTERM);
    }
    if ( $pset[2] ) {
      posix_kill($pset[2], SIGTERM);
    }
  }
}

if ( count($argv) != 1 ) {
  $msg = <<<_MSG_
Invalid arguments !

Usage:
  gateway_controller

Example:
  gateway_controller

_MSG_;
   print $msg;
   var_dump($argv);
   die ();
}

Log::warn('GatewayController start : ');
$ctrl = new GatewayController();
$ctrl->init();
$ctrl->main();
Log::warn('GatewayController end : ');
