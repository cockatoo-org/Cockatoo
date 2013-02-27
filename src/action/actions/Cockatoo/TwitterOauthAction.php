<?php
namespace Cockatoo;
require_once(Config::COCKATOO_ROOT.'action/actions/utils/twitteroauth.php');

abstract class TwitterOauthAction extends \Cockatoo\Action {
  protected $EXPIRES  = 315360000; // 10 years
  // protected $BASE_BRL = 'storage://core-storage/users/';

  abstract function getTOC();
  abstract function first_success($session);
  abstract function success($access_token,$session);
  public function proc(){
    try {
      if ( false ) {
        // logout
        $s[AccountUtil::SESSION_LOGIN] = null;
        $s['twitter'] = null;
        $this->updateSession($s);
      }else {
        // login
        $TOC = $this->getTOC();
        if ( ! $TOC ||
           ! $TOC['CONSUMER_KEY'] ||
             ! $TOC['CONSUMER_SECRET'] ||
           ! $TOC['CALLBACK_URL']  ) {
          throw new \Exception('Fail to get twitter oauth config !! : ' );
        }
        $session = $this->getSession();
        if ( $session[AccountUtil::SESSION_LOGIN] ) {
          throw new \Exception('Already logined !! : ' . $session[AccountUtil::SESSION_LOGIN]['user'] );
        }
        $twitter = $session['twitter'];
        $request = $session[\Cockatoo\Def::SESSION_KEY_GET];
        if ( $twitter['oauth_token'] && $request['oauth_token'] ) {
          // Callback (Second step)
          if ($twitter['oauth_token'] !== $request['oauth_token']) {
            $this->updateSession(array('twitter' => null ) );
            throw new \Exception('Unmatch request tokens !!');
          }
          $tw = new \TwitterOAuth(
            $TOC['CONSUMER_KEY'],$TOC['CONSUMER_SECRET'],
            $twitter['oauth_token'], $twitter['oauth_token_secret']);
          $access_token = $tw->getAccessToken($request['oauth_verifier']);
          
          $user_id     = $access_token['user_id'];
          $screen_name = $access_token['screen_name'];
          
          if ( $user_id && $screen_name ) {
            $s[AccountUtil::SESSION_LOGIN] = null;
            $user_data = AccountUtil::get_account($this->BASE_BRL,$screen_name);
            $user_data[AccountUtil::KEY_EXPIRES] = $now + $this->EXPIRES;
            $s[AccountUtil::SESSION_LOGIN] = $user_data;
            $s['twitter'] = null;
            $this->updateSession($s);
            return $this->success($access_token,$session);
          }
          throw new \Exception('Login failed !!');
        }
        
        // Redirect to tiwtter (First step)
        $tw = new \TwitterOAuth($TOC['CONSUMER_KEY'],$TOC['CONSUMER_SECRET']);
        $token = $tw->getRequestToken($TOC['CALLBACK_URL']);
        if(! isset($token['oauth_token'])){
          throw new \Exception('Could not get RequestToken !!');
        }
        
        $twitter['oauth_token']        = $token['oauth_token'];
        $twitter['oauth_token_secret'] = $token['oauth_token_secret'];
        
        $this->updateSession(array('twitter' => $twitter ) );
        $authURL = $tw->getAuthorizeURL($twitter['oauth_token']);
        $this->first_success($session);
        $this->setMovedTemporary($authURL);
      }
    }catch ( \Exception $e ) {
      $s[\Cockatoo\Def::SESSION_KEY_ERROR] = $e->getMessage();
      $this->updateSession($s);
      $this->setMovedTemporary('/wiki/view');
      \Cockatoo\Log::error(__CLASS__ . '::' . __FUNCTION__ . $e->getMessage(),$e);
      return null;
    }
  }
  public function postProc(){
  }
}

  