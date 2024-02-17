<?php

$_POST['pword'];
$password = $_POST['pword'];
$output = shell_exec("bash /var/www/netacbackup/bash/hash-debian12.sh $password>2>&1");
echo "<pre>$output</pre>";

//$output2 = shell_exec("whoami 2>&1");
//echo "<pre>$output2</pre>";

?>

