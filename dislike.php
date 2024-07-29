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

// Check if post ID is provided
if (isset($_GET['post_id']) && !empty($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
    $user_id = $_SESSION["id"];

    // Check if the user already disliked the post
    $sql_check = "SELECT * FROM post_dislikes WHERE post_id = ? AND user_id = ?";
    if ($stmt_check = mysqli_prepare($conn, $sql_check)) {
        mysqli_stmt_bind_param($stmt_check, "ii", $post_id, $user_id);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            // User has already disliked the post
            $_SESSION['message'] = "You have already disliked this post.";
            $_SESSION['message_type'] = "danger";
        } else {
            // User has not disliked the post, proceed to dislike it
            $sql_dislike = "INSERT INTO post_dislikes (post_id, user_id) VALUES (?, ?)";
            if ($stmt_dislike = mysqli_prepare($conn, $sql_dislike)) {
                mysqli_stmt_bind_param($stmt_dislike, "ii", $post_id, $user_id);
                if (mysqli_stmt_execute($stmt_dislike)) {
                    // Increment the dislike count in the posts table
                    $sql_update_post = "UPDATE posts SET dislikes = dislikes + 1 WHERE id = ?";
                    if ($stmt_update_post = mysqli_prepare($conn, $sql_update_post)) {
                        mysqli_stmt_bind_param($stmt_update_post, "i", $post_id);
                        mysqli_stmt_execute($stmt_update_post);
                        mysqli_stmt_close($stmt_update_post);
                    }
                    $_SESSION['message'] = "You disliked this post.";
                    $_SESSION['message_type'] = "danger";
                } else {
                    $_SESSION['message'] = "Oops! Something went wrong. Please try again later.";
                    $_SESSION['message_type'] = "danger";
                }
                mysqli_stmt_close($stmt_dislike);
            }
        }
        mysqli_stmt_close($stmt_check);
    } else {
        $_SESSION['message'] = "Oops! Something went wrong. Please try again later.";
        $_SESSION['message_type'] = "danger";
    }

    // Redirect back to the post details page after disliking
    header("location: post_details.php?post_id=$post_id");
    exit();
} else {
    // Redirect to home page if no post ID is provided
    header("location: welcome.php");
    exit();
}

// Close database connection
mysqli_close($conn);
?>
