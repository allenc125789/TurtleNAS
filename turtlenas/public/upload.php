<?php
include "../private/php/DBcontrol.php";
$control = new DBcontrol;

error_reporting(E_ALL);
ini_set('display_errors', 'On');

function pre_r( $array ){
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

function reArrayFiles(&$file_post) {
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);
    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }
    return $file_ary;
}

$query = $_SERVER['QUERY_STRING'];
$username = $_SESSION['sessuser'];
$path = str_replace("$username:/", '', $query);
$fullpath = $control->getFullPath($path);
if (isset($_FILES['file'])){
    $file_array = reArrayFiles($_FILES['file']);
    for ($i=0;$i<count($file_array);$i++){
        move_uploaded_file($file_array[$i]['tmp_name'], $fullpath . $file_array[$i]['name']);
        echo $fullpath . $file_array[$i]['name'];
    }
}
header("Location: /browser.php?$query");




?>
