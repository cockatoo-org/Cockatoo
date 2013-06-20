#!/usr/bin/env php
<?php
/**
 * action_controller.php - Action control daemon
 *  
 * @access public
 * @package cockatoo-action
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
class ActionController {
  /**
   * for ActionController
   */
  const DeviceDaemon       = 'action/device_daemon.php';
  const ChildDaemon        = 'action/child_daemon.php';
  const HealthCheckDaemon  = 'action/action_healthcheck.php';
  /**
   * Poll interval (usec)
   */
  const LOOP_SLEEP = 1000000;
  /**
   * Number of trying to kill child-processes when terminating.
   */
  const NUM_KILL_TRY = 10;
  /**
   * Watching interval (usec)
   */
  const TERM_WAIT = 10000000;
  /**
   * Waiting time (usec), It will raise kill-signal if this time has pasted since a terminate-packet was sent to child-process.
   */
  const KILL_WAIT  = 2000000;
  /**
   * Device-daemon's PID
   */
  protected $deviceDaemon = null;
  /**
   * HealthCheck-daemon's PID
   */
  protected $healthcheckeDaemon = null;
  /**
   * Action-daemon's PID
   */
  protected $actions   = array();
  /**
   * Number of Action-daemon.
   */
  protected $numChild  = 10;
  /**
   * Number of request to die after processing.
   */
  protected $maxreq;

  /**
   * Beak location setter
   */
  protected $beakLocation;
  /**
   * zeroMQ
   */
  protected $serviceDSN;
  /**
   * zeroMQ
   */
  protected $childDSN;
  /**
   * IPC name prefix
   */
  const IPC_SEGMENT = 'A';
  /**
   * Signal flg
   */
  protected $hupFlg = false;
  /**
   * Signal flg
   */
  protected $termFlg = false;
  /**
   * Signal handler (SIGTERM)
   *
   * @param int $no signal number
   */
  function term($no){
    $this->termFlg = true;
  }
  /**
   * Signal handler (SIGHUP)
   *
   * @param int $no signal number
   */
  function hup($no){
    $this->hupFlg = true;
  }
  protected $brls;
  protected $ipport;
  /**
   * Constructor
   *
   *   Construct a object.
   *
   * @param String $brl      Action's brl
   * @param String $ipport   Service port
   * @param String $numChild Number of Action-process
   * @param String $maxreq   
   */
  public function __construct($brls,$external,$ipport,$numChild,$maxreq){
    $this->brls        = $brls;
    $this->external    = $external;
    $this->numChild    = $numChild;
    $this->maxreq      = $maxreq;
    $this->serviceDSN  = 'tcp://'.$ipport;
    $this->beakLocation= BeakLocationSetter::singleton();
    $this->childDSN    = brl2ipc(self::IPC_SEGMENT,implode("=",$this->brls));
  }

  /**
   * Main loop
   *
   *   Send signal(SIGTERM) to exit this function.
   */
  public function main() {
    pcntl_sigprocmask(SIG_BLOCK, array(SIGCHLD));
    pcntl_signal ( SIGTERM , array(&$this,"term"));
    pcntl_signal ( SIGHUP , array(&$this,"hup"));
    while(true) {
      try {
        if ( $this->healthcheckDaemon ) {
          if ( ! $this->waitPid($this->healthcheckDaemon) ) {
            $this->healthcheckDaemon = null;
            Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : Healthcheck daemon down ! ');
            $this->killDeviceDaemon(SIGKILL);
          }
        }
        if ( ! $this->healthcheckDaemon ) {
          $this->healthcheckDaemon = $this->forkChild(self::HealthCheckDaemon,array($this->brls[0],'tcp://'.$this->external,self::TERM_WAIT*2));
        }

        // Check and Fork device-child
        if ( $this->deviceDaemon ) {
          if ( ! $this->waitPid($this->deviceDaemon) ) {
            $this->deviceDaemon = null;
            $this->hupFlg = true;
            Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : Device daemon down ! ');
          }
        }
        if ( ! $this->deviceDaemon ) {
          $this->deviceDaemon = $this->forkChild(self::DeviceDaemon,array($this->serviceDSN,$this->childDSN));
        }

        // Health check
        $this->healthCheck();
        // Not enough ( add children )
        $this->startChildren();
        if ( $this->hupFlg ) {
          $this->hupFlg = false;
          Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : enter hup');
          foreach( $this->brls as $brl ) {
            $this->beakLocation->delete($brl,$this->external);
          }
          usleep(self::TERM_WAIT);
          $this->killHealthCheckDaemon(SIGHUP);
          $this->killChildren(SIGTERM);
          usleep(self::TERM_WAIT);
          $this->startChildren($this->numChild);
          $this->count = 0;
          Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : leave hup');
        }
        if ( $this->termFlg ) {
          Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : enter terminator');
          foreach( $this->brls as $brl ) {
            $this->beakLocation->delete($brl,$this->external);
          }
          $this->killHealthCheckDaemon(SIGTERM);
          usleep(self::TERM_WAIT);
          $this->killChildren(SIGTERM);
          for ( $i = 0 ; $i < self::NUM_KILL_TRY; $i++ ) {
            $this->healthCheck();
            if ( count($this->actions) === 0 ) {
              break;
            }
            usleep(self::KILL_WAIT);
          }
          $this->killChildren(SIGKILL);
          $this->killDeviceDaemon(SIGKILL);
          Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : leave terminator');
          return 0;
        }
      }catch ( \Exception $e ) {
        Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : Unexpect exception : ' . $e->getMessage(),$e);
      }
      usleep(self::LOOP_SLEEP);
      try {
          foreach( $this->brls as $brl ) {
            $this->beakLocation->regist($brl,$this->external,'');
          }
      }catch ( \Exception $e ) {
        Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : Unexpect exception : ' . $e->getMessage(),$e);
      }
    }
  }
  
  /**
   * Create new process
   *
   * @param $child child-process 
   * @param $arg   child options
   * @return int   forked process-id
   */
  protected function forkChild($child,$args){
    $pid = pcntl_fork();
    if ($pid === -1) {
    } else if ($pid) {
      // Parent
      Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : Forking a child : ' . $pid);
    } else {
      // Child
      try {
        pcntl_exec($child,$args);
        Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : Cannot execute : ' . $child,$args);
        die('Cannot execute ' . $child);
      } catch ( \Exception $e ) {
        die($e->getMessage());
      }
      exit();
    }
    return $pid;
  }

  protected function startChildren($num=null){
    if ( ! $num ) {
      $num = $this->numChild - count($this->actions);
    }
    for( $i = 0 ; $i < $num ; $i++){
      $maxreq = $this->maxreq + rand(0,$this->maxreq/8);
      $this->actions [] = $this->forkChild(self::ChildDaemon,array($this->childDSN,$maxreq));
    }
  }

  protected function killDeviceDaemon ($sig) {
    if ( $this->deviceDaemon ) {
      Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : send signal to the device-daemon');
      posix_kill($this->deviceDaemon,$sig);
    }
  }
  protected function killHealthCheckDaemon ($sig) {
    if ( $this->healthcheckDaemon ) {
      posix_kill($this->healthcheckDaemon,$sig);
    }
  }
  protected function killChildren ($sig) {
    foreach($this->actions as $pid){
      if ( $pid ) {
        posix_kill($pid,$sig);
      }
    }
  }

  /**
   * Healthcheck the children
   *
   */
  protected function healthCheck () {
    foreach(array_keys($this->actions) as $idx ){
      $pid = $this->actions[$idx];
      if ( $pid ) {
        if ( ! $this->waitPid($pid) ) {
          unset($this->actions[$idx]);
        }
      }
    }
  }

  /**
   * Wait for dead child.
   *
   * @param $pid specified process-id
   * @return boolean success/failure
   */
  protected function waitPid ($pid) {
    if (  pcntl_waitpid($pid,$status,WNOHANG) !== 0 ) {
      // Clean up
      Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : child is dead : ' . $pid);
      return false;
    }
    return true;
  }
}

$options = getopt('f:',array('brl:','external','ipport:','worker:','maxreq:'));
$conf   =  $options['f'];
if ( $conf ) {
  $json = file_get_contents($conf);
  $content = json_decode($json,true);
  $brls     = $content['brl'];
  $external= $content['external'];
  $ipport  = $content['ipport'];
  $worker  = $content['worker'];
  $maxreq  = $content['maxreq'];
}
function option( &$options , $key, $default ) {
  return isset($options[$key])?$options[$key]:$default;
}

$brls    = option($options,'brl',$brls);
$external= option($options,'external',$external);
$ipport  = option($options,'ipport',$ipport);
$worker  = option($options,'worker',$worker); 
$maxreq  = option($options,'maxreq',$maxreq); 

if ( ! $external ) {
  $external = $ipport;
}

if ( ! $brls or ! $ipport or ! $worker ) {
  $msg = <<<_MSG_
Invalid arguments !

Usage:
  action_controller.php [-f config.json] [--brl  <BRL>] [--external <HOST:PORT>] [--ipport <IP:PORT>] [--worker <NUM-WORKER>] [--maxreq <NUM-REQUEST-TO-DIE>]

Example:
  action_controller.php  -f action.conf 
  action_controller.php  --brl action://news-action  --ipport 127.0.0.1:9999  --worker 10
  action_controller.php  -f action.conf --worker 10

_MSG_;
   print $msg;
   var_dump($argv);
   die ();
}
if ( ! is_array($brls) ){
  $brls = array($brls);
}
Log::warn('ActionController start : ' . var_export($brls,1) . ' ' . $external . ' ' . $ipport . ' ' . $worker);
$ctrl = new ActionController($brls,$external,$ipport,$worker,$maxreq);
$ctrl->main();
Log::warn('ActionController end : ' . var_export($brls,1) . ' ' . $external . ' ' . $ipport . ' ' . $worker);
