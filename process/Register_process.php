<?php
include '../includes/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $plan = $_POST['plan'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    
    $fname = trim($_POST['firstname']);
    $lname = trim($_POST['lastname']);
    if ((!preg_match("/^[a-zA-Z]+$/", $fname)) || (!preg_match("/^[a-zA-Z]+$/", $lname))) {
        echo "<script>alert('Name should contain letters only!'); window.history.back();</script>";
        exit();
    }

    
    if (!preg_match('/^[0-9]{10}$/', $number)) {        
        echo "<script>alert('Invalid phone number! Must be exactly 10 digits (Ex: 0701234567)'); window.history.back();</script>";
        exit();
    }
    
    
    $emailTrimed = trim($_POST['email']);
    if (!filter_var($emailTrimed, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address! (Ex: youremail@example.com)'); window.history.back();</script>";
        exit();
    }

    
    $ageInt = intval($_POST['age']);  
    if ($ageInt < 18 || $ageInt > 100) {
        echo "<script>alert('Age must be between 18 and 100'); window.history.back();</script>";
        exit();
    }

    
    $passwordTrimed = trim($_POST['password']);
    if (strlen($passwordTrimed) < 6 || strlen($passwordTrimed) > 12) {
        echo "<script>alert('Password must be between 6 and 12 characters!'); window.history.back();</script>";
        exit();
    }

    
    if ($password !== $confirm_password) {
        echo "<script>alert('Password & Re-enter password does not matching!'); window.history.back();</script>";
        exit();
    }

    $sql = "INSERT INTO users (firstname, lastname, number, email, age, plan, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssiss", $firstname, $lastname, $number, $email, $age, $plan, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Please login to your account.'); window.location.href='../login.php';</script>";
    } else {
        if ($conn->errno == 1062) {
            echo "<script>alert('Error: This email or phone number is already registered.'); window.history.back();</script>";
        } else {
            $error = addslashes($stmt->error);
            echo "<script>alert('Registration failed. Error: $error'); window.history.back();</script>";
        }
    }
}
?>