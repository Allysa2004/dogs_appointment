<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM appointments WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Redirect back to the main page with a success flag
        header("Location: appointments.php?deleted=1");
        exit;
    } else {
        // Redirect back with an error flag
        header("Location: appointments.php?error=1");
        exit;
    }
}
?>
