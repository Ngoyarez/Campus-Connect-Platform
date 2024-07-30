<?php
// Initialize the session
session_start();

// Include database connection
require_once "../config.php";

// Check if query is provided
if (isset($_GET['query']) && !empty($_GET['query']) && isset($_GET['filter'])) {
    $query = "%" . $_GET['query'] . "%";
    $filter = $_GET['filter'];

    switch ($filter) {
        // case 'title':
        //     $sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, users.username, users.school AS school FROM posts 
        //             JOIN users ON posts.user_id = users.id
        //             -- JOIN schools ON users.school_id = schools.id
        //             WHERE posts.title LIKE ?";
        //     break;
        // case 'user':
        //     $sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, users.username, users.school AS school FROM posts 
        //             JOIN users ON posts.user_id = users.id
        //             -- JOIN schools ON users.school_id = schools.id
        //             WHERE users.username LIKE ?";
        //     break;
        case 'status':
            $sql = "SELECT id, username, email, role, active FROM users
                    WHERE active LIKE ?";
            break;
        case 'school':
            $sql = "SELECT users.id, users.username, users.email, users.role, users.active FROM users
                    WHERE users.school LIKE ?";
            break;
        default:
            $sql = "";
            break;
    }

    if ($sql) {
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $query);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $user_id, $username, $email, $role, $status);
            $results = [];
            while (mysqli_stmt_fetch($stmt)) {
                $results[] = [
                    'id' => $user_id,
                    'username' => $username,
                    'email' => $email,
                    'role' => $role,
                    'active' => $status,
                ];
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
} else {
    // Redirect to home page if no query is provided
    header("location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Search Results</h2>
        <?php if (!empty($results)): ?>
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
                        <?php foreach ($results as $user): ?>
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
        <?php else: ?>
            <p>No results found for your search query.</p>
        <?php endif; ?>
    </div>
</body>
</html>
