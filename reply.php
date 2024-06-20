<?php
// Initialize the session
session_start();

// Include database connection
require_once "config.php";

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit();
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate comment ID and reply content
    $comment_id = $_POST["comment_id"];
    $reply_content = trim($_POST["reply"]);

    // Prepare SQL statement to insert the reply into the database
    $sql = "INSERT INTO comment_replies (comment_id, user_id, content) VALUES (?, ?, ?)";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "iis", $comment_id, $_SESSION["id"], $reply_content);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Reply successfully added
            $_SESSION["message"] = "Your reply was posted successfully.";
            $_SESSION["message_type"] = "success";
        } else {
            // Error occurred while adding reply
            $_SESSION["message"] = "Oops! Something went wrong. Please try again later.";
            $_SESSION["message_type"] = "danger";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        // Error preparing the SQL statement
        $_SESSION["message"] = "Oops! Something went wrong. Please try again later.";
        $_SESSION["message_type"] = "danger";
    }

    // Redirect back to the post details page
    header("location: post_details.php?post_id=" . $_POST["post_id"]);
    exit();
} else {
    // If the form data is not submitted, redirect to the index page
    header("location: welcome.php");
    exit();
}

// Close database connection
mysqli_close($conn);
?>
