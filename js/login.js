function validateForm() {
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    let error = document.getElementById("error");

    if (email === "" || password === "") {
        error.innerText = "All fields are required!";
        return false;
    }

    if (password.length < 6) {
        error.innerText = "Password must be at least 6 characters!";
        return false;
    }

    return true;
}

function togglePassword() {
    let pass = document.getElementById("password");

    if (pass.type === "password") {
        pass.type = "text";
    } else {
        pass.type = "password";
    }
}
