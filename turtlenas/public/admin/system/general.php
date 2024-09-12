<?php

require("../../../private/php/DBcontrol-admin.php");
$control = new DBcontrol;

$verify = $control->validate_auth();
$verifyPriv = $control->validate_priv("admin");

if ($verify && $verifyPriv){
    include("../../../private/html/admin-pageSelectMenu.html");
    include("../../../private/php/admin-general.php");
}

?>

<!--Screen blocking div for when a request is "loading".-->
<div id='window-block'><text id="loadingTxt">Loading...</text></div>
