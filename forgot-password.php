<?php
$pageTitle = "Reset Password - GymFlow";
$currentPage = "login";
$extraStyles = ["style.css"];
include 'includes/header.php';
?>

<div class="login-container" style="margin: 100px auto;">
    <h1>Reset Password</h1>

    <p style="color: #aaa; margin-bottom: 20px;">Enter your email and new password to reset your account access.</p>

    <form action="process/reset_password_process.php" method="POST">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="Enter your email" required style="width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #444; background: #222; color: #fff;">
        
        <label>New Password</label>
        <input type="password" name="password" placeholder="Enter new password" required style="width: 100%; padding: 12px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #444; background: #222; color: #fff;">

        <button type="submit" class="member-btn">Update Password</button>

        <p style="margin-top: 20px; text-align: center;">Back to <a href="login.php">Login</a></p>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
