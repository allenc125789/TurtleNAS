<?php

$password = $_POST['pword'];
$username = $_POST['uname'];

$command = shell_exec("sudo bash ../private/bash/handle-auth-pam.sh");
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
