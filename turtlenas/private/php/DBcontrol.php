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

    public function getInsertLockRecord($vusername, $vstate){
        $stmt = $this->get_connection()->prepare("INSERT INTO locks (user, state) VALUES (:vuser, :vstate)");
        $stmt->execute([
            'vusername' => $vusername,
            'vstate' => $vstate,
        ]);
    }

    public function getdeleteLockRecordByName(){
        $vusername = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->prepare("DELETE FROM locks WHERE user = :vusername");
        $stmt->bindParam(':vusername', $vusername, PDO::PARAM_STR);
        $stmt->execute(['vusername' => $vusername]);
    }

    public function getLockByName(){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT user FROM locks WHERE user = '$username'");
        while ($row = $stmt->fetch()){
            $sqlhash = $row['state'];
        }
        return $sqlhash;
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
        $username = $_SESSION['sessuser'];
        $lock = $this->getLockByName();
        if ($lock === "0" || $lock === null){
            $this->getInsertLockRecord($username, "1");
        } else {
            exit();
        }
        $skipFiles = array();
        $mtime = '';
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
        if ($lock === "1" || $lock === null){
            $this->getInsertLockRecord($username, "0");
        } else {
            exit();
        }

    }

}

?>
