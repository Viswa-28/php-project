<?php
// session_start();
include('../include/config.php');
include('../include/header.php');


$error = '';
$success = '';

// Handle form submission
if(isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $category = $_POST['category'];
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    // $testimonial = trim($_POST['testimonial']);

    // Check for required fields
    if(empty($name) || empty($category) || empty($description) || empty($price) || empty($stock)) {
        $error = "Please fill in all required fields.";
    } elseif(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        // Handle image upload
        $image_name = time().'_'.basename($_FILES['image']['name']);
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_folder = '../uploads/'.$image_name;

        if(move_uploaded_file($image_tmp, $image_folder)) {
            // Prevent duplicate submission on refresh
            if(!isset($_SESSION['last_insert']) || $_SESSION['last_insert'] != $name) {
                $sql = "INSERT INTO products (name, category, image, description, price, stock, testimonial)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssdis", $name, $category, $image_name, $description, $price, $stock, $testimonial);

                if($stmt->execute()) {
                    $success = "Product added successfully!";
                    $_SESSION['last_insert'] = $name; // prevent duplicate insert
                    header("Location: stocks.php");
                } else {
                    $error = "Database error: " . $stmt->error;
                }
            } else {
                $error = "This product was already added.";
            }
        } else {
            $error = "Failed to upload image.";
        }
    } else {
        $error = "Please upload a valid image.";
    }
include('./admin-nav.php');

}
?>

<div class="container mt-5">
    <h2>Add New Product</h2>

    <?php if($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="mt-3">
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select name="category" id="category" class="form-select" required>
                <option value="">Select Category</option>
                <option value="Men">Men</option>
                <option value="Women">Women</option>
                <option value="Accessory">Accessory</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" name="stock" id="stock" class="form-control" min="0" required>
        </div>

        <!-- <div class="mb-3">
            <label for="testimonial" class="form-label">Testimonial</label>
            <textarea name="testimonial" id="testimonial" class="form-control" rows="2"></textarea>
        </div> -->

        <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
    </form>
</div>
