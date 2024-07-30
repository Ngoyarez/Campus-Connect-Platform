<?php
session_start();
require_once "../config.php";

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) && $_SESSION['role'] != 'admin') {
    header("location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Toggle user status
    $sql = "UPDATE users SET active = 0 WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    header("location: users.php");
    exit;
}
?>
