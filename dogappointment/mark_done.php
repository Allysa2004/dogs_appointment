<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];
    $stmt = $conn->prepare("UPDATE appointments SET done_status = 'Done' WHERE user_id = ?");
    $stmt->bind_param("i", $appointment_id);
    if ($stmt->execute()) {
        header("Location: appointments.php?done_updated=1");
    } else {
        header("Location: appointments.php?error=1");
    }
    $stmt->close();
    exit;
}
header("Location: appointments.php?error=1");
exit;