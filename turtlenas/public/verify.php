<?php

require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

$password = $_POST['pword'];
$username = $_POST['uname'];

echo "<a href='/login.html'>Page not loaded...</a>";

#Send user to the file-browser if credentials are verified by PAM.
$control->user_auth($username, $password);
$verify = $control->validate_auth();
if($verify){
    setcookie('cwd', "/");
    setcookie('log', "> Successful login!<br>-<br>");
    header("Location: /browser.php");
}

?>
