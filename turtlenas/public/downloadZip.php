<?php
require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

//Verfies creds.
$groups = "www-data";
$auth = $control->validate_auth();
$priv = $control->validate_priv($groups);

if($auth && $priv){
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
