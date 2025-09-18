<?php
include('config.php');
include('header.php');

if(isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $sql = "SELECT * FROM users WHERE id = '$user_id'";
  $result = $conn->query($sql);
  $user = $result->fetch_assoc();
  $email = $user['email'];
  $name= $user['name'];

  // $username = $user['username'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Navbar with User Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    #profileDropdown {
      display: none;
      position: absolute;
      right: 10px;
      top: 60px;
      background-color: white;
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 5px;
      z-index: 1000;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-black sticky-top">
  <div class="container w-100">
    <a class="navbar-brand" href="#">Navbar</a>

    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link text-white fs-5" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white fs-5" href="#new arrivals">New arrivals</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white fs-5" href="#catogory">Categories</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white fs-5" href="#contact">Contact</a>
        </li>
      </ul>

      <!-- Authentication/User Area -->
      <?php if (isset($_SESSION['user_id'])): ?>
        <div class="position-relative">
          <button class="btn text-white primary  " id="profileBtn"><i class="bi bi-person"><?php echo $name; ?></i></button>
          <div id="profileDropdown">
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
          </div>
        </div>
      <?php else: ?>
        <div class="d-flex align-items-center">
          <a href="login.php" class=" primary me-2">Login</a>
         
        </div>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- Script to handle dropdown toggle -->
<script>
  const profileBtn = document.getElementById('profileBtn');
  const dropdown = document.getElementById('profileDropdown');

  if (profileBtn && dropdown) {
    profileBtn.addEventListener('click', function (e) {
      dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    window.addEventListener('click', function (e) {
      if (!dropdown.contains(e.target) && !profileBtn.contains(e.target)) {
        dropdown.style.display = 'none';
      }
    });
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
