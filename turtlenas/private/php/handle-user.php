<?php

$id = session_id();

$command1 = shell_exec("bash ../private/bash/mapped-drives.sh 2>&1");
$output = "$command2";



// Verify Session.
$validated = $_SESSION['allowed'];
switch ($validated) {
    case 1:
        echo "Success!";
        break;
    default:
        header('Location: /index.html');
        break;
}

// Verify Privlige.
$privlige = $_SESSION['admin'];
switch ($privlige) {
    case 1:
        echo "Success!";
        break;
    default:
        echo "not admin";
        break;
}

?>
