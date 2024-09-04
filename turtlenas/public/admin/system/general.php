<?php

require("../../../private/php/DBcontrol-admin.php");
$control = new DBcontrol;

$verify = $control->validate_auth();
$verifyPriv = $control->validate_priv("admin");

if ($verify && $verifyPriv){
    include("../../../private/html/admin-PageSelectMenu.html");
}

?>
