<?php


error_reporting(-1); // display all faires
ini_set('display_errors', 1);  // ensure that faires will be seen
ini_set('display_startup_errors', 1); // display faires that didn't born


//POST creds.
$password = $_POST['pword'];
$username = $_POST['uname'];

require_once("../private/php/DBcontrol.php");
$DBcontrol = new DBcontrol;

$DBcontrol->user_auth($username, $password);

echo "test";

?>
