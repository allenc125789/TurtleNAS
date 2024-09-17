<?php
session_start();


class DBcontrol {
    //Sends user back to the login screen.
    public function redirect_login(){
        header('Location: /login.html');
    }

    // Verifies Session, allows user or returns user to the login page.
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

    // Verifies Privlige, allows user or returns user to the login page.
    public function validate_priv($group) {
        $username = $_SESSION['sessuser'];
        $command = shell_exec(' bash ../../../private/bash/validate-group.sh '.escapeshellarg($username)." ".escapeshellarg($group));
        switch ($command) {
            case 1:
                return true;
                break;
            default:
                $this->redirect_login();
                break;
        }
    }

    public function printUpdateList($count = FALSE) {
        ob_start();
        if ($count == FALSE){
            $command = shell_exec("apt-get --just-print dist-upgrade 2>&1 | perl -ne 'if (/Inst\s([\w,\-,\d,\.,~,:,\+]+)\s\[([\w,\-,\d,\.,~,:,\+]+)\]\s\(([\w,\-,\d,\.,~,:,\+]+)\)? /i) {print \"+<b>$1</b> [CURRENT: $2, NEW: $3]<br>\"}'");
            ob_flush();
        } else {
            $command = shell_exec('apt list --upgradable 2>/dev/null | tail -n+2 | wc -l | tr -d "[:space:]"');
            ob_flush();
        }
        return $command;
        ob_end_clean();
    }

    public function requestAptUpdate() {
        shell_exec("sudo apt-get update");
    }

    public function requestAptUpgrade() {
        shell_exec("sudo apt-get -y upgrade");
    }
}

?>
