<?php
include('../include/config.php');
include('../include/header.php');
include('./admin-nav.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}



?>