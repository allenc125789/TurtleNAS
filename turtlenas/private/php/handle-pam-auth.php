<?php

//POST creds.
$password = $_POST['pword'];
$username = $_POST['uname'];

// Session user tagging
session_start();
$_SESSION['sessuser'] = $_POST['uname'];
$id = session_id();

// Restricted users.
$restricted = array("root", "sysadmin");

// Case empty strings.
if(empty($password || $username)){
    header('Location: /index.html');
    exit;
// Case restricted users.
} elseif(in_array($username, $restricted)){
    header('Location: /index.html');
    exit;
// Allow all else.
} else {
    $command = shell_exec(" sudo python3 ../private/python3/pam-auth.py $username $password 2>&1");
    $output = "$command";
}


// Tag & Redirect
if($output){
    echo "$id". $_SESSION['sessuser'];
// header("Location: /handle-user.php");
} elseif(!$output){
    header('Location: /index.html');
    exit;
} else{
    echo "<pre>$output</pre>";
}


?>
