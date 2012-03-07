#!/usr/bin/env php
<?php
/**
 * renew_pages.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/03/07
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
namespace wiki;
ini_set('log_errors','On');
ini_set('display_errors','On');
require_once(dirname(__FILE__) . '/../../def.php');
require_once(\Cockatoo\Config::COCKATOO_ROOT.'tools/beak/beak_walk.php');
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/actions/wiki/Lib.php');
require_once(\Cockatoo\Config::COCKATOO_ROOT.'action/actions/wiki/PageAction.php');
function PageCallback(&$brl,&$data){
  if ( preg_match('@^storage://wiki-storage/page/(.*)@',$brl,$matches) !== 0 ) {
    $action = new PageAction('action://wiki-action/wiki/PageAction');
    $action->session = array(\Cockatoo\Def::SESSION_KEY_POST =>array( 'op' => 'save',
                                    'origin' => $data['origin']),
                             'login' => array('user' => 'tool')
      );
    $action->args['P'] = $data['title'];
    $action->proc();
  }
}

$from = 'file';
$service = 'wiki';
$scheme = 'storage';
$froml = null;
$walk = new \Cockatoo\BeakWalk($from,$service,$scheme,$froml);
$walk->walk('\wiki\PageCallback');