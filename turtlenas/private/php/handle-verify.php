<?php

// Verify Session.
function validate_auth() {
    $validated = $_SESSION['allowed'];
    switch ($validated) {
        case 1:
            return true;
            break;
        default:
            header('Location: /login.html');
            break;
    }
}


// Verify Privlige.
function validate_priv() {
    $privlige = $_SESSION['admin'];
    switch ($privlige) {
        case 1:
            return true;
            break;
        default:
            header('Location: /login.html');
            break;
    }
}

?>
