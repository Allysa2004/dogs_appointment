<?php
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

$today = date('Y-m-d');
$start_of_week = date('Y-m-d', strtotime('sunday last week'));
$start_of_month = date('Y-m-01');

// Daily Income (Only Paid)
$sql_daily = "SELECT SUM(price) AS total_income FROM appointments WHERE DATE(appointment_datetime) = ? AND payment_status = 'Paid'";
$stmt_daily = $conn->prepare($sql_daily);
$stmt_daily->bind_param("s", $today);
$stmt_daily->execute();
$result_daily = $stmt_daily->get_result();
$daily_income = $result_daily->fetch_assoc()['total_income'] ?? 0;

// Weekly Income (Only Paid)
$sql_weekly = "SELECT SUM(price) AS total_income FROM appointments WHERE DATE(appointment_datetime) BETWEEN ? AND ? AND payment_status = 'Paid'";
$stmt_weekly = $conn->prepare($sql_weekly);
$stmt_weekly->bind_param("ss", $start_of_week, $today);
$stmt_weekly->execute();
$result_weekly = $stmt_weekly->get_result();
$weekly_income = $result_weekly->fetch_assoc()['total_income'] ?? 0;

// Monthly Income (Only Paid)
$sql_monthly = "SELECT SUM(price) AS total_income FROM appointments WHERE DATE(appointment_datetime) BETWEEN ? AND ? AND payment_status = 'Paid'";
$stmt_monthly = $conn->prepare($sql_monthly);
$stmt_monthly->bind_param("ss", $start_of_month, $today);
$stmt_monthly->execute();
$result_monthly = $stmt_monthly->get_result();
$monthly_income = $result_monthly->fetch_assoc()['total_income'] ?? 0;

// Today's Appointments (All, regardless of status)
$sql_today_appointments = "SELECT * FROM appointments WHERE DATE(appointment_datetime) = ?";
$stmt_today_appointments = $conn->prepare($sql_today_appointments);
$stmt_today_appointments->bind_param("s", $today);
$stmt_today_appointments->execute();
$result_today_appointments = $stmt_today_appointments->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            display: flex;
            background-color: #eef2f5;
        }

        .sidebar {
            width: 240px;
            background-color:rgb(41, 112, 187);
            min-height: 100vh;
            padding: 25px 20px;
            color: white;
            position: fixed;
        }

        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: #cbd5e1;
            text-decoration: none;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .sidebar a i {
            margin-right: 10px;
            width: 20px;
        }

        .sidebar a:hover {
            background-color: #334155;
            color: #ffffff;
        }

        .dashboard {
            margin-left: 260px;
            padding: 30px;
            flex-grow: 1;
        }

        .dashboard h2 {
            font-size: 26px;
            margin-bottom: 25px;
            color: #0f172a;
        }

        .stats {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 35px;
        }

        .stat-card {
            flex: 1;
            min-width: 200px;
            background-color: #2563eb;
            color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .stat-card h3 {
            font-size: 15px;
            color: #dbeafe;
            margin-bottom: 5px;
        }

        .stat-card p {
            font-size: 22px;
            font-weight: bold;
        }

        .appointments h4 {
            margin-bottom: 15px;
            font-size: 18px;
            color: #2563eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
            text-align: left;
        }

        th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #334155;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tr:hover {
            background-color: #f1f5f9;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            .dashboard {
                margin-left: 0;
                padding: 20px;
            }
            .stats {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>🐾 Admin Panel</h2>
    <a href="admin_dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="appointments.php"><i class="fas fa-calendar-check"></i> View Appointments</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="dashboard">
    <h2>📊 Admin Dashboard</h2>

    <div class="stats">
        <div class="stat-card">
            <h3>Daily Income</h3>
            <p>₱<?= number_format($daily_income, 2) ?></p>
        </div>
        <div class="stat-card">
            <h3>Weekly Income</h3>
            <p>₱<?= number_format($weekly_income, 2) ?></p>
        </div>
        <div class="stat-card">
            <h3>Monthly Income</h3>
            <p>₱<?= number_format($monthly_income, 2) ?></p>
        </div>
    </div>

    <div class="appointments">
        <h4>📅 Appointments Today</h4>
        <table>
            <thead>
                <tr>
                    <th>Owner Name</th>
                    <th>Dog Name</th>
                    <th>Service Type</th>
                    <th>Appointment Date/Time</th>
                    <th>Price</th>
                    <th>Payment Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_today_appointments->num_rows > 0): ?>
                    <?php while ($appointment = $result_today_appointments->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($appointment['owner_name']) ?></td>
                            <td><?= htmlspecialchars($appointment['dog_name']) ?></td>
                            <td><?= htmlspecialchars($appointment['service_type']) ?></td>
                            <td><?= date('M d, Y - g:i A', strtotime($appointment['appointment_datetime'])) ?></td>
                            <td>₱<?= number_format($appointment['price'], 2) ?></td>
                            <td><?= $appointment['payment_status'] === 'Paid' ? '✅ Paid' : '⌛ Pending' ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center;">No appointments today.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
