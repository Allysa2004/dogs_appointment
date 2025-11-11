<?php
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us | Happy Paws</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #2e59d9, #1e3c72);
            color: #fff;
            padding: 30px 20px;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar h3 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 40px;
        }

        .sidebar a {
            display: block;
            text-decoration: none;
            color: #fff;
            padding: 12px 15px;
            margin-bottom: 12px;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.25);
            transform: translateX(5px);
        }

        .container {
            margin-left: 250px;
            padding: 40px;
            flex: 1;
        }

        h2 {
            color: #2e59d9;
            font-size: 32px;
            margin-bottom: 25px;
        }

        .about-content {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            color: #333;
        }

        .about-content h3 {
            color: #1e3c72;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .about-content p {
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .info-box {
            background: #eaf2ff;
            border-left: 5px solid #2e59d9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .info-box h4 {
            margin-bottom: 10px;
            color: #1c3879;
            font-size: 18px;
        }

        ul {
            padding-left: 20px;
        }

        ul li {
            margin-bottom: 10px;
            font-size: 15px;
        }

        footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #999;
            margin-left: 250px;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .container {
                margin-left: 0;
                padding: 20px;
            }

            footer {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h3>🐾 Customer Panel</h3>
    <a href="customer_dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="customer_appointment.php"><i class="fas fa-calendar-plus"></i> Book Appointment</a>
    <a href="appointments_history.php"><i class="fas fa-history"></i> My Appointments</a>
    <a href="about_us.php" class="active"><i class="fas fa-info-circle"></i> About Us</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="container">
    <h2>About Us</h2>
    <div class="about-content">
        <h3>Welcome to Happy Paws 🐶</h3>
        <p>
            At <strong>Happy Paws</strong>, we treat pets like family. Our appointment system is built to make scheduling fast and convenient for pet owners, while ensuring smooth operations for our dedicated team.
        </p>

        <div class="info-box">
            <h4>📍 Our Address</h4>
            <p>
                Happy Paws<br>
                Inventor Street Corner, E. Reyes Avenue, Estancia, Iloilo<br>
                Open: Mon - Sat, 8:00 AM to 5:00 PM<br>
                Mobile Number: 0999 454 3229
            </p>
        </div>

        <div class="info-box">
            <h4>💼 Services We Offer</h4>
            <ul>
                <li>🐾 Pet Grooming (Bath & Blower)</li>
                <li>🐾 Nail Cutting</li>
                <li>🐾 Ear Cleaning</li>
            </ul>
        </div>

        <p><strong>Thank you for choosing Happy Paws. We love your pets like our own! 🐾</strong></p>
    </div>
</div>

<!-- Footer -->
<footer>
    &copy; <?= date("Y") ?> Happy Paws. All rights reserved.
</footer>

</body>
</html>
