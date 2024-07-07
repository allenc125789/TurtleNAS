<?php

require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

$verify = $control->validate_auth();
if($verify){
    $control->getZipFolder();
//    header("Location: /browser.php");
}
?>
