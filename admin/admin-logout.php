<?php
session_start();

// Only unset admin-related session vars
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_id']);
unset($_SESSION['admin_role']);

// Keep user session alive
header("Location: ../login.php");
exit();
?>
