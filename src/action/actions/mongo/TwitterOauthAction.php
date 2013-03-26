<?php
namespace mongo;

class TwitterOauthAction extends \Cockatoo\TwitterOauthAction {
  protected $BASE_BRL = MongoConfig::USER_COLLECTION;
  protected $TOC_BRL = MongoConfig::TOC_TWITTER;
}
