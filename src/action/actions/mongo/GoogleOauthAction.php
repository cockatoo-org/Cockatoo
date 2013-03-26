<?php
namespace mongo;

class GoogleOauthAction extends \Cockatoo\GoogleOauthAction {
  protected $BASE_BRL = MongoConfig::USER_COLLECTION;
  protected $TOC_BRL = MongoConfig::TOC_GOOGLE;
}
