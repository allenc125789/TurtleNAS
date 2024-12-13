<?php

require_once("../private/php/admin-DBcontrol.php");
$control = new DBcontrol;

//Verfies creds.
$groups = "admin";
$auth = $control->validate_auth();
$priv = $control->validate_priv($groups);

/*if($auth && $priv){
    $interface = urldecode($_POST['interface']
    $status = urldecode($_POST['status']
    $control->changeInterfaceStatus($interface, $status);
}
*/
?>
