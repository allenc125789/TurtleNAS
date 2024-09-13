<?php

require("../../../private/php/admin-DBcontrol.php");
$control = new DBcontrol;

$verify = $control->validate_auth();
$verifyPriv = $control->validate_priv("admin");

if ($verify && $verifyPriv){
    echo("Admin page loaded.");
}

?>
