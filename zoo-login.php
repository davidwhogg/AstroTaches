<?php
/* -----------------------------------
   Setup Database
   ----------------------------------- */
   $db_sum  = mysql_connect("127.0.0.1","root","SQLgoNOGO");
   mysql_select_db('astrotaches', $db_sum);

/*------------------------------------
  Handles Authentication 
  ------------------------------------*/

	include_once('./libraries/CAS-1.2.0/CAS.php');
	phpCAS::setDebug();
	phpCAS::client(CAS_VERSION_2_0,'login.zooniverse.org',443,'');
	phpCAS::setNoCasServerValidation();
	GLOBAL $auth;
	$auth = phpCAS::checkAuthentication();
  
    // This will force the person to login to the Zooniverse 
   	phpCAS::forceAuthentication();
	$attr = phpCAS::getAttributes();

	$id = $attr['id'];
		
   // Set time. This get's used a lot
	$time = new DateTime(null, new DateTimeZone('America/Chicago'));
	$time = date_format($time, 'Y-m-d H:i:s');

   // Set Public Cookie
   // Step 1: Make sure cookies can be setup
   $cookie_value = md5("LOGGED_IN_KEY".$id);

   // Step 2: Actually setup the cookie
   $expire = time()+60*60; // Set to one day
   $path = "/";
   setcookie("stache", $cookie_value, $expire, $path);
   setcookie("id", $id, $expire, $path);

   // Check if they are in the database. Set Language Cookie accordingly
   $query = "SELECT * from zooniverse_user_id WHERE id = $id";
   $get_user = mysql_query($query);

   if ($user = mysql_fetch_array($get_user)) {
		echo "Welcome Back!";
	} else {
		$attrib =  phpCAS::getAttributes();
		$attrib['username'] = phpCAS::getUser();
		
		
		$url = "https://starstryder:16310rlm@www.zooniverse.org/api/users/".$attrib['id'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);		

		include("./XML-parser.php");
		
		$arr = xml_decode($data);
		$attrib['display_name'] = $arr['LOGIN'];
		$attrib['name'] = $arr['NAME'];
	    $zooniverse_user_id = $id;
		$username = $arr['LOGIN']; 
		$name = $arr['NAME'];
		$email = $arr['EMAIL']; 
		$created_at = $time; 
		$updated_at = $time; 
		$api_key = $CAS_attrib['API_KEY'];
		$query = "INSERT INTO zooniverse_users (zooniverse_user_id, username, name, email, created_at, updated_at, api_key) VALUES ($zooniverse_user_id, '$username', '$name', '$email', '$created_at', '$updated_at', '$api_key')";
	    mysql_query($query);
	    echo mysql_error();
		
	}

   header( 'Location: ./index.php');
   
   die();

?>
