<?php
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$appointments = [];

$stmt = $conn->prepare("SELECT * FROM appointments WHERE owner_name = ? ORDER BY appointment_datetime DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Appointment History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            color: #1f2937;
            margin-bottom: 30px;
            font-size: 28px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            text-align: left;
            padding: 14px 16px;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #2563eb;
            color: white;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f1f5f9;
        }

        .status {
            font-weight: bold;
        }

        .paid {
            color: #16a34a;
        }

        .pending {
            color: #f59e0b;
        }

        .back-link {
            text-align: center;
            margin-top: 30px;
        }

        .back-link a {
            text-decoration: none;
            background-color: #2563eb;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .back-link a:hover {
            background-color: #1e40af;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                background: white;
                border: 1px solid #e5e7eb;
                margin-bottom: 15px;
                border-radius: 8px;
                padding: 12px;
            }

            td {
                padding: 10px;
                position: relative;
            }

            td::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                margin-bottom: 6px;
                color: #6b7280;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-dog"></i> My Appointment History</h2>

    <?php if (empty($appointments)): ?>
        <p style="text-align: center;">No appointments found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>👤 Owner's Name</th>
                    <th>🐶 Dog's Name</th>
                    <th>🧼 Service(s)</th>
                    <th>📅 Appointment Date/Time</th>
                    <th>💰 Total Price</th>
                    <th>💳 Payment Status</th>
                    <th>✅ Done Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td data-label="Owner's Name"><?= htmlspecialchars($appointment['owner_name'] ?? 'N/A') ?></td>
                        <td data-label="Dog's Name"><?= htmlspecialchars($appointment['dog_name'] ?? 'N/A') ?></td>
                        <td data-label="Service(s)"><?= htmlspecialchars($appointment['service_type'] ?? 'N/A') ?></td>
                        <td data-label="Appointment Date"><?= date('F j, Y g:i A', strtotime($appointment['appointment_datetime'])) ?></td>
                        <td data-label="Total Price">₱<?= number_format($appointment['price'] ?? 0, 2) ?></td>
                        <td data-label="Payment Status" class="status <?= $appointment['payment_status'] === 'Paid' ? 'paid' : 'pending' ?>">
                            <?= $appointment['payment_status'] === 'Paid' ? '✅ Paid' : '⌛ Pending' ?>
                        </td>
                        <td data-label="Done Status" class="status <?= $appointment['done_status'] === 'Done' ? 'paid' : 'pending' ?>">
                            <?= $appointment['done_status'] === 'Done' ? '✔️ Done' : '⏳ Not Done' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="back-link">
        <a href="customer_dashboard.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

</body>
</html>
