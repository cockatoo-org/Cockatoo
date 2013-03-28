<?php
namespace Cockatoo;
require_once(Config::COCKATOO_ROOT.'action/actions/utils/twitteroauth.php');

class TwitterOauthAction extends AccountAction {
  protected $TOC_BRL = 'storage://core-storage/toc/twitter';

  protected function getTOC() {
    $brl = $this->TOC_BRL . '?' . Beak::M_GET;
    $toc = BeakController::beakSimpleQuery($brl);
    if ( ! $toc ||
         ! $toc['CONSUMER_KEY'] ||
         ! $toc['CONSUMER_SECRET'] ){
      throw new \Exception('Fail to get twitter oauth config !! : ' );
    }
    $session = $this->getSession();
    $callback = $session[Def::SESSION_KEY_REQ][Def::CS_CORE_FULLURL];
    $qpos = strpos($callback,'?');
    if ( $qpos ) {
      $callback = substr($callback,0,$qpos);
    }
    $toc['CALLBACK_URL'] = $callback;
    return $toc;
  }


  protected function login_hook(&$user_data) {
    $session = $this->getSession();
    $TOC = $this->getTOC();
    $twitter =& $user_data['twitter'];
    $request = $session[Def::SESSION_KEY_GET];
    // Callback (Second step)
    if ( $twitter['oauth_token'] && $request['oauth_token'] ) {
      if ($twitter['oauth_token'] !== $request['oauth_token']) {
        throw new \Exception('Unmatch request tokens !!');
      }
      $tw = new \TwitterOAuth(
        $TOC['CONSUMER_KEY'],
        $TOC['CONSUMER_SECRET'],
        $twitter['oauth_token'], 
        $twitter['oauth_token_secret']);

      $access_token = $tw->getAccessToken($request['oauth_verifier']);
          
      $user_id     = $access_token['user_id'];
      $screen_name = $access_token['screen_name'];

      if ( $user_id && $screen_name ) {
        $user_data = AccountUtil::get_account($this->BASE_BRL,$screen_name);
        if ( ! $user_data ) {
          $user_data[AccountUtil::KEY_USER] = $screen_name;
        }
        $this->setMovedTemporary($twitter['redirect']);
        return $user_data;
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
    $twitter['redirect']           = $request['r'];
    
    $authURL = $tw->getAuthorizeURL($twitter['oauth_token']);
    $this->setMovedTemporary($authURL);
    return $user_data;
  }
  protected function already_hook(&$user_data) {
    return $user_data;
  }

  public function postProc(){
  }
}

  