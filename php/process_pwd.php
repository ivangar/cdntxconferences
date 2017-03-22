<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/php/PasswordHash.php");

/*
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/php/PasswordHash.php");  //USE THIS PATH FOR LOCAL ENVIRONMENT
*/

$access = '';
$cookie = true;

function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}

function set_astellas_cookie($name)
{   
	$cookie_name = $name;
	$rand_key = 'cb4bd824f0d9c2823dc25349e058dfe858873caa52e6708bc791760dd2a3a068336aca08238c67d69b6434a4864366f5ce9a32f57bf5b710043e019fd3d1a8d268ceec2f3f556d597f339320d17eb91c9592f15a0555e15b298ab57055de8fce6df5a70bd967fc15d5bcaef6f24008e3eeb7a5a0b79c081c1bac124535096c88'; 
	$ip = get_client_ip();
	$Passhash = new PasswordHash(); //Create a password hashed Object to use pbkdf2
	$token = base64_encode($Passhash->pbkdf2("sha256",$ip,$rand_key,1000,24,true));
	$cookie = $ip . ':' . $token;
	setcookie($cookie_name, $cookie, 0,"/", ".cdntxconferences.com", 0,0);//Cookie should be user:random_key:keyed_hash
}

function CheckCookie($name){
	$cookie_name = $name;
	$correct = true;
	$rand_key = 'cb4bd824f0d9c2823dc25349e058dfe858873caa52e6708bc791760dd2a3a068336aca08238c67d69b6434a4864366f5ce9a32f57bf5b710043e019fd3d1a8d268ceec2f3f556d597f339320d17eb91c9592f15a0555e15b298ab57055de8fce6df5a70bd967fc15d5bcaef6f24008e3eeb7a5a0b79c081c1bac124535096c88';
	list ($ip, $token) = explode(':', $_COOKIE[$cookie_name]); //Cookie should be user:random_key:keyed_hash
	
	$Passhash = new PasswordHash(); //Create a password hashed Object to use pbkdf2

    if ( $token !== base64_encode($Passhash->pbkdf2("sha256",$ip,$rand_key,1000,24,true)) ) {
        $correct = false;
    } 

    else {  $correct = true; }

    return $correct;
}

//This is called first
if(isset($_POST["cookie_page"]) && !empty($_POST["cookie_page"]) ){

	$page = $_POST['cookie_page']; //Set json to include the page in the POST array
	$access = 'denied';

	if(isset($_COOKIE['remember_fellows']) && !empty($_COOKIE['remember_fellows']) ){

		$cookie_name = "remember_fellows";

		switch ($page) {
			case "attendee_survey2016";
		    case "itinerary2016";
			case "browse_slides2016";
					if(CheckCookie($cookie_name)){ $access = 'access';	}
					$json_response["section"] = "fellows";	
				  	break;
		}
	}

	if(isset($_COOKIE['remember_forum']) && !empty($_COOKIE['remember_forum']) ){

		$cookie_name = "remember_forum";

		switch ($page) {
			case "calendar_2016";
		    case "agenda";
			case "registration";
					if(CheckCookie($cookie_name)){ $access = 'access';	}
					$json_response["section"] = "forum";
				  	break;
		}	
	}

	$json_response["access"] = $access;

	//CHANGE TO RETURN JSON ARRAY WITH THE SECTION WHERE IT NEEDS TO REDIRECT
	echo json_encode($json_response);
}


if(isset($_POST["pwd"]) && !empty($_POST["pwd"]) ){

	$page = $_POST['page']; //Set json to include the page in the POST array

	if(strcmp($_POST['pwd'], "TxFellows_2016") == 0){
		
		$cookie_name = "remember_fellows";

		switch ($page) {
			case "attendee_survey2016";
		    case "itinerary2016";
			case "browse_slides2016";
				  	$access = 'correct';
				  	set_astellas_cookie($cookie_name);
				  	break;
			default:
				$access = 'incorrect';
				break;
		}

		$json_response["access"] = $access;
		$json_response["section"] = "fellows";

	}

	elseif(strcmp($_POST['pwd'], "Transplant_Forum2017") == 0){

		$cookie_name = "remember_forum";

		switch ($page) {
			case "calendar_2016";
		    case "agenda";
			case "registration";
				  	$access = 'correct';
				  	set_astellas_cookie($cookie_name);
				  	break;
			default:
				$access = 'incorrect';
				break;
		}

		$json_response["access"] = $access;
		$json_response["section"] = "forum";
	}
	
	else {
		$json_response["access"] = 'incorrect';
	}

	//echo $access;
	echo json_encode($json_response);
}

?>