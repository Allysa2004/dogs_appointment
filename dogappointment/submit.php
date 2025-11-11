<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $owner_name = $_POST['owner_name'];
    $dog_name = $_POST['dog_name'];
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO appointments (owner_name, dog_name, appointment_date, appointment_time, reason) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $owner_name, $dog_name, $date, $time, $reason);

    if ($stmt->execute()) {
        echo "Appointment scheduled successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
<br><a href="index.php">Back to Form</a> | <a href="admin.php">View All Appointments</a>
