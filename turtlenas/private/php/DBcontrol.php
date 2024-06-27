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
root@home-ok-na01p:/home/user# nano /var/www/turtlenas/public/browser.php 
root@home-ok-na01p:/home/user# nano /var/www/turtlenas/public/browser.php 
root@home-ok-na01p:/home/user# nano /var/www/turtlenas/private/php/DBcontrol.php 
root@home-ok-na01p:/home/user# nano /var/www/turtlenas/private/php/DBcontrol.php 
root@home-ok-na01p:/home/user# nano /media/LOCAL/5d2aee01-0ecd-4ac4-b2ca-796b24be7e34/admin/
Lamar Pics 06.10.2024/ more stufff/           stuff' 2/              User-Manual.txt
root@home-ok-na01p:/home/user# nano /media/LOCAL/5d2aee01-0ecd-4ac4-b2ca-796b24be7e34/admin/stuff\'\ 2/^C
root@home-ok-na01p:/home/user# nano /var/www/turtlenas/private/php/DBcontrol.php 
root@home-ok-na01p:/home/user# nano /var/www/turtlenas/private/php/DBcontrol.php 
root@home-ok-na01p:/home/user# nano /var/www/turtlenas/private/php/DBcontrol.php 
root@home-ok-na01p:/home/user# cat /var/www/turtlenas/private/php/DBcontrol.php 
<?php
session_start();

error_reporting(-1); // display all faires
ini_set('display_errors', 1);  // ensure that faires will be seen
ini_set('display_startup_errors', 1); // display faires that didn't born



class DBcontrol {
    private $host;
    private $dbname;
    private $user;
    private $pass;
    private $charset;

    public function redirect_login(){
        header('Location: /login.html');
    }

    public function get_connection(){
        $this->host = "localhost";
        $this->dbname = "turtlenas";
        $this->user = "www-data";
        $this->pass = "";
        $this->charset = "utf8mb4";
        try{
            $dsn = "mysql:host=".$this->host.";dbname=".$this->dbname.";charset=".$this->charset;
            $pdo = new PDO($dsn, $this->user, $this->pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e){
            echo "Connection failed: ".$e->getMessage();
        }
    }

    public function prepFileSize($fullpath, $size='0', $unit=''){
        if (is_dir($fullpath)){
            $scandir = $this->scanDirAndSubdir($fullpath, $_FilesOnly = TRUE);
            foreach ($scandir as $files){
                $sfiles = filesize($files);
                $size += $sfiles;
            }
        } else{
            $size = filesize($fullpath);
        }
        if( (!$unit && $size >= 1<<30) || $unit == "GB"){
            return number_format($size/(1<<30),2)."GB";
        } if( (!$unit && $size >= 1<<20) || $unit == "MB"){
            return number_format($size/(1<<20),2)."MB";
        } if( (!$unit && $size >= 1<<10) || $unit == "KB"){
            return number_format($size/(1<<10),2)."KB";
        }
        return number_format($size)."B";
    }

    public function prepFileDate($fullpath){
        if (file_exists($fullpath)) {
            return date("m/d/y (H:i:s)", filemtime($fullpath));
        }
    }

    public function prepFileHash($fullpath, $hfile = ''){
        if ((file_exists($fullpath)) && (!is_dir($fullpath))) {
            return hash_file('sha224', "$fullpath");
        } elseif (file_exists($fullpath)) {
            $scandir = $this->scanDirAndSubdir($fullpath, $_FilesOnly = TRUE);
            foreach ($scandir as $file){
                $hfile .= hash_file('sha224', "$file");
            }
            return hash('sha224', "$hfile");
        }
    }

    public function getDiskByUser(){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT * FROM drives WHERE user = '$username'");
        while ($row = $stmt->fetch()){
            $allrows = $row['type']. "|".$row['uuid']. "|".$row['disk']. "|".$row['user'];
            $data[] = $allrows;
        }
        return $username;
    }

    public function getShortPath($fullpath){
        $username = $_SESSION['sessuser'];
        $root = $this->getRootByUser();
        $parent = str_replace($root,'',$fullpath);
        if ("$fullpath/" == $root){
            $parent = '/';
            return $parent;
        } else {
            return "/$parent/";
        }
    }

    public function getFullPath($shortpath){
        $root = $this->getRootByUser();
        $filename = ($root . $shortpath);
//        $fullpath = str_replace("\'", "\\'", $filename);
        return $filename;
    }

    public function getFullPathByHash($hash){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT fullpath FROM files_$username WHERE hash = '$hash'");
        while ($row = $stmt->fetch()){
            $fullpath = $row['fullpath'];
            return $fullpath;
        }
    }

    public function getNameByHash($hash){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT name FROM files_$username WHERE hash = '$hash'");
        while ($row = $stmt->fetch()){
            $fullpath = $row['fullpath'];
            return $fullpath;
        }
    }

    public function getParentByHash($hash){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT parent FROM files_$username WHERE hash = '$hash'");
        while ($row = $stmt->fetch()){
            $fullpath = $row['fullpath'];
            return $fullpath;
        }
    }


    public function getFTimeByPath($fullpath){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT mtime FROM files_$username WHERE fullpath = '$fullpath'");
        while ($row = $stmt->fetch()){
            $fullpath = $row['mtime'];
            return $fullpath;
        }
    }

    public function getFilesForDisplay($query){
        $username = $_SESSION['sessuser'];
        $query = str_replace("'", "\\'", "$query");
        $path = str_replace("$username:", '', "$query");
        $stmt = $this->get_connection()->query("SELECT * FROM files_$username");
        while ($row = $stmt->fetch()){
            $allrows = $row['fullpath']. "|".$row['name']. "|".$row['date']. "|".$row['size']. "|".$row['parent']. "|".$row['mtime'];
            $data[] = $allrows;
        }
        return $data;
    }

    public function getRootByUser(){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT * FROM drives WHERE user = '$username'");
        while ($row = $stmt->fetch()){
            $root = "/media/".$row['type']. "/".$row['uuid']. "/".$row['user']. "/";
            return $root;
        }
    }

    public function getParentByQuery($query){
        $username = $_SESSION['sessuser'];
        $path = str_replace("$username:", '', "$query");
        $root = $this->getRootByUser();
        if ($path !== "/"){
            $path = ltrim($path, '/');
            $path = $this->getFullPath($path);
            $path = dirname($path);
            $path = $this->getShortPath($path);
            return $path;
        }
        $stmt = $this->get_connection()->query("SELECT parent FROM files_$username WHERE name = '$path'");
        while ($row = $stmt->fetch()){
            $parent = $row['parent'];
            return $parent;

        }
    }

    public function getPathByPath($data = ''){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT fullpath FROM files_$username");
        while ($row = $stmt->fetch()){
            $newpath = $row['fullpath'];
            $data .= ("$newpath|");
            $sqlpaths = explode('|', $data);
        }
        return $sqlpaths;
    }

    public function getHashByPath($fullpath, $data = '', $sqlhash = ''){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT hash FROM files_$username WHERE fullpath = '$fullpath'");
        while ($row = $stmt->fetch()){
            $sqlhash = $row['hash'];
        }
        return $sqlhash;
    }

    public function deleteFile(){
        $post = $_POST['fileToDelete'];
        $username = $_SESSION['sessuser'];
        $query = urldecode($_SERVER['QUERY_STRING']);
        $parent = str_replace("$username:/", '', "$query");
        $root = $this->getRootByUser();
        foreach ($post as $filename){
            $filename = $root . $parent . $filename;
            if (is_dir($filename)){
                $arr = $this->scanDirAndSubdir($filename);
                foreach ($arr as $file){
                    if (is_dir($file)) {
                        rmdir($file);
                    } else {
                        unlink($file);
                    }
                }
                rmdir($filename);
            } else{
                unlink($filename);
                echo $filename;
            }
        }
        $_POST = array();
//        header("Location: /browser.php?$query");
    }

    public function createDir($post){
        $username = $_SESSION['sessuser'];
        $query = urldecode($_SERVER['QUERY_STRING']);
        $parent = str_replace("$username:/", '', "$query");
        $root = $this->getRootByUser();
        foreach ($post as $dir){
            $pos = strpos($dir, $query);
            $newdir = str_replace("$root$parent", '', $dir);
            if ($pos === false && str_contains($dir, $root) && !file_exists($root . $parent . $newdir)){
                mkdir($root . $parent . $newdir, 0777, true);
            } elseif (!str_contains($dir, $root)){
                mkdir($root . $parent . $dir, 0777);
//                echo ($root . $parent . $dir ."/");
            }
        }
    }

    function reArrayFiles(&$file_post) {
        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);
        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }
        return $file_ary;
    }

    public function uploadFile(){
        error_reporting(-1); // display all faires
        ini_set('display_errors', 1);  // ensure that faires will be seen
        ini_set('display_startup_errors', 1); // display faires that didn't born
        $query = urldecode($_SERVER['QUERY_STRING']);
        $username = $_SESSION['sessuser'];
        $path = str_replace("$username:/", '', $query);
        $fullpath = $this->getFullPath($path);


        if (isset($_FILES['file'])){
            $file_array = $this->reArrayFiles($_FILES['file']);
            for ($i=0;$i<count($file_array);$i++){
                move_uploaded_file($file_array[$i]['tmp_name'], $fullpath . $file_array[$i]['name']);
                echo ($fullpath . $file_array[$i]['name']);

            }
        }
    }

    public function uploadDir(){
        $query = urldecode($_SERVER['QUERY_STRING']);
        $query = str_replace("%20", " ", $query);
        $username = $_SESSION['sessuser'];
        $path = str_replace("$username:/", '', $query);
        $fullpath = $this->getFullPath($path);
        if (isset($_FILES['dir'])){
            $file_array = $this->reArrayFiles($_FILES['dir']);
            for ($i=0;$i<count($file_array);$i++){
                $directory[] = pathinfo($fullpath . $file_array[$i]['full_path'], PATHINFO_DIRNAME);
            }
            $uniqueDir = array_unique($directory, SORT_STRING);
            $this->createDir($uniqueDir);
                for ($i=0;$i<count($file_array);$i++){
                    $parent = pathinfo($file_array[$i]['full_path']);
                    move_uploaded_file($file_array[$i]['tmp_name'], $fullpath . $file_array[$i]['full_path']);
                }
        }
    }

    public function deleteRecordByPath($vfullpath){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->prepare("DELETE FROM files_$username WHERE fullpath = :vfullpath");
        $stmt->bindParam(':vfullpath', $vfullpath, PDO::PARAM_STR);
        $stmt->execute(['vfullpath' => $vfullpath]);
    }

    public function getInsertFileRecord($vfullpath, $vparent, $vname, $vdate, $vsize, $vmtime){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->prepare("INSERT INTO files_$username (fullpath, parent, name, date, size, mtime) VALUES (:vfullpath, :vparent, :vname, :vdate, :vsize, :vmtime)");
        $stmt->execute([
            'vfullpath' => addslashes($vfullpath),
            'vparent' => $vparent,
            'vname' => $vname,
            'vdate' => $vdate,
            'vsize' => $vsize,
            'vmtime' => $vmtime,
        ]);
    }

//    public function save_to_file_manager($data=[]){

    public function user_auth($username, $password) {
        // Restricted users.
        $restricted = array("root", "sysadmin");
        // Deny empty strings.
        if(empty($password || $username)){
            $this->redirect_login();
            exit;
        // Deny restricted users.
        } elseif(in_array($username, $restricted)){
            $this->redirect_login();
            exit;
        // Allow all else.
        } else {
            $command = shell_exec(" sudo python3 ../private/python3/pam-auth.py $username $password 2>&1");
            $output = "$command";
        }
        // Run Python3 code through Bash.
        // Successful Auth.
        if($output){
            $_SESSION['allowed'] = 1;
            $_SESSION['sessuser'] = $username;
        // Failed Auth.
        } elseif(!$output){
            $this->redirect_login();
            exit;
        // Error (Add logs)
        } else{
            $this->redirect_login();
            exit;
        }
        // Set Privlige through Bash.
        $command2 = shell_exec(" bash ../private/bash/admin-check.sh $username 2>&1");
        $output2 = "$command2";
        // Admin True
        if ($output2 == "1"){
            $_SESSION['admin_status'] = 1;
        // Admin False
        } else{
            $_SESSION['admin_status'] = 0;
        }
    }

    // Verify Session.
    public function validate_auth() {
        $validated = $_SESSION['allowed'];
        switch ($validated) {
            case 1:
                return true;
                break;
            default:
                $this->redirect_login();
                break;
        }
    }

    // Verify Privlige.
    public function validate_priv() {
        $privlige = $_SESSION['admin_status'];
        switch ($privlige) {
            case 1:
                return true;
                break;
            default:
                $this->redirect_login();
                break;
        }
    }

    // Function to fetch file list from directory.
    public function scanDirAndSubdir($dir, $_FilesOnly = FALSE, &$out = []) {
        $username = $_SESSION['sessuser'];
        $root = $this->getRootByUser();
        $sun = scandir(($dir));
        foreach ($sun as $a => $filename) {
            $way = realpath($dir . DIRECTORY_SEPARATOR . $filename);
    // List Files.
            if (!is_dir($way)) {
                $out[] = $way;
    // List Directories.
            } else if (!$_FilesOnly && $filename != "." && $filename != "..") {
                $this->scanDirAndSubdir($way, FALSE, $out);
                $out[] = ("$way/");
            } else if ($_FilesOnly && $filename != "." && $filename != "..") {
                $this->scanDirAndSubdir($way, TRUE, $out);
            }
        }
        return $out;
    }

    public function updateFileRecord() {
        $skipFiles = array();
        $mtime = '';
        $username = $_SESSION['sessuser'];
        $root = $this->getRootByUser();
        $afiles = $this->scanDirAndSubdir($root);
        $sqlpathcheck = $this->getPathByPath();
        // Remove old files from database.
        foreach ($sqlpathcheck as $sqlpath) {
            $sqlmtime = $this->getFTimeByPath($sqlpath);
            try {
//                $mtime = stat($sqlpath);
            } catch (Exception $e) {
                continue;
            }
//            $sqlhashcheck = $this->getHashByPath($sqlpath);
//            $realhash = $this->prepFileHash($sqlpath);
            $stat = stat(stripslashes($sqlpath));
            if (!file_exists($sqlpath)) {
                $this->deleteRecordByPath($sqlpath);
            } elseif ($stat['mtime'] == $sqlmtime) {
                $skipFiles[] = $sqlpath;
            }
        }
        // Insert new files into database.
        foreach ($afiles as $fullpath) {
            if (!in_array($fullpath, $skipFiles)) {
                $parse = dirname($fullpath). '/';
                $parent = str_replace($root, '/', $parse);
                $filename = str_replace($parse, '', $fullpath);
                $date = $this->prepFileDate($fullpath);
                $size = $this->prepFileSize($fullpath);
                $mtime = stat($fullpath);
                try {
                    $this->getInsertFileRecord($fullpath, $parent, $filename, $date, $size, $mtime['mtime']);
                } catch (PDOException $e) {
                    continue;
                }
            }
        }
    }

}

?>
