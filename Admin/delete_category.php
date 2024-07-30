<?php
// Initialize the session
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin") {
    header("location: ../login.php");
    exit;
}

// Include config file
require_once "../config.php";

// Check if the ID parameter is set
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $category_id = $_GET['id'];

    // Prepare a delete statement
    $sql = "DELETE FROM categories WHERE id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = $category_id;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to categories page
            header("location: categories.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($conn);
} else {
    // Redirect to categories page if no ID is provided
    header("location: categories.php");
    exit();
}
?>
