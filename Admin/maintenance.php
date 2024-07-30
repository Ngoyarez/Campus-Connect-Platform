<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin") {
    header("location: ../login.php");
    exit();
}

if (isset($_POST['schedule'])) {
    $task = $_POST['task'];
    $date = $_POST['date'];

    $sql = "INSERT INTO maintenance_tasks (task, date) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $task, $date);
    mysqli_stmt_execute($stmt);
    echo "Task scheduled.";
}

$sql = "SELECT * FROM maintenance_tasks ORDER BY date ASC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Schedule Maintenance</title>
</head>
<body>
    <h1>Schedule Maintenance Tasks</h1>
    <form method="post">
        <label>Task</label>
        <input type="text" name="task"><br>
        <label>Date</label>
        <input type="date" name="date"><br>
        <input type="submit" name="schedule" value="Schedule">
    </form>

    <h2>Scheduled Tasks</h2>
    <table border="1">
        <tr>
            <th>Task</th>
            <th>Date</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['task']; ?></td>
                <td><?php echo $row['date']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>