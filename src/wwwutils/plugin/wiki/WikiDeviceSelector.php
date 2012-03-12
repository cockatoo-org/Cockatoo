<?php
/**
 * WikiDeviceSelector.php - ????
 *  
 * @package ????
 * @access public
 * @author  <hiroaki.kubota@mail.rakuten.com> 
 * @create 2012/03/07
 * @version $Id$
 * @copyright Copyright (C) 2012, rakuten 
 */
namespace wiki;
class Wikideviceselector extends \Cockatoo\DefaultDeviceSelector {
  protected     $deviceTree = array('android'=>'default');
}