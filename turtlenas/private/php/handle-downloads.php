<?php
include "../private/php/DBcontrol.php";
$control = new DBcontrol;

// Verify Session and download.
$validated = $_SESSION['allowed'];
switch ($validated) {
    case 1:
        $query = $_SERVER['QUERY_STRING'];
        $username = $_SESSION['sessuser'];
        $shortpath = urldecode("$username:", '', $query);
        $file = $control->getRootByUser($username) . $shortpath;
        echo basename(stripslashes($file));
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename(stripslashes($file)));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile(stripslashes($file));
        exit;
    default:
        header('Location: /login.html');
        break;
}

?>
