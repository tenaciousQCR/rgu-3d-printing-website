// --
// change password modal
// --

// Get the modal
var passwordModal = document.getElementById("mModal");

// When the user clicks the button, open the modal
document.getElementById("chng-pass-btn").addEventListener("click", function (event) {
    event.preventDefault();

    passwordModal.style.display = "block";
});

// cancel btn to close modal
let cancelBtn = document.getElementById("cancel-change-pass");
cancelBtn.addEventListener("click", function (event) {
    passwordModal.style.display = "none";
});