<?php

// Call DBcontrol and declare it's class.
require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

//POST creds.
$password = $_POST['pword'];
$username = $_POST['uname'];

echo "<a href='/login.html'>Page not loaded...</a>";


$control->user_auth($username, $password);

$groups = "www-data";
$auth = $control->validate_auth();
$priv = $control->validate_priv($groups);

if($auth && $priv){
    setcookie('cwd', "/");
    setcookie('log', "> Successful login!<br>-<br>");
    header("Location: /browser.php");
} else{
    header("Location: /login.html");
}

?>
