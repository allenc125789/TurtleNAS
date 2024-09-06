<?php




function execPrintUptime(){
    $uptime = shell_exec(' uptime -p');
    $uptime = ltrim($uptime, "up ");
    return $uptime;
}

function execPrintCPUs(){
    $grep = shell_exec(" grep 'cpu cores' /proc/cpuinfo | uniq");
    $grep = ltrim($grep, "cpu cores\t:");
    return $grep;
}

function execPrintMem(){
    $grep = shell_exec(" grep MemTotal /proc/meminfo");
    $grep = ltrim($grep, "MemTotal:\t");
    return $grep;
}

function execPrint($command){
    $command = shell_exec(" $command");
    return $command;
}

$printUptime = execPrintUptime();
$printHostname = execPrint("hostname");
$printDomainname = execPrint("domainname");
$printIP = execPrint("hostname -I");
$printCPUs = execPrintCPUs();
$printMem = execPrintMem();
$printLinuxVersion = execPrint("cat /proc/version");
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
    <text>RAM: <?php echo($printMem);?></text>
    <br><br>

    <text>Debian version: <?php echo($printLinuxVersion);?></text>
    <br>
    <text>TurtleNAS version:</text>
    <br>
</div>
