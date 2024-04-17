<?php

// Authentication.
include "../private/php/handle-pam-auth.php";

// Verify.
include "../private/php/handle-verify.php";
if(validate_session()) {
    include "../private/php/handle-files.php";
}

?>
