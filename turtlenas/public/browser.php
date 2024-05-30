<?php
include "../private/php/DBcontrol.php";
$control = new DBcontrol;

error_reporting(-1); // display all faires
ini_set('display_errors', 1);  // ensure that faires will be seen
ini_set('display_startup_errors', 1); // display faires that didn't born

$verify = $control->validate_auth();
if($verify){
    $control->updateFileRecord();
    $fObject = $control->getFilesForDisplay();
    $query = $_SERVER['QUERY_STRING'];
    $queryparent = $control->getParentByQuery();
    $username = $_SESSION['sessuser'];
}
if ($query == NULL || $username == NULL){
    header('Location: /browser.php?/');
    header('Location: /login.html');
}
?>

<html>
<head>
    <meta charset="UTF=8">
    <meta name="viewport" content="width==device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/login.css">
</head>

<tbody>
    <table border=2px>
        <tr bgcolor="grey">
            <th>File Name</th>
            <th>Last Modified</th>
            <th>File Size</th>
        </tr>
        <tr>
            <td style="font-size: 20"><?php echo "<a href='/browser.php?/'>⟲</a>";?>
            <?php if (!is_null($queryparent)):?>
            <?php echo "<a href='/browser.php?$queryparent'>↩</a>";?></td>
            <?php endif;?>

            <td colspan=2><?php echo $query;?></td>

            <?php foreach($fObject as $row):?>
            <?php $data = explode('|', $row);?>
        </tr>
        <tr bgcolor="lightgrey">
            <?php if (is_dir($data[0])):?>
            <td><?php echo "<a href='/browser.php?$data[4]$data[1]'>$data[1]";?></td>
            <?php else:?>
            <td><?php echo "<a href='/download.php?$data[1]'>$data[1]";?></td>
            <?php endif;?>
            <td><?php echo $data[2];?></td>
            <td><?php echo $data[3];?></td>
            <?php endforeach;?>
        </tr>
</tbody>
</html>
