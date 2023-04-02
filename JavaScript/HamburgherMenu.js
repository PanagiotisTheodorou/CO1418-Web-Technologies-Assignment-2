/* THIS JAVASCRIPT FILE IS RESPONSIBLE FOR THE HAMBURGHER MENU USED IN ALL PAGES */

/* The following function allows to change display types upon clicking the button of the menu click */

function myFunction() {
    var x = document.getElementById("myLinks");
    if (x.style.display === "grid") {
        x.style.display = "none";
    } else {
        x.style.display = "grid";
    }
}