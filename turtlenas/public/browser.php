<?php
include "../private/php/DBcontrol.php";
$control = new DBcontrol;

error_reporting(-1); // display all faires
ini_set('display_errors', 1);  // ensure that faires will be seen
ini_set('display_startup_errors', 1); // display faires that didn't born

$verify = $control->validate_auth();
if ($verify){
//    $control->updateFileRecord();
    $username = $_SESSION['sessuser'];
    $queryen = $_SERVER['QUERY_STRING'];
    $query = urldecode($queryen);
    $fObject = $control->getFilesForDisplay($query);
    $casequery = str_starts_with("$username:", $query);
    $queryparent = $control->getParentByQuery($query);
}

if ($casequery || $query == NULL || $username == NULL){
    header("Location: /browser.php?$username:/");
    header('Location: /login.html');
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
            <td colspan=2 style="font-size:12" bgcolor="black"><font id='displayCwd' style="color:white;"></font>
            </td>
        </tr>
        <?php echo "<form action='/delete.php?$queryen' id='deleteForm'  method='post'>";?>
    </table>
</tbody>

</body>
<div id="buttonDivs" class="buttonDivs">
    <button id="delete" disabled="true">Delete</button>
    </form>
    <br><br>
    <button id="refresh" onclick="refreshDB()">Refresh DB</button>
    <br><br>

    <?php echo "<form action='/upload.php?$queryen' method='POST' enctype='multipart/form-data'>"?>
    <input type="file" id="button" name="file[]" multiple="" onchange="this.form.submit()">
    </form>

    <?php echo "<form action='/uploadDir.php?$queryen' method='POST' enctype='multipart/form-data'>"?>
    <input type="file" id="filepicker" name="dir[]" onchange="this.form.submit()" webkitdirectory mozdirectory multiple />
    </form>

    <?php echo "<form action='/mkdir.php?$queryen' method='POST'>"?>
    <button type="submit" id="mkdir">Create Folder...</button>
    <input type="text" id="createDir" name="createDir" required minlength="1" maxlength="255" size="10" />
    </form>
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
    cwd = decodeURIComponent(cwdURI);
    countReset();
    removeElementsByClass('tableItems');
    var table = document.getElementById("fileTables");
    var form0 = document.createElement('form');
    var form = document.getElementById('deleteForm');
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
                    cell1.insertAdjacentHTML('beforeEnd', "<a href=javascript:displayFiles('"+dirURI+"')>"+fileArray[1]);
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
    getRequest();
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





function getRequest (){
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "/updateRecords.php", true);
    xhttp.send();
}

let count = 0;
let jArray = <?php echo json_encode($fObject); ?>;
displayFiles("/");
        window.onload = function () {
            setTimeout(function () {
                if (jArray === null){
                    displayFiles(' Loading Files...');
                    getRequest();
                    location.reload();
                }
            }, 2500); // Delay of 5 seconds
        };

//window.onload = function (){
//    displayFiles("/");
//    document.getElementById('delete').disabled = true;
//    getRequest();
//};



</script>

</html>
