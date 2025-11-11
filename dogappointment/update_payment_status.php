<?php
require 'db.php';

if (isset($_POST['appointment_id']) && isset($_POST['status'])) {
    $userId = intval($_POST['appointment_id']);  // using user_id here
    $status = $_POST['status'] === 'Paid' ? 'Paid' : 'Pending';

    $stmt = $conn->prepare("UPDATE appointments SET payment_status = ? WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("si", $status, $userId);
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: appointments.php?status_updated=1");
            exit;
        } else {
            $stmt->close();
            header("Location: appointments.php?error=1");
            exit;
        }
    } else {
        header("Location: appointments.php?error=1");
        exit;
    }
} else {
    header("Location: appointments.php?error=1");
    exit;
}
