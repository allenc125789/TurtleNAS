<?php

class DBcontrol {

    // Verifies Privlige, allows user or returns user to the login page.
    public function validate_priv($group) {
        $username = $_SESSION['sessuser'];
        $command = shell_exec(' bash ../../../private/bash/validate-group.sh '.escapeshellarg($username)." ".escapeshellarg($group));
        $output = var_dump($command);
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

<?
