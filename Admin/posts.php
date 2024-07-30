<?php
// Include database connection
include_once "../config.php";

// Check if user is logged in as admin
// Include session_start() at the beginning of your config.php or in this file if not already included
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin") {
    header("location: login.php");
    exit();
}

// CRUD operations for posts can be implemented here

// Example: Retrieve all posts from the database
$sql = "SELECT * FROM posts";
$result = mysqli_query($conn, $sql);

// Example: Display posts in a table
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Posts</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <?php include 'sidebar.php'; ?>
        </div>

        <div class="content">
            <div class="container mt-5">
                <h2>Admin - Manage Posts</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['title']; ?></td>
                                <?php $content = $row['content']; ?>
                                <?php $status = $row['approved']; ?>
                                <td><?php echo strlen($content) > 100 ? substr($content, 0, 100) . '...' : $content; ?></td>
                                <td><?php echo $status ?></td>
                                <td>
                                    <a href="edit_post.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a>
                                    <a href="delete_post.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                                    <a href="approve_post.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Approve</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="add_post.php" class="btn btn-success">Add New Post</a>
            </div>
        </div>

    </div>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
