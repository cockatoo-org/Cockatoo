<?php
/**
 * BeakNull.php - Beak driver : Do nothing
 *  
 * @access public
 * @package cockatoo-beaks
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
namespace Cockatoo;
require_once ($COCKATOO_ROOT.'utils/beak.php');

/**
 * Null request
 *
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 */
class BeakNull extends Beak {
  /**
   * Constructor
   * 
   * @see Action.php
   */
  public function __construct(&$brl,&$scheme,&$domain,&$collection,&$path,&$method,&$queries,&$comments,&$arg,&$hide) {
    parent::__construct($brl,$scheme,$domain,&$collection,$path,$method,$queries,$comments,$arg,$hide);
  }

  /**
   * Craete collection and Create index
   *   
   * @see Action.php
   */
  public function createColQuery(){
  }

  /**
   * Get all collections name
   *
   * @see Action.php
   */
  public function listColQuery() {
  }

  /**
   * Get all keys containing the collection.
   *
   * @see Action.php
   */
  public function listKeyQuery() {
  }

  /**
   * T.B.D @@@
   * 
   * @see Action.php
   */
  public function getaQuery() {
  }

  /**
   * Get document data
   *
   * @see Action.php
   */
  public function getQuery() {
  }
  /**
   * T.B.D @@@
   *
   * @see Action.php
   */
  public function delQuery() {
  }
  /**
   * T.B.D @@@
   *
   * @see Action.php
   */
  public function delaQuery() {
  }
  /**
   * Set multi document datas
   * 
   * @see Action.php
   */
  public function setaQuery(){
  }
  /**
   * Set document data
   *
   * @see Action.php
   */
  public function setQuery() {
  }
  /**
   * Move collection
   */
  public function mvColQuery() {
  }


  /**
   * Get operation results
   * 
   * @see Action.php
   */
  public function result() {
    return true;
  }
}

