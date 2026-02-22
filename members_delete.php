<?php
include 'includes/Database.php';
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: members_manage.php");
    exit();
}

$sql = "DELETE FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: members_manage.php?msg=deleted");
} else {
    echo "Error: " . $stmt->error;
}
?>