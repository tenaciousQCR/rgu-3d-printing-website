// login register modal
let loginRegisterModal = document.getElementById("loginRegisterModal");

// When the user clicks the button, open the modal
document.getElementById("register").addEventListener("click", function(event){
    loginRegisterModal.style.display = "block";
    // check checkbox to change to register form
    document.getElementById("form-switch").checked = true;
});
document.getElementById("login").addEventListener("click", function(event){
    loginRegisterModal.style.display = "block";
    // uncheck checkbox to change to login form
    document.getElementById("form-switch").checked = false;
});

// When the user clicks cancel
document.getElementById("cancel-lr").addEventListener("click", function(event){
    loginRegisterModal.style.display = "none";
});