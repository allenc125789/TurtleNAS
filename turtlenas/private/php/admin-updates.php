<?php
$printUpdates = $control->printUpdateList();
$printUpdatesCount = $control->printUpdateList(TRUE);
$requestAptUpgrade = $control->requestAptUpgrade();
?>

<html>

<head>
    <meta charset="UTF=8">
    <meta name="viewport" content="width==device-width, initial-scale=0.1">
    <link rel="stylesheet" href="/css/admin.css">
</head>

<?php include("../../../private/html/admin-pageSelectMenu.html");?>

<!--Upgrade Action section.-->
<div class='pageContents'>
    <div class='pageTitle'>
        <h1>Updates</h1>
        <hr>
        <br>
    </div>

    <div id='updateMenuDiv'>
        <!--Button to refresh the update list.-->
        <label for="refresh" id="refreshTxt" class="buttonTxt">Refresh</label>
        <input class="buttons" id="refresh" onclick="getRequestAptUpdate()">

        <!--Button to bring up the update prompt.-->
        <label for="upgrade" id="upgradeTxt" class="buttonTxt">Upgrade</label>
        <button class="buttons" id="upgrade" onclick="openConsole()"></button>
    </div>

    <div>
        <text><br>Upgradable packages(<?php echo("${printUpdatesCount})."); if ($printUpdateCount == 0){ echo(" All packages up to date!");}?><br><br></text>
        <?php echo($printUpdates);?>
    </div>

</div>

<!--Screen blocking div for when a request is "loading".-->
<div id='window-block'><text id="loadingTxt">Loading...</text></div>

<!--Console for showing upgrade process.-->
<div id='console'>
    <div id='console-output'>
        <text id='console-text'><?php echo($requestAptUpgrade);?></text>
    </div>
    <div id='console-buttons'>
        <label for="closeConsoleButton" id="closeConsoleButtonTxt" class="buttonTxt">Stop</label>
        <button class="buttons" id="closeConsoleButton" onclick="closeConsole()"></button>

        <label for="upgrade" id="upgradeTxt" class="buttonTxt">Continue</label>
        <button class="buttons" id="upgrade" onclick="#"></button>
    </div>
</div>


<script type='text/javascript'>
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

function openConsole(){
    var console = document.getElementById("console");
    windowBlockON();
    console.style.visibility = "visible";
}

function closeConsole(){
    var console = document.getElementById("console");
    windowBlockOFF();
    console.style.visibility = "hidden";
}

windowBlockOFF();
</script>
</html>
