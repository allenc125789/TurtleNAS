<?php

include "../private/php/DBcontrol.php"

user_auth($username, $password);
validate_priv();
validate_auth();

?>
