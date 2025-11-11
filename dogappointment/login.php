<?php
require 'db.php'; // Ensure $conn is a valid mysqli connection

$error = '';
$username = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            $error = "Both fields are required.";
        } else {
            $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
            if ($stmt) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($user_id, $hashed_password, $role);
                    $stmt->fetch();

                    if (password_verify($password, $hashed_password)) {
                        session_start();
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['username'] = $username;
                        $_SESSION['role'] = $role;

                        if ($role === 'admin') {
                            header("Location: admin_dashboard.php");
                        } else {
                            header("Location: customer_dashboard.php");
                        }
                        exit;
                    } else {
                        $error = "Invalid password.";
                    }
                } else {
                    $error = "Username not found.";
                }
                $stmt->close();
            } else {
                $error = "Database error (select statement).";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Dog Appointment System</title>
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

        .login-box {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 16px;
            padding: 40px 30px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            max-width: 370px;
            width: 90%;
            backdrop-filter: blur(10px);
            position: relative;
        }

        .login-box h1 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 20px;
            color: #333;
        }

        .login-box h2 {
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
            .login-box {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="logo">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRirV6SYobBs74VeVXHSSbi17drYs0xVOdQxw&s" alt="Dog Grooming Logo">
    </div>
    <h1>🐾 Happy Paws Appointment</h1>
    <h2>Login</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <div class="form-group input-icon">
            <label for="username">Username</label>
            <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input name="username" type="text" id="username" value="<?= htmlspecialchars($username) ?>" required>
            </div>
        </div>

        <div class="form-group input-icon">
            <label for="password">Password</label>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input name="password" type="password" id="password" required>
                <i class="fas fa-eye toggle-password" id="togglePassword"></i>
            </div>
        </div>

        <button type="submit">Login</button>
    </form>

    <div class="bottom-text">
        Don't have an account? <a href="signup.php">Sign Up</a>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const passwordField = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
</script>

</body>
</html>
