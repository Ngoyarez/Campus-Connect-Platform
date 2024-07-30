<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
    header("location: ../login.php");
    exit();
}

// Example: Generating a report of user activity in the last 30 days
$report = [];
$sql = "SELECT username, action, COUNT(*) as count FROM user_activity WHERE timestamp > NOW() - INTERVAL 30 DAY GROUP BY username, action";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $report[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Generate Report</title>
</head>
<body>
    <h1>User Activity Report (Last 30 Days)</h1>
    <table border="1">
        <tr>
            <th>Username</th>
            <th>Action</th>
            <th>Count</th>
        </tr>
        <?php foreach($report as $row): ?>
            <tr>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['action']; ?></td>
                <td><?php echo $row['count']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
