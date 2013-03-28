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

/*   protected function login_hook(&$user_data) { */
/*     $session = $this->getSession(); */
/*     if ( $this->submit === 'login' ) { */
/*       return array( */
/*         'user' => 'admin', */
/* //      'user' => 'crumb', */
/*         'root' => '1', */
/*         'writable' => '1' */
/*         ); */
/*     } */
/*     parent::login_hook($user_data); */
/*   } */
  protected function first_hook() {
    parent::first_hook();
    $session =& $this->getSession();
    if ( $session[\Cockatoo\Def::SESSION_KEY_POST] && ! $session[\Cockatoo\Def::SESSION_KEY_POST]['r'] ) {
      $session[\Cockatoo\Def::SESSION_KEY_POST]['r'] = $session[\Cockatoo\Def::SESSION_KEY_REQ]['Referer'];
    }
  }
}