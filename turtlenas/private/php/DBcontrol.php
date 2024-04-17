<?php

class DBcontrol {
    public function user_auth($username, $password) {
        
        // Session Start & Tag.
        session_start();
        $_SESSION['sessuser'] = $username;
        
        // Restricted users.
        $restricted = array("root", "sysadmin");
        
        // Deny empty strings.
        if(empty($password || $username)){
            header('Location: /index.html');
            exit;
        // Deny restricted users.
        } elseif(in_array($username, $restricted)){
            header('Location: /index.html');
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
            header('Location: /index.html');
            exit;
        // Error (Add logs)
        } else{
            header('Location: /index.html');
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
                header('Location: /login.html');
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
                header('Location: /login.html');
                break;
        }
    }

    // Function to fetch file list from directory.
    function scanDirAndSubdir($dir, &$out = []) {
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
}

?>
