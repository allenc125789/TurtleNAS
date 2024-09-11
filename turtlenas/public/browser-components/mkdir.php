<?php

require_once("../../private/php/DBcontrol.php");
$control = new DBcontrol;

$verify = $control->validate_auth();
if($verify){
    $post[] = $_POST['createDir'];
    $control->createDir($post);
    header("Location: /browser.php");
}
?>
