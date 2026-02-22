<?php
session_start();
include '../includes/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $class_name = $_POST['class_name'];
    $booking_date = $_POST['booking_date'];
    $trainer = $_POST['trainer'];

    $sql = "INSERT INTO bookings (user_id, class_name, booking_date, trainer) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $class_name, $booking_date, $trainer);

    if ($stmt->execute()) {
        echo "Booking successful! <a href='../dashboard.php'>Go to Dashboard</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>