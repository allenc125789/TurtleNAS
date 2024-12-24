<?php

function execPrintTimezones(){
    $command = shell_exec("timedatectl list-timezones | more | cat | sed 's/$/\|/' | tr -d '[:space:]'");
    $timezones = explode("|", $command);
    $n = array_pop($timezones);
    return $timezones;
}


$displayTimezones = execPrintTimezones();
$currentTimezone = shell_exec("timedatectl | awk '/Time zone: / {print $3}'");

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
        <div id="saveMenu">
            <!--Button to save the new timezone.-->
            <label for="save-btn" id="save-label" class="buttonTxt">Save</label>
            <input class="buttons" id="save-btn" onclick="#">
        </div>

        <text><b>Time Zone: </b></text>
        <!--Dropdown button for timezones.-->
        <div id='timezone-menu'>
            <label for="tz-btn" id="tz-btn-label" class="dropdownTxt"><?php echo($currentTimezone); ?></label>
            <button id="tz-btn" class="dropdown-btn">
            <i class="fa fa-caret-down"></i>
            </button>
            <div id="container" class="dropdown-container">
            </div>
         </div>
    <br>
    <br>
    <text><b>Server Time: </b><?php echo(shell_exec('date')); ?></text>
</div>

<?php include("../../../private/html/admin-pageSelectMenu.html");?>

<script type='text/javascript'>
//Refreshes the timezones and organizes for display when called.
function displayTimezones(){
    var container = document.getElementById("container");
    for (i in jArray){
        container.insertAdjacentHTML('beforeEnd', "<button class='dropdownItem' value="+jArray[i]+" onclick='selectTZ(this)'>"+jArray[i]+"</button>");
    }
}


function selectTZ(tz){
    var newTZ = tz.value;
    var currentTZ = document.getElementById('tz-btn-label');
    currentTZ.innerHTML = newTZ;
}

let jArray = <?php echo json_encode($displayTimezones); ?>;
displayTimezones();

</script>
</html>
