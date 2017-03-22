<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/PasswordHash.php");

$rand_key = 'cb4bd824f0d9c2823dc25349e058dfe858873caa52e6708bc791760dd2a3a068336aca08238c67d69b6434a4864366f5ce9a32f57bf5b710043e019fd3d1a8d268ceec2f3f556d597f339320d17eb91c9592f15a0555e15b298ab57055de8fce6df5a70bd967fc15d5bcaef6f24008e3eeb7a5a0b79c081c1bac124535096c88';
list ($ip, $token) = explode(':', $_COOKIE['remember_forum']); //Cookie should be user:random_key:keyed_hash

$Passhash = new PasswordHash(); //Create a password hashed Object to use pbkdf2

if ( $token !== base64_encode($Passhash->pbkdf2("sha256",$ip,$rand_key,1000,24,true)) ) {
    echo 'restrict';
} 

else {  echo 'access'; }


?>