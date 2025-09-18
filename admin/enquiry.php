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

$sql="SELECT * FROM contacts ORDER BY id DESC LIMIT 10";
$result=$conn->query($sql);

if($result && $result->num_rows > 0){
    echo '<h2 class="sub text-center mt-5 mb-4">Enquiries</h2>';
    echo '<div class="container">';
    echo '<div class="table-responsive">';
    echo '<table class="table table-striped table-bordered text-white">';
    echo '<thead class="table-dark">';
    echo '<tr>';
    echo '<th class="text-start">ID</th>';
    echo '<th class="text-start">Name</th>';
    echo '<th class="text-start">Email</th>';
    echo '<th class="text-start">Message</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    $i=1;
    while($row=$result->fetch_assoc()){
        echo '<tr>';
        echo '<td>' . $i++ . '</td>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['email'] . '</td>';
        echo '<td>' . $row['message'] . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    echo '</div>';
}else{
    echo '<p class="text-center text-white mt-4">No enquiries found.</p>';
}





?>
