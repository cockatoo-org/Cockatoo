#!/usr/bin/env php
<?php
namespace pwatch;
require_once('/usr/local/cockatoo/def.php');
//require_once(dirname(__FILE__) . '/../../def.php');
require_once(\Cockatoo\Config::COCKATOO_ROOT.'utils/beak.php');
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/actions/Cockatoo/UrlUtil.php');
declare(ticks = 1);

// svn propset svn:keywords "Date Rev Id" pwatch_daemon.php
// TZ=Asia/Tokyo phpdoc -t html -d source
/**
 * pwatch_daemon.php - ????
 *  
 * @package ????
 * @access public
 * @author Hiroaki.Kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/07/10
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */

/**
 * ??????????
 *
 * @author Hiroaki.Kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class PwatchDaemon {
  const NODEJS        = '/usr/local/nodejs/bin/node';
  const HTMLMON       = '/usr/local/cockatoo/tools/html/htmlmon.js';
  const LOOP_SLEEP    = 60000000;
  public function __construct(){
  }
  public function main() {
    pcntl_sigprocmask(SIG_BLOCK, array(SIGCHLD));
    while(true) {
      try {
        $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'pwatch','URLS','',\Cockatoo\Beak::M_GET_RANGE,array(),array());
        $settings = \Cockatoo\BeakController::beakSimpleQuery($brl,array('_u' => array('$gt' => '')));
        foreach ( $settings as $setting ) {
          $eurl = $setting['_u'];
          $now = time();
          if ( ! $setting['data'] or ($setting['data']['_t'] + $setting['interval']*60) < $now ) {
            $args = array ( self::HTMLMON,'-u',"'".$setting['url']."'",'-w','100','-t','60000','-j','1','-A');
            if ( $setting['style'] === 'on' ) {
              $args []='-S';
            }else{
              $args []='-F';
            }
            $cmd = self::NODEJS . ' ' . join($args,' ') . ' 2> /dev/null';
            \Cockatoo\Log::info(__CLASS__ . '::' . __FUNCTION__ . ' : Watch : ' . $setting['url'] . ' =cmd=> ' . $cmd);
            $proc = popen($cmd,'r');
            if ( is_resource($proc) ) {
              $json = '';
              while(!feof($proc)){
                $buf = fgets($proc);
                if ( $buf ){
                  $json = $buf;
                }
              }
              pclose($proc);
              $results = json_decode($json,true);
              foreach ( $results as $u => $d ) {
                if ( $u === '' ) {
                  $data['(unknown)'] = $d;
                  $data['(unknown)']['name'] = '(unknown)';
                }else if ( preg_match('@\.@',$u,$matches ) === 0 ) {
                  $data[$u] = $d;
                  $data[$u]['name'] = $u;
                }else {
                  $eu= \Cockatoo\UrlUtil::urlencode($u);
                  $data[$eu] = $d;
                  $data[$eu]['name'] = $u;
                }
              }
              $data['t'] = strftime('%Y-%m-%d %H:%M:%S',$now);
              $data['_t'] = $now;
              // Save data
              $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'pwatch',$eurl,$now,\Cockatoo\Beak::M_SET,array(),array());
              $ret = \Cockatoo\BeakController::beakSimpleQuery($brl,$data);
              // Save list
              $brl = \Cockatoo\brlgen(\Cockatoo\Def::BP_STORAGE,'pwatch','URLS',$eurl,\Cockatoo\Beak::M_SET,array(),array(\Cockatoo\Beak::COMMENT_KIND_OP_SET));
              $ret = \Cockatoo\BeakController::beakSimpleQuery($brl,array('data' => $data));
            }
            \Cockatoo\Log::info(__CLASS__ . '::' . __FUNCTION__ . ' : Done : ' . $setting['url']);
          }else{
            \Cockatoo\Log::info(__CLASS__ . '::' . __FUNCTION__ . ' : Skip : ' . $setting['url'] . ' untill : ' . strftime('%Y-%m-%d %H:%M:%S',$setting['data']['_t']+(int)$setting['interval']*60) );
          }
        }
      }catch ( \Exception $e ) {
        \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ . ' : Unexpect exception : ' . $e->getMessage(),$e);
      }
      usleep(self::LOOP_SLEEP);
    }
  }
}      
$options = getopt('f:',array('brl:','external','ipport:','worker:','maxreq:'));
$conf   =  $options['f'];
if ( $conf ) {
  $json = file_get_contents($conf);
  $content = json_decode($json,true);
  $brl     = $content['brl'];
  $external= $content['external'];
  $ipport  = $content['ipport'];
  $worker  = $content['worker'];
  $maxreq  = $content['maxreq'];
}
function option( &$options , $key, $default ) {
  return isset($options[$key])?$options[$key]:$default;
}

$brl     = option($options,'brl',$brl);
$external= option($options,'external',$external);
$ipport  = option($options,'ipport',$ipport);
$worker  = option($options,'worker',$worker); 
$maxreq  = option($options,'maxreq',$maxreq); 

if ( ! $external ) {
  $external = $ipport;
}

if ( false ) {
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

\Cockatoo\Log::warn('PwatchDaemon start : ' . $brl . ' ' . $external . ' ' . $ipport . ' ' . $worker);
$pwatch = new PwatchDaemon($brl,$external,$ipport,$worker,$maxreq);
$pwatch->main();
\Cockatoo\Log::warn('PwatchDaemon end : ' . $brl . ' ' . $external . ' ' . $ipport . ' ' . $worker);
