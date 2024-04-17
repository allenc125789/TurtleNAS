<?php
include "../private/php/DBcontrol.php"

// Authentication.
if(validate_auth()) {
    include "../private/php/handle-files.php";
}

?>
