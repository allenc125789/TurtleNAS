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

    public function getDiskByUser(){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT * FROM drives WHERE user = '$username'");
        while ($row = $stmt->fetch()){
            $allrow = $row['type']. " ".$row['uuid']. " ".$row['disk']. " ".$row['user'];
            $data = explode(' ', $allrow);
            return $data;
        }
    }

    public function get_fullpath_ByUser(){
        $username = $_SESSION['sessuser'];
        $sfullpath = '';
        $stmt = $this->get_connection()->query("SELECT * FROM files_$username");
        while ($row = $stmt->fetch()){
            $sfullpath .= $row['fullpath']. " ";
            $fullpath = explode (' ', $sfullpath);
        }
        return $fullpath;
    }

    public function get_parent_ByUser(){
        $username = $_SESSION['sessuser'];
        $sparent = '';
        $stmt = $this->get_connection()->query("SELECT * FROM files_$username");
        while ($row = $stmt->fetch()){
            $sparent .= $row['parent']. " ";
            $parent = explode (' ', $sparent);
        }
            return $parent;
    }

    public function get_name_ByUser(){
        $username = $_SESSION['sessuser'];
        $sfilename = '';
        $stmt = $this->get_connection()->query("SELECT * FROM files_$username");
        while ($row = $stmt->fetch()){
            $sfilename .= $row['name']. " ";
            $filename = explode (' ', $sfilename);
        }
            return $filename;
    }

    public function getRootByUser(){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT * FROM drives WHERE user = '$username'");
        while ($row = $stmt->fetch()){
            $root = "/media/".$row['type']. "/".$row['uuid']. "/".$row['user'];
            return $root;
        }
    }

    public function getPathByPath(){
        $data = '';
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT fullpath FROM files_$username");
        while ($row = $stmt->fetch()){
            $newpath = $row['fullpath'];
            $data .= ("$newpath ");
            $sqlpaths = explode(' ', $data);
        }
        return $sqlpaths;
    }

    public function deleteRecordByPath($vfullpath){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->prepare("DELETE FROM files_$username WHERE fullpath = :vfullpath");
        $stmt->bindParam(':vfullpath', $vfullpath, PDO::PARAM_STR);
        $stmt->execute(['vfullpath' => $vfullpath]);
    }

    public function getInsertFileRecord($vfullpath, $vparent, $vname){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->prepare("INSERT INTO files_$username (fullpath, parent, name) VALUES (:vfullpath, :vparent, :vname)");
        $stmt->execute([
            'vfullpath' => $vfullpath,
            'vparent' => $vparent,
            'vname' => $vname,
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
        $sun = scandir($dir);
        foreach ($sun as $a => $filename) {
            $way = realpath($dir . DIRECTORY_SEPARATOR . $filename);
    // List Files.
            if (!is_dir($way)) {
                $out[] = $way;
    // List Directories.
            } else if ($filename != "." && $filename != "..") {
                $this->scanDirAndSubdir($way, $out);
                $out[] = ("$way/");
            }
        }
        return $out;
    }

    public function updateFileRecord() {
        $root = $this->getRootByUser();
        $afiles = $this->scanDirAndSubdir($root);
        $sqlcheck = $this->getPathByPath();
        // Remove old files from database.
        foreach ($sqlcheck as $sqlpath) {
            if (!file_exists($sqlpath)) {
                $this->deleteRecordByPath($sqlpath);
            } else {
                continue;
            }
        }
        // Insert new files into database.
        foreach ($afiles as $fullpath) {
            $parse = dirname($fullpath, 2) . "/";
            $parse2 = dirname($fullpath);
            $parent = str_replace($parse, "", $parse2) . "/";
            $filename = str_replace("$parse2/", "", $fullpath);
            try {
                $this->getInsertFileRecord($fullpath, $parent, $filename);
            } catch (PDOException $e) {
                continue;
            }
        }
    }
}

?>
