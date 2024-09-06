<?php

function printUptime(){
    $uptime = shell_exec(' uptime -p');
    $uptime = ltrim($uptime, "up ");
    return $uptime;
}

function execPrintCPUs(){
    $grep = shell_exec(" grep 'cpu cores' /proc/cpuinfo | uniq");
    $grep = ltrim($grep, "cpu cores\t:");
    return $grep;
}

function execPrint($command){
    $uptime = shell_exec(" $command");
    return $uptime;
}

$printUptime = printUptime();
$printHostname = execPrint("hostname");
$printDomainname = execPrint("domainname");
$printIP = execPrint("hostname -I");
$printCPUs = execPrintCPUs();
?>

<div class='pageContents'>
    <text>Logged in as: <?php echo($_SESSION['sessuser']);?></text>
    <br><br>
    <text>Uptime: <?php echo($printUptime);?></text>
    <br><br>
    <text>Computer Name: <?php echo($printHostname);?></text>
    <br>
    <text>Domain Name: <?php echo($printDomainname);?></text>
    <br>
    <text>IP Address: <?php echo($printIP);?></text>
    <br><br>

    <text>CPU(s): <?php echo($printCPUs);?></text>
    <br>
    <text>RAM:</text>
    <br><br>

    <text>Debian version:</text>
    <br>
    <text>TurtleNAS version:</text>
    <br>
</div>
