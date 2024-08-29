<?php

require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

#Calls Download function on verification.
$verify = $control->validate_auth();
if($verify){
    $control->getDownload();
}

?>
