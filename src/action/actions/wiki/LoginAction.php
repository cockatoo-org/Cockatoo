<?php
namespace wiki;
require_once('twitteroauth.php');

class LoginAction extends \Cockatoo\Action {
  const CONSUMER_KEY ='DITmNemE0fVGsVuxOelhEA';
  const CONSUMER_SECRET = 'kJaPFKItcwfobXrbmAsKjio6mQUPekyVOkDhctCio';
  const CALLBACK_URL = 'http://cockatoo.jp/wiki/view';

  public function proc(){
    $session = $this->getSession();
    $twitter = $session['twitter'];
    if ( $twitter['oauth_token'] ) {
      if ($twitter['oauth_token'] !== $_REQUEST['oauth_token']) {
var_dump('unmatch');
        return;
      }

      $tw = new \TwitterOAuth(
        CONSUMER_KEY,CONSUMER_SECRET,
        $twitter['oauth_token'], $twitter['oauth_token_secret']);
      $access_token = $tw->getAccessToken($_REQUEST['oauth_verifier']);

      $user_id     = $access_token['user_id'];
      $screen_name = $access_token['screen_name'];

var_dump($access_token);
      return $access_token;
    }
    
    $tw = new \TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET);

    $token = $tw->getRequestToken(CALLBACK_URL);
var_dump($token);
    if(! isset($token['oauth_token'])){
      echo "error: getRequestToken\n";
      exit;
    }

    $twitter['oauth_token']        = $token['oauth_token'];
    $twitter['oauth_token_secret'] = $token['oauth_token_secret'];
    
    $this->updateSession(array('twitter' => $twitter ) );

    $authURL = $tw->getAuthorizeURL($twitter['oauth_token']);

    $this->setMovedTemporary($authURL);
  }
  public function postProc(){
  }
}
