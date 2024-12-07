<?php
session_start();

class DBcontrol {
    private $host;
    private $dbname;
    private $user;
    private $pass;
    private $charset;

    //Sends user back to the login screen.
    public function redirect_login(){
        header('Location: /login.html');
    }

    //Establishes connection to MariaDB.
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

    //Delete session for user when signing out.
    public function signout(){
        session_destroy();
    }

    //get the file size for files, and converts it to human readable format.
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

    //get the date for the last modified time of a file.
    public function prepFileDate($fullpath){
        if (file_exists($fullpath)) {
            return date("m/d/y (H:i:s)", filemtime($fullpath));
        }
    }

    //creates a hash for files and folders.
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

    //Fetches disk information, and root folder for users.
    public function getDiskByUser(){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT * FROM drives WHERE user = '$username'");
        while ($row = $stmt->fetch()){
            $allrows = $row['type']. "|".$row['uuid']. "|".$row['disk']. "|".$row['user'];
            $data[] = $allrows;
        }
        return $username;
    }

    //gets the full path for a file, based on the user's designated root folder.
    public function getFullPath($shortpath){
        $root = $this->getRootByUser();
        $filename = ($root . $shortpath);
        return $filename;
    }

    //gets the mtime for when a file was last modified.
    public function getMTimeByPath($fullpath){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT mtime FROM files_$username WHERE fullpath = '$fullpath'");
        while ($row = $stmt->fetch()){
            $fullpath = $row['mtime'];
            return $fullpath;
        }
    }

    //Get file records for user view in the file browser.
    public function getFilesForDisplay(){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT * FROM files_$username");
        while ($row = $stmt->fetch()){
            $allrows = $row['name']. "|".$row['date']. "|".$row['size']. "|".$row['parent']. "|".$row['mtime'];
            $data[] = $allrows;
        }
        return $data;
    }

    //Get the user's root directory
    public function getRootByUser(){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT * FROM drives WHERE user = '$username'");
        while ($row = $stmt->fetch()){
            $root = "/media/".$row['type']. "/".$row['uuid']. "/".$row['user']. "/";
            return $root;
        }
    }

    //function to delete files and directories.
    public function deleteFile(){
        $post = $_POST['fileToDelete'];
        $username = $_SESSION['sessuser'];
        $cookie = urldecode($_COOKIE["cwd"]);
        $parent = ltrim($cookie, "/");
        $root = $this->getRootByUser();
        foreach ($post as $filename){
            $filename = $root . $parent . $filename;
            if (is_dir($filename)){
                $arr = $this->scanDirAndSubdir($filename);
                foreach ($arr as $file){
                    if (is_dir($file)) {
                        rmdir($file);
                        $this->deleteRecordByPath($file);
                    } else {
                        unlink($file);
                        $this->deleteRecordByPath($file);
                    }
                }
                rmdir($filename);
                $this->deleteRecordByPath($filename);
            } else{
                unlink($filename);
                $this->deleteRecordByPath($filename);
            }
        }
        $this->updateParentRecords();
        $_POST = array();
    }

    //Function to create new directories.
    public function createDir($post){
        $username = $_SESSION['sessuser'];
        $cookie = urldecode($_COOKIE['cwd']);
        $parent = ltrim($cookie, "/");
        $root = $this->getRootByUser();
        foreach ($post as $dir){
            $pos = strpos($dir, $cookie);
            $newdir = str_replace("$root$parent", '', $dir);
            $basedir = basename($dir);
            if (!str_contains($dir, $root)){
                $this->filterString($basedir);
                mkdir($root . $parent . $basedir, 0755);
                $this->updateFileRecord($root . $parent . $basedir . "/", $_REFRESH_DB = FALSE);
            } elseif (!file_exists($root . $parent . $newdir)){
                $this->filterString($basedir);
                mkdir($root . $parent . $newdir, 0755, true);
            }
        }
    }

    //String Filter/Blacklist. Returns a 406 error.
    function filterString($dir){
        if (preg_match('/[\"^£$%&*;}\\\{@#~?><,|=¬]/', $dir)){
            header("HTTP/1.1 406 Not Acceptable");
            die();
        }
    }


    //creates an array from file names passed through POST to iterate through.
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

    //Moves and orders files uploaded from the browser.
    public function uploadFile(){
        $cookie = $_COOKIE['cwd'];
        $username = $_SESSION['sessuser'];
        $path = urldecode($cookie);
        $fullpath = $this->getFullPath($cookie);
        $sqlArray = array();
        if (isset($_FILES['file'])){
            $file_array = $this->reArrayFiles($_FILES['file']);
            for ($i=0;$i<count($file_array);$i++){
                $this->filterString($file_array[$i['name']]);
                move_uploaded_file($file_array[$i]['tmp_name'], $fullpath . $file_array[$i]['name']);
                $sqlArray[] = $fullpath . $file_array[$i]['name'];
                echo ($fullpath . $file_array[$i]['name']);
            }
            for ($i=0;$i<count($sqlArray);$i++){
                    $this->updateFileRecord($uniqueDir[$i], $_REFRESH_DB = FALSE);
            }
            $this->updateParentRecords();
        }
    }

    //Moves and orders folders uploaded from the browser.
    public function uploadDir(){
        $username = $_SESSION['sessuser'];
        $cookie = urldecode($_COOKIE['cwd']);
        $parent = ltrim($cookie, "/");
        $root = $this->getRootByUser();
        $cwd = $root . $parent;
        if (isset($_FILES['dir'])){
            $file_array = $this->reArrayFiles($_FILES['dir']);
            for ($i=0;$i<count($file_array);$i++){
                $directory[] = pathinfo($cwd . $file_array[$i]['full_path'], PATHINFO_DIRNAME);
            }
            $uniqueDir = array_unique($directory, SORT_STRING);
            $this->createDir($uniqueDir);
                for ($i=0;$i<count($file_array);$i++){
                    $file = $cwd . $file_array[$i]['full_path'];
                    $this->filterString($file_array[$i['name']]);
                    move_uploaded_file($file_array[$i]['tmp_name'], $file);
                    $this->updateFileRecord($file, $_REFRESH_DB = FALSE);
                }
                foreach ($uniqueDir as $dir){
                    $this->updateFileRecord($dir . "/", $_REFRESH_DB = FALSE);
                }
                // For Windows compatibility. If a file is uploaded from Windows OS, a DB record is not made without the following.
                for ($i=0;$i<count($file_array);$i++){
                    $file = $file_array[$i]['full_path'];
                    $fileFilter[] = substr($file, 0, strpos($file, "/"));
                    $uniqueDir = array_unique($fileFilter, SORT_STRING);
                }
                foreach ($uniqueDir as $dir){
                    $this->updateFileRecord($cwd . $dir . "/", $_REFRESH_DB = FALSE);
                }
            $this->updateParentRecords();
        }
    }

    //Updates the size of parent folder records when new files are uploaded.
    public function updateParentRecords(){
        $cwd = urldecode($_COOKIE['cwd']);
        $root = $this->getRootByUser();
        $parentArray = array();
        $parent = "$root";
        $dirNames = explode("/", $cwd . "/");
        for ($i=0;$i<count($dirNames);$i++){
            if ($dirNames[$i] !== "" && $dirNames[$i] !== $root){
                $parent .= $dirNames[$i] . "/";
                $parentArray[] .= $parent;
            }
        }
        foreach ($parentArray as $parent){
                $this->deleteRecordByPath($parent);
                $this->updateFileRecord($parent, $_REFRESH_DB = FALSE);
                setcookie('test', $parent);
        }

    }

    //Download selected files or folders.
    public function getDownload($needsRoot = TRUE){
        $username = $_SESSION['sessuser'];
        $path = urldecode($_SERVER['QUERY_STRING']);
        $shortpath = str_replace("$username:", '', $path);
        $download = urldecode($_COOKIE['download']);
        if ($needsRoot == TRUE){
            $fullpath = $this->getRootByUser($username) . $shortpath;
        } else {
            $fullpath = "/tmp/" . $download;
        }
        $mime = mime_content_type($fullpath);
        $fullpath = urldecode(trim(stripslashes($fullpath)));
        header('Content-Type: ' . $mime);
        if (!isset($_COOKIE['filename'])){
            header('Content-Disposition: attachment; filename=' . basename($fullpath));
        } else {
            header('Content-Disposition: attachment; filename=' . urldecode($_COOKIE['filename']));
        }
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fullpath));
        ob_clean();
        flush();
        setcookie("download", "");
        setcookie("filename", "");
        readfile($fullpath);
        exit;
    }

    //Creates a zip folder for downloading.
    public function execZipFolder($post = ''){
        $post = urldecode($post);
        $query = urldecode($_SERVER['QUERY_STRING']);
        $cwd = urldecode($_COOKIE['cwd']);
        $root = $this->getRootByUser();
        $fullpath = $root . $cwd;
        $basename = basename($root . $cwd);
        if ($query === "PLAINTEXT"){
            $command = shell_exec(" bash ../private/bash/zipFolder.sh $query ".escapeshellarg($fullpath));
        } elseif ($query === "ENCRYPT"){
            $command = shell_exec(" bash ../private/bash/zipFolder.sh $query ".escapeshellarg($fullpath) . " " . escapeshellarg($post));
        }
        $output = $command;
        setcookie("download", $output);
        setcookie("filename", $basename . ".zip");
    }

    //Create a tar file for downloading.
    public function execTarFolder($post = ''){
        $post = urldecode($post);
        $query = urldecode($_SERVER['QUERY_STRING']);
        $cwd = urldecode($_COOKIE['cwd']);
        $root = $this->getRootByUser();
        $fullpath = $root . $cwd;
        $basename = basename($root . $cwd);
        if ($query === "PLAINTEXT"){
            $command = shell_exec(" bash ../private/bash/tarFolder.sh $query ".escapeshellarg($fullpath));
            $output = $command;
            setcookie("download", $output);
            setcookie("filename", $basename . ".tar.gz");
        } elseif ($query === "ENCRYPT"){
            $command = shell_exec(" bash ../private/bash/tarFolder.sh $query ".escapeshellarg($fullpath) . " " . escapeshellarg($post));
            $output = $command;
            setcookie("download", $output);
            setcookie("filename", $basename . ".tar.gz.gpg");
        }
    }

    //Delete all records for a user's files.
    public function deleteAllRecords(){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->prepare("DELETE FROM files_$username");
        $stmt->execute();
    }

    //Delete a specific file by path.
    public function deleteRecordByPath($vfullpath){
        $vfullpath = addslashes($vfullpath);
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->prepare("DELETE FROM files_$username WHERE fullpath = :vfullpath");
        $stmt->bindParam(':vfullpath', $vfullpath, PDO::PARAM_STR);
        $stmt->execute(['vfullpath' => $vfullpath]);
    }

    //Function to set file records passed from updateFileRecord.
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

    //Sets SESSSION user identity and permission attributes, referring to the python PAM script for user identity, and a bash script for sudo permissions. (the bash script will be changed to reference an sql DB for permissions).
    public function user_auth($username, $password) {
        // Restricted users.
        $restricted = array("root", "sysadmin");
        // Deny empty strings.
        if(empty($password) || empty($username)){
            $this->redirect_login();
            exit;
        // Deny restricted users.
        } elseif(in_array($username, $restricted)){
            $this->redirect_login();
            exit;
        // Allow all else.
        } else {
            $command = shell_exec(' sudo python3 ../private/python3/pam-auth.py '.escapeshellarg($username)." ".escapeshellarg($password));
            $output = "$command";
        }
        // Run Python3 code through Bash.
        // Successful Auth.
        if($output == "1"){
            $_SESSION['allowed'] = 1;
            $_SESSION['sessuser'] = $username;
        // Failed Auth.
        } elseif($output == "0"){
            $this->redirect_login();
            exit;
        // Error (Add logs)
        } else{
            $this->redirect_login();
            exit;
        }
    }


    // Verifies Session, allows user or returns user to the login page.
    public function validate_auth() {
        $validated = $_SESSION['allowed'];
        switch ($validated) {
            case 1:
                return true;
                break;
            default:
                return false;
                break;
        }
    }

    // Verifies Privlige for group argument, allows user or returns user to the login page.
    public function validate_priv($group) {
        $username = $_SESSION['sessuser'];
        $command = shell_exec(' bash ../private/bash/validate-group.sh '.escapeshellarg($username)." ".escapeshellarg($group));
        switch ($command) {
            case 1:
                return true;
                break;
            default:
                return false;
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

    //Checks files from an array for updating records.
    public function updateFileRecord($afiles = NULL, $_REFRESH_DB = TRUE) {
        $username = $_SESSION['sessuser'];
        $mtime = '';
        $root = $this->getRootByUser();
        if (is_null($afiles)){
            $bfiles = $this->scanDirAndSubdir($root);
        } else {
            $bfiles = array($afiles);
        }
        if ($_REFRESH_DB === TRUE){
            $this->deleteAllRecords();
        }
        // Insert new files into database.
        foreach ($bfiles as $fullpath) {
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

?>
