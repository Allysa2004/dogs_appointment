<?php 
require 'db.php';

// Redirect to login if user is not logged in or not a customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: #f4f6fa;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #2e59d9, #1e3c72);
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 30px 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.08);
        }

        .logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 35px;
            
        }

        .logo img {
            max-width: 180px;
            height: auto;
             border-radius: 100;
             
           
        }

        .sidebar a {
            text-decoration: none;
            color: #fff;
            padding: 12px 15px;
            margin-bottom: 12px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateX(5px);
        }

        .main-content {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .avatar {
            background: #2e59d9;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .greeting h2 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .greeting p {
            color: #7f8fa6;
            font-size: 14px;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            flex: 1 1 300px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
        }

        .card h3 {
            margin-bottom: 15px;
            color: #34495e;
            font-size: 20px;
        }

        .card p {
            color: #6c757d;
            font-size: 15px;
        }

        .button {
            margin-top: 20px;
            display: inline-block;
            background: #2e59d9;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            transition: background 0.3s ease;
        }

        .button:hover {
            background: #1c3879;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
<div class="sidebar">
    <h3>🐾 Customer Panel</h3><br><br>
    <a href="customer_dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="customer_appointment.php"><i class="fas fa-calendar-plus"></i> Book Appointment</a>
    <a href="appointments_history.php"><i class="fas fa-history"></i> My Appointments</a>
    <a href="about_us.php" class="active"><i class="fas fa-info-circle"></i> About Us</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

    <div class="main-content">
        <div class="header">
            <div class="greeting">
                <h2>Hello, <?= htmlspecialchars($_SESSION['username']) ?> 👋</h2>
                <p>Welcome back to your dashboard</p>
            </div>
            <div class="avatar">
                <?= strtoupper(substr($_SESSION['username'], 0, 1)) ?>
            </div>
        </div>

        <div class="card-container">
            <div class="card">
                <h3>📅 Upcoming Appointments</h3>
                <p>No upcoming appointments yet.</p>
                <a href="customer_appointment.php" class="button">Book Now</a>
            </div>

            <div class="card">
                <h3>📈 Appointment History</h3>
                <p>Check your past appointments and service details.</p>
                <a href="appointments_history.php" class="button">View History</a>
            </div>
        </div>
    </div>

</body>
</html>
