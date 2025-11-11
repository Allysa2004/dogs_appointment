<?php
require 'db.php';

$error = '';
$username = '';
$role = 'customer';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'customer';

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,}$/', $username)) {
        $error = "Username must be at least 3 characters and contain only letters, numbers, or underscores.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $hashed_password, $role);
            if ($insert->execute()) {
                header("Location: login.php?status=success");
                exit;
            } else {
                $error = "Something went wrong. Try again.";
            }
            $insert->close();
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up | Dog Appointment System</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(to right, #dfe9f3, #fefefe);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .signup-box {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 16px;
            padding: 40px 30px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            max-width: 380px;
            width: 90%;
            backdrop-filter: blur(10px);
        }

        .signup-box h1 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 10px;
            color: #333;
        }

        .signup-box h2 {
            text-align: center;
            font-size: 18px;
            color: #0077cc;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 6px;
            color: #555;
        }
        .logo {
            margin-bottom: 30px;
            text-align: center;
        }

        .logo img {
            max-width: 140px;
            height: auto;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        .input-wrapper .fa-user,
        .input-wrapper .fa-lock {
            left: 12px;
        }

        .input-wrapper .toggle-password {
            right: 12px;
            cursor: pointer;
        }

        .input-wrapper input {
            width: 80%;
            padding: 10px 14px;
            padding-left: 36px;
            padding-right: 36px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #0077cc;
            background-color: #f4faff;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #0077cc;
            color: white;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #005fa3;
        }

        .error {
            background-color: #ffe3e3;
            color: #b40000;
            padding: 10px;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .bottom-text {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #333;
        }

        .bottom-text a {
            color: #0077cc;
            text-decoration: none;
            font-weight: 500;
        }

        .bottom-text a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 420px) {
            .signup-box {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

<div class="signup-box">
    <div class="logo">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRirV6SYobBs74VeVXHSSbi17drYs0xVOdQxw&s" alt="Dog Grooming Logo">
    </div>
    <h1>🐾 Happy Paws Appointment</h1>
    <h2>Create an Account</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <div class="form-group">
            <label for="username">Username</label>
            <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input name="username" type="text" id="username" value="<?= htmlspecialchars($username) ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input name="password" type="password" id="password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
            </div>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input name="confirm_password" type="password" id="confirm_password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm_password', this)"></i>
            </div>
        </div>

        <button type="submit">Sign Up</button>
    </form>

    <div class="bottom-text">
        Already have an account? <a href="login.php">Login here</a>
    </div>
</div>

<script>
    function togglePassword(id, icon) {
        const field = document.getElementById(id);
        const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
        field.setAttribute('type', type);
        icon.classList.toggle('fa-eye-slash');
    }
</script>

</body>
</html>
