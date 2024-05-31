<?php
include "../private/php/DBcontrol.php";
$control = new DBcontrol;

error_reporting(E_ALL);
ini_set('display_errors', 'On');
$temp_file = tempnam(sys_get_temp_dir(), 'Tux');

echo $temp_file;


$query = $_SERVER['QUERY_STRING'];
$username = $_SESSION['sessuser'];
$path = str_replace("$username:/", '', $query);
$file = $control->getFullPath($path);

if (isset($_POST['submit'])){
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

}
//if ($filesize < 500000){
    $fileDestination = $file . $fileName;
    move_uploaded_file($fileTmpName, $fileDestination);
//    header("Location: /browser.php?$query");
    echo $fileDestination . $fileTmpName;
//} else {
    echo "File size too large!";
//}





?>
