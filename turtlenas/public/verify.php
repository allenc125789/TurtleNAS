<?php

// Call DBcontrol and declare it's class.
require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

//POST creds.
$password = $_POST['pword'];
$username = $_POST['uname'];

echo "<a href='/login.html'>Page not loaded...</a>";

$control->user_auth($username, $password);
$verify = $control->validate_auth();
if($verify){
    header("Location: /browser.php");
}

?>
