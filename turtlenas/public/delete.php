<?php

require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

#Delete files/folders.
$verify = $control->validate_auth();
if($verify){
    $control->deleteFile();
}

?>
