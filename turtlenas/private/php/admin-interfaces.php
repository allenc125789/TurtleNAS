<?php
$printUpdates = $control->printUpdateList();
$printUpdatesCount = $control->printUpdateList(TRUE);
$requestAptUpgrade = $control->requestAptUpgrade();

//Get network's for user view in the interface page.
function getNetworksForDisplay(){
    $data = [];
    $username = $_SESSION['sessuser'];
    //Prepare network numbers.
    $command = shell_exec("ip link show | awk 'NR % 2 {print} !(NR % 2) && /pattern/ {print}' | awk '{print $1}' | sed 's/.$//'");
    $subArray = explode("/n", $command);
    foreach ($subArray as $i){
        $data['number'] += $i
    }
    //Prepare network names.
    $command = shell_exec("ip link show | awk 'NR % 2 {print} !(NR % 2) && /pattern/ {print}' | awk '{print $2}' | sed 's/.$//'");
    $subArray = explode("/n", $command);
    foreach ($subArray as $i){
        $data['names'] += $i
    }
    //Prepare network status.
    $command = shell_exec("ip link show | awk 'NR % 2 {print} !(NR % 2) && /pattern/ {print}' | awk '{print $9}'");
    $subArray = explode("/n", $command);
    foreach ($subArray as $i){
        $data['status'] += $i
    }
    //Prepare network IP's.
    foreach ($data['names'] as $i){
        $command = shell_exec("ip route show 0.0.0.0/0 dev $i | cut -d\  -f3");
        $data['ip'] += $command
    return $data;
}

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
    <?php echo($getNetworksForDisplay);?>
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
</script>
</html>
