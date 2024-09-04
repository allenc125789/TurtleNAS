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

}

?>