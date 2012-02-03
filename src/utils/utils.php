<?php
/**
 * utils.php - Utilities
 *  
 * @access public
 * @package cockatoo-utils
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;

/**
 * Get time as micro second
 *
 * @return Array Returns micro second
 */
function utime() {
  return explode(' ',microtime());
}
/**
 * Get difference between the micro second objects
 *
 * @param Array $t1  Micro second object
 * @param Array $t2  Micro second object
 * @return int Returns difference as micro second
 */
function diffutime($t1,$t2) {
  return (($t1[0]-$t2[0]) + ($t1[1]-$t2[1]))*1000000;
}

