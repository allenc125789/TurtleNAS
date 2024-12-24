<?php

function execPrintTimezones(){
    $command = shell_exec("timedatectl list-timezones | more | cat | sed 's/$/\|/' | tr -d '[:space:]'");
    $timezones = explode("|", $command);
    $n = array_pop($timezones);
    return $timezones;
}

$displayTimezones = execPrintTimezones();

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
        <!--Dropdown button for timezones.-->
        <div id='timezone-menu'>
            <label for="interface-btn" id="timeout-current" class="dropdownTxt"><?php echo(shell_exec("timedatectl | awk '/Time zone: / {print $3}'")); ?></label>
            <button id="interface-btn" class="dropdown-btn">
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
//Refreshes the file-table and organizes files for display when called.
function displayTimezones(){
    var container = document.getElementById("container");
    //If files are found, start processing them into the table.
    //Establishes table content variables.
    for (i in jArray){
        container.insertAdjacentHTML('beforeEnd', "<button class='dropdownItem'> "+jArray[i]+".</button>");
/*
        if (jArray[i]['name'] == 'lo'){
            checkboxes.setAttribute("disabled", "disabled");
            checkboxes.setAttribute("checked", "true");
        }
        if (jArray[i]['status'] == 'UP'){
            checkboxes.setAttribute("checked", "true");
        }
        //Sets cell 0 as a checkbox.
        cell0.appendChild(checkboxes);
        cell0.setAttribute("class", "checks");
        //Sets cell content.
        cell0.insertAdjacentHTML('beforeEnd', "<text> "+jArray[i]['number']+".</text>");
        cell1.insertAdjacentHTML('beforeEnd', "<text>"+jArray[i]['name']+"</text>");
        cell2.insertAdjacentHTML('beforeEnd', "<text>"+jArray[i]['status']+"</text>");
        cell3.insertAdjacentHTML('beforeEnd', "<text>"+jArray[i]['ip']+"</text>");
        //Sets netmask cell.
        if (jArray[i]['netmask'] == "EMPTY"){
            cell4.insertAdjacentHTML('beforeEnd', "<text>"+jArray[i]['netmask']+"</text>");
        }else {
            cell4.insertAdjacentHTML('beforeEnd', "<text>/"+jArray[i]['netmask']+"</text>");
        }
        cell5.insertAdjacentHTML('beforeEnd', "<text>"+jArray[i]['gateway']+"</text>");
*/    }
}



let jArray = <?php echo json_encode($displayTimezones); ?>;
displayTimezones();

</script>
</html>
