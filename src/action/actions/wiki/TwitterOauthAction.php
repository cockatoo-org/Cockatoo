<?php
namespace wiki;

class TwitterOauthAction extends \Cockatoo\TwitterOauthAction {
  protected $BASE_BRL = WikiConfig::USER_COLLECTION;
  function getTOC() {
    $brl = WikiConfig::TOC_DOCUMENT . '?' . \Cockatoo\Beak::M_GET;
    $toc = \Cockatoo\BeakController::beakSimpleQuery($brl);
    return $toc;
  }
  function first_success($session) {
  }
  function success($access_token,$session) {
  }
}
