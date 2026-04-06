// ============================
// 🎯 Login Form Validation + SweetAlert Error Message
// ============================

// Input fields
const userNameInput = document.getElementById("userName");
const passwordInput = document.getElementById("password");
const userNameError = document.getElementById("userNameError");
const passwordError = document.getElementById("passwordError");
const form = document.getElementById("loginForm");

// ============================
// 🧩 Username Validation
// ============================
userNameInput.onblur = function () {
    if (userNameInput.value.trim().length < 2) {
        userNameInput.classList.add("invalid");
        userNameError.textContent = "Name must be at least 2 characters.";
    } else {
        userNameInput.classList.remove("invalid");
        userNameError.textContent = "";
    }
};

// ============================
// 🔒 Password Validation
// ============================
passwordInput.onblur = function () {
    const passwordValue = passwordInput.value.trim();
    const passwordPattern =
        /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    if (!passwordPattern.test(passwordValue)) {
        passwordInput.classList.add("invalid");
        passwordError.textContent =
            "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.";
    } else {
        passwordInput.classList.remove("invalid");
        passwordError.textContent = "";
    }
};

// ============================
// 🚫 Form Submission Validation
// ============================
form.addEventListener("submit", function (e) {
    const isUserNameValid = userNameInput.value.trim().length >= 2;
    const passwordPattern =
        /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    const isPasswordValid = passwordPattern.test(passwordInput.value.trim());

    if (!isUserNameValid || !isPasswordValid) {
        e.preventDefault(); // Stop form submission
        Swal.fire({
            icon: "warning",
            title: "Invalid Form",
            text: "Please enter a valid username and password!",
        });
    }
});

// ============================
// ⚠️ Show Error Alert (From PHP redirect)
// ============================
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get("error") === "invalid") {
    Swal.fire({
        icon: "error",
        title: "Login Failed",
        text: "Invalid Username or Password!",
        confirmButtonColor: "#5c39d1",
    }).then(() => {
        // 🧹 Remove ?error=invalid from URL to prevent popup on refresh
        window.history.replaceState({}, document.title, window.location.pathname);
    });
}
