<?php
include "../private/php/DBcontrol.php";
$control = new DBcontrol;

// Verify Session and download.
$validated = $_SESSION['allowed'];
switch ($validated) {
    case 1:
        $query = $_SERVER['QUERY_STRING'];
        $file = $control->getFullPath($query);
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        exit;
    default:
        header('Location: /index.html');
        break;
}

?>
