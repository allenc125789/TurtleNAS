<?php

//POST creds.
$password = $_POST['pword'];
$username = $_POST['uname'];

// Session Start & Tag.
session_start();
$_SESSION['sessuser'] = $_POST['uname'];

// Restricted users.
$restricted = array("root", "sysadmin");

// Deny empty strings.
if(empty($password || $username)){
    header('Location: /index.html');
    exit;
// Deny restricted users.
} elseif(in_array($username, $restricted)){
    header('Location: /index.html');
    exit;
// Allow all else.
} else {
    $command = shell_exec(" sudo python3 ../private/python3/pam-auth.py $username $password 2>&1");
    $output = "$command";
}

// Run Python3 code through Bash.
// Successful Auth.
if($output){
    $_SESSION['allowed'] = 1;
// Failed Auth.
} elseif(!$output){
    header('Location: /index.html');
    exit;
// Error
} else{
    header('Location: /index.html');
    exit;
}

// Set Privlige.
$command2 = shell_exec(" bash ../private/bash/admin-check.sh $username 2>&1");
$output2 = "$command2";
// Admin True
if ($output2 == "1"){
    $_SESSION['admin'] = 1;
// Admin False
} else{
    $_SESSION['admin'] = 0;
}

?>
