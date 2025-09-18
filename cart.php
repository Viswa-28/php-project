<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include('./include/config.php');
include('./include/header.php');
include('./include/navbar.php');

if (isset($_POST['add_to_cart'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $image = $_POST['image'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $size = $_POST['size'];
}
$discount = 500;
?>
<div class="breadcrump">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="product.php?id=<?php echo $id; ?>" class="text-decoration-none">Product</a></li>
        <li class="breadcrumb-item active text-white" aria-current="page">Cart</li>
      </ol>
    </nav>
  </div>
</div>

<div class="container mt-5">
    <h1 class="text-center text-white mb-5">Cart</h1>
    <div class="row g-4 flex-wrap">
        <!-- Cart Item -->
        <div class="col-12 col-md-8 d-flex flex-column flex-md-row gap-3 cart-item">
            <img src="./uploads/<?php echo $image; ?>" 
                 alt="<?php echo $name; ?>" 
                 class="object-fit-cover img-fluid w-100 w-md-50" style="max-width:500px; max-height:400px;">
            <!-- Product Details -->
            <div class="content d-flex flex-column gap-2 flex-grow-1">
                <h2 class="product-title text-white"><?php echo $name; ?></h2>
                <p class="text-white">Price: ₹<?php echo number_format($price); ?></p>
                <p class="product-description text-white"><?php echo $description; ?></p>
                <p class="product-size text-white">Size: <?php echo $size; ?></p>
                <p class="product-category text-white">Category: <?php echo $category; ?></p>
                <!-- Quantity Selector -->
                <div class="quantity-container d-flex align-items-center justify-content-center gap-3 mt-2">
                    <button type="button" class="qty-btn minus">-</button>
                    <div class="qty-num text-white">1</div>
                    <button type="button" class="qty-btn plus">+</button>
                </div>
            </div>
        </div>
        <!-- Order Summary -->
        <div class="col-12 col-md-4">
            <div class="order-summary bg-dark text-white p-4 rounded">
                <h2>Order Summary</h2>

                <div class="summary-row d-flex justify-content-between">
                    <p>Subtotal</p>
                    <p class="subtotal">₹ <?php echo number_format($price); ?></p>
                </div>

                <div class="summary-row d-flex justify-content-between">
                    <p>Shipping</p>
                    <p>Free</p>
                </div>

                <div class="summary-row d-flex justify-content-between">
                    <p>Discount</p>
                    <p>- ₹ <?php echo number_format($discount); ?></p>
                </div>

                <hr>

                <div class="summary-row d-flex justify-content-between total">
                    <p>Total</p>
                    <p class="total-val">₹ <?php echo number_format($price - $discount); ?></p>
                </div>

                <!-- Checkout Form -->
                <form action="checkout.php" method="post" class="mt-3">
                    <!-- Dynamic values -->
                    <input type="hidden" name="qty" id="qtyInput" value="1">
                    <input type="hidden" name="subtotal" id="subtotalInput" value="<?php echo $price; ?>">
                    <input type="hidden" name="total" id="totalInput" value="<?php echo $price - $discount; ?>">

                    <!-- Product details -->
                    <input type="hidden" name="name" value="<?php echo $name; ?>">
                    <input type="hidden" name="image" value="<?php echo $image; ?>">
                    <input type="hidden" name="price" value="<?php echo $price; ?>">
                    <input type="hidden" name="description" value="<?php echo $description; ?>">
                    <input type="hidden" name="stock" value="<?php echo $stock; ?>">
                    <input type="hidden" name="category" value="<?php echo $category; ?>">
                    <input type="hidden" name="size" value="<?php echo $size; ?>">

                    <button class="primary-btn checkout-btn btn primary text-white mt-3 w-100" 
                            type="submit" name="checkout">
                        Checkout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>





<script>
const cartItem = document.querySelector('.cart-item');
const minusBtn = cartItem.querySelector('.minus');
const plusBtn = cartItem.querySelector('.plus');
const qtyNum = cartItem.querySelector('.qty-num');

const price = <?php echo $price; ?>;
const discount = <?php echo $discount; ?>;

const subtotalEl = document.querySelector('.subtotal');
const totalEl = document.querySelector('.total-val');

const qtyInput = document.getElementById('qtyInput');
const subtotalInput = document.getElementById('subtotalInput');
const totalInput = document.getElementById('totalInput');

function updateTotals(qty) {
    const subtotal = price * qty;
    const total = subtotal - discount;

    // Update UI
    subtotalEl.textContent = '₹ ' + subtotal.toLocaleString();
    totalEl.textContent = '₹ ' + total.toLocaleString();

    // Update form hidden inputs
    qtyInput.value = qty;
    subtotalInput.value = subtotal;
    totalInput.value = total;
}

plusBtn.addEventListener('click', () => {
    let qty = parseInt(qtyNum.textContent);
    qty++;
    qtyNum.textContent = qty;
    updateTotals(qty);
});

minusBtn.addEventListener('click', () => {
    let qty = parseInt(qtyNum.textContent);
    if (qty > 1) {
        qty--;
        qtyNum.textContent = qty;
        updateTotals(qty);
    }
});
</script>
