<?php

require_once("../../private/php/DBcontrol.browser.php");
$control = new DBcontrol;

#Delete files/folders.
$verify = $control->validate_auth();
if($verify){
    $control->deleteFile();
}

?>
