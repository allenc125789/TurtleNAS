<?php

// Call DBcontrol and declare it's class.
require_once("../private/php/DBcontrol.php");
$DBcontrol = new DBcontrol;


//POST creds.
$password = $_POST['pword'];
$username = $_POST['uname'];

$DBcontrol->user_auth($username, $password);

echo "test";

?>
