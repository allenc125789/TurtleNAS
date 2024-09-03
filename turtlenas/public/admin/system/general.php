<?php

require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

$verify = $control->validate_auth();
$verifyPriv = $control->validate_priv();
if($verify && $verifyPriv("admin")){
    echo("hi");
}


?>
