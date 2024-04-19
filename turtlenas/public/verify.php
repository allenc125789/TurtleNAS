<?php
require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

//POST creds.
$password = $_POST['pword'];
$username = $_POST['uname'];

$control->user_auth($username, $password);
$redirect = $control->redirect_login();
return  <a href="$redirect">Verifying...</a> 

$verify = $control->validate_auth();
if($verify){
    echo "test";
}

?>
