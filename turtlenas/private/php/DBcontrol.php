<?php
session_start();

class DBcontrol {
    public function redirect_login() {
        header('Location: /login.html');
    }

    public function get_connection(){
        $servername = "localhost";
        $username = "www-data";
        $password = "";
        $db = "turtlenas";
        $conn = new mysqli($servername, $username, $password, $db);
        //Error check.
        if($conn->connect_error){
            die("Connection failed. ".$conn->connect_error);
        }
        return $conn;
    }

    public function getAllByUser(){
        $conn = $this->get_connection();
        $username = $_SESSION['sessuser'];
        $sql = "SELECT * FROM drives WHERE user = '$username'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $allrow = $row['type']. " ".$row['uuid']. " ".$row['disk']. " ".$row['user'];
        $data = explode(' ', $allrow);
        var_dump($data);
        $conn->close();
    }

    public function getPathByUser(){
        $conn = $this->get_connection();
        $username = $_SESSION['sessuser'];
        $sql = "SELECT * FROM drives WHERE user = '$username'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $path = "/media/".$row['type']. "/".$row['uuid']. "/".$row['user'];
        return $path;
        $conn->close();
    }

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
        $sun = scandir($dir);
        foreach ($sun as $a => $filename) {
            $way = realpath($dir . DIRECTORY_SEPARATOR . $filename);
    // List Files.
            if (!is_dir($way)) {
                $out[] = $way;
    // List Directories.
            } else if ($filename != "." && $filename != "..") {
                scanDirAndSubdir($way, $out);
                $out[] = ("$way/");
            }
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
