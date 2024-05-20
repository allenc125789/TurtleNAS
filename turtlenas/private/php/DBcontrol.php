<?php

session_start();


class DBcontrol {
    public function redirect_login() {
        header('Location: /login.html');
    }
    public function user_auth($username, $password) {
        // Session Start & Tag.
        $_SESSION['sessuser'] = $username;

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
            $_SESSION['admin'] = 1;
        // Admin False
        } else{
            $_SESSION['admin'] = 0;
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
        $privlige = $_SESSION['admin'];
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

    public function scanDirAndSubdir() {
        $verify = $this->validate_auth();
        if($verify){
            // Directory to fetch. (edit path with variables "/media/$vLOCATION/$vDRIVE/$vUSER" for better reference by databases)
            $afiles = $this->scanDirAndSubdir("/media/LOCAL/5d2aee01-0ecd-4ac4-b2ca-796b24be7e34/admin");
            // List files in a browser format.
            foreach ($afiles as $a2) {
                echo "<a href='/download.php?$a2'>$a2</a><br>";
        }
    }

}
?>
