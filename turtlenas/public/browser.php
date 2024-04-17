<?php
include "../private/php/DBcontrol.php"

// Authentication.
if(validate_auth()) {
    // Directory to fetch. (edit path with variables "/media/$vLOCATION/$vDRIVE/$vUSER" for better reference by databases)
    $afiles = (scanDirAndSubdir("/media/Local/local/$username"));
// List files in a browser format.
    foreach ($afiles as $a2) {
        echo "<a href='/download.php?$a2'>$a2</a><br>";
    }
}

?>
