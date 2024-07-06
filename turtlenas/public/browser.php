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

<div id="contents">
    <body>
        <tbody>
            <table id="fileTables" class="fileTables" border=2px>
                <tr bgcolor="grey">
                    <th colspan=2>File Name</th>
                    <th>Last Modified</th>
                    <th>File Size</th>
                </tr>
                <tr class="hotbar">
                    <td bgcolor="white"><input type="checkbox" name="massSelect[]" id="massSelect" onchange="checkAll(this)"></input></td>
                    <td style="font-size: 20" bgcolor="white">
                    <a id='refresh' href='#'>⟲</a>
                    <a id='wayBack' href='#'>↩</a>
                    </td>
                    <td colspan=2 style="font-size:12" bgcolor="black"><font id='displayCwd' style="color:white;"> Loading Files...</font>
                    </td>
                </tr>
                <?php echo "<form id='deleteForm' method='post'>";?>
            </table>
        </tbody>
    </body>
</div>

<div id="buttonDivs" class="buttonDivs">
    <label for="delete" id="deleteTxt" class="buttonTxt" style="cursor:default; background: #c7c7c7">Delete</label>
    <input class="buttons" id="delete" value="Delete" type="button" onclick="getRequestDelete()" disabled="true">
    </form>
    <br><br>

    <?php echo "<form id='uploadFile' action='/upload.php' method='POST' enctype='multipart/form-data'>"?>
    <label for="file" id="fileTxt" class="buttonTxt">Upload File</label>
    <input class="buttons" type="file" onchange="getRequestUploadFile()" id="file" name="file[]" multiple="">
    </form>

    <?php echo "<form id='uploadDir' action='/uploadDir.php' method='POST' enctype='multipart/form-data'>"?>
    <label for="dir" id="dirTxt" class="buttonTxt">Upload Folder</label>
    <input class="buttons" type="file" onchange="getRequestUploadDir()" id="dir" name="dir[]" directory webkitdirectory mozdirectory multiple />
    </form>

    <?php echo "<form id='makeDir' onsubmit='getRequestMakeDir()' taget='_self' method='POST'>"?>
    <label for="mkdir" id="mkdirTxt" class="buttonTxt">Create Folder...</label>
    <button class="buttons" type="submit" id="mkdir"></button>
    <input type="text" id="createDir" name="createDir" required minlength="1" maxlength="255" size="10" />
    </form>
</div>

<div id="logBox">
    <h4 id="logHeader">---Logs---</h4>
    <div id="logOutput"></div>
</div>

<div id="refreshDBDiv">
    <label for="refreshDB" id="refreshDBTxt" class="buttonTxt" style="cursor:default; background: #c7c7c7">Refresh DB</label>
    <button class="buttons" id="refreshDB" disabled="true" onclick="refreshDB()"></button>
</div>

<div id="refreshLogsDiv">
    <label for="refreshLogs" id="buttonTxt" class="buttonTxt">Refresh Log List</label>
    <button class="buttons" id="refreshLogs" onclick="refreshLogs()"></button>
</div>

<script type='text/javascript'>
function getCookie(cname){
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

function displayFiles(cwdURI){
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
            if (cwd == fileArray[3]){
                var row = table.insertRow(-1);
                var cell0 = row.insertCell(0);
                var cell1 = row.insertCell(1);
                var cell2 = row.insertCell(2);
                var cell3 = row.insertCell(2);
                var dir = fileArray[3].concat(fileArray[0]);
                var dirURI = encodeURIComponent(dir);
                var checkboxes = document.createElement("INPUT");
                checkboxes.setAttribute("type", "checkbox");
                checkboxes.setAttribute("class", "cb");
                checkboxes.setAttribute("name", "fileToDelete[]");
                checkboxes.setAttribute("id", fileArray[0]);
                checkboxes.setAttribute("value", fileArray[0]);
                cell0.appendChild(checkboxes);
                if (!dir.endsWith("/")){
                    cell1.insertAdjacentHTML('beforeEnd', "<a href='download.php?"+userName+":"+dirURI+"'>"+fileArray[0]);
                } else {
                    cell1.insertAdjacentHTML('beforeEnd', "<a href=javascript:displayFiles(\""+dirURI+"\")>"+fileArray[0]);
                }
                console.log(cwd);
                cell2.innerHTML = fileArray[2];
                cell3.innerHTML = fileArray[1];
                row.className = "tableItems";
            }
        }
    }
    var wayBack = document.getElementById('wayBack');
    var refresh = document.getElementById('refresh');
    if (cwd !== "/"){
        var i = cwd.substring(0, cwd.lastIndexOf("/"));
        var o = i.substring(0, i.lastIndexOf("/") + 1);
        wayBack.setAttribute("onclick", "javascript:displayFiles(\""+encodeURIComponent(o)+"\");return false;");
        wayBack.removeAttribute("hidden");
    } else{
        wayBack.setAttribute("hidden", "true");
    }
    refresh.setAttribute("onclick", "javascript:displayFiles('%2F');return false;");
    document.getElementById('displayCwd').textContent = userName+":"+cwd;
    deleteItems();
}

function checkAll(ele){
   var form = document.getElementById('deleteForm');
    var checkboxes = document.getElementsByClassName('cb');
    if (ele.checked){
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox' && checkboxes[i].checked == false){
                checkboxes[i].checked = true;
                count += 1;
                let p = checkboxes[i].cloneNode(true);
                form.appendChild(p)
                console.log(count);
                enableButtons("delete");
//                document.getElementById('delete').disabled = false;
            }

        }
    } else {
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox' && checkboxes[i].checked == true){
                checkboxes[i].checked = false;
                count -= 1;
                disableButtons("delete");
//                document.getElementById('delete').disabled = true;
            }
        }
        form.textContent = '';
    }
}

function disableButtons(ID){
    if (ID == "ALL"){
        var n = document.getElementsByClassName('buttons');
        var l = document.getElementsByClassName('cb');
        for(var i=0;i<n.length;i++){
            n[i].disabled = true;
        }
        for(var i=0;i<l.length;i++){
            l[i].disabled = true;
        }
        document.getElementById('massSelect').disabled = true;
        document.getElementById("createDir").disabled = true;
        document.getElementById("refresh").style.visibility = "hidden";
        document.getElementById("wayBack").style.visibility = "hidden";
        var buttonsTxt = document.getElementsByClassName("buttonTxt")
        for(var i=0;i<buttonsTxt.length;i++){
            buttonsTxt[i].style.background = "#c7c7c7";
            buttonsTxt[i].style.cursor = "default";
        }
    } else if (ID == 'massSelect'){
        var button = document.getElementById(ID);
        button.disabled = true;
    } else if (ID.tagName == 'a'){
        var button = document.getElementById(ID);
        button.style.visibility = "hidden";
    } else {
        var button = document.getElementById(ID);
        button.disabled = true;
        var button = document.getElementById(ID+"Txt");
        button.style.background = "#c7c7c7";
        button.style.cursor = "default";
    }
}

function enableButtons(ID){
    if (ID == "ALL"){
        var n = document.getElementsByClassName('buttons');
        var l = document.getElementsByClassName('cb');
        for(var i=0;i<n.length;i++){
            n[i].disabled = false;
        }
        for(var i=0;i<l.length;i++){
            l[i].disabled = false;
        }
        document.getElementById('massSelect').disabled = false;
        document.getElementById("refresh").style.visibility = "visible";
        document.getElementById("wayBack").style.visibility = "visible";
        var buttonsTxt = document.getElementsByClassName("buttonTxt")
        for(var i=0;i<buttonsTxt.length;i++){
            buttonsTxt[i].style.background = "white";
            buttonsTxt[i].style.cursor = "pointer";
        }
    } else if (ID == 'massSelect'){
        button = document.getElementById(ID);
        button.disabled = false;
    } else if (ID.tagName == 'a'){
        var button = document.getElementById(ID);
        button.style.visibility = "visible";
    } else {
        var button = document.getElementById(ID);
        button.disabled = false;
        var ID = ID+"Txt";
        var button = document.getElementById(ID);
        button.style.background = "white";
        button.style.cursor = "pointer";
    }
}

function refreshDB() {
    getRequestUpdateRecords();
}

function cookieLogAdd(ele){
    logcookie += ele;
    document.cookie = 'log=' + logcookie;
}

function refreshLogs(){
    document.cookie = "log=; expires=Thu, 01 Jan 0000 00:00:00 UTC; path=/;";
    document.getElementById('logOutput').innerHTML = '';
    delete logcookie;
    logcookie = '';
}

function countReset(){
    count = 0;
//    document.getElementById('delete').disabled = true;
    disableButtons("delete");
    document.getElementById('massSelect').checked = false;
//    disableButtons("massSelect");
}

function deleteItems(){
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
//                disableButtons("refreshDB");
//                document.getElementById('refreshDB').disabled = false;
                console.log(checks);
                form.removeChild(old);
            }
            if (count == 0) {
                disableButtons("delete");
//                document.getElementById('delete').disabled = true;
            } else {
                enableButtons("delete");
//                document.getElementById('delete').disabled = false;
            }
        });
    });
}

const activateWakeLock = async () => {
  try {
    const wakeLock = await navigator.wakeLock.request("screen");
  } catch (err) {
    // The wake lock request fails - usually system-related, such as low battery.

    console.log(`${err.name}, ${err.message}`);
  }
};

function deactivateWakeLock(){
    wakeLock.release().then(() => {
        wakeLock = null;
    });
}


function getRequestDelete(){
    if (confirm("You are about to DELETE "+count+" File(s). Continue?")){
        var xhttp = new XMLHttpRequest();
    } else {
        return;
    }
    var formData = new FormData( document.getElementById("deleteForm") );
    var log = "> Deleting Files/Directories...<br>";
    document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
    disableButtons("ALL");
    cookieLogAdd(log);
    xhttp.onreadystatechange = function(){
    // Write code for writing output when databse updates start.:
        var log = "> "+this.statusText+"!<br>-<br>";
        if (this.readyState == 4 && this.status == 200){
           // Write code for writing output when databse is fully updated.:
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
            location.reload();
        } else if(this.status < 200){
            var log = "> Please do not reload the page.<br>";
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
        } else if(this.status >= 400){
            var log = "> "+this.statusText+"("+this.status+")!<br>-<br>";
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
            location.reload();
        }
    };
    xhttp.open("POST", "/delete.php", true);
    xhttp.send(formData);
}

async function getRequestUploadFile(){
    var xhttp = new XMLHttpRequest();
    var formData = new FormData( document.getElementById("uploadFile") );
    var log = "> Uploading File(s)...<br>";
    document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
    activateWakeLock();
    disableButtons("ALL");
    cookieLogAdd(log);
    xhttp.onreadystatechange = function(){
    // Write code for writing output when databse updates start.:
        var log = "> "+this.statusText+"!<br>-<br>";
        if (this.readyState == 4 && this.status == 200){
           // Write code for writing output when databse is fully updated.:
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
            location.reload();
        } else if(this.status < 200){
            var log = "> Please do not reload the page.<br>";
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
        } else if(this.status >= 400){
            var log = "> "+this.statusText+"("+this.status+")!<br>-<br>";
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
            location.reload();
        }
    };
    xhttp.open("POST", "/upload.php", true);
    xhttp.send(formData);
}

async function getRequestMakeDir(){
    var xhttp = new XMLHttpRequest();
    var formData = new FormData( document.getElementById("makeDir") );
    var log = "> Creating Folder...<br>";
    document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
    activateWakeLock();
    disableButtons("ALL");
    cookieLogAdd(log);
    event.preventDefault();

    xhttp.onreadystatechange = function(){
    // Write code for writing output when databse updates start.:
        var log = "> "+this.statusText+"!<br>-<br>";
        if (this.readyState == 4 && this.status == 200){
           // Write code for writing output when databse is fully updated.:
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
            location.reload();
        } else if(this.status < 200){
            var log = "> Please do not reload the page.<br>";
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
        } else if(this.status >= 400){
            var log = "> "+this.statusText+"("+this.status+")!<br>-<br>";
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
            location.reload();
        }
    };
    xhttp.open("POST", "/mkdir.php", true);
    xhttp.send(formData);
}


function getRequestUploadDir(){
    var xhttp = new XMLHttpRequest();
    var formData = new FormData( document.getElementById("uploadDir") );
    var log = "> Uploading Directory...<br>";
    document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
    activateWakeLock();
    disableButtons("ALL");
    cookieLogAdd(log);
    xhttp.onreadystatechange = function(){
    // Write code for writing output when databse updates start.:
        var log = "> "+this.statusText+"!<br>-<br>";
        if (this.readyState == 4 && this.status == 200){
           // Write code for writing output when databse is fully updated.:
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
            location.reload();
        } else if(this.status < 200){
            var log = "> Please do not reload the page.<br>";
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
        } else if(this.status >= 400){
            var log = "> "+this.statusText+"("+this.status+")!<br>-<br>";
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
            location.reload();
        }
    };
    xhttp.open("POST", "/uploadDir.php", true);
    xhttp.send(formData);
}

function getRequestUpdateRecords(){
    if (confirm("Refreshing the DataBase can fix files not appearing and inaccurate data, but will take time depending on the number of files. Continue?")){
        var xhttp = new XMLHttpRequest();
    } else {
        return;
    }
    var log = "> Database Refreshing...<br>";
    document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
    activateWakeLock();
    disableButtons("ALL");
    cookieLogAdd(log);
    xhttp.onreadystatechange = function(){
    // Write code for writing output when databse updates start.:
        var log = "> "+this.statusText+"!<br>-<br>";
        if (this.readyState == 4 && this.status == 200){
           // Write code for writing output when databse is fully updated.:
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
            location.reload();
        } else if(this.status < 200){
            var log = "> Please do not reload the page.<br>";
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
        } else if(this.status >= 400){
            var log = "> "+this.statusText+"("+this.status+")!<br>-<br>";
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
            location.reload();
        }
    };
    xhttp.open("GET", "/updateRecords.php", true);
    xhttp.send();
}

let wakeLock = null;
let count = 0;
let jArray = <?php echo json_encode($fObject); ?>;


let logcookie = getCookie('log');
let cwdcookie = getCookie('cwd');
document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', logcookie);
        window.onload = function () {
            setTimeout(function () {
                if (jArray === null){
                    disableButtons("ALL");
                    getRequestUpdateRecords();
                }
            enableButtons('refreshDB');
            displayFiles(cwdcookie);
            }, 2500); // Delay of 5 seconds
        };




</script>

</html>
