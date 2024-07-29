<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit();
}

if (isset($_POST['comment_id']) && !empty($_POST['comment_id'])) {
    $comment_id = $_POST['comment_id'];
    $user_id = $_SESSION["id"];

    $sql_check = "SELECT * FROM comment_likes WHERE comment_id = ? AND user_id = ?";
    if ($stmt_check = mysqli_prepare($conn, $sql_check)) {
        mysqli_stmt_bind_param($stmt_check, "ii", $comment_id, $user_id);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $_SESSION['message'] = "You have already liked this comment.";
            $_SESSION['message_type'] = "success";
        } else {
            $sql_like = "INSERT INTO comment_likes (comment_id, user_id) VALUES (?, ?)";
            if ($stmt_like = mysqli_prepare($conn, $sql_like)) {
                mysqli_stmt_bind_param($stmt_like, "ii", $comment_id, $user_id);
                if (mysqli_stmt_execute($stmt_like)) {
                    $_SESSION['message'] = "You liked this comment.";
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

    header("location: post_details.php?post_id=" . $_POST['post_id']);
    exit();
} else {
    header("location: index.php");
    exit();
}

mysqli_close($conn);
?>
