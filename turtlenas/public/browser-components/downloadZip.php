<?php

require_once("../../private/php/DBcontrol.browser.php");
$control = new DBcontrol;

#Download a zip file of a folder. Encryption depending on query as args.
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
