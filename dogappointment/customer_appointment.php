<?php
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header('Location: login.php');
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $owner_name = htmlspecialchars($_POST['owner_name']);
    $dog_name = htmlspecialchars($_POST['dog_name']);
    $services = $_POST['service_type'] ?? [];
    $appointment_datetime = $_POST['appointment_datetime'];
    $price = $_POST['price'];
    $dog_size = $_POST['dog_size'] ?? null;

    if (in_array('Pet Grooming', $services) && $dog_size) {
        $services = array_map(function ($service) use ($dog_size) {
            return $service == 'Pet Grooming' ? "Pet Grooming ($dog_size)" : $service;
        }, $services);
    }

    if (!empty($owner_name) && !empty($dog_name) && !empty($services) && !empty($appointment_datetime)) {
        $services_str = implode(", ", $services);
        $stmt = $conn->prepare("INSERT INTO appointments (owner_name, dog_name, service_type, appointment_datetime, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssd", $owner_name, $dog_name, $services_str, $appointment_datetime, $price);

        if ($stmt->execute()) {
            header("Location: customer_dashboard.php");
            exit;
        } else {
            $message = "Failed to book the appointment. Please try again.";
        }

        $stmt->close();
    } else {
        $message = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment | Happy Paws</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f2f4f8;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 650px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 100px;
        }

        h2 {
            text-align: center;
            color: #34495e;
            margin-bottom: 25px;
        }

        .error {
            text-align: center;
            color: red;
            font-weight: 500;
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 500;
            color: #333;
        }

        input[type="text"],
        input[type="datetime-local"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            margin-top: 5px;
            background-color: #fdfdfd;
            transition: 0.3s;
        }

        input:focus,
        select:focus {
            border-color: #007BFF;
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.2);
        }

        .multi-service {
            margin-top: 10px;
        }

        .multi-service label {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
            font-weight: normal;
        }

        .multi-service input[type="checkbox"] {
            transform: scale(1.2);
        }

        #dogSizeContainer {
            display: none;
            margin-top: 10px;
            padding-left: 10px;
        }

        .submit-btn {
            width: 100%;
            background-color: #007BFF;
            color: #fff;
            padding: 14px;
            margin-top: 25px;
            font-size: 17px;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
        }

        .form-footer a {
            color: #007BFF;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRirV6SYobBs74VeVXHSSbi17drYs0xVOdQxw&s" alt="Happy Paws Logo">
    </div>

    <h2>Book an Appointment</h2>

    <?php if ($message): ?>
        <div class="error"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Owner's Name:</label>
        <input type="text" name="owner_name" value="<?= htmlspecialchars($_SESSION['username']) ?>" required>

        <label>Dog's Name:</label>
        <input type="text" name="dog_name" required>

        <label>Services:</label>
        <div class="multi-service">
            <label>
                <input type="checkbox" name="service_type[]" value="Pet Grooming" id="petGroomingCheckbox"> Pet Grooming (Bath & Blower)
            </label>

            <div id="dogSizeContainer">
                <label for="dog_size">Dog Size:</label>
                <select name="dog_size" id="dogSize">
                    <option value="">-- Select Size --</option>
                    <option value="Small" data-price="400">Small (₱400)</option>
                    <option value="Medium" data-price="500">Medium (₱500)</option>
                    <option value="Large" data-price="900">Large (₱900)</option>
                </select>
            </div>

            <label>
                <input type="checkbox" name="service_type[]" value="Ear Cleaning" data-price="100"> Ear Cleaning (₱100)
            </label>
            <label>
                <input type="checkbox" name="service_type[]" value="Hair Cut" data-price="100"> Nail Cutting (₱100)
            </label>
        </div>

        <label>Appointment Date & Time:</label>
        <input type="datetime-local" name="appointment_datetime" required>

        <label>Total Price (₱):</label>
        <input type="number" name="price" id="totalPrice" readonly required>

        <button type="submit" class="submit-btn">Book Now</button>
    </form>

    <div class="form-footer">
        <a href="customer_dashboard.php">← Back to Dashboard</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const petGroomingCheckbox = document.getElementById('petGroomingCheckbox');
    const dogSizeContainer = document.getElementById('dogSizeContainer');
    const dogSizeSelect = document.getElementById('dogSize');
    const totalPriceInput = document.getElementById('totalPrice');
    const checkboxes = document.querySelectorAll('input[type="checkbox"][data-price]');

    function updatePrice() {
        let total = 0;

        checkboxes.forEach(cb => {
            if (cb.checked) {
                total += parseFloat(cb.dataset.price);
            }
        });

        if (petGroomingCheckbox.checked) {
            const selectedSize = dogSizeSelect.options[dogSizeSelect.selectedIndex];
            const groomingPrice = parseFloat(selectedSize.dataset.price || 0);
            total += groomingPrice;
        }

        totalPriceInput.value = total.toFixed(2);
    }

    petGroomingCheckbox.addEventListener('change', function () {
        dogSizeContainer.style.display = this.checked ? 'block' : 'none';
        updatePrice();
    });

    dogSizeSelect.addEventListener('change', updatePrice);
    checkboxes.forEach(cb => cb.addEventListener('change', updatePrice));

    updatePrice(); // Initialize
});
</script>

</body>
</html>
