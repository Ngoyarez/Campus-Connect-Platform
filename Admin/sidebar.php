<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Sidebar styling */
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #f8f9fa;
            padding-top: 50px;
            transition: margin-left 0.5s;
        }

        .sidebar a {
            padding: 10px;
            text-decoration: none;
            color: #343a40;
            display: block;
        }

        .sidebar a:hover {
            background-color: #e9ecef;
        }

        /* Toggle button styling */
        .toggle-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div id="sidebar" class="sidebar">
    <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</a>
    <a href="./users.php"><i class="fas fa-users"></i> Users</a>
    <a href="posts.php"><i class="fas fa-file-alt"></i> Posts</a>
    <a href="comments.php"><i class="fas fa-comments"></i> Comments</a>
    <a href="categories.php"><i class="fas fa-folder"></i> Categories</a>
    <a href="messages.php"><i class="fas fa-envelope"></i> Messages</a>
    <a href="tags.php"><i class="fas fa-tags"></i> Tags</a>
    <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
    <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
