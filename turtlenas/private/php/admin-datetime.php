<?php

?>

<html>

<head>
    <meta charset="UTF=8">
    <meta name="viewport" content="width==device-width, initial-scale=0.1">
    <link rel="stylesheet" href="/css/admin.css">
</head>


<div class='pageContents'>
    <div class='pageTitle'>
        <h1>Date/Time</h1>
        <hr>
        <br>
    </div>
    <text><b>Time Zone: </b></text>
        <!--Dropdown button for network.-->
    <div id='timezone-menu'>
      <label for="interface-btn" id="signOutTxt" class="dropdownTxt">Network</label>
      <button id="interface-btn" class="dropdown-btn">Network
        <i class="fa fa-caret-down"></i>
      </button>
      <div class="dropdown-container">
        <a class="dropdownItem" href="/admin/network/interfaces.php">Interfaces</a><br>
        <a class="dropdownItem" href="#">Connections</a><br>
        <a class="dropdownItem" href="#">Limits</a><br>
      </div>
    </div>
    <br>
    <br>
    <text><b>Server Time: </b><?php echo(shell_exec('date')); ?></text>
</div>

<?php include("../../../private/html/admin-pageSelectMenu.html");?>
</html>
