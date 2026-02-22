<?php
include '../includes/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = $_POST['password'];

    
    $emailTrimed = trim($_POST['email']);
    if (!filter_var($emailTrimed, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address! (Ex: youremail@example.com)'); window.history.back();</script>";
        exit();
    }

    
    $passwordTrimed = trim($_POST['password']);
    if (strlen($passwordTrimed) < 6 || strlen($passwordTrimed) > 12) {
        echo "<script>alert('Password must be between 6 and 12 characters!'); window.history.back();</script>";
        exit();
    }
    
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        
        $update_sql = "UPDATE users SET password = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $new_password, $email);
        
        if ($update_stmt->execute()) {
            echo "<script>alert('Password updated successfully! Please login with your new password.'); window.location.href='../login.php';</script>";
        } else {
            echo "<script>alert('Error updating password. Please try again.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No account found with that email.'); window.history.back();</script>";
    }
}
?>
