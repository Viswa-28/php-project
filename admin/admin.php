<?php


session_start();

if (!isset($_SESSION['role']) || 
    ($_SESSION['role'] !== 'super_admin' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../login.php');
    exit;
}
include('../include/config.php');
include('../include/header.php');
include('./admin-nav.php');

$totalSalesQuery = "SELECT SUM(price) AS total_sales FROM checkout";
$totalSalesResult = $conn->query($totalSalesQuery);
$totalSales = $totalSalesResult->fetch_assoc()['total_sales'] ?? 0;

$today = date("Y-m-d");
$todaySalesQuery = "SELECT SUM(price) AS today_sales FROM checkout WHERE DATE(created_at) = '$today'";
$todaySalesResult = $conn->query($todaySalesQuery);
$todaySales = $todaySalesResult->fetch_assoc()['today_sales'] ?? 0;

$orderCountQuery = "SELECT COUNT(*) AS total_orders FROM checkout";
$orderCountResult = $conn->query($orderCountQuery);
$orderCount = $orderCountResult->fetch_assoc()['total_orders'] ?? 0;

$sql = "SELECT * FROM checkout ORDER BY created_at DESC LIMIT 3";
$result = $conn->query($sql);
?>

<div class="container mt-5">

    <div class="row text-center mb-4">
        <div class="col-md-4">
            <div class="card shadow-lg primary text-white p-3 rounded">
                <h4>Total Sales</h4>
                <h2>₹<?= number_format($totalSales, 2); ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-lg bg-info text-white p-3 rounded">
                <h4>Today's Sales</h4>
                <h2>₹<?= number_format($todaySales, 2); ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-lg bg-warning text-white p-3 rounded">
                <h4>Orders Placed</h4>
                <h2><?= $orderCount; ?></h2>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <h1 class="text-center mb-4 text-white">Checkout Orders</h1>
    <div class="table-responsive">
        <table class="table table-striped table-bordered text-white">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Name</th> 
                    <th>Product Image</th>
              
                    <th>Price (₹)</th>
                    <th>Description</th>
                    <!-- <th>Stock</th> -->
                    <th>Category</th>
                    <th>Size</th>
                    <th>Ordered At</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
                    $i=1
                ?>
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $row['name']; ?></td>
                            <td>
                                <img src="../uploads/<?= $row['image']; ?>" alt="<?= $row['name']; ?>" style="width:80px; height:80px; object-fit:cover; border-radius:5px;">
                            </td>
                            

                            <td><?= number_format($row['price'],2); ?></td>
                            <td><?= $row['description']; ?></td>
                            <!-- <td><?= $row['stock']; ?></td> -->
                            <td><?= $row['category']; ?></td>
                            <td><?= $row['size']; ?></td>
                            <td><?= $row['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>

</style>
