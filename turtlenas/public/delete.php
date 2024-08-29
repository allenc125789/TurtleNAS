<?php

require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

#Calls the Delete function on verification.
$verify = $control->validate_auth();
if($verify){
    $control->deleteFile();
}

?>
