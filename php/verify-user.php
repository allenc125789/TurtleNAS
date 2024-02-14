<?php

$_POST['pword'];
$password = $_POST['pword'];
$output = shell_exec("openssl passwd -6 -salt xyz $password 2>&1");

echo "<pre>$output</pre>";

?>

