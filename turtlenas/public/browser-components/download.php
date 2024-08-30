<?php

require_once("../../private/php/DBcontrol.browser.php");
$control = new DBcontrol;

#Download files/folders.
$verify = $control->validate_auth();
if($verify){
    $control->getDownload();
}

?>
