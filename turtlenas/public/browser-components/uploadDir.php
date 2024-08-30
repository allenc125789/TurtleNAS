<?php
require_once("../../private/php/DBcontrol.browser.php");
$control = new DBcontrol;

#Upload a folder to the server.
$verify = $control->validate_auth();
if($verify){
    $control->uploadDir();
}

?>
