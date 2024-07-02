<?php
include "../private/php/DBcontrol.php";
$control = new DBcontrol;

error_reporting(-1); // display all faires
ini_set('display_errors', 1);  // ensure that faires will be seen
ini_set('display_startup_errors', 1); // display faires that didn't born

$verify = $control->validate_auth();
if ($verify){
    $username = $_SESSION['sessuser'];
    $fObject = $control->getFilesForDisplay();
}

?>

<html>
<head>
    <meta charset="UTF=8">
    <meta name="viewport" content="width==device-width, initial-scale=0.1">
    <link rel="stylesheet" href="/css/browser.css">
</head>

<body>
<tbody>
    <table id="fileTables" class="fileTables" border=2px>
        <tr bgcolor="grey">
            <th colspan=2>File Name</th>
            <th>Last Modified</th>
            <th>File Size</th>
        </tr>
        <tr class="hotbar">
            <td bgcolor="white"><input type="checkbox" name="massSelect[]" id="massSelect" onchange="checkAll(this)"/></td>
            <td style="font-size: 20" bgcolor="white">
            <a id='refresh' href='#'>⟲</a>
            <a id='wayBack' href='#'>↩</a>
            </td>
            <td colspan=2 style="font-size:12" bgcolor="black"><font id='displayCwd' style="color:white;"> Loading Files...</font>
            </td>
        </tr>
        <?php echo "<form action='/delete.php?' id='deleteForm'  method='post'>";?>
    </table>
</tbody>

</body>
<div id="buttonDivs" class="buttonDivs">
    <button id="delete" disabled="true">Delete</button>
    </form>
    <br><br>

    <?php echo "<form id='uploadFile' action='/upload.php' method='POST' enctype='multipart/form-data'>"?>
    <input type="file" onchange="getRequestUploadFile()" id="file" name="file[]" multiple="">
    </form>

    <?php echo "<form id='uploadDir' action='/uploadDir.php' method='POST' enctype='multipart/form-data'>"?>
    <input type="file" onchange="getRequestUploadDir()" id="dir" name="dir[]" webkitdirectory mozdirectory multiple />
    </form>

    <?php echo "<form action='/mkdir.php' method='POST'>"?>
    <button type="submit" id="mkdir">Create Folder...</button>
    <input type="text" id="createDir" name="createDir" required minlength="1" maxlength="255" size="10" />
    </form>
</div>

<div id="logBox">
    <h4 id="logHeader">---Logs---</h4>
    <div id="logOutput"></div>
</div>

<div id="refreshDBDiv">
    <button id="refreshDB" disabled="true" onclick="refreshDB()">Refresh DB</button>
</div>

<div id="refreshLogsDiv">
    <button id="refreshLogs" onclick="refreshLogs()">Refresh Log List</button>
</div>

<script type='text/javascript'>

function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function removeElementsByClass(className){
    const elements = document.getElementsByClassName(className);
    while(elements.length > 0){
        elements[0].parentNode.removeChild(elements[0]);
    }
}

function displayFiles (cwdURI){
    var userName = <?php echo json_encode($username); ?>;
    var cwd = decodeURIComponent(cwdURI);
    var table = document.getElementById("fileTables");
    var form0 = document.createElement('form');
    var form = document.getElementById('deleteForm');
    countReset();
    removeElementsByClass('tableItems');
    document.cookie = "cwd="+cwd;
    form.textContent = '';
    table.append(form0);
    if (jArray !== null){
        for (var i=0; i<jArray.length; i++){
            const fileArray = jArray[i].split("|");
            if (cwd == fileArray[4]){
                var row = table.insertRow(-1);
                var cell0 = row.insertCell(0);
                var cell1 = row.insertCell(1);
                var cell2 = row.insertCell(2);
                var cell3 = row.insertCell(2);
                var dir = fileArray[4].concat(fileArray[1]);
                var dirURI = encodeURIComponent(dir);
                var checkboxes = document.createElement("INPUT");
                checkboxes.setAttribute("type", "checkbox");
                checkboxes.setAttribute("class", "cb");
                checkboxes.setAttribute("name", "fileToDelete[]");
                checkboxes.setAttribute("id", fileArray[1]);
                checkboxes.setAttribute("value", fileArray[1]);
                cell0.appendChild(checkboxes);
                if (!dir.endsWith("/")){
                    cell1.insertAdjacentHTML('beforeEnd', "<a href='download.php?"+userName+":"+dirURI+"'>"+fileArray[1]);
                } else {
                    cell1.insertAdjacentHTML('beforeEnd', "<a href=javascript:displayFiles(\""+dirURI+"\")>"+fileArray[1]);
                }
                console.log(cwd);
                cell2.innerHTML = fileArray[3];
                cell3.innerHTML = fileArray[2];
                row.className = "tableItems";
            }
        }
    }
    var wayBack = document.getElementById('wayBack');
    var refresh = document.getElementById('refresh');
    if (cwd !== "/"){
        var i = cwd.substring(0, cwd.lastIndexOf("/"));
        var o = i.substring(0, i.lastIndexOf("/") + 1);
        wayBack.setAttribute("onclick", "javascript:displayFiles('"+encodeURIComponent(o)+"');return false;");
        wayBack.removeAttribute("hidden");
    } else{
        wayBack.setAttribute("hidden", "true");
    }
    refresh.setAttribute("onclick", "javascript:displayFiles('%2F');return false;");
    document.getElementById('displayCwd').textContent = userName+":"+cwd;
    deleteItems();
}


function countReset(){
    count = 0;
    document.getElementById('delete').disabled = true;
    document.getElementById('massSelect').checked = false;
}


function checkAll(ele) {
   var form = document.getElementById('deleteForm');
    var checkboxes = document.getElementsByClassName('cb');
    if (ele.checked) {
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox' && checkboxes[i].checked == false) {
                checkboxes[i].checked = true;
                count += 1;
                let p = checkboxes[i].cloneNode(true);
                form.appendChild(p)
                console.log(count);
                document.getElementById('delete').disabled = false;
            }

        }
    } else {
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox' && checkboxes[i].checked == true) {
                checkboxes[i].checked = false;
                count -= 1;
                document.getElementById('delete').disabled = true;
            }
        }
        form.textContent = '';
    }
}

function refreshDB() {
    getRequestUpdateRecords();
}

function refreshLogs() {
    document.cookie = "log=;";
    document.getElementById('logOutput').innerHTML = '';
}

function deleteItems() {
   var form = document.getElementById('deleteForm');
   var results = document.getElementsByClassName("cb");
    Array.prototype.forEach.call(results, function(checks) {
        checks.addEventListener('change', function(e) {
            if (checks.checked == true) {
                count += 1;
                let p = checks.cloneNode(true);
                console.log(count);
                form.appendChild(p)
            } else {
                count -= 1;
                var p = checks.value;
                var old = document.getElementById(p)
                document.getElementById('refreshDB').disabled = false;
                console.log(checks);
                form.removeChild(old);
            }
            if (count == 0) {
                document.getElementById('delete').disabled = true;
            } else {
                document.getElementById('delete').disabled = false;
            }
        });
    });
}

function getRequestUploadFile() {
    var formData = new FormData( document.getElementById("uploadFile") );
    var xhttp = new XMLHttpRequest();
    var log = "> Uploading File(s)...<br><br>";
    document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
    cookieLogAdd(log);
        xhttp.onreadystatechange = function() {
            if(xhttp.readyState == 4 && xhttp.status == 200)
            {
                var log = "> File(s) Uploading Finished!<br><br>";
                document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
                cookieLogAdd(log);
                location.reload();
            }
        };
        xhttp.open("post", "/upload.php", true);
        xhttp.send(formData);
}


function getRequestUploadDir() {
    var formData = new FormData( document.getElementById("uploadDir") );
    var xhttp = new XMLHttpRequest();
    var log = "> Uploading Directory...<br><br>";
    document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
    cookieLogAdd(log);
        xhttp.onreadystatechange = function() {
            if(xhttp.readyState == 4 && xhttp.status == 200)
            {
                var log = "> Directory Uploading Finished!<br><br>";
                document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
                cookieLogAdd(log);
                location.reload();
            }
        };
        xhttp.open("post", "/uploadDir.php", true);
        xhttp.send(formData);
}


function getRequestUpdateRecords (){
    var xhttp = new XMLHttpRequest();
    var log = "> Database Refreshing...<br>";
    document.getElementById('refreshDB').disabled = true;
    document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
    cookieLogAdd(log);
    xhttp.onreadystatechange = function() {
    // Write code for writing output when databse updates start.:
        if (this.readyState == 4 && this.status == 200) {
           // Write code for writing output when databse is fully updated.:
            var log = "> Database Reloaded!<br>";
            document.getElementById('refreshDB').disabled = false;
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
            location.reload();
        }
    };
    xhttp.open("GET", "/updateRecords.php", true);
    xhttp.send();
}

function cookieLogAdd(ele) {
    logcookie += ele;
    document.cookie = 'log=' + logcookie;
}


let count = 0;
let jArray = <?php echo json_encode($fObject); ?>;


let logcookie = getCookie('log');
let cwdcookie = getCookie('cwd');
document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', logcookie);
        window.onload = function () {
            setTimeout(function () {
                if (jArray === null){
                    getRequestUpdateRecords();
                }
            document.getElementById('refreshDB').disabled = false;
            displayFiles(cwdcookie);
            }, 2500); // Delay of 5 seconds
        };




</script>

</html>
