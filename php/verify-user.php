<?php

$password = $_POST['pword'];
$username = $_POST['uname'];

$output = shell_exec("sudo python3 /var/www/netacbackup/python3/pam-auth.py $username $password 2>&1");
$output2 = shell_exec("history 2>&1");

echo "<pre>$output</pre>";
echo "<pre>$output2</pre>";


//$output2 = shell_exec("whoami 2>&1");
//echo "<pre>$output2</pre>";

?>

