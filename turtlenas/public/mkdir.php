<?php

require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

//Verfies creds.
$groups = "www-data";
$auth = $control->validate_auth();
$priv = $control->validate_priv($groups);

if($auth && $priv){
    $post[] = $_POST['createDir'];
    $control->createDir($post);
    header("Location: /browser.php");
}
?>
