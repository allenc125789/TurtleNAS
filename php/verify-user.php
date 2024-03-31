<?php

$password = $_POST['pword'];
$username = $_POST['uname'];

$command = shell_exec("sudo python3 /var/www/netacbackup/python3/pam-auth.py $username $password 2>&1");
$output = "<pre>$command</pre>";

if($output == "1"){
    echo "if statement working";
} elseif($output == "0"){
    echo "if works, password didnt match"
} else{
    echo $output
}


//$output2 = shell_exec("whoami 2>&1");
//echo "<pre>$output2</pre>";

?>

