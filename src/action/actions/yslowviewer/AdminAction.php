<?php
namespace yslowviewer;
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
  protected $BASE_BRL = YslowviewerConfig::USER_COLLECTION;
  protected $MAIL_FROM= YslowviewerConfig::MAIL_FROM;
  protected $REPLY_TO = YslowviewerConfig::MAIL_FROM;
  protected $EREDIRECT = 'main';
}
