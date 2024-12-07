<?php

// Call DBcontrol and declare it's class.
require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

//POST creds.
$password = $_POST['pword'];
$username = $_POST['uname'];

echo "<a href='/login.html'>Page not loaded...</a>";

#Compare user input to PAM. Authorizes user session.
$control->user_auth($username, $password);

#Validate Authentication.
$auth = $control->validate_auth();
$priv = $control->validate_priv();
$groups = "www-data"

if($auth && $priv($groups)){
    setcookie('cwd', "/");
    setcookie('log', "> Successful login!<br>-<br>");
    header("Location: /browser.php");
} else{
    session_destroy();
}

?>
