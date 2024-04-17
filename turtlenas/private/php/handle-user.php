<?php

// Verify Session.
function validate_session(){
    $validated = $_SESSION['allowed'];
    switch ($validated) {
        case 1:
            $a = True;
            break;
        default:
            header('Location: /login.html');
            break;
    }
    return $a;
}


// Verify Privlige.
function validate_privlige(){
    $privlige = $_SESSION['admin'];
    switch ($privlige) {
        case 1:
            $a = True;
            break;
        default:
            header('Location: /login.html');
            break;
    }
    return $a;
}

?>
