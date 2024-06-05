document.getElementById('delete').disabled = true;
var results = document.getElementsByClassName("filechecks");
Array.prototype.forEach.call(results, function(checks) {
    console.log('test');
    checks.addEventListener('change', function(e) {
        console.log(checks.checked);
    });
});
