<?php
namespace pwatch;
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
  protected $BASE_BRL = PwatchConfig::USER_COLLECTION;
  protected $MAIL_FROM= PwatchConfig::MAIL_FROM;
  protected $REPLY_TO = PwatchConfig::MAIL_FROM;
  protected $REDIRECT = 'main';
  protected $EREDIRECT= 'main';
}
