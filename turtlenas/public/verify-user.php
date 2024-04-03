<?php

$password = $_POST['pword'];
$username = $_POST['uname'];

// Handle for verification.
$command = shell_exec(" sudo bash ../private/bash/handle-auth-pam.sh $username $password 2>&1");
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
