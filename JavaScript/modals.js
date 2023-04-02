// Get the modals
var signupModal = document.getElementById("signupModal");
var signinModal = document.getElementById("signinModal");

// Get the buttons that open the modals
var signupBtn = document.getElementById("signupBtn");
var signinBtn = document.getElementById("signinBtn");

// Get the <span> element that closes the modal
var signupClose = signupModal.getElementsByClassName("close")[0];
var signinClose = signinModal.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
signupBtn.onclick = function() {
    signupModal.style.display = "block";
}
signinBtn.onclick = function() {
    signinModal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
signupClose.onclick = function() {
    signupModal.style.display = "none";
}
signinClose.onclick = function() {
    signinModal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == signupModal) {
        signupModal.style.display = "none";
    }
    if (event.target == signinModal) {
        signinModal.style.display = "none";
    }
}