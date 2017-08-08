<?php

namespace Mysrc\controllers;

use Mysrc\classes\FbConfig;
use Mysrc\classes\GlobalsHandler;

class LoginController {
    protected $fb;

    public function __construct(FbConfig $fbConfig) {
        $this->fb = $fbConfig;
        var_dump($this->fb->isAccessToken());
        if ($this->fb->isAccessToken()) {
            $this->fb->setAccessToken();
        } else {
            $this->fb->askForLogin();
        }
    }
}
