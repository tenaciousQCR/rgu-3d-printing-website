// login register modal
// Get the modal
let modal = document.getElementById("loginRegisterModal");

// When the user clicks the button, open the modal
document.getElementById("register").addEventListener("click", function(event){
    modal.style.display = "block";
    // check checkbox to change to register form
    document.getElementById("form-switch").checked = true;
});
document.getElementById("login").addEventListener("click", function(event){
    modal.style.display = "block";
    // uncheck checkbox to change to login form
    document.getElementById("form-switch").checked = false;
});

// When the user clicks cancel
document.getElementById("cancel-lr").addEventListener("click", function(event){
    modal.style.display = "none";
});

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
}