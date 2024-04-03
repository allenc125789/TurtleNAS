$command = shell_exec(" bash ../private/bash/handle-pam-auth.sh $username $password' 2>&1");
$output = "$command";

// Deny/Allow Access.
if($output){
    echo "if statement working";
} elseif(!$output){
    header('Location: /index.html');
    exit;
} else{
    echo "<pre>$output</pre>";
}
