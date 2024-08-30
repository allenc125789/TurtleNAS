<?php
require_once("../../private/php/DBcontrol.browser.php");
$control = new DBcontrol;

#Sign out of account (ends php session).
$verify = $control->validate_auth();
if ($verify){
    $fObject = $control->signout();
}
?>
