<?php

// Call DBcontrol and declare it's class.
require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

//POST creds.
$password = $_POST['pword'];
$username = $_POST['uname'];

echo "<a href='/login.html'>Page not loaded...</a>";

$control->user_auth($username, $password);
$auth = $control->validate_auth();
$priv = $control->validate_priv();

if($auth & $priv){
    setcookie('cwd', "/");
    setcookie('log', "> Successful login!<br>-<br>");
    header("Location: /browser.php");
} else{
    session_destroy();
}

?>
