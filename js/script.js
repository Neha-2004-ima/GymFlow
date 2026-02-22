console.log("Gym Flow JS Loaded");


function togglePassword(id) {
    let field = document.getElementById(id);
    if (field) {
        field.type = (field.type === "password") ? "text" : "password";
    }
}


function adminLogin() {
    const email = document.getElementById("email");
    if (email && email.value === "") {
        email.value = "admin@gymflow.com";
        alert("Admin demo: Try logging in with admin@gymflow.com if matches your DB");
    }
}


function togglePassword(fieldId, icon) {
    const input = document.getElementById(fieldId);

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}



function toggleBothPassword(clickedIcon) {
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm_password");
    const allIcons = document.querySelectorAll(".toggle-password");

    const isHidden = password.type === "password";

    
    password.type = isHidden ? "text" : "password";
    confirmPassword.type = isHidden ? "text" : "password";

    
    allIcons.forEach(icon => {
        icon.classList.toggle("fa-eye", !isHidden);
        icon.classList.toggle("fa-eye-slash", isHidden);
    });
}
