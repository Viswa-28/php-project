<?php
session_start();
include('./include/config.php');
include('./include/header.php');
include('./include/navbar.php');

// ✅ Update quantity
if (isset($_POST['update'])) {
    foreach ($_POST['qty'] as $index => $qty) {
        // Store as 'quantity' for checkout consistency
        $_SESSION['cart'][$index]['quantity'] = max(1, (int)$qty);
    }
}

// ✅ Remove item
if (isset($_GET['remove'])) {
    $removeIndex = $_GET['remove'];
    unset($_SESSION['cart'][$removeIndex]);
    $_SESSION['cart'] = array_values($_SESSION['cart']); // reindex
}

// ✅ Checkout
if (isset($_POST['checkout'])) {
    if (!empty($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        $date = date('Y-m-d H:i:s'); // current date/time

        foreach ($cart as $item) {
            $name = $item['name'];
            $image = $item['image'];
            $size = $item['size'];
            $quantity = isset($item['quantity']) ? $item['quantity'] : 1; // default 1
            $price_each = $item['price'];
            $total_price = $price_each * $quantity;

            // Insert into sales table (change created_at if your column is different)
            $sql = "INSERT INTO sales (name, image, size, quantity, price_each, total_price, created_at) 
                    VALUES ('$name', '$image', '$size', '$quantity', '$price_each', '$total_price', '$date')";

            if (!$conn->query($sql)) {
                die("Error: " . $conn->error);
            }
        }

        // Clear cart
        unset($_SESSION['cart']);
        echo "<script>alert('Order placed successfully!'); window.location.href='index.php';</script>";
        exit;
    }
}
?>

<style>
.cart-img { width: 100px; height: 100px; object-fit: cover; }
.cart-card { background: #1a1a1a; color: #fff; border-radius: 12px; }
.cart-summary { background: #242424; color: #fff; border-radius: 12px; }
.btn.primary { background-color: #7C3AED; color: #fff; border: none; }
.btn.primary:hover { background-color: #5b27c7; color: #fff; }
</style>

<div class="container my-5">
    <h2 class="mb-4 text-white">Your Shopping Cart</h2>

    <?php if (!empty($_SESSION['cart'])): ?>
        
        <!-- Cart Update Form -->
        <form method="post" action="cart.php">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card cart-card p-3 shadow-sm">
                        <table class="table table-dark table-borderless align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Size</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $subtotal = 0;
                                foreach ($_SESSION['cart'] as $index => $item):
                                    // Use quantity for consistency
                                    $qty = isset($item['quantity']) ? $item['quantity'] : 1;
                                    $total = $item['price'] * $qty;
                                    $subtotal += $total;
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="./uploads/<?php echo $item['image']; ?>" class="cart-img rounded me-3" alt="">
                                                <div>
                                                    <h6 class="mb-1"><?php echo $item['name']; ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo $item['size']; ?></td>
                                        <td>Rs. <?php echo number_format($item['price']); ?></td>
                                        <td>
                                            <input type="number" 
                                                   name="qty[<?php echo $index; ?>]" 
                                                   value="<?php echo $qty; ?>" 
                                                   min="1" 
                                                   class="form-control form-control-sm w-50">
                                        </td>
                                        <td>Rs. <?php echo number_format($total); ?></td>
                                        <td>
                                            <a href="cart.php?remove=<?php echo $index; ?>" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="text-end mt-3">
                            <button type="submit" name="update" class="btn btn-outline-light">
                                <i class="bi bi-arrow-repeat me-2"></i> Update Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Cart Summary & Checkout -->
        <div class="row mt-4">
            <div class="col-lg-4 offset-lg-8">
                <div class="card cart-summary p-4 shadow-sm">
                    <h5>Order Summary</h5>
                    <hr>
                    <p class="d-flex justify-content-between">
                        <span>Subtotal</span>
                        <strong>Rs. <?php echo number_format($subtotal); ?></strong>
                    </p>
                    <p class="d-flex justify-content-between">
                        <span>Tax (5%)</span>
                        <strong>Rs. <?php echo number_format($subtotal * 0.05); ?></strong>
                    </p>
                    <hr>
                    <p class="d-flex justify-content-between fs-5">
                        <span>Total</span>
                        <strong>Rs. <?php echo number_format($subtotal * 1.05); ?></strong>
                    </p>

                    <!-- Checkout Button -->
                    <form action="cart.php" method="post">
                        <button type="submit" name="checkout" class="btn primary w-100 py-2 mt-3">
                            <i class="bi bi-credit-card me-2"></i> Proceed to Checkout
                        </button>
                    </form>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-warning">
            Your cart is empty. 
            <a href="index.php" class="alert-link">Continue Shopping</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
