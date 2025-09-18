<?php


include('../include/config.php');
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $sql = "DELETE FROM products WHERE id = $product_id";
    $result = $conn->query($sql);
    header("Location: stocks.php");
    exit;
}

include('../include/header.php');
include('./admin-nav.php');
?>