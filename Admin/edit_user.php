<?php
session_start();
require_once "../config.php";

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedinadmin']) && $_SESSION['role'] != 'admin') {
    header("location: ../login.php");
    exit;
}

// Fetch user details
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "SELECT id, username, email, role, active FROM users WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $username, $email, $role, $active);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Update user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $is_active = isset($_POST['active']) ? 1 : 0;

    $sql = "UPDATE users SET username = ?, email = ?, role = ?, active = ? WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssii", $username, $email, $role, $active, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("location: users.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit User</h1>
        <form action="edit_user.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" class="form-control" required>
                    <option value="user" <?php if ($role == 'user') echo 'selected'; ?>>User</option>
                    <option value="admin" <?php if ($role == 'admin') echo 'selected'; ?>>Admin</option>
                </select>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" name="active" class="form-check-input" <?php if ($active) echo 'checked'; ?>>
                <label class="form-check-label" for="active">Active</label>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="users.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
