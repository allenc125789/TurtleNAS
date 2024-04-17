<?php

include "../private/php/DBcontrol.php"

user_auth($username, $password);
validate_priv();

if(validate_auth()) {
    header("Location: /browser.php")
}

?>
