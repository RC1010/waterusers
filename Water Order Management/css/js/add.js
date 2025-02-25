// Toggle Password Visibility
const pwShowHide = document.querySelectorAll(".eye-icon");

pwShowHide.forEach(eyeIcon => {
    eyeIcon.addEventListener("click", () => {
        let password = eyeIcon.previousElementSibling;
        
        if (password.type === "password") {
            password.type = "text";
            eyeIcon.classList.replace("bx-hide", "bx-show");
        } else {
            password.type = "password";
            eyeIcon.classList.replace("bx-show", "bx-hide");
        }
    });
});

const password = document.getElementById("password");
const confirmPassword = document.getElementById("confirm_password");
const errorMessage = document.getElementById("password-error");
const submitBtn = document.getElementById("submitBtn");

function validatePassword() {
    if (confirmPassword.value !== "") { // Check if the user has started typing in the Confirm Password field
        if (password.value !== confirmPassword.value) {
            errorMessage.style.display = "block";
            submitBtn.disabled = true; // Disable submit button if passwords don't match
        } else {
            errorMessage.style.display = "none";
            submitBtn.disabled = false; // Enable submit button if passwords match
        }
    }
}

