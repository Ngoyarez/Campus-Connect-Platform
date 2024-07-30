<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedinadmin']) && $_SESSION['role'] != 'admin') {
    header("location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
    /* Additional styling for the admin dashboard */
</style>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="./users.php">Users</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_posts.php">Posts</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_comments.php">Comments</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_categories.php">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_tags.php">Tags</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_settings.php">Settings</a></li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Welcome, Admin</h1>
        <p>Use the navigation bar to manage different aspects of the site.</p>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
