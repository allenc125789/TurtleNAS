<?php
$printUpdates = $control->printUpdateList();
$printUpdatesCount = $control->printUpdateList(TRUE);
$requestAptUpgrade = $control->requestAptUpgrade();

    //Prepare network names.
function execPrintNames(){
    $command = shell_exec("ip link show | awk '/^[1-9]/' | awk '{print $2}' | sed 's/.$//' | sed 's/$/\|/' | tr -d '[:space:]'");
    $names = explode("|", $command);
    $n = array_pop($names);
    return $names;
}

    //Prepare network status.
function execPrintStatus(){
    $command = shell_exec("ip link show | awk '/^[1-9]/' | awk '{print $9}' | sed 's/$/\|/' | tr -d '[:space:]'");
    $status = explode("|", $command);
    $n = array_pop($status);
    return $status;
}

    //Prepare network IP's.
function execPrintIP($printNames){
    $ip = [];
    foreach ($printNames as $i){
        $command = shell_exec("ip a l $i | awk '/inet/ {print $2}' | cut -d/ -f1 | head -n 1 | tr -d '[:space:]'");
        if ($command == null){
            $command = "empty";
        }
        $ip[] = $command;
    }
    return $ip;
}

    //Prepare network Netmask's.
function execPrintNetmask($printNames){
    $netmask = [];
    foreach ($printNames as $i){
        $command = shell_exec("ip a l $i | awk '/inet/ {print $2}' | cut -d/ -f2 | head -n 1 | tr -d '[:space:]'");
        if ($command == null){
            $command = "empty";
        }
        $netmask[] = $command;
    }
    return $netmask;
}

    //Prepare network Gateway's.
function execPrintGateway($printNames){
    $gateway = [];
    foreach ($printNames as $i){
        $command = shell_exec("ip route show 0.0.0.0/0 dev $i | cut -d\  -f3 | tr -d '[:space:]'");
        if ($command == null && $i == "lo"){
            $command = "null";
        } elseif ($command == null){
            $command = "empty";
        }
        $gateways[] = $command;
    }
    return $gateways;
}

    //Displays Interfaces.
function displayInterfaces(){
    $data = [];
    $command = shell_exec("ip link show | awk '/^[1-9]/' | awk '{print $1}' | sed 's/.$//' | sed 's/$/\|/' | tr -d '[:space:]'");
    $numbers = explode("|", $command);
    $n = array_pop($numbers);

    $printNames = execPrintNames();
    $printStatus = execPrintStatus();
    $printIP = execPrintIP($printNames);
    $printNetmask = execPrintNetmask($printNames);
    $printGateway = execPrintGateway($printNames);

    foreach ($numbers as $i){
        $data[$i]['number'] = $numbers[$i - 1];
        $data[$i]['name'] = $printNames[$i - 1];
        $data[$i]['status'] = $printStatus[$i - 1];
        $data[$i]['ip'] = $printIP[$i - 1];
        $data[$i]['netmask'] = $printNetmask[$i - 1];
        $data[$i]['gateway'] = $printGateway[$i - 1];
    }
    return $data;
}


$displayInterfaces = displayInterfaces();
$printStatus = execPrintStatus();


?>

<html>

<head>
    <meta charset="UTF=8">
    <meta name="viewport" content="width==device-width, initial-scale=0.1">
    <link rel="stylesheet" href="/css/admin.css">
</head>

<?php include("../../../private/html/admin-pageSelectMenu.html");?>

<!--Account Management section.-->
<div class='pageContents'>
    <div id='networkMenuDiv'>
        <tbody>
            <!--File Table body-->
            <table id="fileTables" class="fileTables" border=2px>
                <tr bgcolor="grey">
                    <th>Number</th>
                    <th>Interface Name</th>
                    <th>Status</th>
                    <th>IP Address</th>
                    <th>Netmask</th>
                    <th>Gateway</th>
                </tr>
            </table>
        </tbody>
    </div>
</div>

<!--Screen blocking div for when a request is "loading".-->
<div id='window-block'><text id="loadingTxt">Loading...</text></div>

<!--Console for showing upgrade process.-->
<div id='console'>
    <div id='console-output'>
        <text id='console-text'></text>
    </div>
    <div id='console-buttons'>
        <label for="closeConsoleButton" id="closeConsoleButtonTxt" class="buttonTxt">Stop</label>
        <button class="buttons" id="closeConsoleButton" onclick="closeConsole()"></button>

        <label for="upgrade" id="upgradeTxt" class="buttonTxt">Continue</label>
        <button class="buttons" id="upgrade" onclick="#"></button>
    </div>
</div>


<script type='text/javascript'>

//Refreshes the file-table and organizes files for display when called.
function displayInterfaces(){
    //Prepares table for contents.
    var table = document.getElementById("fileTables");
    //If files are found, start processing them into the table.
    //Establishes table content variables.
    for (i in jArray){
        console.log(jArray[i]);
        var row = table.insertRow(-1);
        var cell0 = row.insertCell(0);
        var cell1 = row.insertCell(1);
        var cell2 = row.insertCell(2);
        var cell3 = row.insertCell(3);
        var cell4 = row.insertCell(4);
        var cell5 = row.insertCell(5);
//        var cell5 = row.insertCell(5);
        var checkboxes = document.createElement("INPUT");
        //Sets row class name.
        row.className = "tableItems";
                //Sets checkbox attributes, according to the file in their row.
        checkboxes.setAttribute("type", "checkbox");
        checkboxes.setAttribute("class", "cb");
        checkboxes.setAttribute("name", "toggleInterface[]");
//                checkboxes.setAttribute("id", fileArray[0]);
//                checkboxes.setAttribute("value", fileArray[0]);
     //Sets cell 0 as a checkbox.
        cell0.appendChild(checkboxes);
        cell0.setAttribute("class", "checks");
    //Sets Cell 1 as a file or a directory.
        cell0.insertAdjacentHTML('beforeEnd', "<text> "+jArray[i]['number']+"</text>");
        cell1.insertAdjacentHTML('beforeEnd', "<text> "+jArray[i]['name']+"</text>");
        cell2.insertAdjacentHTML('beforeEnd', "<text> "+jArray[i]['status']+"</text>");
        cell3.insertAdjacentHTML('beforeEnd', "<text> "+jArray[i]['ip']+"</text>");
        cell4.insertAdjacentHTML('beforeEnd', "<text> "+jArray[i]['netmask']+"</text>");
        cell5.insertAdjacentHTML('beforeEnd', "<text> "+jArray[i]['gateway']+"</text>");
    }
/*                //Sets cell 2 as the size of the file.
                cell2.innerHTML = fileArray[2];
                cell2.setAttribute("class", "sizeItems");
                //Sets cell 3 as the date of when the file was last modified.
                cell3.innerHTML = fileArray[1];
                cell3.setAttribute("class", "dateItems");
            }
        }
    }
*/}



function windowBlockOFF(){
    document.getElementById("window-block").style.visibility = "hidden";
}

function windowBlockON(){
    document.getElementById("window-block").style.visibility = "visible";
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

let jArray = <?php echo json_encode($displayInterfaces); ?>;

windowBlockOFF();
displayInterfaces();
</script>
</html>
