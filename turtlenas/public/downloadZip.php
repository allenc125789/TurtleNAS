<?php

require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

$verify = $control->validate_auth();
if($verify){
    if ($query = urldecode($_SERVER['QUERY_STRING']) === "PLAINTEXT"){
        $output = $control->execZipFolder();
    } elseif ($query = urldecode($_SERVER['QUERY_STRING']) === "ENCRYPT"){
        $post = $_POST['tmpPass'];
        $output = $control->execZipFolder($post);
    } else {
        $control->getDownload(FALSE);
    }
}
?>
