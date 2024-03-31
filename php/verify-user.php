<?php

$password = $_POST['pword'];
$username = $_POST['uname'];

$command = shell_exec("sudo python3 /var/www/netacbackup/python3/pam-auth.py $username $password 2>&1");
$output = "$command";

echo "verifying..."

if($output == 1){
    echo "if statement working";
} elseif($output == 0){
    echo "if works, password didnt match";
} else{
    echo "<pre>$output2</pre>";
}


?>

