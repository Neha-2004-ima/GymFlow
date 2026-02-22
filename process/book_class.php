<?php
session_start();
include '../includes/Database.php';

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'Not logged in']));
}

$user_id = $_SESSION['user_id'];
$class_id = $_POST['class_id'];
$date = date('Y-m-d'); 


$check = $conn->prepare("SELECT id FROM bookings WHERE user_id = ? AND class_id = ? AND status = 'active'");
$check->bind_param("ii", $user_id, $class_id);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'You already booked this class!']);
    exit();
}


$cap_sql = "SELECT total_spots, (SELECT COUNT(*) FROM bookings WHERE class_id = ? AND status = 'active') as booked FROM classes WHERE id = ?";
$cap_stmt = $conn->prepare($cap_sql);
$cap_stmt->bind_param("ii", $class_id, $class_id);
$cap_stmt->execute();
$cap_res = $cap_stmt->get_result()->fetch_assoc();

if ($cap_res['booked'] >= $cap_res['total_spots']) {
    echo json_encode(['status' => 'error', 'message' => 'Sorry class is full! no spots left']);
    exit();
}


$book = $conn->prepare("INSERT INTO bookings (user_id, class_id, booking_date) VALUES (?, ?, ?)");
$book->bind_param("iis", $user_id, $class_id, $date);

if ($book->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Class successfully booked!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
?>
