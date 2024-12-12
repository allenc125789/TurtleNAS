<?php
$printUpdates = $control->printUpdateList();
$printUpdatesCount = $control->printUpdateList(TRUE);
$requestAptUpgrade = $control->requestAptUpgrade();

    //Prepare network numbers.
function execPrintNumbers(){
    $command = shell_exec("ip link show | awk '/^[1-9]/' | awk '{print $1}' | sed 's/.$//' | sed 's/$/\|/' | tr -d '[:space:]'");
    $numbers = explode("|", $command);
    $n = array_pop($numbers);
    return $numbers;
}

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





$printNumbers = execPrintNumbers();
$printNames = execPrintNames();
$printStatus = execPrintStatus();
$printIP = execPrintIP($printNames);
$printGateway = execPrintGateway($printNames);

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
                    <th colspan=2>Number</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>IP Address</th>
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
    var row = table.insertRow(-1);
    var cell0 = row.insertCell(0);
    var cell1 = row.insertCell(1);
    var cell2 = row.insertCell(2);
    var cell3 = row.insertCell(3);
    var cell4 = row.insertCell(4);
    var cell5 = row.insertCell(5);
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
/*                //Sets Cell 1 as a file or a directory.
                if (!dir.endsWith("/")){
                    cell1.insertAdjacentHTML('beforeEnd', "<img id='fileIcon' src='/images/file-icon.png'><a href=download.php?"+dirURI+">"+fileArray[0]);
                } else {
                    cell1.insertAdjacentHTML('beforeEnd', "<img id='folderIcon' src='/images/folder-icon.png'></img><a href=javascript:displayFiles(\""+dirURI+"\")>"+fileArray[0]);
                }
                //Sets cell 2 as the size of the file.
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

windowBlockOFF();
displayInterfaces();
</script>
</html>
