<?php
require_once "../private/php/DBcontrol.php";
$control = new DBcontrol;

//Verfies creds.
$verify = $control->validate_auth();
$verifyPriv = $control->validate_priv();
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
            <!--File Table body-->
            <table id="fileTables" class="fileTables" border=2px>
                <tr bgcolor="grey">
                    <th colspan=2>File Name</th>
                    <th>Last Modified</th>
                    <th>File Size</th>
                </tr>

                <!--Hotbar/Actionbar for file table.-->
                <tr class="hotbar">
                    <!--Select all checkboxes button.-->
                    <td bgcolor="white"><input type="checkbox" name="massSelect[]" id="massSelect" onchange="checkAll(this)"></input></td>

                    <!--Hotbar Background.-->
                    <td style="font-size: 150%" bgcolor="white">

                    <!--Return to root folder button.-->
                    <a id='return' href='#'>⟲</a>

                    <!--Way back directory button.-->
                    <a id='wayBack' href='#'>↩</a>
                    </td>

                    <!--Current folder display.-->
                    <td colspan=2 style="font-size:100%" title="Current folder." bgcolor="black"><font id='displayCwd' style="color:white;"> Loading Files...</font>
                    </td>
                </tr>
                <form id='deleteForm' method='post'>
            </table>
        </tbody>
    </body>
</div>

<!--Button menu.-->
<div id="actionMenuDiv" class="actionMenuDiv">
    <!--Delete button.-->
    <label for="delete" id="deleteTxt" class="buttonTxt" style="cursor:default;">Delete</label>
    <input class="buttons" id="delete" value="Delete" type="button" onclick="getRequestDelete()" disabled="true">
    </form>
    <br><br>

    <!--Upload file button.-->
    <form id='uploadFile' action='/browser-components/upload.php' method='POST' enctype='multipart/form-data'>
    <label for="file" id="fileTxt" class="buttonTxt">Upload File</label>
    <input class="buttons" type="file" onchange="getRequestUploadFile()" id="file" name="file[]" multiple="">
    </form>
    <br><br>

    <!--Upload directory button.-->
    <form id='uploadDir' action='/browser-components/uploadDir.php' method='POST' enctype='multipart/form-data'>
    <label for="dir" id="dirTxt" class="buttonTxt">Upload Folder</label>
    <input class="buttons" type="file" onchange="getRequestUploadDir()" id="dir" name="dir[]" directory webkitdirectory mozdirectory multiple />
    </form>
    <br><br>

    <!--Create directory button.-->
    <form id='makeDir' onsubmit='getRequestMakeDir()' taget='_self' method='POST'>
    <label for="mkdir" id="mkdirTxt" class="buttonTxt">Create Folder...</label>
    <button class="buttons" type="submit" id="mkdir"></button>
    <br>

    <!--Create directory text box.-->
    <input type="text" id="createDir" name="createDir" required minlength="1" maxlength="255" size="10" />
    </form>

    <!--Log box.-->
    <div id="logBox">
        <h4 id="logHeader">---Logs---</h4>
        <div id="logOutput"></div>
    </div>

    <!--Refresh DB button.-->
    <div id="refreshDBDiv">
        <label for="refreshDB" id="refreshDBTxt" class="buttonTxt" style="cursor:default;">Refresh DB</label>
        <button class="buttons" id="refreshDB" disabled="true" onclick="getRequestUpdateRecords()"></button>
    </div>

    <!--Refresh logs button.-->
    <div id="refreshLogsDiv">
        <label for="refreshLogs" id="buttonTxt" class="buttonTxt">Refresh Log</label>
        <button class="buttons" id="refreshLogs" onclick="refreshLogs()"></button>
    </div>
</div>

<!--Download options button.-->
<div id='downloadMenuDiv'>
    <div class="dropdown">
        <div title="Download the current folder as a compressed archive(zip) file." class="select">
            <span class="selected"><img id="zipIcon" src="/images/zip-icon.png"></img></span>
        </div>
            <div class="caret"></div>
        <ul class="menu">
            <!--Button to download zip file of current folder.-->
            <li><label for="downloadZip" id="downloadZipTxt" class="subButtonTxt">Zip File</label></li>
            <input class="buttons" id="downloadZip" type="button" onclick="getRequestDownloadZip()">

            <!--Button to download encrypted zip file of current folder.-->
            <form id='downloadZipENForm' method='POST'>
            <li><label for="downloadZipEN" id="downloadZipENTxt" class="subButtonTxt">Zip File (Encrypted)</label></li>
            <input class="buttons" id="downloadZipEN" type="button" onclick="getRequestDownloadZipEN()">
            <input type='hidden' id= 'hiddenZipEN' name='tmpPass' value='' />
            </form>

            <!--Button to download tar file of current folder.-->
            <li><label for="downloadTar" id="downloadTarTxt" class="subButtonTxt">tarball</label></li>
            <input class="buttons" id="downloadTar" type="button" onclick="getRequestDownloadTar()">

            <!--Button to download encrypted tar file of current folder.-->
            <form id='downloadTarENForm' method='POST'>
            <li><label for="downloadTarEN" id="downloadTarENTxt" class="subButtonTxt">tarball GPG (Encrypted)</label></li>
            <input class="buttons" id="downloadTarEN" type="button" onclick="getRequestDownloadTarEN()">
            <input type='hidden' id= 'hiddenTarEN' name='tmpPass' value='' />
            </form>
        </ul>
    </div>
</div>

<!--Account Management section.-->
<div id='accountMenuDiv'>
    <!--Button to sign out of account.-->
    <label for="signOut" id="signOutTxt" class="buttonTxt">Log-out</label>
    <button class="buttons" id="signOut" onclick="getRequestSignOut()"></button>

    <?php if($verifyPriv){include("../private/html/browser-adminButton.html");}?>
</div>

<!--Screen blocking div for when a request is "loading".-->
<div id='window-block'><text id="loadingTxt">Loading...</text></div>

<!--JavaScript Section.-->
<script src="/js/dropmenu.js"></script>
<script type='text/javascript'>
//Gets the value of a cookie by it's name.
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

//Removes all items from the file-table.
function removeElementsByClass(className){
    const elements = document.getElementsByClassName(className);
    while(elements.length > 0){
        elements[0].parentNode.removeChild(elements[0]);
    }
}

//Refreshes the file-table and organizes files for display when called.
function displayFiles(cwdURI){
    //Establishes table variables.
    var userName = <?php echo json_encode($username); ?>;
    var cwd = decodeURIComponent(cwdURI);
    var table = document.getElementById("fileTables");
    var form0 = document.createElement('form');
    var form = document.getElementById('deleteForm');
    //Prepares table for contents.
    countReset();
    removeElementsByClass('tableItems');
    document.cookie = "cwd="+cwd;
    form.textContent = '';
    table.append(form0);
    //If files are found, start processing them into the table.
    if (jArray !== null){
        for (var i=0; i<jArray.length; i++){
            const fileArray = jArray[i].split("|");
            if (cwd == fileArray[3]){
               //Establishes table content variables.
                var row = table.insertRow(-1);
                var cell0 = row.insertCell(0);
                var cell1 = row.insertCell(1);
                var cell2 = row.insertCell(2);
                var cell3 = row.insertCell(2);
                var dir = fileArray[3].concat(fileArray[0]);
                var dirURI = encodeURIComponent(fileArray[3].concat(encodeURIComponent(fileArray[0])));
                var checkboxes = document.createElement("INPUT");
                //Sets row class name.
                row.className = "tableItems";
                //Sets checkbox attributes, according to the file in their row.
                checkboxes.setAttribute("type", "checkbox");
                checkboxes.setAttribute("class", "cb");
                checkboxes.setAttribute("name", "fileToDelete[]");
                checkboxes.setAttribute("id", fileArray[0]);
                checkboxes.setAttribute("value", fileArray[0]);
                //Sets cell 0 as a checkbox.
                cell0.appendChild(checkboxes);
                cell0.setAttribute("class", "checks");
                //Sets Cell 1 as a file or a directory.
                if (!dir.endsWith("/")){
                    cell1.insertAdjacentHTML('beforeEnd', "<img id='fileIcon' src='/images/file-icon.png'><a href=browser-components/download.php?"+dirURI+">"+fileArray[0]);
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
    //Sets directory traversal buttons.
    var wayBack = document.getElementById('wayBack');
    var returnButton = document.getElementById('return');
    if (cwd !== "/"){
        var i = cwd.substring(0, cwd.lastIndexOf("/"));
        var o = i.substring(0, i.lastIndexOf("/") + 1);
        wayBack.setAttribute("onclick", "javascript:displayFiles(\""+encodeURIComponent(o)+"\");return false;");
        wayBack.removeAttribute("hidden");
    } else{
        wayBack.setAttribute("hidden", "true");
    }
    returnButton.setAttribute("onclick", "javascript:displayFiles('%2F');return false;");
    document.getElementById('displayCwd').textContent = userName+":"+cwd;
    //Sets event listeners on checkboxes.
    selectedItemsCount();
}

//Disables buttons by ID, or "ALL" for all buttons.
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
        document.getElementById("return").style.visibility = "hidden";
        document.getElementById("wayBack").style.visibility = "hidden";
        document.getElementById("downloadMenuDiv").style.visibility = "hidden";
        document.getElementById("window-block").style.visibility = "visible";
        var buttonsTxt = document.getElementsByClassName("buttonTxt")
        for(var i=0;i<buttonsTxt.length;i++){
            buttonsTxt[i].style.background = "darkgrey";
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
        button.style.background = "darkgrey";
        button.style.cursor = "default";
    }
}

//Enables buttons by ID, or "ALL" for all buttons.
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
        document.getElementById("createDir").disabled = false;
        document.getElementById("return").style.visibility = "visible";
        document.getElementById("wayBack").style.visibility = "visible";
        document.getElementById("downloadMenuDiv").style.visibility = "visible";
        document.getElementById("window-block").style.visibility = "hidden";
        var buttonsTxt = document.getElementsByClassName("buttonTxt")
        for(var i=0;i<buttonsTxt.length;i++){
            buttonsTxt[i].style.background = "";
            buttonsTxt[i].style.cursor = "pointer";
        }
        document.getElementById('delete').disabled = true;
        document.getElementById('deleteTxt').style.background = "darkgrey"
        document.getElementById('deleteTxt').style.cursor = "default"
        countReset();
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
        button.style.background = "";
        button.style.cursor = "pointer";
    }
}

//Adds text to the log cookie.
function cookieLogAdd(ele){
    logcookie += ele;
    document.cookie = 'log=' + logcookie;
}

//Refreshes logs by deleting the log cookie.
function refreshLogs(){
    document.cookie = "log=; expires=Thu, 01 Jan 0000 00:00:00 UTC; path=/;";
    document.getElementById('logOutput').innerHTML = '';
    delete logcookie;
    logcookie = '';
}

//The "Select All" checkbox button.
function checkAll(ele){
    var form = document.getElementById('deleteForm');
    var checkboxes = document.getElementsByClassName('cb');
    //Checks all checkboxes.
    if (ele.checked){
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox' && checkboxes[i].checked == false){
                checkboxes[i].checked = true;
                count += 1;
                let p = checkboxes[i].cloneNode(true);
                form.appendChild(p)
                console.log(count);
                enableButtons("delete");
            }
        }
    //Unchecks all checkboxes.
    } else {
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox' && checkboxes[i].checked == true){
                checkboxes[i].checked = false;
                countReset();
                disableButtons("delete");
            }
        }
        form.textContent = '';
    }
}

//Resets the "checkbox selection" count to 0.
function countReset(){
    count = 0;
    disableButtons("delete");
    document.getElementById('massSelect').checked = false;
    var l = document.getElementsByClassName('cb');
    for(var i=0;i<l.length;i++){
        l[i].checked = false;
    }
}

//Adds listening events to track checkboxes being checked, determining if the delete button should be active.
function selectedItemsCount(){
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
                form.removeChild(old);
            }
            if (count == 0) {
                disableButtons("delete");
            } else {
                enableButtons("delete");
            }
        });
    });
}

//Prevents client's sceens from turning off while uploading files. If a user's screen locked, the upload could be cancelled.
const activateWakeLock = async () => {
  try {
    const wakeLock = await navigator.wakeLock.request("screen");
  } catch (err) {
    // The wake lock request fails - usually system-related, such as low battery.
    console.log(`${err.name}, ${err.message}`);
  }
};

//Allows client's screen to turn off.
function deactivateWakeLock(){
    wakeLock.release().then(() => {
        wakeLock = null;
    });
}

//Request for deleting files and folders.
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
    xhttp.open("POST", "/browser-components/delete.php", true);
    xhttp.send(formData);
}

//Request for uploading files.
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
    xhttp.open("POST", "/browser-components/upload.php", true);
    xhttp.send(formData);
}

//Request for creating a directory.
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
    xhttp.open("POST", "/browser-components/mkdir.php", true);
    xhttp.send(formData);
}

//Request for uploading a directory.
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
    xhttp.open("POST", "/browser-components/uploadDir.php", true);
    xhttp.send(formData);
}

//Request for downloading an Zip file.
function getRequestDownloadZip(){
    var xhttp = new XMLHttpRequest();
    var log = "> Downloading zip...<br>";
    document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
    activateWakeLock();
    disableButtons("ALL");
    cookieLogAdd(log);
    xhttp.onreadystatechange = function(){
    // Write code for writing output when databse updates start.:
        var log = "> "+this.statusText+"!<br>-<br>";
        if (this.readyState == 4 && this.status == 200){
           // Write code for writing output when databse is fully updated.:
            location.assign("/browser-components/downloadZip.php?DOWNLOAD");
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
            enableButtons("ALL")
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
    xhttp.open("GET", "/browser-components/downloadZip.php?PLAINTEXT", true);
    xhttp.responseType = 'blob';
    xhttp.send();
}

//Request for downloading an encrypted Zip file.
function getRequestDownloadZipEN(){
    let formPrompt = prompt("Please type a password to use for your encrypted zip.");
    var form = document.getElementById("downloadZipENForm");
    var tmpInput = document.getElementById("hiddenZipEN");
    var log = "> Downloading encrypted zip...<br>";
    if (formPrompt != null){
        var xhttp = new XMLHttpRequest();
        tmpInput.setAttribute("value", formPrompt);
        form.append(tmpInput);
    } else {
        die();
    }
    document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
    var formData = new FormData( document.getElementById("downloadZipENForm") );
    activateWakeLock();
    disableButtons("ALL");
    cookieLogAdd(log);
    xhttp.onreadystatechange = function(){
    // Write code for writing output when databse updates start.:
        var log = "> "+this.statusText+"!<br>-<br>";
        if (this.readyState == 4 && this.status == 200){
           // Write code for writing output when databse is fully updated.:
            location.assign("/browser-components/downloadZip.php?DOWNLOAD");
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
            enableButtons("ALL")
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
    xhttp.open("POST", "/browser-components/downloadZip.php?ENCRYPT", true);
    xhttp.responseType = 'blob';
    xhttp.send(formData);
}

//Request for downloading a plain-text tar file of the current folder.
function getRequestDownloadTar(){
    var xhttp = new XMLHttpRequest();
    var log = "> Downloading tar...<br>";
    document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
    activateWakeLock();
    disableButtons("ALL");
    cookieLogAdd(log);
    xhttp.onreadystatechange = function(){
    // Write code for writing output when databse updates start.:
        var log = "> "+this.statusText+"!<br>-<br>";
        if (this.readyState == 4 && this.status == 200){
           // Write code for writing output when databse is fully updated.:
            location.assign("/browser-components/downloadTar.php?DOWNLOAD");
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
            enableButtons("ALL")
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
    xhttp.open("GET", "/browser-components/downloadTar.php?PLAINTEXT", true);
    xhttp.responseType = 'blob';
    xhttp.send();
}

//Request for downloading a encrypted tar file of the current folder.
function getRequestDownloadTarEN(){
    let formPrompt = prompt("Please type a password to use for your encrypted tar.");
    var form = document.getElementById("downloadTarENForm");
    var tmpInput = document.getElementById("hiddenTarEN");
    var log = "> Downloading encrypted tar...<br>";
    if (formPrompt != null){
        var xhttp = new XMLHttpRequest();
        tmpInput.setAttribute("value", formPrompt);
        form.append(tmpInput);
    } else {
        die();
    }
    document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
    var formData = new FormData( document.getElementById("downloadTarENForm") );
    activateWakeLock();
    disableButtons("ALL");
    cookieLogAdd(log);
    xhttp.onreadystatechange = function(){
    // Write code for writing output when databse updates start.:
        var log = "> "+this.statusText+"!<br>-<br>";
        if (this.readyState == 4 && this.status == 200){
           // Write code for writing output when databse is fully updated.:
            location.assign("/browser-components/downloadTar.php?DOWNLOAD");
            document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', log);
            cookieLogAdd(log);
            enableButtons("ALL")
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
    xhttp.open("POST", "/browser-components/downloadTar.php?ENCRYPT", true);
    xhttp.responseType = 'blob';
    xhttp.send(formData);
}

//Request to update the file DB.
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
    xhttp.open("GET", "/browser-components/updateRecords.php", true);
    xhttp.send();
}

//Request to sign out of account, and sends user back to the login page.
function getRequestSignOut(){
    if (confirm("Logging off account. Continue?")){
        var xhttp = new XMLHttpRequest();
    } else {
        return;
    }
    var log = "> Logging off...<br>";
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
    xhttp.open("GET", "/signout.php", true);
    xhttp.send();
}

//Establishes variables for other functions.
let wakeLock = null;
let count = 0;
let jArray = <?php echo json_encode($fObject); ?>;
let logcookie = getCookie('log');
let cwdcookie = getCookie('cwd');

//Main JS code body.
disableButtons("ALL");
document.getElementById("logOutput").insertAdjacentHTML('beforeEnd', logcookie);
        window.onload = function () {
            setTimeout(function () {
                enableButtons("ALL");
                displayFiles(cwdcookie);
            }, 2500); // Delay of 5 seconds
        };
</script>
</html>
