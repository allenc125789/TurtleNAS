<?php
include "../private/php/DBcontrol.php";
$control = new DBcontrol;

$verify = $control->validate_auth();
if ($verify){
    $control->updateFileRecord();
//    sleep(5);
}

?>
