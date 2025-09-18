<?php
// session_start();
include('../include/config.php');
include('../include/header.php');

// Example: Set user role dynamically from session
// Make sure this is set during login
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'Admin'; // default to Admin
$server = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg" style="background-color: #000;">
    <div class="container-fluid">
        <!-- Logo / Brand -->
        <a class="navbar-brand text-white fw-bold" href="#">Midnight Vogue</a>

        <!-- Hamburger for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" 
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-3">
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo ($server == 'admin.php') ? 'active' : ''; ?>" href="./admin.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo ($server == 'stocks.php') ? 'active' : ''; ?>" href="./stocks.php">Stocks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo ($server == 'users.php') ? 'active' : ''; ?>" href="./users.php">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo ($server == 'enquiry.php') ? 'active' : ''; ?>" href="./enquiry.php">Enquiry</a>
                </li>
            </ul>

            <!-- Role Badge -->
            <div class="d-flex align-items-center ms-lg-3 px-3 py-1 rounded" 
     style="background-color: #7C3AED; color: #fff; font-weight: 600; gap: 15px;">
     
    <!-- User Role -->
    <span><?php echo ucfirst(str_replace('', ' ', $user_role)); ?></span>

    <!-- Logout Link -->
    <a href="admin-logout.php" class="btn btn-sm btn-light fw-bold" 
       style="color:#7C3AED; background:#fff; border-radius:8px; padding:4px 12px;">
        Logout
    </a>
</div>

        </div>
    </div>
</nav>

<style>
    .nav-link.active {
    color: #7C3AED !important;
    font-weight: 600;
}
.navbar-nav .nav-link {
    transition: color 0.3s;
}
.navbar-nav .nav-link:hover {
    color: #7C3AED !important;
}
</style>



<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
