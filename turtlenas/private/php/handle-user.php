<?php

$id = session_id();


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
        break;
}


?>
