<?php
session_start();
include('./include/config.php');

$error = "";
$success = "";

/* ---------------- LOGIN ---------------- */
if (isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['password'])) {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $row['role'];

                if ($row['role'] == 'super_admin') {
                    header("Location: ./admin/admin.php");
                } elseif ($row['role'] == 'admin') {
                    header("Location: ./admin/admin.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No account found with that email.";
        }
    }
}

/* ---------------- REGISTER ---------------- */
if (isset($_POST['register'])) {
    $regname= trim($_POST['reg_name'] ?? '');
    $regEmail = trim($_POST['reg_email'] ?? '');
    $regPassword = trim($_POST['reg_password'] ?? '');

    $errors = [];

    // Email validation
    if (empty($regEmail)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($regEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Password validation
    if (empty($regPassword)) {
        $errors[] = "Password is required.";
    } elseif (strlen($regPassword) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if (empty($errors)) {
        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $regEmail);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email already registered. Please login.";
        } else {
            $hashedPassword = password_hash($regPassword, PASSWORD_DEFAULT);
            $defaultRole = "user"; // default role

            $sql = "INSERT INTO users (name,email, password, role) VALUES (?,?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss",$regname, $regEmail, $hashedPassword, $defaultRole);

            if ($stmt->execute()) {
                $success = "Registration successful! Please log in.";
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
    } else {
        $error = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Midnight Vogue | Login</title>
    <style>
        :root {
            --bg: #000000;
            --card: #0f0f11;
            --muted: #9ca3af;
            --accent: #7C3AED;
        }
        * { box-sizing: border-box }
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background: #000;
            color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 420px;
            width: 100%;
            background: #111;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6)
        }
        h1 { margin: 0 0 8px; font-size: 22px }
        p { margin: 0 0 18px; color: var(--muted); font-size: 14px }
        .error { color: #f87171; margin-bottom: 14px; font-size: 14px }
        .success { color: #4ade80; margin-bottom: 14px; font-size: 14px }
        label { font-size: 13px; color: var(--muted); display: block; margin-bottom: 6px }
        .input {
            width: 100%; padding: 12px; border-radius: 8px;
            border: 1px solid #222; background: #1a1a1a; color: #fff;
            margin-bottom: 14px
        }
        .input:focus { border-color: var(--accent); outline: none }
        .btn {
            width: 100%; padding: 12px; border: none; border-radius: 8px;
            background: var(--accent); color: #fff; font-weight: 600;
            cursor: pointer; margin-top: 6px
        }
        .btn:hover { opacity: 0.9 }
        .toggle-link {
            display: block; margin-top: 14px; color: var(--accent);
            font-size: 14px; text-align: center; cursor: pointer
        }
        .footer-note {
            margin-top: 12px; text-align: center; color: var(--muted); font-size: 12px
        }
    </style>
</head>
<body>
    <main class="container">
        <h1 id="formTitle">Welcome Back</h1>
        <p id="formSubtitle">Sign in to continue to Midnight Vogue</p>

        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="post" id="loginForm">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="input" placeholder="you@domain.com" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="input" placeholder="Enter your password" required>

            <button type="submit" class="btn" name="login">Sign In</button>
        </form>

        <!-- Register Form -->
        <form method="post" id="registerForm" style="display:none">
            <label for="reg_name">Name</label>
            <input type="text" name="reg_name" id="reg_name" class="input" placeholder="John Doe" required>
            <label for="reg_email">Email</label>
            <input type="email" name="reg_email" id="reg_email" class="input" placeholder="you@domain.com" required>

            <label for="reg_password">Password</label>
            <input type="password" name="reg_password" id="reg_password" class="input" placeholder="Create a password" required>

            <button type="submit" name="register" class="btn">Register</button>
        </form>

        <span class="toggle-link" id="toggleForm">Don't have an account? Register</span>

        <p class="footer-note">
            By continuing you agree to our
            <a href="#" style="color:var(--accent);text-decoration:none">Terms</a> and
            <a href="#" style="color:var(--accent);text-decoration:none">Privacy</a>.
        </p>
        <p class="text-center"><a href="index.php" style="color:var(--accent);text-decoration:none" class="">Guest</a></p>
    </main>

    <script>
        const toggle = document.getElementById('toggleForm');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const title = document.getElementById('formTitle');
        const subtitle = document.getElementById('formSubtitle');

        toggle.addEventListener('click', () => {
            if (loginForm.style.display === 'none') {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
                title.textContent = 'Welcome Back';
                subtitle.textContent = 'Sign in to continue to Midnight Vogue';
                toggle.textContent = "Don't have an account? Register";
            } else {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                title.textContent = 'Create Account';
                subtitle.textContent = 'Register to get started with Midnight Vogue';
                toggle.textContent = "Already have an account? Login";
            }
        });
    </script>
</body>
</html>
