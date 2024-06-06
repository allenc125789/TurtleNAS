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


//var arr = [];
//document.getElementById("filepicker").addEventListener(
//  "change",
//  (event) => {
//    let output = document.getElementById("listing");
//    for (const file of event.target.files) {
//      let item = document.createElement("li");
//      item.textContent = file.webkitRelativePath;
//      console.log(file.webkitRelativePath);
//    }
//  },
//  false,
//);

//console.log(arr);
