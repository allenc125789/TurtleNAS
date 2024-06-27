<?php
include "../private/php/DBcontrol.php";
$control = new DBcontrol;

error_reporting(-1); // display all faires
ini_set('display_errors', 1);  // ensure that faires will be seen
ini_set('display_startup_errors', 1); // display faires that didn't born

$verify = $control->validate_auth();
if ($verify){
    $control->updateFileRecord();
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
            <td style="font-size: 20" bgcolor="white"><?php echo "<a href='/browser.php?$username:%2F'>⟲</a>";?>
            <?php if (!is_null($queryparent)):?>
            <?php echo "<a href='/browser.php?$username:". urlencode($queryparent) ."'>↩</a>";?></td>
            <?php endif;?>
            <td colspan=2 style="font-size:12" bgcolor="black"><font style="color:white;"><?php echo $query;?></font></td>
        </tr>
        <?php echo "<form action='/delete.php?$queryen' id='deleteForm'  method='post'>";?>
    </table>
</tbody>
</body>
<div id="buttonDivs" class="buttonDivs">
<button id="delete">Delete</button>
<br><br>
</form>

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

function displayFiles (parentURI){
    var jArray = <?php echo json_encode($fObject); ?>;
    var userName = <?php echo json_encode($username); ?>;
    parent = decodeURIComponent(parentURI);
    countReset();
    removeElementsByClass('tableItems');
    console.log(parent);
            var table = document.getElementById("fileTables");
            var form0 = document.createElement('form');
            table.append(form0);
    for (var i=0; i<jArray.length; i++){
        const fileArray = jArray[i].split("|");
        console.log("1");
        if (parent == fileArray[4]){
            var row = table.insertRow(-1);
            var form = document.getElementById('deleteForm');
            var cell0 = row.insertCell(0);
            var cell1 = row.insertCell(1);
            var cell2 = row.insertCell(2);
            var cell3 = row.insertCell(2);
            var dir = fileArray[4].concat(fileArray[1]);
            var dirURI = encodeURIComponent(dir);
            var checkboxes = document.createElement("INPUT");
//            document.body.append(form0);
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
            cell2.innerHTML = fileArray[3];
            cell3.innerHTML = fileArray[2];
            row.className = "tableItems";
        }
    }
    deleteItems();
//    var items = document.getElementsByClassName('tableItems');
//    form0.appendChild(items);
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




let count = 0;
displayFiles("/");
document.getElementById('delete').disabled = true;


</script>

</html>
