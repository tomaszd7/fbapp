<?php

namespace Mysrc\classes;
/**
 *
 */
class FbDataLoader extends FbConfig {
    private $fbUserProfile;

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
      return $this->fbUserProfile;
    }
}
