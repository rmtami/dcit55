<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'admin.php'; 
$admin = new Admin();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($admin->login($_POST['username'], $_POST['password'])) {
        header("Location: dashboard.php");
        exit; 
    } else {
        $error = "Invalid login!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>Admin Login</h2>

        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <input name="username" required placeholder="Username" type="text">
            <input name="password" required placeholder="Password" type="password">
            <button type="submit">Login</button>
        </form>
    </div>

</body>
</html>
