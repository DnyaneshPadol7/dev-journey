// Username Validation
function checkUsername() {
    let input = document.getElementById("userName");
    let error = document.getElementById("userNameError");

    if (input.value.trim() === "") {
        error.textContent = "";
        input.style.borderColor = "";
        input.style.backgroundColor = "";
        return false;
    }

    if (!/^[A-Za-z ]+$/.test(input.value)) {
        error.textContent = "** User Name must contain only letters (A-Z or a-z)";
        input.style.borderColor = "red";
        input.style.backgroundColor = "#ffe6e6";
        return false;
    }

    if (input.value.length < 2 || input.value.length > 50) {
        error.textContent = "** User Name must be between 2 and 50 characters";
        input.style.borderColor = "red";
        input.style.backgroundColor = "#ffe6e6";
        return false;
    }

    error.textContent = "";
    input.style.borderColor = "lime";
    input.style.backgroundColor = "#e6ffe6";
    return true;
}

// Email Validation
function checkEmail() {
    let input = document.getElementById("email");
    let error = document.getElementById("emailError");

    if (input.value.trim() === "") {
        error.textContent = "";
        input.style.borderColor = "";
        input.style.backgroundColor = "";
        return false;
    }

    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
    if (!emailPattern.test(input.value.trim())) {
        error.textContent = "** Please enter a valid email address";
        input.style.borderColor = "red";
        input.style.backgroundColor = "#ffe6e6";
        return false;
    }

    if (input.value.length > 30) {
        error.textContent = "** Email length should not exceed 30 characters";
        input.style.borderColor = "red";
        input.style.backgroundColor = "#ffe6e6";
        return false;
    }

    error.textContent = "";
    input.style.borderColor = "lime";
    input.style.backgroundColor = "#e6ffe6";
    return true;
}

// Mobile Validation
function checkMobile() {
    let input = document.getElementById("mobileNo");
    let error = document.getElementById("mobileNoError");
    let mobile = input.value.trim();

    if (mobile === "") {
        error.textContent = "";
        input.style.borderColor = "";
        input.style.backgroundColor = "";
        return false;
    }

    if (!/^\d+$/.test(mobile)) {
        error.textContent = "** Mobile number must contain only digits";
        input.style.borderColor = "red";
        input.style.backgroundColor = "#ffe6e6";
        return false;
    }

    if (mobile.length !== 10) {
        error.textContent = "** Mobile number must be 10 digits only";
        input.style.borderColor = "red";
        input.style.backgroundColor = "#ffe6e6";
        return false;
    }

    error.textContent = "";
    input.style.borderColor = "lime";
    input.style.backgroundColor = "#e6ffe6";
    return true;
}

// Password Validation
function checkPassword() {
    let input = document.getElementById("password");
    let error = document.getElementById("passwordError");
    let password = input.value.trim();

    if (password === "") {
        error.textContent = "";
        input.style.borderColor = "";
        input.style.backgroundColor = "";
        return false;
    }

    if (password.length < 8) {
        error.textContent = "** Password must be at least 8 characters long";
        input.style.borderColor = "red";
        input.style.backgroundColor = "#ffe6e6";
        return false;
    }

    if (!/[A-Z]/.test(password)) {
        error.textContent = "** Password must include at least one uppercase letter";
        input.style.borderColor = "red";
        input.style.backgroundColor = "#ffe6e6";
        return false;
    }

    if (!/[a-z]/.test(password)) {
        error.textContent = "** Password must include at least one lowercase letter";
        input.style.borderColor = "red";
        input.style.backgroundColor = "#ffe6e6";
        return false;
    }

    if (!/[0-9]/.test(password)) {
        error.textContent = "** Password must include at least one number";
        input.style.borderColor = "red";
        input.style.backgroundColor = "#ffe6e6";
        return false;
    }

    if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        error.textContent = "** Password must include at least one special character";
        input.style.borderColor = "red";
        input.style.backgroundColor = "#ffe6e6";
        return false;
    }

    error.textContent = "";
    input.style.borderColor = "lime";
    input.style.backgroundColor = "#e6ffe6";
    return true;
}

// Address Validation (optional)
function checkAddress() {
    let input = document.getElementById("address");
    let error = document.getElementById("addressError");
    if (input.value.trim().length >= 2) {
        error.textContent = "";
        input.style.borderColor = "lime";
        input.style.backgroundColor = "#e6ffe6";
        return true;
    }
    error.textContent = "";
    input.style.borderColor = "";
    input.style.backgroundColor = "";
    return false;
}

// Form Validator
function validateForm() {
    return checkUsername() && checkEmail() && checkMobile() && checkPassword() && checkAddress();
}

// Optional: Add blur events for instant feedback
document.getElementById("userName").onblur = checkUsername;
document.getElementById("email").onblur = checkEmail;
document.getElementById("mobileNo").onblur = checkMobile;
document.getElementById("password").onblur = checkPassword;
document.getElementById("address").onblur = checkAddress;
