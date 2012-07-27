<?php
namespace yslowviewer;
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
  protected $BASE_BRL = YslowviewerConfig::USER_COLLECTION;
  protected $MAIL_FROM= YslowviewerConfig::MAIL_FROM;
  protected $REPLY_TO = YslowviewerConfig::MAIL_FROM;
  protected $REDIRECT = 'main';
  protected $EREDIRECT = 'main';
}