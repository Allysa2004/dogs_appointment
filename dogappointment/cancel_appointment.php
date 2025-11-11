<?php
// Prevent duplicate session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $username = $_SESSION['username'];

    // Update appointment status if owned by this user and still booked
    $stmt = $conn->prepare("UPDATE appointments SET status = 'cancelled' WHERE user_id = ? AND owner_name = ? AND status = 'booked'");
    $stmt->bind_param("is", $appointment_id, $username);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: appointments_history.php");
        exit;
    } else {
        echo "Database error.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
