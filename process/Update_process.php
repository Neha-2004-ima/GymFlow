<?php
include '../includes/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $plan = $_POST['plan'];

    $sql = "UPDATE users SET firstname=?, lastname=?, number=?, email=?, age=?, plan=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssisi", $firstname, $lastname, $number, $email, $age, $plan, $id);

    if ($stmt->execute()) {
        header("Location: ../members_manage.php?msg=updated");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
