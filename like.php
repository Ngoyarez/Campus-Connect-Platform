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

    // Check if the user already liked the post
    $sql_check = "SELECT * FROM post_likes WHERE post_id = ? AND user_id = ?";
    if ($stmt_check = mysqli_prepare($conn, $sql_check)) {
        mysqli_stmt_bind_param($stmt_check, "ii", $post_id, $user_id);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            // User has already liked the post
            $_SESSION['message'] = "You have already liked this post.";
            $_SESSION['message_type'] = "success";
        } else {
            // User has not liked the post, proceed to like it
            $sql_like = "INSERT INTO post_likes (post_id, user_id) VALUES (?, ?)";
            if ($stmt_like = mysqli_prepare($conn, $sql_like)) {
                mysqli_stmt_bind_param($stmt_like, "ii", $post_id, $user_id);
                if (mysqli_stmt_execute($stmt_like)) {
                    // Increment the like count in the posts table
                    $sql_update_post = "UPDATE posts SET likes = likes + 1 WHERE id = ?";
                    if ($stmt_update_post = mysqli_prepare($conn, $sql_update_post)) {
                        mysqli_stmt_bind_param($stmt_update_post, "i", $post_id);
                        mysqli_stmt_execute($stmt_update_post);
                        mysqli_stmt_close($stmt_update_post);
                    }
                    $_SESSION['message'] = "You liked this post.";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Oops! Something went wrong. Please try again later.";
                    $_SESSION['message_type'] = "danger";
                }
                mysqli_stmt_close($stmt_like);
            }
        }
        mysqli_stmt_close($stmt_check);
    } else {
        $_SESSION['message'] = "Oops! Something went wrong. Please try again later.";
        $_SESSION['message_type'] = "danger";
    }

    // Redirect back to the referring page after liking
    $referer = $_SERVER['HTTP_REFERER'];
    if ($referer && strpos($referer, './post_details.php') !== false) {
        // Redirect back to the post details page if the user came from there
        header("location: $referer");
    } else {
        // Redirect to the home page if no referrer or if referrer is not post_details.php
        header("location: welcome.php");
    }
    exit();
} else {
    // Redirect to home page if no post ID is provided
    header("location: welcome.php");
    exit();
}

// Close database connection
mysqli_close($conn);
?>
