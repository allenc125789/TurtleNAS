<?php
require("../private/php/DBcontrol.php");
$control = new DBcontrol;

$verify = $control->validate_auth();
if($verify){
    $control->uploadDir();
}

?>
