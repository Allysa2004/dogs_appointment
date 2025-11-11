<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$id = $_GET['id'] ?? null;

if (!$id) {
    exit('Missing appointment ID.');
}

// Fetch appointment
$stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ? AND owner_name = ?");
$stmt->bind_param("is", $id, $username);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();
$stmt->close();

if (!$appointment) {
    exit('Appointment not found.');
}

if (strtotime($appointment['appointment_datetime']) - time() < 86400) {
    exit('Cannot edit appointments within 24 hours.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newDate = $_POST['appointment_datetime'];

    $stmt = $conn->prepare("UPDATE appointments SET appointment_datetime = ? WHERE id = ? AND owner_name = ?");
    $stmt->bind_param("sis", $newDate, $id, $username);
    $stmt->execute();
    $stmt->close();

    // Email notification
    $stmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();

    $subject = "Appointment Rescheduled";
    $message = "Hello $username,\n\nYour appointment has been rescheduled to: $newDate.\n\nThank you.";
    $headers = "From: no-reply@yourdomain.com";

    mail($email, $subject, $message, $headers);

    header('Location: customer_appointments.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Appointment</title>
</head>
<body>
<h2>Edit Appointment for <?= htmlspecialchars($appointment['dog_name']) ?></h2>
<form method="post">
    <label>New Appointment Date & Time:</label><br>
    <input type="datetime-local" name="appointment_datetime" value="<?= date('Y-m-d\TH:i', strtotime($appointment['appointment_datetime'])) ?>" required><br><br>
    <button type="submit">Save Changes</button>
</form>
<br>
<a href="appointments_history.php">← Back to Appointments</a>
</body>
</html>
