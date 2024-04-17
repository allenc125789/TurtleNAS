<?php

include "../private/php/DBcontrol.php"

//POST creds.
$password = $_POST['pword'];
$username = $_POST['uname'];

user_auth($username, $password);
validate_priv();

if(validate_auth()) {
    header("Location: /browser.php")
}

?>
