<?php
include('./include/config.php');
include('./include/header.php');
include('./include/navbar.php');

if(isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT * FROM products WHERE id = '$id'";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $name = $row['name'];
  $image = $row['image'];
  $price = $row['price'];
  $description = $row['description'];
  $stock = $row['stock'];
  $category = $row['category'];
  // $testimonial = $row['testimonial'];
}

?>

<div class="breadcrump">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active text-white" aria-current="page">Product</li>
      </ol>
    </nav>
  </div>
</div>

<div class="row w-100 product mt-5">

  <div class="col-md-6 d-flex justify-content-center">
    <img src="./uploads/<?= $image; ?>" alt="" class="img-fluid" height="400px" width="400px">
  </div>

 <div class="col-md-6">
    <div class="product">
        <h1 class="product-title text-white"><?= $name; ?></h1>
        <p class="product-price text-white">Rs. <?= number_format($price); ?></p>
        <p class="product-description text-white"><?= $description; ?></p>

        <!-- Single Form -->
        <form action="./cart.php" method="POST">
            <input type="hidden" name="id" value="<?= $id; ?>">
            <input type="hidden" name="name" value="<?= $name; ?>">
            <input type="hidden" name="image" value="<?= $image; ?>">
            <input type="hidden" name="price" value="<?= $price; ?>">
            <input type="hidden" name="description" value="<?= $description; ?>">
            <input type="hidden" name="stock" value="<?= $stock; ?>">
            <input type="hidden" name="category" value="<?= $category; ?>">

            <div class="sizes mb-3">
                <label>
                    <input type="radio" name="size" value="Small" required> Small
                </label>
                <label>
                    <input type="radio" name="size" value="Medium"> Medium
                </label>
                <label>
                    <input type="radio" name="size" value="Large"> Large
                </label>
            </div>

            <!-- Add to Cart button -->
            <button type="submit" name="add_to_cart" class="btn primary mt-3 text-white">
                Add to Cart
            </button>
        </form>

        <!-- Optional info -->
        <p class="product-category text-white">Category: <?= $category; ?></p>
        <!-- <p class="product-stock text-white">Stock: <?= $stock; ?></p> -->
    </div>
</div>


</div>


<section class="rating-review">
      <h2 class="section-title">Customer Reviews for Noir Muse</h2>

      <div class="review-grid">
        <!-- Review 1 -->
        <div class="review-card">
          <div class="review-header">
            <h3><?php echo $name?></h3>
            <div class="stars">★★★★☆</div>
          </div>
          <p class="review-text">"Elegant and bold. Loved the material and finish!"</p>
          <div class="review-author">— Aisha K., <span class="date">June 2025</span></div>
        </div>

        <!-- Review 2 -->
        <div class="review-card">
          <div class="review-header">
            <h3><?php echo $name?></h3>
            <div class="stars">★★★★★</div>
          </div>
          <p class="review-text">"Perfect for a night out. The details are stunning!"</p>
          <div class="review-author">— Riya M., <span class="date">May 2025</span></div>
        </div>

        <!-- Review 3 -->
        <div class="review-card">
          <div class="review-header">
            <h3><?php echo $name?></h3>
            <div class="stars">★★★★☆</div>
          </div>
          <p class="review-text">"Received so many compliments! Premium feel."</p>
          <div class="review-author">— Sneha J., <span class="date">April 2025</span></div>
        </div>

        <!-- Review 4 -->
        <div class="review-card">
          <div class="review-header">
            <h3><?php echo $name?></h3>
            <div class="stars">★★★☆☆</div>
          </div>
          <p class="review-text">"Nice but delivery was delayed. Product is good."</p>
          <div class="review-author">— Divya S., <span class="date">March 2025</span></div>
        </div>
      </div>
    </section>




<?php
include('./include/footer.php');
?>
