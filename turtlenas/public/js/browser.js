document.getElementById('delete').disabled = true;
var results = document.getElementsByClassName("filechecks");
let count = 0;
Array.prototype.forEach.call(results, function(checks) {
    checks.addEventListener('change', function(e) {
        if (checks.checked == true) {
            count += 1;
        } else {
            count -= 1;
        }
        if (count == 0) {
            document.getElementById('delete').disabled = true;
        } else {
            document.getElementById('delete').disabled = false;
        }
    });
});

var results = document.getElementsByClassName("filechecks");
Array.prototype.forEach.call(results, function(checks) {
    console.log(checks);
});


