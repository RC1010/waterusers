document.getElementById('loginButton').addEventListener('click', function() {
    document.getElementById('modal').style.display = 'flex';
});

document.getElementById('closeButton').addEventListener('click', function() {
    document.getElementById('modal').style.display = 'none';
});

window.addEventListener('click', function(event) {
    if (event.target == document.getElementById('modal')) {
        document.getElementById('modal').style.display = 'none';
    }
});

// Toggle Password Visibility
const pwShowHide = document.querySelectorAll(".eye-icon");

pwShowHide.forEach(eyeIcon => {
    eyeIcon.addEventListener("click", () => {
        let pwFields = eyeIcon.parentElement.parentElement.querySelectorAll(".password");
        
        pwFields.forEach(password => {
            if (password.type === "password") {
                password.type = "text";
                eyeIcon.classList.replace("bx-hide", "bx-show");
            } else {
                password.type = "password";
                eyeIcon.classList.replace("bx-show", "bx-hide");
            }
        });
    });
});

const button = document.querySelector(".button2");
      button.addEventListener("click", (e) => {
        e.preventDefault;
        button.classList.add("animate");
        setTimeout(() => {
          button.classList.remove("animate");
        }, 600);
      });

// Check if the error message exists
const errorMessage = document.getElementById('errorMessage');

if (errorMessage) {
    setTimeout(() => {
        errorMessage.classList.add('hide'); // Add the hide class
        // Optionally remove the element from the DOM after the transition completes
        setTimeout(() => {
            errorMessage.style.display = 'none';
        }, 500); // Delay equal to the CSS transition duration
    }, 5000); // Time before starting the hide effect
}
