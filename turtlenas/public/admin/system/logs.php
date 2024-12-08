<?php

require("../../../private/php/admin-DBcontrol.php");
$control = new DBcontrol;

//Verfies creds.
$groups = "www-data";
$auth = $control->validate_auth();
$priv = $control->validate_priv($groups);

if($auth && $priv){
    echo("Admin page loaded.");
}

?>
