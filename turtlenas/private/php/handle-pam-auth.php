<?php

// Case/Authorize empty strings
if(empty($password || $username)){
    header('Location: /index.html');
    exit;
// Restrict user
} elseif ($username == "root"){
    header('Location: /index.html');
    exit;
} else {
    $command = shell_exec(" sudo python3 ../private/python3/pam-auth.py $username $password 2>&1");
    $output = "$command";
}

// Redirect

if($output){
    echo "if statement working";
    header("Location: ../private/user/$username/");
} elseif(!$output){
    header('Location: /index.html');
    exit;
} else{
    echo "<pre>$output</pre>";
}


?>
