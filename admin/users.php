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

$sql = "SELECT * FROM users ORDER BY id DESC LIMIT 10";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo '<h2 class="sub text-center mt-5 mb-4">Users</h2>';
    echo '<div class="container">';
    echo '<div class="table-responsive">';
    echo '<table class="table table-striped table-bordered  table-bordered text-white mt-4 mb-4 text-center">';
    echo '<thead class="table-dark">';
    echo '<tr>';
    echo '<th class="text-start">ID</th>';
    echo '<th class="text-start">Role</th>';
    echo '<th class="text-start">Email</th>';
    
    // Show password column only if logged-in user is super_admin
    if ($_SESSION['role'] === 'super_admin') {
        echo '<th class="text-start">Password</th>';
        // echo '<th>edit</th>';
        echo '<th class="text-start">delete</th>';
    }

    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    $i = 1;

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td class="text-start">' . $i++ . '</td>';
        echo '<td class="text-start">' . htmlspecialchars($row['role']) . '</td>';
        echo '<td class="text-start">' . htmlspecialchars($row['email']) . '</td>';

        // Show password only for super_admin logged-in user, otherwise mask or hide
        if ($_SESSION['role'] === 'super_admin') {
            echo '<td class="text-start">' . htmlspecialchars($row['password']) . '</td>';
            // echo '<td><a href="edit-users.php?id=' . $row['id'] . '" class="btn btn-primary">Edit</a></td>';
            echo '<td class="text-start"><a href="delete-users.php?id=' . $row['id'] . '" class="btn btn-danger">Delete</a></td>';
        }

        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>'; // table-responsive
    echo '</div>'; // container
} else {
    echo '<p class="text-center text-white mt-4">No users found.</p>';
}
?>
