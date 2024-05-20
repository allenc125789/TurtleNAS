<?php
include "../private/php/DBcontrol.php";
$control = new DBcontrol;


$verify = $control->validate_auth();
if($verify){
    // Directory to fetch. (edit path with variables "/media/$vLOCATION/$vDRIVE/$vUSER" for better reference by databases)
    $afiles = $control->scanDirAndSubdir("/media/LOCAL/5d2aee01-0ecd-4ac4-b2ca-796b24be7e34/admin");
    // List files in a browser format.
    foreach ($afiles as $a2) {
        //echo "<a href='/download.php?$a2'>$a2</a><br>";
//    }
    $control->getPathBySQL();

}




// Authentication.
//if($valuser) {

?>
