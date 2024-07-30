<?php
session_start();
require_once "../config.php";

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedinadmin']) && $_SESSION['role'] != 'admin') {
    header("location: ../login.php");
    exit;
}

// Fetch all users
$sql = "SELECT id, username, email, role, active FROM users";
$result = mysqli_query($conn, $sql);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <?php include 'sidebar.php'; ?>
        </div>
        
        <div class="content">
            <div class="container mt-5">
                <form class="form-inline mx-auto" action="search_user.php" method="get" style="display: flex; align-items: center;">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search users" aria-label="Search" name="query" style="width: 300px; font-size: 20px;">
                    <select class="form-control mr-sm-2" name="filter" style="font-size: 20px;">
                        <!-- <option value="title">Title</option> -->
                        <!-- <option value="user">User</option> -->
                        <option value="status">Status</option>
                        <option value="school">School</option>
                    </select>
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" style="font-size: 20px;">Search</button>
                </form>
                <h1>Manage Users</h1>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                <td><?php echo $user['active'] ? 'Active' : 'Inactive'; ?></td>
                                <td>
                                    <a href="./edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="./delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                    <a href="./toggle_user.php?id=<?php echo $user['id']; ?>" class="btn btn-secondary btn-sm"><?php echo $user['active'] ? 'Deactivate' : 'Activate'; ?></a>
                                    <a href="reset_user_password.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">Reset Password</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
</body>
</html>
