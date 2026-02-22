<?php
$pageTitle = "Gym Flow Login";
$currentPage = "login";
$extraStyles = ["style.css"];

$extraScripts = ["script.js"];
include 'includes/header.php';
?>

    
    <div class="login-container">
        <h1>Welcome Back</h1>
        <p>Sign in to access your account</p>

        <form action="process/login_process.php" method="POST">
            <label>Email Address / Phone</label>
            <input type="text" name="login_id" placeholder="your.email@example.com" required>

            <label>Password</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="Enter your password" 
                minlength="6" 
                maxlength="12" 
                required>
                <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
            </div>

            <div class="extra">
                <a href="forgot-password.php">Forgot password?</a>
            </div>

            <button type="submit" class="member-btn">Sign In</button>

              <p>Don't have an account? <a href="register.php">Sign up now</a></p>
        </form>
    </div>

<?php include 'includes/footer.php'; ?>