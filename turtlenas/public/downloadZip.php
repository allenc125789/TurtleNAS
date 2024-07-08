<?php

require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

$verify = $control->validate_auth();
if($verify){
    if ($query = urldecode($_SERVER['QUERY_STRING']) !== "DOWNLOAD"){
        $output = $control->execZipFolder($_SERVER['QUERY_STRING']);
    } else {
        $control->getDownload(TRUE);
    }
}
?>
