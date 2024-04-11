<?php

session_start();

$validated = $_SESSION['allowed'];
switch ($validated) {
    case 1:
        echo "Downloading...";
        break;
    default:
        header('Location: /index.html');
        break;
}
$file = $_SERVER['QUERY_STRING'];
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

?>
