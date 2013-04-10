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
if ( Config::$UseZookeeper ) {
  require_once(Config::COCKATOO_ROOT.'utils/zoo.php');
}

class ZookeeperWatch {
  /**
   * Memory leak. (64MB=67108864)
   */
  const MEM_THRESHOLD = 67108864;
//  const MEM_THRESHOLD =     1043000;
  /**
   * Watching interval (nsec)
   */
  const LOOP_SLEEP = 1000000;
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
   * @param int $arg Argument.....
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

    if ( Config::$UseZookeeper ) {
      while(true) {
        try {
            Zoo::init(array('hosts' => Config::$UseZookeeper ));
            break;
        }catch(\Exception $e){
          Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . $e->getMessage(),$e);
        }
      }
      usleep(self::LOOP_SLEEP);
    }

    while(true) {
      if ( Config::$UseZookeeper ) {
        try {
          $data=null;
          $groups = Zoo::getGroups();
          if ( $groups === null ) {
            throw new \Exception('Unable get groups');
          }
          foreach ($groups as $group) {
            $nodes = Zoo::getProcesses($group);
            if ( $nodes === null  ) {
              throw new \Exception('Unable get nodes ('.$group.')');
            }
            foreach ( $nodes as $node ) {
              $info = Zoo::getData($group,$node);
              if ( $info === null ) {
                $info = array();
              }
              $data[$group][$node] = json_decode($info,1);
            }
          }
          $json = json_encode($data);
          clearstatcache(Config::$ZookeeperCacheFile);
          if ( ! is_file(Config::$ZookeeperCacheFile) or strcmp(md5_file(Config::$ZookeeperCacheFile),md5($json)) !== 0 ){
            Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : ' . '(zoo) Location changed ! ',$data);
            $tmpfile = Config::$ZookeeperCacheFile . '.tmp';
            file_put_contents($tmpfile,$json);
            system ( 'mv ' . $tmpfile . ' ' . Config::$ZookeeperCacheFile ) ;
          }
        }catch(\Exception $e){
          Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : ' . $e->getMessage(),$e);
        }
      }
      usleep(self::LOOP_SLEEP);
      if ( $this->termFlg ) {
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : Enter terminator');
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : Leave terminator');
        return 0;
      }
      $usage = memory_get_usage(true);
      if ( $usage > self::MEM_THRESHOLD ) {
        Log::warn(__CLASS__ . '::' . __FUNCTION__ . ' : Too much memory used. : ' . $usage );
        reexec_myself();
        return 1;
      }
      Log::trace(__CLASS__ . '::' . __FUNCTION__ . ' : Memory. : ' . $usage );
    }
  }
}
function reexec_myself(){
  global $argv;
  $pid = pcntl_fork();
  if ($pid === -1) {
    // Fail
    return;
  } else if ($pid) {
    // Parent
    return;
  } else {
    $myself = array_shift($argv);
    pcntl_exec($myself,$argv);
    Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : Cannot execute : ' . $myself,$argv);
  }
}
Log::warn('ZookeeperWatch start');
$zoo = new ZookeeperWatch();
$zoo->main();
Log::warn('ZookeeperWatch end');
