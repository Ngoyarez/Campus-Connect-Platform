<?php
require_once "../config.php";
// session_start();
// if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
//     header("location: ../login.php");
//     exit();
// }

// Example: Displaying number of users, posts, and comments
$user_count = mysqli_query($conn, "SELECT COUNT(*) AS count FROM users");
$post_count = mysqli_query($conn, "SELECT COUNT(*) AS count FROM posts");
$comment_count = mysqli_query($conn, "SELECT COUNT(*) AS count FROM comments");

$user_count = mysqli_fetch_assoc($user_count)['count'];
$post_count = mysqli_fetch_assoc($post_count)['count'];
$comment_count = mysqli_fetch_assoc($comment_count)['count'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Site Analytics</title>
</head>
<body>
<div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text"><?php echo $user_count; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Posts</h5>
                    <p class="card-text"><?php echo $post_count; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Comments</h5>
                    <p class="card-text"><?php echo $comment_count; ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>