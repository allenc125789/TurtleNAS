<?php
require_once("../private/php/DBcontrol.browser.php");
$control = new DBcontrol;

#Update user's SQL file databases. 
$verify = $control->validate_auth();
if($verify){
    $control->updateFileRecord();
}

?>
