<?php


// Include the autoloader provided in the SDK
require_once __DIR__ . '/facebook-php-sdk/autoload.php';

// Include required libraries
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

/**
* 
*/
class FbConfig {
  private $appId         = 'InsertAppID'; //Facebook App ID
  private $appSecret     = 'InsertAppSecret'; //Facebook App Secret
  private $redirectURL   = 'http://localhost/facebook/'; //Callback URL
  private $fbPermissions = array('email');  //Optional permissions

  private $fb;
  private $globalsHandler;

  private $accessToken = false;
  private $helper;

  private $fbUserProfile;

/*
 * Configuration and setup Facebook SDK
 */
  public function __construct($globalsHandler) {
    $this->globalsHandler = $globalsHandler;
    $this->fb = new Facebook(array(
      'app_id' => $appId,
      'app_secret' => $appSecret,
      'default_graph_version' => 'v2.2',
    ));
  }



  private function redirect() {
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

  public function setAccessToken() {
    if($this->accessToken)){
        if($token = $this->globalsHandler->getFbAccessToken()){
            $this->fb->setDefaultAccessToken($token);
        }else{
            // Put short-lived access token in session
            $this->globalsHandler->setFbAccessToken((string) $this->accessToken);
            
              // OAuth 2.0 client handler helps to manage access tokens
            $oAuth2Client = $fb->getOAuth2Client();
            
            // Exchanges a short-lived access token for a long-lived one
            $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($this->globalsHandler->getFbAccessToken());
            $this->globalsHandler->setFbAccessToken((string) $longLivedAccessToken);
            
            // Set default access token to be used in script
            $this->fb->setDefaultAccessToken($this->globalsHandler->getFbAccessToken());
        }    
      }
    }

    public function getFbProfile() {

    // Getting user facebook profile info
      try {
          $profileRequest = $this->fb->get('/me?fields=name,first_name,last_name,email,link,gender,locale,picture');
          $this->fbUserProfile = $profileRequest->getGraphNode()->asArray();
      } catch(FacebookResponseException $e) {
          throw new Exception("Graph returned an error: " . $e->getMessage(), 1);            
          $this->globalsHandler->destroySession();          
      } catch(FacebookSDKException $e) {
          throw new Exception("Facebook SDK returned an error: " . $e->getMessage(), 1);
      }

    }
?>