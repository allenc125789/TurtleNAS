<html>
<head>
    <meta charset="UTF=8">
    <meta name="viewport" content="width==device-width, initial-scale=0.1">
    <link rel="stylesheet" href="/css/admin.css">
</head>

<div id="actionMenuDiv" class="actionMenuDiv">
</div>

<div id='actionMenuDiv'>
    <!--Dropdown button for system.-->
  <label for="system-btn" id="signOutTxt" class="dropdownTxt">System</label>
  <button id="system-btn" class="dropdown-btn">System
    <i class="fa fa-caret-down"></i>
  </button>
  <div class="dropdown-container">
    <a class="dropdownItem" href="/admin/system/general.php">General</a><br>
    <a class="dropdownItem" href="#">Date/Time</a><br>
    <a class="dropdownItem" href="/admin/system/updates.php">Updates</a><br>
    <a class="dropdownItem" href="#">Logs</a><br>
    <a class="dropdownItem" href="#">Power</a><br>
    </div>

    <!--Dropdown button for network.-->
  <label for="network-btn" id="signOutTxt" class="dropdownTxt">Network</label>
  <button id="network-btn" class="dropdown-btn">Network
    <i class="fa fa-caret-down"></i>
  </button>
  <div class="dropdown-container">
    <a class="dropdownItem" href="#">Interfaces</a><br>
    <a class="dropdownItem" href="#">Connections</a><br>
    <a class="dropdownItem" href="#">Limits</a><br>
    </div>

    <!--Dropdown button for Security.-->
  <label for="security-btn" id="signOutTxt" class="dropdownTxt">Security</label>
  <button id="security-btn" class="dropdown-btn">Security
    <i class="fa fa-caret-down"></i>
  </button>
  <div class="dropdown-container">
    <a class="dropdownItem" href="#">Users</a><br>
    <a class="dropdownItem" href="#">Groups</a><br>
    <a class="dropdownItem" href="#">Access</a><br>
    </div>

    <!--Dropdown button for backups.-->
  <label for="backup-btn" id="signOutTxt" class="dropdownTxt">Backup</label>
  <button id="backup-btn" class="dropdown-btn">Backup
    <i class="fa fa-caret-down"></i>
  </button>
  <div class="dropdown-container">
    <a class="dropdownItem" href="#">Disks</a><br>
    <a class="dropdownItem" href="#">Capacity</a><br>
    <a class="dropdownItem" href="#">SSHFS</a><br>
    <a class="dropdownItem" href="#">rsync</a><br>
    </div>
</div>


<!--Account Management section.-->
<div id='accountMenuDiv'>
    <!--Button to sign out of account.-->
    <label for="signOut" id="signOutTxt" class="buttonTxt">Log-out</label>
    <button class="buttons" id="signOut" onclick="getRequestSignOut()"></button>

    <!--Button to switch to the browser view.-->
    <form action='/browser.php' method='GET'>
    <label for="adminView" id="adminViewTxt" class="buttonTxt">Browser-View</label>
    <input class="buttons" id="adminView" type="submit">
    </form>
</div>



<script src="/js/admin-PageSelectMenu.js"></script>
<script src="/js/admin-dropmenu.js"></script>


</html>

