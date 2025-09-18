<?php
session_start();

// Only unset user-related session vars
unset($_SESSION['user_logged_in']);
unset($_SESSION['user_id']);
unset($_SESSION['username']);

header("Location: ./login.php");
exit();
?>
