<?php
require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

$verify = $control->validate_auth();
if($verify){
    $query = $_SERVER['QUERY_STRING'];
    $control->uploadFile();
    header("Location: /browser.php?$query");
}

?>
