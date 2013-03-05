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

  protected function login_hook(&$user_data) {
    return array(
      'user' => 'admin',
      'root' => '1',
      'writable' => '1'
      );
//    return array(
//      'user' => 'crumb',
//      'writable' => '1'
//      );
  }
  protected function already_hook(&$user_data) {
    return null;
  }
  protected function first_hook() {
    parent::first_hook();
    $session =& $this->getSession();
    $session[\Cockatoo\Def::SESSION_KEY_POST]['r'] = $session[\Cockatoo\Def::SESSION_KEY_REQ]['Referer'];
  }
}