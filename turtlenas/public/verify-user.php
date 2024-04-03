<?php

$password = $_POST['pword'];
$username = $_POST['uname'];

// Handle for verification.
$command = shell_exec(" su sysadmin -c ' python3 /var/www/turtlenas/private/python3/pam-auth.py $username $password' 2>&1");
$output = "$command";

// Deny/Allow Access.
if($output){
    echo "if statement working";
} elseif(!$output){
    header('Location: /index.html');
    exit;
} else{
    echo "<pre>$output</pre>";
}


?>
