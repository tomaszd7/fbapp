<?php

namespace Mysrc\controllers;

class DataController extends LoginController {

    public function getFbProfile() {
        $fbUserProfile = $this->fb->getFbProfile();
        $fbUserData = array(
            'oauth_provider'=> 'facebook',
            'oauth_uid'     => $fbUserProfile['id'],
            'first_name'    => $fbUserProfile['first_name'],
            'last_name'     => $fbUserProfile['last_name'],
            // 'email'         => $fbUserProfile['email'],
            'gender'        => $fbUserProfile['gender'],
            'locale'        => $fbUserProfile['locale'],
            'picture'       => $fbUserProfile['picture']['url'],
            'link'          => $fbUserProfile['link']
        );

        return $fbUserData;

    }

}
