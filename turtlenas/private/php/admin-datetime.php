<?php

?>

<html>

<head>
    <meta charset="UTF=8">
    <meta name="viewport" content="width==device-width, initial-scale=0.1">
    <link rel="stylesheet" href="/css/admin.css">
</head>

<?php include("../../../private/html/admin-pageSelectMenu.html");?>

<div class='pageContents'>
    <div class='pageTitle'>
        <h1>Date/Time</h1>
        <hr>
        <br>
    </div>
    <text>Time Zone: </text>
        <!--Dropdown button for network.-->
  <label for="network-btn" id="signOutTxt" class="dropdownTxt">Network</label>
  <button id="network-btn" class="dropdown-btn">Network
    <i class="fa fa-caret-down"></i>
  </button>
  <div class="dropdown-container">
    <a class="dropdownItem" href="/admin/network/interfaces.php">Interfaces</a><br>
    <a class="dropdownItem" href="#">Connections</a><br>
    <a class="dropdownItem" href="#">Limits</a><br>
    </div>
    <text>Server Time: </text>

</div>
</html>
