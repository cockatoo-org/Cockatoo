<?php
namespace wiki;
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
  protected $BASE_BRL = WikiConfig::USER_COLLECTION;
  protected $MAIL_FROM= WikiConfig::MAIL_FROM;
  protected $REPLY_TO = WikiConfig::MAIL_FROM;
  protected $REDIRECT = 'main';
  protected $EREDIRECT = 'main';
}