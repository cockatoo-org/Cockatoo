<?php
namespace mongo;
/**
 * AccountAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/07/07
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */

class AccountAction extends \Cockatoo\AccountAction {
  protected $BASE_BRL = MongoConfig::USER_COLLECTION;
  protected $MAIL_FROM= MongoConfig::MAIL_FROM;
  protected $REPLY_TO = MongoConfig::MAIL_FROM;
  protected $REDIRECT = 'main';
  protected $EREDIRECT = 'main';
  public function proc(){
    $session = $this->getSession();
    if ( $session[\Cockatoo\AccountUtil::SESSION_LOGIN] ) {
      $s[\Cockatoo\AccountUtil::SESSION_LOGIN] = null;
      $this->updateSession($s);
      $this->setMovedTemporary(MongoConfig::TOP_PAGE);
    }else{
      $user_data = \Cockatoo\AccountUtil::get_account($this->BASE_BRL,'admin');
      $s[\Cockatoo\AccountUtil::SESSION_LOGIN] = $user_data;
      $this->updateSession($s);
      $this->setMovedTemporary(MongoConfig::TOP_PAGE);
    }
  }
}