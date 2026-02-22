<?php
session_start();
include '../includes/Database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    
    $sql = "UPDATE bookings SET status = 'cancelled' WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $booking_id, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Booking successfully cancelled!'); window.location.href='../dashboard.php';</script>";
    } else {
        echo "<script>alert('Error cancelling booking.'); window.location.href='../dashboard.php';</script>";
    }
} else {
    header("Location: ../dashboard.php");
}
?>
