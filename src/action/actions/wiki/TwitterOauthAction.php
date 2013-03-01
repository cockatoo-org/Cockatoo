<?php
namespace wiki;

class TwitterOauthAction extends \Cockatoo\TwitterOauthAction {
  protected $BASE_BRL = WikiConfig::USER_COLLECTION;
  protected $TOC_BRL = WikiConfig::TOC_TWITTER;
}
