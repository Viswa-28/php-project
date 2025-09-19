<?php
session_start();
include('./include/config.php');

$error = "";
$success = "";
$formToShow = "login"; // default form to show

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
            if (password_verify($password, $row['password'])) { // hashed check
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $row['role'];

                // Redirect based on role
                if ($row['role'] == 'super_admin' || $row['role'] == 'admin') {
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
    $formToShow = "register"; // stay on register form if error
    $regname = trim($_POST['reg_name'] ?? '');
    $regEmail = trim($_POST['reg_email'] ?? '');
    $regPassword = trim($_POST['reg_password'] ?? '');
    $regConfirm = trim($_POST['reg_confirm'] ?? '');

    $errors = [];

    // Name validation
    if (empty($regname)) $errors[] = "Name is required.";

    // Email validation
    if (empty($regEmail)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($regEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Password validation
    if (empty($regPassword) || empty($regConfirm)) {
        $errors[] = "Password and Confirm Password are required.";
    } elseif (strlen($regPassword) != 6) {
        $errors[] = "Password must be exactly 6 characters.";
    } elseif ($regPassword !== $regConfirm) {
        $errors[] = "Password and Confirm Password do not match.";
    }

    if (empty($errors)) {
        // Check if email exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $regEmail);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email already registered. Please login.";
        } else {
            $hashedPassword = password_hash($regPassword, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)";
            $role = "user";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $regname, $regEmail, $hashedPassword, $role);

            if ($stmt->execute()) {
                $success = "Registration successful! Please log in.";
                $formToShow = "login"; // switch to login after success
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
<title>Midnight Vogue | Login/Register</title>
<style>
:root { --bg:#000; --card:#0f0f11; --muted:#9ca3af; --accent:#7C3AED; }
*{box-sizing:border-box;}
body {margin:0; font-family: 'Montserrat',sans-serif; background:var(--bg); color:#fff; display:flex; align-items:center; justify-content:center; height:100vh; padding:20px;}
.container {max-width:420px; width:100%; background:var(--card); border-radius:14px; padding:28px; box-shadow:0 8px 30px rgba(0,0,0,0.6);}
h1{margin:0 0 8px;font-size:22px;}
p{margin:0 0 18px;color:var(--muted);font-size:14px;}
.error{color:#f87171;margin-bottom:14px;font-size:14px;}
.success{color:#4ade80;margin-bottom:14px;font-size:14px;}
label{font-size:13px;color:var(--muted);display:block;margin-bottom:6px;}
.input{width:100%;padding:12px;border-radius:8px;border:1px solid #222;background:#1a1a1a;color:#fff;margin-bottom:14px;}
.input:focus{border-color:var(--accent);outline:none;}
.btn{width:100%;padding:12px;border:none;border-radius:8px;background:var(--accent);color:#fff;font-weight:600;cursor:pointer;margin-top:6px;}
.btn:hover{opacity:0.9;}
.toggle-link{display:block;margin-top:14px;color:var(--accent);font-size:14px;text-align:center;cursor:pointer;}
.footer-note{margin-top:12px;text-align:center;color:var(--muted);font-size:12px;}
</style>
</head>
<body>
<main class="container">
<h1 id="formTitle"><?= $formToShow=='login'?'Welcome Back':'Create Account' ?></h1>
<p id="formSubtitle"><?= $formToShow=='login'?'Sign in to continue':'Register to get started' ?></p>

<?php if($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
<?php if($success): ?><div class="success"><?= $success ?></div><?php endif; ?>

<form method="post" id="loginForm" style="<?= $formToShow=='login'?'':'display:none' ?>">
    <label>Email</label>
    <input type="email" name="email" class="input" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    <label>Password</label>
    <input type="password" name="password" class="input">
    <button type="submit" name="login" class="btn">Sign In</button>
</form>

<form method="post" id="registerForm" style="<?= $formToShow=='register'?'':'display:none' ?>">
    <label>Name</label>
    <input type="text" name="reg_name" class="input" value="<?= htmlspecialchars($regname ?? '') ?>">
    <label>Email</label>
    <input type="email" name="reg_email" class="input" value="<?= htmlspecialchars($regEmail ?? '') ?>">
    <label>Password</label>
    <input type="password" name="reg_password" class="input" >
    <label>Confirm Password</label>
    <input type="password" name="reg_confirm" class="input" >
    <button type="submit" name="register" class="btn">Register</button>
</form>

<span class="toggle-link" id="toggleForm"><?= $formToShow=='login'?"Don't have an account? Register":"Already have an account? Login" ?></span>

<p class="footer-note">
By continuing you agree to our
<a href="#" style="color:var(--accent);text-decoration:none">Terms</a> and
<a href="#" style="color:var(--accent);text-decoration:none">Privacy</a>.
</p>
<p class="text-center"><a href="index.php" style="color:var(--accent);text-decoration:none">Guest</a></p>
</main>

<script>
const toggle = document.getElementById('toggleForm');
const loginForm = document.getElementById('loginForm');
const registerForm = document.getElementById('registerForm');
const title = document.getElementById('formTitle');
const subtitle = document.getElementById('formSubtitle');

toggle.addEventListener('click', ()=>{
    if(loginForm.style.display==='none'){
        loginForm.style.display='block';
        registerForm.style.display='none';
        title.textContent='Welcome Back';
        subtitle.textContent='Sign in to continue';
        toggle.textContent="Don't have an account? Register";
    }else{
        loginForm.style.display='none';
        registerForm.style.display='block';
        title.textContent='Create Account';
        subtitle.textContent='Register to get started';
        toggle.textContent="Already have an account? Login";
    }
});
</script>
</body>
</html>
