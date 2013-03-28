<?php
namespace Cockatoo;

class GoogleOauthAction extends AccountAction {
  protected $TOC_BRL = 'storage://core-storage/toc/google';

  protected function getTOC() {
    $brl = $this->TOC_BRL . '?' . Beak::M_GET;
    $toc = BeakController::beakSimpleQuery($brl);
    if ( ! $toc ||
         ! $toc['CONSUMER_KEY'] ||
         ! $toc['CONSUMER_SECRET'] ){
      throw new \Exception('Fail to get google oauth config !! : ' );
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
    $google =& $user_data['google'];
    $request = $session[Def::SESSION_KEY_GET];

    if ( $request['code'] ) {
      $code = $request['code'];
      $baseURL = 'https://accounts.google.com/o/oauth2/token';
      $params = array(
        'code'          => $code,
        'client_id'     => $TOC['CONSUMER_KEY'],
        'client_secret' => $TOC['CONSUMER_SECRET'],
        'redirect_uri'  => $TOC['CALLBACK_URL'],
        'grant_type'    => 'authorization_code'
        );

      $response = \Cockatoo\http($baseURL,'POST',$params);
      if ( ! $response ) {
        throw new \Exception('Could not get RequestToken !!');
      }
      $response = json_decode($response);
      if ( ! $response ) {
        throw new \Exception('Unexpected RequestToken !!');
      }
      $access_token = $response->access_token;
      $response = \Cockatoo\http('https://www.googleapis.com/oauth2/v1/userinfo?'.'access_token='.$access_token);
      if ( ! $response ) {
        throw new \Exception('Could not get UserInfo !!');
      }
      $userInfo = json_decode($response);
      if ( ! $response ) {
        throw new \Exception('Unexpected UserInfo !!');
      }
      if ( $userInfo->email ) {
        $user_data = AccountUtil::get_account($this->BASE_BRL,$userInfo->email);
        if ( ! $user_data ) {
          $user_data['user'] = $userInfo->email;
        }
        $this->setMovedTemporary($google['redirect']);
        return $user_data;
      }
      throw new \Exception('Login failed !!');
    }
    $baseURL = 'https://accounts.google.com/o/oauth2/auth?';
    $scope = array(
//      'https://www.googleapis.com/auth/userinfo.profile',
      'https://www.googleapis.com/auth/userinfo.email'
      );
    $authURL = $baseURL . 'scope=' . urlencode(implode(' ', $scope)) .
      '&redirect_uri=' . urlencode($TOC['CALLBACK_URL']) .
      '&response_type=code' .
      '&client_id=' . $TOC['CONSUMER_KEY'];
    $this->setMovedTemporary($authURL);
    $google['redirect']           = $request['r'];
    return $user_data;
  }
  protected function already_hook(&$user_data) {
    return $user_data;
  }

  public function postProc(){
  }
}