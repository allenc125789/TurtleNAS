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

    public function getAllByUser(){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT * FROM drives WHERE user = '$username'");
        while ($row = $stmt->fetch()){
            $allrow = $row['type']. " ".$row['uuid']. " ".$row['disk']. " ".$row['user'];
            $data = explode(' ', $allrow);
            return $data;
        }
    }

    public function getPathByUser(){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->query("SELECT * FROM drives WHERE user = '$username'");
        while ($row = $stmt->fetch()){
            $path = "/media/".$row['type']. "/".$row['uuid']. "/".$row['user'];
            return $path;
        }
    }

    public function insertFileRecord($vname, $vfolder, $vfile, $vhash){
        $username = $_SESSION['sessuser'];
        $stmt = $this->get_connection()->prepare("INSERT INTO files_dirs (user, folder, file, hash) VALUES (:vuser, :vfolder, :vfile, :vhash)");
        $stmt->execute([
            'vhash' => $vhash,
            'vuser' => $vname,
            'vfolder' => $vfolder,
            'vfile' => $vfile,
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
        $sqlhash = '';
        $sqlfolder = '';
        $sqlfile = '';
        $username = $_SESSION['sessuser'];
        $path = $this->getPathByUser();
        $sun = scandir($dir);
        foreach ($sun as $a => $filename) {
            $way = realpath($dir . DIRECTORY_SEPARATOR . $filename);
    // List Files.
            if (!is_dir($way)) {
                $sqlhash = hash_file('sha224', "$way");
                $sqlfile = $filename;
                $out[] = $way;
    // List Directories.
            } else if ($filename != "." && $filename != "..") {
                $this->scanDirAndSubdir($way, $out);
                $sqlfolder = ("$filename/");
                $out[] = ("$way/");
            } else if (empty($sqlhash)) {
                continue;
            }
            $this->insertFileRecord($username, $sqlfolder, $sqlfile, $sqlhash);
            echo ("$username, $sqlfolder, $sqlfile, $sqlhash");
        }
        return $out;
    }

    public function listDirAndSubdir() {
        $path = $this->getPathByUser();
        $afiles = $this->scanDirAndSubdir($path);
        // List files in a browser format.
        foreach ($afiles as $a2) {
            echo "<a href='/download.php?$a2'>$a2</a><br>";
        }
    }
}
?>
