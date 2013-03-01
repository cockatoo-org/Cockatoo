<?php
namespace wiki;

class GoogleOauthAction extends \Cockatoo\GoogleOauthAction {
  protected $BASE_BRL = WikiConfig::USER_COLLECTION;
  protected $TOC_BRL = WikiConfig::TOC_GOOGLE;
}
