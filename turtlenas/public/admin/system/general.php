<?php

require("../../../private/php/DBcontrol-admin.php");
$control = new DBcontrol;

$verify = $control->validate_auth();
$verifyPriv = $control->validate_priv("admin");


    include("../../../private/php/admin-general.php");
}

?>
