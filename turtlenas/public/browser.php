<?php
include "../private/php/DBcontrol.php";
$control = new DBcontrol;

error_reporting(-1); // display all faires
ini_set('display_errors', 1);  // ensure that faires will be seen
ini_set('display_startup_errors', 1); // display faires that didn't born

$verify = $control->validate_auth();
if($verify){
    // Directory to fetch. (edit path with variables "/media/$vLOCATION/$vDRIVE/$vUSER" for better reference by databases)
    $control->updateFileRecord();
}




// Authentication.
?>
