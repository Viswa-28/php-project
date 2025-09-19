<?php
session_start();
include('../include/config.php');


// Allow only super_admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    header("Location: ../login.php");
    exit;
}

$error = $success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = trim($_POST['username']);
    $password   = trim($_POST['password']);
    $confirmPwd = trim($_POST['confirm_password']);
    $role       = 'admin';

    // Basic validation
    if (empty($username) || empty($password) || empty($confirmPwd)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirmPwd) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Username already exists!";
        } else {
            // Insert new admin (securely hash password)
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashedPassword, $role);

            if ($stmt->execute()) {
                $success = "Admin created successfully!";
                header("Location: users.php");
                exit;
            } else {
                $error = "Error creating admin.";
            }
            $stmt->close();
        }
        $check->close();
    }
}
include('../include/header.php');
include('./admin-nav.php');
?>

<div class="container mt-5">
    <h2 class="text-center text-white mb-4">Create New Admin</h2>

    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>
    <?php if($success): ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php endif; ?>

    <form method="POST" class="bg-dark text-white p-4 rounded shadow-lg" style="max-width:500px; margin:auto;">
        <div class="mb-3">
            <label>Username (Email)</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required minlength="6">
        </div>

        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required minlength="6">
        </div>

        <button type="submit" class="btn btn-primary">Create Admin</button>
        <a href="admin.php" class="btn btn-secondary">Back</a>
    </form>
</div>
