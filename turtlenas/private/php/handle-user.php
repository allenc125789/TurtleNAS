<?php

session_start();

$id = session_id();
$command = shell_exec("cat /var/lib/php/sessions/sess_$id 2>&1");
$output = "$command";

// Verify Session
switch (true) {
    case strstr($output, 'allowed|i:1'):
        echo "<pre>$output</pre>";
        break;
    default:
        header('Location: /index.html');
        break;
}

?>
