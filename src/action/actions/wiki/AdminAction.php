<?php
namespace wiki;
/**
 * AdminAction.php - ????
 *  
 * @package ????
 * @access public
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @create 2011/07/07
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
class AdminAction extends \Cockatoo\AdminAction {
  protected $BASE_BRL = WikiConfig::USER_COLLECTION;
  protected $MAIL_FROM= WikiConfig::MAIL_FROM;
  protected $REPLY_TO = WikiConfig::MAIL_FROM;
  protected $EREDIRECT = 'view';
}