// Get the modal
let errorModal = document.getElementById("error-modal");

// cancel btn to close modal
let okBtn = document.getElementById("error-ok");
okBtn.addEventListener("click", function (event) {
    errorModal.style.display = "none";
});

// show the modal
function showModal() {
    errorModal.style.display = "block";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    if (event.target === errorModal) {
        // outside of user message modal clicked
        errorModal.style.display = "none";
    }
    try {
        if (event.target === loginRegisterModal) {
            // outside of login/register modal clicked
            loginRegisterModal.style.display = "none";
        }
    } catch (e) {
        // ignore..
    }

    try {
        if (event.target === passwordModal) {
            // outside of password modal clicked
            passwordModal.style.display = "none";
        }
    } catch (e) {
        // ignore..
    }
}