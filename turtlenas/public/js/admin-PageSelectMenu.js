//Request to sign out of account, and sends user back to the login page.
function getRequestSignOut(){
    if (confirm("Logging off account. Continue?")){
        var xhttp = new XMLHttpRequest();
    } else {
        return;
    }
//    activateWakeLock();
    xhttp.onreadystatechange = function(){
    // Write code for writing output when databse updates start.:
        if (this.readyState == 4 && this.status == 200){
           // Write code for writing output when databse is fully updated.:
            location.reload();
        } else if(this.status >= 400){
            location.reload();
        }
    };
    xhttp.open("GET", "/signout.php", true);
    xhttp.send();
}
