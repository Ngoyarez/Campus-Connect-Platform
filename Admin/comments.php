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

// Function to fetch all comments
function fetch_comments($conn) {
    $sql = "SELECT comments.id, comments.comment, comments.created_at, users.username, posts.title 
            FROM comments 
            JOIN users ON comments.user_id = users.id 
            JOIN posts ON comments.post_id = posts.id 
            ORDER BY comments.created_at DESC";
    $result = mysqli_query($conn, $sql);
    return $result;
}

// Fetch comments
$comments = fetch_comments($conn);

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Comments</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <?php include 'sidebar.php'; ?>
        </div>


        <div class="content">
            <h2>Manage Comments</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Content</th>
                        <th>Author</th>
                        <th>Post</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($comment = mysqli_fetch_assoc($comments)) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($comment['id']); ?></td>
                            <td><?php echo htmlspecialchars($comment['comment']); ?></td>
                            <td><?php echo htmlspecialchars($comment['username']); ?></td>
                            <td><?php echo htmlspecialchars($comment['title']); ?></td>
                            <td><?php echo htmlspecialchars($comment['created_at']); ?></td>
                            <td>
                                <a href="edit_comment.php?id=<?php echo $comment['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_comment.php?id=<?php echo $comment['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this comment?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
