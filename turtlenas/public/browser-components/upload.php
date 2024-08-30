<?php
require_once("../../private/php/DBcontrol.browser.php");
$control = new DBcontrol;

#Upload file(s) to server.
$verify = $control->validate_auth();
if($verify){
    $control->uploadFile();
}

?>
