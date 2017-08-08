<?php 

class GlobalsHandler {

	public function __construct() {
		if(!session_id()){
		    session_start();
		}
	}

	public function getFbAccessToken() {
		if (isset($_SESSION['facebook_access_token'])) {
			return $_SESSION['facebook_access_token'];
		} else {
			return false;
		}
	}

	public function setFbAccessToken($val) {
		$_SESSION['facebook_access_token'] = $val;
	}

	public function destroySession() {
          session_destroy();
          // Redirect user back to app login page
          header("Location: ./");
	}

	public function relocateToMain() {
		    // Redirect the user back to the same page if url has "code" parameter in query string
	    if(isset($_GET['code'])){
	        header('Location: ./');
	    }
	}
}

 ?>