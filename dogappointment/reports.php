<?php
require 'db.php';

// Fetch all appointment records
$sql = "SELECT appointment_datetime, price FROM appointments";
$result = $conn->query($sql);

// Prepare report data
$dailyIncome = [];
$weeklyIncome = [];
$monthlyIncome = [];
$totalIncome = 0.00;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $price = isset($row['price']) ? (float)$row['price'] : 0.00;
        $date = $row['appointment_datetime'];  // Updated column name here

        // Extract day, week, and month from datetime
        $day = date("Y-m-d", strtotime($date));
        $week = date("o-\WW", strtotime($date));
        $month = date("Y-m", strtotime($date));

        $dailyIncome[$day] = ($dailyIncome[$day] ?? 0) + $price;
        $weeklyIncome[$week] = ($weeklyIncome[$week] ?? 0) + $price;
        $monthlyIncome[$month] = ($monthlyIncome[$month] ?? 0) + $price;

        $totalIncome += $price;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dog Appointment Income Report</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .summary {
            text-align: center;
            font-size: 22px;
            margin-bottom: 20px;
            color: #27ae60;
        }

        a {
            display: inline-block;
            margin: 10px auto 20px;
            text-align: center;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
        }

        .section {
            background: #fff;
            margin: 20px auto;
            padding: 10px 20px;
            width: 90%;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 20px;
            margin-bottom: 10px;
            text-align: center;
            color: #34495e;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px 14px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        td {
            color: #555;
        }

        .table-container {
            overflow-x: auto;
        }

        /* Responsive styling */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .section {
                width: 100%;
                padding: 10px;
            }

            th, td {
                font-size: 12px;
                padding: 8px;
            }

            .summary {
                font-size: 18px;
            }

            .section-title {
                font-size: 16px;
            }

            a {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <a href="admin_dashboard.php">← Back to Dashboard</a>

    <h1>Dog Appointment Income Report</h1>
    
    <div class="summary">
        Total Income: <strong>₱<?= number_format($totalIncome, 2) ?></strong>
    </div>

    <!-- Income per Day -->
    <div class="section">
        <div class="section-title">📅 Income Per Day</div>
        <div class="table-container">
            <table>
                <thead>
                    <tr><th>Date</th><th>Income</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($dailyIncome as $date => $income): ?>
                        <tr>
                            <td><?= htmlspecialchars($date) ?></td>
                            <td>₱<?= number_format($income, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Income per Week -->
    <div class="section">
        <div class="section-title">🗓️ Income Per Week</div>
        <div class="table-container">
            <table>
                <thead>
                    <tr><th>Week</th><th>Income</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($weeklyIncome as $week => $income): ?>
                        <tr>
                            <td><?= htmlspecialchars($week) ?></td>
                            <td>₱<?= number_format($income, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Income per Month -->
    <div class="section">
        <div class="section-title">📆 Income Per Month</div>
        <div class="table-container">
            <table>
                <thead>
                    <tr><th>Month</th><th>Income</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($monthlyIncome as $month => $income): ?>
                        <tr>
                            <td><?= htmlspecialchars($month) ?></td>
                            <td>₱<?= number_format($income, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
