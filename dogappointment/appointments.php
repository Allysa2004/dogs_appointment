<?php
require 'db.php';

$sql = "SELECT * FROM appointments ORDER BY appointment_datetime";
$result = $conn->query($sql);
$totalAppointments = $result ? $result->num_rows : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Dog Appointments</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .total-count,
        .success-message,
        .error-message {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .success-message { color: green; }
        .error-message { color: red; }

        table {
            width: 95%;
            margin: auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 14px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        form {
            display: inline-block;
        }

        button {
            font-size: 14px;
            padding: 6px 10px;
            margin: 2px 0;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }

        .btn-delete    { background-color: #e74c3c; color: white; }
        .btn-paid      { background-color: #28a745; color: white; }
        .btn-pending   { background-color: #ffc107; color: black; }

        @media screen and (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            td {
                margin-bottom: 10px;
                border: none;
                border-bottom: 1px solid #ddd;
            }

            td::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                color: #333;
            }
        }
    </style>
</head>
<body>

<a href="admin_dashboard.php" style="display: inline-block; margin: 10px auto 20px; text-align: center; background-color: #007BFF; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">← Back to Dashboard</a>

<h2>Dog Appointments</h2>

<?php if (isset($_GET['deleted'])): ?>
    <div class="success-message">🗑️ Appointment deleted successfully.</div>
<?php endif; ?>

<?php if (isset($_GET['status_updated'])): ?>
    <div class="success-message">💰 Payment status updated successfully.</div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="error-message">⚠️ Error processing the request.</div>
<?php endif; ?>

<div class="total-count">📋 Total Appointments: <?= $totalAppointments ?></div>

<table>
    <thead>
        <tr>
            <th>User ID</th>
            <th>Owner Name</th>
            <th>Dog Name</th>
            <th>Service Type</th>
            <th>Date/Time</th>
            <th>Price</th>
            <th>Payment Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td data-label="User ID"><?= htmlspecialchars($row['user_id']) ?></td>
                    <td data-label="Owner Name"><?= htmlspecialchars($row['owner_name']) ?></td>
                    <td data-label="Dog Name"><?= htmlspecialchars($row['dog_name']) ?></td>
                    <td data-label="Service Type"><?= htmlspecialchars($row['service_type']) ?></td>
                    <td data-label="Date/Time"><?= date("M d, Y - g:i A", strtotime($row['appointment_datetime'])) ?></td>
                    <td data-label="Price">₱<?= number_format($row['price'], 2) ?></td>
                    <td data-label="Status">
                        <?= $row['payment_status'] === 'Paid' ? '✅ Paid' : '⌛ Pending' ?>
                        <br>
                        <?= $row['done_status'] === 'Done' ? '✔️ Done' : '⏳ Not Done' ?>
                    </td>
                    <td data-label="Actions">
                        <!-- Delete -->
                        <form method="POST" action="delete_appointment.php" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                            <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                            <button type="submit" class="btn-delete">🗑️ Delete</button>
                        </form>

                        <!-- Update Payment Status -->
                        <?php if ($row['payment_status'] === 'Pending'): ?>
                            <form method="POST" action="update_payment_status.php">
                                <input type="hidden" name="appointment_id" value="<?= $row['user_id'] ?>">
                                <input type="hidden" name="status" value="Paid">
                                <button type="submit" class="btn-paid">💰 Mark as Paid</button>
                            </form>
                        <?php else: ?>
                            <form method="POST" action="update_payment_status.php">
                                <input type="hidden" name="appointment_id" value="<?= $row['user_id'] ?>">
                                <input type="hidden" name="status" value="Pending">
                                <button type="submit" class="btn-pending">↩ Mark as Pending</button>
                            </form>
                        <?php endif; ?>

                        <!-- Mark as Done -->
                        <?php if ($row['done_status'] !== 'Done'): ?>
                            <form method="POST" action="mark_done.php">
                                <input type="hidden" name="appointment_id" value="<?= $row['user_id'] ?>">
                                <button type="submit" class="btn-done" style="background:#17a2b8;color:white;">✔️ Mark as Done</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8" style="text-align: center;">No appointments found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>