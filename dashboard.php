<?php
require_once 'admin.php';
$admin = new Admin();

if (!$admin->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$message = "";

if (isset($_POST['create'])) {
    $result = $admin->create($_POST['new_username'], $_POST['new_password'], $_POST['role_id']);
    if ($result === "exists") {
        $message = "<p style='color:red; text-align:center;'>❌ Username already exists!</p>";
    } elseif ($result) {
        $message = "<p style='color:green; text-align:center;'>✅ Admin added successfully!</p>";
    }
}

if (isset($_POST['update'])) {
    $admin->update($_POST['id'], $_POST['username'], $_POST['password'], $_POST['role_id']);
}

if (isset($_GET['delete'])) {
    $admin->delete($_GET['delete']);
}

$admins = $admin->read();
$roles = $admin->getRoles();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f5f7fa; margin: 0; padding: 0; }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        h2, h3 { text-align: center; color: #333; }
        .logout {
            float: right;
            margin-top: -45px;
            text-decoration: none;
            color: #e74c3c;
            font-weight: bold;
        }
        form { margin-bottom: 20px; }
        input[type="text"],
        input[type="password"],
        select {
            padding: 10px;
            margin: 5px 0;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        button:hover { background-color: #0056b3; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .action-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .action-buttons a {
            color: #e74c3c;
            font-weight: bold;
            text-decoration: none;
        }
        .action-buttons a:hover { text-decoration: underline; }
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .toggle-eye {
            cursor: pointer;
            font-size: 18px;
            user-select: none;
            color: #007bff;
            padding-left: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Welcome Admin: <?= htmlspecialchars($_SESSION['admin']) ?></h2>
    <a class="logout" href="logout.php">Logout</a>

    <?= $message ?>

    <h3>Add New Admin</h3>
    <form method="POST">
        <input type="text" name="new_username" placeholder="New Username" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <select name="role_id" required>
            <option value="">Select Role</option>
            <?php foreach ($roles as $role): ?>
                <option value="<?= $role['role_id'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="create">Add Admin</button>
    </form>

    <h3>Admin List</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Username & Password</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($admins as $row): ?>
            <tr>
                <form method="POST">
                    <td>
                        <?= htmlspecialchars($row['id']) ?>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                    </td>
                    <td>
                        <input type="text" name="username" value="<?= htmlspecialchars($row['username']) ?>" required><br>
                        <div class="password-wrapper">
                            <input type="password" name="password" placeholder="New Password" class="password-input" id="pass-<?= $row['id'] ?>">
                            <span class="toggle-eye" onclick="togglePassword('pass-<?= $row['id'] ?>', this)">👁️</span>
                        </div>
                    </td>
                    <td>
                        <select name="role_id" required>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['role_id'] ?>" <?= $row['role_id'] == $role['role_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($role['role_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="action-buttons">
                        <button type="submit" name="update">Update</button>
                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this admin?')">Delete</a>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<script>
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.textContent = "🙈";
        } else {
            input.type = "password";
            icon.textContent = "👁️";
        }
    }
</script>
</body>
</html>