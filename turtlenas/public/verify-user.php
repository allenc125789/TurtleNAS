<?php

$password = $_POST['pword'];
$username = $_POST['uname'];

$command = shell_exec("sudo python3 ../private/python3/pam-auth.py $username $password 2>&1");
$output = "$command";

if($output){
    echo "if statement working";
} elseif(!$output){
    header('Location: /index.html');
    exit;
} else{
    echo "<pre>$output</pre>";
}


?>
