<?php
$printUpdates = $control->printUpdateList();
$printUpdatesCount = $control->printUpdateList(TRUE);
?>

<html>
<head>
    <meta charset="UTF=8">
    <meta name="viewport" content="width==device-width, initial-scale=0.1">
    <link rel="stylesheet" href="/css/admin.css">
</head>

<!--Account Management section.-->
<div class='pageContents'>
    <div id='updateMenuDiv'>
        <!--Button to switch to the browser view.-->
        <label for="refresh" id="refreshTxt" class="buttonTxt">Refresh</label>
        <input class="buttons" id="refresh" onclick="getRequestAptUpdate()">

        <!--Button to sign out of account.-->
        <label for="upgrade" id="upgradeTxt" class="buttonTxt">Upgrade</label>
        <button class="buttons" id="upgrade" onclick="#"></button>
    </div>

    <div>
        <text><br>Upgradable packages(<?php echo($printUpdatesCount);?>).<br><br></text>
        <?php echo($printUpdates);?>
    </div>

</div>

<!--Screen blocking div for when a request is "loading".-->
<div id='window-block'><text id="loadingTxt">Loading...</text></div>

<script type='text/javascript'>
//Request to sign out of account, and sends user back to the login page.

function windowBlockOFF(){
    document.getElementById("window-block").style.visibility = "hidden";
}

function windowBlockON(){
    document.getElementById("window-block").style.visibility = "visible";
}

function getRequestAptUpdate(){
    var xhttp = new XMLHttpRequest();
    windowBlockON();
    xhttp.onreadystatechange = function(){
    // Write code for writing output when databse updates start.:
        if (this.readyState == 4 && this.status == 200){
           // Write code for writing output when databse is fully updated.:
            location.reload();
        } else if(this.status >= 400){
            location.reload();
        }
    };
    xhttp.open("GET", "/admin/system/requestAptUpdate.php", true);
    xhttp.send();
}
</script>
</html>
