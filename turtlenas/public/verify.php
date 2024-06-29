<?php

// Call DBcontrol and declare it's class.
require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

//POST creds.
$password = $_POST['pword'];
$username = $_POST['uname'];

$control->user_auth($username, $password);
echo "<a href='/login.html'>Verifying...</a>";

$verify = $control->validate_auth();
if($verify){
    header("Location: /browser.php?$username:%2F");
}

?>
