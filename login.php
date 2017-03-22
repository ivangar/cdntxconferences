<?php


$passwordFail = 'The password you entered is incorrect,<br> <a href="index.html">please click here to try again</a>';

if($_POST['password'] == "transplant") 
	
	{
		sendEmail();
	}

else 
	{
		echo $passwordFail;
	}

function sendEmail() {

	$from = "info@transplantforum.ca";	
	$to = "broy@stacom.com";
	$subject = "Transplant Forum Login";
	$message = 'A user has accessed the website!';
				
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	mail($to, $subject, $message, $headers, 'info@transplantforum.ca');
	header( 'Location: menu.html' );
}

if ($_POST['password'] == "transplant") 

?>