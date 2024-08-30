<?php

require_once("../private/php/DBcontrol.browser.php");
$control = new DBcontrol;

#Create a directory.
$verify = $control->validate_auth();
if($verify){
    $post[] = $_POST['createDir'];
    $control->createDir($post);
}

?>
