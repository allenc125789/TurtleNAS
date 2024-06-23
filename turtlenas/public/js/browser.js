document.getElementById('delete').disabled = true;

//function deleteItems() {
//    var results = document.getElementsByClassName("cb");
//    var count = 0;
//    Array.prototype.forEach.call(results, function(checks) {
//        checks.addEventListener('change', function(e) {
//            if (checks.checked == true) {
//                count += 1;
//            } else {
//                count -= 1;
//            }
//            if (count == 0) {
//                document.getElementById('delete').disabled = true;
//            } else {
//                document.getElementById('delete').disabled = false;
//            }
//        });
//    });
//}




function checkAll(ele) {
    var checkboxes = document.getElementsByClassName('cb');
    if (ele.checked) {
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox' && checkboxes[i].checked == false) {
                checkboxes[i].checked = true;
                count += 1;
                document.getElementById('delete').disabled = false;
            }
        }
    } else {
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox' && checkboxes[i].checked == true) {
                checkboxes[i].checked = false;
                count -= 1;
                document.getElementById('delete').disabled = true;
            }
        }
    }
}

Array.prototype.forEach.call(results, function(checks) {
    checks.addEventListener('change', function(e) {
            console.log(count);
    });
});
