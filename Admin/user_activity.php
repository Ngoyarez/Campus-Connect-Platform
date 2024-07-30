<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
    header("location: ../login.php");
    exit();
}

$sql = "SELECT * FROM user_activity ORDER BY timestamp DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Activity Logs</title>
</head>
<body>
    <h1>User Activity Logs</h1>
    <table border="1">
        <tr>
            <th>User</th>
            <th>Action</th>
            <th>Timestamp</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['action']; ?></td>
                <td><?php echo $row['timestamp']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>