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

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM users WHERE id = '$id'";
    $result = $conn->query($sql);
    header('Location:./users.php');
    exit;
}

?>

<h2 class="sub text-center mt-5 mb-4">Delete User</h2>

<div class="container detele mt-5">
    <form action="" method="POST">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <p class="text-white">Are you sure you want to delete this user?</p>
        <button type="submit" name="delete" class="btn btn-danger">Delete</button>
    </form>
</div>