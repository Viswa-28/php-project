<?php
include('../include/config.php');


$error = '';
$success = '';

// Get product ID
if (!isset($_GET['product_id'])) {
    header("Location: stocks.php");
    exit;
}
$product_id = $_GET['product_id'];

// Fetch product data
$result = $conn->query("SELECT * FROM products WHERE id = $product_id");
$product = $result->fetch_assoc();

if (!$product) {
    echo "<p class='text-center text-white mt-4'>Product not found.</p>";
    exit;
}

// Handle form submission
if (isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    // $testimonial = $_POST['testimonial'];?

    // Keep current image
    $image_name = $product['image'];

    // If new image uploaded
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_folder = '../uploads/' . $image_name;

        if (!move_uploaded_file($image_tmp, $image_folder)) {
            $error = "Failed to upload image.";
        }
    }

    if (!$error) {
        $sql = "UPDATE products SET 
            name='$name',
            category='$category',
            description='$description',
            price='$price',
            stock='$stock',
            testimonial='$testimonial',
            image='$image_name'
            WHERE id = $product_id";

        if ($conn->query($sql)) {
            $success = "Product updated successfully!";
            // Refresh product data
            $result = $conn->query("SELECT * FROM products WHERE id = $product_id");
            $product = $result->fetch_assoc();
            header("Location: stocks.php");
        
            // At the very top, before including HTML/header files
if (!isset($_GET['product_id'])) {
    header("Location: stocks.php");
    exit;
}

        } else {
            $error = "Database error: " . $conn->error;
        }
    }
}
include('../include/header.php');
include('./admin-nav.php');
?>

<div class="container mt-5">
    <h2 class="sub text-center mb-4">Edit Product</h2>

    <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if ($success) echo "<div class='alert alert-success'>$success</div>"; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" required value="<?php echo $product['name']; ?>">
        </div>

        <div class="mb-3">
            <label>Category</label>
            <select name="category" class="form-control" required>
                <option value="Men" <?php if($product['category']=='Men') echo 'selected'; ?>>Men</option>
                <option value="Women" <?php if($product['category']=='Women') echo 'selected'; ?>>Women</option>
                <option value="Accessory" <?php if($product['category']=='Accessory') echo 'selected'; ?>>Accessory</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3" required><?php echo $product['description']; ?></textarea>
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control" required value="<?php echo $product['price']; ?>">
        </div>

        <div class="mb-3">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" min="0" required value="<?php echo $product['stock']; ?>">
        </div>

        <!-- <div class="mb-3">
            <label>Testimonial</label>
            <textarea name="testimonial" class="form-control" rows="2"><?php echo $product['testimonial']; ?></textarea>
        </div> -->

        <div class="mb-3">
            <label>Current Image</label><br>
            <img src="../uploads/<?php echo $product['image']; ?>" width="120" class="mb-2" alt="Current Image">
            <input type="file" name="image" class="form-control">
            <small class="text-muted">Leave blank to keep existing image.</small>
        </div>

        <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
    </form>
</div>
