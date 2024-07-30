<?php
session_start();
require_once "../config.php";

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedinadmin']) && $_SESSION['role'] != 'admin') {
    header("location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete user
    $sql = "DELETE FROM users WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    header("location: admin_users.php");
    exit;
}
?>
