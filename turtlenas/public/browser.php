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
    $username = $_SESSION['sessuser'];
    $query = $_SERVER['QUERY_STRING'];
    $query = str_replace("%20", " ", $query);
    $casequery = str_starts_with("$username:", $query);
    $queryparent = $control->getParentByQuery();
}

if($casequery || $query == NULL || $username == NULL){
    header("Location: /browser.php?$username:/");
    header('Location: /login.html');
}
?>

<html>
<head>
    <meta charset="UTF=8">
    <meta name="viewport" content="width==device-width, initial-scale=0.1">
    <link rel="stylesheet" href="/css/browser.css">
</head>


<tbody>
    <table class="fileTables" border=2px>
        <tr bgcolor="grey">
            <th colspan=2>File Name</th>
            <th>Last Modified</th>
            <th>File Size</th>
        </tr>
        <tr class="hotbar">
            <td bgcolor="white"><input class="cb" type="checkbox" name="massSelect[]" id="massSelect" onchange="checkAll(this)"/></td>
            <td style="font-size: 20" bgcolor="white"><?php echo "<a href='/browser.php?$username:/'>⟲</a>";?>
            <?php if (!is_null($queryparent)):?>
            <?php echo "<a href='/browser.php?$username:$queryparent'>↩</a>";?></td>
            <?php endif;?>
            <td colspan=2 style="font-size:12" bgcolor="black"><font style="color:white;"><?php echo $query;?></font></td>
        </tr>
        <?php echo "<form action='/delete.php?$query' method='post'>";?>
        <?php foreach($fObject as $row):?>
        <?php $data = explode('|', $row);?>
        <?php $arrkey = array_search($row, $fObject);?>
        <tr class="tableItems" bgcolor="lightgrey">
            <td id="checks"><?php echo "<input type=\"checkbox\" class=\"cb\" id=\"filechecks\" name=\"fileToDelete[]\" value=\"$data[1]\">";?></td>
            <?php if (is_dir($data[0])):?>
            <td id="files"><?php echo "<a href='/browser.php?$username:$data[4]$data[1]'>$data[1]";?></td>
            <?php else:?>
            <td id="files"><?php echo "<a href='/download.php?$username:$data[4]$data[1]'>$data[1]";?></td>
            <?php endif;?>
            <td id="dates"><?php echo $data[2];?></td>
            <td id="size"><?php echo $data[3];?></td>
            <?php endforeach;?>
        </tr>
    </table>
</tbody>

<div class="buttonDivs">

<input type="submit" value="Delete" id="delete">
</form>
<br><br>

    <?php echo "<form action='/upload.php?$query' method='POST' enctype='multipart/form-data'>"?>
    <input type="file" id="button" name="file[]" multiple="" onchange="this.form.submit()">
<br><br>

    <?php echo "<form action='/upload.php?$query' method='POST' enctype='multipart/form-data'>"?>
    <input type="file" id="filepicker" name="fileList" webkitdirectory multiple />

</div>

<div class='empty'></div>

<script src="/js/browser.js"></script>

</html>
