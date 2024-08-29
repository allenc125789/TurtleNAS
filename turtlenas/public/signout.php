<?php
require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

$verify = $control->validate_auth();
if ($verify){
    $fObject = $control->signout();
}
?>
