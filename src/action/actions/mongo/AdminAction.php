<?php
namespace mongo;
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
  protected $BASE_BRL = MongoConfig::USER_COLLECTION;
  protected $MAIL_FROM= MongoConfig::MAIL_FROM;
  protected $REPLY_TO = MongoConfig::MAIL_FROM;
  protected $EREDIRECT = 'main';
}