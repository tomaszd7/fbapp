<?php

namespace Mysrc\classes;

// Include required libraries
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class FbConfig {
  private $appId         = ' '; //Facebook App ID
  private $appSecret     = ' '; //Facebook App Secret
  private $redirectURL   = 'http://localhost:8000/facebook'; //Callback URL
  private $fbPermissions = array('email');  //Optional permissions

  protected $fb;
  private $globalsHandler;

  private $accessToken;
  private $helper;


  public function __construct($globalsHandler) {
    $this->globalsHandler = $globalsHandler;
    $this->fb = new Facebook(array(
      'app_id' => $this->appId,
      'app_secret' => $this->appSecret,
      'default_graph_version' => 'v2.2',
    ));

    $this->loginHelper();
    $this->getAccessToken();
  }

  private function loginHelper() {
    // Get redirect login helper
    $this->helper = $this->fb->getRedirectLoginHelper();
  }

  private function getAccessToken() {
      // Try to get access token
    try {
        if($token = $this->globalsHandler->getFbAccessToken() ){
            $this->accessToken = $token;
        }else{
            $this->accessToken = $this->helper->getAccessToken();
        }
    } catch(FacebookResponseException $e) {
         echo 'Graph returned an error: ' . $e->getMessage();
          exit;
    } catch(FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
    }
  }

  public function isAccessToken() {
      return isset($this->accessToken);
  }

  public function setAccessToken() {
      // already stored session
        if ($token = $this->globalsHandler->getFbAccessToken()){
            $this->fb->setDefaultAccessToken($token);
        } else {
            // Put short-lived access token in session
            $this->globalsHandler->setFbAccessToken((string) $this->accessToken);

              // OAuth 2.0 client handler helps to manage access tokens
            $oAuth2Client = $this->fb->getOAuth2Client();
            // Exchanges a short-lived access token for a long-lived one
            $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($this->globalsHandler->getFbAccessToken());
            // set session cookie
            $this->globalsHandler->setFbAccessToken((string) $longLivedAccessToken);

            // Set default access token to be used in script
            $this->fb->setDefaultAccessToken($this->globalsHandler->getFbAccessToken());
        }
    }

    public function askForLogin() {
        // Get login url
        $loginURL = $this->helper->getLoginUrl($this->redirectURL);

        // Render facebook login button
        echo '<a href="'.htmlspecialchars($loginURL).'"><img src="images/fblogin-btn.png"></a>';
    }

}
?>
