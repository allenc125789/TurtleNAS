<?php
include "../private/php/DBcontrol.php";
$control = new DBcontrol;

$verify = $control->validate_auth();
if ($verify){
    $username = $_SESSION['sessuser'];
    $fObject = $control->signout();
}
?>
