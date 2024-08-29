<?php

require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

#Download a tar file of a folder. Encryption depending on query as args.
$verify = $control->validate_auth();
if($verify){
    if ($query = urldecode($_SERVER['QUERY_STRING']) === "PLAINTEXT"){
        $output = $control->execTarFolder();
    } elseif ($query = urldecode($_SERVER['QUERY_STRING']) === "ENCRYPT"){
        $post = $_POST['tmpPass'];
        $output = $control->execTarFolder($post);
    } else {
        $control->getDownload(FALSE);
    }
}

?>
