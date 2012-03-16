#!/usr/bin/env php
<?php
/**
 * session_expired.php - ????
 *  
 * @package ????
 * @access public
 * @author  <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/03/16
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */

namespace wiki;
ini_set('log_errors','On');
ini_set('display_errors','On');
require_once(dirname(__FILE__) . '/../../def.php');
require_once(\Cockatoo\Config::COCKATOO_ROOT.'utils/session.php');
require_once(\Cockatoo\Config::COCKATOO_ROOT.'tools/beak/beak_walk.php');
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/actions/wiki/Lib.php');
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/actions/wiki/PageAction.php');

function SessionCallback(&$brl,&$data){
  $now = time();
  if ( preg_match('@^session://wiki-session/default/(.*)@',$brl,$matches) !== 0 ) {
    if ( $now > $data['_e'] ) {
      // expired session
      echo $data['_e'] . '  Expired (' .  strftime('%Y-%m-%d %H:%M:%S',$data['_e']) .') => '  . $brl . "\n";
      \Cockatoo\delSession($data['_u'],'wiki');
    }
  }
}

$from = 'file';
$service = 'wiki';
$scheme = 'session';
$froml = null;
$walk = new \Cockatoo\BeakWalk($from,$service,$scheme,$froml);
$walk->walk('\wiki\SessionCallback');
