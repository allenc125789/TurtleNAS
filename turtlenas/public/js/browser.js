document.getElementById('delete').disabled = true;
var results = document.getElementsByClassName("cb");
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

var results = document.getElementsByClassName("cb");
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

 function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
  	   if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
 }
