?>
root@home-ok-na01p:/home/user# cat /var/www/turtlenas/public/mkdir.php
<?php

require_once("../private/php/DBcontrol.php");
$control = new DBcontrol;

$verify = $control->validate_auth();
if($verify){
    $control->createDir();
}
?>
