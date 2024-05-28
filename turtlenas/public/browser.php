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
//    $control->switchWorkingDir();
}

?>

<html>
<tbody>
    <table>
        <tr>
            <th>File Name</th>
            <th>Last Modified</th>
            <th>File Size</th>
        </tr>
        <?php foreach($fObject as $row):?>
        <?php $data = explode('|', $row);?>
        <tr>
            <?php if (is_dir($data[0])):?>
                <td><?php echo "<a href='/browser.php?/$data[1]'>$data[1]";?></td>
            <?php else :?>
                <td><?php echo "<a href='/download.php?$data[1]'>$data[1]";?></td>
            <?php endif; ?>
            <td><?php echo $data[1];?></td>
            <td><?php echo $data[2];?></td>
            <?php endforeach;?>
        </tr>
</tbody>
</html>
