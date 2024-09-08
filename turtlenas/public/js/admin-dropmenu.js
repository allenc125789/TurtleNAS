var dropdown = document.getElementsByClassName("dropdown-btn");
var dropdownTxt = document.getElementsByClassName("dropdownTxt");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  });
}

for (i = 0; i < dropdownTxt.length; i++) {
  dropdownTxt[i].addEventListener("click", function() {
    this.classList.toggle("active");
  });
}
