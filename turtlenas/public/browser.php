<?php

// Authentication.
include "../private/php/handle-pam-auth.php";

// Verify.
include "../private/php/handle-verify.php";
if(validate_auth()) {
    include "../private/php/handle-files.php";
}

?>
