<?php
include('./include/config.php');
include('./include/header.php');


session_start(); // start session at very top

//


include('./include/navbar.php');
?>

<section class="banner d-flex align-items-center" style="background-color: #000; min-height: 80vh;">
    <div class="container">
        <div class="row align-items-center justify-content-center flex-column-reverse flex-md-row text-center text-md-start">

            <!-- Text Content -->
            <div class="col-12 col-md-6 col-lg-7 mb-4 mb-md-0">
                <h1 class="heading text-white">Step into the Dark. Discover the Light of Fashion.</h1>
                <p class="para text-white mt-3">
                    Where luxury meets darkness, and style finds its true expression.
                </p>
                <a href="./all.php" class="primary mt-3 d-inline-block">Explore Collections</a>
            </div>

            <!-- Image Content -->
            <div class="col-12 col-md-6 col-lg-5 d-flex justify-content-center">
                <img src="./images/banner-img.png" class="img-fluid" alt="Fashion Banner">
            </div>

        </div>
    </div>
</section>


<section id="new arrivals" class="new mt-5 container">
    <h2 class="sub">
        New Arrivals
    </h2>
    <?php
    $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 3";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '<div class="row w-100">';
        while ($product = mysqli_fetch_assoc($result)) {
            $product_name = htmlspecialchars($product['name']);
            $product_image = $product['image'];
    ?>
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <a href="product.php?id=<?php echo $product['id']; ?>" class="text-decoration-none d-block">
                    <div class="card product-card" style="background-image: url('./uploads/<?php echo $product_image; ?>');">
                        <div class="card-name">
                            <?php echo $product_name; ?>
                        </div>
                    </div>
                </a>
            </div>


    <?php
        }
        echo '</div>';
    }
    ?>
</section>

<section id="category" class="category container mt-5 mb-5">

  <!-- Tabs -->
  <nav class="d-flex justify-content-center">
    <div class="nav nav-tabs gap-5 border-0" id="nav-tab" role="tablist">
      <button class="nav-link active" id="nav-men-tab" data-bs-toggle="tab" data-bs-target="#nav-men" type="button"
        role="tab" aria-controls="nav-men" aria-selected="true">Men</button>
      <button class="nav-link" id="nav-women-tab" data-bs-toggle="tab" data-bs-target="#nav-women" type="button"
        role="tab" aria-controls="nav-women" aria-selected="false">Women</button>
    </div>
  </nav>

  <!-- Tab Content -->
  <div class="tab-content mt-5" id="nav-tabContent">

    <!-- Men Section -->
    <div class="tab-pane fade show active" id="nav-men" role="tabpanel" aria-labelledby="nav-men-tab">
      <div class="row g-4">
        <?php
        $sql = "SELECT * FROM products WHERE category='Men'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo '
              <div class="col-12 col-md-6 col-lg-3 d-flex flex-column align-items-center">
                <a href="product.php?id=' . $row['id'] . '" class="text-decoration-none">
                  <img src="./uploads/' . $row['image'] . '" alt="' . $row['name'] . '" class="img-fluid rounded shadow-sm" style="height:300px; object-fit:cover;">
                  <p class="text-center text-white mt-2 fw-bold">' . $row['name'] . '</p>
                </a>
              </div>
            ';
          }
        } else {
          echo '<p class="text-center text-muted">No products found in Men category.</p>';
        }
        ?>
      </div>
    </div>

    <!-- Women Section -->
    <div class="tab-pane fade" id="nav-women" role="tabpanel" aria-labelledby="nav-women-tab">
      <div class="row g-4">
        <?php
        $sql = "SELECT * FROM products WHERE category='Women'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo '
              <div class="col-12 col-md-6 col-lg-3 d-flex flex-column align-items-center">
                <a href="product.php?id=' . $row['id'] . '" class="text-decoration-none">
                  <img src="./uploads/' . $row['image'] . '" alt="' . $row['name'] . '" class="img-fluid rounded shadow-sm" style="height:300px; object-fit:cover;">
                  <p class="text-center text-white mt-2 fw-bold">' . $row['name'] . '</p>
                </a>
              </div>
            ';
          }
        } else {
          echo '<p class="text-center text-muted">No products found in Women category.</p>';
        }
        ?>
      </div>
    </div>

  </div>
</section>


<?php
 // make sure $conn is defined here

$name = $email = $message = "";
$errors = [];
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Name validation: only letters and spaces, 3-15 chars
    if (empty($name)) {
        $errors['name'] = "Name is required.";
    } elseif (!preg_match("/^[a-zA-Z ]{3,15}$/", $name)) {
        $errors['name'] = "Name must be 3-15 letters and no numbers or special chars.";
    }

    // Email validation
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i", $email)) {
        $errors['email'] = "Enter a valid email address.";
    }

    // Message validation: at least 15 chars, letters, numbers, spaces only
    if (empty($message)) {
        $errors['message'] = "Message is required.";
    } elseif (strlen($message) < 15) {
        $errors['message'] = "Message must be at least 15 characters.";
    } elseif (!preg_match("/^[a-zA-Z0-9 ]+$/", $message)) {
        $errors['message'] = "Message can only contain letters, numbers, and spaces.";
    }

    // If no errors, insert into DB
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO contacts (name,email,message) VALUES (?,?,?)");
        $stmt->bind_param("sss", $name, $email, $message);
        if ($stmt->execute()) {
            $success = "✅ Thank you, $name! Your message has been sent.";
            $name = $email = $message = ""; // clear form
        } else {
            $success = "❌ Something went wrong. Please try again.";
        }
    }
}
?>

<section id="contact" class="contact">
    <form id="contactForm" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <h2 class="section-title">Contact</h2>

        <input type="text" class="username" name="name" placeholder="Username" value="<?= htmlspecialchars($name) ?>">
        <span class="error"><?= $errors['name'] ?? '' ?></span>

        <input type="text" class="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>">
        <span class="error"><?= $errors['email'] ?? '' ?></span>

        <textarea class="message" name="message" placeholder="Your Message"><?= htmlspecialchars($message) ?></textarea>
        <span class="error mt-2"><?= $errors['message'] ?? '' ?></span>

        <button type="submit" name="submit" class="primary">Submit</button>

        <?php if (!empty($success)) : ?>
            <p class="success mt-2"><?= $success ?></p>
        <?php endif; ?>
    </form>
</section>






<?php
include('./include/footer.php');
?>

 