<?php
session_start();

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

    public function prepFileSize($fullpath, $unit=''){
        $size = filesize($fullpath);
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

    public function prepFileHash($fullpath){
        if ((file_exists($fullpath)) && (!is_dir($fullpath))) {
            return hash_file('sha224', "$fullpath");
        } else {
            return '';
        }
    }

    public function getDiskByUser(){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT * FROM drives WHERE user = '$username'");
        while ($row = $stmt->fetch()){
            $allrows = $row['type']. "|".$row['uuid']. "|".$row['disk']. ";|".$row['user'];
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
        return $filename;
    }

    public function getFilesForDisplay(){
        $username = $_SESSION['sessuser'];
        $parent = $_SERVER['QUERY_STRING'];
        $stmt = $this->get_connection()->query("SELECT * FROM files_$username WHERE parent = '$parent'");
        while ($row = $stmt->fetch()){
            $allrows = $row['fullpath']. "|".$row['name']. "|".$row['date']. "|".$row['size']. "|".$row['parent'];
            $data[] = $allrows;
        }
        return $data;
//        var_dump($data);
    }

    public function getRootByUser(){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT * FROM drives WHERE user = '$username'");
        while ($row = $stmt->fetch()){
            $root = "/media/".$row['type']. "/".$row['uuid']. "/".$row['user']. "/";
            return $root;
        }
    }


// need to use filename, grab parent. session passes current folder.
    public function getParentByQuery(){
        $username = $_SESSION['sessuser'];
        $query = $_SERVER['QUERY_STRING'];
        $root = $this->getRootByUser();
        if ($query !== "/"){
            $query = ltrim($query, '/');
            $query = $this->getFullPath($query);
            $query = dirname($query);
            $query = $this->getShortPath($query);
            return $query;
        }
        $stmt = $this->get_connection()->query("SELECT parent FROM files_$username WHERE name = '$query'");
        while ($row = $stmt->fetch()){
            $parent = $row['parent'];
            return $parent;

        }
//        return $query;
    }

    public function getPathByPath(){
        $data = '';
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT fullpath FROM files_$username");
        while ($row = $stmt->fetch()){
            $newpath = $row['fullpath'];
            $data .= ("$newpath|");
            $sqlpaths = explode('|', $data);
        }
        return $sqlpaths;
    }

    public function getHashByPath($fullpath){
        $data = '';
        $sqlhash = '';
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT hash FROM files_$username WHERE fullpath = '$fullpath'");
        while ($row = $stmt->fetch()){
            $sqlhash = $row['hash'];
        }
        return $sqlhash;
    }

    public function deleteRecordByPath($vfullpath){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->prepare("DELETE FROM files_$username WHERE fullpath = :vfullpath");
        $stmt->bindParam(':vfullpath', $vfullpath, PDO::PARAM_STR);
        $stmt->execute(['vfullpath' => $vfullpath]);
    }

    public function getInsertFileRecord($vfullpath, $vparent, $vname, $vdate, $vsize, $vhash){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->prepare("INSERT INTO files_$username (fullpath, parent, name, date, size, hash) VALUES (:vfullpath, :vparent, :vname, :vdate, :vsize, :vhash)");
        $stmt->execute([
            'vfullpath' => $vfullpath,
            'vparent' => $vparent,
            'vname' => $vname,
            'vdate' => $vdate,
            'vsize' => $vsize,
            'vhash' => $vhash,
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
    public function scanDirAndSubdir($dir, &$out = []) {
        $username = $_SESSION['sessuser'];
        $root = $this->getRootByUser();
        $sun = scandir(($dir));
        foreach ($sun as $a => $filename) {
            $way = realpath($dir . DIRECTORY_SEPARATOR . $filename);
    // List Files.
            if (!is_dir($way)) {
                $out[] = $way;
    // List Directories.
            } else if ($filename != "." && $filename != "..") {
                $this->scanDirAndSubdir($way, $out);
                $out[] = ("$way/");
//            } else if ($filename == "..") {
//                $out[] = ("$way/");
            }
        }
        return $out;
    }

    public function updateFileRecord() {
        $username = $_SESSION['sessuser'];
        $root = $this->getRootByUser();
        $afiles = $this->scanDirAndSubdir($root);
        $sqlpathcheck = $this->getPathByPath();
        // Remove old files from database.
        foreach ($sqlpathcheck as $sqlpath) {
            $sqlhashcheck = $this->getHashByPath($sqlpath);
            $realhash = $this->prepFileHash($sqlpath);
            if ((!file_exists($sqlpath)) || ($sqlhashcheck !== $realhash)) {
                $this->deleteRecordByPath($sqlpath);
            } else {
                continue;
            }
        }
        // Insert new files into database.
        foreach ($afiles as $fullpath) {
            $parse = dirname($fullpath). '/';
            $parent = str_replace($root, '/', $parse);
            $filename = str_replace($parse, '', $fullpath);
            $date = $this->prepFileDate($fullpath);
            $size = $this->prepFileSize($fullpath);
            $hash = $this->prepFileHash($fullpath);
            try {
                $this->getInsertFileRecord($fullpath, $parent, $filename, $date, $size, $hash);
            } catch (PDOException $e) {
                continue;
            }
//            if ($filename == "../"){
//                try {
//                    $this->getInsertFileRecord($fullpath, $parent, $filename, '', '', '');
//                } catch (PDOException $e) {
//                    continue;
//                }
//            }
        }
    }

}

?>
