<?php
/*------------------------------------
  Handles Authentication 
  ------------------------------------*/

// And start logging them out, cookies get eaten first
   $path = "/";
   setcookie("id", "", time()-3600, $path);
   setcookie("stache", "", time()-3600, $path);

// Do this LAST as it will maroon you on the Zoo CAS page
	 include_once('./libraries/CAS-1.2.0/CAS.php');
	phpCAS::setDebug();
	phpCAS::client(CAS_VERSION_2_0,'login.zooniverse.org',443,'');
	phpCAS::setNoCasServerValidation();
	GLOBAL $auth;
	$auth = phpCAS::checkAuthentication();
	phpCAS::logout();
?>
