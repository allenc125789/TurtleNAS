<?php

include "../private/php/handle-verify.php";
if(validate_auth()) {
    include "../private/php/handle-downloads.php";

?>
