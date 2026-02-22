<?php
session_start();
include '../includes/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_id = $_POST['login_id'];
    $password = $_POST['password'];

    
    $passwordTrimed = trim($_POST['password']);
    if (strlen($passwordTrimed) < 6 || strlen($passwordTrimed) > 12) {
        echo "<script>alert('Password must be between 6 and 12 characters!'); window.history.back();</script>";
        exit();
    }

    $sql = "SELECT * FROM users WHERE email = ? OR number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $login_id, $login_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['plan'] = $user['plan'];
            $_SESSION['role'] = $user['role'] ?? 'member';

            header("Location: ../dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password! Please check.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No user found with this email/phone!'); window.history.back();</script>";
    }
}
?>